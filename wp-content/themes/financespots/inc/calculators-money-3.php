<?php
/**
 * Calculators (v2), batch 4: balance transfer, student loan RAP vs standard plan.
 * Dispatched by tool slug (see fs_render_calculator).
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

/* ─────────────────────────────────────────────
   BALANCE TRANSFER CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_balance_transfer() { ?>
<div class="fsc-wrap" id="fs-bt">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'bt-bal', 'Balance to transfer ($)', 8000, [ 'min' => 100, 'step' => 100 ] ); ?>
    <?php fsl_field( 'bt-apr', 'Your current card APR (%)', 24, [ 'min' => 0, 'max' => 40, 'step' => 0.1 ] ); ?>
    <?php fsl_field( 'bt-pay', 'What you can pay each month ($)', 400, [ 'min' => 10, 'step' => 10 ] ); ?>
    <h3 class="fsc-section-title">The balance transfer offer</h3>
    <div class="fsl-row2">
      <?php fsl_field( 'bt-fee', 'Transfer fee (%)', 3, [ 'min' => 0, 'max' => 10, 'step' => 0.1, 'hint' => 'Usually 3% to 5%.' ] ); ?>
      <?php fsl_field( 'bt-promo-m', 'Promo months', 18, [ 'min' => 0, 'max' => 24, 'step' => 1 ] ); ?>
    </div>
    <div class="fsl-row2">
      <?php fsl_field( 'bt-promo-apr', 'Promo APR (%)', 0, [ 'min' => 0, 'max' => 30, 'step' => 0.1 ] ); ?>
      <?php fsl_field( 'bt-after', 'APR after promo (%)', 22, [ 'min' => 0, 'max' => 40, 'step' => 0.1 ] ); ?>
    </div>
    <button type="button" class="fsc-btn" id="bt-go">Compare</button>
    <p class="fsl-error" id="bt-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="bt-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'bt-save', 'You save with the transfer', 'primary' );
      fsl_card( 'bt-fee-v', 'Transfer fee' );
      fsl_card( 'bt-int-now', 'Interest if you stay on your card' );
      fsl_card( 'bt-int-new', 'Interest with the transfer' );
      fsl_card( 'bt-time-now', 'Time to pay off: staying' );
      fsl_card( 'bt-time-new', 'Time to pay off: transfer', 'secondary' );
      fsl_card( 'bt-left', 'Balance left when the promo ends', 'gold' );
      fsl_card( 'bt-need', 'Monthly payment to clear it in the promo', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="bt-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function payoff(bal,pay,fn){ /* fn(month) -> monthly rate */
    var m=0,int=0; while(bal>0.005&&m<720){ var r=fn(m), i=bal*r; if(pay<=i+0.005&&m>6&&r>0){return {m:Infinity,int:Infinity,left:bal};} int+=i; bal=bal+i-Math.min(pay,bal+i); m++; } return {m:m,int:int,left:bal};
  }
  function calc(){
    var B=$('bt-bal'), apr=$('bt-apr')/1200, pay=$('bt-pay'), feeP=$('bt-fee'), pm=Math.round($('bt-promo-m')), papr=$('bt-promo-apr')/1200, after=$('bt-after')/1200;
    if(!FSL.valid(B>0&&pay>0,'bt-err','Enter a balance and a monthly payment.'))return;
    var fee=B*feeP/100, nb=B+fee;
    var now=payoff(B,pay,function(){return apr;});
    var nw=payoff(nb,pay,function(m){return m<pm?papr:after;});
    /* balance when the promo ends */
    var bal=nb; for(var m=0;m<pm&&bal>0.005;m++){ bal=bal+bal*papr-Math.min(pay,bal+bal*papr); }
    var leftPromo=Math.max(bal,0);
    var needPromo=pm>0?(papr===0?nb/pm:nb*papr/(1-Math.pow(1+papr,-pm))):0;
    var costNow=now.int, costNew=nw.int+fee, save=costNow-costNew;
    FSL.set('bt-save',isFinite(save)?(save>=0?'Save ':'Lose ')+M(Math.abs(save)):'Payment too low to pay off');
    FSL.set('bt-fee-v',M(fee)); FSL.set('bt-int-now',isFinite(now.int)?M(now.int):'Never at this payment'); FSL.set('bt-int-new',isFinite(nw.int)?M(nw.int)+' (+ '+M(fee)+' fee)':'Never at this payment');
    FSL.set('bt-time-now',isFinite(now.m)?FSL.monthsText(now.m):'Never'); FSL.set('bt-time-new',isFinite(nw.m)?FSL.monthsText(nw.m):'Never');
    FSL.set('bt-left',pm>0?M(leftPromo):'No promo period'); FSL.set('bt-need',pm>0?M(needPromo)+'/mo':'--');
    FSL.show('bt-results','grid');
    var note=leftPromo>0.5?'When the '+pm+'-month promo ends you would still owe '+M(leftPromo)+', which then accrues interest at '+(after*1200).toFixed(1)+'%. To clear the balance inside the promo you would need to pay about '+M(needPromo)+' a month.':'You clear the balance inside the promo period, so you pay only the transfer fee in costs.';
    if(isFinite(save)&&save<0)note+=' This offer costs more than staying put; look for a lower fee or a longer promo.';
    FSL.set('bt-note',note);
  }
  FSL.el('bt-go').addEventListener('click',calc); FSL.bind('fs-bt',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   STUDENT LOAN: RAP vs TIERED STANDARD PLAN (loans first disbursed on or after July 1, 2026)
───────────────────────────────────────────── */
function fs_calc2_student_rap() { ?>
<div class="fsc-wrap" id="fs-rp">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'rp-agi', 'Adjusted gross income, yearly ($)', 52000, [ 'min' => 0, 'step' => 1000, 'hint' => 'RAP uses your AGI from your tax return (household AGI if you file jointly).' ] ); ?>
    <?php fsl_field( 'rp-dep', 'Dependents (children or others you support)', 0, [ 'min' => 0, 'max' => 10, 'step' => 1 ] ); ?>
    <?php fsl_field( 'rp-bal', 'Total federal loan balance ($)', 38000, [ 'min' => 100, 'step' => 500 ] ); ?>
    <?php fsl_field( 'rp-rate', 'Interest rate (%)', 6.52, [ 'min' => 0, 'max' => 15, 'step' => 0.01 ] ); ?>
    <button type="button" class="fsc-btn" id="rp-go">Compare plans</button>
    <p class="fsl-error" id="rp-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="rp-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'rp-rap', 'Estimated RAP payment', 'primary' );
      fsl_card( 'rp-pct', 'Share of AGI used' );
      fsl_card( 'rp-std', 'Tiered Standard payment' );
      fsl_card( 'rp-term', 'Standard plan term' );
      fsl_card( 'rp-diff', 'RAP vs Standard, per month', 'gold' );
      fsl_card( 'rp-int', 'Monthly interest on your balance', 'secondary' );
      fsl_card( 'rp-cover', 'Does the RAP payment cover the interest?' );
      ?>
    </div>
    <p class="fsl-note" id="rp-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var agi=$('rp-agi'), dep=Math.round($('rp-dep')), B=$('rp-bal'), rate=$('rp-rate');
    if(!FSL.valid(B>0,'rp-err','Enter your loan balance.'))return;
    /* RAP: 1% of AGI for each $10,000 band, from 1% at $10,001 up to 10% above $100,000; $10 minimum; $50 off per dependent */
    var pct=agi<=10000?0:Math.min(Math.ceil(agi/10000)-1,10), annual=agi*pct/100, monthly=annual/12-50*dep;
    monthly=Math.max(monthly,10);
    var yrs=B<25000?10:(B<50000?15:(B<100000?20:25)), n=yrs*12, r=rate/1200, std=FSL.pmt(B,r,n), interest=B*r;
    FSL.set('rp-rap',M(monthly,2)+'/mo'); FSL.set('rp-pct',pct?pct+'% of AGI':'$10 minimum'); FSL.set('rp-std',M(std,2)+'/mo'); FSL.set('rp-term',yrs+' years');
    FSL.set('rp-diff',(monthly<=std?'RAP is ':'RAP is ')+M(Math.abs(std-monthly),2)+(monthly<=std?' lower':' higher')); FSL.set('rp-int',M(interest,2)+'/mo');
    FSL.set('rp-cover',monthly>=interest?'Yes, part goes to principal':'No: payment is below the monthly interest');
    FSL.show('rp-results','grid');
    FSL.set('rp-note','On '+M(agi)+' of income, RAP asks for about '+M(monthly,2)+' a month ('+(pct||0)+'% of AGI, less $50 per dependent, with a $10 minimum), against '+M(std,2)+' on the Tiered Standard plan, which repays a '+M(B)+' balance in '+yrs+' years. This is an estimate based on the published rules for loans first disbursed on or after July 1, 2026; confirm your exact figure with the Loan Simulator on studentaid.gov, because the Department of Education sets the final formula and may update it.');
  }
  FSL.el('rp-go').addEventListener('click',calc); FSL.bind('fs-rp',calc); calc();
});
</script>
<?php }
