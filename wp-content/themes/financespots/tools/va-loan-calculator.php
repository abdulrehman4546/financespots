<?php
// Allow both standalone access and WordPress include
$is_wp = defined('ABSPATH');
if ( ! $is_wp ) {
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>VA Loan Funding Fee Calculator 2026 | Free Military Tool | FinanceSpots</title>
<meta name="description" content="Free VA loan funding fee calculator 2026. Calculate your VA funding fee, monthly PITI payment, amortization schedule, and compare VA vs conventional loan savings.">
<meta name="robots" content="index, follow">
<link rel="canonical" href="https://financespots.com/tools/va-loan-funding-fee-calculator/">
<?php } ?>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<!-- jsPDF + AutoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<style>
:root{
  --green:#00C896;--green-d:#009970;--blue:#3B82F6;--gold:#F59E0B;
  --red:#EF4444;--purple:#8B5CF6;--dark:#0A0F1E;--card:#fff;
  --border:#E2E8F0;--text:#1E293B;--muted:#64748B;--bg:#F0F4F8;
  --radius:14px;
}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:var(--bg);color:var(--text)}

/* ── Hero ── */
.hero{background:linear-gradient(135deg,#06102b 0%,#0b2218 60%,#06102b 100%);padding:3.5rem 1.5rem 4.5rem;text-align:center;position:relative;overflow:hidden}
.hero::after{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 50% 100%,rgba(0,200,150,.12),transparent)}
.hero-badge{display:inline-flex;align-items:center;gap:.45rem;background:rgba(0,200,150,.15);border:1px solid rgba(0,200,150,.3);color:var(--green);padding:.3rem .9rem;border-radius:999px;font-size:.75rem;font-weight:800;letter-spacing:.08em;margin-bottom:1.1rem}
.hero h1{font-size:clamp(1.9rem,4.5vw,3rem);font-weight:900;color:#fff;line-height:1.15;margin-bottom:.7rem}
.hero h1 em{color:var(--green);font-style:normal}
.hero p{color:rgba(255,255,255,.55);font-size:.97rem;max-width:540px;margin:0 auto 2rem}
.hero-kpis{display:flex;justify-content:center;gap:3rem;flex-wrap:wrap}
.kpi strong{display:block;font-size:1.6rem;font-weight:900;color:var(--green);line-height:1}
.kpi span{font-size:.72rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.07em;margin-top:.25rem;display:block}

/* ── Tabs ── */
.tabs-bar{background:#fff;border-bottom:2px solid var(--border);position:sticky;top:0;z-index:100;box-shadow:0 2px 16px rgba(0,0,0,.06)}
.tabs{display:flex;max-width:1120px;margin:0 auto;overflow-x:auto;scrollbar-width:none;padding:0 1rem}
.tabs::-webkit-scrollbar{display:none}
.tab{flex-shrink:0;padding:.85rem 1.2rem;font-size:.82rem;font-weight:700;color:var(--muted);border:none;border-bottom:3px solid transparent;cursor:pointer;background:none;transition:all .2s;white-space:nowrap}
.tab.on{color:var(--dark);border-bottom-color:var(--green)}
.tab:hover:not(.on){color:#334155}

/* ── Layout ── */
.wrap{max-width:1120px;margin:0 auto;padding:2rem 1.5rem 5rem}
.panel{display:none}.panel.on{display:block}
.grid2{display:grid;grid-template-columns:1fr 400px;gap:1.5rem;align-items:start}
@media(max-width:880px){.grid2{grid-template-columns:1fr}}

/* ── Cards ── */
.c{background:#fff;border:1px solid var(--border);border-radius:var(--radius);padding:1.5rem;margin-bottom:1.25rem;box-shadow:0 1px 6px rgba(0,0,0,.04)}
.c-dark{background:#0d1b3e;border:1px solid rgba(255,255,255,.08);border-radius:var(--radius);padding:1.5rem;color:#E2E8F0;margin-bottom:1.25rem}
.c-title{font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:var(--muted);margin-bottom:1.1rem;display:flex;align-items:center;gap:.5rem}

/* ── Form ── */
.row2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem}
.row3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem}
@media(max-width:600px){.row2,.row3{grid-template-columns:1fr}}
.fg{display:flex;flex-direction:column;gap:.35rem}
label{font-size:.72rem;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:.05em}
.iw{position:relative}
.iw .pre{position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:#94A3B8;font-weight:700;font-size:.88rem;pointer-events:none}
.iw .suf{position:absolute;right:.7rem;top:50%;transform:translateY(-50%);color:#94A3B8;font-weight:700;font-size:.88rem;pointer-events:none}
input[type=number],input[type=text],select{width:100%;padding:.6rem .75rem;border:1.5px solid #CBD5E1;border-radius:8px;font-size:.9rem;color:var(--text);background:#F8FAFC;outline:none;transition:border .2s,box-shadow .2s;-moz-appearance:textfield}
input::-webkit-inner-spin-button,input::-webkit-outer-spin-button{-webkit-appearance:none}
input:focus,select:focus{border-color:var(--green);box-shadow:0 0 0 3px rgba(0,200,150,.14);background:#fff}
.pl input{padding-left:1.75rem}
.pr input{padding-right:2rem}
select{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 5 5-5z' fill='%2394A3B8'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right .75rem center;padding-right:2rem}

/* Toggle */
.tg{display:flex;gap:.4rem;flex-wrap:wrap}
.tg-btn{padding:.4rem .85rem;border:1.5px solid #CBD5E1;border-radius:7px;font-size:.78rem;font-weight:700;color:var(--muted);cursor:pointer;background:#F8FAFC;transition:all .18s;white-space:nowrap}
.tg-btn.on{background:var(--green);border-color:var(--green);color:#fff}

/* Slider */
.sl-wrap{margin-top:.3rem}
input[type=range]{width:100%;height:4px;accent-color:var(--green);cursor:pointer}
.sl-lbl{display:flex;justify-content:space-between;font-size:.68rem;color:var(--muted);margin-top:.2rem}

/* Buttons */
.btn-go{width:100%;padding:.85rem;background:linear-gradient(135deg,var(--green),var(--green-d));color:#fff;font-size:.97rem;font-weight:800;border:none;border-radius:10px;cursor:pointer;transition:transform .15s,box-shadow .15s;box-shadow:0 4px 20px rgba(0,200,150,.3);letter-spacing:.02em}
.btn-go:hover{transform:translateY(-2px);box-shadow:0 6px 28px rgba(0,200,150,.45)}
.btn-go:active{transform:none}
.btn-pdf{display:flex;align-items:center;justify-content:center;gap:.5rem;width:100%;padding:.7rem;background:#0d1b3e;color:#fff;font-size:.85rem;font-weight:700;border:none;border-radius:9px;cursor:pointer;margin-top:.75rem;transition:background .2s}
.btn-pdf:hover{background:#162540}
.btn-reset{width:100%;padding:.55rem;background:none;color:var(--muted);font-size:.82rem;font-weight:600;border:1.5px solid var(--border);border-radius:9px;cursor:pointer;margin-top:.5rem;transition:all .2s}
.btn-reset:hover{border-color:#94A3B8;color:#334155}

/* ── Result display ── */
.rhero{background:linear-gradient(135deg,#0A1628,#0b2218);border-radius:12px;padding:1.6rem;text-align:center;margin-bottom:1.1rem}
.rhero .rlabel{font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35);margin-bottom:.3rem}
.rhero .rval{font-size:2.8rem;font-weight:900;color:var(--green);letter-spacing:-.03em;line-height:1}
.rhero .rsub{font-size:.78rem;color:rgba(255,255,255,.4);margin-top:.35rem}
.rrow{display:flex;justify-content:space-between;align-items:center;padding:.62rem 0;border-bottom:1px solid rgba(255,255,255,.06)}
.rrow:last-child{border:0}
.rk{font-size:.8rem;color:rgba(255,255,255,.5)}
.rv{font-size:.85rem;font-weight:700;color:#E2E8F0}
.rv.g{color:var(--green)}.rv.r{color:var(--red)}.rv.gold{color:var(--gold)}
.exempt-strip{background:rgba(0,200,150,.12);border:1px solid rgba(0,200,150,.3);border-radius:8px;padding:.65rem 1rem;font-size:.82rem;font-weight:700;color:var(--green);text-align:center;margin:.75rem 0}

/* Bar */
.bbar{height:8px;border-radius:999px;overflow:hidden;background:#E2E8F0;display:flex;margin:.5rem 0}
.bseg{height:100%;transition:width .5s ease}
.bleg{display:grid;grid-template-columns:1fr 1fr;gap:.35rem .75rem;margin-top:.6rem}
.bleg-i{display:flex;align-items:center;gap:.4rem;font-size:.72rem;color:var(--muted)}
.bdot{width:9px;height:9px;border-radius:50%;flex-shrink:0}

/* Charts */
.chart-box{position:relative;height:200px;margin:1rem 0}

/* ── Comparison table ── */
.tbl{width:100%;border-collapse:collapse;font-size:.82rem}
.tbl th{background:#F1F5F9;color:#475569;font-weight:800;padding:.6rem .85rem;text-align:right;border:1px solid var(--border);font-size:.72rem;text-transform:uppercase;letter-spacing:.05em}
.tbl th:first-child{text-align:left}
.tbl td{padding:.58rem .85rem;border:1px solid var(--border);text-align:right;color:#334155;vertical-align:middle}
.tbl td:first-child{text-align:left;font-weight:700;color:#1E293B}
.tbl tr.best td{background:rgba(0,200,150,.06)}
.tbl tr:hover td{background:#F8FAFC}
.pill{display:inline-block;background:#EEF2FF;color:#4F46E5;padding:.1rem .4rem;border-radius:4px;font-weight:800;font-size:.75rem}
.best-tag{background:rgba(0,200,150,.15);color:var(--green);border-radius:4px;padding:.05rem .4rem;font-size:.7rem;font-weight:800;margin-left:.3rem}

/* Amort */
.amort-wrap{max-height:440px;overflow-y:auto;border-radius:var(--radius);border:1px solid var(--border)}
.amort-tbl{width:100%;border-collapse:collapse;font-size:.8rem}
.amort-tbl thead{position:sticky;top:0;z-index:10}
.amort-tbl th{background:#0A0F1E;color:var(--green);padding:.55rem .8rem;text-align:right;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap}
.amort-tbl th:first-child{text-align:left}
.amort-tbl td{padding:.48rem .8rem;text-align:right;border-bottom:1px solid #F1F5F9}
.amort-tbl td:first-child{text-align:left;font-weight:700;color:#1E293B}
.amort-tbl tr:hover td{background:#F8FAFC}

/* VA vs Conv */
.vs-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem}
@media(max-width:640px){.vs-grid{grid-template-columns:1fr}}
.vs-card{border-radius:var(--radius);padding:1.4rem;border:2px solid var(--border)}
.vs-card.va{border-color:rgba(0,200,150,.35);background:rgba(0,200,150,.04)}
.vs-card.conv{border-color:rgba(59,130,246,.3);background:rgba(59,130,246,.04)}
.vs-title{font-size:.78rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.85rem}
.vs-title.va{color:var(--green)}.vs-title.conv{color:var(--blue)}
.vs-row{display:flex;justify-content:space-between;padding:.4rem 0;border-bottom:1px solid rgba(0,0,0,.06);font-size:.83rem}
.vs-row:last-child{border:0}
.vs-row .vk{color:var(--muted)}.vs-row .vv{font-weight:700}
.winner-banner{background:linear-gradient(135deg,rgba(0,200,150,.15),rgba(0,153,112,.1));border:1.5px solid rgba(0,200,150,.4);border-radius:12px;padding:1.1rem 1.4rem;display:flex;align-items:center;gap:1rem;margin-bottom:1.25rem}

/* Guide */
.guide-tbl{width:100%;border-collapse:collapse;font-size:.82rem}
.guide-tbl th{background:#0A0F1E;color:var(--green);padding:.65rem .9rem;text-align:left;font-size:.72rem;text-transform:uppercase;letter-spacing:.06em}
.guide-tbl td{padding:.6rem .9rem;border-bottom:1px solid var(--border)}
.guide-tbl tr:hover td{background:#F8FAFC}

/* Tips */
.tip{border-radius:10px;padding:.9rem 1.1rem;margin:.65rem 0;display:flex;gap:.7rem;align-items:flex-start;font-size:.84rem}
.tip.g{background:rgba(0,200,150,.07);border:1px solid rgba(0,200,150,.2)}
.tip.b{background:rgba(59,130,246,.07);border:1px solid rgba(59,130,246,.2)}
.tip.y{background:rgba(245,158,11,.07);border:1px solid rgba(245,158,11,.22)}
.tip.r{background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.2)}
.tip-i{font-size:1rem;flex-shrink:0;margin-top:.1rem}

/* Exempt banner */
.exempt-banner{background:linear-gradient(135deg,rgba(0,200,150,.13),rgba(59,130,246,.08));border:1.5px solid rgba(0,200,150,.35);border-radius:12px;padding:1.1rem 1.4rem;display:flex;gap:1rem;align-items:flex-start;margin:1rem 0}
.eb-icon{font-size:2rem;flex-shrink:0}
.eb-title{font-size:.9rem;font-weight:800;color:#0F172A;margin-bottom:.2rem}
.eb-desc{font-size:.82rem;color:#475569}

/* Sec header */
.sh{margin-bottom:1.4rem}
.sh h2{font-size:1.3rem;font-weight:900}
.sh p{font-size:.87rem;color:var(--muted);margin-top:.25rem}

@media print{.tabs-bar,.tab,.btn-go,.btn-reset,.hero{display:none!important}}
</style>
</head>
<body>

<!-- ── Hero ── -->
<div class="hero">
  <div class="hero-badge">&#127894;&#65039; MILITARY BENEFIT CALCULATOR</div>
  <h1>VA Loan <em>Funding Fee</em><br>Calculator 2026</h1>
  <p>Instant funding fee, full PITI payment, amortization schedule, VA vs Conventional comparison — all with PDF export.</p>
  <div class="hero-kpis">
    <div class="kpi"><strong>0%</strong><span>Down Payment</span></div>
    <div class="kpi"><strong>$0</strong><span>PMI Required</span></div>
    <div class="kpi"><strong>2.15%</strong><span>Min Fee Rate</span></div>
    <div class="kpi"><strong>100%</strong><span>Free Tool</span></div>
  </div>
</div>

<!-- ── Tabs ── -->
<div class="tabs-bar">
  <div class="tabs">
    <button class="tab on" onclick="goTab('quick',this)">&#9889; Quick Calc</button>
    <button class="tab" onclick="goTab('piti',this)">&#127968; Full PITI</button>
    <button class="tab" onclick="goTab('compare',this)">&#9878;&#65039; Scenarios</button>
    <button class="tab" onclick="goTab('vsconv',this)">&#128202; VA vs Conv</button>
    <button class="tab" onclick="goTab('amort',this)">&#128197; Amortization</button>
    <button class="tab" onclick="goTab('guide',this)">&#128214; Fee Guide</button>
  </div>
</div>

<div class="wrap">

<!-- ══════════════════════
     TAB 1 — QUICK CALC
══════════════════════ -->
<div class="panel on" id="p-quick">
<div class="grid2">
<div>
  <div class="sh"><h2>Quick VA Funding Fee Estimate</h2><p>Instant results as you type — no button needed.</p></div>

  <div class="c">
    <div class="c-title"><span>&#127968;</span> Loan Details</div>
    <div class="row2">
      <div class="fg"><label>Purchase Price</label><div class="iw pl"><span class="pre">$</span><input type="number" id="q_price" value="350000" oninput="Q()"></div></div>
      <div class="fg">
        <label>Down Payment</label>
        <div class="iw pr"><input type="number" id="q_down" value="0" min="0" max="99" step=".5" oninput="syncR();Q()"><span class="suf">%</span></div>
        <div class="sl-wrap">
          <input type="range" id="q_dr" min="0" max="25" step=".5" value="0" oninput="document.getElementById('q_down').value=this.value;Q()">
          <div class="sl-lbl"><span>0%</span><span>5%</span><span>10%</span><span>25%</span></div>
        </div>
      </div>
    </div>
    <div class="row2">
      <div class="fg"><label>Loan Type</label>
        <select id="q_type" onchange="Q()">
          <option value="p1">Purchase — 1st Time</option>
          <option value="ps">Purchase — Subsequent</option>
          <option value="c1">Cash-Out Refi — 1st</option>
          <option value="cs">Cash-Out Refi — Sub</option>
          <option value="ir">IRRRL / Streamline</option>
          <option value="as">Loan Assumption</option>
        </select>
      </div>
      <div class="fg"><label>Military Category</label>
        <select id="q_mil" onchange="Q()">
          <option value="r">Regular Military</option>
          <option value="g">Reserves / Nat'l Guard</option>
        </select>
      </div>
    </div>
    <div class="row2">
      <div class="fg"><label>Interest Rate</label><div class="iw pr"><input type="number" id="q_rate" value="6.75" step=".05" oninput="Q()"><span class="suf">%</span></div></div>
      <div class="fg"><label>Loan Term</label>
        <div class="tg" id="q_term">
          <button class="tg-btn" onclick="tg('q_term',this,'15');Q()">15 yr</button>
          <button class="tg-btn on" onclick="tg('q_term',this,'30');Q()">30 yr</button>
        </div>
      </div>
    </div>
    <div class="fg" style="margin-bottom:1rem">
      <label>Disability / Exemption Status</label>
      <div class="tg" id="q_dis">
        <button class="tg-btn on" onclick="tg('q_dis',this,'no');Q()">None</button>
        <button class="tg-btn" onclick="tg('q_dis',this,'ex');Q()">10%+ Disabled (Exempt)</button>
        <button class="tg-btn" onclick="tg('q_dis',this,'sp');Q()">Surviving Spouse</button>
      </div>
    </div>
    <button class="btn-go" onclick="Q()">Calculate ↗</button>
    <button class="btn-reset" onclick="resetQ()">↺ Reset</button>
  </div>

  <div id="q_exempt_box" style="display:none">
    <div class="exempt-banner">
      <div class="eb-icon">&#127894;&#65039;</div>
      <div><div class="eb-title">You Are EXEMPT from the VA Funding Fee!</div>
      <div class="eb-desc">Veterans with 10%+ service-connected disability rating & surviving spouses pay $0 funding fee — saving thousands at closing.</div></div>
    </div>
  </div>

  <div class="tip b"><span class="tip-i">&#128161;</span><span><strong>5% Down Savings:</strong> Moving from 0%→5% down cuts your fee from 2.15%→1.50% — saving <strong id="q_tip_save">$2,275</strong> on a $350k loan.</span></div>
</div>

<!-- Results -->
<div>
  <div class="c-dark">
    <div class="rhero">
      <div class="rlabel">VA Funding Fee</div>
      <div class="rval" id="q_fee_disp">$7,525</div>
      <div class="rsub" id="q_fee_pct_disp">2.15% of base loan</div>
    </div>
    <div class="rrow"><span class="rk">Purchase Price</span><span class="rv" id="q_r1">$350,000</span></div>
    <div class="rrow"><span class="rk">Down Payment</span><span class="rv" id="q_r2">$0 (0%)</span></div>
    <div class="rrow"><span class="rk">Base Loan Amount</span><span class="rv" id="q_r3">$350,000</span></div>
    <div class="rrow"><span class="rk">+ Funding Fee</span><span class="rv gold" id="q_r4">$7,525</span></div>
    <div class="rrow" style="border-top:1px solid rgba(255,255,255,.15);padding-top:.8rem;margin-top:.3rem">
      <span class="rk" style="color:#fff;font-weight:800;font-size:.88rem">Total Loan Amount</span>
      <span class="rv g" style="font-size:1.05rem" id="q_r5">$357,525</span>
    </div>
    <div class="rrow"><span class="rk">Monthly Payment (P+I)</span><span class="rv g" id="q_r6">$2,319/mo</span></div>
    <div class="rrow"><span class="rk">Total Interest (lifetime)</span><span class="rv r" id="q_r7">$476,726</span></div>
    <div class="rrow"><span class="rk">Total Cost</span><span class="rv" id="q_r8">$834,251</span></div>

    <div style="margin-top:1.1rem">
      <div class="c-title" style="color:rgba(255,255,255,.35)"><span>&#128202;</span> Cost Breakdown</div>
      <div class="bbar">
        <div class="bseg" id="bb1" style="background:#00C896;width:96%"></div>
        <div class="bseg" id="bb2" style="background:#F59E0B;width:2%"></div>
        <div class="bseg" id="bb3" style="background:#3B82F6;width:2%"></div>
      </div>
      <div class="bleg">
        <div class="bleg-i"><div class="bdot" style="background:#00C896"></div>Principal</div>
        <div class="bleg-i"><div class="bdot" style="background:#F59E0B"></div>Funding Fee</div>
        <div class="bleg-i"><div class="bdot" style="background:#3B82F6"></div>Down Payment</div>
        <div class="bleg-i"><div class="bdot" style="background:#EF4444"></div>Interest</div>
      </div>
    </div>

    <!-- Payment chart -->
    <div style="margin-top:1.1rem">
      <div class="c-title" style="color:rgba(255,255,255,.35)"><span>&#128200;</span> Balance Over Time</div>
      <div class="chart-box"><canvas id="q_chart"></canvas></div>
    </div>

    <button class="btn-pdf" onclick="pdfQuick()">&#128196; Download PDF Report</button>
  </div>
</div>
</div>
</div>

<!-- ══════════════════════
     TAB 2 — FULL PITI
══════════════════════ -->
<div class="panel" id="p-piti">
<div class="grid2">
<div>
  <div class="sh"><h2>Full Monthly Payment (PITI)</h2><p>Taxes, insurance, HOA included for true cost of ownership.</p></div>
  <div class="c">
    <div class="c-title"><span>&#127968;</span> Loan</div>
    <div class="row2">
      <div class="fg"><label>Purchase Price</label><div class="iw pl"><span class="pre">$</span><input type="number" id="p_price" value="350000" oninput="P()"></div></div>
      <div class="fg"><label>Down Payment %</label><div class="iw pr"><input type="number" id="p_down" value="0" oninput="P()"><span class="suf">%</span></div></div>
    </div>
    <div class="row2">
      <div class="fg"><label>Interest Rate</label><div class="iw pr"><input type="number" id="p_rate" value="6.75" step=".05" oninput="P()"><span class="suf">%</span></div></div>
      <div class="fg"><label>Loan Term</label><select id="p_term" onchange="P()"><option value="30">30 Years</option><option value="20">20 Years</option><option value="15">15 Years</option></select></div>
    </div>
    <div class="row2">
      <div class="fg"><label>Loan Type</label><select id="p_type" onchange="P()"><option value="p1">Purchase — 1st Time</option><option value="ps">Purchase — Subsequent</option><option value="c1">Cash-Out — 1st</option><option value="cs">Cash-Out — Sub</option><option value="ir">IRRRL</option></select></div>
      <div class="fg"><label>Military</label><select id="p_mil" onchange="P()"><option value="r">Regular Military</option><option value="g">Reserves / Guard</option></select></div>
    </div>
  </div>
  <div class="c">
    <div class="c-title"><span>&#128176;</span> Monthly Expenses</div>
    <div class="row2">
      <div class="fg"><label>Annual Property Tax</label><div class="iw pl"><span class="pre">$</span><input type="number" id="p_tax" value="4200" oninput="P()"></div></div>
      <div class="fg"><label>Annual Home Insurance</label><div class="iw pl"><span class="pre">$</span><input type="number" id="p_ins" value="1800" oninput="P()"></div></div>
    </div>
    <div class="row2">
      <div class="fg"><label>Monthly HOA Fee</label><div class="iw pl"><span class="pre">$</span><input type="number" id="p_hoa" value="0" oninput="P()"></div></div>
      <div class="fg"><label>Closing Costs</label><div class="iw pl"><span class="pre">$</span><input type="number" id="p_close" value="5000" oninput="P()"></div></div>
    </div>
    <div class="fg" style="margin-bottom:1rem"><label>Disability Status</label>
      <div class="tg" id="p_dis">
        <button class="tg-btn on" onclick="tg('p_dis',this,'no');P()">Not Exempt</button>
        <button class="tg-btn" onclick="tg('p_dis',this,'ex');P()">Exempt (10%+ Disability)</button>
      </div>
    </div>
    <button class="btn-go" onclick="P()">Calculate PITI</button>
  </div>
</div>
<div>
  <div class="c-dark">
    <div class="rhero">
      <div class="rlabel">Total Monthly (PITI)</div>
      <div class="rval" id="p_piti_disp">$2,970/mo</div>
      <div class="rsub">Principal + Interest + Tax + Insurance + HOA</div>
    </div>
    <div class="c-title" style="color:rgba(255,255,255,.35);margin-top:.5rem"><span>&#128202;</span> Breakdown</div>
    <div class="bbar">
      <div class="bseg" id="pb1" style="background:#00C896;width:78%"></div>
      <div class="bseg" id="pb2" style="background:#3B82F6;width:12%"></div>
      <div class="bseg" id="pb3" style="background:#F59E0B;width:6%"></div>
      <div class="bseg" id="pb4" style="background:#8B5CF6;width:4%"></div>
    </div>
    <div class="bleg" style="margin-bottom:1rem">
      <div class="bleg-i"><div class="bdot" style="background:#00C896"></div>P&I</div>
      <div class="bleg-i"><div class="bdot" style="background:#3B82F6"></div>Property Tax</div>
      <div class="bleg-i"><div class="bdot" style="background:#F59E0B"></div>Insurance</div>
      <div class="bleg-i"><div class="bdot" style="background:#8B5CF6"></div>HOA</div>
    </div>
    <div class="rrow"><span class="rk">Principal & Interest</span><span class="rv g" id="p_r1">$2,319/mo</span></div>
    <div class="rrow"><span class="rk">Property Tax</span><span class="rv" id="p_r2">$350/mo</span></div>
    <div class="rrow"><span class="rk">Insurance</span><span class="rv" id="p_r3">$150/mo</span></div>
    <div class="rrow"><span class="rk">HOA</span><span class="rv" id="p_r4">$0/mo</span></div>
    <div class="rrow"><span class="rk">Funding Fee</span><span class="rv gold" id="p_r5">$7,525</span></div>
    <div class="rrow"><span class="rk">Total Loan + Fee</span><span class="rv" id="p_r6">$357,525</span></div>
    <div class="rrow"><span class="rk">Cash Needed to Close</span><span class="rv gold" id="p_r7">$5,000</span></div>
    <div class="rrow" style="border-top:1px solid rgba(255,255,255,.12);margin-top:.4rem;padding-top:.75rem">
      <span class="rk" style="color:#fff;font-weight:800">30-Year Total Cost</span>
      <span class="rv r" id="p_r8">$1,069,200</span>
    </div>
    <!-- PITI Donut chart -->
    <div class="chart-box" style="height:170px"><canvas id="p_chart"></canvas></div>
    <button class="btn-pdf" onclick="pdfPiti()">&#128196; Download PDF Report</button>
  </div>
</div>
</div>
</div>

<!-- ══════════════════════
     TAB 3 — SCENARIOS
══════════════════════ -->
<div class="panel" id="p-compare">
  <div class="sh"><h2>Down Payment Scenario Comparison</h2><p>0%, 5%, 10%, 20% down — which saves you most over time?</p></div>
  <div class="c">
    <div class="row3">
      <div class="fg"><label>Purchase Price</label><div class="iw pl"><span class="pre">$</span><input type="number" id="c_price" value="350000" oninput="C()"></div></div>
      <div class="fg"><label>Interest Rate</label><div class="iw pr"><input type="number" id="c_rate" value="6.75" step=".05" oninput="C()"><span class="suf">%</span></div></div>
      <div class="fg"><label>Loan Type</label><select id="c_type" onchange="C()"><option value="p1">First Time Purchase</option><option value="ps">Subsequent Purchase</option></select></div>
    </div>
    <div class="row2">
      <div class="fg"><label>Loan Term</label><select id="c_term" onchange="C()"><option value="30">30 Years</option><option value="15">15 Years</option></select></div>
      <div class="fg"><label>Military</label><select id="c_mil" onchange="C()"><option value="r">Regular Military</option><option value="g">Reserves / Guard</option></select></div>
    </div>
  </div>
  <div class="c" style="overflow-x:auto">
    <div class="c-title"><span>&#9878;&#65039;</span> Side-by-Side Results</div>
    <table class="tbl" id="c_table"><thead></thead><tbody><tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--muted)">Fill inputs above — auto calculates</td></tr></tbody></table>
  </div>
  <div class="c">
    <div class="c-title"><span>&#128202;</span> Fee vs Total Cost Chart</div>
    <div class="chart-box" style="height:220px"><canvas id="c_chart"></canvas></div>
  </div>
  <button class="btn-pdf" onclick="pdfCompare()" style="max-width:260px">&#128196; Download Comparison PDF</button>
</div>

<!-- ══════════════════════
     TAB 4 — VA vs CONV
══════════════════════ -->
<div class="panel" id="p-vsconv">
  <div class="sh"><h2>VA Loan vs Conventional Loan</h2><p>See exactly how much VA saves you over 30 years.</p></div>
  <div class="c">
    <div class="row3">
      <div class="fg"><label>Purchase Price</label><div class="iw pl"><span class="pre">$</span><input type="number" id="v_price" value="350000" oninput="VS()"></div></div>
      <div class="fg"><label>Down Payment %</label><div class="iw pr"><input type="number" id="v_down" value="0" oninput="VS()"><span class="suf">%</span></div></div>
      <div class="fg"><label>Interest Rate</label><div class="iw pr"><input type="number" id="v_rate" value="6.75" step=".05" oninput="VS()"><span class="suf">%</span></div></div>
    </div>
    <div class="row3">
      <div class="fg"><label>Conventional Rate</label><div class="iw pr"><input type="number" id="v_crate" value="7.10" step=".05" oninput="VS()"><span class="suf">%</span></div></div>
      <div class="fg"><label>Monthly PMI (Conv)</label><div class="iw pl"><span class="pre">$</span><input type="number" id="v_pmi" value="175" oninput="VS()"></div></div>
      <div class="fg"><label>Loan Term</label><select id="v_term" onchange="VS()"><option value="30">30 Years</option><option value="15">15 Years</option></select></div>
    </div>
  </div>

  <div id="v_winner" class="winner-banner" style="display:none">
    <div style="font-size:2rem">&#127942;</div>
    <div>
      <div style="font-size:.85rem;font-weight:800;color:#0F172A" id="v_winner_title">VA Loan Saves You More!</div>
      <div style="font-size:.82rem;color:#475569" id="v_winner_desc">Over 30 years, VA loan saves you $0 compared to conventional.</div>
    </div>
  </div>

  <div class="vs-grid">
    <div class="vs-card va">
      <div class="vs-title va">&#127894;&#65039; VA Loan</div>
      <div class="vs-row"><span class="vk">Loan Amount</span><span class="vv" id="v_va_loan">—</span></div>
      <div class="vs-row"><span class="vk">Funding Fee</span><span class="vv" id="v_va_fee">—</span></div>
      <div class="vs-row"><span class="vk">Monthly P+I</span><span class="vv" id="v_va_pi">—</span></div>
      <div class="vs-row"><span class="vk">Monthly PMI</span><span class="vv" style="color:var(--green)">$0 — Never!</span></div>
      <div class="vs-row"><span class="vk">Total Monthly</span><span class="vv" id="v_va_mo">—</span></div>
      <div class="vs-row"><span class="vk">Total Interest</span><span class="vv" id="v_va_int">—</span></div>
      <div class="vs-row"><span class="vk">Total Cost</span><span class="vv" id="v_va_total">—</span></div>
    </div>
    <div class="vs-card conv">
      <div class="vs-title conv">&#127974; Conventional</div>
      <div class="vs-row"><span class="vk">Loan Amount</span><span class="vv" id="v_cv_loan">—</span></div>
      <div class="vs-row"><span class="vk">Funding Fee</span><span class="vv">$0</span></div>
      <div class="vs-row"><span class="vk">Monthly P+I</span><span class="vv" id="v_cv_pi">—</span></div>
      <div class="vs-row"><span class="vk">Monthly PMI</span><span class="vv" id="v_cv_pmi">—</span></div>
      <div class="vs-row"><span class="vk">Total Monthly</span><span class="vv" id="v_cv_mo">—</span></div>
      <div class="vs-row"><span class="vk">Total Interest</span><span class="vv" id="v_cv_int">—</span></div>
      <div class="vs-row"><span class="vk">Total Cost</span><span class="vv" id="v_cv_total">—</span></div>
    </div>
  </div>

  <div class="c">
    <div class="c-title"><span>&#128202;</span> Monthly Cost Comparison</div>
    <div class="chart-box" style="height:220px"><canvas id="v_chart"></canvas></div>
  </div>
  <button class="btn-pdf" onclick="pdfVsConv()" style="max-width:260px">&#128196; Download VA vs Conv PDF</button>
</div>

<!-- ══════════════════════
     TAB 5 — AMORTIZATION
══════════════════════ -->
<div class="panel" id="p-amort">
  <div class="sh"><h2>Amortization Schedule</h2><p>Year-by-year breakdown. Add extra payments to see early payoff.</p></div>
  <div class="c">
    <div class="row2">
      <div class="fg"><label>Total Loan Amount (with Fee)</label><div class="iw pl"><span class="pre">$</span><input type="number" id="a_loan" value="357525" oninput="A()"></div></div>
      <div class="fg"><label>Interest Rate</label><div class="iw pr"><input type="number" id="a_rate" value="6.75" step=".05" oninput="A()"><span class="suf">%</span></div></div>
    </div>
    <div class="row2">
      <div class="fg"><label>Loan Term</label><select id="a_term" onchange="A()"><option value="30">30 Years</option><option value="20">20 Years</option><option value="15">15 Years</option></select></div>
      <div class="fg"><label>Extra Monthly Payment</label><div class="iw pl"><span class="pre">$</span><input type="number" id="a_extra" value="0" oninput="A()"></div></div>
    </div>
  </div>

  <div class="c" style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.25rem;text-align:center">
    <div><div style="font-size:1.3rem;font-weight:900;color:var(--green)" id="a_kpi1">$834,251</div><div style="font-size:.72rem;color:var(--muted);text-transform:uppercase;margin-top:.2rem">Total Paid</div></div>
    <div><div style="font-size:1.3rem;font-weight:900;color:var(--red)" id="a_kpi2">$476,726</div><div style="font-size:.72rem;color:var(--muted);text-transform:uppercase;margin-top:.2rem">Total Interest</div></div>
    <div><div style="font-size:1.3rem;font-weight:900;color:var(--blue)" id="a_kpi3">2055</div><div style="font-size:.72rem;color:var(--muted);text-transform:uppercase;margin-top:.2rem">Payoff Year</div></div>
  </div>

  <div class="c">
    <div class="c-title"><span>&#128200;</span> Balance & Interest Over Time</div>
    <div class="chart-box" style="height:220px"><canvas id="a_chart"></canvas></div>
  </div>

  <div class="amort-wrap">
    <table class="amort-tbl">
      <thead><tr><th>Year</th><th>Pmt/mo</th><th>Principal Paid</th><th>Interest Paid</th><th>Remaining Balance</th><th>Equity</th></tr></thead>
      <tbody id="a_body"></tbody>
    </table>
  </div>

  <button class="btn-pdf" onclick="pdfAmort()" style="max-width:260px;margin-top:1rem">&#128196; Download Amortization PDF</button>
</div>

<!-- ══════════════════════
     TAB 6 — FEE GUIDE
══════════════════════ -->
<div class="panel" id="p-guide">
  <div class="sh"><h2>VA Funding Fee Rate Guide (2024–2026)</h2><p>Official rates from VA.gov — bookmark this page.</p></div>

  <div class="exempt-banner">
    <div class="eb-icon">&#127894;&#65039;</div>
    <div>
      <div class="eb-title">Who Pays $0 Funding Fee? (Full Exemptions)</div>
      <div class="eb-desc">• Veterans receiving VA service-connected disability compensation (any rating)<br>
      • Veterans rated eligible for compensation but receiving military retirement pay<br>
      • Active-duty service members who have received a Purple Heart<br>
      • Surviving spouses of veterans who died in service or from service-connected disability<br>
      • Recipients of the Medal of Honor</div>
    </div>
  </div>

  <div class="c" style="overflow-x:auto;margin-bottom:1.25rem">
    <div class="c-title"><span>&#127968;</span> Purchase & Construction Loans</div>
    <table class="guide-tbl">
      <thead><tr><th>Military Category</th><th>1st Use — 0% Down</th><th>1st Use — 5%+ Down</th><th>1st Use — 10%+ Down</th><th>Subsequent Use</th></tr></thead>
      <tbody>
        <tr><td>Regular Military (Active/Reserve)</td><td><span class="pill">2.15%</span></td><td><span class="pill">1.50%</span></td><td><span class="pill">1.25%</span></td><td><span class="pill">3.30%</span></td></tr>
        <tr><td>National Guard / Reserves</td><td><span class="pill">2.40%</span></td><td><span class="pill">1.75%</span></td><td><span class="pill">1.50%</span></td><td><span class="pill">3.30%</span></td></tr>
      </tbody>
    </table>
  </div>

  <div class="c" style="overflow-x:auto;margin-bottom:1.25rem">
    <div class="c-title"><span>&#128260;</span> Refinance Loans</div>
    <table class="guide-tbl">
      <thead><tr><th>Loan Type</th><th>Regular Military</th><th>Reserves / Nat'l Guard</th></tr></thead>
      <tbody>
        <tr><td>Cash-Out Refinance (1st Use)</td><td><span class="pill">2.15%</span></td><td><span class="pill">2.40%</span></td></tr>
        <tr><td>Cash-Out Refinance (Subsequent)</td><td><span class="pill">3.30%</span></td><td><span class="pill">3.30%</span></td></tr>
        <tr><td>IRRRL / Streamline Refinance</td><td><span class="pill">0.50%</span></td><td><span class="pill">0.50%</span></td></tr>
        <tr><td>Loan Assumption</td><td><span class="pill">0.50%</span></td><td><span class="pill">0.50%</span></td></tr>
        <tr><td>Native American Direct Loan</td><td><span class="pill">1.25%</span></td><td>N/A</td></tr>
      </tbody>
    </table>
  </div>

  <div class="c">
    <div class="c-title"><span>&#128161;</span> Expert Strategy Tips</div>
    <div class="tip g"><span class="tip-i">&#9989;</span><span><strong>Roll it into the loan:</strong> Fund your fee inside the loan — no cash needed at closing. Your rate stays the same.</span></div>
    <div class="tip b"><span class="tip-i">ℹ&#65039;</span><span><strong>No PMI — ever:</strong> VA loans never require Private Mortgage Insurance, saving $100–$300/month vs conventional, regardless of down payment.</span></div>
    <div class="tip y"><span class="tip-i">&#9888;&#65039;</span><span><strong>Subsequent use penalty:</strong> Used a VA loan before? 0% down triggers 3.30% — but putting 5% down does NOT reduce this for subsequent use. Plan accordingly.</span></div>
    <div class="tip g"><span class="tip-i">&#128176;</span><span><strong>5% sweet spot:</strong> First-time users — 5% down cuts fee from 2.15%→1.50% (30% reduction). On a $400k loan that's $2,600 saved.</span></div>
    <div class="tip r"><span class="tip-i">&#10071;</span><span><strong>Check your rating:</strong> Even a pending disability claim can qualify for exemption. Always confirm your status before closing — you can get a refund if later approved.</span></div>
  </div>
</div>

</div><!-- /wrap -->

<script>
// ═══════ RATE TABLES ═══════
const R = {
  p1: { r:{0:2.15,5:1.50,10:1.25}, g:{0:2.40,5:1.75,10:1.50} },
  ps: { r:3.30, g:3.30 },
  c1: { r:2.15, g:2.40 },
  cs: { r:3.30, g:3.30 },
  ir: { r:0.50, g:0.50 },
  as: { r:0.50, g:0.50 },
};

function getRate(type, mil, dp) {
  const t = R[type];
  if (!t) return 2.15;
  if (typeof t === 'number') return t; // flat rate types
  if (t.r && typeof t.r === 'object') {
    const rates = t[mil] || t.r;
    if (dp >= 10) return rates[10];
    if (dp >= 5)  return rates[5];
    return rates[0];
  }
  return t[mil] || 2.15;
}

// ═══════ HELPERS ═══════
const $ = id => document.getElementById(id);
const fmt  = n => '$' + Math.round(n).toLocaleString();
const fmtD = n => '$' + parseFloat(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');
const pct  = n => n.toFixed(2) + '%';
function mp(principal, annRate, yrs) {
  if (!annRate || !principal) return 0;
  const r = annRate/100/12, n = yrs*12;
  return principal * r * Math.pow(1+r,n) / (Math.pow(1+r,n)-1);
}
function tg(id, btn, val) {
  document.querySelectorAll('#'+id+' .tg-btn').forEach(b=>b.classList.remove('on'));
  btn.classList.add('on');
  btn.dataset.val = val;
}
function tgVal(id) {
  const a = document.querySelector('#'+id+' .tg-btn.on');
  return a ? (a.dataset.val||'no') : 'no';
}
function syncR() { $('q_dr').value = Math.min($('q_down').value,25); }

// Chart instances
let qChart=null, pChart=null, cChart=null, vChart=null, aChart=null;
function mkChart(id,cfg){
  const c = Chart.getChart(id);
  if(c) c.destroy();
  return new Chart($(id), cfg);
}

// ═══════ TAB 1: QUICK ═══════
function Q() {
  const price = +$('q_price').value||0;
  const dp    = +$('q_down').value||0;
  const type  = $('q_type').value;
  const mil   = $('q_mil').value;
  const rate  = +$('q_rate').value||6.75;
  const term  = +(tgVal('q_term')||30);
  const dis   = tgVal('q_dis');
  const exempt= dis==='ex'||dis==='sp';

  $('q_exempt_box').style.display = exempt ? 'block' : 'none';

  const downAmt   = price*dp/100;
  const baseLoan  = price - downAmt;
  const feeRate   = exempt ? 0 : getRate(type,mil,dp);
  const fee       = baseLoan*feeRate/100;
  const totalLoan = baseLoan + fee;
  const monthly   = mp(totalLoan, rate, term);
  const totalInt  = monthly*term*12 - totalLoan;
  const totalCost = monthly*term*12;

  // tip savings
  const f0 = baseLoan*getRate(type,mil,0)/100;
  const f5 = baseLoan*getRate(type,mil,5)/100;
  $('q_tip_save').textContent = fmt(Math.max(f0-f5,0));

  $('q_fee_disp').textContent    = exempt ? '$0 — EXEMPT &#127894;&#65039;' : fmt(fee);
  $('q_fee_pct_disp').textContent= exempt ? 'Disability exemption applied' : pct(feeRate)+' of base loan';
  $('q_r1').textContent = fmt(price);
  $('q_r2').textContent = fmt(downAmt)+' ('+dp+'%)';
  $('q_r3').textContent = fmt(baseLoan);
  $('q_r4').textContent = exempt ? '$0 (Exempt)' : fmt(fee);
  $('q_r5').textContent = fmt(totalLoan);
  $('q_r6').textContent = fmt(monthly)+'/mo';
  $('q_r7').textContent = fmt(totalInt);
  $('q_r8').textContent = fmt(totalCost);

  // bars
  const tot = price+fee;
  $('bb1').style.width = (baseLoan/tot*100).toFixed(1)+'%';
  $('bb2').style.width = (fee/tot*100).toFixed(1)+'%';
  $('bb3').style.width = (downAmt/tot*100).toFixed(1)+'%';

  // Balance chart
  const labels=[], balData=[], intData=[];
  let bal=totalLoan, cumInt=0;
  const r=rate/100/12;
  for(let yr=1;yr<=term;yr++){
    for(let m=0;m<12;m++){
      const i=bal*r, p=Math.min(monthly-i,bal);
      cumInt+=i; bal=Math.max(bal-p,0);
    }
    labels.push('Yr '+yr);
    balData.push(+bal.toFixed(0));
    intData.push(+cumInt.toFixed(0));
  }
  qChart = mkChart('q_chart',{
    type:'line',
    data:{
      labels,
      datasets:[
        {label:'Remaining Balance',data:balData,borderColor:'#00C896',backgroundColor:'rgba(0,200,150,.12)',fill:true,tension:.4,pointRadius:0,borderWidth:2},
        {label:'Cumulative Interest',data:intData,borderColor:'#EF4444',backgroundColor:'rgba(239,68,68,.08)',fill:true,tension:.4,pointRadius:0,borderWidth:2},
      ]
    },
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{color:'rgba(255,255,255,.5)',font:{size:11}}}},scales:{x:{ticks:{color:'rgba(255,255,255,.35)',font:{size:10},maxTicksLimit:8},grid:{color:'rgba(255,255,255,.05)'}},y:{ticks:{color:'rgba(255,255,255,.35)',font:{size:10},callback:v=>fmt(v)},grid:{color:'rgba(255,255,255,.05)'}}}}
  });
}

function resetQ(){
  $('q_price').value=350000;$('q_down').value=0;$('q_dr').value=0;$('q_rate').value=6.75;
  $('q_type').value='p1';$('q_mil').value='r';
  document.querySelectorAll('#q_dis .tg-btn').forEach((b,i)=>b.classList.toggle('on',i===0));
  document.querySelectorAll('#q_term .tg-btn').forEach((b,i)=>b.classList.toggle('on',i===1));
  Q();
}

// ═══════ TAB 2: PITI ═══════
let pDonut=null;
function P() {
  const price = +$('p_price').value||0;
  const dp    = +$('p_down').value||0;
  const rate  = +$('p_rate').value||6.75;
  const term  = +$('p_term').value||30;
  const type  = $('p_type').value;
  const mil   = $('p_mil').value;
  const tax   = +$('p_tax').value||0;
  const ins   = +$('p_ins').value||0;
  const hoa   = +$('p_hoa').value||0;
  const close = +$('p_close').value||0;
  const exempt= tgVal('p_dis')==='ex';

  const downAmt  = price*dp/100;
  const baseLoan = price-downAmt;
  const feeRate  = exempt?0:getRate(type,mil,dp);
  const fee      = baseLoan*feeRate/100;
  const totalLoan= baseLoan+fee;
  const pi       = mp(totalLoan,rate,term);
  const taxMo    = tax/12, insMo=ins/12;
  const piti     = pi+taxMo+insMo+hoa;
  const lifetime = piti*term*12;

  const piW=pi/piti*100, txW=taxMo/piti*100, insW=insMo/piti*100, hoaW=hoa/piti*100;
  $('pb1').style.width=piW.toFixed(1)+'%';
  $('pb2').style.width=txW.toFixed(1)+'%';
  $('pb3').style.width=insW.toFixed(1)+'%';
  $('pb4').style.width=hoaW.toFixed(1)+'%';

  $('p_piti_disp').textContent=fmt(piti)+'/mo';
  $('p_r1').textContent=fmt(pi)+'/mo';
  $('p_r2').textContent=fmt(taxMo)+'/mo';
  $('p_r3').textContent=fmt(insMo)+'/mo';
  $('p_r4').textContent=fmt(hoa)+'/mo';
  $('p_r5').textContent=exempt?'$0 (Exempt)':fmt(fee)+' ('+pct(feeRate)+')';
  $('p_r6').textContent=fmt(totalLoan);
  $('p_r7').textContent=fmt(downAmt+close);
  $('p_r8').textContent=fmt(lifetime);

  // Donut
  pDonut = mkChart('p_chart',{
    type:'doughnut',
    data:{labels:['Principal & Interest','Property Tax','Insurance','HOA'],datasets:[{data:[+pi.toFixed(0),+taxMo.toFixed(0),+insMo.toFixed(0),+hoa.toFixed(0)],backgroundColor:['#00C896','#3B82F6','#F59E0B','#8B5CF6'],borderWidth:0}]},
    options:{responsive:true,maintainAspectRatio:false,cutout:'68%',plugins:{legend:{position:'right',labels:{color:'#475569',font:{size:11},padding:12}},tooltip:{callbacks:{label:ctx=>' '+fmt(ctx.parsed)+'/mo'}}}}
  });
}

// ═══════ TAB 3: COMPARE ═══════
function C() {
  const price = +$('c_price').value||350000;
  const rate  = +$('c_rate').value||6.75;
  const type  = $('c_type').value;
  const term  = +$('c_term').value||30;
  const mil   = $('c_mil').value;
  const downs = [0,5,10,20];

  const sc = downs.map(dp=>{
    const downAmt  = price*dp/100;
    const baseLoan = price-downAmt;
    const fr       = getRate(type,mil,dp);
    const fee      = baseLoan*fr/100;
    const loan     = baseLoan+fee;
    const mo       = mp(loan,rate,term);
    const totalInt = mo*term*12-loan;
    return {dp,downAmt,baseLoan,fr,fee,loan,mo,totalInt,total:mo*term*12};
  });

  // Best = lowest total cost
  const bestI = sc.reduce((b,s,i)=>s.total<sc[b].total?i:b,0);

  const rows = [
    ['Down Payment Amount',  s=>fmt(s.downAmt)],
    ['Fee Rate',             s=>'<span class="pill">'+pct(s.fr)+'</span>'],
    ['Funding Fee',          s=>fmt(s.fee)],
    ['Total Loan + Fee',     s=>fmt(s.loan)],
    ['Monthly Payment',      s=>fmt(s.mo)+'/mo'],
    ['Total Interest',       s=>fmt(s.totalInt)],
    ['Total Cost ('+term+'yr)',s=>fmt(s.total)],
    ['Cash at Closing',      s=>fmt(s.downAmt)],
  ];

  const thead = `<tr><th>Metric</th>${downs.map(d=>`<th>${d}% Down</th>`).join('')}</tr>`;
  const tbody = rows.map(([lbl,fn])=>{
    const cells = sc.map((s,i)=>`<td${i===bestI?' style="color:var(--green);font-weight:800"':''}>` + fn(s) +'</td>').join('');
    return `<tr${''/*bestI handled inline*/}><td>${lbl}</td>${cells}</tr>`;
  }).join('');

  $('c_table').querySelector('thead').innerHTML = thead;
  $('c_table').querySelector('tbody').innerHTML = tbody;

  // Bar chart
  cChart = mkChart('c_chart',{
    type:'bar',
    data:{
      labels:['0% Down','5% Down','10% Down','20% Down'],
      datasets:[
        {label:'Funding Fee',data:sc.map(s=>+s.fee.toFixed(0)),backgroundColor:'#F59E0B'},
        {label:'Total Interest',data:sc.map(s=>+s.totalInt.toFixed(0)),backgroundColor:'#EF4444'},
        {label:'Down Payment',data:sc.map(s=>+s.downAmt.toFixed(0)),backgroundColor:'#3B82F6'},
      ]
    },
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{color:'#475569',font:{size:11}}}},scales:{x:{stacked:false,ticks:{color:'#475569'}},y:{ticks:{color:'#475569',callback:v=>fmt(v)},grid:{color:'rgba(0,0,0,.06)'}}}}
  });
}

// ═══════ TAB 4: VA vs CONV ═══════
function VS() {
  const price  = +$('v_price').value||350000;
  const dp     = +$('v_down').value||0;
  const vaRate = +$('v_rate').value||6.75;
  const cvRate = +$('v_crate').value||7.10;
  const pmi    = +$('v_pmi').value||175;
  const term   = +$('v_term').value||30;

  const downAmt = price*dp/100;
  const baseLoan= price-downAmt;

  // VA
  const vaFeeRate = getRate('p1','r',dp);
  const vaFee     = baseLoan*vaFeeRate/100;
  const vaLoan    = baseLoan+vaFee;
  const vaPi      = mp(vaLoan,vaRate,term);
  const vaInt     = vaPi*term*12-vaLoan;
  const vaTotal   = vaPi*term*12;

  // Conventional (no fee, but higher rate + PMI)
  const cvLoan    = baseLoan;
  const cvPi      = mp(cvLoan,cvRate,term);
  const cvPmiYrs  = dp<20 ? Math.min(term,11) : 0; // PMI until 20% equity ~11yrs avg
  const cvPmiTotal= pmi*cvPmiYrs*12;
  const cvInt     = cvPi*term*12-cvLoan;
  const cvTotal   = cvPi*term*12+cvPmiTotal;

  const vaBetter = vaTotal < cvTotal;
  const diff = Math.abs(cvTotal-vaTotal);

  $('v_winner').style.display='flex';
  $('v_winner_title').textContent = vaBetter ? '&#127942; VA Loan Wins!' : '&#9888;&#65039; Conventional May Be Better';
  $('v_winner_desc').textContent  = vaBetter
    ? `VA loan saves you ${fmt(diff)} over ${term} years vs conventional.`
    : `Conventional saves ${fmt(diff)} — compare carefully based on your situation.`;

  const fill = id => v => $(id).textContent=v;
  fill('v_va_loan')(fmt(vaLoan)); fill('v_va_fee')(fmt(vaFee)+' ('+pct(vaFeeRate)+')');
  fill('v_va_pi')(fmt(vaPi)+'/mo'); fill('v_va_mo')(fmt(vaPi)+'/mo');
  fill('v_va_int')(fmt(vaInt)); fill('v_va_total')(fmt(vaTotal));
  fill('v_cv_loan')(fmt(cvLoan)); fill('v_cv_pmi')(fmt(pmi)+'/mo');
  fill('v_cv_pi')(fmt(cvPi)+'/mo'); fill('v_cv_mo')(fmt(cvPi+pmi)+'/mo');
  fill('v_cv_int')(fmt(cvInt)); fill('v_cv_total')(fmt(cvTotal));

  vChart = mkChart('v_chart',{
    type:'bar',
    data:{
      labels:['Monthly P+I','Monthly PMI','Monthly Total','Total Interest','Total Cost'],
      datasets:[
        {label:'VA Loan',data:[+vaPi.toFixed(0),0,+vaPi.toFixed(0),+vaInt.toFixed(0),+vaTotal.toFixed(0)],backgroundColor:'rgba(0,200,150,.7)'},
        {label:'Conventional',data:[+cvPi.toFixed(0),pmi,+(cvPi+pmi).toFixed(0),+cvInt.toFixed(0),+cvTotal.toFixed(0)],backgroundColor:'rgba(59,130,246,.7)'},
      ]
    },
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{color:'#475569',font:{size:11}}}},scales:{x:{ticks:{color:'#475569'}},y:{ticks:{color:'#475569',callback:v=>fmt(v)},grid:{color:'rgba(0,0,0,.06)'}}}}
  });
}

// ═══════ TAB 5: AMORT ═══════
function A() {
  const loan  = +$('a_loan').value||357525;
  const rate  = +$('a_rate').value||6.75;
  const yrs   = +$('a_term').value||30;
  const extra = +$('a_extra').value||0;

  const r = rate/100/12;
  const basePmt = r ? loan*r*Math.pow(1+r,yrs*12)/(Math.pow(1+r,yrs*12)-1) : loan/(yrs*12);
  const monthly = basePmt + extra;

  let bal=loan, cumInt=0, cumPrin=0, rows=[], payoffYr=yrs;
  const balArr=[], intArr=[], labels=[];

  for(let yr=1;yr<=yrs;yr++){
    let yrP=0,yrI=0;
    for(let m=0;m<12;m++){
      if(bal<=0) break;
      const i=bal*r, p=Math.min(monthly-i,bal);
      bal=Math.max(bal-p,0); yrP+=p; yrI+=i; cumInt+=i; cumPrin+=p;
    }
    labels.push('Yr '+yr);
    balArr.push(+bal.toFixed(0));
    intArr.push(+cumInt.toFixed(0));
    rows.push({yr,monthly,yrP,yrI,bal:Math.max(bal,0),equity:loan-Math.max(bal,0)});
    if(bal<=0 && payoffYr===yrs){payoffYr=yr; break;}
  }

  const totalPaid = rows.reduce((s,r)=>s+r.yrP+r.yrI,0);
  $('a_kpi1').textContent = fmt(totalPaid);
  $('a_kpi2').textContent = fmt(cumInt);
  $('a_kpi3').textContent = new Date().getFullYear() + payoffYr;

  $('a_body').innerHTML = rows.map(row=>`<tr>
    <td>Year ${row.yr}</td>
    <td>${fmt(row.monthly)}</td>
    <td style="color:#00C896">${fmt(row.yrP)}</td>
    <td style="color:#EF4444">${fmt(row.yrI)}</td>
    <td>${fmt(row.bal)}</td>
    <td style="color:#3B82F6">${fmt(row.equity)}</td>
  </tr>`).join('');

  aChart = mkChart('a_chart',{
    type:'line',
    data:{
      labels,
      datasets:[
        {label:'Remaining Balance',data:balArr,borderColor:'#00C896',backgroundColor:'rgba(0,200,150,.1)',fill:true,tension:.4,pointRadius:0,borderWidth:2},
        {label:'Cumulative Interest',data:intArr,borderColor:'#EF4444',backgroundColor:'rgba(239,68,68,.08)',fill:true,tension:.4,pointRadius:0,borderWidth:2},
      ]
    },
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{color:'#475569',font:{size:11}}}},scales:{x:{ticks:{color:'#475569',font:{size:10},maxTicksLimit:8},grid:{color:'rgba(0,0,0,.05)'}},y:{ticks:{color:'#475569',font:{size:10},callback:v=>fmt(v)},grid:{color:'rgba(0,0,0,.05)'}}}}
  });
}

// ═══════ TAB SWITCH ═══════
function goTab(name, btn) {
  document.querySelectorAll('.panel').forEach(p=>p.classList.remove('on'));
  document.querySelectorAll('.tab').forEach(b=>b.classList.remove('on'));
  $('p-'+name).classList.add('on');
  btn.classList.add('on');
  // lazy init
  if(name==='compare') C();
  if(name==='vsconv')  VS();
  if(name==='amort')   A();
}

// ═══════════════════════════════════
//  PDF GENERATORS (jsPDF)
// ═══════════════════════════════════
function pdfHeader(doc, title) {
  doc.setFillColor(10,15,30);
  doc.rect(0,0,210,28,'F');
  doc.setTextColor(0,200,150);
  doc.setFont('helvetica','bold');
  doc.setFontSize(14);
  doc.text('FinanceSpots.com', 14, 11);
  doc.setTextColor(255,255,255);
  doc.setFontSize(10);
  doc.text(title, 14, 20);
  doc.setTextColor(150,150,150);
  doc.setFontSize(8);
  doc.text('Generated: ' + new Date().toLocaleDateString('en-US',{year:'numeric',month:'long',day:'numeric'}), 196, 20, {align:'right'});
  return 36;
}

function pdfFooter(doc) {
  const pages = doc.internal.getNumberOfPages();
  for(let i=1;i<=pages;i++){
    doc.setPage(i);
    doc.setFontSize(7);
    doc.setTextColor(150);
    doc.text('FinanceSpots.com — VA Loan Calculator | For informational purposes only. Not financial advice.', 105, 290, {align:'center'});
    doc.text('Page '+i+' of '+pages, 196, 290, {align:'right'});
  }
}

function pdfQuick() {
  const {jsPDF} = window.jspdf;
  const doc = new jsPDF();
  let y = pdfHeader(doc, 'VA Loan Funding Fee — Quick Analysis Report');

  const price = +$('q_price').value||0;
  const dp    = +$('q_down').value||0;
  const type  = $('q_type').value;
  const mil   = $('q_mil').value;
  const rate  = +$('q_rate').value||6.75;
  const term  = +(tgVal('q_term')||30);
  const exempt= ['ex','sp'].includes(tgVal('q_dis'));
  const downAmt  = price*dp/100;
  const baseLoan = price-downAmt;
  const feeRate  = exempt?0:getRate(type,mil,dp);
  const fee      = baseLoan*feeRate/100;
  const totalLoan= baseLoan+fee;
  const monthly  = mp(totalLoan,rate,term);
  const totalInt = monthly*term*12-totalLoan;

  // Input summary
  doc.autoTable({
    startY:y,
    head:[['INPUT','VALUE'],['OUTPUT','VALUE']],
    body:[
      ['Purchase Price', fmt(price), 'VA Funding Fee Rate', pct(feeRate)+(exempt?' (EXEMPT)':'')],
      ['Down Payment', fmt(downAmt)+' ('+dp+'%)', 'Funding Fee Amount', fmt(fee)],
      ['Loan Type', $('q_type').options[$('q_type').selectedIndex].text, 'Base Loan Amount', fmt(baseLoan)],
      ['Interest Rate', rate+'%', 'Total Loan + Fee', fmt(totalLoan)],
      ['Loan Term', term+' Years', 'Monthly Payment (P+I)', fmt(monthly)+'/mo'],
      ['Military Category', $('q_mil').options[$('q_mil').selectedIndex].text, 'Total Interest Paid', fmt(totalInt)],
      ['Disability Status', exempt?'Exempt':'Not Exempt', 'Total Cost', fmt(monthly*term*12)],
    ],
    theme:'grid',
    headStyles:{fillColor:[10,15,30],textColor:[0,200,150],fontSize:8,fontStyle:'bold'},
    columnStyles:{0:{fontStyle:'bold',cellWidth:45},1:{cellWidth:45},2:{fontStyle:'bold',cellWidth:55},3:{cellWidth:45}},
    styles:{fontSize:9,cellPadding:3},
    margin:{left:14,right:14},
  });

  // Green highlight box
  y = doc.lastAutoTable.finalY + 8;
  doc.setFillColor(0,200,150);
  doc.roundedRect(14, y, 182, 18, 3, 3, 'F');
  doc.setTextColor(255,255,255);
  doc.setFontSize(11);
  doc.setFont('helvetica','bold');
  doc.text('Total Loan Amount: ' + fmt(totalLoan) + '   |   Monthly Payment: ' + fmt(monthly)+'/mo', 105, y+11, {align:'center'});

  pdfFooter(doc);
  doc.save('VA-Loan-Quick-Report-'+new Date().toISOString().slice(0,10)+'.pdf');
}

function pdfPiti() {
  const {jsPDF} = window.jspdf;
  const doc = new jsPDF();
  let y = pdfHeader(doc, 'VA Loan — Full PITI Monthly Payment Report');

  const price = +$('p_price').value||0;
  const dp    = +$('p_down').value||0;
  const rate  = +$('p_rate').value||6.75;
  const term  = +$('p_term').value||30;
  const type  = $('p_type').value;
  const mil   = $('p_mil').value;
  const tax   = +$('p_tax').value||0;
  const ins   = +$('p_ins').value||0;
  const hoa   = +$('p_hoa').value||0;
  const close = +$('p_close').value||0;
  const exempt= tgVal('p_dis')==='ex';

  const downAmt  = price*dp/100;
  const baseLoan = price-downAmt;
  const feeRate  = exempt?0:getRate(type,mil,dp);
  const fee      = baseLoan*feeRate/100;
  const totalLoan= baseLoan+fee;
  const pi       = mp(totalLoan,rate,term);
  const taxMo=tax/12, insMo=ins/12;
  const piti     = pi+taxMo+insMo+hoa;

  doc.autoTable({
    startY:y,
    head:[['MONTHLY PAYMENT BREAKDOWN','AMOUNT'],['LOAN SUMMARY','VALUE']],
    body:[
      ['Principal & Interest', fmt(pi)+'/mo', 'Purchase Price', fmt(price)],
      ['Property Tax', fmt(taxMo)+'/mo', 'Down Payment', fmt(downAmt)+' ('+dp+'%)'],
      ['Home Insurance', fmt(insMo)+'/mo', 'Funding Fee', fmt(fee)+' ('+pct(feeRate)+')'],
      ['HOA Fee', fmt(hoa)+'/mo', 'Total Loan Amount', fmt(totalLoan)],
      ['─────────────', '─────────', 'Closing Costs', fmt(close)],
      ['TOTAL PITI', fmt(piti)+'/mo', 'Cash Needed to Close', fmt(downAmt+close)],
      ['', '', 'Total 30yr Cost', fmt(piti*term*12)],
    ],
    theme:'grid',
    headStyles:{fillColor:[10,15,30],textColor:[0,200,150],fontSize:8,fontStyle:'bold'},
    columnStyles:{0:{fontStyle:'bold',cellWidth:55},1:{cellWidth:35},2:{fontStyle:'bold',cellWidth:55},3:{cellWidth:45}},
    styles:{fontSize:9,cellPadding:3},
    margin:{left:14,right:14},
  });

  y = doc.lastAutoTable.finalY + 8;
  doc.setFillColor(10,15,30);
  doc.roundedRect(14, y, 182, 18, 3, 3, 'F');
  doc.setTextColor(0,200,150);
  doc.setFontSize(12);
  doc.setFont('helvetica','bold');
  doc.text('Total Monthly PITI: '+fmt(piti)+'/mo', 105, y+11, {align:'center'});

  pdfFooter(doc);
  doc.save('VA-Loan-PITI-Report-'+new Date().toISOString().slice(0,10)+'.pdf');
}

function pdfCompare() {
  const {jsPDF} = window.jspdf;
  const doc = new jsPDF('landscape');
  let y = pdfHeader(doc, 'VA Loan — Down Payment Scenario Comparison');

  const price = +$('c_price').value||350000;
  const rate  = +$('c_rate').value||6.75;
  const type  = $('c_type').value;
  const term  = +$('c_term').value||30;
  const mil   = $('c_mil').value;
  const downs = [0,5,10,20];

  const sc = downs.map(dp=>{
    const downAmt=price*dp/100, baseLoan=price-downAmt;
    const fr=getRate(type,mil,dp), fee=baseLoan*fr/100, loan=baseLoan+fee;
    const mo=mp(loan,rate,term);
    return {dp,downAmt,baseLoan,fr,fee,loan,mo,totalInt:mo*term*12-loan,total:mo*term*12};
  });

  doc.autoTable({
    startY:y,
    head:[['Metric','0% Down','5% Down','10% Down','20% Down']],
    body:[
      ['Down Payment Amount', ...sc.map(s=>fmt(s.downAmt))],
      ['Funding Fee Rate',    ...sc.map(s=>pct(s.fr))],
      ['Funding Fee Amount',  ...sc.map(s=>fmt(s.fee))],
      ['Total Loan + Fee',    ...sc.map(s=>fmt(s.loan))],
      ['Monthly Payment',     ...sc.map(s=>fmt(s.mo)+'/mo')],
      ['Total Interest',      ...sc.map(s=>fmt(s.totalInt))],
      ['Total Cost ('+term+'yr)', ...sc.map(s=>fmt(s.total))],
      ['Cash at Closing',     ...sc.map(s=>fmt(s.downAmt))],
    ],
    theme:'grid',
    headStyles:{fillColor:[10,15,30],textColor:[0,200,150],fontSize:9,fontStyle:'bold'},
    styles:{fontSize:9,cellPadding:3.5},
    columnStyles:{0:{fontStyle:'bold'}},
    didParseCell: d=>{ if(d.section==='body'&&d.column.index===1&&d.row.index===6){d.cell.styles.textColor=[0,200,150];d.cell.styles.fontStyle='bold';}},
    margin:{left:14,right:14},
  });

  pdfFooter(doc);
  doc.save('VA-Loan-Scenario-Comparison-'+new Date().toISOString().slice(0,10)+'.pdf');
}

function pdfVsConv() {
  const {jsPDF} = window.jspdf;
  const doc = new jsPDF();
  let y = pdfHeader(doc, 'VA Loan vs Conventional Loan — Comparison Report');

  const price  = +$('v_price').value||350000;
  const dp     = +$('v_down').value||0;
  const vaRate = +$('v_rate').value||6.75;
  const cvRate = +$('v_crate').value||7.10;
  const pmi    = +$('v_pmi').value||175;
  const term   = +$('v_term').value||30;

  const downAmt=price*dp/100, baseLoan=price-downAmt;
  const vaFR=getRate('p1','r',dp), vaFee=baseLoan*vaFR/100, vaLoan=baseLoan+vaFee;
  const vaPi=mp(vaLoan,vaRate,term), vaTotal=vaPi*term*12;
  const cvPi=mp(baseLoan,cvRate,term), cvPmiTotal=pmi*(dp<20?Math.min(term,11)*12:0);
  const cvTotal=cvPi*term*12+cvPmiTotal;

  doc.autoTable({
    startY:y,
    head:[['Metric','VA Loan','Conventional','Difference']],
    body:[
      ['Loan Amount', fmt(vaLoan), fmt(baseLoan), fmt(baseLoan-vaLoan)],
      ['Funding Fee', fmt(vaFee), '$0', '-'+fmt(vaFee)],
      ['Monthly P+I', fmt(vaPi)+'/mo', fmt(cvPi)+'/mo', fmt(Math.abs(vaPi-cvPi))+'/mo'],
      ['Monthly PMI', '$0', fmt(pmi)+'/mo', fmt(pmi)+'/mo saved'],
      ['Total Monthly', fmt(vaPi)+'/mo', fmt(cvPi+pmi)+'/mo', fmt(Math.abs(vaPi-(cvPi+pmi)))+'/mo'],
      ['Total Interest', fmt(vaPi*term*12-vaLoan), fmt(cvPi*term*12-baseLoan), '—'],
      ['PMI Total Cost', '$0', fmt(cvPmiTotal), fmt(cvPmiTotal)+' saved'],
      ['TOTAL COST', fmt(vaTotal), fmt(cvTotal), fmt(Math.abs(vaTotal-cvTotal))+(vaTotal<cvTotal?' VA WINS':'')],
    ],
    theme:'grid',
    headStyles:{fillColor:[10,15,30],textColor:[0,200,150],fontSize:9,fontStyle:'bold'},
    columnStyles:{0:{fontStyle:'bold'},3:{textColor:[0,150,100],fontStyle:'bold'}},
    styles:{fontSize:9,cellPadding:3.5},
    margin:{left:14,right:14},
  });

  pdfFooter(doc);
  doc.save('VA-vs-Conventional-'+new Date().toISOString().slice(0,10)+'.pdf');
}

function pdfAmort() {
  const {jsPDF} = window.jspdf;
  const doc = new jsPDF();
  let y = pdfHeader(doc, 'VA Loan — Amortization Schedule (Yearly Summary)');

  const loan  = +$('a_loan').value||357525;
  const rate  = +$('a_rate').value||6.75;
  const yrs   = +$('a_term').value||30;
  const extra = +$('a_extra').value||0;

  const r = rate/100/12;
  const basePmt = r ? loan*r*Math.pow(1+r,yrs*12)/(Math.pow(1+r,yrs*12)-1) : loan/(yrs*12);
  const monthly = basePmt + extra;

  let bal=loan, rows=[], cumInt=0;
  for(let yr=1;yr<=yrs;yr++){
    let yrP=0,yrI=0;
    for(let m=0;m<12;m++){
      if(bal<=0) break;
      const i=bal*r, p=Math.min(monthly-i,bal);
      bal=Math.max(bal-p,0); yrP+=p; yrI+=i; cumInt+=i;
    }
    rows.push(['Year '+yr, fmt(monthly)+'/mo', fmt(yrP), fmt(yrI), fmt(Math.max(bal,0)), fmt(loan-Math.max(bal,0))]);
    if(bal<=0) break;
  }

  doc.autoTable({
    startY:y,
    head:[['Year','Payment/mo','Principal Paid','Interest Paid','Balance','Equity']],
    body:rows,
    theme:'striped',
    headStyles:{fillColor:[10,15,30],textColor:[0,200,150],fontSize:8,fontStyle:'bold'},
    styles:{fontSize:8,cellPadding:2.5},
    columnStyles:{0:{fontStyle:'bold'},2:{textColor:[0,120,80]},3:{textColor:[180,50,50]}},
    margin:{left:14,right:14},
  });

  pdfFooter(doc);
  doc.save('VA-Loan-Amortization-'+new Date().toISOString().slice(0,10)+'.pdf');
}

// ═══════ INIT ═══════
Q(); P(); A();
</script>
<?php if ( ! $is_wp ) { ?>
</body>
</html>
<?php } ?>
