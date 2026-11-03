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
