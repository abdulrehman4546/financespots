<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function fs_render_calculator( $tool_type, $tool_title ) {
    switch ( $tool_type ) {

        case 'loan_payment':
        case 'affordability':
            fs_calc_loan_payment( $tool_type, $tool_title ); break;

        case 'amortization':
            fs_calc_amortization( $tool_title ); break;

        case 'refinance':
            fs_calc_refinance(); break;

        case 'loan_compare':
            fs_calc_loan_compare(); break;

        case 'compound':
            fs_calc_compound( $tool_title ); break;

        case 'roi':
            fs_calc_roi( $tool_title ); break;

        case 'dividend':
            fs_calc_dividend(); break;

        case 'dca':
            fs_calc_dca( $tool_title ); break;

        case 'portfolio':
            fs_calc_portfolio(); break;

        case 'capital_gains':
            fs_calc_capital_gains( $tool_title ); break;

        case 'cagr':
            fs_calc_cagr(); break;

        case 'pv':
            fs_calc_pv(); break;

        case 'breakeven':
            fs_calc_breakeven(); break;

        case 'inflation':
            fs_calc_inflation(); break;

        case 'sharpe':
            fs_calc_sharpe(); break;

        case 'bond':
            fs_calc_bond(); break;

        case 'options':
            fs_calc_options(); break;

        case 'income_tax':
            fs_calc_income_tax( $tool_title ); break;

        case 'self_emp_tax':
            fs_calc_self_emp_tax(); break;

        case 'savings_goal':
            fs_calc_savings_goal( $tool_title ); break;

        case 'savings_52':
            fs_calc_52week(); break;

        case 'retirement':
            fs_calc_retirement( $tool_title ); break;

        case 'fire':
            fs_calc_fire(); break;

        case 'budget':
            fs_calc_budget( $tool_title ); break;

        case 'budget_503020':
            fs_calc_503020(); break;

        case 'dti':
            fs_calc_dti(); break;

        case 'net_worth':
            fs_calc_net_worth(); break;

        case 'currency':
            fs_calc_currency(); break;

        case 'crypto_convert':
            fs_calc_crypto_convert( $tool_title ); break;

        case 'crypto_pnl':
            fs_calc_crypto_pnl(); break;

        case 'staking':
            fs_calc_staking( $tool_title ); break;

        case 'mining':
            fs_calc_mining(); break;

        case 'risk':
            fs_calc_risk(); break;

        case 'simple_calc':
            fs_calc_simple( $tool_title ); break;

        /* ── New specialized loan types ── */
        case 'mortgage_calc':
            fs_calc_mortgage(); break;
        case 'auto_loan_calc':
            fs_calc_auto_loan(); break;
        case 'personal_loan_calc':
            fs_calc_personal_loan(); break;
        case 'student_loan_calc':
            fs_calc_student_loan(); break;
        case 'heloc_calc':
            fs_calc_heloc(); break;
        case 'debt_consol_calc':
            fs_calc_debt_consolidation(); break;
        case 'fha_loan_calc':
            fs_calc_fha_loan(); break;
        case 'balloon_calc':
            fs_calc_balloon_loan(); break;
        case 'interest_only_calc':
            fs_calc_interest_only(); break;
        case 'loan_payoff_calc':
            fs_calc_loan_payoff(); break;
        case 'commercial_calc':
            fs_calc_commercial_loan(); break;
        case 'bridge_calc':
            fs_calc_bridge_loan(); break;

        default:
            fs_calc_simple( $tool_title ); break;
    }
}

/* ─────────────────────────────────────────────
   SHARED WRAPPER HELPERS
───────────────────────────────────────────── */
function fs_calc_wrap_open( $id = 'fs-calc' ) {
    echo '<div class="fsc-wrap" id="' . esc_attr($id) . '">';
}
function fs_calc_wrap_close() {
    echo '</div>';
}

