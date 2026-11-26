<?php
/**
 * Calculators (v2), batch 2: income tax, capital gains, compound interest,
 * retirement savings, 401(k), savings goal, emergency fund, ROI, net worth,
 * 50/30/20 budget. Uses helpers from calculators-loans.php and window.FSL.
 *
 * These are dispatched by tool SLUG (see fs_render_calculator), so tools that
 * only share the old generic calculator type are not affected.
 *
 * @package financespots
 */
defined( 'ABSPATH' ) || exit;

/** 2026 federal tax tables, printed once per page as window.FSTAX. */
function fs_tax_data_script() {
    static $done = false;
    if ( $done ) return;
    $done = true; ?>
<script>
/* IRS Rev. Proc. 2025-32 (tax year 2026); Social Security wage base per SSA */
window.FSTAX={
  std:{single:16100,mfj:32200,mfs:16100,hoh:24150},
  br:{
    single:[[12400,.10],[50400,.12],[105700,.22],[201775,.24],[256225,.32],[640600,.35],[1e15,.37]],
    mfj:[[24800,.10],[100800,.12],[211400,.22],[403550,.24],[512450,.32],[768700,.35],[1e15,.37]],
    mfs:[[12400,.10],[50400,.12],[105700,.22],[201775,.24],[256225,.32],[384350,.35],[1e15,.37]],
    hoh:[[17700,.10],[67450,.12],[105700,.22],[201750,.24],[256200,.32],[640600,.35],[1e15,.37]]
  },
  cg:{single:[49450,545500],mfj:[98900,613700],mfs:[49450,306850],hoh:[66200,579600]},
  niit:{single:200000,mfj:250000,mfs:125000,hoh:200000},
  ssBase:184500,
  tax:function(ti,status){var b=this.br[status],t=0,p=0;for(var i=0;i<b.length;i++){var a=Math.min(ti,b[i][0])-p;if(a<=0)break;t+=a*b[i][1];p=b[i][0];}return t;},
  marginal:function(ti,status){var b=this.br[status];for(var i=0;i<b.length;i++){if(ti<=b[i][0])return b[i][1];}return .37;}
};
</script>
<?php }

/* ─────────────────────────────────────────────
   1. INCOME TAX CALCULATOR (2026 brackets)
───────────────────────────────────────────── */
function fs_calc2_income_tax() { fs_tax_data_script(); ?>
<div class="fsc-wrap" id="fs-tx">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'tx-income', 'Gross annual income ($)', 85000, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <?php fsl_select( 'tx-status', 'Filing status', [ 'single' => 'Single', 'mfj' => 'Married filing jointly', 'hoh' => 'Head of household', 'mfs' => 'Married filing separately' ], 'single' ); ?>
    <?php fsl_field( 'tx-pretax', 'Pre-tax deductions: 401(k), HSA, etc. ($)', 6000, [ 'min' => 0, 'step' => 500, 'hint' => 'Reduces federal and state taxable income, not Social Security or Medicare wages (except HSA through payroll).' ] ); ?>
    <?php fsl_field( 'tx-itemized', 'Itemized deductions ($, optional)', 0, [ 'min' => 0, 'step' => 500, 'hint' => 'We use whichever is larger: your itemized total or the standard deduction.' ] ); ?>
    <?php fsl_field( 'tx-state', 'State income tax rate (%)', 0, [ 'min' => 0, 'max' => 15, 'step' => 0.1, 'hint' => 'Use your state’s effective rate. Alaska, Florida, Nevada, New Hampshire, South Dakota, Tennessee, Texas, Washington and Wyoming have no state wage income tax.' ] ); ?>
    <?php fsl_check( 'tx-fica', 'Include Social Security and Medicare (FICA) as an employee', true ); ?>
    <button type="button" class="fsc-btn" id="tx-go">Calculate tax</button>
    <p class="fsl-error" id="tx-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="tx-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'tx-take', 'Take-home pay per year', 'primary' );
      fsl_card( 'tx-month', 'Take-home per month' );
      fsl_card( 'tx-fed', 'Federal income tax' );
      fsl_card( 'tx-ti', 'Taxable income' );
      fsl_card( 'tx-marg', 'Marginal federal bracket' );
      fsl_card( 'tx-eff', 'Effective federal rate' );
      fsl_card( 'tx-fica-v', 'Social Security + Medicare' );
      fsl_card( 'tx-st', 'State tax' );
      fsl_card( 'tx-total', 'Total taxes', 'gold' );
      fsl_card( 'tx-all', 'Total effective rate' );
      ?>
    </div>
    <p class="fsl-note" id="tx-note"></p>
  </div>
</div>
<div id="tx-brk" style="display:none;margin-top:1.5rem">
  <h3 class="fsl-h3">Your federal tax by bracket (tax year 2026)</h3>
  <div class="fsc-table-wrap" style="padding:0"><table class="fsc-table fsl-cmp"><thead><tr><th>Rate</th><th>Income in bracket</th><th>Tax</th></tr></thead><tbody id="tx-brk-body"></tbody></table></div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money, T=window.FSTAX;
  function calc(){
    var gross=$('tx-income'), st=FSL.el('tx-status').value, pre=Math.min($('tx-pretax'),gross), item=$('tx-itemized'), sr=$('tx-state')/100;
    if(!FSL.valid(gross>0,'tx-err','Enter your annual income.'))return;
    var ded=Math.max(T.std[st],item), agi=gross-pre, ti=Math.max(agi-ded,0);
    var fed=T.tax(ti,st), marg=T.marginal(ti,st);
    var fica=0;
    if(FSL.el('tx-fica').checked){
      var ssW=gross, medW=gross, add={single:200000,mfj:250000,mfs:125000,hoh:200000}[st];
      fica=Math.min(ssW,T.ssBase)*0.062+medW*0.0145+Math.max(medW-add,0)*0.009;
    }
    var stateTax=Math.max(agi-0,0)*sr;
    var total=fed+fica+stateTax, take=gross-pre-total;
    FSL.set('tx-take',M(take)); FSL.set('tx-month',M(take/12)+'/mo');
    FSL.set('tx-fed',M(fed)); FSL.set('tx-ti',M(ti)); FSL.set('tx-marg',(marg*100)+'%');
    FSL.set('tx-eff',FSL.pct(fed/gross*100,1)); FSL.set('tx-fica-v',M(fica)); FSL.set('tx-st',M(stateTax));
    FSL.set('tx-total',M(total)); FSL.set('tx-all',FSL.pct(total/gross*100,1));
    FSL.show('tx-results','grid'); FSL.show('tx-brk');
    var body='',prev=0,b=T.br[st];
    for(var i=0;i<b.length;i++){var a=Math.min(ti,b[i][0])-prev; if(a<=0&&i>0&&ti<=prev)break; if(a>0)body+='<tr><td><strong>'+(b[i][1]*100)+'%</strong></td><td>'+M(a)+'</td><td>'+M(a*b[i][1])+'</td></tr>'; prev=b[i][0];}
    FSL.el('tx-brk-body').innerHTML=body||'<tr><td colspan="3">No taxable income after deductions.</td></tr>';
    FSL.set('tx-note','You are using the '+(item>T.std[st]?'itemized deduction of '+M(item):'standard deduction of '+M(T.std[st]))+'. Your top dollars are taxed at '+(marg*100)+'%, but your overall federal rate is '+FSL.pct(fed/gross*100,1)+'. This estimate leaves out tax credits, additional standard deductions for age 65+ or blindness, and the newer deductions for tips, overtime and seniors.');
  }
  FSL.el('tx-go').addEventListener('click',calc); FSL.bind('fs-tx',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   2. CAPITAL GAINS TAX CALCULATOR (2026)
───────────────────────────────────────────── */
function fs_calc2_capital_gains() { fs_tax_data_script(); ?>
<div class="fsc-wrap" id="fs-cg">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'cg-basis', 'Purchase price / cost basis ($)', 20000, [ 'min' => 0, 'step' => 500, 'hint' => 'Include purchase fees and improvements.' ] ); ?>
    <?php fsl_field( 'cg-sale', 'Sale price ($)', 35000, [ 'min' => 0, 'step' => 500 ] ); ?>
    <?php fsl_field( 'cg-costs', 'Selling costs ($)', 0, [ 'min' => 0, 'step' => 100, 'hint' => 'Commissions and fees reduce your gain.' ] ); ?>
    <?php fsl_select( 'cg-hold', 'How long did you hold it?', [ 'long' => 'More than one year (long-term)', 'short' => 'One year or less (short-term)' ], 'long' ); ?>
    <?php fsl_select( 'cg-status', 'Filing status', [ 'single' => 'Single', 'mfj' => 'Married filing jointly', 'hoh' => 'Head of household', 'mfs' => 'Married filing separately' ], 'single' ); ?>
    <?php fsl_field( 'cg-income', 'Other taxable income ($)', 70000, [ 'min' => 0, 'step' => 1000, 'hint' => 'Your taxable income before this sale, after deductions. This sets which rate your gain lands in.' ] ); ?>
    <?php fsl_field( 'cg-state', 'State tax rate on gains (%)', 0, [ 'min' => 0, 'max' => 15, 'step' => 0.1 ] ); ?>
    <?php fsl_field( 'cg-loss', 'Capital losses to offset ($, optional)', 0, [ 'min' => 0, 'step' => 100 ] ); ?>
    <button type="button" class="fsc-btn" id="cg-go">Calculate tax</button>
    <p class="fsl-error" id="cg-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="cg-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'cg-tax', 'Total tax on this sale', 'primary' );
      fsl_card( 'cg-gain', 'Taxable gain' );
      fsl_card( 'cg-fed', 'Federal capital gains tax' );
      fsl_card( 'cg-niit', 'Net investment income tax (3.8%)' );
      fsl_card( 'cg-stt', 'State tax' );
      fsl_card( 'cg-eff', 'Effective rate on the gain' );
      fsl_card( 'cg-net', 'Profit after tax', 'gold' );
      fsl_card( 'cg-bands', 'Rates applied', 'secondary' );
      ?>
    </div>
    <p class="fsl-note" id="cg-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money, T=window.FSTAX;
  function calc(){
    var basis=$('cg-basis'), sale=$('cg-sale'), costs=$('cg-costs'), loss=$('cg-loss'), st=FSL.el('cg-status').value, hold=FSL.el('cg-hold').value, inc=$('cg-income'), sr=$('cg-state')/100;
    if(!FSL.valid(sale>0,'cg-err','Enter the sale price.'))return;
    var gain=sale-costs-basis-loss, g=Math.max(gain,0), fed=0, bands='';
    if(g>0){
      if(hold==='short'){ fed=T.tax(inc+g,st)-T.tax(inc,st); bands='Ordinary rates up to '+(T.marginal(inc+g,st)*100)+'%'; }
      else{
        var z=T.cg[st][0], f=T.cg[st][1], r0=Math.max(Math.min(g,z-inc),0), rest=g-r0, topRoom=Math.max(f-Math.max(inc,z),0), r15=Math.min(rest,Math.max(f-Math.max(inc+r0,z),0)), r20=Math.max(rest-r15,0);
        fed=r15*0.15+r20*0.20; var parts=[]; if(r0>0)parts.push(M(r0)+' at 0%'); if(r15>0)parts.push(M(r15)+' at 15%'); if(r20>0)parts.push(M(r20)+' at 20%'); bands=parts.join(', ');
      }
    }
    var niit=g>0?Math.min(g,Math.max(inc+g-T.niit[st],0))*0.038:0, stt=g*sr, total=fed+niit+stt;
    FSL.set('cg-tax',M(total)); FSL.set('cg-gain',(gain<0?'Loss of ':'')+M(Math.abs(gain)));
    FSL.set('cg-fed',M(fed)); FSL.set('cg-niit',M(niit)); FSL.set('cg-stt',M(stt));
    FSL.set('cg-eff',g>0?FSL.pct(total/g*100,1):'0%'); FSL.set('cg-net',M(gain-total)); FSL.set('cg-bands',bands||'No tax due');
    FSL.show('cg-results','grid');
    var note=gain<=0?'You have a capital loss. Losses offset other gains, and up to $3,000 a year can offset ordinary income, with the rest carried forward.':(hold==='long'?'Long-term gains are taxed at 0%, 15% or 20% depending on your total taxable income. For 2026 the 0% rate applies up to '+M(T.cg[st][0])+' of taxable income and the 20% rate starts above '+M(T.cg[st][1])+'.':'Short-term gains are taxed as ordinary income. Holding the asset more than one year can lower the rate.');
    FSL.set('cg-note',note);
  }
  FSL.el('cg-go').addEventListener('click',calc); FSL.bind('fs-cg',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   3. COMPOUND INTEREST CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_compound() { ?>
<div class="fsc-wrap" id="fs-ci">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'ci-p', 'Starting amount ($)', 10000, [ 'min' => 0, 'step' => 500 ] ); ?>
    <?php fsl_field( 'ci-m', 'Monthly contribution ($)', 500, [ 'min' => 0, 'step' => 50 ] ); ?>
    <?php fsl_select( 'ci-when', 'Contributions are made', [ 'end' => 'At the end of each month', 'start' => 'At the start of each month' ], 'end' ); ?>
    <?php fsl_field( 'ci-r', 'Annual interest rate (%)', 7, [ 'min' => 0, 'max' => 50, 'step' => 0.1, 'hint' => 'The S&amp;P 500 has averaged roughly 10% a year before inflation over the long run, but returns vary every year.' ] ); ?>
    <?php fsl_field( 'ci-y', 'Years', 20, [ 'min' => 1, 'max' => 80, 'step' => 1 ] ); ?>
    <?php fsl_select( 'ci-n', 'Compounding', [ 365 => 'Daily', 12 => 'Monthly', 4 => 'Quarterly', 1 => 'Annually' ], 12 ); ?>
    <?php fsl_field( 'ci-inf', 'Inflation (%, optional)', 3, [ 'min' => 0, 'max' => 20, 'step' => 0.1 ] ); ?>
    <button type="button" class="fsc-btn" id="ci-go">Calculate growth</button>
    <p class="fsl-error" id="ci-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="ci-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'ci-fv', 'Future value', 'primary' );
      fsl_card( 'ci-contrib', 'Total you put in' );
      fsl_card( 'ci-int', 'Interest earned' );
      fsl_card( 'ci-real', 'Value in today’s dollars', 'secondary' );
      fsl_card( 'ci-share', 'Share of balance from growth' );
      fsl_card( 'ci-dbl', 'Years to double (no deposits)' );
      ?>
    </div>
    <p class="fsl-note" id="ci-note"></p>
  </div>
