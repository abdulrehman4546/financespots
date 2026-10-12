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

/* ─────────────────────────────────────────────
   SALES TAX CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_sales_tax() { ?>
<div class="fsc-wrap" id="fs-st">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_select( 'st-mode', 'What do you want to find?', [ 'add' => 'Total price with tax added', 'extract' => 'Price before tax, from a total that includes tax' ], 'add' ); ?>
    <?php fsl_field( 'st-amt', 'Amount ($)', 250, [ 'min' => 0, 'step' => 0.01 ] ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'st-state', 'State rate (%)', 6.25, [ 'min' => 0, 'max' => 20, 'step' => 0.001 ] ); ?>
      <?php fsl_field( 'st-local', 'City / county rate (%)', 2, [ 'min' => 0, 'max' => 10, 'step' => 0.001, 'hint' => 'Leave 0 if none.' ] ); ?>
    </div>
    <?php fsl_field( 'st-qty', 'Quantity', 1, [ 'min' => 1, 'step' => 1 ] ); ?>
    <button type="button" class="fsc-btn" id="st-go">Calculate</button>
    <p class="fsl-error" id="st-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="st-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'st-total', 'Total with tax', 'primary' );
      fsl_card( 'st-pre', 'Price before tax' );
      fsl_card( 'st-tax', 'Sales tax' );
      fsl_card( 'st-rate', 'Combined rate', 'secondary' );
      ?>
    </div>
    <p class="fsl-note" id="st-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var amt=$('st-amt'), r=($('st-state')+$('st-local'))/100, q=Math.max(1,Math.round($('st-qty'))), mode=FSL.el('st-mode').value;
    if(!FSL.valid(amt>0,'st-err','Enter an amount above zero.'))return;
    var pre,tot;
    if(mode==='add'){pre=amt*q;tot=pre*(1+r);}else{tot=amt*q;pre=tot/(1+r);}
    FSL.set('st-total',M(tot,2));FSL.set('st-pre',M(pre,2));FSL.set('st-tax',M(tot-pre,2));FSL.set('st-rate',(r*100).toFixed(3).replace(/\.?0+$/,'')+'%');
    FSL.show('st-results','grid');
    FSL.set('st-note',mode==='add'?'A '+M(pre,2)+' purchase at a combined '+(r*100).toFixed(2)+'% rate adds '+M(tot-pre,2)+' of sales tax, for a total of '+M(tot,2)+'.':'A '+M(tot,2)+' total that includes tax at '+(r*100).toFixed(2)+'% means a pre-tax price of '+M(pre,2)+' and '+M(tot-pre,2)+' of tax. Rates and what is taxable vary by state and locality; check your state revenue department for exact rules.');
  }
  FSL.el('st-go').addEventListener('click',calc);FSL.bind('fs-st',calc);calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   FEDERAL ESTATE TAX CALCULATOR (2026: $15,000,000 basic exclusion per person, 40% rate)
───────────────────────────────────────────── */
function fs_calc2_estate_tax() { ?>
<div class="fsc-wrap" id="fs-es">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'es-gross', 'Gross estate ($)', 16000000, [ 'min' => 0, 'step' => 50000, 'hint' => 'Everything you own at death: real estate, investments, retirement accounts, life insurance you own, business interests.' ] ); ?>
    <?php fsl_field( 'es-debts', 'Debts, funeral and administration costs ($)', 500000, [ 'min' => 0, 'step' => 10000 ] ); ?>
    <?php fsl_field( 'es-ded', 'Marital and charitable deductions ($)', 0, [ 'min' => 0, 'step' => 10000, 'hint' => 'Property left to a U.S.-citizen spouse or to charity is generally deductible.' ] ); ?>
    <?php fsl_field( 'es-gifts', 'Taxable gifts made during life ($)', 0, [ 'min' => 0, 'step' => 10000, 'hint' => 'Lifetime gifts above the annual exclusion count against the same exemption.' ] ); ?>
    <?php fsl_select( 'es-status', 'Exemption available', [ '1' => 'One person ($15,000,000)', '2' => 'Married couple using portability ($30,000,000)' ], '1' ); ?>
    <button type="button" class="fsc-btn" id="es-go">Estimate tax</button>
    <p class="fsl-error" id="es-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="es-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'es-tax', 'Estimated federal estate tax', 'primary' );
      fsl_card( 'es-taxable', 'Taxable estate' );
      fsl_card( 'es-excl', 'Exemption applied' );
      fsl_card( 'es-over', 'Amount over the exemption' );
      fsl_card( 'es-eff', 'Tax as a share of gross estate', 'secondary' );
      fsl_card( 'es-net', 'Left for heirs after tax', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="es-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money, EX=15000000;
  function calc(){
    var g=$('es-gross'), d=$('es-debts'), ded=$('es-ded'), gifts=$('es-gifts'), n=parseInt(FSL.el('es-status').value,10)||1;
    if(!FSL.valid(g>0,'es-err','Enter the value of the gross estate.'))return;
    var taxable=Math.max(g-d-ded,0), base=taxable+gifts, excl=EX*n, over=Math.max(base-excl,0), tax=over*0.40;
    FSL.set('es-tax',M(tax));FSL.set('es-taxable',M(taxable));FSL.set('es-excl',M(excl));FSL.set('es-over',M(over));
    FSL.set('es-eff',(tax/g*100).toFixed(2)+'%');FSL.set('es-net',M(g-d-tax));
    FSL.show('es-results','grid');
    FSL.set('es-note',tax>0?'Your taxable estate plus lifetime taxable gifts is '+M(base)+', which is '+M(over)+' above the '+M(excl)+' exemption. That excess is taxed at the 40% top federal rate.':'Your estate is below the '+M(excl)+' federal exemption, so no federal estate tax is expected. Some states charge their own estate or inheritance tax at much lower thresholds, which this calculator does not include.');
  }
  FSL.el('es-go').addEventListener('click',calc);FSL.bind('fs-es',calc);calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   IRS PENALTY AND INTEREST ESTIMATOR (failure-to-file and failure-to-pay)
───────────────────────────────────────────── */
function fs_calc2_irs_penalty() { ?>
<div class="fsc-wrap" id="fs-ip">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'ip-tax', 'Unpaid tax ($)', 5000, [ 'min' => 0, 'step' => 100, 'hint' => 'The tax due on the return, after credits and payments.' ] ); ?>
    <?php fsl_field( 'ip-mf', 'Months your return was filed late', 3, [ 'min' => 0, 'max' => 60, 'step' => 1, 'hint' => '0 if you filed on time. Any part of a month counts as a full month.' ] ); ?>
    <?php fsl_field( 'ip-mp', 'Months the tax has been paid late', 3, [ 'min' => 0, 'max' => 120, 'step' => 1 ] ); ?>
    <?php fsl_field( 'ip-rate', 'IRS interest rate (% a year)', 7, [ 'min' => 0, 'max' => 20, 'step' => 0.01, 'hint' => 'The IRS sets this every quarter (federal short-term rate + 3 points). Check irs.gov for the current figure.' ] ); ?>
    <button type="button" class="fsc-btn" id="ip-go">Estimate</button>
    <p class="fsl-error" id="ip-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="ip-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'ip-total', 'Estimated penalties plus interest', 'primary' );
      fsl_card( 'ip-ftf', 'Failure-to-file penalty' );
      fsl_card( 'ip-ftp', 'Failure-to-pay penalty' );
      fsl_card( 'ip-int', 'Interest on the unpaid tax' );
      fsl_card( 'ip-owe', 'Total you would owe', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="ip-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var t=$('ip-tax'), mf=Math.max(0,Math.round($('ip-mf'))), mp=Math.max(0,Math.round($('ip-mp'))), r=$('ip-rate')/100;
    if(!FSL.valid(t>0,'ip-err','Enter the amount of unpaid tax.'))return;
    /* Failure to file: 5% a month, up to 5 months, reduced by the 0.5% failure-to-pay penalty in months both apply.
       Failure to pay: 0.5% a month, up to 50 months (25%). Assumes the tax stays unpaid the whole time. */
    var ftfMonths=Math.min(mf,5), ftf=t*0.045*ftfMonths;
    var ftp=t*0.005*Math.min(mp,50);
    var interest=t*(Math.pow(1+r/365,30.4*mp)-1);
    var pen=ftf+ftp, total=pen+interest;
    FSL.set('ip-total',M(total,2));FSL.set('ip-ftf',M(ftf,2));FSL.set('ip-ftp',M(ftp,2));FSL.set('ip-int',M(interest,2));FSL.set('ip-owe',M(t+total,2));
    FSL.show('ip-results','grid');
    FSL.set('ip-note','The failure-to-file penalty is 5% of the unpaid tax for each month late (up to 5 months), and the failure-to-pay penalty is 0.5% a month. In months when both apply the filing penalty is reduced to 4.5%, which this estimate reflects. Interest compounds daily. This is an estimate: if your return was more than 60 days late the IRS also applies a minimum filing penalty, and you may qualify for penalty relief such as first-time abatement.');
  }
  FSL.el('ip-go').addEventListener('click',calc);FSL.bind('fs-ip',calc);calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   ETHEREUM GAS FEE CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_gas_fee() { ?>
<div class="fsc-wrap" id="fs-gf">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_select( 'gf-type', 'Transaction type (typical gas used)', [ '21000' => 'ETH transfer (21,000)', '65000' => 'Token transfer, ERC-20 (65,000)', '150000' => 'Token swap on a DEX (150,000)', '120000' => 'NFT mint (120,000)', 'custom' => 'Custom gas amount' ], '21000' ); ?>
    <?php fsl_field( 'gf-units', 'Gas units', 21000, [ 'min' => 21000, 'step' => 1000, 'hint' => 'The wallet or a block explorer shows the exact amount for your transaction.' ] ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'gf-base', 'Base fee (gwei)', 10, [ 'min' => 0, 'step' => 0.1 ] ); ?>
      <?php fsl_field( 'gf-tip', 'Priority tip (gwei)', 1, [ 'min' => 0, 'step' => 0.1 ] ); ?>
    </div>
    <?php fsl_field( 'gf-eth', 'ETH price ($)', 2500, [ 'min' => 0, 'step' => 1, 'hint' => 'Filled with the current price when it can be loaded.' ] ); ?>
    <button type="button" class="fsc-btn" id="gf-go">Calculate</button>
    <p class="fsl-error" id="gf-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="gf-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'gf-usd', 'Fee in dollars', 'primary' );
      fsl_card( 'gf-eth-v', 'Fee in ETH' );
      fsl_card( 'gf-gwei', 'Total gas price' );
      fsl_card( 'gf-wei', 'Fee in gwei', 'secondary' );
      ?>
    </div>
    <p class="fsl-note" id="gf-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num;
  function usd(v){return '$'+v.toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:v<1?4:2});}
  function calc(){
    var sel=FSL.el('gf-type').value; if(sel!=='custom')FSL.el('gf-units').value=sel;
    var u=$('gf-units'), b=$('gf-base'), p=$('gf-tip'), e=$('gf-eth');
    if(!FSL.valid(u>0,'gf-err','Enter the gas units.'))return;
    var gp=b+p, gwei=u*gp, eth=gwei/1e9, d=eth*e;
    FSL.set('gf-usd',usd(d));FSL.set('gf-eth-v',eth.toFixed(6)+' ETH');FSL.set('gf-gwei',gp.toFixed(2)+' gwei');FSL.set('gf-wei',Math.round(gwei).toLocaleString('en-US')+' gwei');
    FSL.show('gf-results','grid');
    FSL.set('gf-note','Fee = gas units × (base fee + priority tip). The base fee changes block to block and is burned; the tip goes to the validator. Enter the live gas price shown in your wallet or a gas tracker for an accurate number. Layer-2 networks charge far less.');
  }
  FSL.el('gf-go').addEventListener('click',calc);FSL.bind('fs-gf',calc);calc();
  fetch('https://api.coingecko.com/api/v3/simple/price?ids=ethereum&vs_currencies=usd').then(function(r){return r.json();}).then(function(j){if(j&&j.ethereum&&j.ethereum.usd){FSL.el('gf-eth').value=Math.round(j.ethereum.usd);calc();}}).catch(function(){});
});
</script>
<?php }

