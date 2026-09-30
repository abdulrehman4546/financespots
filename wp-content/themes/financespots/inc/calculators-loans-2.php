<?php
/**
 * Loan calculators (v2), part 2: debt consolidation, loan comparison,
 * amortization, refinance, FHA. Helpers come from calculators-loans.php.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

/* ─────────────────────────────────────────────
   6. DEBT CONSOLIDATION CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_debt_consolidation() { ?>
<div class="fsc-wrap" id="fs-dc">
  <h3 class="fsc-section-title">Your current debts</h3>
  <div class="fsl-debt-head" aria-hidden="true"><span>Name</span><span>Balance ($)</span><span>APR (%)</span><span>Min. payment ($)</span><span></span></div>
  <div id="dc-debts"></div>
  <button type="button" class="fsl-linkbtn" id="dc-add">+ Add another debt</button>

  <h3 class="fsc-section-title" style="margin-top:1.5rem">Consolidation loan offer</h3>
  <div class="fsl-cols3">
    <?php fsl_field( 'dc-rate', 'Loan APR (%)', 11.0, [ 'min' => 0, 'max' => 40, 'step' => 0.1 ] ); ?>
    <?php fsl_select( 'dc-term', 'Loan term', [ 24 => '24 months', 36 => '36 months', 48 => '48 months', 60 => '60 months', 72 => '72 months', 84 => '84 months' ], 60 ); ?>
    <?php fsl_field( 'dc-fee', 'Origination fee (%)', 3, [ 'min' => 0, 'max' => 12, 'step' => 0.25 ] ); ?>
  </div>
  <?php fsl_check( 'dc-keep', 'Keep paying what I pay today (my current total payment) toward the new loan', false ); ?>
  <button type="button" class="fsc-btn" id="dc-go" style="margin-top:1rem">Compare</button>
  <p class="fsl-error" id="dc-err" role="alert" style="display:none"></p>

  <div class="fsc-results" id="dc-results" style="display:none;margin-top:1.5rem" aria-live="polite">
    <?php
    fsl_card( 'dc-verdict', 'Net savings with consolidation', 'primary' );
    fsl_card( 'dc-old-pay', 'Current total monthly payment' );
    fsl_card( 'dc-new-pay', 'New monthly payment' );
    fsl_card( 'dc-old-cost', 'Interest if you keep your debts' );
    fsl_card( 'dc-new-cost', 'Interest + fee with new loan' );
    fsl_card( 'dc-old-time', 'Time to be debt-free (now)' );
    fsl_card( 'dc-new-time', 'Time to be debt-free (new loan)' );
    fsl_card( 'dc-wapr', 'Average APR of your debts' );
    fsl_card( 'dc-napr', 'True APR of new loan (with fee)', 'secondary' );
    fsl_card( 'dc-total', 'Total debt consolidated' );
    ?>
  </div>
  <p class="fsl-note" id="dc-note"></p>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money, list=FSL.el('dc-debts'), MAXD=10;
  var defaults=[['Credit card 1',8000,22.9,240],['Credit card 2',5500,19.99,165],['Medical bill',3000,0,100]];
  function row(d){
    var div=document.createElement('div'); div.className='fsl-debt-row';
    div.innerHTML='<input type="text" class="dc-name" aria-label="Debt name" value="'+(d?d[0]:'')+'" placeholder="Debt name">'+
      '<input type="number" class="dc-bal" aria-label="Balance" placeholder="Balance $" inputmode="decimal" min="0" step="100" value="'+(d?d[1]:'')+'">'+
      '<input type="number" class="dc-apr" aria-label="APR" placeholder="APR %" inputmode="decimal" min="0" max="60" step="0.01" value="'+(d?d[2]:'')+'">'+
      '<input type="number" class="dc-min" aria-label="Minimum payment" placeholder="Min. payment $" inputmode="decimal" min="0" step="5" value="'+(d?d[3]:'')+'">'+
      '<button type="button" class="fsl-x" aria-label="Remove debt">&times;</button>';
    div.querySelector('.fsl-x').addEventListener('click',function(){ if(list.children.length>1){list.removeChild(div);calc();} });
    list.appendChild(div);
  }
  defaults.forEach(row);
  FSL.el('dc-add').addEventListener('click',function(){ if(list.children.length<MAXD)row(null); else FSL.valid(false,'dc-err','You can add up to '+MAXD+' debts.'); });

  /* pay each debt its own minimum until it is gone; report interest + months (cap 50 yrs) */
  function payoff(b,apr,min){
    var r=apr/1200, bal=b, int=0, m=0, stuck=false;
    if(b<=0)return {int:0,months:0,stuck:false};
    if(min<=b*r+0.005){stuck=true;}
    while(bal>0.005&&m<600){ var i=bal*r; int+=i; var p=Math.min(min-i,bal); if(p<=0){stuck=true;break;} bal-=p; m++; }
    if(bal>0.005)stuck=true;
    return {int:int,months:m,stuck:stuck};
  }
  function calc(){
    var debts=[],tot=0,minTot=0,wsum=0,oldInt=0,oldM=0,stuck=false;
    [].forEach.call(list.children,function(r){
      var b=parseFloat(r.querySelector('.dc-bal').value)||0, a=parseFloat(r.querySelector('.dc-apr').value)||0, m=parseFloat(r.querySelector('.dc-min').value)||0;
      if(b<=0)return; debts.push({b:b,a:a,m:m}); tot+=b; minTot+=m; wsum+=b*a;
      var p=payoff(b,a,m); oldInt+=p.int; oldM=Math.max(oldM,p.months); if(p.stuck)stuck=true;
    });
    if(!FSL.valid(tot>0,'dc-err','Enter at least one debt with a balance.'))return;
    var rate=$('dc-rate'), n=parseInt(FSL.el('dc-term').value,10), feePct=$('dc-fee')/100;
    var L=tot/(1-feePct), fee=L-tot;
    var keep=FSL.el('dc-keep').checked&&minTot>0, pay=FSL.pmt(L,rate/1200,n);
    var s=FSL.schedule({P:L,apr:rate,n:n,payment:keep?Math.max(pay,minTot):pay});
    var newCost=s.totalInterest+fee, save=oldInt-newCost;
    var napr=FSL.apr(tot,s.rows.map(function(x){return x.pay;}));
    FSL.set('dc-verdict',(save>=0?'Save ':'Lose ')+M(Math.abs(save)));
    FSL.set('dc-old-pay',M(minTot)+'/mo');
    FSL.set('dc-new-pay',M(keep?Math.max(pay,minTot):pay)+'/mo');
    FSL.set('dc-old-cost',stuck?'50+ years of interest (min. payments too low)':M(oldInt));
    FSL.set('dc-new-cost',M(newCost)+' ('+M(fee)+' fee)');
    FSL.set('dc-old-time',stuck?'Never at these payments':FSL.monthsText(oldM));
    FSL.set('dc-new-time',FSL.monthsText(s.months));
    FSL.set('dc-wapr',FSL.pct(wsum/tot,2));
    FSL.set('dc-napr',FSL.pct(napr,2));
    FSL.set('dc-total',M(tot)+' across '+debts.length+' debt'+(debts.length>1?'s':''));
    FSL.show('dc-results','grid');
    var msg;
    if(stuck)msg='At least one debt will not pay off at its current minimum payment, so a fixed-term loan may help, but confirm the payment still fits your budget.';
    else if(save>0)msg='Consolidating saves about '+M(save)+' in interest and fees'+(s.months>oldM?', but it stretches repayment by '+FSL.monthsText(s.months-oldM)+'.':'.');
    else msg='This offer costs about '+M(-save)+' more than paying your debts as they are. Try a shorter term, a lower APR, or no fee.';
    if(feePct>0)msg+=' The '+(feePct*100).toFixed(2).replace(/\.?0+$/,'')+'% fee raises the loan APR to '+FSL.pct(napr,2)+'.';
    FSL.set('dc-note',msg);
  }
  FSL.el('dc-go').addEventListener('click',calc); FSL.bind('fs-dc',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   7. LOAN COMPARISON CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_loan_compare() {
    $defaults = [
        'a' => [ 20000, 7.5, 60, 0 ],
        'b' => [ 20000, 6.9, 60, 600 ],
        'c' => [ 20000, 8.2, 48, 0 ],
    ];
    ?>
<div class="fsc-wrap" id="fs-lc">
  <div class="fsl-cols3">
    <?php foreach ( $defaults as $k => $d ) : $K = strtoupper( $k ); ?>
    <div class="fsl-loancol" id="lc-col-<?php echo esc_attr( $k ); ?>">
      <h3 class="fsc-section-title">Loan <?php echo esc_html( $K ); ?><?php if ( 'c' === $k ) echo ' <label class="fsl-inline"><input type="checkbox" id="lc-use-c"> include</label>'; ?></h3>
      <?php
      fsl_field( "lc-$k-amt", 'Amount ($)', $d[0], [ 'min' => 100, 'step' => 500 ] );
      fsl_field( "lc-$k-rate", 'Interest rate (%)', $d[1], [ 'min' => 0, 'max' => 40, 'step' => 0.05 ] );
      fsl_field( "lc-$k-term", 'Term (months)', $d[2], [ 'min' => 1, 'max' => 480, 'step' => 1 ] );
      fsl_field( "lc-$k-fee", 'Upfront fees ($)', $d[3], [ 'min' => 0, 'step' => 50 ] );
      ?>
    </div>
    <?php endforeach; ?>
  </div>
  <button type="button" class="fsc-btn" id="lc-go" style="margin-top:1rem">Compare loans</button>
  <p class="fsl-error" id="lc-err" role="alert" style="display:none"></p>

  <div id="lc-results" style="display:none;margin-top:1.5rem" aria-live="polite">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Best overall</div><div class="fsc-result-value" id="lc-best">--</div></div>
    <div class="fsc-table-wrap" style="padding:1rem 0 0"><table class="fsc-table fsl-cmp" id="lc-table"><thead></thead><tbody></tbody></table></div>
    <p class="fsl-note" id="lc-note"></p>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var keys=['a','b'].concat(FSL.el('lc-use-c').checked?['c']:[]);
    FSL.el('lc-col-c').classList.toggle('is-off',!FSL.el('lc-use-c').checked);
    var L=keys.map(function(k){
      var P=$('lc-'+k+'-amt'), r=$('lc-'+k+'-rate'), n=Math.max(1,Math.round($('lc-'+k+'-term'))), fee=$('lc-'+k+'-fee');
      var s=FSL.schedule({P:P,apr:r,n:n});
      var apr=P-fee>0?FSL.apr(P-fee,s.rows.map(function(x){return x.pay;})):NaN;
      return {k:k.toUpperCase(),P:P,r:r,n:n,fee:fee,pay:s.payment,int:s.totalInterest,cost:s.totalInterest+fee,apr:apr};
    });
    if(!FSL.valid(L.every(function(x){return x.P>0;}),'lc-err','Enter a loan amount for every loan.'))return;
    var best=L.reduce(function(a,b){return b.cost<a.cost?b:a;}), lowPay=L.reduce(function(a,b){return b.pay<a.pay?b:a;});
    function row(label,fn,hi){ return '<tr><th scope="row">'+label+'</th>'+L.map(function(x){return '<td'+(hi&&hi(x)?' class="is-best"':'')+'>'+fn(x)+'</td>';}).join('')+'</tr>'; }
    FSL.el('lc-table').querySelector('thead').innerHTML='<tr><th></th>'+L.map(function(x){return '<th>Loan '+x.k+'</th>';}).join('')+'</tr>';
    FSL.el('lc-table').querySelector('tbody').innerHTML=
      row('Monthly payment',function(x){return M(x.pay,2);},function(x){return x===lowPay;})+
      row('Interest rate',function(x){return x.r.toFixed(2)+'%';})+
      row('True APR (with fees)',function(x){return FSL.pct(x.apr,2);},function(x){return x.apr===Math.min.apply(null,L.map(function(y){return y.apr;}));})+
      row('Term',function(x){return FSL.monthsText(x.n);})+
      row('Total interest',function(x){return M(x.int);})+
      row('Upfront fees',function(x){return M(x.fee);})+
      row('Total cost of borrowing',function(x){return M(x.cost);},function(x){return x===best;});
    FSL.set('lc-best','Loan '+best.k+' ('+M(best.cost)+' in interest and fees)');
    var others=L.filter(function(x){return x!==best;}).sort(function(a,b){return a.cost-b.cost;});
    var note='Loan '+best.k+' is cheapest over the full term, saving '+M(others[0].cost-best.cost)+' versus Loan '+others[0].k+'.';
    if(lowPay!==best)note+=' Loan '+lowPay.k+' has the lowest monthly payment ('+M(lowPay.pay,2)+'), but costs '+M(lowPay.cost-best.cost)+' more in total.';
    FSL.set('lc-note',note);
    FSL.show('lc-results','block');
  }
  FSL.el('lc-go').addEventListener('click',calc); FSL.bind('fs-lc',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   8. AMORTIZATION CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_amortization() { ?>
<div class="fsc-wrap" id="fs-am">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'am-amount', 'Loan amount ($)', 250000, [ 'min' => 100, 'step' => 1000 ] ); ?>
    <?php fsl_field( 'am-rate', 'Interest rate (% APR)', 7.0, [ 'min' => 0, 'max' => 40, 'step' => 0.05 ] ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'am-years', 'Term: years', 30, [ 'min' => 0, 'max' => 50, 'step' => 1 ] ); ?>
      <?php fsl_field( 'am-months', 'and months', 0, [ 'min' => 0, 'max' => 11, 'step' => 1 ] ); ?>
    </div>
    <?php fsl_field( 'am-start', 'First payment month', '', [ 'type' => 'month' ] ); ?>
    <h3 class="fsc-section-title">Extra payments (optional)</h3>
    <?php fsl_field( 'am-extra', 'Extra every month ($)', 0, [ 'min' => 0, 'step' => 25 ] ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'am-yextra', 'Extra once a year ($)', 0, [ 'min' => 0, 'step' => 100 ] ); ?>
      <?php fsl_select( 'am-ymonth', 'in month', [ 1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December' ], 12 ); ?>
    </div>
    <div class="fsl-row2">
      <?php fsl_field( 'am-lump', 'One-time extra ($)', 0, [ 'min' => 0, 'step' => 500 ] ); ?>
      <?php fsl_field( 'am-lumpm', 'in payment #', 12, [ 'min' => 1, 'step' => 1 ] ); ?>
    </div>
    <button type="button" class="fsc-btn" id="am-go">Generate schedule</button>
    <p class="fsl-error" id="am-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="am-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'am-monthly', 'Monthly payment', 'primary' );
      fsl_card( 'am-total', 'Total of all payments' );
      fsl_card( 'am-interest', 'Total interest' );
      fsl_card( 'am-payoff', 'Payoff date' );
      fsl_card( 'am-saved', 'Interest saved by extras', 'gold' );
      fsl_card( 'am-time', 'Time saved', 'gold' );
      fsl_card( 'am-ratio', 'Interest as % of loan' );
      fsl_card( 'am-half', 'Payment # when half is repaid' );
      ?>
    </div>
    <p class="fsl-note" id="am-note"></p>
  </div>
</div>
<div style="margin-top:1.5rem" class="fsl-chart"><canvas id="am-chart" height="200" aria-label="Loan balance over time chart" role="img"></canvas></div>
<div id="am-sched" style="display:none;margin-top:1.5rem"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  FSL.el('am-start').value=FSL.defaultStartValue();
  function calc(){
    var P=$('am-amount'), rate=$('am-rate'), n=Math.round($('am-years')*12+$('am-months')), start=FSL.startDate('am-start');
    if(!FSL.valid(P>0&&n>0,'am-err','Enter a loan amount and a term of at least one month.'))return;
    var extra=$('am-extra'), ye=$('am-yextra'), ym=parseInt(FSL.el('am-ymonth').value,10), lump=$('am-lump'), lm=Math.max(1,Math.round($('am-lumpm',12)));
    var has=extra>0||ye>0||lump>0;
    var base=FSL.schedule({P:P,apr:rate,n:n,start:start});
    var plan=has?FSL.schedule({P:P,apr:rate,n:n,start:start,extra:extra,yearlyExtra:{month:ym,amount:ye},lumps:[{m:lm,amount:lump}]}):base;
    var half=0; for(var i=0;i<plan.rows.length;i++){ if(plan.rows[i].bal<=P/2){half=plan.rows[i].m;break;} }
    FSL.set('am-monthly',M(base.payment,2)+'/mo'+(extra>0?' + '+M(extra):''));
    FSL.set('am-total',M(plan.totalPaid));
    FSL.set('am-interest',M(plan.totalInterest));
    FSL.set('am-payoff',FSL.dateText(plan.payoffDate)+' ('+FSL.monthsText(plan.months)+')');
    FSL.set('am-saved',has?M(base.totalInterest-plan.totalInterest):'Add an extra payment');
    FSL.set('am-time',has?FSL.monthsText(Math.max(base.months-plan.months,0)):'--');
    FSL.set('am-ratio',(plan.totalInterest/P*100).toFixed(1)+'%');
    FSL.set('am-half','#'+half+' ('+FSL.monthsText(half)+')');
    FSL.show('am-results','grid');
    var r1=plan.rows[0];
    FSL.set('am-note','In your first payment, '+M(r1.int,2)+' goes to interest and only '+M(r1.princ,2)+' reduces the balance. Early payments are mostly interest; that shifts over time.');
    var series=[{label:has?'With extra payments':'Balance',rows:plan.rows,start:P}];
    if(has)series.push({label:'Standard schedule',rows:base.rows,start:P});
    FSL.balanceChart('am-chart',null,series);
    FSL.renderSchedule('am-sched',plan,{filename:'amortization-schedule'});
  }
  FSL.el('am-go').addEventListener('click',calc); FSL.bind('fs-am',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   9. REFINANCE CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_refinance() { ?>
<div class="fsc-wrap" id="fs-rf">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <h3 class="fsc-section-title">Current loan</h3>
    <?php fsl_field( 'rf-bal', 'Remaining balance ($)', 250000, [ 'min' => 1000, 'step' => 1000 ] ); ?>
    <?php fsl_field( 'rf-rate0', 'Current interest rate (%)', 7.25, [ 'min' => 0, 'max' => 30, 'step' => 0.05 ] ); ?>
    <?php fsl_field( 'rf-rem', 'Years remaining', 27, [ 'min' => 0.5, 'max' => 40, 'step' => 0.5 ] ); ?>
    <?php fsl_field( 'rf-pay0', 'Current monthly P&I ($, optional)', '', [ 'min' => 0, 'step' => 10, 'hint' => 'Leave blank to calculate it from the balance, rate and years left.' ] ); ?>
    <h3 class="fsc-section-title">New loan</h3>
    <?php fsl_field( 'rf-rate1', 'New interest rate (%)', 6.0, [ 'min' => 0, 'max' => 30, 'step' => 0.05 ] ); ?>
    <?php fsl_select( 'rf-term1', 'New loan term', [ 30 => '30 years', 25 => '25 years', 20 => '20 years', 15 => '15 years', 10 => '10 years' ], 30 ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'rf-points', 'Points (%)', 0, [ 'min' => 0, 'max' => 5, 'step' => 0.125 ] ); ?>
      <?php fsl_field( 'rf-closing', 'Closing costs ($)', 4500, [ 'min' => 0, 'step' => 100, 'hint' => 'Typically 2–5% of the loan.' ] ); ?>
    </div>
    <?php fsl_field( 'rf-cash', 'Cash out ($, optional)', 0, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <?php fsl_check( 'rf-roll', 'Roll closing costs and points into the new loan', false ); ?>
    <?php fsl_field( 'rf-horizon', 'How long will you keep the loan? (years)', 7, [ 'min' => 1, 'max' => 40, 'step' => 1 ] ); ?>
    <button type="button" class="fsc-btn" id="rf-go">Calculate savings</button>
    <p class="fsl-error" id="rf-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="rf-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'rf-net', 'Net savings at your horizon', 'primary' );
      fsl_card( 'rf-change', 'Monthly payment change' );
      fsl_card( 'rf-new', 'New monthly payment' );
      fsl_card( 'rf-old', 'Current monthly payment' );
      fsl_card( 'rf-break', 'Break-even point', 'gold' );
      fsl_card( 'rf-up', 'Upfront cost (closing + points)' );
      fsl_card( 'rf-newloan', 'New loan amount' );
      fsl_card( 'rf-life', 'Interest: current vs new (full term)' );
      ?>
    </div>
    <p class="fsl-note" id="rf-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function balAt(s,m){ if(m<=0)return s.rows.length?s.rows[0].bal+s.rows[0].princ:0; return m>=s.rows.length?0:s.rows[m-1].bal; }
  function paidTo(s,m){ var t=0; for(var i=0;i<Math.min(m,s.rows.length);i++)t+=s.rows[i].pay; return t; }
  function calc(){
    var B=$('rf-bal'), r0=$('rf-rate0'), n0=Math.max(1,Math.round($('rf-rem')*12)), r1=$('rf-rate1'), n1=parseInt(FSL.el('rf-term1').value,10)*12;
    var pts=$('rf-points')/100, cc=$('rf-closing'), cash=$('rf-cash'), roll=FSL.el('rf-roll').checked, H=Math.max(1,Math.round($('rf-horizon')*12));
    if(!FSL.valid(B>0,'rf-err','Enter your remaining loan balance.'))return;
    var ptsCost=pts*(B+cash), fees=cc+ptsCost, L=B+cash+(roll?fees:0), upfront=roll?0:fees;
    var pay0=$('rf-pay0')>0?$('rf-pay0'):FSL.pmt(B,r0/1200,n0);
    var oldS=FSL.schedule({P:B,apr:r0,n:n0,payment:pay0}), newS=FSL.schedule({P:L,apr:r1,n:n1});
    var pay1=newS.payment, change=pay0-pay1;
    var h=Math.min(H,Math.max(oldS.months,newS.months));
    var oldCost=paidTo(oldS,h)+balAt(oldS,h), newCost=upfront+paidTo(newS,h)+balAt(newS,h)-cash, net=oldCost-newCost;
    /* break-even: first month where cumulative cost of new loan drops below old (cash-out cases skip) */
    var be=null;
    if(cash===0){ for(var m=1;m<=Math.max(oldS.months,newS.months);m++){ if(upfront+paidTo(newS,m)+balAt(newS,m)<=paidTo(oldS,m)+balAt(oldS,m)){be=m;break;} } }
    FSL.set('rf-net',(net>=0?'+':'-')+M(Math.abs(net))+' after '+FSL.monthsText(h));
    FSL.set('rf-change',(change>=0?'Save ':'Pay ')+M(Math.abs(change),2)+'/mo');
    FSL.set('rf-new',M(pay1,2)+'/mo');
    FSL.set('rf-old',M(pay0,2)+'/mo');
    FSL.set('rf-break',cash>0?'Not applicable with cash-out':(be?FSL.monthsText(be):'Never at these terms'));
    FSL.set('rf-up',M(fees)+(roll?' (rolled into loan)':''));
    FSL.set('rf-newloan',M(L));
    FSL.set('rf-life',M(oldS.totalInterest)+' vs '+M(newS.totalInterest));
    FSL.show('rf-results','grid');
    var note=net>0?'Refinancing comes out ahead if you keep the loan at least '+(be?FSL.monthsText(be):'that long')+'.':'At these terms refinancing costs more than it saves over '+FSL.monthsText(h)+'.';
    if(n1>oldS.months)note+=' The new loan runs '+FSL.monthsText(n1-oldS.months)+' longer, so a lower payment can still mean more total interest.';
    FSL.set('rf-note',note);
  }
  FSL.el('rf-go').addEventListener('click',calc); FSL.bind('fs-rf',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   10. FHA LOAN CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_fha_loan() { ?>
<div class="fsc-wrap" id="fs-fha">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'fha-price', 'Home price ($)', 350000, [ 'min' => 50000, 'step' => 5000 ] ); ?>
    <?php fsl_field( 'fha-dpct', 'Down payment (%)', 3.5, [ 'min' => 3.5, 'max' => 100, 'step' => 0.5, 'hint' => 'FHA minimum is 3.5% with a 580+ credit score, 10% for 500–579.' ] ); ?>
    <?php fsl_select( 'fha-credit', 'Credit score', [ 'ok' => '580 or higher', 'low' => '500–579' ], 'ok' ); ?>
    <?php fsl_field( 'fha-rate', 'Interest rate (%)', 6.75, [ 'min' => 0, 'max' => 20, 'step' => 0.05 ] ); ?>
    <?php fsl_select( 'fha-term', 'Loan term', [ 30 => '30 years', 25 => '25 years', 20 => '20 years', 15 => '15 years' ], 30 ); ?>
    <?php fsl_field( 'fha-limit', 'FHA loan limit for your county ($)', 541287, [ 'min' => 100000, 'step' => 1000, 'hint' => '2026 national floor is $541,287 and the ceiling is $1,249,125. <a href="https://entp.hud.gov/idapp/html/hicostlook.cfm" rel="noopener" target="_blank">Look up your county</a>.' ] ); ?>
    <?php fsl_field( 'fha-tax', 'Property tax ($ per year)', 4200, [ 'min' => 0, 'step' => 100 ] ); ?>
    <?php fsl_field( 'fha-ins', 'Homeowners insurance ($ per year)', 1800, [ 'min' => 0, 'step' => 50 ] ); ?>
    <?php fsl_field( 'fha-hoa', 'HOA dues ($ per month)', 0, [ 'min' => 0, 'step' => 10 ] ); ?>
    <?php fsl_field( 'fha-start', 'First payment month', '', [ 'type' => 'month' ] ); ?>
    <?php fsl_check( 'fha-finance', 'Finance the 1.75% upfront MIP into the loan', true ); ?>
    <?php fsl_field( 'fha-extra', 'Extra monthly payment ($, optional)', 0, [ 'min' => 0, 'step' => 25 ] ); ?>
    <button type="button" class="fsc-btn" id="fha-go">Calculate FHA payment</button>
    <p class="fsl-error" id="fha-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="fha-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'fha-total', 'Total monthly payment', 'primary' );
      fsl_card( 'fha-pi', 'Principal &amp; interest' );
      fsl_card( 'fha-mip', 'Monthly MIP (first year)' );
      fsl_card( 'fha-ufmip', 'Upfront MIP (1.75%)' );
      fsl_card( 'fha-loan', 'Loan amount' );
      fsl_card( 'fha-dp', 'Down payment' );
      fsl_card( 'fha-dur', 'MIP is paid for', 'secondary' );
      fsl_card( 'fha-mip-total', 'Total MIP paid (annual + upfront)' );
      fsl_card( 'fha-int', 'Total interest' );
      fsl_card( 'fha-close', 'Est. cash to close (down + ~3% costs)' );
      fsl_card( 'fha-income', 'Income needed (31% housing ratio)' );
      fsl_card( 'fha-payoff', 'Payoff date' );
      ?>
    </div>
    <p class="fsl-note" id="fha-note"></p>
  </div>
</div>
<div id="fha-compare" style="display:none;margin-top:1.5rem">
  <h3 class="fsl-h3">How your down payment changes the cost</h3>
  <div class="fsc-table-wrap" style="padding:0"><table class="fsc-table fsl-cmp"><thead><tr><th>Down payment</th><th>Annual MIP rate</th><th>MIP paid for</th><th>Monthly payment</th></tr></thead><tbody id="fha-cmp-body"></tbody></table></div>
</div>
<div id="fha-sched" style="display:none;margin-top:1.5rem"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  FSL.el('fha-start').value=FSL.defaultStartValue();
  /* HUD Mortgagee Letter 2023-05 annual MIP (effective March 20, 2023) */
  function mipRate(base,ltv,years){
    var big=base>726200;
    if(years>15){ if(!big)return ltv>95?0.55:0.50; return ltv>95?0.75:0.70; }
    if(!big)return ltv>90?0.40:0.15;
    return ltv>90?0.65:(ltv>78?0.40:0.15);
  }
  function mipMonths(ltv,years){ return ltv>90?years*12:Math.min(132,years*12); }
  function build(price,dpct,rate,years,fin,extra,start,tax,ins,hoa){
    var down=price*dpct/100, base=price-down, ltv=base/price*100, uf=base*0.0175, L=base+(fin?uf:0);
    var rt=mipRate(base,ltv,years), months=mipMonths(ltv,years), share=base/L, yb=L;
    var side=function(m,bal){ if(m>months)return 0; if((m-1)%12===0)yb=bal; return yb*share*rt/1200; };
    var s=FSL.schedule({P:L,apr:rate,n:years*12,start:start,extra:extra,side:side});
    return {down:down,base:base,ltv:ltv,uf:uf,L:L,rate:rt,months:months,s:s,
            total:s.payment+(s.rows[0]?s.rows[0].side:0)+tax/12+ins/12+hoa+extra};
  }
  function calc(){
    var price=$('fha-price'), dpct=$('fha-dpct'), low=FSL.el('fha-credit').value==='low', rate=$('fha-rate'), years=parseInt(FSL.el('fha-term').value,10);
    var limit=$('fha-limit'), tax=$('fha-tax'), ins=$('fha-ins'), hoa=$('fha-hoa'), extra=$('fha-extra'), fin=FSL.el('fha-finance').checked, start=FSL.startDate('fha-start');
    var minDown=low?10:3.5;
    if(!FSL.valid(price>0&&dpct>=0&&dpct<100,'fha-err','Enter a home price and a down payment under 100%.'))return;
    var f=build(price,Math.max(dpct,0),rate,years,fin,extra,start,tax,ins,hoa);
    var mipTot=f.s.totalSide+f.uf;
    FSL.set('fha-total',M(f.total)+'/mo');
    FSL.set('fha-pi',M(f.s.payment)+'/mo'+(extra>0?' + '+M(extra):''));
    FSL.set('fha-mip',M(f.s.rows[0].side)+'/mo ('+f.rate.toFixed(2)+'% per year)');
    FSL.set('fha-ufmip',M(f.uf)+(fin?' (financed)':' (paid at closing)'));
    FSL.set('fha-loan',M(f.L)+' ('+f.ltv.toFixed(1)+'% LTV)');
    FSL.set('fha-dp',M(f.down));
    FSL.set('fha-dur',f.ltv>90?'the life of the loan':'11 years');
    FSL.set('fha-mip-total',M(mipTot));
    FSL.set('fha-int',M(f.s.totalInterest));
    FSL.set('fha-close',M(f.down+price*0.03+(fin?0:f.uf)));
    FSL.set('fha-income',M(f.total/0.31*12)+'/yr gross');
    FSL.set('fha-payoff',FSL.dateText(f.s.payoffDate)+' ('+FSL.monthsText(f.s.months)+')');
    FSL.show('fha-results','grid'); FSL.show('fha-compare');
    var notes=[];
    if(dpct<minDown-1e-9)notes.push('FHA requires at least '+minDown+'% down for this credit score.');
    if(f.base>limit)notes.push('Your loan ('+M(f.base)+') is above the FHA limit you entered ('+M(limit)+'). Increase the down payment or choose a different loan type.');
    if(f.ltv>90)notes.push('With under 10% down, mortgage insurance lasts for the life of the loan. Refinancing into a conventional loan later is the usual way to drop it.');
    else notes.push('With 10% or more down, annual MIP drops off after 11 years. Unlike PMI on a conventional loan, FHA mortgage insurance is required even with 20% down.');
    FSL.set('fha-note',notes.join(' '));
    var body='';
    [3.5,5,10,20].forEach(function(d){ if(d<minDown)return; var x=build(price,d,rate,years,fin,0,start,tax,ins,hoa);
      body+='<tr><td><strong>'+d+'%</strong> ('+M(x.down)+')</td><td>'+x.rate.toFixed(2)+'%</td><td>'+(x.ltv>90?'Life of loan':'11 years')+'</td><td>'+M(x.total-extra)+'</td></tr>'; });
    FSL.el('fha-cmp-body').innerHTML=body;
    FSL.renderSchedule('fha-sched',f.s,{sideLabel:'MIP',filename:'fha-amortization-schedule'});
  }
  FSL.el('fha-go').addEventListener('click',calc); FSL.bind('fs-fha',calc); calc();
});
</script>
<?php }