</div>
<div style="margin-top:1.5rem" class="fsl-chart"><canvas id="ci-chart" height="200" aria-label="Growth chart" role="img"></canvas></div>
<div id="ci-tbl" style="display:none;margin-top:1.5rem">
  <h3 class="fsl-h3">Year by year</h3>
  <div class="fsc-table-wrap fsl-sched-wrap"><table class="fsc-table fsl-cmp"><thead><tr><th>Year</th><th>Deposits to date</th><th>Interest to date</th><th>Balance</th></tr></thead><tbody id="ci-body"></tbody></table></div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var P=$('ci-p'), m=$('ci-m'), r=$('ci-r')/100, Y=Math.round($('ci-y')), n=parseInt(FSL.el('ci-n').value,10), inf=$('ci-inf')/100, start=FSL.el('ci-when').value==='start';
    if(!FSL.valid(Y>=1&&(P>0||m>0),'ci-err','Enter a starting amount or a monthly contribution, and at least one year.'))return;
    var i=Math.pow(1+r/n,n/12)-1, bal=P, dep=P, rows='', labels=['Now'], vals=[P], dvals=[P];
    for(var mo=1;mo<=Y*12;mo++){
      if(start){bal+=m;dep+=m;}
      bal*=1+i;
      if(!start){bal+=m;dep+=m;}
      if(mo%12===0){var yr=mo/12; labels.push('Yr '+yr); vals.push(Math.round(bal)); dvals.push(Math.round(dep)); rows+='<tr><td>'+yr+'</td><td>'+M(dep)+'</td><td>'+M(bal-dep)+'</td><td>'+M(bal)+'</td></tr>';}
    }
    var real=bal/Math.pow(1+inf,Y);
    FSL.set('ci-fv',M(bal)); FSL.set('ci-contrib',M(dep)); FSL.set('ci-int',M(bal-dep)); FSL.set('ci-real',M(real));
    FSL.set('ci-share',FSL.pct((bal-dep)/bal*100,1));
    FSL.set('ci-dbl',r>0?(Math.log(2)/Math.log(Math.pow(1+r/n,n))).toFixed(1)+' years':'Never at 0%');
    FSL.show('ci-results','grid'); FSL.show('ci-tbl'); FSL.el('ci-body').innerHTML=rows;
    FSL.set('ci-note','Of the '+M(bal)+', '+M(dep)+' is money you contributed and '+M(bal-dep)+' is growth. After '+(inf*100)+'% yearly inflation, that balance buys about as much as '+M(real)+' does today.');
    FSL.chart('ci-chart',{type:'line',data:{labels:labels,datasets:[{label:'Balance',data:vals,borderColor:'#00C896',backgroundColor:'#00C89633',fill:true,tension:.25,pointRadius:0},{label:'Deposits',data:dvals,borderColor:'#3B82F6',fill:false,tension:.25,pointRadius:0}]},options:{plugins:{legend:{display:true}},scales:{y:{ticks:{callback:function(v){return '$'+(v>=1e6?(v/1e6).toFixed(1)+'M':Math.round(v/1000)+'k');}}}}}});
  }
  FSL.el('ci-go').addEventListener('click',calc); FSL.bind('fs-ci',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   4. RETIREMENT SAVINGS CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_retirement() { ?>
<div class="fsc-wrap" id="fs-rs">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsl-row2">
      <?php fsl_field( 'rs-age', 'Your age', 35, [ 'min' => 18, 'max' => 80, 'step' => 1 ] ); ?>
      <?php fsl_field( 'rs-ret', 'Retire at', 65, [ 'min' => 40, 'max' => 80, 'step' => 1 ] ); ?>
    </div>
    <?php fsl_field( 'rs-saved', 'Retirement savings today ($)', 60000, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <?php fsl_field( 'rs-monthly', 'You save per month ($)', 800, [ 'min' => 0, 'step' => 50 ] ); ?>
    <?php fsl_field( 'rs-return', 'Expected annual return (%)', 6.5, [ 'min' => 0, 'max' => 15, 'step' => 0.1 ] ); ?>
    <?php fsl_field( 'rs-inf', 'Inflation (%)', 3, [ 'min' => 0, 'max' => 10, 'step' => 0.1 ] ); ?>
    <?php fsl_field( 'rs-need', 'Yearly income you want in retirement, in today’s dollars ($)', 70000, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <?php fsl_field( 'rs-ss', 'Expected Social Security per month, today’s dollars ($)', 2000, [ 'min' => 0, 'step' => 50, 'hint' => 'The average retired worker receives about $2,032 a month in 2026 (SSA). Check yours at ssa.gov.' ] ); ?>
    <?php fsl_field( 'rs-wd', 'Safe withdrawal rate (%)', 4, [ 'min' => 2, 'max' => 7, 'step' => 0.1, 'hint' => 'The “4% rule” is a common starting point.' ] ); ?>
    <button type="button" class="fsc-btn" id="rs-go">Calculate</button>
    <p class="fsl-error" id="rs-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="rs-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'rs-status', 'Are you on track?', 'primary' );
      fsl_card( 'rs-proj', 'Projected savings at retirement' );
      fsl_card( 'rs-proj-real', 'Same amount in today’s dollars' );
      fsl_card( 'rs-target', 'Savings you need (in future dollars)' );
      fsl_card( 'rs-gap', 'Shortfall or surplus' );
      fsl_card( 'rs-extra', 'Extra to save per month to reach it', 'gold' );
      fsl_card( 'rs-inc', 'Income your savings can support', 'secondary' );
      fsl_card( 'rs-years', 'Years until retirement' );
      ?>
    </div>
    <p class="fsl-note" id="rs-note"></p>
  </div>
</div>
<div style="margin-top:1.5rem" class="fsl-chart"><canvas id="rs-chart" height="200" aria-label="Savings projection chart" role="img"></canvas></div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var age=$('rs-age'), ret=$('rs-ret'), S=$('rs-saved'), mo=$('rs-monthly'), r=$('rs-return')/100, inf=$('rs-inf')/100, need=$('rs-need'), ss=$('rs-ss')*12, wd=$('rs-wd')/100;
    if(!FSL.valid(ret>age,'rs-err','Retirement age must be higher than your current age.'))return;
    var yrs=ret-age, n=yrs*12, i=Math.pow(1+r,1/12)-1;
    var growth=Math.pow(1+i,n), fvAnn=i===0?n:(growth-1)/i;
    var proj=S*growth+mo*fvAnn, infl=Math.pow(1+inf,yrs), projReal=proj/infl;
    var needFut=Math.max(need-ss,0)*infl, target=needFut/wd, gap=proj-target;
    var extra=gap<0?(-gap)/fvAnn:0, support=proj*wd/infl+ss;
    FSL.set('rs-status',gap>=0?'Yes: ahead by '+M(gap):'Not yet: short by '+M(-gap));
    FSL.set('rs-proj',M(proj)); FSL.set('rs-proj-real',M(projReal)); FSL.set('rs-target',M(target));
    FSL.set('rs-gap',(gap>=0?'+':'-')+M(Math.abs(gap))); FSL.set('rs-extra',gap<0?M(extra)+'/mo':'Nothing extra needed');
    FSL.set('rs-inc',M(support)+'/yr (today’s $)'); FSL.set('rs-years',yrs+' years');
    FSL.show('rs-results','grid');
    FSL.set('rs-note','You want '+M(need)+' a year in today’s dollars. Social Security covers about '+M(ss)+' of that, so your savings must supply '+M(Math.max(need-ss,0))+' ('+M(needFut/1)+' a year by the time you retire, after '+(inf*100)+'% inflation). At a '+(wd*100)+'% withdrawal rate that requires '+M(target)+'.');
    var labels=[],vals=[],b=S; for(var y=0;y<=yrs;y++){ labels.push(age+y); vals.push(Math.round(b)); b=b*Math.pow(1+i,12)+mo*(i===0?12:(Math.pow(1+i,12)-1)/i); }
    FSL.chart('rs-chart',{type:'line',data:{labels:labels,datasets:[{label:'Projected balance',data:vals,borderColor:'#00C896',backgroundColor:'#00C89633',fill:true,tension:.25,pointRadius:0},{label:'Savings needed',data:vals.map(function(){return Math.round(target);}),borderColor:'#F59E0B',borderDash:[6,4],fill:false,pointRadius:0}]},options:{plugins:{legend:{display:true}},scales:{y:{ticks:{callback:function(v){return '$'+(v>=1e6?(v/1e6).toFixed(1)+'M':Math.round(v/1000)+'k');}}}}}});
  }
  FSL.el('rs-go').addEventListener('click',calc); FSL.bind('fs-rs',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   5. 401(k) CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_401k() { ?>
<div class="fsc-wrap" id="fs-k4">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsl-row2">
      <?php fsl_field( 'k4-age', 'Your age', 35, [ 'min' => 18, 'max' => 75, 'step' => 1 ] ); ?>
      <?php fsl_field( 'k4-ret', 'Retire at', 65, [ 'min' => 40, 'max' => 80, 'step' => 1 ] ); ?>
    </div>
    <?php fsl_field( 'k4-sal', 'Annual salary ($)', 90000, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <?php fsl_field( 'k4-bal', 'Current 401(k) balance ($)', 40000, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <?php fsl_field( 'k4-pct', 'Your contribution (% of salary)', 8, [ 'min' => 0, 'max' => 100, 'step' => 0.5 ] ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'k4-mpct', 'Employer matches (%)', 50, [ 'min' => 0, 'max' => 200, 'step' => 5, 'hint' => 'Of each dollar you put in' ] ); ?>
      <?php fsl_field( 'k4-mcap', 'up to (% of salary)', 6, [ 'min' => 0, 'max' => 100, 'step' => 0.5 ] ); ?>
    </div>
    <?php fsl_field( 'k4-raise', 'Annual salary raise (%)', 3, [ 'min' => 0, 'max' => 15, 'step' => 0.1 ] ); ?>
    <?php fsl_field( 'k4-ret-r', 'Expected annual return (%)', 7, [ 'min' => 0, 'max' => 15, 'step' => 0.1 ] ); ?>
    <?php fsl_field( 'k4-inf', 'Inflation (%)', 3, [ 'min' => 0, 'max' => 10, 'step' => 0.1 ] ); ?>
    <button type="button" class="fsc-btn" id="k4-go">Calculate</button>
    <p class="fsl-error" id="k4-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="k4-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'k4-bal-ret', 'Balance at retirement', 'primary' );
      fsl_card( 'k4-real', 'In today’s dollars' );
      fsl_card( 'k4-you', 'You contribute this year' );
      fsl_card( 'k4-er', 'Employer match this year' );
      fsl_card( 'k4-limit', '2026 limit for your age', 'secondary' );
      fsl_card( 'k4-left', 'Match you are leaving unclaimed', 'gold' );
      fsl_card( 'k4-tot-you', 'Total you contribute' );
      fsl_card( 'k4-tot-gr', 'Investment growth' );
      ?>
    </div>
    <p class="fsl-note" id="k4-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var age=$('k4-age'), ret=$('k4-ret'), sal=$('k4-sal'), bal=$('k4-bal'), pct=$('k4-pct')/100, mp=$('k4-mpct')/100, mc=$('k4-mcap')/100, raise=$('k4-raise')/100, r=$('k4-ret-r')/100, inf=$('k4-inf')/100;
    if(!FSL.valid(ret>age&&sal>0,'k4-err','Enter a salary and a retirement age higher than your current age.'))return;
    /* 2026 IRS limits: $24,500 deferral, +$8,000 catch-up at 50+, +$11,250 at 60-63 */
    var limit=24500+(age>=60&&age<=63?11250:(age>=50?8000:0));
    var yrs=ret-age, b=bal, you0=0, er0=0, totYou=0, totEr=0, sal0=sal, lim0=limit;
    for(var y=0;y<yrs;y++){
      var a=age+y, lim=24500+(a>=60&&a<=63?11250:(a>=50?8000:0));
      var yy=Math.min(sal*pct,lim), ee=Math.min(sal*pct,sal*mc)*mp;
      if(y===0){you0=yy;er0=ee;}
      totYou+=yy; totEr+=ee;
      b=b*(1+r)+(yy+ee)*(1+r/2);
      sal*=1+raise;
    }
    var real=b/Math.pow(1+inf,yrs), full=sal0*mc*mp, left=Math.max(full-er0,0);
    FSL.set('k4-bal-ret',M(b)); FSL.set('k4-real',M(real)); FSL.set('k4-you',M(you0)+(sal0*pct>limit?' (capped)':'')); FSL.set('k4-er',M(er0));
    FSL.set('k4-limit',M(limit)); FSL.set('k4-left',left>0?M(left)+' a year':'None: you get the full match');
    FSL.set('k4-tot-you',M(totYou+0)); FSL.set('k4-tot-gr',M(b-bal-totYou-totEr));
    FSL.show('k4-results','grid');
    var n=[];
    if(left>0)n.push('Your employer would match up to '+M(full)+' a year. Raising your contribution to '+(mc*100)+'% of salary captures the full amount.');
    if(sal0*pct>limit)n.push('Your chosen contribution is above the 2026 limit of '+M(limit)+', so the calculator caps it.');
    n.push('The 2026 employee limit is $24,500, with an extra $8,000 catch-up from age 50 and $11,250 for ages 60–63.');
    FSL.set('k4-note',n.join(' '));
  }
  FSL.el('k4-go').addEventListener('click',calc); FSL.bind('fs-k4',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   6. SAVINGS GOAL CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_savings_goal() { ?>
<div class="fsc-wrap" id="fs-sg">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'sg-goal', 'Savings goal ($)', 20000, [ 'min' => 1, 'step' => 500 ] ); ?>
    <?php fsl_field( 'sg-have', 'Already saved ($)', 2500, [ 'min' => 0, 'step' => 100 ] ); ?>
    <?php fsl_field( 'sg-apy', 'Savings account APY (%)', 4, [ 'min' => 0, 'max' => 20, 'step' => 0.05, 'hint' => 'Top online accounts pay up to about 4.5% in October 2026; the national average is under 1%.' ] ); ?>
    <?php fsl_field( 'sg-monthly', 'I can save per month ($)', 400, [ 'min' => 0, 'step' => 25 ] ); ?>
    <?php fsl_field( 'sg-months', 'I want to reach it in (months)', 36, [ 'min' => 1, 'max' => 600, 'step' => 1 ] ); ?>
    <button type="button" class="fsc-btn" id="sg-go">Calculate</button>
    <p class="fsl-error" id="sg-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="sg-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'sg-time', 'Time to reach your goal', 'primary' );
      fsl_card( 'sg-date', 'Goal date' );
      fsl_card( 'sg-req', 'Monthly needed for your deadline', 'gold' );
      fsl_card( 'sg-int', 'Interest earned (at your monthly amount)' );
      fsl_card( 'sg-dep', 'Total deposits' );
      fsl_card( 'sg-end', 'Balance at the goal date' );
      ?>
    </div>
    <p class="fsl-note" id="sg-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var G=$('sg-goal'), C=$('sg-have'), apy=$('sg-apy')/100, m=$('sg-monthly'), N=Math.max(1,Math.round($('sg-months',36)));
    if(!FSL.valid(G>0,'sg-err','Enter a savings goal.'))return;
    var i=Math.pow(1+apy,1/12)-1, bal=C, dep=0, mo=0;
    while(bal<G&&mo<1200&&(m>0||i>0)){bal=bal*(1+i)+m;dep+=m;mo++;}
    var reached=bal>=G;
    var g=Math.pow(1+i,N), req=C>=G*1?0:(i===0?(G-C)/N:Math.max((G-C*g)*i/(g-1),0));
    FSL.set('sg-time',C>=G?'Already there':(reached?FSL.monthsText(mo):'Not reachable at this savings rate'));
    FSL.set('sg-date',reached?FSL.dateText(FSL.addMonths(new Date(),mo)):'--');
    FSL.set('sg-req',req>0?M(req)+'/mo for '+FSL.monthsText(N):'Nothing needed');
    FSL.set('sg-int',reached?M(bal-C-dep):'--'); FSL.set('sg-dep',reached?M(dep):'--'); FSL.set('sg-end',reached?M(bal):'--');
    FSL.show('sg-results','grid');
    FSL.set('sg-note',C>=G?'You have already reached this goal.':(reached?'Saving '+M(m)+' a month, you reach '+M(G)+' in '+FSL.monthsText(mo)+', and interest contributes '+M(bal-C-dep)+'. To finish in '+FSL.monthsText(N)+' you would need '+M(req)+' a month.':'Add a monthly amount to see when you can reach your goal.'));
  }
  FSL.el('sg-go').addEventListener('click',calc); FSL.bind('fs-sg',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   7. EMERGENCY FUND CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_emergency_fund() { ?>
<div class="fsc-wrap" id="fs-ef">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <h3 class="fsc-section-title">Essential monthly costs</h3>
    <?php fsl_field( 'ef-rent', 'Rent or mortgage ($)', 1500, [ 'min' => 0, 'step' => 50 ] ); ?>
    <?php fsl_field( 'ef-util', 'Utilities, phone, internet ($)', 300, [ 'min' => 0, 'step' => 25 ] ); ?>
    <?php fsl_field( 'ef-food', 'Groceries ($)', 500, [ 'min' => 0, 'step' => 25 ] ); ?>
    <?php fsl_field( 'ef-ins', 'Insurance and health costs ($)', 350, [ 'min' => 0, 'step' => 25 ] ); ?>
    <?php fsl_field( 'ef-debt', 'Minimum debt payments ($)', 300, [ 'min' => 0, 'step' => 25 ] ); ?>
    <?php fsl_field( 'ef-trans', 'Transportation ($)', 250, [ 'min' => 0, 'step' => 25 ] ); ?>
    <h3 class="fsc-section-title">Your situation</h3>
    <?php fsl_select( 'ef-months', 'How many months should it cover?', [ 3 => '3 months: stable job, two incomes', 6 => '6 months: typical recommendation', 9 => '9 months: one income or variable pay', 12 => '12 months: self-employed or high risk' ], 6 ); ?>
    <?php fsl_field( 'ef-have', 'Emergency savings today ($)', 2000, [ 'min' => 0, 'step' => 100 ] ); ?>
    <?php fsl_field( 'ef-save', 'You can add per month ($)', 300, [ 'min' => 0, 'step' => 25 ] ); ?>
    <?php fsl_field( 'ef-apy', 'Savings account APY (%)', 4, [ 'min' => 0, 'max' => 20, 'step' => 0.05 ] ); ?>
    <button type="button" class="fsc-btn" id="ef-go">Calculate</button>
    <p class="fsl-error" id="ef-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="ef-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'ef-target', 'Your emergency fund target', 'primary' );
      fsl_card( 'ef-exp', 'Essential expenses per month' );
      fsl_card( 'ef-gap', 'Still to save' );
      fsl_card( 'ef-pct', 'Progress' );
      fsl_card( 'ef-time', 'Time to reach it', 'gold' );
      fsl_card( 'ef-cover', 'Your savings cover today', 'secondary' );
      ?>
    </div>
    <div class="fsl-bar" aria-hidden="true"><span id="ef-bar" style="width:0"></span></div>
    <p class="fsl-note" id="ef-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var exp=$('ef-rent')+$('ef-util')+$('ef-food')+$('ef-ins')+$('ef-debt')+$('ef-trans'), mo=parseInt(FSL.el('ef-months').value,10), have=$('ef-have'), add=$('ef-save'), i=Math.pow(1+$('ef-apy')/100,1/12)-1;
    if(!FSL.valid(exp>0,'ef-err','Enter your essential monthly costs.'))return;
    var target=exp*mo, gap=Math.max(target-have,0), pct=Math.min(have/target*100,100), n=0, b=have;
    while(b<target&&n<1200&&(add>0||i>0)){b=b*(1+i)+add;n++;}
    FSL.set('ef-target',M(target)); FSL.set('ef-exp',M(exp)+'/mo'); FSL.set('ef-gap',M(gap)); FSL.set('ef-pct',pct.toFixed(0)+'%');
    FSL.set('ef-time',gap===0?'Fully funded':(b>=target?FSL.monthsText(n)+' ('+FSL.dateText(FSL.addMonths(new Date(),n))+')':'Add a monthly amount'));
    FSL.set('ef-cover',(have/exp).toFixed(1)+' months'); FSL.el('ef-bar').style.width=pct+'%';
    FSL.show('ef-results','grid');
    FSL.set('ef-note',gap===0?'You have enough to cover '+mo+' months of essentials. Keep it in a high-yield savings account, separate from spending money.':'Keeping '+mo+' months of essentials ('+M(exp)+' each month) means '+M(target)+'. A first milestone is one month of expenses ('+M(exp)+'). Keep the fund in an insured high-yield savings account, not the stock market.');
  }
  FSL.el('ef-go').addEventListener('click',calc); FSL.bind('fs-ef',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   8. ROI CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_roi() { ?>
<div class="fsc-wrap" id="fs-roi">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'roi-init', 'Amount invested ($)', 10000, [ 'min' => 0.01, 'step' => 100 ] ); ?>
    <?php fsl_field( 'roi-final', 'Final value ($)', 14500, [ 'min' => 0, 'step' => 100 ] ); ?>
    <?php fsl_field( 'roi-inc', 'Income received along the way ($)', 0, [ 'min' => 0, 'step' => 50, 'hint' => 'Dividends, rent or interest.' ] ); ?>
    <?php fsl_field( 'roi-cost', 'Fees, commissions and other costs ($)', 0, [ 'min' => 0, 'step' => 50 ] ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'roi-y', 'Held: years', 3, [ 'min' => 0, 'max' => 100, 'step' => 1 ] ); ?>
      <?php fsl_field( 'roi-m', 'and months', 0, [ 'min' => 0, 'max' => 11, 'step' => 1 ] ); ?>
    </div>
    <?php fsl_field( 'roi-inf', 'Inflation per year (%, optional)', 0, [ 'min' => 0, 'max' => 20, 'step' => 0.1 ] ); ?>
    <button type="button" class="fsc-btn" id="roi-go">Calculate ROI</button>
    <p class="fsl-error" id="roi-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="roi-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'roi-pct', 'Total ROI', 'primary' );
      fsl_card( 'roi-profit', 'Net profit or loss' );
      fsl_card( 'roi-ann', 'Annualized return (CAGR)', 'secondary' );
      fsl_card( 'roi-mult', 'Money multiple' );
      fsl_card( 'roi-real', 'Annualized after inflation' );
      fsl_card( 'roi-time', 'Holding period' );
      ?>
    </div>
    <p class="fsl-note" id="roi-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var I=$('roi-init'), F=$('roi-final'), inc=$('roi-inc'), cost=$('roi-cost'), yrs=$('roi-y')+$('roi-m')/12, inf=$('roi-inf')/100;
    if(!FSL.valid(I>0,'roi-err','Enter the amount you invested (greater than zero).'))return;
    var end=F+inc-cost, profit=end-I, roi=profit/I*100, mult=end/I;
    var ann=(yrs>0&&end>0)?(Math.pow(end/I,1/yrs)-1)*100:NaN, real=isFinite(ann)?((1+ann/100)/(1+inf)-1)*100:NaN;
    FSL.set('roi-pct',FSL.pct(roi,2)); FSL.set('roi-profit',(profit>=0?'+':'-')+M(Math.abs(profit),2));
    FSL.set('roi-ann',yrs>0?(isFinite(ann)?FSL.pct(ann,2)+' per year':'Total loss'):'Enter a holding period');
    FSL.set('roi-mult',mult.toFixed(2)+'x'); FSL.set('roi-real',isFinite(real)?FSL.pct(real,2)+' per year':'--'); FSL.set('roi-time',yrs>0?FSL.monthsText(Math.round(yrs*12)):'--');
    FSL.show('roi-results','grid');
    FSL.set('roi-note',profit>=0?'You turned '+M(I)+' into '+M(end)+'. ROI ignores time, so compare investments of different lengths by their annualized return, not the total percentage.':'This investment lost '+M(-profit)+'. A loss of '+Math.abs(roi).toFixed(1)+'% needs a gain of '+(roi>-100?(100/(100+roi)*100-100).toFixed(1):'--')+'% just to break even.');
  }
  FSL.el('roi-go').addEventListener('click',calc); FSL.bind('fs-roi',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   9. NET WORTH CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_net_worth() { ?>
<div class="fsc-wrap" id="fs-nw">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <h3 class="fsc-section-title">Assets (what you own)</h3>
    <?php fsl_field( 'nw-cash', 'Cash and savings ($)', 15000, [ 'min' => 0, 'step' => 500 ] ); ?>
    <?php fsl_field( 'nw-inv', 'Taxable investments ($)', 20000, [ 'min' => 0, 'step' => 500 ] ); ?>
    <?php fsl_field( 'nw-ret', 'Retirement accounts ($)', 60000, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <?php fsl_field( 'nw-home', 'Home value ($)', 350000, [ 'min' => 0, 'step' => 5000 ] ); ?>
    <?php fsl_field( 'nw-car', 'Vehicles ($)', 18000, [ 'min' => 0, 'step' => 500 ] ); ?>
    <?php fsl_field( 'nw-oa', 'Other assets ($)', 0, [ 'min' => 0, 'step' => 500 ] ); ?>
    <h3 class="fsc-section-title">Liabilities (what you owe)</h3>
    <?php fsl_field( 'nw-mort', 'Mortgage ($)', 240000, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <?php fsl_field( 'nw-auto', 'Auto loans ($)', 9000, [ 'min' => 0, 'step' => 500 ] ); ?>
    <?php fsl_field( 'nw-stu', 'Student loans ($)', 15000, [ 'min' => 0, 'step' => 500 ] ); ?>
    <?php fsl_field( 'nw-cc', 'Credit card debt ($)', 3000, [ 'min' => 0, 'step' => 100 ] ); ?>
    <?php fsl_field( 'nw-ol', 'Other debts ($)', 0, [ 'min' => 0, 'step' => 100 ] ); ?>
    <?php fsl_select( 'nw-age', 'Your age group (for comparison)', [ 'u35' => 'Under 35', 'a35' => '35–44', 'a45' => '45–54', 'a55' => '55–64', 'a65' => '65–74', 'a75' => '75 and older' ], 'a35' ); ?>
    <button type="button" class="fsc-btn" id="nw-go">Calculate net worth</button>
  </div>
  <div>
    <div class="fsc-results" id="nw-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'nw-net', 'Your net worth', 'primary' );
      fsl_card( 'nw-assets', 'Total assets' );
      fsl_card( 'nw-liab', 'Total liabilities' );
      fsl_card( 'nw-ratio', 'Debt as % of assets' );
      fsl_card( 'nw-liquid', 'Net worth excluding home equity', 'secondary' );
      fsl_card( 'nw-med', 'Median for your age group', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="nw-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  /* Federal Reserve 2022 Survey of Consumer Finances, median family net worth by age of head */
  var MED={u35:39040,a35:135300,a45:246700,a55:364270,a65:410000,a75:334700};
  function calc(){
    var cash=$('nw-cash'),inv=$('nw-inv'),ret=$('nw-ret'),home=$('nw-home'),car=$('nw-car'),oa=$('nw-oa');
    var mort=$('nw-mort'),au=$('nw-auto'),stu=$('nw-stu'),cc=$('nw-cc'),ol=$('nw-ol');
    var A=cash+inv+ret+home+car+oa, L=mort+au+stu+cc+ol, net=A-L, nohome=net-(home-mort), med=MED[FSL.el('nw-age').value];
    var el=FSL.el('nw-net'); FSL.set('nw-net',(net<0?'-':'')+M(Math.abs(net)));
    FSL.set('nw-assets',M(A)); FSL.set('nw-liab',M(L)); FSL.set('nw-ratio',A>0?FSL.pct(L/A*100,1):'--'); FSL.set('nw-liquid',(nohome<0?'-':'')+M(Math.abs(nohome))); FSL.set('nw-med',M(med));
    FSL.show('nw-results','grid');
    FSL.set('nw-note',net>=med?'You are above the median of '+M(med)+' for your age group, by '+M(net-med)+'.':'You are '+M(med-net)+' below the median of '+M(med)+' for your age group. Medians are one benchmark only; your goals and income matter more.');
  }
  FSL.el('nw-go').addEventListener('click',calc); FSL.bind('fs-nw',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   10. 50/30/20 BUDGET CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_budget_503020() { ?>
<div class="fsc-wrap" id="fs-bg">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'bg-inc', 'Monthly take-home pay, after tax ($)', 5000, [ 'min' => 0, 'step' => 100 ] ); ?>
    <h3 class="fsc-section-title">Your split (default 50 / 30 / 20)</h3>
    <div class="fsl-cols3">
      <?php fsl_field( 'bg-n', 'Needs %', 50, [ 'min' => 0, 'max' => 100, 'step' => 1 ] ); ?>
      <?php fsl_field( 'bg-w', 'Wants %', 30, [ 'min' => 0, 'max' => 100, 'step' => 1 ] ); ?>
      <?php fsl_field( 'bg-s', 'Savings %', 20, [ 'min' => 0, 'max' => 100, 'step' => 1 ] ); ?>
    </div>
    <h3 class="fsc-section-title">What you actually spend (optional)</h3>
    <div class="fsl-cols3">
      <?php fsl_field( 'bg-an', 'Needs ($)', 0, [ 'min' => 0, 'step' => 50 ] ); ?>
      <?php fsl_field( 'bg-aw', 'Wants ($)', 0, [ 'min' => 0, 'step' => 50 ] ); ?>
      <?php fsl_field( 'bg-as', 'Savings ($)', 0, [ 'min' => 0, 'step' => 50 ] ); ?>
    </div>
    <button type="button" class="fsc-btn" id="bg-go">Calculate budget</button>
    <p class="fsl-error" id="bg-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="bg-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'bg-needs', 'Needs budget', 'primary' );
      fsl_card( 'bg-wants', 'Wants budget' );
      fsl_card( 'bg-sav', 'Savings and debt payoff', 'gold' );
      fsl_card( 'bg-year', 'Saved per year' );
      ?>
    </div>
    <div class="fsc-table-wrap" id="bg-cmp" style="display:none;padding:1rem 0 0"><table class="fsc-table fsl-cmp"><thead><tr><th></th><th>Target</th><th>Your spending</th><th>Difference</th></tr></thead><tbody id="bg-body"></tbody></table></div>
    <p class="fsl-note" id="bg-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var inc=$('bg-inc'), n=$('bg-n'), w=$('bg-w'), s=$('bg-s'), sum=n+w+s;
    if(!FSL.valid(inc>0&&Math.abs(sum-100)<0.01,'bg-err',inc>0?'Your three percentages add up to '+sum+'%. They need to total 100%.':'Enter your monthly take-home pay.'))return;
    var tn=inc*n/100, tw=inc*w/100, ts=inc*s/100, an=$('bg-an'), aw=$('bg-aw'), as=$('bg-as'), has=an+aw+as>0;
    FSL.set('bg-needs',M(tn)+'/mo'); FSL.set('bg-wants',M(tw)+'/mo'); FSL.set('bg-sav',M(ts)+'/mo'); FSL.set('bg-year',M(ts*12)+'/yr');
    FSL.show('bg-results','grid');
    var cmp=FSL.el('bg-cmp'); cmp.style.display=has?'block':'none';
    if(has){
      function row(l,t,a){var d=a-t;return '<tr><th scope="row">'+l+'</th><td>'+M(t)+'</td><td>'+M(a)+'</td><td'+(l==='Savings'?(d>=0?' class="is-best"':''):(d<=0?' class="is-best"':''))+'>'+(d>=0?'+':'-')+M(Math.abs(d))+'</td></tr>';}
      FSL.el('bg-body').innerHTML=row('Needs',tn,an)+row('Wants',tw,aw)+row('Savings',ts,as);
    }
    var tot=an+aw+as;
    FSL.set('bg-note',has?(tot>inc?'You are spending '+M(tot-inc)+' more than your take-home pay each month. Start by trimming wants.':(an>tn?'Your needs are '+M(an-tn)+' above the 50% guide. That is common in high-cost areas; consider shifting some from wants.':'You are within the needs guide. Make sure savings reach at least '+M(ts)+' a month.')):'Needs are essentials such as rent, groceries, utilities, insurance and minimum debt payments. Wants are everything optional. Savings includes emergency fund, retirement and extra debt payments.');
  }
  FSL.el('bg-go').addEventListener('click',calc); FSL.bind('fs-bg',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   11. PROPERTY TAX CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_property_tax() { ?>
<div class="fsc-wrap" id="fs-pt">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'pt-value', 'Home market value ($)', 400000, [ 'min' => 1000, 'step' => 5000 ] ); ?>
    <?php fsl_field( 'pt-rate', 'Property tax rate (% of assessed value)', 1.0, [ 'min' => 0, 'max' => 5, 'step' => 0.01, 'hint' => 'Check your county assessor’s rate. Effective rates range from about 0.3% to over 2% depending on the state and county.' ] ); ?>
    <?php fsl_field( 'pt-ratio', 'Assessment ratio (% of market value)', 100, [ 'min' => 1, 'max' => 100, 'step' => 1, 'hint' => 'Some areas assess at a fraction of market value. Use 100 if unsure.' ] ); ?>
    <?php fsl_field( 'pt-exempt', 'Exemptions, such as homestead ($)', 0, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <?php fsl_field( 'pt-growth', 'Yearly increase in assessed value (%)', 3, [ 'min' => 0, 'max' => 15, 'step' => 0.1, 'hint' => 'Some states cap annual increases for homesteads.' ] ); ?>
    <?php fsl_field( 'pt-years', 'Project for (years)', 10, [ 'min' => 1, 'max' => 40, 'step' => 1 ] ); ?>
    <button type="button" class="fsc-btn" id="pt-go">Calculate property tax</button>
    <p class="fsl-error" id="pt-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="pt-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'pt-annual', 'Property tax per year', 'primary' );
      fsl_card( 'pt-month', 'Per month (escrow amount)' );
      fsl_card( 'pt-assessed', 'Taxable assessed value' );
      fsl_card( 'pt-mill', 'Rate per $1,000 of value' );
      fsl_card( 'pt-total', 'Total over the projection', 'gold' );
      fsl_card( 'pt-last', 'Tax in the final year', 'secondary' );
      ?>
    </div>
    <p class="fsl-note" id="pt-note"></p>
  </div>
</div>
<div id="pt-tbl" style="display:none;margin-top:1.5rem">
  <h3 class="fsl-h3">Year-by-year projection</h3>
  <div class="fsc-table-wrap fsl-sched-wrap"><table class="fsc-table fsl-cmp"><thead><tr><th>Year</th><th>Assessed value</th><th>Tax</th><th>Per month</th></tr></thead><tbody id="pt-body"></tbody></table></div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var v=$('pt-value'), rate=$('pt-rate')/100, ratio=$('pt-ratio')/100, ex=$('pt-exempt'), g=$('pt-growth')/100, Y=Math.max(1,Math.round($('pt-years')));
    if(!FSL.valid(v>0,'pt-err','Enter your home value.'))return;
    var assessed=Math.max(v*ratio-ex,0), tax=assessed*rate, rows='', total=0, a=v*ratio, last=0;
    for(var y=1;y<=Y;y++){ var t=Math.max(a-ex,0)*rate; total+=t; last=t; rows+='<tr><td>'+y+'</td><td>'+M(Math.max(a-ex,0))+'</td><td>'+M(t)+'</td><td>'+M(t/12)+'</td></tr>'; a*=1+g; }
    FSL.set('pt-annual',M(tax)); FSL.set('pt-month',M(tax/12)+'/mo'); FSL.set('pt-assessed',M(assessed)); FSL.set('pt-mill','$'+(rate*ratio*1000).toFixed(2));
    FSL.set('pt-total',M(total)+' over '+Y+' years'); FSL.set('pt-last',M(last));
    FSL.show('pt-results','grid'); FSL.show('pt-tbl'); FSL.el('pt-body').innerHTML=rows;
    FSL.set('pt-note','At '+(rate*100).toFixed(2)+'% on '+M(assessed)+' of assessed value, your bill is '+M(tax)+' a year. If your assessed value rises '+(g*100)+'% a year, the bill grows to about '+M(last)+' by year '+Y+'. Property tax is usually collected monthly with your mortgage payment through escrow.');
  }
  FSL.el('pt-go').addEventListener('click',calc); FSL.bind('fs-pt',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   12. IRA CALCULATOR (Traditional vs Roth, 2026 limits)
───────────────────────────────────────────── */
function fs_calc2_ira() { ?>
<div class="fsc-wrap" id="fs-ira">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <div class="fsl-row2">
      <?php fsl_field( 'ira-age', 'Your age', 35, [ 'min' => 18, 'max' => 75, 'step' => 1 ] ); ?>
      <?php fsl_field( 'ira-ret', 'Retire at', 65, [ 'min' => 40, 'max' => 80, 'step' => 1 ] ); ?>
    </div>
    <?php fsl_field( 'ira-bal', 'Current IRA balance ($)', 25000, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <?php fsl_field( 'ira-contrib', 'Contribution per year ($)', 7500, [ 'min' => 0, 'step' => 100, 'hint' => '2026 limit: $7,500, or $8,600 at age 50 or older.' ] ); ?>
    <?php fsl_field( 'ira-return', 'Expected annual return (%)', 7, [ 'min' => 0, 'max' => 15, 'step' => 0.1 ] ); ?>
    <?php fsl_field( 'ira-now', 'Your tax rate today (%)', 22, [ 'min' => 0, 'max' => 45, 'step' => 1, 'hint' => 'Your marginal federal + state rate.' ] ); ?>
    <?php fsl_field( 'ira-later', 'Your expected tax rate in retirement (%)', 15, [ 'min' => 0, 'max' => 45, 'step' => 1 ] ); ?>
    <?php fsl_field( 'ira-inf', 'Inflation (%)', 3, [ 'min' => 0, 'max' => 10, 'step' => 0.1 ] ); ?>
    <button type="button" class="fsc-btn" id="ira-go">Compare Traditional and Roth</button>
    <p class="fsl-error" id="ira-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="ira-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'ira-win', 'Better choice for these inputs', 'primary' );
      fsl_card( 'ira-bal-ret', 'IRA balance at retirement' );
      fsl_card( 'ira-roth', 'Roth: spendable after tax' );
      fsl_card( 'ira-trad', 'Traditional: spendable after tax' );
      fsl_card( 'ira-sav', 'Tax saved each year with Traditional' );
      fsl_card( 'ira-limit', '2026 contribution limit for you', 'secondary' );
      fsl_card( 'ira-real', 'Roth value in today’s dollars' );
      ?>
    </div>
    <p class="fsl-note" id="ira-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var age=$('ira-age'), ret=$('ira-ret'), bal=$('ira-bal'), c=$('ira-contrib'), r=$('ira-return')/100, tn=$('ira-now')/100, tl=$('ira-later')/100, inf=$('ira-inf')/100;
    if(!FSL.valid(ret>age,'ira-err','Retirement age must be higher than your current age.'))return;
    var yrs=ret-age, limit=7500+(age>=50?1100:0), cc=Math.min(c,limit);
    /* contributions at the start of each year */
    var b=bal; for(var y=0;y<yrs;y++){ b=(b+cc)*(1+r); }
    var roth=b, trad=b*(1-tl), sav=cc*tn;
    FSL.set('ira-bal-ret',M(b)); FSL.set('ira-roth',M(roth)); FSL.set('ira-trad',M(trad)); FSL.set('ira-sav',M(sav)+' a year');
    FSL.set('ira-limit',M(limit)+(age>=50?' (incl. catch-up)':'')); FSL.set('ira-real',M(roth/Math.pow(1+inf,yrs)));
    var win=tn>tl?'Traditional (tax rate drops in retirement)':(tn<tl?'Roth (tax rate rises in retirement)':'Roth and Traditional are about equal');
    FSL.set('ira-win',win); FSL.show('ira-results','grid');
    var n=[]; if(c>limit)n.push('Your contribution is above the 2026 limit of '+M(limit)+', so the calculator caps it.');
    n.push('Same pre-tax contribution in both accounts: Roth grows tax-free but gives no deduction, while Traditional saves '+M(sav)+' of tax each year (if deductible) and is taxed on withdrawal. The simple rule: Traditional wins when your tax rate in retirement is lower than today, Roth wins when it is higher. Traditional contributions may be tax-deductible and are taxed on withdrawal. Roth contributions are not deductible but qualified withdrawals are tax-free. Roth IRA eligibility phases out for single filers with income between $153,000 and $168,000 and for married couples filing jointly between $242,000 and $252,000 in 2026.');
    FSL.set('ira-note',n.join(' '));
  }
  FSL.el('ira-go').addEventListener('click',calc); FSL.bind('fs-ira',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   13. NFT ROI CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_nft_roi() { ?>
<div class="fsc-wrap" id="fs-nft">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'nft-buy', 'Purchase or mint price ($)', 500, [ 'min' => 0, 'step' => 10 ] ); ?>
    <?php fsl_field( 'nft-gas-buy', 'Gas / network fee when buying ($)', 15, [ 'min' => 0, 'step' => 1 ] ); ?>
    <?php fsl_field( 'nft-sell', 'Sale price ($)', 900, [ 'min' => 0, 'step' => 10 ] ); ?>
    <?php fsl_field( 'nft-gas-sell', 'Gas / network fee when selling ($)', 10, [ 'min' => 0, 'step' => 1 ] ); ?>
    <div class="fsl-row2">
      <?php fsl_field( 'nft-mkt', 'Marketplace fee (%)', 2.5, [ 'min' => 0, 'max' => 20, 'step' => 0.1, 'hint' => 'Check your marketplace’s current fee.' ] ); ?>
      <?php fsl_field( 'nft-roy', 'Creator royalty (%)', 5, [ 'min' => 0, 'max' => 20, 'step' => 0.1 ] ); ?>
    </div>
    <?php fsl_field( 'nft-months', 'Held (months)', 6, [ 'min' => 0, 'max' => 240, 'step' => 1 ] ); ?>
    <?php fsl_field( 'nft-tax', 'Tax rate on gains (%)', 15, [ 'min' => 0, 'max' => 45, 'step' => 1, 'hint' => 'NFTs can be taxed as collectibles at up to 28% if held over a year.' ] ); ?>
    <button type="button" class="fsc-btn" id="nft-go">Calculate NFT ROI</button>
    <p class="fsl-error" id="nft-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="nft-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'nft-roi', 'ROI after fees', 'primary' );
      fsl_card( 'nft-profit', 'Net profit before tax' );
      fsl_card( 'nft-after', 'Profit after tax', 'gold' );
      fsl_card( 'nft-fees', 'Total fees and royalties' );
      fsl_card( 'nft-be', 'Break-even sale price', 'secondary' );
      fsl_card( 'nft-ann', 'Annualized return' );
      ?>
    </div>
    <p class="fsl-note" id="nft-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  function calc(){
    var buy=$('nft-buy'), gb=$('nft-gas-buy'), sell=$('nft-sell'), gs=$('nft-gas-sell'), mk=$('nft-mkt')/100, ry=$('nft-roy')/100, mo=$('nft-months'), tax=$('nft-tax')/100;
    var cost=buy+gb;
    if(!FSL.valid(cost>0,'nft-err','Enter what you paid for the NFT.'))return;
    var fees=sell*(mk+ry)+gs, net=sell-fees, profit=net-cost, taxAmt=profit>0?profit*tax:0;
    var roi=profit/cost*100, yrs=mo/12, ann=(yrs>0&&net>0)?(Math.pow(net/cost,1/yrs)-1)*100:NaN;
    var be=(cost+gs)/(1-mk-ry);
    FSL.set('nft-roi',FSL.pct(roi,1)); FSL.set('nft-profit',(profit>=0?'+':'-')+M(Math.abs(profit),2)); FSL.set('nft-after',(profit-taxAmt>=0?'+':'-')+M(Math.abs(profit-taxAmt),2));
    FSL.set('nft-fees',M(fees+gb,2)); FSL.set('nft-be',M(be,2)); FSL.set('nft-ann',isFinite(ann)?FSL.pct(ann,1)+' per year':'--');
    FSL.show('nft-results','grid');
    FSL.set('nft-note',profit>=0?'After '+M(fees+gb,2)+' of fees and royalties you keep '+M(profit,2)+' before tax. You must sell above '+M(be,2)+' just to break even.':'This sale loses '+M(-profit,2)+' after fees. The break-even price is '+M(be,2)+'. A capital loss may offset other gains on your tax return.');
  }
  FSL.el('nft-go').addEventListener('click',calc); FSL.bind('fs-nft',calc); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   14. TAX & BONUS CALCULATOR (percentage vs aggregate method)
───────────────────────────────────────────── */
function fs_calc2_tax_bonus() { fs_tax_data_script(); ?>
<div class="fsc-wrap" id="fs-bn">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'bn-bonus', 'Bonus amount ($)', 10000, [ 'min' => 0, 'step' => 100, 'hint' => 'How much a bonus is taxed depends on the method your employer uses.' ] ); ?>
    <?php fsl_field( 'bn-pay', 'Regular salary this pay period ($)', 4000, [ 'min' => 0, 'step' => 50, 'hint' => 'Only used for the aggregate method, where the bonus is combined with one paycheck.' ] ); ?>
    <?php fsl_select( 'bn-freq', 'Pay frequency', [ 52 => 'Weekly', 26 => 'Biweekly', 24 => 'Semi-monthly', 12 => 'Monthly' ], 26 ); ?>
    <?php fsl_select( 'bn-status', 'Filing status', [ 'single' => 'Single', 'mfj' => 'Married filing jointly', 'hoh' => 'Head of household', 'mfs' => 'Married filing separately' ], 'single' ); ?>
    <?php fsl_field( 'bn-ytd-income', 'Year-to-date gross income before this bonus ($)', 60000, [ 'min' => 0, 'step' => 1000, 'hint' => 'Used for the Medicare surtax threshold check, and as a conservative stand-in for cumulative supplemental wages when checking the $1 million / 37% rule (the IRS rule technically applies to supplemental wages specifically, not total pay).' ] ); ?>
    <?php fsl_field( 'bn-ytd-ss', 'Year-to-date Social Security wages ($)', 60000, [ 'min' => 0, 'step' => 1000, 'hint' => 'Social Security tax stops once wages for the year reach the annual wage base.' ] ); ?>
    <?php fsl_select( 'bn-state', 'State bonus withholding', [
        'none' => 'No state income tax (AK, FL, NV, NH, SD, TN, TX, WA, WY)',
        'az' => 'Arizona — flat 2.00% supplemental rate',
        'ca' => 'California — flat 10.23% supplemental rate (bonuses/stock options)',
        'co' => 'Colorado — flat 4.40% supplemental rate',
        'ga' => 'Georgia — flat 5.39% supplemental rate',
        'il' => 'Illinois — flat 4.95% supplemental rate',
        'in' => 'Indiana — flat 3.05% supplemental rate',
        'ky' => 'Kentucky — flat 4.00% supplemental rate',
        'ma' => 'Massachusetts — flat 5.00% supplemental rate',
        'mi' => 'Michigan — flat 4.25% supplemental rate',
        'nj' => 'New Jersey — flat 5.525% supplemental rate',
        'ny' => 'New York — flat 11.70% supplemental rate',
        'nc' => 'North Carolina — flat 4.25% supplemental rate',
        'pa' => 'Pennsylvania — flat 3.07% supplemental rate',
        'other' => 'Other state — enter my own rate',
    ], 'none', [ 'hint' => 'Flat supplemental-wage rates states publish for bonus withholding; your actual paycheck may differ. Confirm with your state revenue department.' ] ); ?>
    <div class="fsc-field" id="bn-other-wrap" style="display:none">
      <label for="bn-other-rate">State flat supplemental rate (%)</label>
      <input type="number" id="bn-other-rate" value="5" min="0" max="15" step="0.01" inputmode="decimal">
    </div>
    <div class="fsl-row2">
      <?php fsl_select( 'bn-method', 'Withholding method', [ 'percentage' => 'IRS percentage method (flat 22% / 37%)', 'aggregate' => 'Aggregate method (combined with a paycheck)' ], 'percentage', [ 'hint' => 'The percentage method is what most employers use for a bonus paid separately from regular wages.' ] ); ?>
    </div>
    <button type="button" class="fsc-btn" id="bn-go">Calculate Bonus Tax</button>
    <p class="fsl-error" id="bn-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="bn-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'bn-net', 'Net take-home bonus', 'primary' );
      fsl_card( 'bn-fed', 'Federal withholding on bonus' );
      fsl_card( 'bn-fed-rate', 'Federal supplemental rate used' );
      fsl_card( 'bn-ss', 'Social Security (6.2%)' );
      fsl_card( 'bn-medicare', 'Medicare (1.45% + surtax)' );
      fsl_card( 'bn-state-v', 'State withholding' );
      fsl_card( 'bn-total', 'Total withheld', 'gold' );
      fsl_card( 'bn-eff', 'Effective withholding rate' );
      fsl_card( 'bn-compare', 'Other method would withhold', 'secondary' );
      ?>
    </div>
    <p class="fsl-note" id="bn-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money, T=window.FSTAX;
  var stateSel=FSL.el('bn-state'), otherWrap=FSL.el('bn-other-wrap');
  stateSel.addEventListener('change',function(){ otherWrap.style.display = stateSel.value==='other' ? '' : 'none'; });

  /* IRS Pub 15 "percentage method" bracket table for one payroll period, used by the aggregate method */
  function aggregateFederalTax(taxablePerPeriod, periods, status){
    var annual = taxablePerPeriod*periods;
    var tax = T.tax(Math.max(annual - T.std[status],0), status);
    return tax/periods;
  }

  function calc(){
    var bonus=$('bn-bonus'), pay=$('bn-pay'), freq=parseInt(FSL.el('bn-freq').value,10), status=FSL.el('bn-status').value;
    var ytdIncome=$('bn-ytd-income'), ytdSS=$('bn-ytd-ss'), method=FSL.el('bn-method').value;
    var stateRates={az:0.02,ca:0.1023,co:0.044,ga:0.0539,il:0.0495,'in':0.0305,ky:0.04,ma:0.05,mi:0.0425,nj:0.05525,ny:0.117,nc:0.0425,pa:0.0307};
    var stateVal=stateSel.value, stateRate = stateVal==='none' ? 0 : (stateVal==='other' ? ($('bn-other-rate')||0)/100 : (stateRates[stateVal]||0));
    if(!FSL.valid(bonus>0,'bn-err','Enter the bonus amount.'))return;

    /* Social Security: 6.2% up to the annual wage base, pro-rated by what's already been paid this year */
    var ssRoom=Math.max(T.ssBase-ytdSS,0);
    var ssWages=Math.min(bonus,ssRoom);
    var ss=ssWages*0.062;

    /* Medicare: 1.45% on all wages, plus 0.9% surtax above $200,000 of cumulative wages.
       Employers must withhold the 0.9% surtax once an employee's wages from that employer
       pass $200,000 in the year, regardless of filing status — the status-based thresholds
       (250k MFJ, 125k MFS) only apply when the employee files their return, not at withholding. */
    var addThresh=200000;
    var medicareBase=bonus*0.0145;
    var overBefore=Math.max(ytdIncome-addThresh,0), overAfter=Math.max(ytdIncome+bonus-addThresh,0);
    var surtax=(overAfter-overBefore)*0.009;
    var medicare=medicareBase+surtax;

    var fedPct, fedAgg, fedRateLabel;
    /* Percentage method: flat 22% up to $1M in supplemental wages for the year, 37% above */
    var roomAt22=Math.max(1000000-ytdIncome,0);
    var at22=Math.min(bonus,Math.max(roomAt22,0)), at37=Math.max(bonus-at22,0);
    fedPct = at22*0.22+at37*0.37;
    fedRateLabel = at37>0 ? '22% up to $1M, 37% above' : '22% flat';

    /* Aggregate method: combine the bonus with one regular paycheck, withhold as if it were the normal wage for that period, then subtract the tax on the regular wage alone.
       The mandatory flat 37% above $1M in cumulative supplemental wages still applies on top of this method, per IRS rules, so that portion is added separately below. */
    var taxOnCombined=aggregateFederalTax(pay+bonus,freq,status);
    var taxOnRegular=aggregateFederalTax(pay,freq,status);
    fedAgg=Math.max(taxOnCombined-taxOnRegular,0);
    if(at37>0){ fedAgg=Math.max(fedAgg,fedPct); }

    var fedUsed = method==='aggregate' ? fedAgg : fedPct;
    var fedOther = method==='aggregate' ? fedPct : fedAgg;

    var stateTax=bonus*stateRate;
    var total=fedUsed+ss+medicare+stateTax;
    var net=bonus-total;

    FSL.set('bn-net',M(net)); FSL.set('bn-fed',M(fedUsed));
    FSL.set('bn-fed-rate', method==='aggregate' ? 'Aggregate (marginal rate on combined pay)' : fedRateLabel);
    FSL.set('bn-ss',M(ss)); FSL.set('bn-medicare',M(medicare)); FSL.set('bn-state-v',M(stateTax));
    FSL.set('bn-total',M(total)); FSL.set('bn-eff',FSL.pct(total/bonus*100,1));
    FSL.set('bn-compare', M(fedOther+ss+medicare+stateTax)+' total ('+(method==='aggregate'?'percentage method':'aggregate method')+')');
    FSL.show('bn-results','grid');

    var n=[];
    if(ssWages<bonus) n.push('Only '+M(ssWages)+' of this bonus is subject to Social Security tax because your year-to-date wages are near or above the '+M(T.ssBase)+' wage base.');
    if(overAfter>overBefore) n.push('An extra 0.9% Medicare surtax applies to the part of this bonus that pushes your year-to-date wages over '+M(addThresh)+'.');
    n.push(method==='percentage' ? 'The IRS percentage method withholds a flat 22% on supplemental wages up to $1 million for the year and 37% on any amount above that, regardless of your regular tax bracket.' : 'The aggregate method adds this bonus to one regular paycheck, calculates withholding on the combined amount using IRS tables, then subtracts what would have been withheld on the regular pay alone — the result can be higher or lower than the flat 22% depending on your bracket.');
    n.push('This is withholding, not your final tax bill: the bonus is ordinary income on your tax return, and you may get some of the withholding back (or owe more) when you file.');
    FSL.set('bn-note',n.join(' '));
  }
  FSL.el('bn-go').addEventListener('click',calc); FSL.bind('fs-bn',calc);
  FSL.el('bn-method').addEventListener('change',calc);
  calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   401(K) / IRA EARLY WITHDRAWAL TAX CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_401k_withdrawal() { fs_tax_data_script(); ?>
<div class="fsc-wrap" id="fs-ew">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'ew-amount', 'Withdrawal amount ($)', 20000, [ 'min' => 0, 'step' => 500, 'hint' => 'The gross amount taken out of the traditional 401(k), 403(b) or IRA before any tax or penalty.' ] ); ?>
    <?php fsl_field( 'ew-age', 'Your age at withdrawal', 45, [ 'min' => 18, 'max' => 100, 'step' => 1 ] ); ?>
    <?php fsl_select( 'ew-status', 'Filing status', [ 'single' => 'Single', 'mfj' => 'Married filing jointly', 'hoh' => 'Head of household', 'mfs' => 'Married filing separately' ], 'single' ); ?>
    <?php fsl_field( 'ew-other-income', 'Other taxable income this year, after deductions ($)', 50000, [ 'min' => 0, 'step' => 1000, 'hint' => 'Your taxable income before this withdrawal — wages, self-employment, etc. This sets which tax brackets the withdrawal stacks on top of.' ] ); ?>
    <?php fsl_select( 'ew-exception', '10% penalty exception', [
        'none'        => 'None — early withdrawal, no exception applies',
        'rule55'      => 'Rule of 55 — separated from this employer in/after the year you turned 55 (401k/403b only, not IRA)',
        'disability'  => 'Total and permanent disability',
        'sepp'        => 'Substantially equal periodic payments (SEPP / IRS Section 72(t))',
        'medical'     => 'Unreimbursed medical expenses above 7.5% of AGI',
        'qdro'        => 'Qualified domestic relations order (QDRO, 401k/403b only)',
        'death'       => 'Death — distribution to a beneficiary',
        'levy'        => 'IRS levy on the account',
    ], 'none', [ 'hint' => 'These IRS exceptions waive the 10% penalty but not the regular income tax. Confirm your situation qualifies before relying on this.' ] ); ?>
    <?php fsl_field( 'ew-state-rate', 'State income tax rate on this withdrawal (%, optional)', 0, [ 'min' => 0, 'max' => 15, 'step' => 0.1, 'hint' => 'Alaska, Florida, Nevada, New Hampshire, South Dakota, Tennessee, Texas, Washington and Wyoming have no state wage income tax; most other states tax a 401(k)/IRA withdrawal as ordinary income.' ] ); ?>
    <button type="button" class="fsc-btn" id="ew-go">Calculate Withdrawal Tax</button>
    <p class="fsl-error" id="ew-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="ew-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'ew-net', 'You actually keep', 'primary' );
      fsl_card( 'ew-fed', 'Federal income tax on withdrawal' );
      fsl_card( 'ew-penalty', '10% early withdrawal penalty' );
      fsl_card( 'ew-state-v', 'State income tax' );
      fsl_card( 'ew-total', 'Total tax and penalty', 'gold' );
      fsl_card( 'ew-eff', 'Effective tax rate on withdrawal' );
      fsl_card( 'ew-withhold', 'Mandatory 20% withheld at distribution', 'secondary' );
      ?>
    </div>
    <p class="fsl-note" id="ew-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money, T=window.FSTAX;

  function calc(){
    var amount=$('ew-amount'), age=$('ew-age'), status=FSL.el('ew-status').value;
    var otherIncome=$('ew-other-income'), exception=FSL.el('ew-exception').value;
    var stateRate=$('ew-state-rate')/100;
    if(!FSL.valid(amount>0,'ew-err','Enter the withdrawal amount.'))return;

    /* Federal tax: the withdrawal stacks on top of other taxable income (marginal effect) */
    var taxWithout=T.tax(otherIncome,status);
    var taxWith=T.tax(otherIncome+amount,status);
    var fedTax=Math.max(taxWith-taxWithout,0);

    /* 10% penalty applies unless 59.5+ or a qualifying IRS exception */
    var penaltyFree = age>=59.5 || exception!=='none';
    var penalty = penaltyFree ? 0 : amount*0.10;

    var stateTax=amount*stateRate;
    var total=fedTax+penalty+stateTax;
    var net=amount-total;
    var mandatoryWithhold=amount*0.20;

    FSL.set('ew-net',M(net)); FSL.set('ew-fed',M(fedTax)); FSL.set('ew-penalty',M(penalty));
    FSL.set('ew-state-v',M(stateTax)); FSL.set('ew-total',M(total)); FSL.set('ew-eff',FSL.pct(total/amount*100,1));
    FSL.set('ew-withhold',M(mandatoryWithhold));
    FSL.show('ew-results','grid');

    var n=[];
    if(age>=59.5) n.push('No early withdrawal penalty applies because you are 59½ or older.');
    else if(exception!=='none') n.push('The 10% penalty is waived under the exception you selected, but the withdrawal is still taxed as ordinary income.');
    else n.push('A 10% federal penalty applies because this is an early withdrawal (before 59½) with no exception selected.');
    n.push('For a 401(k) or 403(b), the plan must withhold 20% for federal tax on an eligible rollover distribution paid directly to you, regardless of your actual tax bracket — that is shown separately above and is not the same as your real tax owed. IRAs generally default to 10% withholding, which you can adjust.');
    n.push('The federal tax shown is the extra tax this withdrawal adds on top of your other income this year, not a flat rate — a larger withdrawal can push part of itself into a higher bracket.');
    n.push('This is an estimate for a traditional (pre-tax) 401(k)/403(b)/IRA. Roth account withdrawals of your own contributions are generally tax- and penalty-free; only Roth earnings withdrawn early and non-qualified are taxed and penalized.');
    FSL.set('ew-note',n.join(' '));
  }
  FSL.el('ew-go').addEventListener('click',calc); FSL.bind('fs-ew',calc);
  calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   RETIREMENT WITHDRAWAL CALCULATOR (how long will my money last)
───────────────────────────────────────────── */
function fs_calc2_retirement_withdrawal() { ?>
<div class="fsc-wrap" id="fs-rw">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'rw-savings', 'Current retirement savings ($)', 500000, [ 'min' => 0, 'step' => 5000 ] ); ?>
    <?php fsl_field( 'rw-withdraw', 'Annual withdrawal, today\'s dollars ($)', 30000, [ 'min' => 0, 'step' => 500, 'hint' => 'The amount you plan to withdraw in the first year; later years rise with inflation so your buying power stays the same.' ] ); ?>
    <?php fsl_field( 'rw-return', 'Expected annual return (%)', 6, [ 'min' => 0, 'max' => 15, 'step' => 0.1, 'hint' => 'A diversified stock/bond portfolio has historically averaged roughly 6-7% a year after inflation is removed from the return assumption below, or use a nominal return with inflation checked separately.' ] ); ?>
    <?php fsl_field( 'rw-inflation', 'Inflation (%)', 3, [ 'min' => 0, 'max' => 10, 'step' => 0.1, 'hint' => 'Your withdrawal amount increases by this rate each year to keep the same purchasing power.' ] ); ?>
    <?php fsl_field( 'rw-years', 'Show results up to this many years', 40, [ 'min' => 5, 'max' => 60, 'step' => 1 ] ); ?>
    <button type="button" class="fsc-btn" id="rw-go">Calculate</button>
    <p class="fsl-error" id="rw-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="rw-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'rw-lasts', 'Your money lasts', 'primary' );
      fsl_card( 'rw-rate', 'First-year withdrawal rate' );
      fsl_card( 'rw-4pct', '4% rule "safe" withdrawal' );
      fsl_card( 'rw-balance10', 'Balance after 10 years' );
      fsl_card( 'rw-balance20', 'Balance after 20 years' );
      fsl_card( 'rw-balance30', 'Balance after 30 years', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="rw-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;

  function simulate(savings,withdraw,returnRate,inflation,maxYears){
    var balance=savings, w=withdraw, years=null, snapshots={};
    for(var y=1;y<=maxYears;y++){
      balance = balance*(1+returnRate) - w;
      if(balance<=0){ years = y; balance = 0; }
      if(y===10||y===20||y===30) snapshots[y]=Math.max(balance,0);
      w = w*(1+inflation);
      if(years!==null) break;
    }
    if(years===null){
      /* still positive at maxYears: keep simulating lightly to fill 10/20/30 snapshots if beyond loop range already covered above */
    }
    return {years:years, snapshots:snapshots, finalBalance:balance};
  }

  function calc(){
    var savings=$('rw-savings'), withdraw=$('rw-withdraw'), returnPct=$('rw-return'), inflPct=$('rw-inflation'), maxYears=$('rw-years');
    if(!FSL.valid(savings>0,'rw-err','Enter your current retirement savings.'))return;
    if(!FSL.valid(withdraw>0,'rw-err','Enter an annual withdrawal amount.'))return;
    var returnRate=returnPct/100, inflation=inflPct/100;

    var result=simulate(savings,withdraw,returnRate,inflation,Math.max(maxYears,30));
    var firstYearRate=withdraw/savings*100;
    var safeWithdraw=savings*0.04;

    FSL.set('rw-lasts', result.years===null ? 'More than '+Math.max(maxYears,30)+' years' : result.years+' years');
    FSL.set('rw-rate', FSL.pct(firstYearRate,2));
    FSL.set('rw-4pct', M(safeWithdraw)+'/year');
    FSL.set('rw-balance10', M(result.snapshots[10]!==undefined?result.snapshots[10]:0));
    FSL.set('rw-balance20', M(result.snapshots[20]!==undefined?result.snapshots[20]:0));
    FSL.set('rw-balance30', M(result.snapshots[30]!==undefined?result.snapshots[30]:0));
    FSL.show('rw-results','grid');

    var n=[];
    if(firstYearRate>4.5) n.push('Your first-year withdrawal rate of '+FSL.pct(firstYearRate,1)+' is above the traditional "4% rule" guideline, which raises the risk of running out of money over a 30-year retirement.');
    else if(firstYearRate<=4) n.push('Your first-year withdrawal rate of '+FSL.pct(firstYearRate,1)+' is at or below the traditional "4% rule" guideline, which was designed to have a high probability of lasting at least 30 years.');
    n.push('This model grows the balance by a fixed annual return and increases the withdrawal with inflation every year — it does not account for market volatility, sequence-of-returns risk, taxes, Social Security, or required minimum distributions, all of which affect how long money actually lasts.');
    n.push('A fixed assumed return is a simplification: real portfolios have good and bad years, and a few bad years early in retirement can deplete savings faster than this average-return model shows.');
    FSL.set('rw-note',n.join(' '));
  }
  FSL.el('rw-go').addEventListener('click',calc); FSL.bind('fs-rw',calc);
  calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   CHILD TAX CREDIT CALCULATOR (2026)
───────────────────────────────────────────── */
function fs_calc2_child_tax_credit() { ?>
<div class="fsc-wrap" id="fs-ctc">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_select( 'ctc-status', 'Filing status', [ 'single' => 'Single / Head of household', 'mfj' => 'Married filing jointly', 'mfs' => 'Married filing separately' ], 'single' ); ?>
    <?php fsl_field( 'ctc-magi', 'Modified adjusted gross income ($)', 150000, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <?php fsl_field( 'ctc-kids', 'Qualifying children under 17', 2, [ 'min' => 0, 'max' => 15, 'step' => 1, 'hint' => 'A child must be under 17 at the end of the year, have a Social Security number, and be claimed as your dependent.' ] ); ?>
    <?php fsl_field( 'ctc-other', 'Other dependents (not under 17)', 0, [ 'min' => 0, 'max' => 15, 'step' => 1, 'hint' => 'Older children, elderly parents, or other qualifying relatives you claim as dependents — these get a smaller $500 credit instead.' ] ); ?>
    <?php fsl_field( 'ctc-earned', 'Earned income ($, for the refundable portion)', 150000, [ 'min' => 0, 'step' => 1000, 'hint' => 'Wages, salary and self-employment income. Used to estimate how much of the credit could be refundable if it exceeds your tax owed.' ] ); ?>
    <button type="button" class="fsc-btn" id="ctc-go">Calculate Child Tax Credit</button>
    <p class="fsl-error" id="ctc-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="ctc-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'ctc-total', 'Total credit before phase-out', 'primary' );
      fsl_card( 'ctc-reduction', 'Phase-out reduction' );
      fsl_card( 'ctc-final', 'Your estimated credit', 'gold' );
      fsl_card( 'ctc-refundable', 'Maximum refundable portion (ACTC)' );
      ?>
    </div>
    <p class="fsl-note" id="ctc-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;

  function calc(){
    var status=FSL.el('ctc-status').value, magi=$('ctc-magi'), kids=$('ctc-kids'), other=$('ctc-other'), earned=$('ctc-earned');
    if(!FSL.valid(kids+other>0,'ctc-err','Enter at least one qualifying child or dependent.'))return;

    var perChild=2200, perOther=500;
    var totalCredit = kids*perChild + other*perOther;

    /* Phase-out: $50 reduction per $1,000 (or part of $1,000) of MAGI above the threshold */
    var threshold = status==='mfj' ? 400000 : (status==='mfs' ? 200000 : 200000);
    var over = Math.max(magi-threshold, 0);
    var steps = Math.ceil(over/1000);
    var reduction = Math.min(steps*50, totalCredit);
    var finalCredit = Math.max(totalCredit-reduction, 0);

    /* ACTC: lesser of (up to $1,700 per qualifying child) or 15% of earned income above $2,500 */
    var actcCap = kids*1700;
    var earnedBase = Math.max(earned-2500, 0)*0.15;
    var refundable = Math.min(actcCap, earnedBase, finalCredit);

    FSL.set('ctc-total',M(totalCredit)); FSL.set('ctc-reduction',M(reduction));
    FSL.set('ctc-final',M(finalCredit)); FSL.set('ctc-refundable',M(refundable));
    FSL.show('ctc-results','grid');

    var n=[];
    if(reduction>0) n.push('Your MAGI is '+M(over)+' above the '+M(threshold)+' threshold, which reduces the credit by $50 for every $1,000 (or part of $1,000) over that line.');
    else n.push('Your MAGI is under the '+M(threshold)+' threshold, so the full credit applies with no phase-out reduction.');
    n.push('Up to $1,700 per qualifying child can be refundable through the Additional Child Tax Credit (ACTC) even if you owe no tax, limited to 15% of earned income above $2,500.');
    n.push('This is a 2026 estimate using the $2,200 per-child credit made permanent by the One Big Beautiful Bill Act. Your actual credit depends on your full tax return — confirm with IRS Schedule 8812.');
    FSL.set('ctc-note',n.join(' '));
  }
  FSL.el('ctc-go').addEventListener('click',calc); FSL.bind('fs-ctc',calc);
  calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   SALT DEDUCTION CALCULATOR (2026)
───────────────────────────────────────────── */
function fs_calc2_salt_deduction() { ?>
<div class="fsc-wrap" id="fs-salt">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_select( 'salt-status', 'Filing status', [ 'single' => 'Single', 'mfj' => 'Married filing jointly', 'hoh' => 'Head of household', 'mfs' => 'Married filing separately' ], 'single' ); ?>
    <?php fsl_field( 'salt-magi', 'Modified adjusted gross income ($)', 350000, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <?php fsl_field( 'salt-property', 'Property tax paid ($)', 9000, [ 'min' => 0, 'step' => 100 ] ); ?>
    <?php fsl_field( 'salt-income-sales', 'State/local income OR sales tax paid ($)', 12000, [ 'min' => 0, 'step' => 100, 'hint' => 'Use whichever is larger for your state — most people use state income tax paid; residents of no-income-tax states use sales tax instead.' ] ); ?>
    <label class="fsl-check"><input type="checkbox" id="salt-itemize" checked> <span>I am itemizing deductions (not taking the standard deduction)</span></label>
    <button type="button" class="fsc-btn" id="salt-go">Calculate SALT Deduction</button>
    <p class="fsl-error" id="salt-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="salt-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'salt-combined', 'Your total SALT paid' );
      fsl_card( 'salt-cap', '2026 SALT cap for your income' );
      fsl_card( 'salt-deduction', 'Your actual SALT deduction', 'primary' );
      fsl_card( 'salt-lost', 'Amount capped out (not deductible)', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="salt-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;

  function calc(){
    var magi=$('salt-magi'), property=$('salt-property'), incomeSales=$('salt-income-sales');
    var itemizing=FSL.el('salt-itemize').checked;
    var combined = property+incomeSales;

    /* 2026 SALT cap: $40,400 ($20,200 MFS), phased down 30% of MAGI over $505,000 (not below $10,000 / $5,000 MFS) */
    var status=FSL.el('salt-status').value;
    var baseCap = status==='mfs' ? 20200 : 40400;
    var floor = status==='mfs' ? 5000 : 10000;
    var phaseStart = 505000;
    var cap = baseCap;
    if(magi>phaseStart){
      var reduction = (magi-phaseStart)*0.30;
      cap = Math.max(baseCap-reduction, floor);
    }

    var deduction = itemizing ? Math.min(combined, cap) : 0;
    var lost = itemizing ? Math.max(combined-cap, 0) : combined;

    FSL.set('salt-combined',M(combined)); FSL.set('salt-cap',M(cap));
    FSL.set('salt-deduction',M(deduction)); FSL.set('salt-lost',M(lost));
    FSL.show('salt-results','grid');

    var n=[];
    if(!itemizing) n.push('You are not itemizing, so none of this is deducted — you are taking the standard deduction instead, which may still be the better choice. Compare both before deciding.');
    else if(magi>phaseStart) n.push('Your MAGI is above the $505,000 phase-out start, so your cap is reduced by 30% of the amount over that line, down to a floor of '+M(floor)+'.');
    else n.push('Your MAGI is under the $505,000 phase-out threshold, so you get the full '+M(baseCap)+' cap.');
    n.push('The SALT deduction covers property tax plus either state/local income tax or sales tax (not both) — use whichever is larger for your situation.');
    n.push('This reflects the 2026 SALT cap of $40,400 under the One Big Beautiful Bill Act. Confirm your state\'s specific rules and your total itemized deductions against the standard deduction before filing.');
    FSL.set('salt-note',n.join(' '));
  }
  FSL.el('salt-go').addEventListener('click',calc); FSL.bind('fs-salt',calc);
  FSL.el('salt-itemize').addEventListener('change',calc);
  calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   SENIOR $6,000 BONUS DEDUCTION CALCULATOR (2026)
───────────────────────────────────────────── */
function fs_calc2_senior_deduction() { ?>
<div class="fsc-wrap" id="fs-senior">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_select( 'senior-status', 'Filing status', [ 'single' => 'Single', 'mfj' => 'Married filing jointly', 'hoh' => 'Head of household', 'mfs' => 'Married filing separately', 'qss' => 'Qualifying surviving spouse' ], 'single' ); ?>
    <?php fsl_check( 'senior-self', 'I am 65 or older by December 31, 2026', true ); ?>
    <div id="senior-spouse-wrap" style="display:none">
      <?php fsl_check( 'senior-spouse', 'My spouse is also 65 or older by December 31, 2026', false ); ?>
    </div>
    <?php fsl_field( 'senior-magi', 'Modified adjusted gross income ($)', 60000, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <button type="button" class="fsc-btn" id="senior-go">Calculate My Senior Deduction</button>
    <p class="fsl-error" id="senior-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="senior-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'senior-base', 'Full senior bonus deduction before phase-out' );
      fsl_card( 'senior-reduction', 'Phase-out reduction' );
      fsl_card( 'senior-final', 'Your actual senior bonus deduction', 'primary' );
      fsl_card( 'senior-savings', 'Estimated tax savings (at your bracket)', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="senior-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;

  function toggleSpouse(){
    var status=FSL.el('senior-status').value;
    FSL.el('senior-spouse-wrap').style.display = (status==='mfj'||status==='qss') ? '' : 'none';
    if(status!=='mfj' && status!=='qss') FSL.el('senior-spouse').checked=false;
  }

  function calc(){
    var magi=$('senior-magi');
    var status=FSL.el('senior-status').value;
    var self65=FSL.el('senior-self').checked;
    var spouse65=(status==='mfj'||status==='qss') && FSL.el('senior-spouse').checked;
    var qualifyingPeople = (self65?1:0) + (spouse65?1:0);
    var base = qualifyingPeople * 6000;

    /* OBBBA, 26 U.S.C. §151(d)(5)(C): $6,000 per qualifying person 65+, tax years 2025-2028.
       Phases out 6% of MAGI over $75,000 (single/HOH/MFS) or $150,000 (MFJ/QSS), per person's
       own threshold -- so a joint return with both spouses 65+ doesn't fully phase out until
       a higher combined MAGI than a return with only one qualifying spouse. */
    var perPersonThreshold = (status==='mfj'||status==='qss') ? 150000 : 75000;
    var excess = Math.max(magi - perPersonThreshold, 0);
    var reduction = qualifyingPeople>0 ? Math.min(excess*0.06, base) : 0;
    var finalDeduction = Math.max(base - reduction, 0);

    var bracket = magi<=12400?0.10 : magi<=50400?0.12 : magi<=105700?0.22 : magi<=201775?0.24 : magi<=256225?0.32 : magi<=640600?0.35 : 0.37;
    var savings = finalDeduction * bracket;

    FSL.set('senior-base',M(base)); FSL.set('senior-reduction',M(reduction));
    FSL.set('senior-final',M(finalDeduction)); FSL.set('senior-savings',M(savings));
    FSL.show('senior-results','grid');

    var n=[];
    if(qualifyingPeople===0){ n.push('Neither filer is 65 or older, so this deduction does not apply to you yet.'); }
    else {
      n.push('This is '+qualifyingPeople+' qualifying person'+(qualifyingPeople>1?'s':'')+' x $6,000 = '+M(base)+' before any phase-out.');
      if(excess>0) n.push('Your MAGI is '+M(excess)+' over the '+M(perPersonThreshold)+' threshold, which reduces the deduction by 6% of that amount ('+M(reduction)+'), down to '+M(finalDeduction)+'.');
      else n.push('Your MAGI is under the '+M(perPersonThreshold)+' phase-out threshold, so you get the full amount with no reduction.');
      n.push('This is a separate, additional deduction on top of the regular extra standard deduction that filers 65+ already receive -- it does not replace it. It applies for tax years 2025 through 2028 only and is scheduled to expire after 2028 unless Congress extends it.');
    }
    FSL.set('senior-note',n.join(' '));
  }
  FSL.el('senior-go').addEventListener('click',calc);
  FSL.el('senior-status').addEventListener('change',function(){toggleSpouse();calc();});
  FSL.el('senior-self').addEventListener('change',calc);
  FSL.bind('fs-senior',calc);
  toggleSpouse(); calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   AUTO LOAN INTEREST DEDUCTION CALCULATOR (OBBBA, 2025-2028)
───────────────────────────────────────────── */
function fs_calc2_auto_interest_deduction() { ?>
<div class="fsc-wrap" id="fs-autoint">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_select( 'autoint-status', 'Filing status', [ 'single' => 'Single', 'mfj' => 'Married filing jointly', 'hoh' => 'Head of household', 'mfs' => 'Married filing separately', 'qss' => 'Qualifying surviving spouse' ], 'single' ); ?>
    <?php fsl_field( 'autoint-interest', 'Auto loan interest paid this year ($)', 3500, [ 'min' => 0, 'step' => 50 ] ); ?>
    <?php fsl_field( 'autoint-magi', 'Modified adjusted gross income ($)', 85000, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <?php fsl_check( 'autoint-new', 'The vehicle was new (not used) when I bought it', true ); ?>
    <?php fsl_check( 'autoint-personal', 'I use the vehicle for personal use (not a business/fleet)', true ); ?>
    <?php fsl_check( 'autoint-assembly', 'Final assembly was in the United States', true ); ?>
    <?php fsl_check( 'autoint-weight', 'Gross vehicle weight rating is under 14,000 lbs', true ); ?>
    <?php fsl_check( 'autoint-date', 'The loan originated after December 31, 2024', true ); ?>
    <button type="button" class="fsc-btn" id="autoint-go">Calculate My Deduction</button>
    <p class="fsl-error" id="autoint-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="autoint-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'autoint-eligible', 'Interest eligible before phase-out (capped at $10,000)' );
      fsl_card( 'autoint-reduction', 'Phase-out reduction' );
      fsl_card( 'autoint-final', 'Your actual deduction', 'primary' );
      fsl_card( 'autoint-savings', 'Estimated tax savings (at your bracket)', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="autoint-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;

  function calc(){
    var interest=$('autoint-interest'), magi=$('autoint-magi');
    var status=FSL.el('autoint-status').value;
    var ok = FSL.el('autoint-new').checked && FSL.el('autoint-personal').checked && FSL.el('autoint-assembly').checked && FSL.el('autoint-weight').checked && FSL.el('autoint-date').checked;

    /* OBBBA auto loan interest deduction: above-the-line, cap $10,000/yr interest,
       tax years 2025-2028. Phases out $200 per $1,000 (or part) of MAGI over
       $100,000 single/HoH/MFS or $200,000 MFJ/QSS -- fully gone at $150,000 /
       $250,000 respectively. Requires: new (not used) vehicle, personal use,
       final assembly in the US, GVWR under 14,000 lbs, loan after 12/31/2024,
       and the VIN reported on Schedule 1-A. */
    var threshold = (status==='mfj'||status==='qss') ? 200000 : 100000;
    var capped = Math.min(interest, 10000);
    var excess = Math.max(magi - threshold, 0);
    var steps = Math.ceil(excess/1000);
    var reduction = ok ? Math.min(steps*200, capped) : capped;
    var finalDeduction = ok ? Math.max(capped - reduction, 0) : 0;

    var bracket = magi<=12400?0.10 : magi<=50400?0.12 : magi<=105700?0.22 : magi<=201775?0.24 : magi<=256225?0.32 : magi<=640600?0.35 : 0.37;
    var savings = finalDeduction * bracket;

    FSL.set('autoint-eligible',M(capped)); FSL.set('autoint-reduction',M(ok?reduction:capped));
    FSL.set('autoint-final',M(finalDeduction)); FSL.set('autoint-savings',M(savings));
    FSL.show('autoint-results','grid');

    var n=[];
    if(!ok){ n.push('Based on the boxes you unchecked, this vehicle/loan does not meet the eligibility rules (new U.S.-assembled personal vehicle under 14,000 lbs GVWR, loan originated after December 31, 2024), so none of the interest qualifies for this deduction -- though it may still be deductible elsewhere if used for business.'); }
    else {
      n.push('Interest paid, capped at the $10,000/year maximum: '+M(capped)+'.');
      if(excess>0) n.push('Your MAGI is '+M(excess)+' over the '+M(threshold)+' threshold ('+steps+' step'+(steps>1?'s':'')+' of $1,000), reducing the deduction by $200 per step ('+M(Math.min(steps*200,capped))+'), down to '+M(finalDeduction)+'.');
      else n.push('Your MAGI is under the '+M(threshold)+' phase-out threshold, so none of it is reduced.');
      n.push('This is an above-the-line deduction -- you can claim it whether you itemize or take the standard deduction -- and it applies for tax years 2025 through 2028 only. Your lender will report the interest on Form 1098-VLI, and you report the vehicle\'s VIN on Schedule 1-A.');
    }
    FSL.set('autoint-note',n.join(' '));
  }
  FSL.el('autoint-go').addEventListener('click',calc);
  ['autoint-status','autoint-new','autoint-personal','autoint-assembly','autoint-weight','autoint-date'].forEach(function(id){FSL.el(id).addEventListener('change',calc);});
  FSL.bind('fs-autoint',calc);
  calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   BNPL CREDIT SCORE IMPACT CALCULATOR (2026)
───────────────────────────────────────────── */
function fs_calc2_bnpl_credit_impact() { ?>
<div class="fsc-wrap" id="fs-bnpl">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_select( 'bnpl-provider', 'Main BNPL provider you use', [ 'affirm' => 'Affirm', 'klarna-monthly' => 'Klarna (monthly financing plan)', 'klarna-pay4' => 'Klarna Pay in 4 / Pay in 30', 'afterpay' => 'Afterpay', 'paypal' => 'PayPal Pay in 4', 'other' => 'Other / not sure' ], 'affirm' ); ?>
    <?php fsl_field( 'bnpl-loans', 'Number of BNPL loans currently open', 2, [ 'min' => 0, 'max' => 20, 'step' => 1 ] ); ?>
    <?php fsl_check( 'bnpl-ontime', 'I have paid all my BNPL installments on time so far', true ); ?>
    <?php fsl_select( 'bnpl-lender-check', 'Are you about to apply for a mortgage, auto loan or other credit?', [ 'no' => 'No, just curious', 'soon' => 'Yes, in the next few months' ], 'no' ); ?>
    <button type="button" class="fsc-btn" id="bnpl-go">Estimate My Credit Impact</button>
    <p class="fsl-error" id="bnpl-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="bnpl-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'bnpl-reports', 'Does this provider currently report to the bureaus?' );
      fsl_card( 'bnpl-estimate', 'Estimated score effect of your open loans', 'primary' );
      fsl_card( 'bnpl-risk', 'Biggest risk to watch', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="bnpl-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num;

  /* As of late 2026: Affirm reports most loan data to Experian and TransUnion.
     Klarna reports its longer-term "monthly financing" product, but NOT Pay in
     4, Pay in 30, or Pay in Full. Afterpay and PayPal Pay in 4 do not broadly
     report short-term installment data to the bureaus as of this writing.
     Even when a provider reports, your score only moves if your lender pulls
     a FICO 10 BNPL / FICO 10 T BNPL or VantageScore 4.0 model that reads it --
     older FICO 8/9 and most mortgage-scoring models used today ignore it
     entirely. This is why BNPL's real-world score impact is inconsistent. */
  var reportsData = {
    'affirm':         { reports: true,  label: 'Likely yes -- Affirm reports most loans to Experian and TransUnion.' },
    'klarna-monthly':  { reports: true,  label: 'Yes, if it is the longer-term "Monthly financing" product.' },
    'klarna-pay4':     { reports: false, label: 'Mostly no as of this writing -- Klarna\'s Pay in 4 / Pay in 30 historically was not reported, though some 2026 reporting suggests this is starting to expand. Check your own report to be sure.' },
    'afterpay':        { reports: false, label: 'No, not broadly, as of this writing -- the clearest holdout among major providers.' },
    'paypal':          { reports: false, label: 'PayPal has not stated that it reports Pay in 4 data, but this is not independently confirmed either way -- check your own report.' },
    'other':           { reports: false, label: 'Unknown -- check your own credit report at each bureau to see.' }
  };

  function calc(){
    var provider=FSL.el('bnpl-provider').value, loans=$('bnpl-loans'), ontime=FSL.el('bnpl-ontime').checked;
    var soon = FSL.el('bnpl-lender-check').value==='soon';
    var info = reportsData[provider];

    FSL.set('bnpl-reports', info.label);

    var estimate;
    if(!info.reports || loans===0){
      estimate = 'Little to none -- this provider\'s data most likely is not reaching your score yet.';
    } else {
      var perLoan = ontime ? 10 : -25;
      var totalPts = Math.min(Math.abs(perLoan*Math.min(loans,3)), 30);
      estimate = ontime
        ? ('Roughly +0 to -'+Math.round(totalPts/2)+' points if a lender pulls a BNPL-aware score -- FICO\'s own simulated study (modeling scores on existing Affirm data, not live production scores) found about 10 points of movement for most users opening a new BNPL loan, and multiple open loans are grouped together rather than stacked one by one.')
        : ('Potentially -'+totalPts+' points or more if a lender pulls a BNPL-aware score and sees missed payments -- late BNPL payments can be reported as delinquencies just like any other account.');
    }
    FSL.set('bnpl-estimate', estimate);

    var risk;
    if(soon && info.reports) risk = 'Before applying, know that most mortgage lenders still use older FICO models (2, 4, or 5) that do NOT read BNPL data at all -- but some auto and personal-loan lenders have already adopted BNPL-aware scores. Ask your lender which score they pull.';
    else if(!ontime) risk = 'A missed BNPL payment can be sent to collections and reported like any other delinquent account, which hurts far more than simply having the loan open.';
    else risk = 'The real risk is stacking several BNPL loans across different retailers at once -- it is easy to lose track of total monthly obligations even if each individual payment looks small.';
    FSL.set('bnpl-risk', risk);
    FSL.show('bnpl-results','grid');
  }
  FSL.el('bnpl-go').addEventListener('click',calc);
  ['bnpl-provider','bnpl-ontime','bnpl-lender-check'].forEach(function(id){FSL.el(id).addEventListener('change',calc);});
  FSL.bind('fs-bnpl',calc);
  calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   ESTIMATED TAX UNDERPAYMENT PENALTY CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_estimated_tax_penalty() { ?>
<div class="fsc-wrap" id="fs-estpen">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'estpen-tax', 'Your estimated total tax for this year ($)', 18000, [ 'min' => 0, 'step' => 100 ] ); ?>
    <?php fsl_field( 'estpen-paid', 'Total withholding + estimated payments made so far ($)', 12000, [ 'min' => 0, 'step' => 100 ] ); ?>
    <?php fsl_field( 'estpen-lastyear-tax', 'Last year\'s total tax (from last year\'s return) ($)', 15000, [ 'min' => 0, 'step' => 100 ] ); ?>
    <?php fsl_field( 'estpen-lastyear-agi', 'Last year\'s adjusted gross income (AGI) ($)', 95000, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <button type="button" class="fsc-btn" id="estpen-go">Check My Safe Harbor</button>
    <p class="fsl-error" id="estpen-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="estpen-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'estpen-target', 'Your safe harbor target' );
      fsl_card( 'estpen-status', 'Are you covered?', 'primary' );
      fsl_card( 'estpen-shortfall', 'Shortfall if any' );
      fsl_card( 'estpen-penalty', 'Rough penalty estimate', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="estpen-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;

  function calc(){
    var tax=$('estpen-tax'), paid=$('estpen-paid'), lyTax=$('estpen-lastyear-tax'), lyAgi=$('estpen-lastyear-agi');

    /* Safe harbor: withholding + timely estimated payments must reach the
       SMALLER of 90% of this year's tax, or 100% of last year's tax
       (110% if last year's AGI was over $150,000). Owing under $1,000
       after withholding/credits also avoids the penalty entirely. */
    var pct = lyAgi > 150000 ? 1.10 : 1.00;
    var currentYearTarget = tax * 0.90;
    var priorYearTarget = lyTax * pct;
    var target = Math.min(currentYearTarget, priorYearTarget);
    var owedAfterPaid = tax - paid;
    var covered = paid >= target || owedAfterPaid < 1000;
    var shortfall = covered ? 0 : (target - paid);

    /* Rough illustrative estimate only: IRS rate is the federal short-term
       rate + 3 points, set quarterly, applied per-installment on a
       daily-interest basis via Form 2210 -- not a single flat rate for the
       whole year. We assume a representative ~7% annual rate and an average
       of roughly 6 months the shortfall was outstanding across the year's
       installments, purely to give an illustrative ballpark. */
    var assumedRate = 0.07;
    var roughPenalty = shortfall * assumedRate * 0.5;

    FSL.set('estpen-target', M(target) + ' (the smaller of ' + M(currentYearTarget) + ' [90% this year] or ' + M(priorYearTarget) + ' [' + (pct===1.10?'110%':'100%') + ' last year])');
    FSL.set('estpen-status', covered ? 'Yes — you are covered by the safe harbor.' : 'No — you are short of the safe harbor target.');
    FSL.set('estpen-shortfall', M(shortfall));
    FSL.set('estpen-penalty', covered ? '$0' : ('roughly ' + M(roughPenalty) + ' (illustrative only)'));
    FSL.show('estpen-results','grid');

    var n=[];
    if(covered) n.push('Based on what you entered, your withholding and payments so far meet the safe harbor, so you should not owe an underpayment penalty even if you owe additional tax when you file.');
    else {
      n.push('You are roughly ' + M(shortfall) + ' short of the safe harbor target. Making a Q4 estimated payment (due January 15 for the prior tax year) to close some or all of this gap reduces or eliminates the penalty for the period it covers.');
      n.push('The penalty estimate above is illustrative only — the IRS calculates it per quarterly installment, using the federal short-term rate plus 3 percentage points (set quarterly and subject to change), applied as simple daily interest on each period\'s specific shortfall via Form 2210. Your real number could be meaningfully higher or lower depending on which quarter(s) you underpaid and the actual rate in effect each quarter.');
    }
    n.push('Owing less than $1,000 in tax after subtracting withholding and credits avoids the penalty regardless of the safe harbor percentages.');
    FSL.set('estpen-note', n.join(' '));
  }
  FSL.el('estpen-go').addEventListener('click',calc);
  FSL.bind('fs-estpen',calc);
  calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   SECURE 2.0 STUDENT LOAN 401(k) MATCH CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_secure2_student_loan_match() { ?>
<div class="fsc-wrap" id="fs-slmatch">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'slmatch-salary', 'Your annual salary ($)', 65000, [ 'min' => 0, 'step' => 1000 ] ); ?>
    <?php fsl_field( 'slmatch-loan-payments', 'Your qualified student loan payments this year ($)', 4800, [ 'min' => 0, 'step' => 100 ] ); ?>
    <?php fsl_field( 'slmatch-pct', 'Your employer\'s match rate (%)', 50, [ 'min' => 0, 'max' => 100, 'step' => 1, 'hint' => 'Example: a "50% match up to 5% of salary" plan enters 50 here and 5 below.' ] ); ?>
    <?php fsl_field( 'slmatch-cap-pct', 'Match cap, as % of salary', 5, [ 'min' => 0, 'max' => 100, 'step' => 0.5 ] ); ?>
    <?php fsl_field( 'slmatch-years', 'Years until you expect to retire', 25, [ 'min' => 1, 'max' => 50, 'step' => 1 ] ); ?>
    <?php fsl_field( 'slmatch-return', 'Expected average annual return (%)', 7, [ 'min' => 0, 'max' => 15, 'step' => 0.5 ] ); ?>
    <button type="button" class="fsc-btn" id="slmatch-go">Estimate My Match</button>
    <p class="fsl-error" id="slmatch-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="slmatch-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'slmatch-cap-dollar', 'Your plan\'s maximum match this year' );
      fsl_card( 'slmatch-this-year', 'Estimated match you actually earn this year', 'primary' );
      fsl_card( 'slmatch-future', 'If invested, roughly worth at retirement', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="slmatch-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;

  function calc(){
    var salary=$('slmatch-salary'), payments=$('slmatch-loan-payments'), pct=$('slmatch-pct')/100, capPct=$('slmatch-cap-pct')/100, years=$('slmatch-years'), ret=$('slmatch-return')/100;

    /* SECURE 2.0 Section 110: an employer MAY (not must) treat qualified
       student loan payments as if they were 401(k) deferrals for matching
       purposes. The match still follows the plan's normal match formula and
       cap -- it is not a new, separate benefit amount, just a new way to
       EARN the existing match without contributing to the 401(k) yourself.
       The match itself goes into your retirement account, not toward your
       loan balance. */
    var capDollar = salary * capPct;
    var matchFromPayments = Math.min(payments, salary*capPct/pct) * pct;
    var thisYearMatch = Math.min(matchFromPayments, capDollar);
    var futureValue = thisYearMatch * Math.pow(1+ret, years);

    FSL.set('slmatch-cap-dollar', M(capDollar) + ' per year (your plan\'s match cap)');
    FSL.set('slmatch-this-year', M(thisYearMatch));
    FSL.set('slmatch-future', M(futureValue) + ' (assuming this single year\'s match alone grows untouched)');
    FSL.show('slmatch-results','grid');

    var n=[];
    if(thisYearMatch >= capDollar) n.push('Your loan payments alone are enough to earn your plan\'s full match cap for the year -- the same as if you had contributed enough to your 401(k) directly, except the money went to your loans instead.');
    else n.push('Your loan payments earn you ' + M(thisYearMatch) + ' of a possible ' + M(capDollar) + ' match cap. You could still contribute directly to your 401(k) on top of this to capture the rest of the match, if your plan allows combining both.');
    n.push('This benefit is optional for employers -- not every 401(k) plan offers it. Ask your HR or plan administrator whether your specific plan has adopted SECURE 2.0 Section 110 student loan matching, since the law only permits it, it does not require it.');
    n.push('The match goes into your retirement account as an employer contribution, not toward paying down your loan balance -- the two benefits are separate.');
    FSL.set('slmatch-note', n.join(' '));
  }
  FSL.el('slmatch-go').addEventListener('click',calc);
  FSL.bind('fs-slmatch',calc);
  calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   2027 401(K) & IRA CONTRIBUTION LIMIT CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_2027_contribution_limits() { ?>
<div class="fsc-wrap" id="fs-contrib27">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'contrib27-age', 'Your age by December 31, 2027', 52, [ 'min' => 18, 'max' => 100, 'step' => 1 ] ); ?>
    <?php fsl_field( 'contrib27-wages', 'Your prior-year (2026) FICA wages ($)', 90000, [ 'min' => 0, 'step' => 1000, 'hint' => 'Box 3 of your W-2 -- this determines whether your catch-up must go to Roth.' ] ); ?>
    <?php fsl_select( 'contrib27-plan', 'Plan type', [ '401k' => '401(k) / 403(b) / governmental 457 / TSP', 'ira' => 'Traditional or Roth IRA' ], '401k' ); ?>
    <?php fsl_field( 'contrib27-pay-periods', 'Pay periods remaining in the year', 26, [ 'min' => 1, 'max' => 52, 'step' => 1, 'hint' => 'E.g. 26 for biweekly starting Jan 1, 12 for monthly, or fewer if you are starting mid-year.' ] ); ?>
    <div id="contrib27-ira-fields" style="display:none">
      <?php fsl_select( 'contrib27-filing', 'Filing status', [ 'single' => 'Single / Head of household', 'mfj' => 'Married filing jointly', 'mfs' => 'Married filing separately' ], 'single' ); ?>
      <?php fsl_field( 'contrib27-magi', 'Your 2027 modified AGI ($)', 100000, [ 'min' => 0, 'step' => 1000, 'hint' => 'Used only to check Roth IRA eligibility -- not needed for the contribution limit itself.' ] ); ?>
    </div>
    <button type="button" class="fsc-btn" id="contrib27-go">Calculate My 2027 Limit</button>
    <p class="fsl-error" id="contrib27-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="contrib27-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'contrib27-base', 'Base 2027 limit (under 50)' );
      fsl_card( 'contrib27-catchup', 'Your catch-up amount' );
      fsl_card( 'contrib27-total', 'Your total 2027 limit', 'primary' );
      fsl_card( 'contrib27-roth-flag', 'Catch-up tax treatment', 'gold' );
      fsl_card( 'contrib27-per-paycheck', 'Needed per paycheck to hit the max', 'primary' );
      fsl_card( 'contrib27-monthly', 'Needed per month to hit the max' );
      ?>
    </div>
    <div id="contrib27-roth-elig-wrap" style="display:none">
      <?php fsl_card( 'contrib27-roth-elig', 'Roth IRA eligibility at your income' ); ?>
    </div>
    <p class="fsl-note" id="contrib27-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;

  function toggleIraFields(){
    var plan = FSL.el('contrib27-plan').value;
    FSL.el('contrib27-ira-fields').style.display = plan === 'ira' ? '' : 'none';
  }

  function calc(){
    var age=$('contrib27-age'), wages=$('contrib27-wages'), plan=FSL.el('contrib27-plan').value;
    var filing = FSL.el('contrib27-filing').value, magi = $('contrib27-magi');
    var payPeriods = Math.max($('contrib27-pay-periods'), 1);

    /* IRS Notice 2026-66 (Nov 12, 2026), effective tax year 2027:
       401(k)/403(b)/gov 457/TSP base: $25,500. Age 50+ catch-up: $8,500
       (total $34,000). Age 60-63 "super" catch-up (SECURE 2.0): $11,750
       (total $37,250) -- note this higher tier applies ONLY to ages 60,
       61, 62 and 63, reverting to the regular $8,500 catch-up at 64+.
       IRA: base $7,500 (unchanged), catch-up $1,100 (unchanged), total
       $8,600 for 50+, no special 60-63 tier for IRAs.
       Mandatory Roth catch-up: anyone whose PRIOR-YEAR FICA wages (not
       current-year gross income) exceeded $155,000 must have their
       entire catch-up contribution treated as Roth (after-tax), not
       pre-tax -- a frequently-missed 2026+ requirement under SECURE 2.0
       Section 603 for 401(k)/403(b)/457 plans. */
    var base, catchup = 0, tier = '';
    if (plan === '401k') {
      base = 25500;
      if (age >= 60 && age <= 63) { catchup = 11750; tier = 'the age 60-63 "super" catch-up'; }
      else if (age >= 50) { catchup = 8500; tier = 'the standard age-50+ catch-up'; }
    } else {
      base = 7500;
      if (age >= 50) { catchup = 1100; tier = 'the IRA age-50+ catch-up (no special 60-63 tier for IRAs)'; }
    }
    var total = base + catchup;
    var rothRequired = plan === '401k' && catchup > 0 && wages > 155000;

    FSL.set('contrib27-base', M(base));
    FSL.set('contrib27-catchup', catchup > 0 ? (M(catchup) + ' (' + tier + ')') : '$0 (not yet eligible for a catch-up)');
    FSL.set('contrib27-total', M(total));
    FSL.set('contrib27-roth-flag', catchup === 0 ? 'Not applicable' : (rothRequired ? 'Must be Roth (after-tax)' : 'Pre-tax or Roth, your choice'));

    /* Per-paycheck and per-month pacing, based on how many pay periods
       remain in the year -- most "limit" pages stop at the annual
       dollar figure and never show what that means per paycheck. */
    var perPaycheck = total / payPeriods;
    var perMonth = total / 12;
    FSL.set('contrib27-per-paycheck', M(perPaycheck) + ' per pay period (over ' + payPeriods + ' remaining)');
    FSL.set('contrib27-monthly', M(perMonth) + ' per month');
    FSL.show('contrib27-results','grid');

    /* 2027 Roth IRA MAGI phase-out (IRS Notice 2026-66): single/HoH
       $158,000-$173,000; MFJ $250,000-$260,000; MFS $0-$10,000 (not
       inflation-adjusted). Below the range: full $7,500/$8,600
       contribution allowed. Within range: reduced amount. Above: $0
       direct Roth IRA contribution (a "backdoor" Roth may still be
       possible but is a separate topic). */
    if (plan === 'ira') {
      FSL.el('contrib27-roth-elig-wrap').style.display = '';
      var lo, hi;
      if (filing === 'mfj') { lo = 250000; hi = 260000; }
      else if (filing === 'mfs') { lo = 0; hi = 10000; }
      else { lo = 158000; hi = 173000; }
      var elig;
      if (magi < lo) elig = 'Full amount allowed (' + M(total) + ') -- your MAGI is under the ' + M(lo) + ' phase-out start.';
      else if (magi >= hi) elig = 'Not eligible to contribute directly to a Roth IRA at this income (MAGI at or above ' + M(hi) + '). A Traditional IRA (possibly non-deductible) or a "backdoor" Roth conversion may still be an option -- that is a separate strategy, not a direct contribution.';
      else elig = 'Partial contribution only -- your MAGI falls inside the ' + M(lo) + '-' + M(hi) + ' phase-out range, so your allowed Roth IRA contribution is reduced below ' + M(total) + '.';
      FSL.set('contrib27-roth-elig', elig);
    } else {
      FSL.el('contrib27-roth-elig-wrap').style.display = 'none';
    }

    var n = [];
    n.push('For 2027, the IRS set the ' + (plan==='401k' ? '401(k)/403(b)/457/TSP' : 'IRA') + ' base limit at ' + M(base) + '.');
    if (catchup > 0) n.push('At your age, you qualify for ' + tier + ' of ' + M(catchup) + ', bringing your total to ' + M(total) + '.');
    if (plan === '401k') {
      if (rothRequired) n.push('Because your 2026 FICA wages were over $155,000, IRS rules require your entire catch-up contribution to be made as Roth (after-tax) starting in 2027 -- you cannot elect pre-tax for the catch-up portion, even if the rest of your plan is pre-tax.');
      else if (catchup > 0) n.push('Your 2026 FICA wages were under the $155,000 threshold, so you can still choose pre-tax or Roth for your catch-up contribution.');
      if (age === 64) n.push('Note: the higher "super" catch-up only applies through age 63 -- at 64 you drop back to the standard catch-up amount.');
    }
    FSL.set('contrib27-note', n.join(' '));
  }
  FSL.el('contrib27-go').addEventListener('click',calc);
  FSL.el('contrib27-plan').addEventListener('change',function(){toggleIraFields();calc();});
  FSL.el('contrib27-filing').addEventListener('change',calc);
  FSL.el('contrib27-pay-periods').addEventListener('change',calc);
  FSL.bind('fs-contrib27',calc);
  toggleIraFields();
  calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   2027 STANDARD DEDUCTION & TAX BRACKET CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_2027_tax_brackets() { ?>
<div class="fsc-wrap" id="fs-brk27">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'brk27-income', 'Income before the standard deduction ($)', 85000, [ 'min' => 0, 'step' => 1000, 'hint' => 'Your gross income after any pre-tax deductions like 401(k) or HSA, but before the standard deduction.' ] ); ?>
    <?php fsl_select( 'brk27-status', 'Filing status', [ 'single' => 'Single', 'mfj' => 'Married filing jointly', 'mfs' => 'Married filing separately', 'hoh' => 'Head of household' ], 'single' ); ?>
    <?php fsl_check( 'brk27-65', 'I am 65 or older by January 1, 2028', false ); ?>
    <?php fsl_check( 'brk27-blind', 'I am legally blind', false ); ?>
    <div id="brk27-spouse-wrap" style="display:none">
      <?php fsl_check( 'brk27-65-sp', 'My spouse is 65 or older by January 1, 2028', false ); ?>
      <?php fsl_check( 'brk27-blind-sp', 'My spouse is legally blind', false ); ?>
    </div>
    <?php fsl_field( 'brk27-kids', 'Qualifying children under 17 (for Child Tax Credit estimate)', 0, [ 'min' => 0, 'max' => 15, 'step' => 1 ] ); ?>
    <button type="button" class="fsc-btn" id="brk27-go">Calculate my 2027 tax</button>
    <p class="fsl-error" id="brk27-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="brk27-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'brk27-std', 'Your 2027 standard deduction' );
      fsl_card( 'brk27-ti', 'Taxable income after deduction' );
      fsl_card( 'brk27-marg', 'Your marginal tax bracket' );
      fsl_card( 'brk27-eff', 'Your effective tax rate' );
      fsl_card( 'brk27-tax', 'Estimated 2027 federal tax', 'primary' );
      fsl_card( 'brk27-ctc', 'Estimated Child Tax Credit', 'gold' );
      ?>
    </div>
    <p class="fsl-note" id="brk27-note"></p>
  </div>
</div>
<div id="brk27-table-wrap" style="display:none;margin-top:1.5rem">
  <h3 class="fsl-h3">Your 2027 tax by bracket</h3>
  <div class="fsc-table-wrap" style="padding:0"><table class="fsc-table fsl-cmp"><thead><tr><th>Rate</th><th>Income in this bracket</th><th>Tax from this bracket</th></tr></thead><tbody id="brk27-table-body"></tbody></table></div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;

  /* Source: IRS Rev. Proc. 2026-35 (released Nov 5, 2026), tax year 2027.
     Brackets are stored as [top-of-bracket, rate]; the last entry's "top"
     is a placeholder (Infinity) representing the 37% top rate.
     Single, MFJ and HOH thresholds come directly from the IRS release.
     MFS is not published as a full table every year -- by long-standing
     IRS practice MFS brackets equal exactly half of the MFJ brackets at
     every tier (confirmed for the 10-32% tiers, where MFS = Single =
     half of MFJ here too), so the 35%/37% MFS thresholds below are
     derived as half of the published MFJ figures, not pulled verbatim
     from a published MFS table -- flagged here for anyone double-checking
     this file against Rev. Proc. 2026-35 directly. */
  var BRACKETS = {
    single: [ [12800,0.10], [52025,0.12], [109125,0.22], [208325,0.24], [264550,0.32], [661375,0.35], [Infinity,0.37] ],
    mfj:    [ [25600,0.10], [104050,0.12], [218250,0.22], [416650,0.24], [529100,0.32], [793650,0.35], [Infinity,0.37] ],
    hoh:    [ [18250,0.10], [69650,0.12], [109100,0.22], [208300,0.24], [264550,0.32], [661350,0.35], [Infinity,0.37] ],
    mfs:    [ [12800,0.10], [52025,0.12], [109125,0.22], [208325,0.24], [264550,0.32], [396825,0.35], [Infinity,0.37] ]
  };
  /* 2027 standard deduction: MFJ $33,200, Single/MFS $16,600, HOH $24,925.
     Additional amount for 65+/blind: $2,100 (single/HOH), $1,700 per
     married person (MFJ/MFS), each one stacking independently. */
  var STD = { single:16600, mfj:33200, mfs:16600, hoh:24925 };
  var ADD_SINGLE_HOH = 2100, ADD_MARRIED = 1700;
  var CTC_PER_CHILD = 2300;

  function toggleSpouse(){
    var status = FSL.el('brk27-status').value;
    FSL.el('brk27-spouse-wrap').style.display = (status === 'mfj' || status === 'mfs') ? '' : 'none';
  }

  function taxFor(ti, status){
    var b = BRACKETS[status], prev = 0, tax = 0;
    for (var i=0;i<b.length;i++){
      var top = b[i][0], rate = b[i][1];
      var amt = Math.max(Math.min(ti, top) - prev, 0);
      tax += amt * rate;
      if (ti <= top) break;
      prev = top;
    }
    return tax;
  }
  function marginalFor(ti, status){
    var b = BRACKETS[status];
    for (var i=0;i<b.length;i++){ if (ti <= b[i][0]) return b[i][1]; }
    return b[b.length-1][1];
  }

  function calc(){
    var income = $('brk27-income'), status = FSL.el('brk27-status').value;
    var kids = $('brk27-kids');
    if (!FSL.valid(income >= 0, 'brk27-err', 'Enter your income.')) return;

    var isMarried = (status === 'mfj' || status === 'mfs');
    var addPerPerson = isMarried ? ADD_MARRIED : ADD_SINGLE_HOH;
    var extras = 0;
    if (FSL.el('brk27-65').checked) extras += addPerPerson;
    if (FSL.el('brk27-blind').checked) extras += addPerPerson;
    if (isMarried) {
      if (FSL.el('brk27-65-sp').checked) extras += addPerPerson;
      if (FSL.el('brk27-blind-sp').checked) extras += addPerPerson;
    }
    var std = STD[status] + extras;
    var ti = Math.max(income - std, 0);
    var tax = taxFor(ti, status);
    var marg = marginalFor(ti, status);
    var eff = income > 0 ? (tax / income) * 100 : 0;
    var ctc = Math.max(kids, 0) * CTC_PER_CHILD;

    FSL.set('brk27-std', M(std));
    FSL.set('brk27-ti', M(ti));
    FSL.set('brk27-marg', (marg*100) + '%');
    FSL.set('brk27-eff', FSL.pct(eff, 1));
    FSL.set('brk27-tax', M(tax));
    FSL.set('brk27-ctc', kids > 0 ? M(ctc) + (kids === 1 ? '' : (' total for ' + kids + ' children')) : '$0 (no children entered)');
    FSL.show('brk27-results', 'grid');
    FSL.show('brk27-table-wrap');

    var body = '', prev = 0, b = BRACKETS[status];
    for (var i=0;i<b.length;i++){
      var top = b[i][0], rate = b[i][1];
      var amt = Math.max(Math.min(ti, top) - prev, 0);
      if (amt > 0) body += '<tr><td><strong>' + (rate*100) + '%</strong></td><td>' + M(amt) + '</td><td>' + M(amt*rate) + '</td></tr>';
      if (ti <= top) break;
      prev = top;
    }
    FSL.el('brk27-table-body').innerHTML = body || '<tr><td colspan="3">No taxable income after your standard deduction.</td></tr>';

    var n = [];
    n.push('For 2027, your ' + { single:'single', mfj:'married filing jointly', mfs:'married filing separately', hoh:'head of household' }[status] + ' standard deduction is ' + M(std) + (extras > 0 ? ' (including ' + M(extras) + ' in additional age-65/blind amounts)' : '') + '.');
    n.push('That leaves ' + M(ti) + ' in taxable income, taxed at a top marginal rate of ' + (marg*100) + '%, for an effective rate of ' + FSL.pct(eff,1) + ' on your total income.');
    if (kids > 0) n.push('The Child Tax Credit estimate above uses the 2027 maximum of ' + M(CTC_PER_CHILD) + ' per qualifying child under 17 -- it does not account for income phase-outs or the refundable-portion limit, so treat it as a ceiling, not a guarantee.');
    n.push('This is a federal estimate only -- it leaves out credits besides the Child Tax Credit, state income tax, self-employment tax, and the newer no-tax-on-tips, no-tax-on-overtime and senior deductions, which can lower what you actually owe.');
    FSL.set('brk27-note', n.join(' '));
  }

  FSL.el('brk27-go').addEventListener('click', calc);
  FSL.el('brk27-status').addEventListener('change', function(){ toggleSpouse(); calc(); });
  FSL.el('brk27-65').addEventListener('change', calc);
  FSL.el('brk27-blind').addEventListener('change', calc);
  FSL.el('brk27-65-sp').addEventListener('change', calc);
  FSL.el('brk27-blind-sp').addEventListener('change', calc);
  FSL.bind('fs-brk27', calc);
  toggleSpouse();
  calc();
});
</script>
<?php }

/* ─────────────────────────────────────────────
   CARDANO (ADA) STAKING CALCULATOR
───────────────────────────────────────────── */
function fs_calc2_cardano_staking() { ?>
<div class="fsc-wrap" id="fs-ada">
<div class="fsc-grid">
  <div class="fsc-inputs">
    <?php fsl_field( 'ada-amount', 'ADA you plan to stake (delegate)', 1000, [ 'min' => 0, 'step' => 10, 'hint' => 'There is no minimum amount to delegate ADA — unlike Ethereum\'s 32 ETH validator requirement, you can stake any amount, even a few ADA, through a stake pool.' ] ); ?>
    <?php fsl_field( 'ada-rate', 'Expected annual reward rate (%)', 3, [ 'min' => 0, 'max' => 20, 'step' => 0.1, 'hint' => 'Cardano\'s network-wide reward rate has recently run roughly 2%-3% a year (StakingRewards.com measured about 2.1%-2.7% in early October 2026); it moves with how much total ADA is staked and the pool you pick. Edit this if you are tracking a different live figure.' ] ); ?>
    <?php fsl_field( 'ada-fee', 'Stake pool margin / fee (%)', 2, [ 'min' => 0, 'max' => 10, 'step' => 0.1, 'hint' => 'Most Cardano stake pools charge a 0%-5% margin on your rewards, plus a small fixed per-epoch fee the protocol deducts before splitting rewards (currently a 170 ADA minimum pool cost) — the pool\'s own page shows its exact margin.' ] ); ?>
    <?php fsl_field( 'ada-price', 'ADA price today ($ per ADA)', 0.45, [ 'min' => 0, 'step' => 0.01, 'hint' => 'ADA\'s USD price changes constantly — enter today\'s price from an exchange or price tracker to convert your rewards to dollars.' ] ); ?>
    <?php fsl_field( 'ada-months', 'Staking period (months)', 12, [ 'min' => 1, 'max' => 120, 'step' => 1 ] ); ?>
    <button type="button" class="fsc-btn" id="ada-go">Calculate Staking Rewards</button>
    <p class="fsl-error" id="ada-err" role="alert" style="display:none"></p>
  </div>
  <div>
    <div class="fsc-results" id="ada-results" style="display:none" aria-live="polite">
      <?php
      fsl_card( 'ada-rewards', 'Estimated ADA rewards', 'primary' );
      fsl_card( 'ada-rewards-usd', 'Rewards in USD (at today\'s price)' );
      fsl_card( 'ada-eff-apy', 'Effective APY after pool fees', 'gold' );
      fsl_card( 'ada-final-ada', 'Your ADA balance at the end' );
      fsl_card( 'ada-compound-bonus', 'Extra ADA earned from auto-compounding', 'secondary' );
      fsl_card( 'ada-epochs', 'Epochs in this period' );
      ?>
    </div>
    <p class="fsl-note" id="ada-note"></p>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var $=FSL.num, M=FSL.money;
  var EPOCHS_PER_YEAR = 365/5; /* Cardano epochs are a fixed 5 days long */

  function calc(){
    var ada=$('ada-amount'), rate=$('ada-rate')/100, fee=$('ada-fee')/100, price=$('ada-price'), months=$('ada-months');
    if(!FSL.valid(ada>0,'ada-err','Enter the amount of ADA you plan to stake.'))return;
    var years = months/12;
    var netAnnualRate = Math.max(rate*(1-fee),0);
    /* Cardano rewards are paid every epoch (5 days) and are automatically
       re-delegated with your stake, so they compound roughly every epoch
       rather than once a year -- this is a built-in feature of the protocol,
       not something you have to opt into like a traditional savings account. */
    var perEpochRate = netAnnualRate/EPOCHS_PER_YEAR;
    var epochs = Math.max(Math.round(years*EPOCHS_PER_YEAR),1);
    var finalAda = ada*Math.pow(1+perEpochRate,epochs);
    var rewardsAda = finalAda-ada;
    var simpleFinalAda = ada+ada*netAnnualRate*years;
    var compoundBonus = Math.max(finalAda-simpleFinalAda,0);
    var effApy = (Math.pow(1+perEpochRate,EPOCHS_PER_YEAR)-1)*100;

    FSL.set('ada-rewards', rewardsAda.toFixed(2)+' ADA');
    FSL.set('ada-rewards-usd', M(rewardsAda*price));
    FSL.set('ada-eff-apy', FSL.pct(effApy,2));
    FSL.set('ada-final-ada', finalAda.toFixed(2)+' ADA ('+M(finalAda*price)+')');
    FSL.set('ada-compound-bonus', compoundBonus.toFixed(3)+' ADA');
    FSL.set('ada-epochs', epochs+' epochs ('+(epochs*5)+' days)');
    FSL.show('ada-results','grid');

    var n=[];
    n.push('This assumes a steady '+(rate*100)+'% gross annual reward rate and a '+(fee*100)+'% pool margin, giving a net rate of about '+FSL.pct(netAnnualRate*100,2)+' a year, compounded every 5-day epoch because Cardano automatically re-delegates your rewards with your original stake.');
    n.push('There is no minimum ADA required to delegate -- you can stake any amount through a stake pool without running your own node, unlike proof-of-stake networks such as Ethereum that require 32 ETH to run a solo validator.');
    n.push('Staking reward rates are not fixed: they move with total ADA staked across the network, the specific pool\'s saturation and performance, and protocol parameters that can change over time. Treat the rate above as an editable estimate, not a guarantee, and check a live source like StakingRewards.com before relying on it for a decision.');
    n.push('ADA stays liquid while delegated -- there is no lock-up period, and you can switch pools or stop delegating at any time without penalty, though a change only takes effect after a short delay of one to two epochs.');
    FSL.set('ada-note', n.join(' '));
  }
  FSL.el('ada-go').addEventListener('click',calc); FSL.bind('fs-ada',calc);
  calc();
});
</script>
<?php }