/* ─────────────────────────────────────────────
   NEGATIVE EQUITY CAR LOAN CALCULATOR (trading in a car you owe more on than it is worth)
───────────────────────────────────────────── */
function fs_calc2_negative_equity() { ?>
<div class="fsc-wrap" id="fs-ne">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <h3 class="fsc-section-title">Your current car</h3>
    <?php fsl_field( 'ne-val', 'Trade-in value ($)', 12000, [ 'min' => 0, 'step' => 100, 'hint' => 'What a dealer would give you, not the private-sale price.' ] ); ?>
    <?php fsl_field( 'ne-pay', 'Loan payoff amount ($)', 16000, [ 'min' => 0, 'step' => 100, 'hint' => 'Ask your lender for the 10-day payoff figure.' ] ); ?>
    <h3 class="fsc-section-title">The new car</h3>
    <?php fsl_field( 'ne-price', 'New car price ($)', 28000, [ 'min' => 1000, 'step' => 500 ] ); ?>
    <?php fsl_field( 'ne-down', 'Cash down payment ($)', 2000, [ 'min' => 0, 'step' => 100 ] ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'ne-tax', 'Sales tax (%)', 6, [ 'min' => 0, 'max' => 15, 'step' => 0.01 ] ); ?>
      <?php fsl_field( 'ne-fees', 'Fees ($)', 500, [ 'min' => 0, 'step' => 50, 'hint' => 'Title, registration, documentation.' ] ); ?>
    </div>
    <div class="fsl-row2">
      <?php fsl_field( 'ne-apr', 'Loan APR (%)', 7.5, [ 'min' => 0, 'max' => 30, 'step' => 0.01 ] ); ?>
      <?php fsl_field( 'ne-term', 'Term (months)', 72, [ 'min' => 12, 'max' => 96, 'step' => 12 ] ); ?>
    </div>
    <button type="button" class="fsc-btn" id="ne-go">Calculate</button>
    <p class="fsl-error" id="ne-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="ne-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'ne-eq', 'Equity in your current car', 'primary' );
      fsl_card( 'ne-loan', 'New loan amount' );
      fsl_card( 'ne-ltv', 'Loan as a share of the car’s price' );
      fsl_card( 'ne-pay-v', 'Monthly payment', 'secondary' );
      fsl_card( 'ne-extra', 'Extra per month from rolled-in debt', 'gold' );
      fsl_card( 'ne-int', 'Total interest on the new loan' );
      fsl_card( 'ne-cost', 'Extra paid over the loan because of it', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="ne-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var val=$('ne-val'), pay=$('ne-pay'), price=$('ne-price'), down=$('ne-down'), tax=$('ne-tax')/100, fees=$('ne-fees'), apr=$('ne-apr')/1200, n=Math.max(1,Math.round($('ne-term')));
    if(!FSL.valid(price>0,'ne-err','Enter the new car price.'))return;
    var eq=val-pay, neg=Math.max(-eq,0), pos=Math.max(eq,0);
    /* positive equity counts toward the down payment; negative equity is added to the loan */
    var base=price*(1+tax)+fees-down-pos;
    var loan=Math.max(base+neg,0), loan0=Math.max(base,0);
    var p=FSL.pmt(loan,apr,n), p0=FSL.pmt(loan0,apr,n);
    var interest=p*n-loan, extraMonth=p-p0, extraCost=(p-p0)*n;
    FSL.set('ne-eq',(eq>=0?'+':'-')+M(Math.abs(eq)));
    FSL.set('ne-loan',M(loan));FSL.set('ne-ltv',(loan/price*100).toFixed(0)+'%');FSL.set('ne-pay-v',M(p,2)+'/mo');
    FSL.set('ne-extra',neg>0?M(extraMonth,2)+'/mo':'None');FSL.set('ne-int',M(interest));FSL.set('ne-cost',neg>0?M(extraCost):'None');
    FSL.show('ne-results','grid');
    var note;
    if(neg>0)note='You owe '+M(neg)+' more than the car is worth. Rolling it into the new loan means you borrow '+M(loan)+' for a car priced at '+M(price)+' ('+(loan/price*100).toFixed(0)+'% of its price), so you start underwater again. It adds about '+M(extraMonth,2)+' a month and '+M(extraCost)+' over the loan. Paying the shortfall in cash, or waiting while you pay the loan down, avoids that.';
    else note='Your car is worth '+M(pos)+' more than you owe, so that equity reduces what you need to borrow. There is nothing to roll over.';
    FSL.set('ne-note',note);
  }
  FSL.el('ne-go').addEventListener('click',calc);FSL.bind('fs-ne',calc);calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   CASH-BACK REBATE vs LOW-APR FINANCING
