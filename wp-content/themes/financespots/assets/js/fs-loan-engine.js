/**
 * FinanceSpots loan engine: amortization with extra payments, side costs
 * (PMI / MIP), interest-only periods, true APR, schedule rendering, CSV.
 * Pure vanilla JS. Exposes window.FSL.
 */
(function (w) {
  'use strict';
  var FSL = {};

  /* ── helpers ── */
  FSL.el = function (id) { return document.getElementById(id); };
  FSL.num = function (id, def) {
    var e = FSL.el(id);
    if (!e) return def || 0;
    var v = parseFloat(String(e.value).replace(/,/g, ''));
    return isNaN(v) ? (def || 0) : v;
  };
  FSL.money = function (v, d) {
    if (!isFinite(v)) return '--';
    d = d === undefined ? 0 : d;
    var s = Math.abs(v).toLocaleString('en-US', { minimumFractionDigits: d, maximumFractionDigits: d });
    return (v < 0 ? '-$' : '$') + s;
  };
  FSL.pct = function (v, d) { return isFinite(v) ? v.toFixed(d === undefined ? 2 : d) + '%' : '--'; };
  FSL.set = function (id, text) { var e = FSL.el(id); if (e) e.textContent = text; };
  FSL.show = function (id, display) { var e = FSL.el(id); if (e) e.style.display = display || 'block'; };
  FSL.monthsText = function (m) {
    var y = Math.floor(m / 12), mo = m % 12, out = [];
    if (y) out.push(y + (y === 1 ? ' year' : ' years'));
    if (mo || !y) out.push(mo + (mo === 1 ? ' month' : ' months'));
    return out.join(' ');
  };
  FSL.dateText = function (d) { return d.toLocaleDateString('en-US', { month: 'short', year: 'numeric' }); };
  FSL.startDate = function (id) {
    var e = FSL.el(id), d;
    if (e && e.value) { var p = e.value.split('-'); d = new Date(+p[0], +p[1] - 1, 1); }
    else { d = new Date(); d = new Date(d.getFullYear(), d.getMonth() + 1, 1); }
    return d;
  };
  FSL.addMonths = function (d, m) { return new Date(d.getFullYear(), d.getMonth() + m, 1); };
  FSL.defaultStartValue = function () {
    var d = new Date(); d = new Date(d.getFullYear(), d.getMonth() + 1, 1);
    return d.getFullYear() + '-' + ('0' + (d.getMonth() + 1)).slice(-2);
  };

  /* Level payment for principal P, monthly rate r, n months */
  FSL.pmt = function (P, r, n) {
    if (n <= 0) return P;
    return r === 0 ? P / n : (P * r) / (1 - Math.pow(1 + r, -n));
  };

  /**
   * Amortization schedule.
   * o.P, o.apr (percent), o.n (months)
   * o.payment  optional fixed scheduled payment (P&I); default level payment
   * o.extra    extra monthly principal
   * o.yearlyExtra {month:1-12 (calendar), amount}
   * o.lumps    [{m: monthNumber, amount}]
   * o.ioMonths interest-only months at the start
   * o.paymentFn optional function(monthNumber) -> scheduled P&I for that month (graduated plans)
   * o.side     function(monthNumber, balanceBefore, rowSoFar) -> extra monthly cost (PMI/MIP), not applied to principal
   * o.start    Date of first payment
   * o.maxMonths safety cap
   */
  FSL.schedule = function (o) {
    var r = (o.apr || 0) / 1200, bal = o.P, rows = [];
    var pay = o.payment || FSL.pmt(o.P, r, o.n - (o.ioMonths || 0));
    var start = o.start || FSL.startDate();
    var cap = o.maxMonths || 1200;
    var tInt = 0, tPaid = 0, tSide = 0, tExtra = 0;
    var lumps = {};
    (o.lumps || []).forEach(function (l) { if (l.m > 0 && l.amount > 0) lumps[l.m] = (lumps[l.m] || 0) + l.amount; });
    for (var m = 1; m <= cap && bal > 0.005; m++) {
      var date = FSL.addMonths(start, m - 1);
      var interest = bal * r, principal, sched;
      if (o.ioMonths && m <= o.ioMonths) { principal = 0; sched = interest; }
      else { sched = o.paymentFn ? o.paymentFn(m) : pay; principal = Math.max(sched - interest, 0); }
      var extra = (o.extra || 0) + (lumps[m] || 0);
      if (o.yearlyExtra && o.yearlyExtra.amount > 0 && date.getMonth() + 1 === o.yearlyExtra.month) extra += o.yearlyExtra.amount;
      if (principal + extra > bal) { extra = Math.max(bal - principal, 0); if (principal > bal) principal = bal; }
      var princTotal = principal + extra;
      var side = o.side ? (o.side(m, bal, rows) || 0) : 0;
      var out = interest + princTotal;
      bal = Math.max(bal - princTotal, 0);
      tInt += interest; tPaid += out; tSide += side; tExtra += extra;
      rows.push({ m: m, date: date, pay: out, princ: princTotal, int: interest, extra: extra, side: side, bal: bal });
      if (!o.ioMonths && !o.paymentFn && pay <= interest + 1e-9 && !extra && m > o.n) break; /* never amortizes */
    }
    return {
      rows: rows, months: rows.length, payment: pay, totalInterest: tInt, totalPaid: tPaid,
      totalSide: tSide, totalExtra: tExtra, endBalance: bal,
      payoffDate: rows.length ? rows[rows.length - 1].date : start
    };
  };

  /** Annual (calendar-year) summary of a schedule. */
  FSL.annual = function (rows) {
    var map = {}, list = [];
    rows.forEach(function (r) {
      var y = r.date.getFullYear();
      if (!map[y]) { map[y] = { year: y, pay: 0, princ: 0, int: 0, side: 0, bal: 0 }; list.push(map[y]); }
      var a = map[y]; a.pay += r.pay; a.princ += r.princ; a.int += r.int; a.side += r.side; a.bal = r.bal;
    });
    return list;
  };

  /**
   * True APR: monthly rate i such that PV(payments) = netProceeds, returned as nominal % (x12).
   * payments: array of monthly payments (index 0 = month 1).
   */
  FSL.apr = function (net, payments) {
    if (net <= 0 || !payments.length) return NaN;
    var total = payments.reduce(function (a, b) { return a + b; }, 0);
    if (total <= net) return 0;
    var lo = 0, hi = 1;
    function pv(i) { var s = 0, f = 1; for (var k = 0; k < payments.length; k++) { f /= (1 + i); s += payments[k] * f; } return s; }
    for (var it = 0; it < 120; it++) {
      var mid = (lo + hi) / 2;
      if (pv(mid) > net) lo = mid; else hi = mid;
    }
    return ((lo + hi) / 2) * 1200;
  };

  /* ── schedule rendering (monthly / annual toggle + CSV) ── */
  FSL.renderSchedule = function (containerId, sched, opts) {
    opts = opts || {};
    var box = FSL.el(containerId);
    if (!box) return;
    var hasSide = sched.totalSide > 0, sideLabel = opts.sideLabel || 'PMI';
    var state = { mode: 'monthly' };
    box.style.display = 'block';
    box.innerHTML =
      '<div class="fsl-sched-head"><h3 class="fsl-h3">Amortization schedule</h3>' +
      '<div class="fsl-sched-actions">' +
      '<div class="fsl-toggle" role="group" aria-label="Schedule view">' +
      '<button type="button" data-mode="monthly" class="is-active">Monthly</button>' +
      '<button type="button" data-mode="annual">Annual</button></div>' +
      '<button type="button" class="fsl-csv">Download CSV</button></div></div>' +
      '<div class="fsc-table-wrap fsl-sched-wrap"><table class="fsc-table fsl-sched" id="' + containerId + '-table">' +
      '<thead></thead><tbody></tbody></table></div>';
    var thead = box.querySelector('thead'), tbody = box.querySelector('tbody');
    function f(v) { return FSL.money(v, 2); }
    function draw() {
      var h, b = '';
      if (state.mode === 'monthly') {
        h = '<tr><th>#</th><th>Date</th><th>Payment</th><th>Principal</th><th>Interest</th>' +
          (hasSide ? '<th>' + sideLabel + '</th>' : '') + '<th>Balance</th></tr>';
        sched.rows.forEach(function (r) {
          b += '<tr><td>' + r.m + '</td><td>' + FSL.dateText(r.date) + '</td><td>' + f(r.pay) + '</td><td>' + f(r.princ) +
            '</td><td>' + f(r.int) + '</td>' + (hasSide ? '<td>' + f(r.side) + '</td>' : '') + '<td>' + f(r.bal) + '</td></tr>';
        });
      } else {
        h = '<tr><th>Year</th><th>Paid</th><th>Principal</th><th>Interest</th>' +
          (hasSide ? '<th>' + sideLabel + '</th>' : '') + '<th>End balance</th></tr>';
        FSL.annual(sched.rows).forEach(function (a) {
          b += '<tr><td>' + a.year + '</td><td>' + f(a.pay) + '</td><td>' + f(a.princ) + '</td><td>' + f(a.int) + '</td>' +
            (hasSide ? '<td>' + f(a.side) + '</td>' : '') + '<td>' + f(a.bal) + '</td></tr>';
        });
      }
      thead.innerHTML = h; tbody.innerHTML = b;
    }
    box.querySelectorAll('.fsl-toggle button').forEach(function (btn) {
      btn.addEventListener('click', function () {
        state.mode = btn.getAttribute('data-mode');
        box.querySelectorAll('.fsl-toggle button').forEach(function (x) { x.classList.toggle('is-active', x === btn); });
        draw();
      });
    });
    box.querySelector('.fsl-csv').addEventListener('click', function () {
      var lines = [['Payment #', 'Date', 'Payment', 'Principal', 'Interest'].concat(hasSide ? [sideLabel] : []).concat(['Balance']).join(',')];
      sched.rows.forEach(function (r) {
        lines.push([r.m, r.date.getFullYear() + '-' + ('0' + (r.date.getMonth() + 1)).slice(-2), r.pay.toFixed(2), r.princ.toFixed(2), r.int.toFixed(2)]
          .concat(hasSide ? [r.side.toFixed(2)] : []).concat([r.bal.toFixed(2)]).join(','));
      });
      var blob = new Blob([lines.join('\n')], { type: 'text/csv' });
      var a = document.createElement('a');
      a.href = URL.createObjectURL(blob); a.download = (opts.filename || 'amortization-schedule') + '.csv';
      document.body.appendChild(a); a.click(); document.body.removeChild(a);
      setTimeout(function () { URL.revokeObjectURL(a.href); }, 500);
    });
    draw();
  };

  /* ── charts (Chart.js is loaded on tool pages; degrade silently) ── */
  FSL._charts = {};
  FSL.chart = function (canvasId, config) {
    var c = FSL.el(canvasId);
    if (!c || typeof w.Chart === 'undefined') return;
    if (FSL._charts[canvasId]) FSL._charts[canvasId].destroy();
    FSL._charts[canvasId] = new w.Chart(c.getContext('2d'), config);
  };
  FSL.balanceChart = function (canvasId, labelsFrom, series) {
    var step = Math.max(1, Math.ceil(series[0].rows.length / 60));
    var labels = [];
    var longest = series.reduce(function (a, s) { return s.rows.length > a ? s.rows.length : a; }, 0);
    for (var m = 0; m <= longest; m += step) labels.push(m === 0 ? 'Start' : 'Yr ' + (m / 12).toFixed(m % 12 ? 1 : 0));
    var colors = ['#3B82F6', '#00C896', '#F59E0B'];
    var datasets = series.map(function (s, i) {
      var data = [];
      for (var m = 0; m <= longest; m += step) {
        data.push(m === 0 ? s.start : (m > s.rows.length ? 0 : s.rows[m - 1].bal));
      }
      return { label: s.label, data: data, borderColor: colors[i % 3], backgroundColor: colors[i % 3] + '22', fill: i === 0, tension: .25, pointRadius: 0 };
    });
    FSL.chart(canvasId, {
      type: 'line', data: { labels: labels, datasets: datasets },
      options: {
        plugins: { legend: { display: series.length > 1 } },
        scales: { y: { ticks: { callback: function (v) { return '$' + Math.round(v / 1000) + 'k'; } } } }
      }
    });
  };

  /* Build a simple <select>-less validity guard: returns true when inputs look usable */
  FSL.valid = function (cond, msgId, msg) {
    var m = FSL.el(msgId);
    if (m) { m.textContent = cond ? '' : msg; m.style.display = cond ? 'none' : 'block'; }
    return cond;
  };

  /* Recalculate (debounced) whenever an input inside rootId changes */
  FSL.bind = function (rootId, fn) {
    var r = FSL.el(rootId), t;
    if (!r) return;
    r.addEventListener('input', function () { clearTimeout(t); t = setTimeout(fn, 120); });
    r.addEventListener('change', function () { clearTimeout(t); t = setTimeout(fn, 60); });
  };

  /* Run fn once the DOM is ready and on the first paint of results */
  FSL.ready = function (fn) {
    if (document.readyState !== 'loading') fn(); else document.addEventListener('DOMContentLoaded', fn);
  };

  w.FSL = FSL;
})(window);
