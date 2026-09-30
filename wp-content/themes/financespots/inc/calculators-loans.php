<?php
/**
 * Loan calculators (v2): mortgage, auto, personal, student, home equity.
 * All use the shared engine in assets/js/fs-loan-engine.js (window.FSL).
 * Debt consolidation, comparison, amortization, refinance and FHA live in
 * calculators-loans-2.php.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

/* ───────── markup helpers ───────── */
function fsl_field( $id, $label, $value, $o = [] ) {
    $attrs = '';
    foreach ( [ 'step', 'min', 'max' ] as $a ) {
        if ( isset( $o[ $a ] ) ) $attrs .= ' ' . $a . '="' . esc_attr( $o[ $a ] ) . '"';
    }
    $type = $o['type'] ?? 'number';
    echo '<div class="fsc-field"' . ( isset( $o['wrap_id'] ) ? ' id="' . esc_attr( $o['wrap_id'] ) . '"' : '' ) . '><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
    echo '<input type="' . esc_attr( $type ) . '" id="' . esc_attr( $id ) . '" value="' . esc_attr( $value ) . '"' . ( 'number' === $type ? ' inputmode="decimal"' : '' ) . $attrs . '>';
    if ( ! empty( $o['hint'] ) ) echo '<small class="fsl-hint">' . wp_kses_post( $o['hint'] ) . '</small>';
    echo '</div>';
}
function fsl_select( $id, $label, $options, $selected, $o = [] ) {
    echo '<div class="fsc-field"' . ( isset( $o['wrap_id'] ) ? ' id="' . esc_attr( $o['wrap_id'] ) . '"' : '' ) . '><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label><select id="' . esc_attr( $id ) . '">';
    foreach ( $options as $v => $t ) {
        echo '<option value="' . esc_attr( $v ) . '"' . selected( (string) $selected, (string) $v, false ) . '>' . esc_html( $t ) . '</option>';
    }
    echo '</select>';
    if ( ! empty( $o['hint'] ) ) echo '<small class="fsl-hint">' . wp_kses_post( $o['hint'] ) . '</small>';
    echo '</div>';
}
function fsl_check( $id, $label, $checked = false ) {
    echo '<label class="fsl-check" for="' . esc_attr( $id ) . '"><input type="checkbox" id="' . esc_attr( $id ) . '"' . checked( $checked, true, false ) . '> <span>' . esc_html( $label ) . '</span></label>';
}
function fsl_card( $id, $label, $mod = '' ) {
    echo '<div class="fsc-result-card' . ( $mod ? ' fsc-result-card--' . esc_attr( $mod ) : '' ) . '"><div class="fsc-result-label">' . esc_html( $label ) . '</div><div class="fsc-result-value" id="' . esc_attr( $id ) . '">--</div></div>';
}