───────────────────────────────────────────── */
function fs_calc2_cashback_vs_apr() { ?>
<div class="fsc-wrap" id="fs-cb">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'cb-price', 'Car price before incentives ($)', 35000, [ 'min' => 1000, 'step' => 500 ] ); ?>
    <?php fsl_field( 'cb-down', 'Down payment ($)', 3000, [ 'min' => 0, 'step' => 100 ] ); ?>
    <?php fsl_field( 'cb-term', 'Loan term (months)', 60, [ 'min' => 12, 'max' => 96, 'step' => 12 ] ); ?>
    <h3 class="fsc-section-title">Option A: take the cash rebate</h3>
    <?php fsl_field( 'cb-reb', 'Cash-back rebate ($)', 3000, [ 'min' => 0, 'step' => 100 ] ); ?>
    <?php fsl_field( 'cb-apr1', 'Your own loan APR (%)', 6.5, [ 'min' => 0, 'max' => 30, 'step' => 0.01, 'hint' => 'From your bank or credit union.' ] ); ?>
    <h3 class="fsc-section-title">Option B: take the low-APR offer</h3>
    <?php fsl_field( 'cb-apr2', 'Promotional APR (%)', 1.9, [ 'min' => 0, 'max' => 30, 'step' => 0.01 ] ); ?>
    <button type="button" class="fsc-btn" id="cb-go">Compare</button>
    <p class="fsl-error" id="cb-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="cb-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'cb-win', 'Cheaper option', 'primary' );
      fsl_card( 'cb-save', 'You save' );
      fsl_card( 'cb-pa', 'A: monthly payment (rebate)' );
      fsl_card( 'cb-pb', 'B: monthly payment (low APR)', 'secondary' );
      fsl_card( 'cb-ta', 'A: total of payments' );
      fsl_card( 'cb-tb', 'B: total of payments' );
      fsl_card( 'cb-be', 'Break-even promotional APR', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="cb-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var P=$('cb-price'), dn=$('cb-down'), n=Math.max(1,Math.round($('cb-term'))), reb=$('cb-reb'), a1=$('cb-apr1')/1200, a2=$('cb-apr2')/1200;
    if(!FSL.valid(P>dn,'cb-err','The down payment must be less than the price.'))return;
    var A=P-reb-dn, B=P-dn;
    if(!FSL.valid(A>0,'cb-err','The rebate and down payment cannot be more than the price.'))return;
    var pa=FSL.pmt(A,a1,n), pb=FSL.pmt(B,a2,n), ta=pa*n, tb=pb*n;
    /* the promo rate at which both options cost the same in total */
    var lo=0, hi=Math.max(a1,0.0001);
    for(var i=0;i<60;i++){var m=(lo+hi)/2; if(FSL.pmt(B,m,n)*n>ta)hi=m; else lo=m;}
    var be=lo*1200, diff=ta-tb;
    FSL.set('cb-win',Math.abs(diff)<0.5?'About the same':(diff>0?'B: low APR':'A: cash rebate'));
    FSL.set('cb-save',M(Math.abs(diff)));FSL.set('cb-pa',M(pa,2)+'/mo');FSL.set('cb-pb',M(pb,2)+'/mo');FSL.set('cb-ta',M(ta));FSL.set('cb-tb',M(tb));
    FSL.set('cb-be',be.toFixed(2)+'%');
    FSL.show('cb-results','grid');
    FSL.set('cb-note',(diff>0?'The low-APR offer costs '+M(diff)+' less over the loan.':'Taking the rebate costs '+M(-diff)+' less over the loan.')+' The two options tie when the promotional rate is about '+be.toFixed(2)+'%: a lower promo rate than that favors the low-APR deal, a higher one favors the rebate. Compare with your own pre-approved loan rate, and check that the low-APR offer is available for your credit score and term.');
  }
  FSL.el('cb-go').addEventListener('click',calc);FSL.bind('fs-cb',calc);calc();
});
</script>
<?php }