/* ─────────────────────────────────────────────
   1. LOAN PAYMENT CALCULATOR
───────────────────────────────────────────── */
function fs_calc_loan_payment( $type, $title ) { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Loan Amount ($)</label><input type="number" id="lp-amount" value="25000" min="1000"></div>
    <div class="fsc-field"><label>Annual Interest Rate (%)</label><input type="number" id="lp-rate" value="6.5" step="0.1" min="0.1"></div>
    <div class="fsc-field"><label>Loan Term (Years)</label><input type="number" id="lp-term" value="5" min="1" max="30"></div>
    <?php if($type==='affordability'):?>
    <div class="fsc-field"><label>Monthly Income ($)</label><input type="number" id="lp-income" value="6000"></div>
    <?php endif;?>
    <button class="fsc-btn" onclick="calcLoanPayment()">Calculate</button>
  </div>
  <div class="fsc-results" id="lp-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Monthly Payment</div><div class="fsc-result-value" id="lp-monthly">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Payment</div><div class="fsc-result-value" id="lp-total">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Interest</div><div class="fsc-result-value" id="lp-interest">—</div></div>
    <?php if($type==='affordability'):?>
    <div class="fsc-result-card"><div class="fsc-result-label">Debt-to-Income</div><div class="fsc-result-value" id="lp-dti">—</div></div>
    <?php endif;?>
  </div>
</div>
</div>
<script>
function calcLoanPayment(){
  var P=parseFloat(document.getElementById('lp-amount').value)||0;
  var r=parseFloat(document.getElementById('lp-rate').value)/100/12;
  var n=parseFloat(document.getElementById('lp-term').value)*12;
  if(!P||!n){return;}
  var M=r===0?P/n:P*(r*Math.pow(1+r,n))/(Math.pow(1+r,n)-1);
  var T=M*n;
  var I=T-P;
  document.getElementById('lp-monthly').textContent='$'+M.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');
  document.getElementById('lp-total').textContent='$'+T.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');
  document.getElementById('lp-interest').textContent='$'+I.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');
  var dti=document.getElementById('lp-dti');
  if(dti){var inc=parseFloat(document.getElementById('lp-income').value)||1;dti.textContent=(M/inc*100).toFixed(1)+'%';}
  document.getElementById('lp-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   2. AMORTIZATION CALCULATOR
───────────────────────────────────────────── */
function fs_calc_amortization( $title ) { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Loan Amount ($)</label><input type="number" id="am-amount" value="200000"></div>
    <div class="fsc-field"><label>Annual Interest Rate (%)</label><input type="number" id="am-rate" value="7.0" step="0.1"></div>
    <div class="fsc-field"><label>Loan Term (Years)</label><input type="number" id="am-term" value="30"></div>
    <div class="fsc-field"><label>Extra Monthly Payment ($)</label><input type="number" id="am-extra" value="0"></div>
    <button class="fsc-btn" onclick="calcAmort()">Generate Schedule</button>
  </div>
  <div class="fsc-results" id="am-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Monthly Payment</div><div class="fsc-result-value" id="am-monthly">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Paid</div><div class="fsc-result-value" id="am-total">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Interest</div><div class="fsc-result-value" id="am-interest">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Payoff Date</div><div class="fsc-result-value" id="am-payoff">—</div></div>
  </div>
</div>
<div class="fsc-table-wrap"><table class="fsc-table" id="am-table" style="display:none">
  <thead><tr><th>#</th><th>Payment</th><th>Principal</th><th>Interest</th><th>Balance</th></tr></thead>
  <tbody id="am-tbody"></tbody>
</table></div>
</div>
<script>
function calcAmort(){
  var P=parseFloat(document.getElementById('am-amount').value)||0;
  var rate=parseFloat(document.getElementById('am-rate').value)/100/12;
  var n=parseFloat(document.getElementById('am-term').value)*12;
  var extra=parseFloat(document.getElementById('am-extra').value)||0;
  if(!P||!n)return;
  var M=rate===0?P/n:P*(rate*Math.pow(1+rate,n))/(Math.pow(1+rate,n)-1);
  var bal=P,totalPaid=0,totalInt=0,rows='',month=0;
  while(bal>0&&month<n+1){
    month++;
    var interest=bal*rate;
    var principal=Math.min(M-interest+extra,bal);
    bal-=principal;
    totalInt+=interest;
    totalPaid+=principal+interest;
    if(bal<0)bal=0;
    if(month<=24||month%12===0){
      rows+='<tr><td>'+month+'</td><td>$'+(principal+interest).toFixed(0)+'</td><td>$'+principal.toFixed(0)+'</td><td>$'+interest.toFixed(0)+'</td><td>$'+Math.max(bal,0).toFixed(0)+'</td></tr>';
    }
  }
  var fmt=function(v){return '$'+v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('am-monthly').textContent=fmt(M+extra);
  document.getElementById('am-total').textContent=fmt(totalPaid);
  document.getElementById('am-interest').textContent=fmt(totalInt);
  var d=new Date();d.setMonth(d.getMonth()+month);
  document.getElementById('am-payoff').textContent=d.toLocaleDateString('en-US',{month:'short',year:'numeric'});
  document.getElementById('am-results').style.display='grid';
  document.getElementById('am-tbody').innerHTML=rows;
  document.getElementById('am-table').style.display='table';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   3. REFINANCE CALCULATOR
───────────────────────────────────────────── */
function fs_calc_refinance() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <h3 class="fsc-section-title">Current Loan</h3>
    <div class="fsc-field"><label>Remaining Balance ($)</label><input type="number" id="rf-balance" value="180000"></div>
    <div class="fsc-field"><label>Current Rate (%)</label><input type="number" id="rf-old-rate" value="7.5" step="0.1"></div>
    <div class="fsc-field"><label>Remaining Term (Months)</label><input type="number" id="rf-old-term" value="300"></div>
    <h3 class="fsc-section-title">New Loan</h3>
    <div class="fsc-field"><label>New Rate (%)</label><input type="number" id="rf-new-rate" value="6.0" step="0.1"></div>
    <div class="fsc-field"><label>New Term (Years)</label><input type="number" id="rf-new-term" value="25"></div>
    <div class="fsc-field"><label>Closing Costs ($)</label><input type="number" id="rf-costs" value="3000"></div>
    <button class="fsc-btn" onclick="calcRefi()">Calculate Savings</button>
  </div>
  <div class="fsc-results" id="rf-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Monthly Savings</div><div class="fsc-result-value" id="rf-saving">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">New Payment</div><div class="fsc-result-value" id="rf-new-pay">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Break-Even</div><div class="fsc-result-value" id="rf-break">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Lifetime Savings</div><div class="fsc-result-value" id="rf-life">—</div></div>
  </div>
</div></div>
<script>
function calcRefi(){
  var B=parseFloat(document.getElementById('rf-balance').value)||0;
  var r1=parseFloat(document.getElementById('rf-old-rate').value)/100/12;
  var n1=parseFloat(document.getElementById('rf-old-term').value);
  var r2=parseFloat(document.getElementById('rf-new-rate').value)/100/12;
  var n2=parseFloat(document.getElementById('rf-new-term').value)*12;
  var C=parseFloat(document.getElementById('rf-costs').value)||0;
  var oldPay=r1===0?B/n1:B*(r1*Math.pow(1+r1,n1))/(Math.pow(1+r1,n1)-1);
  var newPay=r2===0?B/n2:B*(r2*Math.pow(1+r2,n2))/(Math.pow(1+r2,n2)-1);
  var save=oldPay-newPay;
  var breakEven=save>0?Math.ceil(C/save):999;
  var lifeSave=save*n2-C;
  var fmt=function(v){return '$'+v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('rf-saving').textContent=fmt(save);
  document.getElementById('rf-new-pay').textContent=fmt(newPay);
  document.getElementById('rf-break').textContent=breakEven<999?breakEven+' months':'N/A';
  document.getElementById('rf-life').textContent=lifeSave>0?fmt(lifeSave):'No savings';
  document.getElementById('rf-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   4. LOAN COMPARISON
───────────────────────────────────────────── */
function fs_calc_loan_compare() { ?>
<div class="fsc-wrap">
<div class="fsc-compare-grid">
  <div class="fsc-compare-col">
    <h3 class="fsc-section-title">Loan A</h3>
    <div class="fsc-field"><label>Amount ($)</label><input type="number" id="lca-amt" value="20000"></div>
    <div class="fsc-field"><label>Rate (%)</label><input type="number" id="lca-rate" value="5.9" step="0.1"></div>
    <div class="fsc-field"><label>Term (Years)</label><input type="number" id="lca-term" value="5"></div>
  </div>
  <div class="fsc-compare-col">
    <h3 class="fsc-section-title">Loan B</h3>
    <div class="fsc-field"><label>Amount ($)</label><input type="number" id="lcb-amt" value="20000"></div>
    <div class="fsc-field"><label>Rate (%)</label><input type="number" id="lcb-rate" value="7.2" step="0.1"></div>
    <div class="fsc-field"><label>Term (Years)</label><input type="number" id="lcb-term" value="5"></div>
  </div>
</div>
<button class="fsc-btn" style="margin-top:1rem" onclick="calcCompare()">Compare Loans</button>
<div id="lc-results" style="display:none;margin-top:1.5rem">
<div class="fsc-compare-grid">
  <div class="fsc-compare-col">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Monthly Payment A</div><div class="fsc-result-value" id="lca-pay">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Interest A</div><div class="fsc-result-value" id="lca-int">—</div></div>
  </div>
  <div class="fsc-compare-col">
    <div class="fsc-result-card fsc-result-card--secondary"><div class="fsc-result-label">Monthly Payment B</div><div class="fsc-result-value" id="lcb-pay">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Interest B</div><div class="fsc-result-value" id="lcb-int">—</div></div>
  </div>
</div>
<div class="fsc-result-card fsc-result-card--gold" style="margin-top:1rem"><div class="fsc-result-label">You Save With Loan A</div><div class="fsc-result-value" id="lc-diff">—</div></div>
</div>
</div>
<script>
function loanPay(P,rate,years){var r=rate/100/12,n=years*12;return r===0?P/n:P*(r*Math.pow(1+r,n))/(Math.pow(1+r,n)-1);}
function calcCompare(){
  var aP=parseFloat(document.getElementById('lca-amt').value)||0;
  var aR=parseFloat(document.getElementById('lca-rate').value)||0;
  var aT=parseFloat(document.getElementById('lca-term').value)||1;
  var bP=parseFloat(document.getElementById('lcb-amt').value)||0;
  var bR=parseFloat(document.getElementById('lcb-rate').value)||0;
  var bT=parseFloat(document.getElementById('lcb-term').value)||1;
  var aM=loanPay(aP,aR,aT),bM=loanPay(bP,bR,bT);
  var aI=aM*aT*12-aP,bI=bM*bT*12-bP;
  var fmt=function(v){return '$'+v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('lca-pay').textContent=fmt(aM);
  document.getElementById('lca-int').textContent=fmt(aI);
  document.getElementById('lcb-pay').textContent=fmt(bM);
  document.getElementById('lcb-int').textContent=fmt(bI);
  document.getElementById('lc-diff').textContent=fmt(Math.abs(aI-bI))+' (Loan '+(aI<bI?'A':'B')+' is better)';
  document.getElementById('lc-results').style.display='block';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   5. COMPOUND INTEREST CALCULATOR
───────────────────────────────────────────── */
function fs_calc_compound( $title ) { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Initial Investment ($)</label><input type="number" id="ci-principal" value="10000"></div>
    <div class="fsc-field"><label>Monthly Contribution ($)</label><input type="number" id="ci-monthly" value="500"></div>
    <div class="fsc-field"><label>Annual Return (%)</label><input type="number" id="ci-rate" value="8" step="0.1"></div>
    <div class="fsc-field"><label>Time Period (Years)</label><input type="number" id="ci-years" value="20"></div>
    <div class="fsc-field"><label>Compounding Frequency</label>
      <select id="ci-freq"><option value="365">Daily</option><option value="12" selected>Monthly</option><option value="4">Quarterly</option><option value="2">Semi-Annually</option><option value="1">Annually</option></select>
    </div>
    <div class="fsc-field"><label>Inflation Rate (%) <small style="font-weight:400;color:#888">for real value</small></label><input type="number" id="ci-inf" value="3" step="0.1" min="0"></div>
    <button class="fsc-btn" onclick="calcCompound()">Calculate Growth</button>
  </div>
  <div class="fsc-results" id="ci-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Future Value (Nominal)</div><div class="fsc-result-value" id="ci-final">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Real Value (Inflation-Adj.)</div><div class="fsc-result-value" id="ci-real">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Contributed</div><div class="fsc-result-value" id="ci-contrib">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Interest Earned</div><div class="fsc-result-value" id="ci-earned">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Growth Multiple</div><div class="fsc-result-value" id="ci-mult">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Rule of 72 Doubling</div><div class="fsc-result-value" id="ci-r72">—</div></div>
  </div>
</div>
<!-- Stacked Area Chart -->
<div id="ci-chart-wrap" style="display:none;margin-top:1.5rem;background:#fff;border:1.5px solid var(--fs-border);border-radius:12px;padding:1.25rem">
  <h3 style="font-size:.9rem;font-weight:700;margin:0 0 1rem">&#128200; Investment Growth Over Time</h3>
  <canvas id="ci-chart" height="160"></canvas>
</div>
<!-- Milestone table -->
<div id="ci-milestones" style="display:none;margin-top:1.5rem">
  <h3 style="font-size:.9rem;font-weight:700;margin-bottom:.75rem">&#128202; Growth Milestones</h3>
  <div class="fsc-table-wrap"><table class="fsc-table">
    <thead><tr><th>Year</th><th>Balance</th><th>Total Contributed</th><th>Interest Earned</th><th>Real Value</th></tr></thead>
    <tbody id="ci-milestone-tbody"></tbody>
  </table></div>
</div>
</div>
<script>
var ciChart=null;
function calcCompound(){
  var P=parseFloat(document.getElementById('ci-principal').value)||0;
  var m=parseFloat(document.getElementById('ci-monthly').value)||0;
  var r=parseFloat(document.getElementById('ci-rate').value)/100;
  var y=parseFloat(document.getElementById('ci-years').value)||1;
  var n=parseFloat(document.getElementById('ci-freq').value);
  var inf=parseFloat(document.getElementById('ci-inf').value)/100||0;
  if(!r&&!P)return;

  var fmt=function(v){return '$'+Math.round(v).toLocaleString();};
  var labels=[],contribData=[],earnedData=[],realData=[];
  var milesRows='';
  var milestoneYears=[1,2,3,5,10,15,20,25,30].filter(function(y2){return y2<=y;});
  if(milestoneYears[milestoneYears.length-1]!==y) milestoneYears.push(y);

  var F,C;
  for(var yr=0;yr<=y;yr++){
    if(n===0||r===0){
      F=P+m*12*yr;
    } else {
      F=P*Math.pow(1+r/n,n*yr)+m*(n/r)*(Math.pow(1+r/n,n*yr)-1);
    }
    C=P+m*12*yr;
    var realV=F/Math.pow(1+inf,yr);
    labels.push(yr===0?'Now':yr+'yr');
    contribData.push(Math.round(C));
    earnedData.push(Math.round(F-C));
    realData.push(Math.round(realV));

    if(milestoneYears.indexOf(yr)>-1){
      milesRows+='<tr><td>'+yr+'</td><td>'+fmt(F)+'</td><td>'+fmt(C)+'</td><td>'+fmt(F-C)+'</td><td>'+fmt(realV)+'</td></tr>';
    }
  }

  document.getElementById('ci-final').textContent=fmt(F);
  document.getElementById('ci-real').textContent=fmt(F/Math.pow(1+inf,y));
  document.getElementById('ci-contrib').textContent=fmt(C);
  document.getElementById('ci-earned').textContent=fmt(F-C);
  document.getElementById('ci-mult').textContent=(F/Math.max(C,1)).toFixed(2)+'x';
  document.getElementById('ci-r72').textContent=r>0?(72/((r)*100)).toFixed(1)+' years':'N/A';
  document.getElementById('ci-results').style.display='grid';

  document.getElementById('ci-chart-wrap').style.display='block';
  document.getElementById('ci-milestones').style.display='block';
  document.getElementById('ci-milestone-tbody').innerHTML=milesRows;

  if(ciChart) ciChart.destroy();
  var ctx=document.getElementById('ci-chart').getContext('2d');
  ciChart=new Chart(ctx,{type:'bar',data:{
    labels:labels,
    datasets:[
      {label:'Contributions',data:contribData,backgroundColor:'rgba(59,130,246,.7)',stack:'s'},
      {label:'Interest Earned',data:earnedData,backgroundColor:'rgba(0,200,150,.7)',stack:'s'},
    ]
  },options:{plugins:{legend:{position:'top'}},scales:{x:{stacked:true},y:{stacked:true,ticks:{callback:function(v){return '$'+Math.round(v/1000)+'k';}}}},interaction:{mode:'index'}}});
}
</script>
<?php }

/* ─────────────────────────────────────────────
   6. ROI CALCULATOR
───────────────────────────────────────────── */
function fs_calc_roi( $title ) { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Initial Investment ($)</label><input type="number" id="roi-init" value="5000"></div>
    <div class="fsc-field"><label>Final Value ($)</label><input type="number" id="roi-final" value="8500"></div>
    <div class="fsc-field"><label>Time Period (Years)</label><input type="number" id="roi-years" value="3" step="0.5"></div>
    <button class="fsc-btn" onclick="calcROI()">Calculate ROI</button>
  </div>
  <div class="fsc-results" id="roi-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Total ROI</div><div class="fsc-result-value" id="roi-total">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Net Profit</div><div class="fsc-result-value" id="roi-profit">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Annualized ROI (CAGR)</div><div class="fsc-result-value" id="roi-annual">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Money Multiple</div><div class="fsc-result-value" id="roi-mult">—</div></div>
  </div>
</div></div>
<script>
function calcROI(){
  var I=parseFloat(document.getElementById('roi-init').value)||0;
  var F=parseFloat(document.getElementById('roi-final').value)||0;
  var Y=parseFloat(document.getElementById('roi-years').value)||1;
  var roi=(F-I)/I*100;
  var cagr=(Math.pow(F/I,1/Y)-1)*100;
  var fmt=function(v){return '$'+v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('roi-total').textContent=roi.toFixed(2)+'%';
  document.getElementById('roi-profit').textContent=fmt(F-I);
  document.getElementById('roi-annual').textContent=cagr.toFixed(2)+'%';
  document.getElementById('roi-mult').textContent=(F/I).toFixed(2)+'x';
  document.getElementById('roi-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   7. DIVIDEND CALCULATOR
───────────────────────────────────────────── */
function fs_calc_dividend() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Share Price ($)</label><input type="number" id="dv-price" value="150"></div>
    <div class="fsc-field"><label>Annual Dividend Per Share ($)</label><input type="number" id="dv-div" value="4.50" step="0.01"></div>
    <div class="fsc-field"><label>Number of Shares</label><input type="number" id="dv-shares" value="100"></div>
    <div class="fsc-field"><label>Dividend Growth Rate (%/yr)</label><input type="number" id="dv-growth" value="5" step="0.5"></div>
    <div class="fsc-field"><label>Years</label><input type="number" id="dv-years" value="10"></div>
    <button class="fsc-btn" onclick="calcDividend()">Calculate</button>
  </div>
  <div class="fsc-results" id="dv-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Annual Income (Now)</div><div class="fsc-result-value" id="dv-annual">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Dividend Yield</div><div class="fsc-result-value" id="dv-yield">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Annual Income (Yr <?php echo '10'; ?>)</div><div class="fsc-result-value" id="dv-future">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Dividends Collected</div><div class="fsc-result-value" id="dv-total">—</div></div>
  </div>
</div></div>
<script>
function calcDividend(){
  var P=parseFloat(document.getElementById('dv-price').value)||0;
  var D=parseFloat(document.getElementById('dv-div').value)||0;
  var S=parseFloat(document.getElementById('dv-shares').value)||0;
  var G=parseFloat(document.getElementById('dv-growth').value)/100;
  var Y=parseFloat(document.getElementById('dv-years').value)||1;
  var annual=D*S,total=0;
  for(var i=0;i<Y;i++){total+=D*S*Math.pow(1+G,i);}
  var future=D*S*Math.pow(1+G,Y-1);
  var fmt=function(v){return '$'+v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('dv-annual').textContent=fmt(annual);
  document.getElementById('dv-yield').textContent=(D/P*100).toFixed(2)+'%';
  document.getElementById('dv-future').textContent=fmt(future);
  document.getElementById('dv-total').textContent=fmt(total);
  document.getElementById('dv-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   8. DCA CALCULATOR
───────────────────────────────────────────── */
function fs_calc_dca( $title ) { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Asset Name</label><input type="text" id="dca-asset" value="Bitcoin (BTC)"></div>
    <div class="fsc-field"><label>Monthly Investment ($)</label><input type="number" id="dca-monthly" value="200"></div>
    <div class="fsc-field"><label>Duration (Months)</label><input type="number" id="dca-months" value="24"></div>
    <div class="fsc-field"><label>Expected Annual Return (%)</label><input type="number" id="dca-rate" value="25" step="1"></div>
    <div class="fsc-field"><label>Current Price ($)</label><input type="number" id="dca-price" value="60000"></div>
    <button class="fsc-btn" onclick="calcDCA()">Calculate</button>
  </div>
  <div class="fsc-results" id="dca-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Portfolio Value</div><div class="fsc-result-value" id="dca-value">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Invested</div><div class="fsc-result-value" id="dca-invested">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Profit</div><div class="fsc-result-value" id="dca-profit">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">ROI</div><div class="fsc-result-value" id="dca-roi">—</div></div>
  </div>
</div></div>
<script>
function calcDCA(){
  var m=parseFloat(document.getElementById('dca-monthly').value)||0;
  var months=parseFloat(document.getElementById('dca-months').value)||1;
  var r=parseFloat(document.getElementById('dca-rate').value)/100/12;
  var invested=m*months;
  var value=m*(Math.pow(1+r,months)-1)/r;
  var profit=value-invested;
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('dca-value').textContent=fmt(value);
  document.getElementById('dca-invested').textContent=fmt(invested);
  document.getElementById('dca-profit').textContent=fmt(profit);
  document.getElementById('dca-roi').textContent=(profit/invested*100).toFixed(1)+'%';
  document.getElementById('dca-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   9. PORTFOLIO CALCULATOR
───────────────────────────────────────────── */
function fs_calc_portfolio() { ?>
<div class="fsc-wrap">
<div class="fsc-inputs">
  <p style="color:rgba(255,255,255,.6);margin-bottom:1rem;font-size:.9rem">Enter holdings to analyze your portfolio allocation and value.</p>
  <div id="pf-rows">
    <div class="fsc-portfolio-row"><input placeholder="Asset (e.g. AAPL)" class="pf-name"><input type="number" placeholder="Value ($)" class="pf-value"><input type="number" placeholder="Return %" class="pf-ret" step="0.1"></div>
    <div class="fsc-portfolio-row"><input placeholder="Asset (e.g. BTC)" class="pf-name"><input type="number" placeholder="Value ($)" class="pf-value"><input type="number" placeholder="Return %" class="pf-ret" step="0.1"></div>
    <div class="fsc-portfolio-row"><input placeholder="Asset (e.g. Gold)" class="pf-name"><input type="number" placeholder="Value ($)" class="pf-value"><input type="number" placeholder="Return %" class="pf-ret" step="0.1"></div>
  </div>
  <div style="display:flex;gap:.75rem;margin-top:.75rem">
    <button class="fsc-btn fsc-btn--sm" onclick="addPfRow()">+ Add Asset</button>
    <button class="fsc-btn" onclick="calcPortfolio()">Analyze Portfolio</button>
  </div>
</div>
<div id="pf-results" style="display:none;margin-top:1.5rem">
  <div class="fsc-results" style="grid-template-columns:repeat(3,1fr)">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Total Value</div><div class="fsc-result-value" id="pf-total">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Weighted Return</div><div class="fsc-result-value" id="pf-return">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Assets</div><div class="fsc-result-value" id="pf-count">—</div></div>
  </div>
  <div id="pf-breakdown" style="margin-top:1rem"></div>
</div>
</div>
<script>
function addPfRow(){var d=document.createElement('div');d.className='fsc-portfolio-row';d.innerHTML='<input placeholder="Asset" class="pf-name"><input type="number" placeholder="Value ($)" class="pf-value"><input type="number" placeholder="Return %" class="pf-ret" step="0.1">';document.getElementById('pf-rows').appendChild(d);}
function calcPortfolio(){
  var rows=document.querySelectorAll('.fsc-portfolio-row');
  var total=0,wret=0,bd='';
  rows.forEach(function(r){total+=parseFloat(r.querySelector('.pf-value').value)||0;});
  rows.forEach(function(r){
    var n=r.querySelector('.pf-name').value||'Asset';
    var v=parseFloat(r.querySelector('.pf-value').value)||0;
    var ret=parseFloat(r.querySelector('.pf-ret').value)||0;
    if(!v)return;
    var pct=v/total*100;
    wret+=ret*pct/100;
    bd+='<div class="fsc-pf-row"><span>'+n+'</span><div class="fsc-pf-bar-wrap"><div class="fsc-pf-bar" style="width:'+pct+'%"></div></div><span>'+pct.toFixed(1)+'%</span><span>$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',')+'</span></div>';
  });
  document.getElementById('pf-total').textContent='$'+total.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');
  document.getElementById('pf-return').textContent=wret.toFixed(2)+'%';
  document.getElementById('pf-count').textContent=rows.length;
  document.getElementById('pf-breakdown').innerHTML=bd;
  document.getElementById('pf-results').style.display='block';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   10. CAPITAL GAINS CALCULATOR
───────────────────────────────────────────── */
function fs_calc_capital_gains( $title ) { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Purchase Price ($)</label><input type="number" id="cg-buy" value="10000"></div>
    <div class="fsc-field"><label>Sale Price ($)</label><input type="number" id="cg-sell" value="18000"></div>
    <div class="fsc-field"><label>Holding Period</label>
      <select id="cg-hold"><option value="short">Short-term (&lt;1 year)</option><option value="long" selected>Long-term (&gt;1 year)</option></select></div>
    <div class="fsc-field"><label>Annual Income ($)</label><input type="number" id="cg-income" value="80000"></div>
    <div class="fsc-field"><label>Filing Status</label>
      <select id="cg-status"><option value="single">Single</option><option value="married">Married Filing Jointly</option></select></div>
    <button class="fsc-btn" onclick="calcCapGains()">Calculate Tax</button>
  </div>
  <div class="fsc-results" id="cg-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Capital Gain</div><div class="fsc-result-value" id="cg-gain">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Tax Rate</div><div class="fsc-result-value" id="cg-rate">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Tax Owed</div><div class="fsc-result-value" id="cg-tax">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">After-Tax Profit</div><div class="fsc-result-value" id="cg-net">—</div></div>
  </div>
</div></div>
<script>
function calcCapGains(){
  var buy=parseFloat(document.getElementById('cg-buy').value)||0;
  var sell=parseFloat(document.getElementById('cg-sell').value)||0;
  var hold=document.getElementById('cg-hold').value;
  var income=parseFloat(document.getElementById('cg-income').value)||0;
  var status=document.getElementById('cg-status').value;
  var gain=sell-buy;
  var rate;
  if(hold==='short'){rate=income<44725?0.10:income<95375?0.22:income<182050?0.24:0.32;}
  else{var thresh=status==='married'?89250:44625;rate=income<thresh?0:income<(status==='married'?553850:492300)?0.15:0.20;}
  var tax=gain*rate;
  var fmt=function(v){return '$'+v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('cg-gain').textContent=fmt(gain);
  document.getElementById('cg-rate').textContent=(rate*100).toFixed(0)+'%';
  document.getElementById('cg-tax').textContent=fmt(tax);
  document.getElementById('cg-net').textContent=fmt(gain-tax);
  document.getElementById('cg-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   11. CAGR CALCULATOR
───────────────────────────────────────────── */
function fs_calc_cagr() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Beginning Value ($)</label><input type="number" id="cagr-start" value="10000"></div>
    <div class="fsc-field"><label>Ending Value ($)</label><input type="number" id="cagr-end" value="35000"></div>
    <div class="fsc-field"><label>Number of Years</label><input type="number" id="cagr-years" value="10"></div>
    <button class="fsc-btn" onclick="calcCAGR()">Calculate CAGR</button>
  </div>
  <div class="fsc-results" id="cagr-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">CAGR</div><div class="fsc-result-value" id="cagr-rate">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Growth</div><div class="fsc-result-value" id="cagr-growth">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Absolute Return</div><div class="fsc-result-value" id="cagr-abs">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Money Multiple</div><div class="fsc-result-value" id="cagr-mult">—</div></div>
  </div>
</div></div>
<script>
function calcCAGR(){
  var S=parseFloat(document.getElementById('cagr-start').value)||0;
  var E=parseFloat(document.getElementById('cagr-end').value)||0;
  var Y=parseFloat(document.getElementById('cagr-years').value)||1;
  var cagr=(Math.pow(E/S,1/Y)-1)*100;
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('cagr-rate').textContent=cagr.toFixed(2)+'%';
  document.getElementById('cagr-growth').textContent=((E-S)/S*100).toFixed(1)+'%';
  document.getElementById('cagr-abs').textContent=fmt(E-S);
  document.getElementById('cagr-mult').textContent=(E/S).toFixed(2)+'x';
  document.getElementById('cagr-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   12. PRESENT VALUE
───────────────────────────────────────────── */
function fs_calc_pv() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Future Value ($)</label><input type="number" id="pv-fv" value="50000"></div>
    <div class="fsc-field"><label>Discount Rate (%/yr)</label><input type="number" id="pv-rate" value="8" step="0.1"></div>
    <div class="fsc-field"><label>Years</label><input type="number" id="pv-years" value="10"></div>
    <button class="fsc-btn" onclick="calcPV()">Calculate Present Value</button>
  </div>
  <div class="fsc-results" id="pv-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Present Value</div><div class="fsc-result-value" id="pv-pv">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Discount Amount</div><div class="fsc-result-value" id="pv-disc">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Discount Factor</div><div class="fsc-result-value" id="pv-factor">—</div></div>
  </div>
</div></div>
<script>
function calcPV(){
  var F=parseFloat(document.getElementById('pv-fv').value)||0;
  var r=parseFloat(document.getElementById('pv-rate').value)/100;
  var n=parseFloat(document.getElementById('pv-years').value)||1;
  var pv=F/Math.pow(1+r,n);
  var fmt=function(v){return '$'+v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('pv-pv').textContent=fmt(pv);
  document.getElementById('pv-disc').textContent=fmt(F-pv);
  document.getElementById('pv-factor').textContent=(1/Math.pow(1+r,n)).toFixed(4);
  document.getElementById('pv-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   13. BREAK EVEN CALCULATOR
───────────────────────────────────────────── */
function fs_calc_breakeven() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Fixed Costs ($)</label><input type="number" id="be-fixed" value="10000"></div>
    <div class="fsc-field"><label>Variable Cost per Unit ($)</label><input type="number" id="be-var" value="25"></div>
    <div class="fsc-field"><label>Selling Price per Unit ($)</label><input type="number" id="be-price" value="50"></div>
    <button class="fsc-btn" onclick="calcBreakEven()">Calculate</button>
  </div>
  <div class="fsc-results" id="be-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Break-Even Units</div><div class="fsc-result-value" id="be-units">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Break-Even Revenue</div><div class="fsc-result-value" id="be-rev">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Contribution Margin</div><div class="fsc-result-value" id="be-margin">—</div></div>
  </div>
</div></div>
<script>
function calcBreakEven(){
  var F=parseFloat(document.getElementById('be-fixed').value)||0;
  var V=parseFloat(document.getElementById('be-var').value)||0;
  var P=parseFloat(document.getElementById('be-price').value)||1;
  var units=F/(P-V);
  var fmt=function(v){return '$'+v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('be-units').textContent=Math.ceil(units)+' units';
  document.getElementById('be-rev').textContent=fmt(Math.ceil(units)*P);
  document.getElementById('be-margin').textContent=fmt(P-V)+' / unit';
  document.getElementById('be-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   14. INFLATION CALCULATOR
───────────────────────────────────────────── */
function fs_calc_inflation() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Current Amount ($)</label><input type="number" id="inf-amount" value="10000"></div>
    <div class="fsc-field"><label>Annual Inflation Rate (%)</label><input type="number" id="inf-rate" value="3.5" step="0.1"></div>
    <div class="fsc-field"><label>Years</label><input type="number" id="inf-years" value="20"></div>
    <button class="fsc-btn" onclick="calcInflation()">Calculate</button>
  </div>
  <div class="fsc-results" id="inf-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Future Equivalent</div><div class="fsc-result-value" id="inf-future">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Purchasing Power Loss</div><div class="fsc-result-value" id="inf-loss">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Real Value Today's $</div><div class="fsc-result-value" id="inf-real">—</div></div>
  </div>
</div></div>
<script>
function calcInflation(){
  var A=parseFloat(document.getElementById('inf-amount').value)||0;
  var r=parseFloat(document.getElementById('inf-rate').value)/100;
  var y=parseFloat(document.getElementById('inf-years').value)||1;
  var future=A*Math.pow(1+r,y);
  var real=A/Math.pow(1+r,y);
  var fmt=function(v){return '$'+v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('inf-future').textContent=fmt(future);
  document.getElementById('inf-loss').textContent=((1-1/Math.pow(1+r,y))*100).toFixed(1)+'%';
  document.getElementById('inf-real').textContent=fmt(real);
  document.getElementById('inf-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   15. SHARPE RATIO
───────────────────────────────────────────── */
function fs_calc_sharpe() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Portfolio Return (%)</label><input type="number" id="sh-ret" value="12" step="0.1"></div>
    <div class="fsc-field"><label>Risk-Free Rate (%)</label><input type="number" id="sh-rf" value="4.5" step="0.1"></div>
    <div class="fsc-field"><label>Portfolio Std Deviation (%)</label><input type="number" id="sh-std" value="15" step="0.1"></div>
    <button class="fsc-btn" onclick="calcSharpe()">Calculate</button>
  </div>
  <div class="fsc-results" id="sh-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Sharpe Ratio</div><div class="fsc-result-value" id="sh-ratio">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Excess Return</div><div class="fsc-result-value" id="sh-excess">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Rating</div><div class="fsc-result-value" id="sh-rating">—</div></div>
  </div>
</div></div>
<script>
function calcSharpe(){
  var R=parseFloat(document.getElementById('sh-ret').value)||0;
  var Rf=parseFloat(document.getElementById('sh-rf').value)||0;
  var S=parseFloat(document.getElementById('sh-std').value)||1;
  var ratio=(R-Rf)/S;
  var rating=ratio<0?'Poor':ratio<0.5?'Below Average':ratio<1?'Acceptable':ratio<2?'Good':'Excellent';
  document.getElementById('sh-ratio').textContent=ratio.toFixed(3);
  document.getElementById('sh-excess').textContent=(R-Rf).toFixed(2)+'%';
  document.getElementById('sh-rating').textContent=rating;
  document.getElementById('sh-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   16. BOND YIELD
───────────────────────────────────────────── */
function fs_calc_bond() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Face Value ($)</label><input type="number" id="bn-face" value="1000"></div>
    <div class="fsc-field"><label>Annual Coupon Rate (%)</label><input type="number" id="bn-coupon" value="5" step="0.1"></div>
    <div class="fsc-field"><label>Current Market Price ($)</label><input type="number" id="bn-price" value="950"></div>
    <div class="fsc-field"><label>Years to Maturity</label><input type="number" id="bn-years" value="10"></div>
    <button class="fsc-btn" onclick="calcBond()">Calculate</button>
  </div>
  <div class="fsc-results" id="bn-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Current Yield</div><div class="fsc-result-value" id="bn-cy">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Annual Coupon</div><div class="fsc-result-value" id="bn-ac">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">YTM (approx)</div><div class="fsc-result-value" id="bn-ytm">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Premium/Discount</div><div class="fsc-result-value" id="bn-pd">—</div></div>
  </div>
</div></div>
<script>
function calcBond(){
  var F=parseFloat(document.getElementById('bn-face').value)||0;
  var C=parseFloat(document.getElementById('bn-coupon').value)/100;
  var P=parseFloat(document.getElementById('bn-price').value)||0;
  var n=parseFloat(document.getElementById('bn-years').value)||1;
  var coupon=F*C;
  var cy=coupon/P*100;
  var ytm=((coupon+(F-P)/n)/((F+P)/2))*100;
  var fmt=function(v){return '$'+v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('bn-cy').textContent=cy.toFixed(2)+'%';
  document.getElementById('bn-ac').textContent=fmt(coupon);
  document.getElementById('bn-ytm').textContent=ytm.toFixed(2)+'%';
  document.getElementById('bn-pd').textContent=(P>F?'Premium ':'Discount ')+fmt(Math.abs(F-P));
  document.getElementById('bn-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   17. OPTIONS CALCULATOR
───────────────────────────────────────────── */
function fs_calc_options() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Option Type</label><select id="op-type"><option>Call</option><option>Put</option></select></div>
    <div class="fsc-field"><label>Current Stock Price ($)</label><input type="number" id="op-stock" value="150"></div>
    <div class="fsc-field"><label>Strike Price ($)</label><input type="number" id="op-strike" value="155"></div>
    <div class="fsc-field"><label>Premium Paid ($)</label><input type="number" id="op-premium" value="5" step="0.01"></div>
    <div class="fsc-field"><label>Contracts (100 shares each)</label><input type="number" id="op-contracts" value="1"></div>
    <div class="fsc-field"><label>Target Price at Expiry ($)</label><input type="number" id="op-target" value="165"></div>
    <button class="fsc-btn" onclick="calcOptions()">Calculate P&amp;L</button>
  </div>
  <div class="fsc-results" id="op-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Profit / Loss</div><div class="fsc-result-value" id="op-pnl">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Break-Even Price</div><div class="fsc-result-value" id="op-be">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Max Loss</div><div class="fsc-result-value" id="op-maxloss">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">ROI</div><div class="fsc-result-value" id="op-roi">—</div></div>
  </div>
</div></div>
<script>
function calcOptions(){
  var type=document.getElementById('op-type').value;
  var S=parseFloat(document.getElementById('op-stock').value)||0;
  var K=parseFloat(document.getElementById('op-strike').value)||0;
  var prem=parseFloat(document.getElementById('op-premium').value)||0;
  var C=parseFloat(document.getElementById('op-contracts').value)||1;
  var T=parseFloat(document.getElementById('op-target').value)||0;
  var intrinsic=type==='Call'?Math.max(0,T-K):Math.max(0,K-T);
  var pnl=(intrinsic-prem)*100*C;
  var be=type==='Call'?K+prem:K-prem;
  var maxLoss=prem*100*C;
  var fmt=function(v){return '$'+v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('op-pnl').textContent=(pnl>=0?'+':'')+fmt(pnl);
  document.getElementById('op-be').textContent=fmt(be);
  document.getElementById('op-maxloss').textContent=fmt(maxLoss);
  document.getElementById('op-roi').textContent=(pnl/maxLoss*100).toFixed(1)+'%';
  document.getElementById('op-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   18. INCOME TAX CALCULATOR
───────────────────────────────────────────── */
function fs_calc_income_tax( $title ) { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Annual Income ($)</label><input type="number" id="tx-income" value="75000"></div>
    <div class="fsc-field"><label>Filing Status</label>
      <select id="tx-status"><option value="single">Single</option><option value="married">Married Filing Jointly</option><option value="hoh">Head of Household</option></select></div>
    <div class="fsc-field"><label>Deductions ($)</label><input type="number" id="tx-deduct" value="0"></div>
    <div class="fsc-field"><label>State</label><select id="tx-state"><option value="0">No State Tax</option><option value="3.07">Pennsylvania (3.07%)</option><option value="5.0">Massachusetts (5%)</option><option value="6.0">New York (~6%)</option><option value="9.3">California (~9.3%)</option><option value="13.3">California Top (13.3%)</option></select></div>
    <button class="fsc-btn" onclick="calcTax()">Calculate Tax</button>
  </div>
  <div class="fsc-results" id="tx-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Federal Tax</div><div class="fsc-result-value" id="tx-federal">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">State Tax</div><div class="fsc-result-value" id="tx-state-amt">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Effective Rate</div><div class="fsc-result-value" id="tx-eff">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">After-Tax Income</div><div class="fsc-result-value" id="tx-net">—</div></div>
  </div>
</div></div>
<script>
function calcTax(){
  var I=parseFloat(document.getElementById('tx-income').value)||0;
  var status=document.getElementById('tx-status').value;
  var deduct=parseFloat(document.getElementById('tx-deduct').value)||0;
  var stateR=parseFloat(document.getElementById('tx-state').value)||0;
  var std=status==='married'?27700:status==='hoh'?20800:13850;
  var taxable=Math.max(0,I-Math.max(std,deduct));
  var brackets=status==='married'?[[23200,.10],[94300,.12],[201050,.22],[383900,.24],[487450,.32],[731200,.35],[Infinity,.37]]:[[11600,.10],[47150,.12],[100525,.22],[191950,.24],[243725,.32],[609350,.35],[Infinity,.37]];
  var tax=0,prev=0;
  for(var i=0;i<brackets.length;i++){var b=brackets[i];var amt=Math.min(taxable,b[0])-prev;if(amt<=0)break;tax+=amt*b[1];prev=b[0];}
  var stateTax=I*stateR/100;
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('tx-federal').textContent=fmt(tax);
  document.getElementById('tx-state-amt').textContent=fmt(stateTax);
  document.getElementById('tx-eff').textContent=((tax+stateTax)/I*100).toFixed(1)+'%';
  document.getElementById('tx-net').textContent=fmt(I-tax-stateTax);
  document.getElementById('tx-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   19. SELF EMPLOYMENT TAX
───────────────────────────────────────────── */
function fs_calc_self_emp_tax() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Net Self-Employment Income ($)</label><input type="number" id="se-income" value="80000"></div>
    <div class="fsc-field"><label>Other Income ($)</label><input type="number" id="se-other" value="0"></div>
    <div class="fsc-field"><label>Filing Status</label><select id="se-status"><option>Single</option><option>Married Filing Jointly</option></select></div>
    <button class="fsc-btn" onclick="calcSETax()">Calculate</button>
  </div>
  <div class="fsc-results" id="se-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">SE Tax</div><div class="fsc-result-value" id="se-tax">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Quarterly Payment</div><div class="fsc-result-value" id="se-quarterly">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Deductible SE Tax</div><div class="fsc-result-value" id="se-deduct">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Effective SE Rate</div><div class="fsc-result-value" id="se-rate">—</div></div>
  </div>
</div></div>
<script>
function calcSETax(){
  var I=parseFloat(document.getElementById('se-income').value)||0;
  var netEarnings=I*0.9235;
  var seTax=netEarnings*0.153;
  var seDed=seTax/2;
  var fmt=function(v){return '$'+v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('se-tax').textContent=fmt(seTax);
  document.getElementById('se-quarterly').textContent=fmt(seTax/4);
  document.getElementById('se-deduct').textContent=fmt(seDed);
  document.getElementById('se-rate').textContent=(seTax/I*100).toFixed(1)+'%';
  document.getElementById('se-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   20. SAVINGS GOAL CALCULATOR
───────────────────────────────────────────── */
function fs_calc_savings_goal( $title ) { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Goal Amount ($)</label><input type="number" id="sg-goal" value="20000"></div>
    <div class="fsc-field"><label>Current Savings ($)</label><input type="number" id="sg-current" value="2000"></div>
    <div class="fsc-field"><label>Monthly Savings ($)</label><input type="number" id="sg-monthly" value="500"></div>
    <div class="fsc-field"><label>Annual Interest Rate (%)</label><input type="number" id="sg-rate" value="4" step="0.1"></div>
    <button class="fsc-btn" onclick="calcSavingsGoal()">Calculate</button>
  </div>
  <div class="fsc-results" id="sg-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Months to Goal</div><div class="fsc-result-value" id="sg-months">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Goal Date</div><div class="fsc-result-value" id="sg-date">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Saved</div><div class="fsc-result-value" id="sg-total">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Interest Earned</div><div class="fsc-result-value" id="sg-interest">—</div></div>
  </div>
</div></div>
<script>
function calcSavingsGoal(){
  var G=parseFloat(document.getElementById('sg-goal').value)||0;
  var C=parseFloat(document.getElementById('sg-current').value)||0;
  var M=parseFloat(document.getElementById('sg-monthly').value)||0;
  var r=parseFloat(document.getElementById('sg-rate').value)/100/12;
  var bal=C,months=0;
  while(bal<G&&months<600){bal=bal*(1+r)+M;months++;}
  var d=new Date();d.setMonth(d.getMonth()+months);
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('sg-months').textContent=months+' months';
  document.getElementById('sg-date').textContent=d.toLocaleDateString('en-US',{month:'short',year:'numeric'});
  document.getElementById('sg-total').textContent=fmt(bal);
  document.getElementById('sg-interest').textContent=fmt(bal-C-M*months);
  document.getElementById('sg-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   21. 52-WEEK SAVINGS CHALLENGE
───────────────────────────────────────────── */
function fs_calc_52week() { ?>
<div class="fsc-wrap">
<p style="color:rgba(255,255,255,.7);margin-bottom:1rem">Save Week 1: $1, Week 2: $2 … Week 52: $52. Total = <strong style="color:#00C896">$1,378</strong></p>
<div class="fsc-inputs" style="max-width:400px">
  <div class="fsc-field"><label>Multiplier (save $X × week number)</label><input type="number" id="sw-mult" value="1" step="0.5" min="0.5"></div>
  <button class="fsc-btn" onclick="calc52()">Calculate Total</button>
</div>
<div class="fsc-results" id="sw-results" style="display:none;margin-top:1rem">
  <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Total Saved in 1 Year</div><div class="fsc-result-value" id="sw-total">—</div></div>
  <div class="fsc-result-card"><div class="fsc-result-label">Average Per Week</div><div class="fsc-result-value" id="sw-avg">—</div></div>
  <div class="fsc-result-card"><div class="fsc-result-label">Biggest Week</div><div class="fsc-result-value" id="sw-big">—</div></div>
</div>
<div id="sw-table-wrap" style="display:none;margin-top:1.5rem;max-height:300px;overflow-y:auto">
  <table class="fsc-table"><thead><tr><th>Week</th><th>Save</th><th>Running Total</th></tr></thead><tbody id="sw-tbody"></tbody></table>
</div>
</div>
<script>
function calc52(){
  var m=parseFloat(document.getElementById('sw-mult').value)||1;
  var total=0,rows='';
  for(var w=1;w<=52;w++){var amt=w*m;total+=amt;rows+='<tr><td>'+w+'</td><td>$'+amt.toFixed(2)+'</td><td>$'+total.toFixed(2)+'</td></tr>';}
  document.getElementById('sw-total').textContent='$'+total.toFixed(2);
  document.getElementById('sw-avg').textContent='$'+(total/52).toFixed(2);
  document.getElementById('sw-big').textContent='$'+(52*m).toFixed(2);
  document.getElementById('sw-tbody').innerHTML=rows;
  document.getElementById('sw-results').style.display='grid';
  document.getElementById('sw-table-wrap').style.display='block';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   22. RETIREMENT CALCULATOR
───────────────────────────────────────────── */
function fs_calc_retirement( $title ) { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Current Age</label><input type="number" id="rt-age" value="30" min="18" max="70"></div>
    <div class="fsc-field"><label>Retirement Age</label><input type="number" id="rt-retire" value="65" min="40" max="80"></div>
    <div class="fsc-field"><label>Current Savings ($)</label><input type="number" id="rt-saved" value="15000"></div>
    <div class="fsc-field"><label>Monthly Contribution ($)</label><input type="number" id="rt-contrib" value="800"></div>
    <div class="fsc-field"><label>Employer Match (%)</label><input type="number" id="rt-match" value="50" step="5"></div>
    <div class="fsc-field"><label>Annual Salary ($) <small style="font-weight:400;color:#888">for match limit</small></label><input type="number" id="rt-salary" value="60000"></div>
    <div class="fsc-field"><label>Match Up To (% of salary)</label><input type="number" id="rt-match-limit" value="6" step="1"></div>
    <div class="fsc-field"><label>Expected Return (%/yr)</label><input type="number" id="rt-return" value="7" step="0.5"></div>
    <div class="fsc-field"><label>Inflation Rate (%/yr)</label><input type="number" id="rt-inf" value="3" step="0.1"></div>
    <div class="fsc-field"><label>Desired Monthly Income at Retirement ($)</label><input type="number" id="rt-desired" value="5000"></div>
    <button class="fsc-btn" onclick="calcRetirement()">Calculate Retirement</button>
  </div>
  <div class="fsc-results" id="rt-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Nest Egg at Retirement</div><div class="fsc-result-value" id="rt-nest">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Monthly Income (4% Rule)</div><div class="fsc-result-value" id="rt-income">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Employer Match Earned</div><div class="fsc-result-value" id="rt-match-earned">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Contributed (You)</div><div class="fsc-result-value" id="rt-total">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Investment Growth</div><div class="fsc-result-value" id="rt-growth">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">On Track?</div><div class="fsc-result-value" id="rt-status">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Years of Growth</div><div class="fsc-result-value" id="rt-years">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Real Value (Inflation-Adj.)</div><div class="fsc-result-value" id="rt-real">—</div></div>
  </div>
</div>
<!-- Chart -->
<div id="rt-chart-wrap" style="display:none;margin-top:1.5rem;background:#fff;border:1.5px solid var(--fs-border);border-radius:12px;padding:1.25rem">
  <h3 style="font-size:.9rem;font-weight:700;margin:0 0 1rem">&#128200; Retirement Savings Growth</h3>
  <canvas id="rt-chart" height="160"></canvas>
</div>
</div>
<script>
var rtChart=null;
function calcRetirement(){
  var age=parseFloat(document.getElementById('rt-age').value)||30;
  var retire=parseFloat(document.getElementById('rt-retire').value)||65;
  var saved=parseFloat(document.getElementById('rt-saved').value)||0;
  var contrib=parseFloat(document.getElementById('rt-contrib').value)||0;
  var matchPct=parseFloat(document.getElementById('rt-match').value)||0;
  var salary=parseFloat(document.getElementById('rt-salary').value)||0;
  var matchLimitPct=parseFloat(document.getElementById('rt-match-limit').value)||6;
  var r=parseFloat(document.getElementById('rt-return').value)/100/12;
  var inf=parseFloat(document.getElementById('rt-inf').value)/100||0.03;
  var desired=parseFloat(document.getElementById('rt-desired').value)||5000;
  var years=Math.max(retire-age,1);
  var months=years*12;

  // Employer match (capped)
  var matchCap=salary*matchLimitPct/100/12;
  var matchMonthly=Math.min(contrib*matchPct/100, matchCap);
  var totalMonthly=contrib+matchMonthly;

  var nest=saved*Math.pow(1+r,months)+totalMonthly*(Math.pow(1+r,months)-1)/Math.max(r,0.0001);
  var income=nest*0.04/12;
  var totalContrib=contrib*months;
  var totalMatch=matchMonthly*months;
  var growth=nest-saved-totalContrib-totalMatch;
  var realNest=nest/Math.pow(1+inf,years);
  var needed=desired/0.04*12;
  var onTrack=nest>=needed;

  var fmt=function(v){return '$'+Math.round(v).toLocaleString();};
  document.getElementById('rt-nest').textContent=fmt(nest);
  document.getElementById('rt-income').textContent=fmt(income)+'/mo';
  document.getElementById('rt-match-earned').textContent=fmt(totalMatch);
  document.getElementById('rt-total').textContent=fmt(totalContrib);
  document.getElementById('rt-growth').textContent=fmt(growth);
  document.getElementById('rt-status').innerHTML=onTrack?'<span style="color:#00C896;font-weight:800">&#9989; On Track!</span>':'<span style="color:#EF4444;font-weight:800">&#9888;&#65039; Need More</span>';
  document.getElementById('rt-years').textContent=years+' years';
  document.getElementById('rt-real').textContent=fmt(realNest);
  document.getElementById('rt-results').style.display='grid';

  // Chart
  document.getElementById('rt-chart-wrap').style.display='block';
  var labels=[],yourData=[],matchData=[],growthData=[];
  var bal=saved,yourTotal=0,matchTotal=0;
  for(var yr=0;yr<=years;yr+=Math.max(1,Math.floor(years/15))){
    var m2=yr*12;
    var b=saved*Math.pow(1+r,m2)+totalMonthly*(Math.pow(1+r,m2)-1)/Math.max(r,0.0001);
    var yc=saved+contrib*m2;
    var mc=matchMonthly*m2;
    labels.push(age+yr+'yr');
    yourData.push(Math.round(yc));
    matchData.push(Math.round(mc));
    growthData.push(Math.round(Math.max(b-yc-mc,0)));
  }
  if(rtChart) rtChart.destroy();
  var ctx=document.getElementById('rt-chart').getContext('2d');
  rtChart=new Chart(ctx,{type:'bar',data:{
    labels:labels,
    datasets:[
      {label:'Your Contributions',data:yourData,backgroundColor:'rgba(59,130,246,.7)',stack:'s'},
      {label:'Employer Match',data:matchData,backgroundColor:'rgba(245,158,11,.7)',stack:'s'},
      {label:'Investment Growth',data:growthData,backgroundColor:'rgba(0,200,150,.7)',stack:'s'},
    ]
  },options:{plugins:{legend:{position:'top'}},scales:{x:{stacked:true},y:{stacked:true,ticks:{callback:function(v){return '$'+Math.round(v/1000)+'k';}}}}}});
}
</script>
<?php }

/* ─────────────────────────────────────────────
   23. FIRE CALCULATOR
───────────────────────────────────────────── */
function fs_calc_fire() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Annual Expenses ($)</label><input type="number" id="fi-expenses" value="50000"></div>
    <div class="fsc-field"><label>Current Net Worth ($)</label><input type="number" id="fi-networth" value="100000"></div>
    <div class="fsc-field"><label>Annual Savings ($)</label><input type="number" id="fi-savings" value="40000"></div>
    <div class="fsc-field"><label>Expected Return (%/yr)</label><input type="number" id="fi-return" value="7" step="0.5"></div>
    <div class="fsc-field"><label>Safe Withdrawal Rate (%)</label><input type="number" id="fi-swr" value="4" step="0.1"></div>
    <button class="fsc-btn" onclick="calcFIRE()">Calculate FIRE</button>
  </div>
  <div class="fsc-results" id="fi-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">FIRE Number</div><div class="fsc-result-value" id="fi-number">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Years to FIRE</div><div class="fsc-result-value" id="fi-years">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">FIRE Date</div><div class="fsc-result-value" id="fi-date">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Savings Rate</div><div class="fsc-result-value" id="fi-rate">—</div></div>
  </div>
</div></div>
<script>
function calcFIRE(){
  var E=parseFloat(document.getElementById('fi-expenses').value)||0;
  var NW=parseFloat(document.getElementById('fi-networth').value)||0;
  var S=parseFloat(document.getElementById('fi-savings').value)||0;
  var r=parseFloat(document.getElementById('fi-return').value)/100;
  var swr=parseFloat(document.getElementById('fi-swr').value)/100;
  var fireNum=E/swr;
  var bal=NW,years=0;
  while(bal<fireNum&&years<100){bal=bal*(1+r)+S;years++;}
  var d=new Date();d.setFullYear(d.getFullYear()+years);
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('fi-number').textContent=fmt(fireNum);
  document.getElementById('fi-years').textContent=years+' years';
  document.getElementById('fi-date').textContent=d.getFullYear().toString();
  document.getElementById('fi-rate').textContent=(S/(S+E)*100).toFixed(1)+'%';
  document.getElementById('fi-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   24. BUDGET CALCULATOR
───────────────────────────────────────────── */
function fs_calc_budget( $title ) { ?>
<div class="fsc-wrap">
<div class="fsc-inputs" style="max-width:100%">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
    <div>
      <h3 class="fsc-section-title">Monthly Income</h3>
      <div class="fsc-field"><label>Salary (After Tax)</label><input type="number" id="bg-salary" value="5000"></div>
      <div class="fsc-field"><label>Other Income</label><input type="number" id="bg-other-inc" value="500"></div>
    </div>
    <div>
      <h3 class="fsc-section-title">Monthly Expenses</h3>
      <div class="fsc-field"><label>Housing (Rent/Mortgage)</label><input type="number" id="bg-housing" value="1500"></div>
      <div class="fsc-field"><label>Food & Groceries</label><input type="number" id="bg-food" value="600"></div>
      <div class="fsc-field"><label>Transportation</label><input type="number" id="bg-transport" value="400"></div>
      <div class="fsc-field"><label>Utilities</label><input type="number" id="bg-utilities" value="200"></div>
      <div class="fsc-field"><label>Insurance</label><input type="number" id="bg-insurance" value="300"></div>
      <div class="fsc-field"><label>Entertainment</label><input type="number" id="bg-entertain" value="200"></div>
      <div class="fsc-field"><label>Healthcare</label><input type="number" id="bg-health" value="150"></div>
      <div class="fsc-field"><label>Other Expenses</label><input type="number" id="bg-other-exp" value="250"></div>
    </div>
  </div>
  <button class="fsc-btn" style="margin-top:1rem" onclick="calcBudget()">Analyze Budget</button>
</div>
<div class="fsc-results" id="bg-results" style="display:none;margin-top:1.5rem">
  <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Total Income</div><div class="fsc-result-value" id="bg-income">—</div></div>
  <div class="fsc-result-card"><div class="fsc-result-label">Total Expenses</div><div class="fsc-result-value" id="bg-expenses">—</div></div>
  <div class="fsc-result-card"><div class="fsc-result-label">Monthly Surplus</div><div class="fsc-result-value" id="bg-surplus">—</div></div>
  <div class="fsc-result-card"><div class="fsc-result-label">Savings Rate</div><div class="fsc-result-value" id="bg-saverate">—</div></div>
</div>
</div>
<script>
function calcBudget(){
  var income=(parseFloat(document.getElementById('bg-salary').value)||0)+(parseFloat(document.getElementById('bg-other-inc').value)||0);
  var exp=(parseFloat(document.getElementById('bg-housing').value)||0)+(parseFloat(document.getElementById('bg-food').value)||0)+(parseFloat(document.getElementById('bg-transport').value)||0)+(parseFloat(document.getElementById('bg-utilities').value)||0)+(parseFloat(document.getElementById('bg-insurance').value)||0)+(parseFloat(document.getElementById('bg-entertain').value)||0)+(parseFloat(document.getElementById('bg-health').value)||0)+(parseFloat(document.getElementById('bg-other-exp').value)||0);
  var surplus=income-exp;
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('bg-income').textContent=fmt(income);
  document.getElementById('bg-expenses').textContent=fmt(exp);
  var surEl=document.getElementById('bg-surplus');surEl.textContent=(surplus>=0?'+':'')+fmt(surplus);surEl.style.color=surplus>=0?'#00C896':'#F87171';
  document.getElementById('bg-saverate').textContent=(surplus/income*100).toFixed(1)+'%';
  document.getElementById('bg-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   25. 50/30/20 BUDGET
───────────────────────────────────────────── */
function fs_calc_503020() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Monthly After-Tax Income ($)</label><input type="number" id="b3-income" value="6000"></div>
    <div class="fsc-field"><label>Needs % (default 50)</label><input type="number" id="b3-needs" value="50" min="0" max="100"></div>
    <div class="fsc-field"><label>Wants % (default 30)</label><input type="number" id="b3-wants" value="30" min="0" max="100"></div>
    <div class="fsc-field"><label>Savings % (default 20)</label><input type="number" id="b3-savings" value="20" min="0" max="100"></div>
    <button class="fsc-btn" onclick="calc503020()">Calculate</button>
  </div>
  <div class="fsc-results" id="b3-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Needs Budget</div><div class="fsc-result-value" id="b3-needs-amt">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Wants Budget</div><div class="fsc-result-value" id="b3-wants-amt">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Savings Budget</div><div class="fsc-result-value" id="b3-savings-amt">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Annual Savings</div><div class="fsc-result-value" id="b3-annual">—</div></div>
  </div>
</div></div>
<script>
function calc503020(){
  var I=parseFloat(document.getElementById('b3-income').value)||0;
  var n=parseFloat(document.getElementById('b3-needs').value)/100;
  var w=parseFloat(document.getElementById('b3-wants').value)/100;
  var s=parseFloat(document.getElementById('b3-savings').value)/100;
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('b3-needs-amt').textContent=fmt(I*n)+'/mo';
  document.getElementById('b3-wants-amt').textContent=fmt(I*w)+'/mo';
  document.getElementById('b3-savings-amt').textContent=fmt(I*s)+'/mo';
  document.getElementById('b3-annual').textContent=fmt(I*s*12)+'/yr';
  document.getElementById('b3-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   26. DEBT TO INCOME RATIO
───────────────────────────────────────────── */
function fs_calc_dti() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Gross Monthly Income ($)</label><input type="number" id="dti-income" value="7000"></div>
    <div class="fsc-field"><label>Mortgage/Rent ($)</label><input type="number" id="dti-mortgage" value="1800"></div>
    <div class="fsc-field"><label>Car Payments ($)</label><input type="number" id="dti-car" value="400"></div>
    <div class="fsc-field"><label>Student Loans ($)</label><input type="number" id="dti-student" value="300"></div>
    <div class="fsc-field"><label>Credit Card Minimums ($)</label><input type="number" id="dti-cc" value="150"></div>
    <div class="fsc-field"><label>Other Debts ($)</label><input type="number" id="dti-other" value="100"></div>
    <button class="fsc-btn" onclick="calcDTI()">Calculate DTI</button>
  </div>
  <div class="fsc-results" id="dti-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Front-End DTI</div><div class="fsc-result-value" id="dti-front">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Back-End DTI</div><div class="fsc-result-value" id="dti-back">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Monthly Debt</div><div class="fsc-result-value" id="dti-total">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Loan Qualification</div><div class="fsc-result-value" id="dti-qual">—</div></div>
  </div>
</div></div>
<script>
function calcDTI(){
  var I=parseFloat(document.getElementById('dti-income').value)||0;
  var mortgage=parseFloat(document.getElementById('dti-mortgage').value)||0;
  var car=parseFloat(document.getElementById('dti-car').value)||0;
  var student=parseFloat(document.getElementById('dti-student').value)||0;
  var cc=parseFloat(document.getElementById('dti-cc').value)||0;
  var other=parseFloat(document.getElementById('dti-other').value)||0;
  var front=mortgage/I*100;
  var back=(mortgage+car+student+cc+other)/I*100;
  var qual=back<=36?'Excellent':back<=43?'Good – FHA/Conv':back<=50?'Borderline':' High Risk';
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('dti-front').textContent=front.toFixed(1)+'%';
  document.getElementById('dti-back').textContent=back.toFixed(1)+'%';
  document.getElementById('dti-total').textContent=fmt(mortgage+car+student+cc+other);
  document.getElementById('dti-qual').textContent=qual;
  document.getElementById('dti-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   27. NET WORTH CALCULATOR
───────────────────────────────────────────── */
function fs_calc_net_worth() { ?>
<div class="fsc-wrap">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
  <div class="fsc-inputs">
    <h3 class="fsc-section-title">Assets</h3>
    <div class="fsc-field"><label>Cash & Savings ($)</label><input type="number" id="nw-cash" value="15000"></div>
    <div class="fsc-field"><label>Investments ($)</label><input type="number" id="nw-invest" value="80000"></div>
    <div class="fsc-field"><label>Retirement Accounts ($)</label><input type="number" id="nw-retire" value="120000"></div>
    <div class="fsc-field"><label>Home Value ($)</label><input type="number" id="nw-home" value="350000"></div>
    <div class="fsc-field"><label>Vehicles ($)</label><input type="number" id="nw-vehicles" value="25000"></div>
    <div class="fsc-field"><label>Other Assets ($)</label><input type="number" id="nw-other-a" value="10000"></div>
  </div>
  <div class="fsc-inputs">
    <h3 class="fsc-section-title">Liabilities</h3>
    <div class="fsc-field"><label>Mortgage Balance ($)</label><input type="number" id="nw-mortgage" value="220000"></div>
    <div class="fsc-field"><label>Car Loans ($)</label><input type="number" id="nw-car-loan" value="18000"></div>
    <div class="fsc-field"><label>Student Loans ($)</label><input type="number" id="nw-student" value="35000"></div>
    <div class="fsc-field"><label>Credit Card Debt ($)</label><input type="number" id="nw-cc" value="5000"></div>
    <div class="fsc-field"><label>Other Debts ($)</label><input type="number" id="nw-other-l" value="2000"></div>
  </div>
</div>
<button class="fsc-btn" style="margin-top:1rem" onclick="calcNetWorth()">Calculate Net Worth</button>
<div class="fsc-results" id="nw-results" style="display:none;margin-top:1.5rem">
  <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Net Worth</div><div class="fsc-result-value" id="nw-net">—</div></div>
  <div class="fsc-result-card"><div class="fsc-result-label">Total Assets</div><div class="fsc-result-value" id="nw-assets">—</div></div>
  <div class="fsc-result-card"><div class="fsc-result-label">Total Liabilities</div><div class="fsc-result-value" id="nw-liabilities">—</div></div>
  <div class="fsc-result-card"><div class="fsc-result-label">Debt Ratio</div><div class="fsc-result-value" id="nw-ratio">—</div></div>
</div>
</div>
<script>
function calcNetWorth(){
  var assets=(parseFloat(document.getElementById('nw-cash').value)||0)+(parseFloat(document.getElementById('nw-invest').value)||0)+(parseFloat(document.getElementById('nw-retire').value)||0)+(parseFloat(document.getElementById('nw-home').value)||0)+(parseFloat(document.getElementById('nw-vehicles').value)||0)+(parseFloat(document.getElementById('nw-other-a').value)||0);
  var liab=(parseFloat(document.getElementById('nw-mortgage').value)||0)+(parseFloat(document.getElementById('nw-car-loan').value)||0)+(parseFloat(document.getElementById('nw-student').value)||0)+(parseFloat(document.getElementById('nw-cc').value)||0)+(parseFloat(document.getElementById('nw-other-l').value)||0);
  var net=assets-liab;
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  var netEl=document.getElementById('nw-net');netEl.textContent=fmt(net);netEl.style.color=net>=0?'#00C896':'#F87171';
  document.getElementById('nw-assets').textContent=fmt(assets);
  document.getElementById('nw-liabilities').textContent=fmt(liab);
  document.getElementById('nw-ratio').textContent=(liab/assets*100).toFixed(1)+'%';
  document.getElementById('nw-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   28. CURRENCY CONVERTER
───────────────────────────────────────────── */
function fs_calc_currency() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Amount</label><input type="number" id="cc-amount" value="1000"></div>
    <div class="fsc-field"><label>From Currency</label>
      <select id="cc-from"><option value="1">USD – US Dollar</option><option value="0.93">EUR – Euro</option><option value="0.79">GBP – British Pound</option><option value="149.5">JPY – Japanese Yen</option><option value="1.36">CAD – Canadian Dollar</option><option value="1.54">AUD – Australian Dollar</option><option value="0.89">CHF – Swiss Franc</option><option value="7.24">CNY – Chinese Yuan</option><option value="83.5">INR – Indian Rupee</option><option value="5.05">BRL – Brazilian Real</option></select></div>
    <div class="fsc-field"><label>To Currency</label>
      <select id="cc-to"><option value="0.93">EUR – Euro</option><option value="1">USD – US Dollar</option><option value="0.79">GBP – British Pound</option><option value="149.5">JPY – Japanese Yen</option><option value="1.36">CAD – Canadian Dollar</option><option value="1.54">AUD – Australian Dollar</option><option value="0.89">CHF – Swiss Franc</option><option value="7.24">CNY – Chinese Yuan</option><option value="83.5">INR – Indian Rupee</option><option value="5.05">BRL – Brazilian Real</option></select></div>
    <p style="font-size:.8rem;color:rgba(255,255,255,.5);margin-top:-.5rem">*Approximate rates for reference only</p>
    <button class="fsc-btn" onclick="calcCurrency()">Convert</button>
  </div>
  <div class="fsc-results" id="cc-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Converted Amount</div><div class="fsc-result-value" id="cc-converted">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Exchange Rate</div><div class="fsc-result-value" id="cc-rate">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Inverse Rate</div><div class="fsc-result-value" id="cc-inverse">—</div></div>
  </div>
</div></div>
<script>
function calcCurrency(){
  var amt=parseFloat(document.getElementById('cc-amount').value)||0;
  var from=parseFloat(document.getElementById('cc-from').value)||1;
  var to=parseFloat(document.getElementById('cc-to').value)||1;
  var rate=to/from;
  var result=amt*rate;
  document.getElementById('cc-converted').textContent=result.toFixed(4);
  document.getElementById('cc-rate').textContent='1 = '+rate.toFixed(4);
  document.getElementById('cc-inverse').textContent='1 = '+(1/rate).toFixed(4);
  document.getElementById('cc-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   29. CRYPTO CONVERT
───────────────────────────────────────────── */
function fs_calc_crypto_convert( $title ) { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Amount</label><input type="number" id="crc-amount" value="1" step="0.0001"></div>
    <div class="fsc-field"><label>Cryptocurrency</label>
      <select id="crc-coin">
        <option value="104000">Bitcoin (BTC)</option>
        <option value="2500">Ethereum (ETH)</option>
        <option value="0.5">XRP</option>
        <option value="1">USDT (Tether)</option>
        <option value="400">BNB</option>
        <option value="150">Solana (SOL)</option>
        <option value="0.35">Cardano (ADA)</option>
        <option value="32">Dogecoin (DOGE)</option>
      </select></div>
    <div class="fsc-field"><label>To Fiat</label>
      <select id="crc-fiat"><option value="1">USD</option><option value="0.93">EUR</option><option value="0.79">GBP</option><option value="83.5">INR</option><option value="1.36">CAD</option></select></div>
    <button class="fsc-btn" onclick="calcCryptoConvert()">Convert</button>
    <p style="font-size:.8rem;color:rgba(255,255,255,.5);margin-top:.5rem">*Approximate prices for reference</p>
  </div>
  <div class="fsc-results" id="crc-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Fiat Value</div><div class="fsc-result-value" id="crc-value">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Price Per Coin (USD)</div><div class="fsc-result-value" id="crc-price">—</div></div>
  </div>
</div></div>
<script>
function calcCryptoConvert(){
  var amt=parseFloat(document.getElementById('crc-amount').value)||0;
  var coin=parseFloat(document.getElementById('crc-coin').value)||0;
  var fiat=parseFloat(document.getElementById('crc-fiat').value)||1;
  var value=amt*coin*fiat;
  document.getElementById('crc-value').textContent=value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');
  document.getElementById('crc-price').textContent='$'+coin.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');
  document.getElementById('crc-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   30. CRYPTO P&L
───────────────────────────────────────────── */
function fs_calc_crypto_pnl() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Cryptocurrency</label><input type="text" id="cp-coin" value="Bitcoin (BTC)"></div>
    <div class="fsc-field"><label>Buy Price ($)</label><input type="number" id="cp-buy" value="40000"></div>
    <div class="fsc-field"><label>Sell Price ($)</label><input type="number" id="cp-sell" value="65000"></div>
    <div class="fsc-field"><label>Amount (coins)</label><input type="number" id="cp-qty" value="0.5" step="0.001"></div>
    <div class="fsc-field"><label>Trading Fees (%)</label><input type="number" id="cp-fees" value="0.1" step="0.01"></div>
    <button class="fsc-btn" onclick="calcCryptoPnL()">Calculate P&amp;L</button>
  </div>
  <div class="fsc-results" id="cp-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Net Profit</div><div class="fsc-result-value" id="cp-profit">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">ROI</div><div class="fsc-result-value" id="cp-roi">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Fees Paid</div><div class="fsc-result-value" id="cp-fee-amt">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Break-Even Price</div><div class="fsc-result-value" id="cp-be">—</div></div>
  </div>
</div></div>
<script>
function calcCryptoPnL(){
  var buy=parseFloat(document.getElementById('cp-buy').value)||0;
  var sell=parseFloat(document.getElementById('cp-sell').value)||0;
  var qty=parseFloat(document.getElementById('cp-qty').value)||0;
  var feeR=parseFloat(document.getElementById('cp-fees').value)/100;
  var cost=buy*qty*(1+feeR);
  var proceeds=sell*qty*(1-feeR);
  var profit=proceeds-cost;
  var fees=buy*qty*feeR+sell*qty*feeR;
  var be=buy*(1+feeR)/(1-feeR);
  var fmt=function(v){return '$'+v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  var profEl=document.getElementById('cp-profit');profEl.textContent=(profit>=0?'+':'')+fmt(profit);profEl.style.color=profit>=0?'#00C896':'#F87171';
  document.getElementById('cp-roi').textContent=(profit/cost*100).toFixed(2)+'%';
  document.getElementById('cp-fee-amt').textContent=fmt(fees);
  document.getElementById('cp-be').textContent=fmt(be);
  document.getElementById('cp-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   31. STAKING REWARDS
───────────────────────────────────────────── */
function fs_calc_staking( $title ) { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Staked Amount ($)</label><input type="number" id="sk-amount" value="10000"></div>
    <div class="fsc-field"><label>APY / APR (%)</label><input type="number" id="sk-apy" value="12" step="0.1"></div>
    <div class="fsc-field"><label>Staking Period (Months)</label><input type="number" id="sk-months" value="12"></div>
    <div class="fsc-field"><label>Compound Frequency</label><select id="sk-freq"><option value="365">Daily</option><option value="12" selected>Monthly</option><option value="4">Quarterly</option><option value="1">Annually</option></select></div>
    <button class="fsc-btn" onclick="calcStaking()">Calculate Rewards</button>
  </div>
  <div class="fsc-results" id="sk-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Total Rewards</div><div class="fsc-result-value" id="sk-rewards">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Final Value</div><div class="fsc-result-value" id="sk-final">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Monthly Reward</div><div class="fsc-result-value" id="sk-monthly">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Effective APY</div><div class="fsc-result-value" id="sk-eff">—</div></div>
  </div>
</div></div>
<script>
function calcStaking(){
  var P=parseFloat(document.getElementById('sk-amount').value)||0;
  var apy=parseFloat(document.getElementById('sk-apy').value)/100;
  var months=parseFloat(document.getElementById('sk-months').value)||1;
  var n=parseFloat(document.getElementById('sk-freq').value);
  var years=months/12;
  var F=P*Math.pow(1+apy/n,n*years);
  var effApy=(Math.pow(1+apy/n,n)-1)*100;
  var fmt=function(v){return '$'+v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('sk-rewards').textContent=fmt(F-P);
  document.getElementById('sk-final').textContent=fmt(F);
  document.getElementById('sk-monthly').textContent=fmt((F-P)/months);
  document.getElementById('sk-eff').textContent=effApy.toFixed(2)+'%';
  document.getElementById('sk-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   32. MINING PROFITABILITY
───────────────────────────────────────────── */
function fs_calc_mining() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Hash Rate (TH/s)</label><input type="number" id="mn-hash" value="110" step="1"></div>
    <div class="fsc-field"><label>Power Consumption (W)</label><input type="number" id="mn-power" value="3250"></div>
    <div class="fsc-field"><label>Electricity Cost ($/kWh)</label><input type="number" id="mn-elec" value="0.12" step="0.01"></div>
    <div class="fsc-field"><label>BTC Price ($)</label><input type="number" id="mn-btc" value="104000"></div>
    <div class="fsc-field"><label>Pool Fee (%)</label><input type="number" id="mn-fee" value="1" step="0.1"></div>
    <button class="fsc-btn" onclick="calcMining()">Calculate</button>
  </div>
  <div class="fsc-results" id="mn-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Daily Profit</div><div class="fsc-result-value" id="mn-daily">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Monthly Profit</div><div class="fsc-result-value" id="mn-monthly">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Daily Electricity Cost</div><div class="fsc-result-value" id="mn-elec-cost">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Break-Even BTC Price</div><div class="fsc-result-value" id="mn-be">—</div></div>
  </div>
</div></div>
<script>
function calcMining(){
  var H=parseFloat(document.getElementById('mn-hash').value)||0;
  var W=parseFloat(document.getElementById('mn-power').value)||0;
  var E=parseFloat(document.getElementById('mn-elec').value)||0;
  var BTC=parseFloat(document.getElementById('mn-btc').value)||0;
  var fee=parseFloat(document.getElementById('mn-fee').value)/100;
  // Approximate BTC mined per day: H(TH/s) * 86400 / difficulty_factor
  var btcPerDay=H*86400/(85000000000000)*6.25*(1-fee); // simplified estimate
  var revenuePerDay=btcPerDay*BTC;
  var elecPerDay=W/1000*24*E;
  var profitPerDay=revenuePerDay-elecPerDay;
  var bePrice=elecPerDay/btcPerDay;
  var fmt=function(v){return v>=0?'$'+v.toFixed(2):'−$'+Math.abs(v).toFixed(2);};
  document.getElementById('mn-daily').textContent=fmt(profitPerDay);
  document.getElementById('mn-monthly').textContent=fmt(profitPerDay*30);
  document.getElementById('mn-elec-cost').textContent='$'+elecPerDay.toFixed(2);
  document.getElementById('mn-be').textContent='$'+bePrice.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');
  document.getElementById('mn-results').style.display='grid';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   33. RISK ASSESSMENT
───────────────────────────────────────────── */
function fs_calc_risk() { ?>
<div class="fsc-wrap">
<p style="color:rgba(255,255,255,.7);margin-bottom:1.5rem">Answer the questions below to discover your investor risk profile.</p>
<div class="fsc-inputs">
  <div class="fsc-field"><label>1. Investment time horizon</label>
    <select id="rk-time"><option value="1">Less than 3 years</option><option value="2">3–5 years</option><option value="3" selected>5–10 years</option><option value="4">More than 10 years</option></select></div>
  <div class="fsc-field"><label>2. If your portfolio dropped 20%, you would:</label>
    <select id="rk-drop"><option value="1">Sell everything</option><option value="2">Sell some</option><option value="3" selected>Hold steady</option><option value="4">Buy more</option></select></div>
  <div class="fsc-field"><label>3. Your primary goal is:</label>
    <select id="rk-goal"><option value="1">Capital preservation</option><option value="2" selected>Moderate growth</option><option value="3">Aggressive growth</option></select></div>
  <div class="fsc-field"><label>4. What % of savings are you investing?</label>
    <select id="rk-pct"><option value="1">Less than 10%</option><option value="2" selected>10–30%</option><option value="3">30–60%</option><option value="4">Over 60%</option></select></div>
  <button class="fsc-btn" onclick="calcRisk()">Get My Risk Profile</button>
</div>
<div id="rk-results" style="display:none;margin-top:1.5rem">
  <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Risk Profile</div><div class="fsc-result-value" id="rk-profile">—</div></div>
  <div id="rk-allocation" style="margin-top:1rem"></div>
</div>
</div>
<script>
function calcRisk(){
  var s=parseInt(document.getElementById('rk-time').value)+parseInt(document.getElementById('rk-drop').value)+parseInt(document.getElementById('rk-goal').value)+parseInt(document.getElementById('rk-pct').value);
  var profile,stocks,bonds,cash;
  if(s<=6){profile='Conservative';stocks=30;bonds=50;cash=20;}
  else if(s<=9){profile='Moderate';stocks=60;bonds=35;cash=5;}
  else if(s<=12){profile='Growth';stocks=80;bonds=18;cash=2;}
  else{profile='Aggressive Growth';stocks=95;bonds=5;cash=0;}
  document.getElementById('rk-profile').textContent=profile;
  document.getElementById('rk-allocation').innerHTML='<div style="margin-top:.5rem"><div class="fsc-pf-row"><span>Stocks/Equity</span><div class="fsc-pf-bar-wrap"><div class="fsc-pf-bar" style="width:'+stocks+'%;background:#3B82F6"></div></div><span>'+stocks+'%</span></div><div class="fsc-pf-row"><span>Bonds/Fixed Income</span><div class="fsc-pf-bar-wrap"><div class="fsc-pf-bar" style="width:'+bonds+'%;background:#00C896"></div></div><span>'+bonds+'%</span></div><div class="fsc-pf-row"><span>Cash/Equivalents</span><div class="fsc-pf-bar-wrap"><div class="fsc-pf-bar" style="width:'+(cash||1)+'%;background:#F59E0B"></div></div><span>'+cash+'%</span></div></div>';
  document.getElementById('rk-results').style.display='block';
}
</script>
<?php }

/* ─────────────────────────────────────────────
   34. SIMPLE CALCULATOR (fallback)
───────────────────────────────────────────── */
function fs_calc_simple( $title ) { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Amount ($)</label><input type="number" id="sc-amount" value="10000"></div>
    <div class="fsc-field"><label>Rate (%)</label><input type="number" id="sc-rate" value="10" step="0.1"></div>
    <div class="fsc-field"><label>Time Period (Years)</label><input type="number" id="sc-years" value="5"></div>
    <button class="fsc-btn" onclick="calcSimple()">Calculate</button>
  </div>
  <div class="fsc-results" id="sc-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Result</div><div class="fsc-result-value" id="sc-result">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Amount</div><div class="fsc-result-value" id="sc-total">—</div></div>
  </div>
</div></div>
<script>
function calcSimple(){
  var A=parseFloat(document.getElementById('sc-amount').value)||0;
  var r=parseFloat(document.getElementById('sc-rate').value)/100;
  var y=parseFloat(document.getElementById('sc-years').value)||1;
  var result=A*r*y;
  var total=A+result;
  var fmt=function(v){return '$'+v.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('sc-result').textContent=fmt(result);
  document.getElementById('sc-total').textContent=fmt(total);
  document.getElementById('sc-results').style.display='grid';
}
</script>
<?php }

/* ═══════════════════════════════════════════════════════════
   SPECIALIZED LOAN CALCULATORS (18 Loan Calculators category)
   ═══════════════════════════════════════════════════════════ */

/* ─── MORTGAGE CALCULATOR ─── */
function fs_calc_mortgage() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Home Price ($)</label><input type="number" id="mg-price" value="400000" min="10000" oninput="mgLiveDP()"></div>
    <div class="fsc-field">
      <label>Down Payment</label>
      <div style="display:flex;gap:.5rem;align-items:center">
        <input type="number" id="mg-down" value="80000" min="0" style="flex:1" oninput="mgSyncPct()">
        <input type="number" id="mg-dpct" value="20" min="0" max="100" step="0.5" style="width:70px" oninput="mgSyncAmt()">
        <span style="font-weight:700;color:var(--fs-primary)">%</span>
      </div>
    </div>
    <div class="fsc-field"><label>Annual Interest Rate (%)</label><input type="number" id="mg-rate" value="7.0" step="0.1" min="0.1"></div>
    <div class="fsc-field"><label>Loan Term</label>
      <select id="mg-term"><option value="30">30 Years (Fixed)</option><option value="20">20 Years</option><option value="15">15 Years (Fixed)</option><option value="10">10 Years</option></select>
    </div>
    <div class="fsc-field"><label>Annual Property Tax ($)</label><input type="number" id="mg-tax" value="4800"></div>
    <div class="fsc-field"><label>Annual Home Insurance ($)</label><input type="number" id="mg-ins" value="1200"></div>
    <div class="fsc-field"><label>Monthly PMI ($) <small style="font-weight:400;color:#888">(if &lt;20% down)</small></label><input type="number" id="mg-pmi" value="0"></div>
    <div class="fsc-field"><label>Monthly HOA ($)</label><input type="number" id="mg-hoa" value="0"></div>
    <div style="display:flex;gap:.75rem;flex-wrap:wrap">
      <button class="fsc-btn" onclick="calcMortgage()" style="flex:1">Calculate Payment</button>
      <button onclick="mgCompare()" style="flex:1;background:#F8FAFC;color:var(--fs-text);border:1.5px solid var(--fs-border);border-radius:8px;padding:.75rem;font-weight:700;cursor:pointer">&#9878;&#65039; Compare Terms</button>
    </div>
  </div>
  <div class="fsc-results" id="mg-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Total Monthly Payment</div><div class="fsc-result-value" id="mg-total-pay">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Principal &amp; Interest</div><div class="fsc-result-value" id="mg-pi">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Loan Amount</div><div class="fsc-result-value" id="mg-loan">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Interest Paid</div><div class="fsc-result-value" id="mg-int">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Cost (Life of Loan)</div><div class="fsc-result-value" id="mg-cost">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">LTV Ratio</div><div class="fsc-result-value" id="mg-ltv">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Interest-to-Principal Ratio</div><div class="fsc-result-value" id="mg-ipr">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Payoff Year</div><div class="fsc-result-value" id="mg-payoffyr">—</div></div>
  </div>
</div>

<!-- Payment Breakdown -->
<div id="mg-breakdown" style="display:none;margin-top:1.5rem">
  <h3 style="font-size:1rem;font-weight:700;margin-bottom:1rem">&#128202; Monthly Payment Breakdown</h3>
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:.75rem;margin-bottom:1.5rem">
    <div class="fsc-result-card"><div class="fsc-result-label">&#127968; P&amp;I</div><div class="fsc-result-value" style="font-size:1.1rem" id="mg-b-pi">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">&#127963;&#65039; Property Tax</div><div class="fsc-result-value" style="font-size:1.1rem" id="mg-b-tax">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">&#128737;&#65039; Insurance</div><div class="fsc-result-value" style="font-size:1.1rem" id="mg-b-ins">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">&#128203; PMI</div><div class="fsc-result-value" style="font-size:1.1rem" id="mg-b-pmi">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">&#127970; HOA</div><div class="fsc-result-value" style="font-size:1.1rem" id="mg-b-hoa">—</div></div>
  </div>
  <!-- Charts -->
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
    <div style="background:#fff;border:1.5px solid var(--fs-border);border-radius:12px;padding:1rem">
      <h4 style="font-size:.85rem;font-weight:700;margin:0 0 .75rem;text-align:center">Payment Breakdown</h4>
      <canvas id="mg-pie-chart" height="200"></canvas>
    </div>
    <div style="background:#fff;border:1.5px solid var(--fs-border);border-radius:12px;padding:1rem">
      <h4 style="font-size:.85rem;font-weight:700;margin:0 0 .75rem;text-align:center">Balance Over Time</h4>
      <canvas id="mg-line-chart" height="200"></canvas>
    </div>
  </div>
</div>

<!-- Term Comparison Table -->
<div id="mg-compare-table" style="display:none;margin-top:1.5rem">
  <h3 style="font-size:1rem;font-weight:700;margin-bottom:1rem">&#9878;&#65039; Term Comparison</h3>
  <div class="fsc-table-wrap"><table class="fsc-table">
    <thead><tr><th>Term</th><th>Monthly P&amp;I</th><th>Total Interest</th><th>Total Cost</th><th>Interest Saved vs 30yr</th></tr></thead>
    <tbody id="mg-compare-tbody"></tbody>
  </table></div>
</div>
</div>

<script>
var mgPieChart=null, mgLineChart=null;

function mgSyncPct(){
  var price=parseFloat(document.getElementById('mg-price').value)||400000;
  var down=parseFloat(document.getElementById('mg-down').value)||0;
  document.getElementById('mg-dpct').value=(down/price*100).toFixed(1);
  // Auto-suggest PMI
  if(down/price < 0.2 && !parseFloat(document.getElementById('mg-pmi').value)){
    document.getElementById('mg-pmi').value=Math.round((price-down)*0.005/12);
  }
}
function mgSyncAmt(){
  var price=parseFloat(document.getElementById('mg-price').value)||400000;
  var pct=parseFloat(document.getElementById('mg-dpct').value)||0;
  document.getElementById('mg-down').value=Math.round(price*pct/100);
}
function mgLiveDP(){
  var price=parseFloat(document.getElementById('mg-price').value)||400000;
  var pct=parseFloat(document.getElementById('mg-dpct').value)||20;
  document.getElementById('mg-down').value=Math.round(price*pct/100);
}

function calcMortgage(){
  var price=parseFloat(document.getElementById('mg-price').value)||0;
  var down=parseFloat(document.getElementById('mg-down').value)||0;
  var annRate=parseFloat(document.getElementById('mg-rate').value)||0;
  var rate=annRate/100/12;
  var n=parseInt(document.getElementById('mg-term').value)*12;
  var tax=parseFloat(document.getElementById('mg-tax').value)||0;
  var ins=parseFloat(document.getElementById('mg-ins').value)||0;
  var pmi=parseFloat(document.getElementById('mg-pmi').value)||0;
  var hoa=parseFloat(document.getElementById('mg-hoa').value)||0;
  if(!price||!n)return;
  var P=price-down;
  var M=rate===0?P/n:P*(rate*Math.pow(1+rate,n))/(Math.pow(1+rate,n)-1);
  var totalPI=M*n;
  var totalInt=totalPI-P;
  var totalPay=M+tax/12+ins/12+pmi+hoa;
  var ltv=(P/price*100).toFixed(1);
  var fmt=function(v){return '$'+Math.round(v).toLocaleString();};

  document.getElementById('mg-total-pay').textContent=fmt(totalPay)+'/mo';
  document.getElementById('mg-pi').textContent=fmt(M)+'/mo';
  document.getElementById('mg-loan').textContent=fmt(P);
  document.getElementById('mg-int').textContent=fmt(totalInt);
  document.getElementById('mg-cost').textContent=fmt(totalPay*n);
  document.getElementById('mg-ltv').textContent=ltv+'%';
  document.getElementById('mg-ipr').textContent=(totalInt/P*100).toFixed(1)+'%';
  var yr=new Date().getFullYear()+parseInt(document.getElementById('mg-term').value);
  document.getElementById('mg-payoffyr').textContent=yr;

  document.getElementById('mg-b-pi').textContent=fmt(M);
  document.getElementById('mg-b-tax').textContent=fmt(tax/12);
  document.getElementById('mg-b-ins').textContent=fmt(ins/12);
  document.getElementById('mg-b-pmi').textContent=fmt(pmi);
  document.getElementById('mg-b-hoa').textContent=fmt(hoa);

  document.getElementById('mg-results').style.display='grid';
  document.getElementById('mg-breakdown').style.display='block';

  /* Pie chart */
  if(mgPieChart) mgPieChart.destroy();
  var ctx1=document.getElementById('mg-pie-chart').getContext('2d');
  mgPieChart=new Chart(ctx1,{type:'doughnut',data:{
    labels:['Principal','Interest','Tax','Insurance','PMI','HOA'],
    datasets:[{data:[P,totalInt,tax*n/12,ins*n/12,pmi*n,hoa*n],
      backgroundColor:['#00C896','#3B82F6','#F59E0B','#EF4444','#8B5CF6','#6B7280']}]
  },options:{plugins:{legend:{position:'bottom',labels:{font:{size:10}}}},cutout:'60%'}});

  /* Line chart — balance over time */
  var labels=[],balances=[];
  var bal=P;
  for(var m=0;m<=n;m+=Math.max(1,Math.floor(n/12))){
    labels.push(m===0?'Start':Math.round(m/12)+'yr');
    balances.push(Math.max(0,bal));
    for(var step=0;step<Math.max(1,Math.floor(n/12))&&bal>0;step++){
      var interest=bal*rate;
      bal-=(M-interest);
    }
  }
  if(mgLineChart) mgLineChart.destroy();
  var ctx2=document.getElementById('mg-line-chart').getContext('2d');
  mgLineChart=new Chart(ctx2,{type:'line',data:{
    labels:labels,
    datasets:[{label:'Remaining Balance',data:balances,borderColor:'#3B82F6',backgroundColor:'rgba(59,130,246,.1)',fill:true,tension:.3,pointRadius:3}]
  },options:{plugins:{legend:{display:false}},scales:{y:{ticks:{callback:function(v){return '$'+Math.round(v/1000)+'k';}}}}}});
}

function mgCompare(){
  var price=parseFloat(document.getElementById('mg-price').value)||0;
  var down=parseFloat(document.getElementById('mg-down').value)||0;
  var annRate=parseFloat(document.getElementById('mg-rate').value)||0;
  var rate=annRate/100/12;
  var P=price-down;
  if(!P)return;
  var terms=[10,15,20,30];
  var base30M,base30Int;
  var rows='';
  terms.forEach(function(yr){
    var n=yr*12;
    var M=rate===0?P/n:P*(rate*Math.pow(1+rate,n))/(Math.pow(1+rate,n)-1);
    var totalInt=M*n-P;
    var totalCost=M*n;
    if(yr===30){base30M=M;base30Int=totalInt;}
    var saved=base30Int!==undefined?base30Int-totalInt:0;
    var fmt=function(v){return '$'+Math.round(v).toLocaleString();};
    rows+='<tr>'
      +'<td><strong>'+yr+' yr</strong></td>'
      +'<td>'+fmt(M)+'/mo</td>'
      +'<td>'+fmt(totalInt)+'</td>'
      +'<td>'+fmt(totalCost)+'</td>'
      +'<td style="color:'+(saved>0?'#00C896':'#6B7280')+';font-weight:700">'+(saved>0?fmt(saved)+' saved':'—')+'</td>'
      +'</tr>';
  });
  document.getElementById('mg-compare-tbody').innerHTML=rows;
  document.getElementById('mg-compare-table').style.display='block';
  fsPdfBarShow();
}
</script>
<?php }

/* ─── AUTO LOAN CALCULATOR ─── */
function fs_calc_auto_loan() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Vehicle Price ($)</label><input type="number" id="al-price" value="35000" min="1000"></div>
    <div class="fsc-field"><label>Down Payment ($)</label><input type="number" id="al-down" value="5000" min="0"></div>
    <div class="fsc-field"><label>Trade-In Value ($)</label><input type="number" id="al-trade" value="0" min="0"></div>
    <div class="fsc-field"><label>Sales Tax Rate (%)</label><input type="number" id="al-tax" value="8.0" step="0.1" min="0"></div>
    <div class="fsc-field"><label>Loan APR (%)</label><input type="number" id="al-rate" value="6.5" step="0.1" min="0.1"></div>
    <div class="fsc-field"><label>Loan Term</label>
      <select id="al-term">
        <option value="24">24 Months (2 yr)</option>
        <option value="36">36 Months (3 yr)</option>
        <option value="48">48 Months (4 yr)</option>
        <option value="60" selected>60 Months (5 yr)</option>
        <option value="72">72 Months (6 yr)</option>
        <option value="84">84 Months (7 yr)</option>
      </select>
    </div>
    <button class="fsc-btn" onclick="calcAutoLoan()">Calculate Payment</button>
  </div>
  <div class="fsc-results" id="al-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Monthly Payment</div><div class="fsc-result-value" id="al-monthly">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Loan Amount</div><div class="fsc-result-value" id="al-loan">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Interest</div><div class="fsc-result-value" id="al-interest">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Cost of Car</div><div class="fsc-result-value" id="al-total">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Tax Amount</div><div class="fsc-result-value" id="al-taxamt">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Payoff Date</div><div class="fsc-result-value" id="al-payoff">—</div></div>
  </div>
</div>
</div>
<script>
function calcAutoLoan(){
  var price=parseFloat(document.getElementById('al-price').value)||0;
  var down=parseFloat(document.getElementById('al-down').value)||0;
  var trade=parseFloat(document.getElementById('al-trade').value)||0;
  var taxRate=parseFloat(document.getElementById('al-tax').value)/100;
  var rate=parseFloat(document.getElementById('al-rate').value)/100/12;
  var n=parseInt(document.getElementById('al-term').value);
  var taxAmt=price*taxRate;
  var P=price+taxAmt-down-trade;
  if(P<=0){P=0.01;}
  var M=rate===0?P/n:P*(rate*Math.pow(1+rate,n))/(Math.pow(1+rate,n)-1);
  var totalInt=M*n-P;
  var totalCost=price+taxAmt+totalInt-trade;
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('al-monthly').textContent=fmt(M)+'/mo';
  document.getElementById('al-loan').textContent=fmt(P);
  document.getElementById('al-interest').textContent=fmt(totalInt);
  document.getElementById('al-total').textContent=fmt(totalCost);
  document.getElementById('al-taxamt').textContent=fmt(taxAmt);
  var d=new Date();d.setMonth(d.getMonth()+n);
  document.getElementById('al-payoff').textContent=d.toLocaleDateString('en-US',{month:'short',year:'numeric'});
  document.getElementById('al-results').style.display='grid';
}
</script>
<?php }

/* ─── PERSONAL LOAN CALCULATOR ─── */
function fs_calc_personal_loan() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Loan Amount ($)</label><input type="number" id="pl-amount" value="15000" min="500"></div>
    <div class="fsc-field"><label>Annual Interest Rate (APR %)</label><input type="number" id="pl-rate" value="10.5" step="0.1" min="1"></div>
    <div class="fsc-field"><label>Loan Term</label>
      <select id="pl-term">
        <option value="1">1 Year</option>
        <option value="2">2 Years</option>
        <option value="3" selected>3 Years</option>
        <option value="4">4 Years</option>
        <option value="5">5 Years</option>
        <option value="7">7 Years</option>
      </select>
    </div>
    <div class="fsc-field"><label>Origination Fee (%)</label><input type="number" id="pl-fee" value="1.0" step="0.1" min="0"></div>
    <button class="fsc-btn" onclick="calcPersonalLoan()">Calculate</button>
  </div>
  <div class="fsc-results" id="pl-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Monthly Payment</div><div class="fsc-result-value" id="pl-monthly">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Repayment</div><div class="fsc-result-value" id="pl-total">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Interest</div><div class="fsc-result-value" id="pl-interest">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Origination Fee</div><div class="fsc-result-value" id="pl-feeval">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Net Loan Received</div><div class="fsc-result-value" id="pl-net">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Effective APR</div><div class="fsc-result-value" id="pl-apr">—</div></div>
  </div>
</div>
</div>
<script>
function calcPersonalLoan(){
  var P=parseFloat(document.getElementById('pl-amount').value)||0;
  var rate=parseFloat(document.getElementById('pl-rate').value)/100/12;
  var n=parseInt(document.getElementById('pl-term').value)*12;
  var feePct=parseFloat(document.getElementById('pl-fee').value)/100;
  var fee=P*feePct;
  var M=rate===0?P/n:P*(rate*Math.pow(1+rate,n))/(Math.pow(1+rate,n)-1);
  var total=M*n;
  var interest=total-P;
  var net=P-fee;
  var effRate=((total+fee-P)/P/n*12*100).toFixed(2);
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('pl-monthly').textContent=fmt(M)+'/mo';
  document.getElementById('pl-total').textContent=fmt(total);
  document.getElementById('pl-interest').textContent=fmt(interest);
  document.getElementById('pl-feeval').textContent=fmt(fee);
  document.getElementById('pl-net').textContent=fmt(net);
  document.getElementById('pl-apr').textContent=effRate+'%';
  document.getElementById('pl-results').style.display='grid';
}
</script>
<?php }

/* ─── STUDENT LOAN CALCULATOR ─── */
function fs_calc_student_loan() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Total Loan Balance ($)</label><input type="number" id="sl-amount" value="45000" min="1000"></div>
    <div class="fsc-field"><label>Annual Interest Rate (%)</label><input type="number" id="sl-rate" value="6.54" step="0.01" min="0.1"></div>
    <div class="fsc-field"><label>Repayment Plan</label>
      <select id="sl-plan" onchange="slPlanChange()">
        <option value="standard">Standard (10 Years)</option>
        <option value="extended">Extended (25 Years)</option>
        <option value="graduated">Graduated (10 Years)</option>
        <option value="custom">Custom Term</option>
      </select>
    </div>
    <div class="fsc-field" id="sl-custom-wrap" style="display:none"><label>Custom Term (Years)</label><input type="number" id="sl-custom" value="15" min="1" max="30"></div>
    <div class="fsc-field"><label>Grace Period (Months)</label><input type="number" id="sl-grace" value="6" min="0" max="12"></div>
    <button class="fsc-btn" onclick="calcStudentLoan()">Calculate Repayment</button>
  </div>
  <div class="fsc-results" id="sl-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Monthly Payment</div><div class="fsc-result-value" id="sl-monthly">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Repayment</div><div class="fsc-result-value" id="sl-total">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Interest</div><div class="fsc-result-value" id="sl-interest">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Interest During Grace</div><div class="fsc-result-value" id="sl-grace-int">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Payoff Date</div><div class="fsc-result-value" id="sl-payoff">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Balance After Grace</div><div class="fsc-result-value" id="sl-after-grace">—</div></div>
  </div>
</div>
</div>
<script>
function slPlanChange(){
  var plan=document.getElementById('sl-plan').value;
  document.getElementById('sl-custom-wrap').style.display=plan==='custom'?'block':'none';
}
function calcStudentLoan(){
  var P=parseFloat(document.getElementById('sl-amount').value)||0;
  var annRate=parseFloat(document.getElementById('sl-rate').value)/100;
  var rate=annRate/12;
  var plan=document.getElementById('sl-plan').value;
  var grace=parseInt(document.getElementById('sl-grace').value)||0;
  var years={standard:10,extended:25,graduated:10,custom:parseInt(document.getElementById('sl-custom').value)||15}[plan];
  var n=years*12;
  var graceInt=P*rate*grace;
  var balAfterGrace=P+graceInt;
  var M=rate===0?balAfterGrace/n:balAfterGrace*(rate*Math.pow(1+rate,n))/(Math.pow(1+rate,n)-1);
  var total=M*n;
  var interest=total-P;
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('sl-monthly').textContent=fmt(M)+'/mo';
  document.getElementById('sl-total').textContent=fmt(total);
  document.getElementById('sl-interest').textContent=fmt(interest);
  document.getElementById('sl-grace-int').textContent=fmt(graceInt);
  document.getElementById('sl-after-grace').textContent=fmt(balAfterGrace);
  var d=new Date();d.setMonth(d.getMonth()+grace+n);
  document.getElementById('sl-payoff').textContent=d.toLocaleDateString('en-US',{month:'short',year:'numeric'});
  document.getElementById('sl-results').style.display='grid';
}
</script>
<?php }

/* ─── HOME EQUITY LOAN / HELOC CALCULATOR ─── */
function fs_calc_heloc() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Home Value ($)</label><input type="number" id="he-value" value="500000" min="50000"></div>
    <div class="fsc-field"><label>Current Mortgage Balance ($)</label><input type="number" id="he-balance" value="300000" min="0"></div>
    <div class="fsc-field"><label>Max LTV Allowed (%)</label><input type="number" id="he-ltv" value="85" min="50" max="100"></div>
    <div class="fsc-field"><label>Loan Type</label>
      <select id="he-type">
        <option value="heloan">Home Equity Loan (Fixed)</option>
        <option value="heloc">HELOC (Variable)</option>
      </select>
    </div>
    <div class="fsc-field"><label>Interest Rate (%)</label><input type="number" id="he-rate" value="8.5" step="0.1" min="1"></div>
    <div class="fsc-field"><label>Loan Term (Years)</label><input type="number" id="he-term" value="15" min="1" max="30"></div>
    <button class="fsc-btn" onclick="calcHeloc()">Calculate</button>
  </div>
  <div class="fsc-results" id="he-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Max Available Equity</div><div class="fsc-result-value" id="he-equity">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Monthly Payment</div><div class="fsc-result-value" id="he-monthly">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Interest</div><div class="fsc-result-value" id="he-interest">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Current LTV</div><div class="fsc-result-value" id="he-cur-ltv">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Current Home Equity</div><div class="fsc-result-value" id="he-cur-equity">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">LTV After New Loan</div><div class="fsc-result-value" id="he-new-ltv">—</div></div>
  </div>
</div>
</div>
<script>
function calcHeloc(){
  var val=parseFloat(document.getElementById('he-value').value)||0;
  var bal=parseFloat(document.getElementById('he-balance').value)||0;
  var ltv=parseFloat(document.getElementById('he-ltv').value)/100;
  var rate=parseFloat(document.getElementById('he-rate').value)/100/12;
  var n=parseInt(document.getElementById('he-term').value)*12;
  var maxBorrow=val*ltv-bal;
  if(maxBorrow<0)maxBorrow=0;
  var curEquity=val-bal;
  var curLtv=(bal/val*100).toFixed(1);
  var M=maxBorrow>0?(rate===0?maxBorrow/n:maxBorrow*(rate*Math.pow(1+rate,n))/(Math.pow(1+rate,n)-1)):0;
  var interest=M*n-maxBorrow;
  var newLtv=((bal+maxBorrow)/val*100).toFixed(1);
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('he-equity').textContent=fmt(maxBorrow);
  document.getElementById('he-monthly').textContent=fmt(M)+'/mo';
  document.getElementById('he-interest').textContent=fmt(Math.max(interest,0));
  document.getElementById('he-cur-ltv').textContent=curLtv+'%';
  document.getElementById('he-cur-equity').textContent=fmt(curEquity);
  document.getElementById('he-new-ltv').textContent=newLtv+'%';
  document.getElementById('he-results').style.display='grid';
}
</script>
<?php }

/* ─── DEBT CONSOLIDATION CALCULATOR ─── */
function fs_calc_debt_consolidation() { ?>
<div class="fsc-wrap">
<p style="font-size:.9rem;color:var(--fsc-muted,#64748B);margin-bottom:1rem">Enter up to 5 debts, then compare with a consolidation loan.</p>
<div id="dc-debts">
  <div class="fsc-debt-row" style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:.5rem;margin-bottom:.5rem;align-items:end">
    <div><label style="font-size:.8rem;font-weight:600">Debt Name</label><input type="text" value="Credit Card 1" style="width:100%"></div>
    <div><label style="font-size:.8rem;font-weight:600">Balance ($)</label><input type="number" class="dc-bal" value="8000"></div>
    <div><label style="font-size:.8rem;font-weight:600">Rate (%)</label><input type="number" class="dc-rate" value="22.0" step="0.1"></div>
    <div><label style="font-size:.8rem;font-weight:600">Min Pay ($)</label><input type="number" class="dc-min" value="200"></div>
  </div>
  <div class="fsc-debt-row" style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:.5rem;margin-bottom:.5rem;align-items:end">
    <div><input type="text" value="Credit Card 2" style="width:100%"></div>
    <div><input type="number" class="dc-bal" value="5500"></div>
    <div><input type="number" class="dc-rate" value="19.99" step="0.1"></div>
    <div><input type="number" class="dc-min" value="110"></div>
  </div>
  <div class="fsc-debt-row" style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:.5rem;margin-bottom:.5rem;align-items:end">
    <div><input type="text" value="Medical Bill" style="width:100%"></div>
    <div><input type="number" class="dc-bal" value="3000"></div>
    <div><input type="number" class="dc-rate" value="0" step="0.1"></div>
    <div><input type="number" class="dc-min" value="100"></div>
  </div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin:1rem 0">
  <div class="fsc-field"><label>Consolidation Rate (%)</label><input type="number" id="dc-new-rate" value="9.5" step="0.1"></div>
  <div class="fsc-field"><label>Consolidation Term (yrs)</label><input type="number" id="dc-new-term" value="5" min="1" max="10"></div>
  <div class="fsc-field"><label>Origination Fee (%)</label><input type="number" id="dc-fee" value="1.0" step="0.1"></div>
</div>
<button class="fsc-btn" onclick="calcDebtConsol()">Calculate Savings</button>
<div class="fsc-results" id="dc-results" style="display:none;margin-top:1.5rem">
  <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">New Monthly Payment</div><div class="fsc-result-value" id="dc-new-pay">—</div></div>
  <div class="fsc-result-card"><div class="fsc-result-label">Old Monthly Minimums</div><div class="fsc-result-value" id="dc-old-pay">—</div></div>
  <div class="fsc-result-card fsc-result-card--gold"><div class="fsc-result-label">Monthly Savings</div><div class="fsc-result-value" id="dc-monthly-save">—</div></div>
  <div class="fsc-result-card"><div class="fsc-result-label">Total Debt</div><div class="fsc-result-value" id="dc-total-debt">—</div></div>
  <div class="fsc-result-card"><div class="fsc-result-label">Interest Savings</div><div class="fsc-result-value" id="dc-int-save">—</div></div>
  <div class="fsc-result-card"><div class="fsc-result-label">Payoff Sooner By</div><div class="fsc-result-value" id="dc-time-save">—</div></div>
</div>
</div>
<script>
function calcDebtConsol(){
  var bals=document.querySelectorAll('.dc-bal'),rates=document.querySelectorAll('.dc-rate'),mins=document.querySelectorAll('.dc-min');
  var totalDebt=0,totalMin=0;
  bals.forEach(function(b){totalDebt+=parseFloat(b.value)||0;});
  mins.forEach(function(m){totalMin+=parseFloat(m.value)||0;});
  var newRate=parseFloat(document.getElementById('dc-new-rate').value)/100/12;
  var n=parseInt(document.getElementById('dc-new-term').value)*12;
  var fee=(parseFloat(document.getElementById('dc-fee').value)/100)*totalDebt;
  var P=totalDebt+fee;
  var M=newRate===0?P/n:P*(newRate*Math.pow(1+newRate,n))/(Math.pow(1+newRate,n)-1);
  var newInt=M*n-P;
  var oldInt=totalDebt*0.20; // rough estimate
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('dc-new-pay').textContent=fmt(M)+'/mo';
  document.getElementById('dc-old-pay').textContent=fmt(totalMin)+'/mo';
  document.getElementById('dc-monthly-save').textContent=fmt(Math.max(totalMin-M,0))+'/mo';
  document.getElementById('dc-total-debt').textContent=fmt(totalDebt);
  document.getElementById('dc-int-save').textContent=fmt(Math.max(oldInt-newInt,0));
  document.getElementById('dc-time-save').textContent=Math.max(0,(Math.ceil(totalDebt/totalMin*1.2)-n))+' months';
  document.getElementById('dc-results').style.display='grid';
}
</script>
<?php }

/* ─── FHA LOAN CALCULATOR ─── */
function fs_calc_fha_loan() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Home Purchase Price ($)</label><input type="number" id="fha-price" value="350000" min="50000"></div>
    <div class="fsc-field"><label>Down Payment (%)</label>
      <select id="fha-down" onchange="fhaUpdate()">
        <option value="3.5" selected>3.5% (Minimum FHA)</option>
        <option value="5">5%</option>
        <option value="10">10%</option>
        <option value="20">20% (No MIP)</option>
      </select>
    </div>
    <div class="fsc-field"><label>Interest Rate (%)</label><input type="number" id="fha-rate" value="6.85" step="0.1" min="1"></div>
    <div class="fsc-field"><label>Loan Term</label>
      <select id="fha-term"><option value="30" selected>30 Years</option><option value="15">15 Years</option></select>
    </div>
    <div class="fsc-field"><label>Credit Score Range</label>
      <select id="fha-credit">
        <option value="580">580–619 (Min FHA)</option>
        <option value="620" selected>620–679</option>
        <option value="680">680–719</option>
        <option value="720">720+</option>
      </select>
    </div>
    <button class="fsc-btn" onclick="calcFha()">Calculate FHA Payment</button>
  </div>
  <div class="fsc-results" id="fha-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Total Monthly Payment</div><div class="fsc-result-value" id="fha-total">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Principal &amp; Interest</div><div class="fsc-result-value" id="fha-pi">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Upfront MIP (1.75%)</div><div class="fsc-result-value" id="fha-ufmip">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Annual MIP / Month</div><div class="fsc-result-value" id="fha-amip">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Down Payment</div><div class="fsc-result-value" id="fha-dp">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Loan Amount (+ UFMIP)</div><div class="fsc-result-value" id="fha-loan">—</div></div>
  </div>
</div>
<div id="fha-note" style="display:none;margin-top:1.5rem;padding:1rem;background:#FFF8E7;border-left:4px solid #F59E0B;border-radius:8px;font-size:.88rem;color:#92400E">
  <strong>&#128161; FHA MIP Note:</strong> With &lt;10% down, annual MIP lasts the life of the loan. With 10%+ down, MIP cancels after 11 years. At 20%+ down, no MIP is required.
</div>
</div>
<script>
function calcFha(){
  var price=parseFloat(document.getElementById('fha-price').value)||0;
  var downPct=parseFloat(document.getElementById('fha-down').value)/100;
  var rate=parseFloat(document.getElementById('fha-rate').value)/100/12;
  var n=parseInt(document.getElementById('fha-term').value)*12;
  var down=price*downPct;
  var baseLoan=price-down;
  var ufmip=baseLoan*0.0175;
  var totalLoan=baseLoan+ufmip;
  var mipRate=downPct>=0.2?0:(downPct>=0.1?0.005:0.0055);
  var mipMonthly=baseLoan*mipRate/12;
  var M=rate===0?totalLoan/n:totalLoan*(rate*Math.pow(1+rate,n))/(Math.pow(1+rate,n)-1);
  var totalPay=M+mipMonthly;
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('fha-total').textContent=fmt(totalPay)+'/mo';
  document.getElementById('fha-pi').textContent=fmt(M)+'/mo';
  document.getElementById('fha-ufmip').textContent=fmt(ufmip)+' (rolled in)';
  document.getElementById('fha-amip').textContent=fmt(mipMonthly)+'/mo';
  document.getElementById('fha-dp').textContent=fmt(down)+' ('+( downPct*100)+'%)';
  document.getElementById('fha-loan').textContent=fmt(totalLoan);
  document.getElementById('fha-results').style.display='grid';
  document.getElementById('fha-note').style.display='block';
}
function fhaUpdate(){}
</script>
<?php }

/* ─── BALLOON LOAN CALCULATOR ─── */
function fs_calc_balloon_loan() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Loan Amount ($)</label><input type="number" id="bl-amount" value="300000" min="1000"></div>
    <div class="fsc-field"><label>Annual Interest Rate (%)</label><input type="number" id="bl-rate" value="6.5" step="0.1" min="0.1"></div>
    <div class="fsc-field"><label>Amortization Period (Years)</label><input type="number" id="bl-amort" value="30" min="5" max="30"></div>
    <div class="fsc-field"><label>Balloon Term (Years)</label>
      <select id="bl-balloon">
        <option value="3">3 Years</option>
        <option value="5" selected>5 Years</option>
        <option value="7">7 Years</option>
        <option value="10">10 Years</option>
      </select>
    </div>
    <button class="fsc-btn" onclick="calcBalloon()">Calculate</button>
  </div>
  <div class="fsc-results" id="bl-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Monthly Payment</div><div class="fsc-result-value" id="bl-monthly">—</div></div>
    <div class="fsc-result-card fsc-result-card--secondary"><div class="fsc-result-label">Balloon Payment Due</div><div class="fsc-result-value" id="bl-balloon-pay">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Paid Before Balloon</div><div class="fsc-result-value" id="bl-paid-before">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Interest Paid Before Balloon</div><div class="fsc-result-value" id="bl-int-before">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Principal Paid Before Balloon</div><div class="fsc-result-value" id="bl-prin-before">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Balloon Due Date</div><div class="fsc-result-value" id="bl-due-date">—</div></div>
  </div>
</div>
</div>
<script>
function calcBalloon(){
  var P=parseFloat(document.getElementById('bl-amount').value)||0;
  var rate=parseFloat(document.getElementById('bl-rate').value)/100/12;
  var amortN=parseInt(document.getElementById('bl-amort').value)*12;
  var balloonN=parseInt(document.getElementById('bl-balloon').value)*12;
  var M=rate===0?P/amortN:P*(rate*Math.pow(1+rate,amortN))/(Math.pow(1+rate,amortN)-1);
  var bal=P,intPaid=0,prinPaid=0;
  for(var i=0;i<balloonN;i++){
    var int=bal*rate;
    var prin=M-int;
    intPaid+=int;prinPaid+=prin;
    bal-=prin;
  }
  var totalBefore=M*balloonN;
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('bl-monthly').textContent=fmt(M)+'/mo';
  document.getElementById('bl-balloon-pay').textContent=fmt(bal)+' (lump sum)';
  document.getElementById('bl-paid-before').textContent=fmt(totalBefore);
  document.getElementById('bl-int-before').textContent=fmt(intPaid);
  document.getElementById('bl-prin-before').textContent=fmt(prinPaid);
  var d=new Date();d.setMonth(d.getMonth()+balloonN);
  document.getElementById('bl-due-date').textContent=d.toLocaleDateString('en-US',{month:'long',year:'numeric'});
  document.getElementById('bl-results').style.display='grid';
}
</script>
<?php }

/* ─── INTEREST ONLY CALCULATOR ─── */
function fs_calc_interest_only() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Loan Amount ($)</label><input type="number" id="io-amount" value="400000" min="10000"></div>
    <div class="fsc-field"><label>Annual Interest Rate (%)</label><input type="number" id="io-rate" value="7.0" step="0.1" min="0.1"></div>
    <div class="fsc-field"><label>Interest-Only Period (Years)</label><input type="number" id="io-io-period" value="10" min="1" max="15"></div>
    <div class="fsc-field"><label>Total Loan Term (Years)</label><input type="number" id="io-total" value="30" min="10" max="40"></div>
    <button class="fsc-btn" onclick="calcInterestOnly()">Compare Payments</button>
  </div>
  <div class="fsc-results" id="io-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Interest-Only Payment</div><div class="fsc-result-value" id="io-io-pay">—</div></div>
    <div class="fsc-result-card fsc-result-card--secondary"><div class="fsc-result-label">P&amp;I Payment (After IO)</div><div class="fsc-result-value" id="io-pi-pay">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Traditional P&amp;I (Full Term)</div><div class="fsc-result-value" id="io-trad-pay">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">IO Period Total Interest</div><div class="fsc-result-value" id="io-io-int">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Interest (IO Loan)</div><div class="fsc-result-value" id="io-total-int">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Extra Cost vs. Traditional</div><div class="fsc-result-value" id="io-extra">—</div></div>
  </div>
</div>
</div>
<script>
function calcInterestOnly(){
  var P=parseFloat(document.getElementById('io-amount').value)||0;
  var rate=parseFloat(document.getElementById('io-rate').value)/100/12;
  var ioPeriod=parseInt(document.getElementById('io-io-period').value)*12;
  var totalN=parseInt(document.getElementById('io-total').value)*12;
  var piN=totalN-ioPeriod;
  var ioPayment=P*rate;
  var piPayment=rate===0?P/piN:P*(rate*Math.pow(1+rate,piN))/(Math.pow(1+rate,piN)-1);
  var tradPayment=rate===0?P/totalN:P*(rate*Math.pow(1+rate,totalN))/(Math.pow(1+rate,totalN)-1);
  var ioInt=ioPayment*ioPeriod;
  var piInt=piPayment*piN-P;
  var totalInt=ioInt+piInt;
  var tradInt=tradPayment*totalN-P;
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('io-io-pay').textContent=fmt(ioPayment)+'/mo';
  document.getElementById('io-pi-pay').textContent=fmt(piPayment)+'/mo';
  document.getElementById('io-trad-pay').textContent=fmt(tradPayment)+'/mo';
  document.getElementById('io-io-int').textContent=fmt(ioInt);
  document.getElementById('io-total-int').textContent=fmt(totalInt);
  document.getElementById('io-extra').textContent=fmt(Math.max(totalInt-tradInt,0))+' more';
  document.getElementById('io-results').style.display='grid';
}
</script>
<?php }

/* ─── LOAN PAYOFF CALCULATOR ─── */
function fs_calc_loan_payoff() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Current Loan Balance ($)</label><input type="number" id="lpo-balance" value="185000" min="1000"></div>
    <div class="fsc-field"><label>Annual Interest Rate (%)</label><input type="number" id="lpo-rate" value="6.75" step="0.1" min="0.1"></div>
    <div class="fsc-field"><label>Remaining Term (Months)</label><input type="number" id="lpo-term" value="300" min="6"></div>
    <div class="fsc-field"><label>Extra Monthly Payment ($)</label><input type="number" id="lpo-extra" value="200" min="0"></div>
    <div class="fsc-field"><label>Lump Sum Payment ($)</label><input type="number" id="lpo-lump" value="0" min="0"></div>
    <button class="fsc-btn" onclick="calcLoanPayoff()">Calculate Payoff</button>
  </div>
  <div class="fsc-results" id="lpo-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">New Payoff Date</div><div class="fsc-result-value" id="lpo-new-date">—</div></div>
    <div class="fsc-result-card fsc-result-card--gold"><div class="fsc-result-label">Months Saved</div><div class="fsc-result-value" id="lpo-months-saved">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Original Payoff Date</div><div class="fsc-result-value" id="lpo-orig-date">—</div></div>
    <div class="fsc-result-card fsc-result-card--gold"><div class="fsc-result-label">Interest Saved</div><div class="fsc-result-value" id="lpo-int-saved">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">New Monthly Payment</div><div class="fsc-result-value" id="lpo-new-pay">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Original Monthly Payment</div><div class="fsc-result-value" id="lpo-orig-pay">—</div></div>
  </div>
</div>
</div>
<script>
function calcLoanPayoff(){
  var bal=parseFloat(document.getElementById('lpo-balance').value)||0;
  var rate=parseFloat(document.getElementById('lpo-rate').value)/100/12;
  var n=parseInt(document.getElementById('lpo-term').value);
  var extra=parseFloat(document.getElementById('lpo-extra').value)||0;
  var lump=parseFloat(document.getElementById('lpo-lump').value)||0;
  var origPay=rate===0?bal/n:bal*(rate*Math.pow(1+rate,n))/(Math.pow(1+rate,n)-1);
  var origInt=origPay*n-bal;
  // With extra payments
  var newBal=bal-lump;
  var newPay=origPay+extra;
  var months2=0,newInt=0;
  while(newBal>0&&months2<n){
    months2++;
    var i=newBal*rate;
    newInt+=i;
    newBal-=Math.min(newPay-i,newBal);
  }
  var monthsSaved=n-months2;
  var intSaved=origInt-newInt;
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  var addMonths=function(m){var d=new Date();d.setMonth(d.getMonth()+m);return d.toLocaleDateString('en-US',{month:'short',year:'numeric'});};
  document.getElementById('lpo-new-date').textContent=addMonths(months2);
  document.getElementById('lpo-months-saved').textContent=monthsSaved+' months';
  document.getElementById('lpo-orig-date').textContent=addMonths(n);
  document.getElementById('lpo-int-saved').textContent=fmt(Math.max(intSaved,0));
  document.getElementById('lpo-new-pay').textContent=fmt(newPay)+'/mo';
  document.getElementById('lpo-orig-pay').textContent=fmt(origPay)+'/mo';
  document.getElementById('lpo-results').style.display='grid';
}
</script>
<?php }

/* ─── COMMERCIAL LOAN CALCULATOR ─── */
function fs_calc_commercial_loan() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Loan Amount ($)</label><input type="number" id="cm-amount" value="1000000" min="50000"></div>
    <div class="fsc-field"><label>Annual Interest Rate (%)</label><input type="number" id="cm-rate" value="7.5" step="0.1" min="1"></div>
    <div class="fsc-field"><label>Amortization Period (Years)</label><input type="number" id="cm-amort" value="25" min="5" max="30"></div>
    <div class="fsc-field"><label>Loan Term (Balloon) Years</label><input type="number" id="cm-term" value="10" min="1" max="25"></div>
    <div class="fsc-field"><label>Annual Net Operating Income ($)</label><input type="number" id="cm-noi" value="95000" min="0"></div>
    <div class="fsc-field"><label>Origination Points (%)</label><input type="number" id="cm-points" value="1.0" step="0.5" min="0"></div>
    <button class="fsc-btn" onclick="calcCommercial()">Calculate</button>
  </div>
  <div class="fsc-results" id="cm-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Monthly Payment</div><div class="fsc-result-value" id="cm-monthly">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Balloon Balance Due</div><div class="fsc-result-value" id="cm-balloon">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">DSCR</div><div class="fsc-result-value" id="cm-dscr">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Annual Debt Service</div><div class="fsc-result-value" id="cm-annual">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Origination Fees</div><div class="fsc-result-value" id="cm-fees">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Loan Qualifies (DSCR≥1.25)</div><div class="fsc-result-value" id="cm-qualify">—</div></div>
  </div>
</div>
<div id="cm-dscr-note" style="display:none;margin-top:1rem;padding:1rem;border-left:4px solid #3B82F6;background:#EFF6FF;border-radius:8px;font-size:.88rem;color:#1E40AF">
  <strong>&#128202; DSCR Guide:</strong> Most commercial lenders require DSCR ≥ 1.25. A DSCR of 1.25 means the property earns 25% more than the debt payment. Below 1.0 means the property cannot cover its debt.
</div>
</div>
<script>
function calcCommercial(){
  var P=parseFloat(document.getElementById('cm-amount').value)||0;
  var rate=parseFloat(document.getElementById('cm-rate').value)/100/12;
  var amortN=parseInt(document.getElementById('cm-amort').value)*12;
  var termN=parseInt(document.getElementById('cm-term').value)*12;
  var noi=parseFloat(document.getElementById('cm-noi').value)||0;
  var points=(parseFloat(document.getElementById('cm-points').value)/100)*P;
  var M=rate===0?P/amortN:P*(rate*Math.pow(1+rate,amortN))/(Math.pow(1+rate,amortN)-1);
  var bal=P;
  for(var i=0;i<termN;i++){var int=bal*rate;bal-=(M-int);}
  var annualDebt=M*12;
  var dscr=annualDebt>0?(noi/annualDebt).toFixed(2):0;
  var qualifies=parseFloat(dscr)>=1.25;
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('cm-monthly').textContent=fmt(M)+'/mo';
  document.getElementById('cm-balloon').textContent=fmt(Math.max(bal,0));
  document.getElementById('cm-dscr').textContent=dscr+'x';
  document.getElementById('cm-annual').textContent=fmt(annualDebt)+'/yr';
  document.getElementById('cm-fees').textContent=fmt(points);
  document.getElementById('cm-qualify').textContent=qualifies?'&#9989; Yes ('+dscr+'x)':'&#10060; No ('+dscr+'x)';
  document.getElementById('cm-results').style.display='grid';
  document.getElementById('cm-dscr-note').style.display='block';
}
</script>
<?php }

/* ─── BRIDGE LOAN CALCULATOR ─── */
function fs_calc_bridge_loan() { ?>
<div class="fsc-wrap">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsc-field"><label>Bridge Loan Amount ($)</label><input type="number" id="br-amount" value="250000" min="10000"></div>
    <div class="fsc-field"><label>Annual Interest Rate (%)</label><input type="number" id="br-rate" value="9.5" step="0.1" min="1"></div>
    <div class="fsc-field"><label>Loan Duration (Months)</label>
      <select id="br-term">
        <option value="3">3 Months</option>
        <option value="6" selected>6 Months</option>
        <option value="9">9 Months</option>
        <option value="12">12 Months</option>
        <option value="18">18 Months</option>
        <option value="24">24 Months</option>
      </select>
    </div>
    <div class="fsc-field"><label>Payment Type</label>
      <select id="br-paytype">
        <option value="io" selected>Interest Only Monthly</option>
        <option value="balloon">All Due at Maturity</option>
      </select>
    </div>
    <div class="fsc-field"><label>Origination Fee (%)</label><input type="number" id="br-fee" value="2.0" step="0.5" min="0"></div>
    <div class="fsc-field"><label>Exit Fee (%)</label><input type="number" id="br-exit" value="1.0" step="0.5" min="0"></div>
    <button class="fsc-btn" onclick="calcBridge()">Calculate Bridge Costs</button>
  </div>
  <div class="fsc-results" id="br-results" style="display:none">
    <div class="fsc-result-card fsc-result-card--primary"><div class="fsc-result-label">Monthly Interest Payment</div><div class="fsc-result-value" id="br-monthly">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Total Interest Cost</div><div class="fsc-result-value" id="br-int-total">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Origination Fee</div><div class="fsc-result-value" id="br-orig-fee">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Exit Fee</div><div class="fsc-result-value" id="br-exit-fee">—</div></div>
    <div class="fsc-result-card fsc-result-card--secondary"><div class="fsc-result-label">Total Cost of Loan</div><div class="fsc-result-value" id="br-total-cost">—</div></div>
    <div class="fsc-result-card"><div class="fsc-result-label">Effective Annual Rate</div><div class="fsc-result-value" id="br-ear">—</div></div>
  </div>
</div>
</div>
<script>
function calcBridge(){
  var P=parseFloat(document.getElementById('br-amount').value)||0;
  var rate=parseFloat(document.getElementById('br-rate').value)/100/12;
  var n=parseInt(document.getElementById('br-term').value);
  var origFee=(parseFloat(document.getElementById('br-fee').value)/100)*P;
  var exitFee=(parseFloat(document.getElementById('br-exit').value)/100)*P;
  var monthly=P*rate;
  var totalInt=monthly*n;
  var totalCost=totalInt+origFee+exitFee;
  var ear=((totalCost/P)/(n/12)*100).toFixed(2);
  var fmt=function(v){return '$'+v.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,',');};
  document.getElementById('br-monthly').textContent=fmt(monthly)+'/mo';
  document.getElementById('br-int-total').textContent=fmt(totalInt);
  document.getElementById('br-orig-fee').textContent=fmt(origFee);
  document.getElementById('br-exit-fee').textContent=fmt(exitFee);
  document.getElementById('br-total-cost').textContent=fmt(totalCost);
  document.getElementById('br-ear').textContent=ear+'% effective APR';
  document.getElementById('br-results').style.display='grid';
}
</script>
<?php }