/* ─────────────────────────────────────────────
   1. MORTGAGE CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_mortgage() { ?>
<div class="fsc-wrap" id="fs-mg">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'mg-price', 'Home price ($)', 400000, [ 'min' => 10000, 'step' => 1000 ] ); ?>
    <div class="fsc-field">
      <label for="mg-down">Down payment</label>
      <div class="fsl-row2">
        <input type="number" id="mg-down" value="80000" min="0" step="1000" inputmode="decimal" aria-label="Down payment in dollars">
        <input type="number" id="mg-dpct" value="20" min="0" max="100" step="0.5" inputmode="decimal" aria-label="Down payment percent"><span class="fsl-unit">%</span>
      </div>
    </div>
    <?php fsl_field( 'mg-rate', 'Interest rate (% APR)', 7.0, [ 'step' => 0.05, 'min' => 0, 'max' => 25, 'hint' => 'Compare with the weekly <a href="https://www.freddiemac.com/pmms" rel="noopener" target="_blank">Freddie Mac average</a>.' ] ); ?>
    <?php fsl_select( 'mg-term', 'Loan term', [ 30 => '30 years', 25 => '25 years', 20 => '20 years', 15 => '15 years', 10 => '10 years' ], 30 ); ?>
    <?php fsl_field( 'mg-start', 'First payment month', '', [ 'type' => 'month' ] ); ?>
    <?php fsl_field( 'mg-tax', 'Property tax ($ per year)', 4800, [ 'min' => 0, 'step' => 100, 'hint' => 'US average is roughly 1% of home value; check your county.' ] ); ?>
    <?php fsl_field( 'mg-ins', 'Homeowners insurance ($ per year)', 1800, [ 'min' => 0, 'step' => 50 ] ); ?>
    <?php fsl_field( 'mg-pmi', 'PMI rate (% of loan per year)', 0.6, [ 'min' => 0, 'max' => 3, 'step' => 0.05, 'hint' => 'Charged only when down payment is under 20%; removed once the balance reaches 80% of the home price.' ] ); ?>
    <?php fsl_field( 'mg-hoa', 'HOA dues ($ per month)', 0, [ 'min' => 0, 'step' => 10 ] ); ?>
    <h3 class="fsc-section-title">Pay it off faster (optional)</h3>
    <?php fsl_field( 'mg-extra', 'Extra monthly payment ($)', 0, [ 'min' => 0, 'step' => 25 ] ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'mg-lump', 'One-time extra ($)', 0, [ 'min' => 0, 'step' => 500 ] ); ?>
      <?php fsl_field( 'mg-lumpm', 'in payment #', 12, [ 'min' => 1, 'step' => 1 ] ); ?>
    </div>
    <button type="button" class="fsc-btn" id="mg-go">Calculate payment</button>
    <p class="fsl-error" id="mg-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="mg-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'mg-total', 'Total monthly payment', 'primary' );
      fsl_card( 'mg-pi', 'Principal &amp; interest' );
      fsl_card( 'mg-loan', 'Loan amount' );
      fsl_card( 'mg-int', 'Total interest' );
      fsl_card( 'mg-payoff', 'Payoff date' );
      fsl_card( 'mg-pmi-note', 'PMI' );
      fsl_card( 'mg-saved', 'Interest saved by extra payments', 'gold' );
      fsl_card( 'mg-time', 'Time saved', 'gold' );
      fsl_card( 'mg-cost', 'Total cost (loan + tax + insurance + HOA)' );
      fsl_card( 'mg-income', 'Income needed (28% rule)' );
      ?>
    </div>
    <p class="fsl-note" id="mg-note"></p>
  </div>
</div>

<div id="mg-breakdown" style="display:none;margin-top:1.5rem">
  <h3 class="fsl-h3">Monthly payment breakdown</h3>
  <div class="fsl-chart-grid">
    <div class="fsl-chart"><canvas id="mg-pie" height="220" aria-label="Payment breakdown chart" role="img"></canvas></div>
    <div class="fsl-chart"><canvas id="mg-line" height="220" aria-label="Loan balance over time chart" role="img"></canvas></div>
  </div>
</div>

<div id="mg-compare" style="display:none;margin-top:1.5rem">
  <h3 class="fsl-h3">Compare loan terms (same price, rate and down payment)</h3>
  <div class="fsc-table-wrap" style="padding:0"><table class="fsc-table fsl-cmp"><thead><tr><th>Term</th><th>Monthly P&amp;I</th><th>Total interest</th><th>Interest saved vs 30-year</th></tr></thead><tbody id="mg-cmp-body"></tbody></table></div>
</div>
<div id="mg-sched" style="display:none;margin-top:1.5rem"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money, syncing=false;
  FSL.el('mg-start').value=FSL.defaultStartValue();
  function price(){return $('mg-price');}
  FSL.el('mg-down').addEventListener('input',function(){ if(syncing)return; syncing=true; FSL.el('mg-dpct').value=price()?(Math.round($('mg-down')/price()*1000)/10):0; syncing=false; });
  FSL.el('mg-dpct').addEventListener('input',function(){ if(syncing)return; syncing=true; FSL.el('mg-down').value=Math.round(price()*$('mg-dpct')/100); syncing=false; });
  FSL.el('mg-price').addEventListener('input',function(){ if(syncing)return; syncing=true; FSL.el('mg-down').value=Math.round(price()*$('mg-dpct')/100); syncing=false; });

  function calc(){
    var pr=price(), down=Math.min($('mg-down'),pr), P=pr-down, rate=$('mg-rate'), term=$('mg-term'), n=term*12;
    if(!FSL.valid(pr>0&&P>0&&n>0,'mg-err','Enter a home price higher than the down payment.'))return;
    var tax=$('mg-tax')/12, ins=$('mg-ins')/12, hoa=$('mg-hoa'), ltv=P/pr*100;
    var pmiMonthly=ltv>80?P*$('mg-pmi')/1200:0, pmiLimit=pr*0.8, start=FSL.startDate('mg-start');
    var side=function(m,bal){return (pmiMonthly>0&&bal>pmiLimit+0.005)?pmiMonthly:0;};
    var base=FSL.schedule({P:P,apr:rate,n:n,start:start,side:side});
    var extra=$('mg-extra'), lump=$('mg-lump'), lm=Math.max(1,Math.round($('mg-lumpm',12)));
    var hasExtra=extra>0||lump>0;
    var plan=hasExtra?FSL.schedule({P:P,apr:rate,n:n,start:start,side:side,extra:extra,lumps:[{m:lm,amount:lump}]}):base;
    var pi=base.payment, first=pi+tax+ins+hoa+(plan.rows[0]?plan.rows[0].side:0)+extra;
    var pmiMonths=plan.rows.filter(function(r){return r.side>0;}).length;
    FSL.set('mg-total',M(first)+'/mo');
    FSL.set('mg-pi',M(pi)+'/mo'+(extra>0?' + '+M(extra)+' extra':''));
    FSL.set('mg-loan',M(P)+' ('+ltv.toFixed(1)+'% LTV)');
    FSL.set('mg-int',M(plan.totalInterest));
    FSL.set('mg-payoff',FSL.dateText(plan.payoffDate)+' ('+FSL.monthsText(plan.months)+')');
    FSL.set('mg-pmi-note',pmiMonthly>0?M(pmiMonthly)+'/mo for '+pmiMonths+' mo ('+M(plan.totalSide)+' total)':'None (20%+ down)');
    FSL.set('mg-saved',hasExtra?M(base.totalInterest-plan.totalInterest):'Add an extra payment');
    FSL.set('mg-time',hasExtra?FSL.monthsText(Math.max(base.months-plan.months,0)):'--');
    var life=plan.totalPaid+(tax+ins+hoa)*plan.months+plan.totalSide;
    FSL.set('mg-cost',M(life));
    FSL.set('mg-income',M(first/0.28*12)+'/yr gross');
    FSL.show('mg-results','grid'); FSL.show('mg-breakdown'); FSL.show('mg-compare');
    FSL.set('mg-note','At '+rate+'% over '+term+' years you would pay '+M(base.totalInterest)+' in interest, about '+(base.totalInterest/P).toFixed(1)+' times the amount borrowed'+(ltv>80?'. Putting 20% down ('+M(pr*0.2)+') would remove PMI.':'.'));

    FSL.chart('mg-pie',{type:'doughnut',data:{labels:['Principal & interest','Property tax','Insurance','PMI','HOA'],datasets:[{data:[pi,tax,ins,pmiMonthly,hoa],backgroundColor:['#00C896','#F59E0B','#3B82F6','#8B5CF6','#94A3B8']}]},options:{plugins:{legend:{position:'bottom',labels:{font:{size:11}}}},cutout:'60%'}});
    var series=[{label:hasExtra?'With extra payments':'Balance',rows:plan.rows,start:P}];
    if(hasExtra)series.push({label:'Standard schedule',rows:base.rows,start:P});
    FSL.balanceChart('mg-line',null,series);

    var body='',b30=null;
    [30,20,15,10].forEach(function(y){ var s=FSL.schedule({P:P,apr:rate,n:y*12,start:start}); if(y===30)b30=s.totalInterest;
      body+='<tr><td><strong>'+y+' years</strong></td><td>'+M(s.payment)+'</td><td>'+M(s.totalInterest)+'</td><td>'+(y===30?'--':M(b30-s.totalInterest))+'</td></tr>'; });
    FSL.el('mg-cmp-body').innerHTML=body;
    FSL.renderSchedule('mg-sched',plan,{sideLabel:'PMI',filename:'mortgage-amortization-schedule'});
  }
  FSL.el('mg-go').addEventListener('click',calc);
  FSL.bind('fs-mg',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   2. AUTO LOAN CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_auto_loan() { ?>
<div class="fsc-wrap" id="fs-al">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'al-price', 'Vehicle price ($)', 35000, [ 'min' => 1000, 'step' => 500 ] ); ?>
    <?php fsl_field( 'al-down', 'Cash down payment ($)', 5000, [ 'min' => 0, 'step' => 250 ] ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'al-trade', 'Trade-in value ($)', 0, [ 'min' => 0, 'step' => 250 ] ); ?>
      <?php fsl_field( 'al-owed', 'Still owed on trade-in ($)', 0, [ 'min' => 0, 'step' => 250 ] ); ?>
    </div>
    <?php fsl_field( 'al-rebate', 'Manufacturer rebate ($)', 0, [ 'min' => 0, 'step' => 250 ] ); ?>
    <?php fsl_field( 'al-rate', 'Loan APR (%)', 7.0, [ 'min' => 0, 'max' => 40, 'step' => 0.1, 'hint' => 'New-car loans averaged about 7% APR in 2026 (Edmunds); used cars cost more.' ] ); ?>
    <?php fsl_select( 'al-term', 'Loan term', [ 24 => '24 months', 36 => '36 months', 48 => '48 months', 60 => '60 months', 72 => '72 months', 84 => '84 months' ], 60 ); ?>
    <?php fsl_field( 'al-tax', 'Sales tax rate (%)', 7.0, [ 'min' => 0, 'max' => 15, 'step' => 0.05, 'hint' => 'State + local combined rate.' ] ); ?>
    <?php fsl_field( 'al-fees', 'Title, registration &amp; doc fees ($)', 600, [ 'min' => 0, 'step' => 50 ] ); ?>
    <?php fsl_check( 'al-taxtrade', 'Trade-in reduces taxable price (most states)', true ); ?>
    <?php fsl_check( 'al-finfees', 'Roll tax and fees into the loan', true ); ?>
    <?php fsl_field( 'al-extra', 'Extra monthly payment ($, optional)', 0, [ 'min' => 0, 'step' => 25 ] ); ?>
    <button type="button" class="fsc-btn" id="al-go">Calculate payment</button>
    <p class="fsl-error" id="al-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="al-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'al-monthly', 'Monthly payment', 'primary' );
      fsl_card( 'al-financed', 'Amount financed' );
      fsl_card( 'al-interest', 'Total interest' );
      fsl_card( 'al-tax-amt', 'Sales tax' );
      fsl_card( 'al-due', 'Due at signing' );
      fsl_card( 'al-out', 'Total out-of-pocket (down + payments)' );
      fsl_card( 'al-payoff', 'Payoff date' );
      fsl_card( 'al-saved', 'Interest saved by extra payments', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="al-note"></p>
  </div>
</div>
<div id="al-compare" style="display:none;margin-top:1.5rem">
  <h3 class="fsl-h3">How the loan term changes your cost</h3>
  <div class="fsc-table-wrap" style="padding:0"><table class="fsc-table fsl-cmp"><thead><tr><th>Term</th><th>Monthly payment</th><th>Total interest</th><th>Total paid</th></tr></thead><tbody id="al-cmp-body"></tbody></table></div>
</div>
<div id="al-sched" style="display:none;margin-top:1.5rem"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var price=$('al-price'), down=$('al-down'), trade=$('al-trade'), owed=$('al-owed'), rebate=$('al-rebate');
    var rate=$('al-rate'), term=parseInt(FSL.el('al-term').value,10), rt=$('al-tax')/100, fees=$('al-fees'), extra=$('al-extra');
    var taxable=Math.max(price-(FSL.el('al-taxtrade').checked?trade:0),0), tax=taxable*rt;
    var negEq=Math.max(owed-trade,0), roll=FSL.el('al-finfees').checked;
    var P=price-rebate-down-trade+owed+(roll?tax+fees:0);
    if(!FSL.valid(price>0&&P>0,'al-err','Your down payment, trade-in and rebate cover the full price, so no loan is needed.'))return;
    var due=down+(roll?0:tax+fees);
    function run(n,ex){return FSL.schedule({P:P,apr:rate,n:n,extra:ex||0});}
    var base=run(term,0), plan=extra>0?run(term,extra):base;
    FSL.set('al-monthly',M(base.payment)+'/mo'+(extra>0?' + '+M(extra):''));
    FSL.set('al-financed',M(P)+(negEq>0?' (incl. '+M(negEq)+' negative equity)':''));
    FSL.set('al-interest',M(plan.totalInterest));
    FSL.set('al-tax-amt',M(tax));
    FSL.set('al-due',M(due));
    FSL.set('al-out',M(due+plan.totalPaid));
    FSL.set('al-payoff',FSL.dateText(plan.payoffDate)+' ('+FSL.monthsText(plan.months)+')');
    FSL.set('al-saved',extra>0?M(base.totalInterest-plan.totalInterest):'Add an extra payment');
    FSL.show('al-results','grid'); FSL.show('al-compare');
    var note='Interest adds '+M(plan.totalInterest)+' ('+Math.round(plan.totalInterest/P*100)+'% of the amount financed).';
    if(term>=72)note+=' Terms of 72+ months raise the chance you owe more than the car is worth.';
    if(negEq>0)note+=' Rolling '+M(negEq)+' of negative equity into the loan means you pay interest on your old car.';
    FSL.set('al-note',note);
    var body='';
    [36,48,60,72,84].forEach(function(t){var s=run(t,0);body+='<tr'+(t===term?' class="is-current"':'')+'><td><strong>'+t+' months</strong></td><td>'+M(s.payment)+'</td><td>'+M(s.totalInterest)+'</td><td>'+M(s.totalPaid)+'</td></tr>';});
    FSL.el('al-cmp-body').innerHTML=body;
    FSL.renderSchedule('al-sched',plan,{filename:'auto-loan-schedule'});
  }
  FSL.el('al-go').addEventListener('click',calc); FSL.bind('fs-al',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   3. PERSONAL LOAN CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_personal_loan() { ?>
<div class="fsc-wrap" id="fs-pl">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'pl-amount', 'Loan amount ($)', 15000, [ 'min' => 500, 'step' => 500 ] ); ?>
    <?php fsl_field( 'pl-rate', 'Interest rate (% APR before fees)', 12.0, [ 'min' => 0, 'max' => 40, 'step' => 0.1, 'hint' => 'Banks averaged about 11.9% on 24-month personal loans in Q2 2026 (Federal Reserve G.19).' ] ); ?>
    <?php fsl_select( 'pl-term', 'Loan term', [ 12 => '12 months', 24 => '24 months', 36 => '36 months', 48 => '48 months', 60 => '60 months', 72 => '72 months', 84 => '84 months' ], 36 ); ?>
    <?php fsl_field( 'pl-fee', 'Origination fee (%)', 3, [ 'min' => 0, 'max' => 12, 'step' => 0.25, 'hint' => 'Usually 0–8%, taken out of the money you receive.' ] ); ?>
    <?php fsl_field( 'pl-start', 'First payment month', '', [ 'type' => 'month' ] ); ?>
    <?php fsl_field( 'pl-extra', 'Extra monthly payment ($, optional)', 0, [ 'min' => 0, 'step' => 25 ] ); ?>
    <?php fsl_field( 'pl-budget', 'Monthly budget ($, optional)', 0, [ 'min' => 0, 'step' => 25, 'hint' => 'Enter what you can afford to see the largest loan that fits.' ] ); ?>
    <button type="button" class="fsc-btn" id="pl-go">Calculate loan</button>
    <p class="fsl-error" id="pl-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="pl-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'pl-monthly', 'Monthly payment', 'primary' );
      fsl_card( 'pl-apr', 'True APR (with fee)', 'secondary' );
      fsl_card( 'pl-net', 'Cash you receive' );
      fsl_card( 'pl-feeval', 'Origination fee' );
      fsl_card( 'pl-interest', 'Total interest' );
      fsl_card( 'pl-cost', 'Total cost of borrowing (interest + fee)' );
      fsl_card( 'pl-total', 'Total repaid' );
      fsl_card( 'pl-payoff', 'Payoff date' );
      fsl_card( 'pl-saved', 'Interest saved by extra payments', 'gold' );
      fsl_card( 'pl-max', 'Largest loan for your budget', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="pl-note"></p>
  </div>
</div>
<div id="pl-sched" style="display:none;margin-top:1.5rem"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  FSL.el('pl-start').value=FSL.defaultStartValue();
  function calc(){
    var P=$('pl-amount'), rate=$('pl-rate'), n=parseInt(FSL.el('pl-term').value,10), feePct=$('pl-fee'), extra=$('pl-extra'), budget=$('pl-budget');
    if(!FSL.valid(P>0,'pl-err','Enter a loan amount greater than zero.'))return;
    var fee=P*feePct/100, net=P-fee, start=FSL.startDate('pl-start');
    var base=FSL.schedule({P:P,apr:rate,n:n,start:start}), plan=extra>0?FSL.schedule({P:P,apr:rate,n:n,start:start,extra:extra}):base;
    var pays=plan.rows.map(function(r){return r.pay;});
    var trueApr=FSL.apr(net,pays);
    FSL.set('pl-monthly',M(base.payment,2)+'/mo'+(extra>0?' + '+M(extra):''));
    FSL.set('pl-apr',FSL.pct(trueApr,2));
    FSL.set('pl-net',M(net));
    FSL.set('pl-feeval',M(fee));
    FSL.set('pl-interest',M(plan.totalInterest));
    FSL.set('pl-cost',M(plan.totalInterest+fee));
    FSL.set('pl-total',M(plan.totalPaid));
    FSL.set('pl-payoff',FSL.dateText(plan.payoffDate)+' ('+FSL.monthsText(plan.months)+')');
    FSL.set('pl-saved',extra>0?M(base.totalInterest-plan.totalInterest):'Add an extra payment');
    var r=rate/1200, maxL=budget>0?(r===0?budget*n:budget*(1-Math.pow(1+r,-n))/r):0;
    FSL.set('pl-max',budget>0?M(maxL)+' (before fee)':'Enter a monthly budget');
    FSL.show('pl-results','grid');
    FSL.set('pl-note',feePct>0?'The fee lifts your true APR from '+rate.toFixed(2)+'% to '+FSL.pct(trueApr,2)+'. Compare offers by APR, not by interest rate alone.':'With no fee, your APR equals your interest rate.');
    FSL.renderSchedule('pl-sched',plan,{filename:'personal-loan-schedule'});
  }
  FSL.el('pl-go').addEventListener('click',calc); FSL.bind('fs-pl',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   4. STUDENT LOAN CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_student_loan() { ?>
<div class="fsc-wrap" id="fs-sl">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'sl-amount', 'Total loan balance ($)', 38000, [ 'min' => 500, 'step' => 500, 'hint' => 'The average federal borrower owes roughly $38,000.' ] ); ?>
    <?php fsl_field( 'sl-rate', 'Interest rate (%)', 6.52, [ 'min' => 0, 'max' => 15, 'step' => 0.01, 'hint' => '2026-27 federal rates: 6.52% undergraduate, 8.07% graduate, 9.07% Parent PLUS.' ] ); ?>
    <?php fsl_select( 'sl-plan', 'Repayment plan', [
        'tiered'    => 'Standard (new loans from July 2026; term set by balance)',
        'standard'  => 'Standard 10-year',
        'graduated' => 'Graduated 10-year (payments step up)',
        'extended'  => 'Extended 25-year',
        'custom'    => 'Custom term',
    ], 'standard', [ 'hint' => 'Income-driven plans (RAP, IBR) depend on your income and are not modeled here.' ] ); ?>
    <?php fsl_field( 'sl-custom', 'Custom term (years)', 15, [ 'min' => 1, 'max' => 30, 'wrap_id' => 'sl-custom-wrap' ] ); ?>
    <?php fsl_field( 'sl-grace', 'Months before repayment starts', 6, [ 'min' => 0, 'max' => 72, 'step' => 1, 'hint' => 'In-school time plus the 6-month grace period.' ] ); ?>
    <?php fsl_check( 'sl-accrue', 'Interest accrues before repayment (unsubsidized loans)', true ); ?>
    <?php fsl_field( 'sl-extra', 'Extra monthly payment ($, optional)', 0, [ 'min' => 0, 'step' => 25 ] ); ?>
    <button type="button" class="fsc-btn" id="sl-go">Calculate repayment</button>
    <p class="fsl-error" id="sl-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="sl-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'sl-monthly', 'Monthly payment', 'primary' );
      fsl_card( 'sl-start-bal', 'Balance when repayment starts' );
      fsl_card( 'sl-term', 'Repayment term' );
      fsl_card( 'sl-interest', 'Total interest' );
      fsl_card( 'sl-total', 'Total repaid' );
      fsl_card( 'sl-payoff', 'Payoff date' );
      fsl_card( 'sl-saved', 'Interest saved by extra payments', 'gold' );
      fsl_card( 'sl-income', 'Income where payment is 10% of gross', 'secondary' );
      ?>
    </div>
    <p class="fsl-note" id="sl-note"></p>
  </div>
</div>
<div id="sl-sched" style="display:none;margin-top:1.5rem"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function termYears(plan,bal){
    if(plan==='standard'||plan==='graduated')return 10;
    if(plan==='extended')return 25;
    if(plan==='tiered')return bal<25000?10:(bal<50000?15:(bal<100000?20:25));
    return Math.max(1,Math.round($('sl-custom',15)));
  }
  function build(P,rate,n,plan,extra,grad){
    if(!grad)return FSL.schedule({P:P,apr:rate,n:n,extra:extra});
    var lo=0,hi=P/ (n/2),s; /* bisection for first-step payment p0 */
    function mk(p0){return FSL.schedule({P:P,apr:rate,n:n,extra:extra,maxMonths:n,paymentFn:function(m){return p0*Math.pow(1.2,Math.floor((m-1)/24));}});}
    for(var i=0;i<60;i++){var mid=(lo+hi)/2; s=mk(mid); if(s.endBalance>0.5)lo=mid; else hi=mid;}
    s=mk(hi); s.p0=hi; s.pEnd=hi*Math.pow(1.2,Math.floor((n-1)/24)); return s;
  }
  function calc(){
    var P0=$('sl-amount'), rate=$('sl-rate'), plan=FSL.el('sl-plan').value, grace=Math.max(0,Math.round($('sl-grace'))), extra=$('sl-extra');
    if(!FSL.valid(P0>0,'sl-err','Enter a loan balance greater than zero.'))return;
    FSL.el('sl-custom-wrap').style.display=plan==='custom'?'flex':'none';
    var accrued=FSL.el('sl-accrue').checked?P0*rate/1200*grace:0, P=P0+accrued;
    var yrs=termYears(plan,P0), n=yrs*12, grad=plan==='graduated';
    var base=build(P,rate,n,plan,0,grad), sched=extra>0?build(P,rate,n,plan,extra,grad):base;
    var pay=grad?base.p0:base.payment;
    FSL.set('sl-monthly',grad?M(base.p0)+'/mo rising to '+M(base.pEnd):M(pay)+'/mo'+(extra>0?' + '+M(extra):''));
    FSL.set('sl-start-bal',M(P)+(accrued>0?' (incl. '+M(accrued)+' interest)':''));
    FSL.set('sl-term',yrs+' years');
    FSL.set('sl-interest',M(sched.totalInterest+accrued));
    FSL.set('sl-total',M(sched.totalPaid));
    var pd=FSL.addMonths(sched.payoffDate,grace);
    FSL.set('sl-payoff',FSL.dateText(pd));
    FSL.set('sl-saved',extra>0?M(base.totalInterest-sched.totalInterest):'Add an extra payment');
    FSL.set('sl-income',M((grad?base.p0:pay)*12/0.10)+'/yr');
    FSL.show('sl-results','grid');
    var note='Interest adds '+M(sched.totalInterest+accrued)+' on top of the original '+M(P0)+'.';
    if(plan==='tiered')note+=' Under the July 2026 rules, balances under $25,000 repay over 10 years, $25,000-$49,999 over 15, $50,000-$99,999 over 20, and $100,000+ over 25.';
    if(grad)note+=' Graduated payments are an estimate: they rise about 20% every two years until paid off.';
    FSL.set('sl-note',note);
    FSL.renderSchedule('sl-sched',sched,{filename:'student-loan-schedule'});
  }
  FSL.el('sl-go').addEventListener('click',calc); FSL.bind('fs-sl',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   5. HOME EQUITY LOAN / HELOC CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_heloc() { ?>
<div class="fsc-wrap" id="fs-he">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'he-value', 'Home value ($)', 500000, [ 'min' => 50000, 'step' => 5000 ] ); ?>
    <?php fsl_field( 'he-balance', 'Mortgage balance ($)', 300000, [ 'min' => 0, 'step' => 5000 ] ); ?>
    <?php fsl_select( 'he-cltv', 'Max combined loan-to-value (CLTV)', [ 75 => '75%', 80 => '80%', 85 => '85% (common)', 90 => '90%' ], 85, [ 'hint' => 'Most lenders cap CLTV between 80% and 90%.' ] ); ?>
    <?php fsl_select( 'he-type', 'Product', [ 'loan' => 'Home equity loan (lump sum, fixed rate)', 'heloc' => 'HELOC (credit line, variable rate)' ], 'loan' ); ?>
    <?php fsl_field( 'he-amount', 'Amount to borrow ($)', 60000, [ 'min' => 1000, 'step' => 1000 ] ); ?>
    <?php fsl_field( 'he-rate', 'Interest rate (%)', 8.5, [ 'min' => 0, 'max' => 25, 'step' => 0.05, 'hint' => 'HELOC rates float with the prime rate; this calculator holds the rate constant.' ] ); ?>
    <?php fsl_field( 'he-draw', 'HELOC draw period (years, interest-only)', 10, [ 'min' => 1, 'max' => 15, 'wrap_id' => 'he-draw-wrap' ] ); ?>
    <?php fsl_field( 'he-term', 'Repayment term (years)', 15, [ 'min' => 1, 'max' => 30 ] ); ?>
    <?php fsl_field( 'he-closing', 'Closing costs ($)', 1000, [ 'min' => 0, 'step' => 100 ] ); ?>
    <button type="button" class="fsc-btn" id="he-go">Calculate</button>
    <p class="fsl-error" id="he-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="he-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'he-max', 'Maximum you can borrow', 'primary' );
      fsl_card( 'he-monthly', 'Monthly payment' );
      fsl_card( 'he-after', 'Payment after draw period' );
      fsl_card( 'he-interest', 'Total interest' );
      fsl_card( 'he-apr', 'APR with closing costs', 'secondary' );
      fsl_card( 'he-newcltv', 'CLTV after new loan' );
      fsl_card( 'he-equity', 'Current home equity' );
      fsl_card( 'he-total', 'Total cost (interest + closing)' );
      ?>
    </div>
    <p class="fsl-note" id="he-note"></p>
  </div>
