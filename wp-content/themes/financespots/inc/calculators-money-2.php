<?php
/**
 * Calculators (v2), batch 3: loan payoff, loan affordability, interest-only, balloon, bridge,
 * commercial loan (DSCR), debt-to-income, self-employment tax, monthly budget planner, dividends.
 * Dispatched by tool slug (see fs_render_calculator). Uses helpers from calculators-loans.php
 * and window.FSL / window.FSTAX.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

/* ─────────────────────────────────────────────
   1. LOAN PAYOFF CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_loan_payoff() { ?>
<div class="fsc-wrap" id="fs-lp">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'lp-bal', 'Current loan balance ($)', 185000, [ 'min' => 100, 'step' => 1000 ] ); ?>
    <?php fsl_field( 'lp-rate', 'Interest rate (% APR)', 6.75, [ 'min' => 0, 'max' => 40, 'step' => 0.05 ] ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'lp-yrs', 'Time left: years', 25, [ 'min' => 0, 'max' => 50, 'step' => 1 ] ); ?>
      <?php fsl_field( 'lp-mos', 'and months', 0, [ 'min' => 0, 'max' => 11, 'step' => 1 ] ); ?>
    </div>
    <?php fsl_field( 'lp-extra', 'Extra monthly payment ($)', 200, [ 'min' => 0, 'step' => 25 ] ); ?>
    <?php fsl_field( 'lp-lump', 'One-time lump sum now ($)', 0, [ 'min' => 0, 'step' => 500 ] ); ?>
    <?php fsl_field( 'lp-start', 'Next payment month', '', [ 'type' => 'month' ] ); ?>
    <button type="button" class="fsc-btn" id="lp-go">Calculate payoff</button>
    <p class="fsl-error" id="lp-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="lp-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'lp-time', 'Time saved', 'primary' );
      fsl_card( 'lp-int', 'Interest saved', 'gold' );
      fsl_card( 'lp-date', 'New payoff date' );
      fsl_card( 'lp-old-date', 'Original payoff date' );
      fsl_card( 'lp-pay', 'Regular monthly payment' );
      fsl_card( 'lp-total-new', 'Total interest with extras' );
      fsl_card( 'lp-total-old', 'Total interest without extras' );
      fsl_card( 'lp-roi', 'Return on your extra money', 'secondary' );
      ?>
    </div>
    <p class="fsl-note" id="lp-note"></p>
  </div>
</div>
<div style="margin-top:1.5rem" class="fsl-chart"><canvas id="lp-chart" height="200" aria-label="Loan balance chart" role="img"></canvas></div>
<div id="lp-sched" style="display:none;margin-top:1.5rem"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  FSL.el('lp-start').value=FSL.defaultStartValue();
  function calc(){
    var B=$('lp-bal'), rate=$('lp-rate'), n=Math.round($('lp-yrs')*12+$('lp-mos')), ex=$('lp-extra'), lump=$('lp-lump'), start=FSL.startDate('lp-start');
    if(!FSL.valid(B>0&&n>0,'lp-err','Enter a balance and the time left on the loan.'))return;
    var base=FSL.schedule({P:B,apr:rate,n:n,start:start});
    var plan=FSL.schedule({P:B,apr:rate,n:n,start:start,extra:ex,lumps:[{m:1,amount:lump}]});
    var saved=base.totalInterest-plan.totalInterest, outlay=ex*plan.months+lump;
    FSL.set('lp-time',FSL.monthsText(Math.max(base.months-plan.months,0))); FSL.set('lp-int',M(saved));
    FSL.set('lp-date',FSL.dateText(plan.payoffDate)); FSL.set('lp-old-date',FSL.dateText(base.payoffDate));
    FSL.set('lp-pay',M(base.payment,2)+'/mo'); FSL.set('lp-total-new',M(plan.totalInterest)); FSL.set('lp-total-old',M(base.totalInterest));
    FSL.set('lp-roi',outlay>0?FSL.pct(saved/outlay*100,0)+' of the extra money comes back as interest saved':'Add an extra payment');
    FSL.show('lp-results','grid');
    FSL.set('lp-note',outlay>0?'Putting '+M(ex)+' a month'+(lump>0?' plus '+M(lump)+' now':'')+' toward principal pays the loan off '+FSL.monthsText(Math.max(base.months-plan.months,0))+' sooner and saves '+M(saved)+' in interest. Tell your lender the extra should be applied to principal.':'Enter an extra monthly payment or a lump sum to see how much faster you can pay off the loan.');
    FSL.balanceChart('lp-chart',null,[{label:'With extra payments',rows:plan.rows,start:B},{label:'Original schedule',rows:base.rows,start:B}]);
    FSL.renderSchedule('lp-sched',plan,{filename:'loan-payoff-schedule'});
  }
  FSL.el('lp-go').addEventListener('click',calc); FSL.bind('fs-lp',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   2. LOAN AFFORDABILITY CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_loan_affordability() { ?>
<div class="fsc-wrap" id="fs-af">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'af-inc', 'Gross monthly income ($)', 7500, [ 'min' => 0, 'step' => 100 ] ); ?>
    <?php fsl_field( 'af-debt', 'Other monthly debt payments ($)', 600, [ 'min' => 0, 'step' => 25, 'hint' => 'Car loans, student loans, credit card minimums. Do not include the new loan.' ] ); ?>
    <?php fsl_select( 'af-dti', 'Maximum debt-to-income ratio', [ 28 => '28% (conservative)', 36 => '36% (common guide)', 43 => '43% (typical lender limit)', 50 => '50% (stretch, some programs)' ], 36 ); ?>
    <?php fsl_field( 'af-rate', 'Interest rate (% APR)', 7, [ 'min' => 0, 'max' => 30, 'step' => 0.05 ] ); ?>
    <?php fsl_select( 'af-term', 'Loan term', [ 12 => '1 year', 36 => '3 years', 60 => '5 years', 84 => '7 years', 180 => '15 years', 360 => '30 years' ], 360 ); ?>
    <?php fsl_field( 'af-ti', 'Property tax + insurance per month ($, mortgages only)', 0, [ 'min' => 0, 'step' => 25, 'hint' => 'Subtracted from your housing budget before calculating the loan. Leave 0 for personal or auto loans.' ] ); ?>
    <?php fsl_field( 'af-down', 'Down payment ($, optional)', 0, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <button type="button" class="fsc-btn" id="af-go">Calculate</button>
    <p class="fsl-error" id="af-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="af-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'af-loan', 'Most you can borrow', 'primary' );
      fsl_card( 'af-pay', 'Maximum new monthly payment' );
      fsl_card( 'af-price', 'Price you can afford (with down payment)', 'gold' );
      fsl_card( 'af-total', 'Total debt payments allowed' );
      fsl_card( 'af-int', 'Total interest on that loan' );
      fsl_card( 'af-comfort', 'Comfortable payment (28% of income)', 'secondary' );
      ?>
    </div>
    <p class="fsl-note" id="af-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var inc=$('af-inc'), debt=$('af-debt'), dti=parseInt(FSL.el('af-dti').value,10)/100, rate=$('af-rate'), n=parseInt(FSL.el('af-term').value,10), ti=$('af-ti'), down=$('af-down');
    if(!FSL.valid(inc>0,'af-err','Enter your gross monthly income.'))return;
    var allowed=inc*dti, room=Math.max(allowed-debt,0), pmt=Math.max(room-ti,0), r=rate/1200;
    var loan=pmt<=0?0:(r===0?pmt*n:pmt*(1-Math.pow(1+r,-n))/r), interest=pmt*n-loan, comfy=Math.max(inc*0.28-debt-ti,0);
    FSL.set('af-loan',M(loan)); FSL.set('af-pay',M(pmt)+'/mo'); FSL.set('af-price',M(loan+down)); FSL.set('af-total',M(allowed)+'/mo ('+(dti*100)+'% of income)');
    FSL.set('af-int',M(interest)); FSL.set('af-comfort',M(comfy)+'/mo');
    FSL.show('af-results','grid');
    FSL.set('af-note',room<=0?'Your current debts already use up the '+(dti*100)+'% limit, so there is no room for a new payment. Paying down debt first will raise what you can borrow.':'At a '+(dti*100)+'% debt-to-income limit, your total debt payments can be '+M(allowed)+' a month. After '+M(debt)+' of existing debt'+(ti>0?' and '+M(ti)+' of tax and insurance':'')+', '+M(pmt)+' is left for the new loan, which supports a loan of about '+M(loan)+'.');
  }
  FSL.el('af-go').addEventListener('click',calc); FSL.bind('fs-af',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   3. INTEREST-ONLY LOAN CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_interest_only() { ?>
<div class="fsc-wrap" id="fs-io">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'io-amt', 'Loan amount ($)', 300000, [ 'min' => 1000, 'step' => 5000 ] ); ?>
    <?php fsl_field( 'io-rate', 'Interest rate (% APR)', 7, [ 'min' => 0, 'max' => 30, 'step' => 0.05 ] ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'io-io', 'Interest-only years', 10, [ 'min' => 1, 'max' => 30, 'step' => 1 ] ); ?>
      <?php fsl_field( 'io-total', 'Total term (years)', 30, [ 'min' => 2, 'max' => 40, 'step' => 1 ] ); ?>
    </div>
    <?php fsl_field( 'io-start', 'First payment month', '', [ 'type' => 'month' ] ); ?>
    <button type="button" class="fsc-btn" id="io-go">Calculate</button>
    <p class="fsl-error" id="io-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="io-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'io-p1', 'Payment during interest-only years', 'primary' );
      fsl_card( 'io-p2', 'Payment after interest-only ends' );
      fsl_card( 'io-jump', 'Payment increase', 'gold' );
      fsl_card( 'io-full', 'Payment if fully amortizing from day one' );
      fsl_card( 'io-int', 'Total interest, interest-only loan' );
      fsl_card( 'io-int2', 'Total interest, standard loan' );
      fsl_card( 'io-extra', 'Extra interest cost', 'secondary' );
      fsl_card( 'io-eq', 'Principal paid during interest-only years' );
      ?>
    </div>
    <p class="fsl-note" id="io-note"></p>
  </div>
</div>
<div id="io-sched" style="display:none;margin-top:1.5rem"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  FSL.el('io-start').value=FSL.defaultStartValue();
  function calc(){
    var P=$('io-amt'), rate=$('io-rate'), ioY=Math.round($('io-io')), totY=Math.round($('io-total')), start=FSL.startDate('io-start');
    if(!FSL.valid(P>0&&totY>ioY,'io-err','The total term must be longer than the interest-only period.'))return;
    var n=totY*12, io=ioY*12, r=rate/1200;
    var s=FSL.schedule({P:P,apr:rate,n:n,ioMonths:io,start:start}), std=FSL.schedule({P:P,apr:rate,n:n,start:start});
    var p1=s.rows[0].pay, p2=s.rows[io]?s.rows[io].pay:p1;
    FSL.set('io-p1',M(p1,2)+'/mo'); FSL.set('io-p2',M(p2,2)+'/mo'); FSL.set('io-jump','+'+M(p2-p1,2)+'/mo ('+Math.round((p2/p1-1)*100)+'% higher)');
    FSL.set('io-full',M(std.payment,2)+'/mo'); FSL.set('io-int',M(s.totalInterest)); FSL.set('io-int2',M(std.totalInterest)); FSL.set('io-extra',M(s.totalInterest-std.totalInterest)); FSL.set('io-eq','$0');
    FSL.show('io-results','grid');
    FSL.set('io-note','For '+ioY+' years you pay only interest, so the balance stays at '+M(P)+'. Then the payment jumps to '+M(p2,2)+' because the full balance must now be repaid in '+(totY-ioY)+' years. The interest-only structure costs '+M(s.totalInterest-std.totalInterest)+' more in total interest than a standard '+totY+'-year loan.');
    FSL.renderSchedule('io-sched',s,{filename:'interest-only-schedule'});
  }
  FSL.el('io-go').addEventListener('click',calc); FSL.bind('fs-io',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   4. BALLOON LOAN CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_balloon() { ?>
<div class="fsc-wrap" id="fs-bl">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'bl-amt', 'Loan amount ($)', 300000, [ 'min' => 1000, 'step' => 5000 ] ); ?>
    <?php fsl_field( 'bl-rate', 'Interest rate (% APR)', 6.5, [ 'min' => 0, 'max' => 30, 'step' => 0.05 ] ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'bl-am', 'Payments sized to: years', 30, [ 'min' => 1, 'max' => 40, 'step' => 1 ] ); ?>
      <?php fsl_field( 'bl-bt', 'Balloon due after: years', 7, [ 'min' => 1, 'max' => 39, 'step' => 1 ] ); ?>
    </div>
    <?php fsl_field( 'bl-start', 'First payment month', '', [ 'type' => 'month' ] ); ?>
    <button type="button" class="fsc-btn" id="bl-go">Calculate</button>
    <p class="fsl-error" id="bl-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="bl-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'bl-pay', 'Monthly payment', 'primary' );
      fsl_card( 'bl-balloon', 'Balloon payment due', 'gold' );
      fsl_card( 'bl-date', 'Balloon due date' );
      fsl_card( 'bl-paid', 'Total paid before the balloon' );
      fsl_card( 'bl-int', 'Interest paid before the balloon' );
      fsl_card( 'bl-pct', 'Share of the loan still owed', 'secondary' );
      fsl_card( 'bl-std', 'Payment on a fully amortizing loan' );
      fsl_card( 'bl-total', 'Total cost if you pay the balloon in cash' );
      ?>
    </div>
    <p class="fsl-note" id="bl-note"></p>
  </div>
</div>
<div id="bl-sched" style="display:none;margin-top:1.5rem"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  FSL.el('bl-start').value=FSL.defaultStartValue();
  function calc(){
    var P=$('bl-amt'), rate=$('bl-rate'), am=Math.round($('bl-am')), bt=Math.round($('bl-bt')), start=FSL.startDate('bl-start');
    if(!FSL.valid(P>0&&bt<am,'bl-err','The balloon must come due before the end of the payment schedule.'))return;
    var s=FSL.schedule({P:P,apr:rate,n:am*12,maxMonths:bt*12,start:start}), balloon=s.endBalance, std=FSL.pmt(P,rate/1200,bt*12);
    FSL.set('bl-pay',M(s.payment,2)+'/mo'); FSL.set('bl-balloon',M(balloon)); FSL.set('bl-date',FSL.dateText(FSL.addMonths(start,bt*12-1)));
    FSL.set('bl-paid',M(s.totalPaid)); FSL.set('bl-int',M(s.totalInterest)); FSL.set('bl-pct',FSL.pct(balloon/P*100,1)); FSL.set('bl-std',M(FSL.pmt(P,rate/1200,bt*12),2)+'/mo over '+bt+' years');
    FSL.set('bl-total',M(s.totalPaid+balloon));
    FSL.show('bl-results','grid');
    FSL.set('bl-note','Payments are sized as if the loan ran '+am+' years, so they are low, but after '+bt+' years you still owe '+M(balloon)+' ('+(balloon/P*100).toFixed(0)+'% of the loan). You must pay it from savings, sell the asset, or refinance. If rates are higher or the property is worth less when the balloon comes due, refinancing may be hard.');
    FSL.renderSchedule('bl-sched',s,{filename:'balloon-loan-schedule'});
  }
  FSL.el('bl-go').addEventListener('click',calc); FSL.bind('fs-bl',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   5. BRIDGE LOAN CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_bridge() { ?>
<div class="fsc-wrap" id="fs-br">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'br-amt', 'Bridge loan amount ($)', 250000, [ 'min' => 1000, 'step' => 5000 ] ); ?>
    <?php fsl_field( 'br-rate', 'Interest rate (% APR)', 10, [ 'min' => 0, 'max' => 40, 'step' => 0.1, 'hint' => 'Bridge loans usually cost more than standard mortgages.' ] ); ?>
    <?php fsl_field( 'br-mo', 'Loan length (months)', 6, [ 'min' => 1, 'max' => 36, 'step' => 1 ] ); ?>
    <?php fsl_select( 'br-type', 'Interest payments', [ 'monthly' => 'Paid monthly (interest-only)', 'accrued' => 'Accrued and paid at the end' ], 'monthly' ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'br-orig', 'Origination fee (%)', 2, [ 'min' => 0, 'max' => 10, 'step' => 0.1 ] ); ?>
      <?php fsl_field( 'br-exit', 'Exit fee (%)', 1, [ 'min' => 0, 'max' => 10, 'step' => 0.1 ] ); ?>
    </div>
    <?php fsl_field( 'br-other', 'Appraisal, legal and other costs ($)', 2500, [ 'min' => 0, 'step' => 100 ] ); ?>
    <button type="button" class="fsc-btn" id="br-go">Calculate cost</button>
    <p class="fsl-error" id="br-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="br-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'br-cost', 'Total cost of the bridge loan', 'primary' );
      fsl_card( 'br-apr', 'Effective APR (all fees)', 'gold' );
      fsl_card( 'br-int', 'Interest' );
      fsl_card( 'br-fees', 'Fees and costs' );
      fsl_card( 'br-net', 'Cash you actually receive' );
      fsl_card( 'br-mo-pay', 'Monthly interest payment' );
      fsl_card( 'br-per', 'Cost per month', 'secondary' );
      fsl_card( 'br-payback', 'Total to repay at the end' );
      ?>
    </div>
    <p class="fsl-note" id="br-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var P=$('br-amt'), rate=$('br-rate'), n=Math.max(1,Math.round($('br-mo'))), orig=P*$('br-orig')/100, exit=P*$('br-exit')/100, other=$('br-other'), monthly=FSL.el('br-type').value==='monthly';
    if(!FSL.valid(P>0,'br-err','Enter the loan amount.'))return;
    var r=rate/1200, intTotal=P*r*n, fees=orig+exit+other, net=P-orig-other, pays=[];
    for(var m=1;m<=n;m++){ var p=monthly?P*r:0; if(m===n)p+=P+exit+(monthly?0:intTotal); pays.push(p); }
    var apr=FSL.apr(net,pays), cost=intTotal+fees;
    FSL.set('br-cost',M(cost)); FSL.set('br-apr',FSL.pct(apr,2)); FSL.set('br-int',M(intTotal)); FSL.set('br-fees',M(fees));
    FSL.set('br-net',M(net)); FSL.set('br-mo-pay',monthly?M(P*r,2)+'/mo':'None until the end'); FSL.set('br-per',M(cost/n)+'/mo'); FSL.set('br-payback',M(P+exit+(monthly?0:intTotal)));
    FSL.show('br-results','grid');
    FSL.set('br-note','Over '+n+' months this loan costs '+M(cost)+' in interest and fees, an effective APR of '+FSL.pct(apr,2)+', well above the stated '+rate.toFixed(2)+'% because fees are charged on a short loan. A bridge loan makes sense only when the exit (selling the old property or getting permanent financing) is certain.');
  }
  FSL.el('br-go').addEventListener('click',calc); FSL.bind('fs-br',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   6. COMMERCIAL LOAN CALCULATOR (DSCR)
───────────────────────────────────────────── */
function fs_calc2_commercial() { ?>
<div class="fsc-wrap" id="fs-cm">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'cm-val', 'Property value ($)', 1500000, [ 'min' => 1000, 'step' => 10000 ] ); ?>
    <?php fsl_field( 'cm-amt', 'Loan amount ($)', 1000000, [ 'min' => 1000, 'step' => 10000 ] ); ?>
    <?php fsl_field( 'cm-rate', 'Interest rate (% APR)', 7.25, [ 'min' => 0, 'max' => 30, 'step' => 0.05 ] ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'cm-am', 'Amortization (years)', 25, [ 'min' => 5, 'max' => 40, 'step' => 1 ] ); ?>
      <?php fsl_field( 'cm-bt', 'Loan term (years)', 10, [ 'min' => 1, 'max' => 39, 'step' => 1 ] ); ?>
    </div>
    <?php fsl_field( 'cm-noi', 'Annual net operating income ($)', 130000, [ 'min' => 0, 'step' => 1000, 'hint' => 'Rental income minus operating expenses, before debt payments.' ] ); ?>
    <?php fsl_field( 'cm-dscr', 'Lender’s required DSCR', 1.25, [ 'min' => 1, 'max' => 2, 'step' => 0.05 ] ); ?>
    <button type="button" class="fsc-btn" id="cm-go">Calculate</button>
    <p class="fsl-error" id="cm-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="cm-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'cm-pay', 'Monthly payment', 'primary' );
      fsl_card( 'cm-dscr-v', 'Debt service coverage ratio (DSCR)', 'gold' );
      fsl_card( 'cm-ok', 'Meets the lender’s requirement?' );
      fsl_card( 'cm-ltv', 'Loan-to-value (LTV)' );
      fsl_card( 'cm-balloon', 'Balloon balance at end of term' );
      fsl_card( 'cm-max', 'Largest loan at your required DSCR', 'secondary' );
      fsl_card( 'cm-ads', 'Annual debt service' );
      fsl_card( 'cm-int', 'Interest paid during the term' );
      ?>
    </div>
    <p class="fsl-note" id="cm-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var V=$('cm-val'), P=$('cm-amt'), rate=$('cm-rate'), am=Math.round($('cm-am')), bt=Math.round($('cm-bt')), noi=$('cm-noi'), req=$('cm-dscr');
    if(!FSL.valid(P>0&&V>0&&bt<=am,'cm-err','Enter the loan and property value; the loan term cannot exceed the amortization.'))return;
    var s=FSL.schedule({P:P,apr:rate,n:am*12,maxMonths:bt*12}), ads=s.payment*12, dscr=ads>0?noi/ads:0, r=rate/1200;
    var maxPay=noi/req/12, maxLoan=r===0?maxPay*am*12:maxPay*(1-Math.pow(1+r,-am*12))/r;
    FSL.set('cm-pay',M(s.payment,2)+'/mo'); FSL.set('cm-dscr-v',dscr.toFixed(2)+'x'); FSL.set('cm-ok',dscr>=req?'Yes ('+dscr.toFixed(2)+'x vs '+req.toFixed(2)+'x)':'No: below '+req.toFixed(2)+'x');
    FSL.set('cm-ltv',FSL.pct(P/V*100,1)); FSL.set('cm-balloon',M(s.endBalance)); FSL.set('cm-max',M(maxLoan)); FSL.set('cm-ads',M(ads)); FSL.set('cm-int',M(s.totalInterest));
    FSL.show('cm-results','grid');
    FSL.set('cm-note',dscr>=req?'Your property earns '+dscr.toFixed(2)+' times its loan payments, which clears the '+req.toFixed(2)+'x requirement. After '+bt+' years you owe a balloon of '+M(s.endBalance)+' unless you refinance.':'The property earns only '+dscr.toFixed(2)+' times its loan payments. At a '+req.toFixed(2)+'x requirement, the largest loan this income supports is about '+M(maxLoan)+'. Raise income, lower the loan, or lengthen the amortization.');
  }
  FSL.el('cm-go').addEventListener('click',calc); FSL.bind('fs-cm',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   7. DEBT-TO-INCOME RATIO CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_dti() { ?>
<div class="fsc-wrap" id="fs-dt">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'dt-inc', 'Gross monthly income, before tax ($)', 7000, [ 'min' => 0, 'step' => 100 ] ); ?>
    <h3 class="fsc-section-title">Monthly debt payments</h3>
    <?php fsl_field( 'dt-house', 'Rent or mortgage (with taxes, insurance, HOA) ($)', 1800, [ 'min' => 0, 'step' => 50 ] ); ?>
    <?php fsl_field( 'dt-car', 'Car loans or leases ($)', 400, [ 'min' => 0, 'step' => 25 ] ); ?>
    <?php fsl_field( 'dt-stu', 'Student loans ($)', 300, [ 'min' => 0, 'step' => 25 ] ); ?>
    <?php fsl_field( 'dt-cc', 'Credit card minimums ($)', 150, [ 'min' => 0, 'step' => 25 ] ); ?>
    <?php fsl_field( 'dt-oth', 'Other debt payments, child support ($)', 100, [ 'min' => 0, 'step' => 25 ] ); ?>
    <button type="button" class="fsc-btn" id="dt-go">Calculate DTI</button>
    <p class="fsl-error" id="dt-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="dt-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'dt-back', 'Back-end DTI (all debts)', 'primary' );
      fsl_card( 'dt-front', 'Front-end DTI (housing only)' );
      fsl_card( 'dt-rate', 'How lenders see it', 'gold' );
      fsl_card( 'dt-tot', 'Total monthly debt' );
      fsl_card( 'dt-room36', 'Extra debt payment room at 36%', 'secondary' );
      fsl_card( 'dt-room43', 'Extra debt payment room at 43%' );
      ?>
    </div>
    <div class="fsl-bar" aria-hidden="true"><span id="dt-bar" style="width:0"></span></div>
    <p class="fsl-note" id="dt-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var inc=$('dt-inc'), h=$('dt-house'), tot=h+$('dt-car')+$('dt-stu')+$('dt-cc')+$('dt-oth');
    if(!FSL.valid(inc>0,'dt-err','Enter your gross monthly income.'))return;
    var back=tot/inc*100, front=h/inc*100, v=back<=36?'Good: most lenders are comfortable':(back<=43?'Acceptable: near the usual limit':(back<=50?'High: approval is harder':'Very high: expect denials'));
    FSL.set('dt-back',FSL.pct(back,1)); FSL.set('dt-front',FSL.pct(front,1)); FSL.set('dt-rate',v); FSL.set('dt-tot',M(tot)+'/mo');
    FSL.set('dt-room36',M(Math.max(inc*0.36-tot,0))+'/mo'); FSL.set('dt-room43',M(Math.max(inc*0.43-tot,0))+'/mo');
    FSL.el('dt-bar').style.width=Math.min(back,100)+'%'; FSL.show('dt-results','grid');
    FSL.set('dt-note','Your debt payments take '+back.toFixed(1)+'% of gross income. Lenders often look for a back-end ratio of 36% or lower, accept up to about 43% for many loans, and a housing-only ratio near 28% (31% for FHA). To lower it, pay down balances, avoid new debt, or raise income.');
  }
  FSL.el('dt-go').addEventListener('click',calc); FSL.bind('fs-dt',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   8. SELF-EMPLOYMENT TAX CALCULATOR (2026)
───────────────────────────────────────────── */
function fs_calc2_se_tax() { fs_tax_data_script(); ?>
<div class="fsc-wrap" id="fs-se">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'se-profit', 'Net self-employment profit ($)', 80000, [ 'min' => 0, 'step' => 1000, 'hint' => 'Revenue minus business expenses (Schedule C line 31).' ] ); ?>
    <?php fsl_field( 'se-wages', 'W-2 wages from a job, if any ($)', 0, [ 'min' => 0, 'step' => 1000, 'hint' => 'Wages count toward the Social Security wage base.' ] ); ?>
    <?php fsl_select( 'se-status', 'Filing status', [ 'single' => 'Single', 'mfj' => 'Married filing jointly', 'hoh' => 'Head of household', 'mfs' => 'Married filing separately' ], 'single' ); ?>
    <?php fsl_check( 'se-inc', 'Also estimate income tax (standard deduction, no other income)', true ); ?>
    <button type="button" class="fsc-btn" id="se-go">Calculate</button>
    <p class="fsl-error" id="se-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="se-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'se-tax', 'Self-employment tax', 'primary' );
      fsl_card( 'se-rate', 'Effective SE tax rate' );
      fsl_card( 'se-half', 'Deductible half of SE tax' );
      fsl_card( 'se-ss', 'Social Security part (12.4%)' );
      fsl_card( 'se-med', 'Medicare part (2.9% + 0.9%)' );
      fsl_card( 'se-inctax', 'Estimated federal income tax', 'secondary' );
      fsl_card( 'se-total', 'Total to set aside per year', 'gold' );
      fsl_card( 'se-q', 'Quarterly estimated payment' );
      ?>
    </div>
    <p class="fsl-note" id="se-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money, T=window.FSTAX;
  function calc(){
    var profit=$('se-profit'), wages=$('se-wages'), st=FSL.el('se-status').value;
    if(!FSL.valid(profit>0,'se-err','Enter your net self-employment profit.'))return;
    var ne=profit*0.9235, ssRoom=Math.max(T.ssBase-wages,0), ss=Math.min(ne,ssRoom)*0.124, med=ne*0.029;
    var thr=T.niit[st], addl=Math.max(ne+wages-thr,0), addlMed=Math.min(addl,ne)*0.009, seTax=ss+med+addlMed, half=seTax/2;
    var inc=0; if(FSL.el('se-inc').checked){ var ti=Math.max(profit+wages-half-T.std[st],0); inc=T.tax(ti,st); }
    var total=seTax+inc;
    FSL.set('se-tax',M(seTax,2)); FSL.set('se-rate',FSL.pct(seTax/profit*100,2)); FSL.set('se-half',M(half,2)); FSL.set('se-ss',M(ss,2)); FSL.set('se-med',M(med+addlMed,2));
    FSL.set('se-inctax',FSL.el('se-inc').checked?M(inc):'Not included'); FSL.set('se-total',M(total)); FSL.set('se-q',M(total/4,2)+' each quarter');
    FSL.show('se-results','grid');
    FSL.set('se-note','Self-employed people pay both the employee and employer halves of Social Security and Medicare, 15.3% on 92.35% of net profit, with Social Security stopping at the 2026 wage base of $184,500. You can deduct half of the SE tax from income. The income tax estimate uses the 2026 standard deduction and brackets, and ignores the qualified business income deduction, which may lower it.');
  }
  FSL.el('se-go').addEventListener('click',calc); FSL.bind('fs-se',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   9. MONTHLY BUDGET PLANNER
───────────────────────────────────────────── */
function fs_calc2_budget_planner() {
    $cats = [
        'bp-house' => [ 'Housing (rent or mortgage)', 1500, 'n' ], 'bp-util' => [ 'Utilities, phone, internet', 250, 'n' ],
        'bp-food'  => [ 'Groceries', 500, 'n' ], 'bp-trans' => [ 'Transportation and fuel', 350, 'n' ],
        'bp-ins'   => [ 'Insurance and health', 250, 'n' ], 'bp-debt' => [ 'Minimum debt payments', 300, 'n' ],
        'bp-dine'  => [ 'Dining out and entertainment', 250, 'w' ], 'bp-shop' => [ 'Shopping and subscriptions', 200, 'w' ],
        'bp-trav'  => [ 'Travel and hobbies', 150, 'w' ], 'bp-save' => [ 'Savings and investing', 500, 's' ],
    ]; ?>
<div class="fsc-wrap" id="fs-bp">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'bp-inc', 'Monthly take-home income, after tax ($)', 5000, [ 'min' => 0, 'step' => 100 ] ); ?>
    <?php fsl_field( 'bp-side', 'Other monthly income ($)', 0, [ 'min' => 0, 'step' => 50 ] ); ?>
    <h3 class="fsc-section-title">Monthly spending</h3>
    <?php foreach ( $cats as $id => $c ) fsl_field( $id, $c[0] . ' ($)', $c[1], [ 'min' => 0, 'step' => 10 ] ); ?>
    <button type="button" class="fsc-btn" id="bp-go">Build my budget</button>
    <p class="fsl-error" id="bp-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="bp-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'bp-left', 'Left over each month', 'primary' );
      fsl_card( 'bp-rate', 'Savings rate' );
      fsl_card( 'bp-spend', 'Total spending' );
      fsl_card( 'bp-income', 'Total income' );
      fsl_card( 'bp-needs', 'Needs (target 50%)', 'secondary' );
      fsl_card( 'bp-wants', 'Wants (target 30%)' );
      ?>
    </div>
    <p class="fsl-note" id="bp-note"></p>
    <div class="fsl-chart" style="margin-top:1rem"><canvas id="bp-chart" height="220" aria-label="Spending breakdown chart" role="img"></canvas></div>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  var CATS=<?php echo wp_json_encode( array_map( function ( $c, $id ) { return [ 'id' => $id, 'label' => $c[0], 'kind' => $c[2] ]; }, $cats, array_keys( $cats ) ) ); ?>;
  function calc(){
    var inc=$('bp-inc')+$('bp-side');
    if(!FSL.valid(inc>0,'bp-err','Enter your monthly income.'))return;
    var tot=0,needs=0,wants=0,sav=0,labels=[],vals=[];
    CATS.forEach(function(c){var v=$(c.id); tot+=v; if(c.kind==='n')needs+=v; if(c.kind==='w')wants+=v; if(c.kind==='s')sav+=v; labels.push(c.label); vals.push(v);});
    var left=inc-tot, savedTot=sav+Math.max(left,0);
    FSL.set('bp-left',(left>=0?'+':'-')+M(Math.abs(left))+'/mo'); FSL.set('bp-rate',FSL.pct(savedTot/inc*100,1)); FSL.set('bp-spend',M(tot)); FSL.set('bp-income',M(inc));
    FSL.set('bp-needs',FSL.pct(needs/inc*100,0)+' of income'); FSL.set('bp-wants',FSL.pct(wants/inc*100,0)+' of income');
    FSL.show('bp-results','grid');
    FSL.set('bp-note',left<0?'You are spending '+M(-left)+' more than you earn each month. Trim wants first, then look at the largest needs.':(left>0?'You have '+M(left)+' unassigned. Give it a job: emergency fund, extra debt payments or retirement.':'Every dollar has a job. Check that savings reach at least 20% of income.'));
    FSL.chart('bp-chart',{type:'doughnut',data:{labels:labels,datasets:[{data:vals,backgroundColor:['#00C896','#3B82F6','#F59E0B','#EF4444','#8B5CF6','#64748B','#EC4899','#14B8A6','#F97316','#22C55E']}]},options:{plugins:{legend:{position:'bottom',labels:{font:{size:10}}}},cutout:'55%'}});
  }
  FSL.el('bp-go').addEventListener('click',calc); FSL.bind('fs-bp',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   10. DIVIDEND CALCULATOR (with DRIP)
───────────────────────────────────────────── */
function fs_calc2_dividend() { ?>
<div class="fsc-wrap" id="fs-dv">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'dv-amt', 'Amount invested ($)', 20000, [ 'min' => 0, 'step' => 500 ] ); ?>
    <?php fsl_field( 'dv-yield', 'Dividend yield (%)', 3.5, [ 'min' => 0, 'max' => 20, 'step' => 0.1, 'hint' => 'The S&amp;P 500 yields roughly 1% to 2%; dividend-focused funds yield more.' ] ); ?>
    <?php fsl_field( 'dv-dg', 'Dividend growth per year (%)', 5, [ 'min' => 0, 'max' => 30, 'step' => 0.1 ] ); ?>
    <?php fsl_field( 'dv-pg', 'Share price growth per year (%)', 4, [ 'min' => -10, 'max' => 30, 'step' => 0.1 ] ); ?>
    <?php fsl_field( 'dv-add', 'Add each month ($)', 200, [ 'min' => 0, 'step' => 25 ] ); ?>
    <?php fsl_field( 'dv-y', 'Years', 20, [ 'min' => 1, 'max' => 60, 'step' => 1 ] ); ?>
    <?php fsl_field( 'dv-tax', 'Tax rate on dividends (%)', 15, [ 'min' => 0, 'max' => 40, 'step' => 1, 'hint' => 'Qualified dividends are taxed at 0%, 15% or 20%.' ] ); ?>
    <?php fsl_check( 'dv-drip', 'Reinvest dividends (DRIP)', true ); ?>
    <button type="button" class="fsc-btn" id="dv-go">Calculate dividends</button>
    <p class="fsl-error" id="dv-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="dv-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'dv-end', 'Portfolio value at the end', 'primary' );
      fsl_card( 'dv-income', 'Yearly dividend income, final year', 'gold' );
      fsl_card( 'dv-month', 'Per month, final year' );
      fsl_card( 'dv-total', 'Total dividends received' );
      fsl_card( 'dv-after', 'Total dividends after tax' );
      fsl_card( 'dv-yoc', 'Final-year income as % of total invested', 'secondary' );
      fsl_card( 'dv-in', 'Total you invested' );
      fsl_card( 'dv-first', 'First-year dividends' );
      ?>
    </div>
    <p class="fsl-note" id="dv-note"></p>
  </div>