</div>
<div id="he-sched" style="display:none;margin-top:1.5rem"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var val=$('he-value'), bal=$('he-balance'), cltv=parseInt(FSL.el('he-cltv').value,10)/100, type=FSL.el('he-type').value;
    var amt=$('he-amount'), rate=$('he-rate'), draw=type==='heloc'?Math.round($('he-draw',10)):0, rep=Math.round($('he-term',15)), closing=$('he-closing');
    FSL.el('he-draw-wrap').style.display=type==='heloc'?'flex':'none';
    if(!FSL.valid(val>0&&amt>0&&rep>0,'he-err','Enter a home value, loan amount and term.'))return;
    var maxB=Math.max(val*cltv-bal,0), equity=val-bal, newCltv=(bal+amt)/val*100;
    var n=(draw+rep)*12, s=FSL.schedule({P:amt,apr:rate,n:n,ioMonths:draw*12});
    var first=s.rows[0]?s.rows[0].pay:0, after=draw>0&&s.rows[draw*12]?s.rows[draw*12].pay:first;
    var net=amt-closing, apr=FSL.apr(net,s.rows.map(function(r){return r.pay;}));
    FSL.set('he-max',M(maxB));
    FSL.set('he-monthly',M(first,2)+'/mo'+(draw>0?' (interest-only)':''));
    FSL.set('he-after',draw>0?M(after,2)+'/mo':'Same as above');
    FSL.set('he-interest',M(s.totalInterest));
    FSL.set('he-apr',closing>0?FSL.pct(apr,2):FSL.pct(rate,2));
    FSL.set('he-newcltv',newCltv.toFixed(1)+'%');
    FSL.set('he-equity',M(equity));
    FSL.set('he-total',M(s.totalInterest+closing));
    FSL.show('he-results','grid');
    var note=amt>maxB?'That amount is above the maximum your lender would likely approve ('+M(maxB)+' at '+(cltv*100)+'% CLTV).':'Your home is the collateral: if you cannot repay, the lender can foreclose.';
    if(type==='heloc')note+=' A HELOC rate can rise or fall, so your payment may change.';
    FSL.set('he-note',note);
    FSL.renderSchedule('he-sched',s,{filename:'home-equity-schedule'});
  }
  FSL.el('he-go').addEventListener('click',calc); FSL.bind('fs-he',calc); calc();
});
</script>
<?php }