</div>
<div style="margin-top:1.5rem" class="fsl-chart"><canvas id="dv-chart" height="200" aria-label="Portfolio growth chart" role="img"></canvas></div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var P=$('dv-amt'), y0=$('dv-yield')/100, dg=$('dv-dg')/100, pg=$('dv-pg')/100, add=$('dv-add'), Y=Math.round($('dv-y')), tax=$('dv-tax')/100, drip=FSL.el('dv-drip').checked;
    if(!FSL.valid(P+add>0&&Y>=1,'dv-err','Enter an investment amount or a monthly contribution.'))return;
    var shares=P, dps=y0, cash=0, invested=P, tot=0, first=0, lastDiv=0, labels=['Start'], vals=[Math.round(P)];
    for(var yr=1;yr<=Y;yr++){
      var yrDiv=0;
      for(var m=1;m<=12;m++){
        var px=Math.pow(1+pg,(yr-1)+m/12);          /* share price, starting at 1.00 */
        shares+=add/px; invested+=add;               /* monthly contribution buys shares */
        var d=shares*dps/12; yrDiv+=d;               /* dividend per share starts at the yield */
        if(drip)shares+=d/px; else cash+=d;
      }
      if(yr===1)first=yrDiv; lastDiv=yrDiv; tot+=yrDiv; dps*=1+dg;
      labels.push('Yr '+yr); vals.push(Math.round(shares*Math.pow(1+pg,yr)+cash));
    }
    var end=shares*Math.pow(1+pg,Y)+cash;
    FSL.set('dv-end',M(end)); FSL.set('dv-income',M(lastDiv)); FSL.set('dv-month',M(lastDiv/12)+'/mo'); FSL.set('dv-total',M(tot)); FSL.set('dv-after',M(tot*(1-tax)));
    FSL.set('dv-yoc',invested>0?FSL.pct(lastDiv/invested*100,1):'--'); FSL.set('dv-in',M(invested)); FSL.set('dv-first',M(first));
    FSL.show('dv-results','grid');
    FSL.set('dv-note',(drip?'With dividends reinvested, ':'Taking dividends as cash, ')+'your '+M(invested)+' grows to about '+M(end)+' and pays '+M(lastDiv)+' of dividends in the final year. Dividends are not guaranteed and share prices can fall. Use a conservative growth rate.');
    FSL.chart('dv-chart',{type:'line',data:{labels:labels,datasets:[{label:'Portfolio value',data:vals,borderColor:'#00C896',backgroundColor:'#00C89633',fill:true,tension:.25,pointRadius:0}]},options:{plugins:{legend:{display:false}},scales:{y:{ticks:{callback:function(v){return '$'+(v>=1e6?(v/1e6).toFixed(1)+'M':Math.round(v/1000)+'k');}}}}}});
  }
  FSL.el('dv-go').addEventListener('click',calc); FSL.bind('fs-dv',calc); calc();
});
</script>
<?php }
