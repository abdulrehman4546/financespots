<?php
/**
 * AI Financial Dashboard -- Full-page tool template
 * Loaded by single-fs_tool.php when tool_type = 'ai_dashboard'
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
<style>
/* ── Dashboard Base ── */
:root{--ai-primary:#00C896;--ai-blue:#3B82F6;--ai-purple:#8B5CF6;--ai-orange:#F59E0B;--ai-red:#EF4444;--ai-dark:#0F172A;--ai-card:#1E293B;--ai-border:#334155;--ai-text:#F1F5F9;--ai-muted:#94A3B8;}
*{box-sizing:border-box;margin:0;padding:0;}
body{background:var(--ai-dark);color:var(--ai-text);font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;min-height:100vh;}

/* ── Top Nav ── */
.ai-nav{background:rgba(15,23,42,.95);backdrop-filter:blur(12px);border-bottom:1px solid var(--ai-border);padding:.75rem 1.5rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;}
.ai-nav__logo{font-size:1.1rem;font-weight:800;color:var(--ai-primary);}
.ai-nav__title{font-size:.9rem;font-weight:600;color:var(--ai-muted);}
.ai-nav__badge{background:linear-gradient(135deg,var(--ai-primary),var(--ai-blue));color:#fff;font-size:.7rem;font-weight:700;padding:.25rem .75rem;border-radius:20px;}

/* ── Hero ── */
.ai-hero{background:linear-gradient(135deg,#0F172A 0%,#1E293B 50%,#0F172A 100%);padding:2.5rem 1.5rem 2rem;text-align:center;border-bottom:1px solid var(--ai-border);position:relative;overflow:hidden;}
.ai-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 30% 50%,rgba(0,200,150,.1) 0%,transparent 60%),radial-gradient(ellipse at 70% 50%,rgba(59,130,246,.1) 0%,transparent 60%);}
.ai-hero__tag{display:inline-flex;align-items:center;gap:.4rem;background:rgba(0,200,150,.12);border:1px solid rgba(0,200,150,.3);color:var(--ai-primary);font-size:.75rem;font-weight:700;padding:.35rem .9rem;border-radius:20px;margin-bottom:1rem;}
.ai-hero__tag::before{content:'';width:6px;height:6px;background:var(--ai-primary);border-radius:50%;animation:pulse-dot 1.5s infinite;}
@keyframes pulse-dot{0%,100%{opacity:1;transform:scale(1);}50%{opacity:.5;transform:scale(1.3);}}
.ai-hero h1{font-size:clamp(1.6rem,4vw,2.4rem);font-weight:900;background:linear-gradient(135deg,#fff 30%,var(--ai-primary));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:.6rem;position:relative;}
.ai-hero p{color:var(--ai-muted);font-size:.95rem;max-width:580px;margin:0 auto 1.5rem;position:relative;}

/* ── Score Ring ── */
.ai-score-ring{display:inline-flex;flex-direction:column;align-items:center;gap:.25rem;margin:.5rem 1rem;}
.ai-score-ring svg{filter:drop-shadow(0 0 8px rgba(0,200,150,.4));}
.ai-score-ring__val{font-size:1.8rem;font-weight:900;color:var(--ai-primary);line-height:1;}
.ai-score-ring__lbl{font-size:.7rem;font-weight:600;color:var(--ai-muted);text-transform:uppercase;letter-spacing:.05em;}

/* ── Layout ── */
.ai-layout{display:grid;grid-template-columns:260px 1fr;gap:0;min-height:calc(100vh - 140px);}
@media(max-width:900px){.ai-layout{grid-template-columns:1fr;}}

/* ── Sidebar ── */
.ai-sidebar{background:var(--ai-card);border-right:1px solid var(--ai-border);padding:1.5rem 1rem;}
.ai-sidebar__section{margin-bottom:1.5rem;}
.ai-sidebar__label{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ai-muted);padding:.4rem .75rem;margin-bottom:.5rem;}
.ai-nav-item{display:flex;align-items:center;gap:.65rem;padding:.65rem .75rem;border-radius:10px;cursor:pointer;font-size:.88rem;font-weight:600;color:var(--ai-muted);transition:all .2s;border:none;background:none;width:100%;text-align:left;}
.ai-nav-item:hover{background:rgba(255,255,255,.06);color:var(--ai-text);}
.ai-nav-item.active{background:rgba(0,200,150,.12);color:var(--ai-primary);border-left:3px solid var(--ai-primary);}
.ai-nav-item__icon{font-size:1.1rem;width:22px;text-align:center;}
.ai-nav-item__badge{margin-left:auto;background:var(--ai-primary);color:#0F172A;font-size:.65rem;font-weight:800;padding:.15rem .45rem;border-radius:10px;}

/* ── Main Content ── */
.ai-main{padding:1.5rem;overflow-y:auto;}
.ai-panel{display:none;}
.ai-panel.active{display:block;}

/* ── Section Title ── */
.ai-section-title{font-size:1.15rem;font-weight:800;color:var(--ai-text);margin-bottom:1.25rem;display:flex;align-items:center;gap:.6rem;}
.ai-section-title span{font-size:1.3rem;}

/* ── Stat Cards ── */
.ai-stats{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;}
.ai-stat{background:var(--ai-card);border:1px solid var(--ai-border);border-radius:14px;padding:1.25rem;transition:transform .2s;}
.ai-stat:hover{transform:translateY(-2px);}
.ai-stat__icon{font-size:1.4rem;margin-bottom:.6rem;}
.ai-stat__val{font-size:1.5rem;font-weight:900;color:var(--ai-text);line-height:1.1;}
.ai-stat__lbl{font-size:.75rem;color:var(--ai-muted);font-weight:500;margin-top:.25rem;}
.ai-stat__change{font-size:.75rem;font-weight:700;margin-top:.4rem;}
.ai-stat__change.up{color:var(--ai-primary);}
.ai-stat__change.down{color:var(--ai-red);}

/* ── Cards ── */
.ai-card{background:var(--ai-card);border:1px solid var(--ai-border);border-radius:16px;padding:1.5rem;margin-bottom:1.25rem;}
.ai-card h3{font-size:.95rem;font-weight:700;margin-bottom:1rem;color:var(--ai-text);}
.ai-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
@media(max-width:640px){.ai-grid-2{grid-template-columns:1fr;}}

/* ── Form Fields ── */
.ai-field{margin-bottom:.85rem;}
.ai-field label{display:block;font-size:.75rem;font-weight:600;color:var(--ai-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.35rem;}
.ai-field input,.ai-field select{width:100%;background:rgba(255,255,255,.05);border:1px solid var(--ai-border);border-radius:8px;padding:.6rem .85rem;color:var(--ai-text);font-size:.9rem;outline:none;transition:border-color .2s;}
.ai-field input:focus,.ai-field select:focus{border-color:var(--ai-primary);}
.ai-field select option{background:var(--ai-card);}

/* ── Buttons ── */
.ai-btn{background:linear-gradient(135deg,var(--ai-primary),#00A87A);color:#0F172A;border:none;padding:.7rem 1.5rem;border-radius:10px;font-weight:800;font-size:.9rem;cursor:pointer;transition:all .2s;width:100%;}
.ai-btn:hover{transform:translateY(-1px);box-shadow:0 4px 15px rgba(0,200,150,.35);}
.ai-btn-sec{background:rgba(255,255,255,.07);color:var(--ai-text);border:1px solid var(--ai-border);padding:.6rem 1.2rem;border-radius:10px;font-weight:700;font-size:.85rem;cursor:pointer;transition:all .2s;}
.ai-btn-sec:hover{background:rgba(255,255,255,.12);}

/* ── Results ── */
.ai-result-row{display:flex;align-items:center;justify-content:space-between;padding:.65rem 0;border-bottom:1px solid rgba(255,255,255,.06);font-size:.88rem;}
.ai-result-row:last-child{border-bottom:none;}
.ai-result-row__lbl{color:var(--ai-muted);}
.ai-result-row__val{font-weight:700;color:var(--ai-text);}
.ai-result-row__val.green{color:var(--ai-primary);}
.ai-result-row__val.red{color:var(--ai-red);}
.ai-result-row__val.blue{color:var(--ai-blue);}

/* ── AI Insight Box ── */
.ai-insight{background:linear-gradient(135deg,rgba(0,200,150,.08),rgba(59,130,246,.08));border:1px solid rgba(0,200,150,.25);border-radius:12px;padding:1rem 1.25rem;margin-top:1rem;}
.ai-insight__title{font-size:.78rem;font-weight:700;color:var(--ai-primary);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.4rem;display:flex;align-items:center;gap:.4rem;}
.ai-insight__text{font-size:.85rem;color:var(--ai-muted);line-height:1.6;}

/* ── Score Bar ── */
.ai-score-bar{background:rgba(255,255,255,.08);border-radius:20px;height:8px;overflow:hidden;margin:.4rem 0;}
.ai-score-bar__fill{height:100%;border-radius:20px;transition:width 1s ease;}

/* ── Gauge ── */
.ai-gauge-wrap{text-align:center;padding:1rem 0;}

/* ── AI Chat ── */
.ai-chat{background:rgba(255,255,255,.03);border:1px solid var(--ai-border);border-radius:12px;height:280px;overflow-y:auto;padding:1rem;margin-bottom:1rem;display:flex;flex-direction:column;gap:.75rem;}
.ai-chat-msg{padding:.65rem .9rem;border-radius:10px;font-size:.85rem;line-height:1.6;max-width:85%;}
.ai-chat-msg.ai{background:rgba(0,200,150,.1);border:1px solid rgba(0,200,150,.2);color:var(--ai-text);align-self:flex-start;}
.ai-chat-msg.user{background:rgba(59,130,246,.15);border:1px solid rgba(59,130,246,.25);color:var(--ai-text);align-self:flex-end;}
.ai-chat-input{display:flex;gap:.6rem;}
.ai-chat-input input{flex:1;background:rgba(255,255,255,.05);border:1px solid var(--ai-border);border-radius:8px;padding:.6rem .85rem;color:var(--ai-text);font-size:.88rem;outline:none;}
.ai-chat-input input:focus{border-color:var(--ai-primary);}
.ai-chat-input button{background:var(--ai-primary);color:#0F172A;border:none;border-radius:8px;padding:.6rem 1rem;font-weight:800;cursor:pointer;font-size:.85rem;}

/* ── Table ── */
.ai-table{width:100%;border-collapse:collapse;font-size:.83rem;}
.ai-table th{background:rgba(255,255,255,.05);color:var(--ai-muted);font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;padding:.6rem .85rem;text-align:left;}
.ai-table td{padding:.65rem .85rem;border-bottom:1px solid rgba(255,255,255,.05);color:var(--ai-text);}
.ai-table tr:hover td{background:rgba(255,255,255,.03);}

/* ── Pill tags ── */
.ai-pill{display:inline-block;padding:.2rem .7rem;border-radius:20px;font-size:.72rem;font-weight:700;}
.ai-pill.green{background:rgba(0,200,150,.15);color:var(--ai-primary);}
.ai-pill.red{background:rgba(239,68,68,.15);color:var(--ai-red);}
.ai-pill.blue{background:rgba(59,130,246,.15);color:var(--ai-blue);}
.ai-pill.orange{background:rgba(245,158,11,.15);color:var(--ai-orange);}

/* ── Progress Ring ── */
.ai-ring-container{position:relative;display:inline-block;}
.ai-ring-text{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;}
.ai-ring-text .big{font-size:1.4rem;font-weight:900;color:var(--ai-text);}
.ai-ring-text .small{font-size:.65rem;color:var(--ai-muted);}

/* ── Responsive ── */
@media(max-width:600px){
  .ai-stats{grid-template-columns:1fr 1fr;}
  .ai-nav{flex-wrap:wrap;gap:.5rem;}
}
</style>
</head>
<body>

<!-- Top Nav -->
<nav class="ai-nav">
  <div class="ai-nav__logo">&#9889; FinanceSpots AI</div>
  <div class="ai-nav__title" style="display:flex;align-items:center;gap:.5rem">
    <span>AI Financial Dashboard</span>
    <span style="font-size:.7rem;color:#64748B;">by Abdul Rahman</span>
  </div>
  <div style="display:flex;align-items:center;gap:.75rem">
    <a href="<?php echo esc_url( home_url('/') ); ?>" style="color:#94A3B8;font-size:.78rem;text-decoration:none;font-weight:600;">&#x2190; Home</a>
    <div class="ai-nav__badge">&#129302; AI-Powered</div>
  </div>
</nav>

<!-- Hero -->
<div class="ai-hero">
  <div style="position:relative;z-index:1">
    <div class="ai-hero__tag">&#128994; AI Active -- Real-Time Analysis</div>
    <h1>AI Financial Dashboard</h1>
    <p>Your complete AI-powered financial command center. Analyze, plan, and optimize every aspect of your financial life in one place.</p>
    <div style="display:flex;justify-content:center;gap:1.5rem;flex-wrap:wrap;margin-top:1rem">
      <div class="ai-score-ring">
        <svg width="80" height="80" viewBox="0 0 80 80">
          <circle cx="40" cy="40" r="34" fill="none" stroke="rgba(255,255,255,.1)" stroke-width="8"/>
          <circle id="hero-ring-1" cx="40" cy="40" r="34" fill="none" stroke="#00C896" stroke-width="8" stroke-dasharray="213.6" stroke-dashoffset="213.6" stroke-linecap="round" transform="rotate(-90 40 40)" style="transition:stroke-dashoffset 1.5s ease"/>
        </svg>
        <div class="ai-score-ring__val" id="hero-health">--</div>
        <div class="ai-score-ring__lbl">Health Score</div>
      </div>
      <div class="ai-score-ring">
        <svg width="80" height="80" viewBox="0 0 80 80">
          <circle cx="40" cy="40" r="34" fill="none" stroke="rgba(255,255,255,.1)" stroke-width="8"/>
          <circle id="hero-ring-2" cx="40" cy="40" r="34" fill="none" stroke="#3B82F6" stroke-width="8" stroke-dasharray="213.6" stroke-dashoffset="213.6" stroke-linecap="round" transform="rotate(-90 40 40)" style="transition:stroke-dashoffset 1.5s ease"/>
        </svg>
        <div class="ai-score-ring__val" id="hero-savings">--</div>
        <div class="ai-score-ring__lbl">Savings Rate</div>
      </div>
      <div class="ai-score-ring">
        <svg width="80" height="80" viewBox="0 0 80 80">
          <circle cx="40" cy="40" r="34" fill="none" stroke="rgba(255,255,255,.1)" stroke-width="8"/>
          <circle id="hero-ring-3" cx="40" cy="40" r="34" fill="none" stroke="#8B5CF6" stroke-width="8" stroke-dasharray="213.6" stroke-dashoffset="213.6" stroke-linecap="round" transform="rotate(-90 40 40)" style="transition:stroke-dashoffset 1.5s ease"/>
        </svg>
        <div class="ai-score-ring__val" id="hero-retire">--</div>
        <div class="ai-score-ring__lbl">Retire Ready</div>
      </div>
    </div>
  </div>
</div>

<!-- Main Layout -->
<div class="ai-layout">

  <!-- Sidebar -->
  <aside class="ai-sidebar">
    <div class="ai-sidebar__section">
      <div class="ai-sidebar__label">Dashboard</div>
      <button class="ai-nav-item active" onclick="aiPanel('overview')"><span class="ai-nav-item__icon">&#128202;</span> Overview <span class="ai-nav-item__badge">Live</span></button>
    </div>
    <div class="ai-sidebar__section">
      <div class="ai-sidebar__label">AI Tools</div>
      <button class="ai-nav-item" onclick="aiPanel('health')"><span class="ai-nav-item__icon">&#10084;&#65039;</span> Financial Health</button>
      <button class="ai-nav-item" onclick="aiPanel('budget')"><span class="ai-nav-item__icon">&#128179;</span> Smart Budget</button>
      <button class="ai-nav-item" onclick="aiPanel('networth')"><span class="ai-nav-item__icon">&#128142;</span> Net Worth</button>
      <button class="ai-nav-item" onclick="aiPanel('debt')"><span class="ai-nav-item__icon">&#128279;</span> Debt Optimizer</button>
      <button class="ai-nav-item" onclick="aiPanel('invest')"><span class="ai-nav-item__icon">&#128200;</span> Investment AI</button>
      <button class="ai-nav-item" onclick="aiPanel('retire')"><span class="ai-nav-item__icon">&#127958;&#65039;</span> Retirement</button>
      <button class="ai-nav-item" onclick="aiPanel('tax')"><span class="ai-nav-item__icon">&#129534;</span> Tax Optimizer</button>
      <button class="ai-nav-item" onclick="aiPanel('emergency')"><span class="ai-nav-item__icon">&#128737;&#65039;</span> Emergency Fund</button>
    </div>
    <div class="ai-sidebar__section">
      <div class="ai-sidebar__label">AI Assistant</div>
      <button class="ai-nav-item" onclick="aiPanel('chat')"><span class="ai-nav-item__icon">&#129302;</span> AI Advisor</button>
    </div>
    <div style="margin-top:auto;padding-top:1rem">
      <button onclick="aiExportPDF()" class="ai-btn-sec" style="width:100%;margin-bottom:.5rem">&#11015;&#65039; Export Report PDF</button>
      <a href="<?php echo esc_url(home_url('/tools/')); ?>" class="ai-btn-sec" style="display:block;text-align:center;text-decoration:none;width:100%">&#128736;&#65039; All 110+ Tools</a>
    </div>
  </aside>

  <!-- Main -->
  <main class="ai-main">

    <!-- ── OVERVIEW PANEL ── -->
    <div class="ai-panel active" id="panel-overview">
      <div class="ai-section-title"><span>&#128202;</span> Financial Overview</div>

      <div class="ai-stats" id="overview-stats">
        <div class="ai-stat"><div class="ai-stat__icon">&#128176;</div><div class="ai-stat__val" id="ov-income">--</div><div class="ai-stat__lbl">Monthly Income</div></div>
        <div class="ai-stat"><div class="ai-stat__icon">&#128179;</div><div class="ai-stat__val" id="ov-expense">--</div><div class="ai-stat__lbl">Monthly Expenses</div></div>
        <div class="ai-stat"><div class="ai-stat__icon">&#128200;</div><div class="ai-stat__val" id="ov-surplus">--</div><div class="ai-stat__lbl">Monthly Surplus</div><div class="ai-stat__change up" id="ov-surplus-pct">--</div></div>
        <div class="ai-stat"><div class="ai-stat__icon">&#128142;</div><div class="ai-stat__val" id="ov-networth">--</div><div class="ai-stat__lbl">Net Worth</div></div>
        <div class="ai-stat"><div class="ai-stat__icon">&#128279;</div><div class="ai-stat__val" id="ov-debt">--</div><div class="ai-stat__lbl">Total Debt</div></div>
        <div class="ai-stat"><div class="ai-stat__icon">&#128737;&#65039;</div><div class="ai-stat__val" id="ov-efund">--</div><div class="ai-stat__lbl">Emergency Fund</div></div>
      </div>

      <div class="ai-card">
        <h3>&#9889; Quick Financial Profile -- Enter to Unlock All Tools</h3>
        <div class="ai-grid-2">
          <div>
            <div class="ai-field"><label>Monthly Take-Home Income ($)</label><input type="number" id="qp-income" value="6000" oninput="aiQuickCalc()"></div>
            <div class="ai-field"><label>Monthly Total Expenses ($)</label><input type="number" id="qp-expense" value="4200" oninput="aiQuickCalc()"></div>
            <div class="ai-field"><label>Total Savings & Investments ($)</label><input type="number" id="qp-savings" value="25000" oninput="aiQuickCalc()"></div>
            <div class="ai-field"><label>Total Debt (all loans) ($)</label><input type="number" id="qp-debt" value="18000" oninput="aiQuickCalc()"></div>
          </div>
          <div>
            <div class="ai-field"><label>Emergency Fund ($)</label><input type="number" id="qp-efund" value="8000" oninput="aiQuickCalc()"></div>
            <div class="ai-field"><label>Current Age</label><input type="number" id="qp-age" value="32" oninput="aiQuickCalc()"></div>
            <div class="ai-field"><label>Retirement Goal Age</label><input type="number" id="qp-retire-age" value="65" oninput="aiQuickCalc()"></div>
            <div class="ai-field"><label>Monthly Investing ($)</label><input type="number" id="qp-invest" value="800" oninput="aiQuickCalc()"></div>
          </div>
        </div>
        <button class="ai-btn" onclick="aiQuickCalc();aiScrollDown()">&#129302; Run AI Analysis</button>
      </div>

      <!-- AI Score Card -->
      <div class="ai-card" id="ai-score-card" style="display:none">
        <h3>&#129302; AI Financial Health Analysis</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem">
          <div>
            <div style="font-size:.75rem;font-weight:600;color:var(--ai-muted);margin-bottom:.35rem">Emergency Fund</div>
            <div class="ai-score-bar"><div class="ai-score-bar__fill" id="bar-ef" style="background:var(--ai-primary);width:0%"></div></div>
            <div style="font-size:.78rem;color:var(--ai-muted)" id="bar-ef-lbl">--</div>
          </div>
          <div>
            <div style="font-size:.75rem;font-weight:600;color:var(--ai-muted);margin-bottom:.35rem">Savings Rate</div>
            <div class="ai-score-bar"><div class="ai-score-bar__fill" id="bar-sr" style="background:var(--ai-blue);width:0%"></div></div>
            <div style="font-size:.78rem;color:var(--ai-muted)" id="bar-sr-lbl">--</div>
          </div>
          <div>
            <div style="font-size:.75rem;font-weight:600;color:var(--ai-muted);margin-bottom:.35rem">Debt-to-Income</div>
            <div class="ai-score-bar"><div class="ai-score-bar__fill" id="bar-dti" style="background:var(--ai-orange);width:0%"></div></div>
            <div style="font-size:.78rem;color:var(--ai-muted)" id="bar-dti-lbl">--</div>
          </div>
          <div>
            <div style="font-size:.75rem;font-weight:600;color:var(--ai-muted);margin-bottom:.35rem">Retirement Track</div>
            <div class="ai-score-bar"><div class="ai-score-bar__fill" id="bar-rt" style="background:var(--ai-purple);width:0%"></div></div>
            <div style="font-size:.78rem;color:var(--ai-muted)" id="bar-rt-lbl">--</div>
          </div>
        </div>
        <div class="ai-insight" id="ai-overview-insight">
          <div class="ai-insight__title">&#129302; AI Insight</div>
          <div class="ai-insight__text" id="ai-insight-text">Enter your financial data above to get personalized AI insights.</div>
        </div>
      </div>

      <!-- Chart -->
      <div class="ai-card" id="overview-chart-card" style="display:none">
        <h3>&#128202; Income vs Expenses vs Savings</h3>
        <canvas id="overview-chart" height="100"></canvas>
      </div>
    </div><!-- /overview -->

    <!-- ── HEALTH PANEL ── -->
    <div class="ai-panel" id="panel-health">
      <div class="ai-section-title"><span>&#10084;&#65039;</span> Financial Health Score</div>
      <div class="ai-card">
        <h3>Rate Your Financial Health (1-10)</h3>
        <div class="ai-grid-2">
          <div>
            <div class="ai-field"><label>Emergency Fund (months of expenses)</label><input type="number" id="fh-ef" value="3" min="0" max="24" step=".5"></div>
            <div class="ai-field"><label>Savings Rate (%)</label><input type="number" id="fh-sr" value="15" min="0" max="100"></div>
            <div class="ai-field"><label>Debt-to-Income Ratio (%)</label><input type="number" id="fh-dti" value="28" min="0" max="100"></div>
            <div class="ai-field"><label>Investment Diversification</label>
              <select id="fh-div"><option value="1">None -- all cash</option><option value="3">1-2 asset classes</option><option value="6" selected>3-4 asset classes</option><option value="9">5+ asset classes</option></select></div>
          </div>
          <div>
            <div class="ai-field"><label>Monthly Budget Adherence</label>
              <select id="fh-budget"><option value="2">Never track</option><option value="5">Sometimes track</option><option value="8" selected>Track monthly</option><option value="10">Track daily/weekly</option></select></div>
            <div class="ai-field"><label>Retirement Contribution (%)</label><input type="number" id="fh-ret" value="8" min="0" max="100"></div>
            <div class="ai-field"><label>Credit Score Range</label>
              <select id="fh-credit"><option value="2">Below 580</option><option value="4">580-669</option><option value="7">670-739</option><option value="9" selected>740-799</option><option value="10">800+</option></select></div>
            <div class="ai-field"><label>Insurance Coverage</label>
              <select id="fh-ins"><option value="2">No insurance</option><option value="5">Health only</option><option value="8" selected>Health + life</option><option value="10">Full coverage</option></select></div>
          </div>
        </div>
        <button class="ai-btn" onclick="calcHealthScore()">Calculate Health Score</button>
      </div>
      <div class="ai-card" id="health-results" style="display:none">
        <h3>&#129302; Your Financial Health Report</h3>
        <div style="text-align:center;padding:1rem 0">
          <div style="font-size:4rem;font-weight:900;color:var(--ai-primary)" id="health-score">--</div>
          <div style="font-size:1rem;font-weight:700;color:var(--ai-muted)" id="health-grade">--</div>
        </div>
        <div id="health-breakdown"></div>
        <div class="ai-insight" id="health-insight">
          <div class="ai-insight__title">&#129302; AI Recommendations</div>
          <div class="ai-insight__text" id="health-tips">--</div>
        </div>
      </div>
    </div>

    <!-- ── BUDGET PANEL ── -->
    <div class="ai-panel" id="panel-budget">
      <div class="ai-section-title"><span>&#128179;</span> AI Smart Budget Planner</div>
      <div class="ai-card">
        <h3>Monthly Income & Expenses</h3>
        <div class="ai-grid-2">
          <div>
            <div class="ai-field"><label>Monthly Income ($)</label><input type="number" id="bg2-income" value="6000"></div>
            <div class="ai-field"><label>Housing (Rent/Mortgage)</label><input type="number" id="bg2-housing" value="1500"></div>
            <div class="ai-field"><label>Food & Groceries</label><input type="number" id="bg2-food" value="600"></div>
            <div class="ai-field"><label>Transportation</label><input type="number" id="bg2-transport" value="400"></div>
            <div class="ai-field"><label>Utilities & Bills</label><input type="number" id="bg2-utils" value="200"></div>
          </div>
          <div>
            <div class="ai-field"><label>Healthcare & Insurance</label><input type="number" id="bg2-health" value="250"></div>
            <div class="ai-field"><label>Entertainment & Dining</label><input type="number" id="bg2-fun" value="300"></div>
            <div class="ai-field"><label>Debt Payments</label><input type="number" id="bg2-debt" value="400"></div>
            <div class="ai-field"><label>Savings & Investments</label><input type="number" id="bg2-save" value="600"></div>
            <div class="ai-field"><label>Other Expenses</label><input type="number" id="bg2-other" value="250"></div>
          </div>
        </div>
        <button class="ai-btn" onclick="calcAIBudget()">&#129302; Analyze with AI</button>
      </div>
      <div id="budget-results" style="display:none">
        <div class="ai-card">
          <h3>&#128202; Budget Analysis</h3>
          <div id="budget-rows"></div>
        </div>
        <div class="ai-card">
          <h3>50/30/20 vs Your Actual Spending</h3>
          <canvas id="budget-chart" height="120"></canvas>
        </div>
        <div class="ai-card">
          <div class="ai-insight">
            <div class="ai-insight__title">&#129302; AI Budget Recommendations</div>
            <div class="ai-insight__text" id="budget-ai-text">--</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── NET WORTH PANEL ── -->
    <div class="ai-panel" id="panel-networth">
      <div class="ai-section-title"><span>&#128142;</span> Net Worth Calculator</div>
      <div class="ai-grid-2">
        <div class="ai-card">
          <h3>&#9989; Assets</h3>
          <div class="ai-field"><label>Cash & Savings ($)</label><input type="number" id="nw-cash" value="15000"></div>
          <div class="ai-field"><label>Retirement Accounts ($)</label><input type="number" id="nw-ret" value="45000"></div>
          <div class="ai-field"><label>Investments / Brokerage ($)</label><input type="number" id="nw-invest" value="20000"></div>
          <div class="ai-field"><label>Home Value ($)</label><input type="number" id="nw-home" value="350000"></div>
          <div class="ai-field"><label>Vehicles ($)</label><input type="number" id="nw-car" value="18000"></div>
          <div class="ai-field"><label>Crypto & Other Assets ($)</label><input type="number" id="nw-other" value="5000"></div>
        </div>
        <div class="ai-card">
          <h3>&#10060; Liabilities</h3>
          <div class="ai-field"><label>Mortgage Balance ($)</label><input type="number" id="nw-mortgage" value="280000"></div>
          <div class="ai-field"><label>Auto Loan ($)</label><input type="number" id="nw-autoloan" value="12000"></div>
          <div class="ai-field"><label>Student Loans ($)</label><input type="number" id="nw-student" value="25000"></div>
          <div class="ai-field"><label>Credit Card Debt ($)</label><input type="number" id="nw-cc" value="4500"></div>
          <div class="ai-field"><label>Personal Loans ($)</label><input type="number" id="nw-personal" value="2000"></div>
          <div class="ai-field"><label>Other Liabilities ($)</label><input type="number" id="nw-otherliab" value="0"></div>
        </div>
      </div>
      <button class="ai-btn" style="margin-bottom:1.25rem" onclick="calcNetWorth()">&#128142; Calculate Net Worth</button>
      <div id="nw-results" style="display:none">
        <div class="ai-card">
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;text-align:center;margin-bottom:1rem">
            <div><div style="font-size:.75rem;color:var(--ai-muted);font-weight:600">TOTAL ASSETS</div><div style="font-size:1.6rem;font-weight:900;color:var(--ai-primary)" id="nw-total-assets">--</div></div>
            <div><div style="font-size:.75rem;color:var(--ai-muted);font-weight:600">TOTAL LIABILITIES</div><div style="font-size:1.6rem;font-weight:900;color:var(--ai-red)" id="nw-total-liab">--</div></div>
            <div><div style="font-size:.75rem;color:var(--ai-muted);font-weight:600">NET WORTH</div><div style="font-size:1.6rem;font-weight:900;color:var(--ai-blue)" id="nw-net">--</div></div>
          </div>
          <canvas id="nw-chart" height="120"></canvas>
        </div>
        <div class="ai-card">
          <div class="ai-insight">
            <div class="ai-insight__title">&#129302; AI Net Worth Analysis</div>
            <div class="ai-insight__text" id="nw-ai-text">--</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── DEBT OPTIMIZER ── -->
    <div class="ai-panel" id="panel-debt">
      <div class="ai-section-title"><span>&#128279;</span> AI Debt Payoff Optimizer</div>
      <div class="ai-card">
        <h3>Enter Your Debts</h3>
        <div id="debt-list">
          <div class="debt-row" style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr auto;gap:.5rem;align-items:end;margin-bottom:.5rem">
            <div class="ai-field" style="margin:0"><label>Debt Name</label><input type="text" value="Credit Card" class="d-name"></div>
            <div class="ai-field" style="margin:0"><label>Balance ($)</label><input type="number" value="5000" class="d-bal"></div>
            <div class="ai-field" style="margin:0"><label>Rate (%)</label><input type="number" value="22.9" step=".1" class="d-rate"></div>
            <div class="ai-field" style="margin:0"><label>Min Payment ($)</label><input type="number" value="150" class="d-min"></div>
            <button onclick="this.closest('.debt-row').remove()" style="background:rgba(239,68,68,.15);color:var(--ai-red);border:none;border-radius:8px;padding:.6rem .8rem;cursor:pointer;align-self:flex-end;font-size:1rem">&#10005;</button>
          </div>
          <div class="debt-row" style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr auto;gap:.5rem;align-items:end;margin-bottom:.5rem">
            <div class="ai-field" style="margin:0"><label>Debt Name</label><input type="text" value="Car Loan" class="d-name"></div>
            <div class="ai-field" style="margin:0"><label>Balance ($)</label><input type="number" value="12000" class="d-bal"></div>
            <div class="ai-field" style="margin:0"><label>Rate (%)</label><input type="number" value="7.5" step=".1" class="d-rate"></div>
            <div class="ai-field" style="margin:0"><label>Min Payment ($)</label><input type="number" value="280" class="d-min"></div>
            <button onclick="this.closest('.debt-row').remove()" style="background:rgba(239,68,68,.15);color:var(--ai-red);border:none;border-radius:8px;padding:.6rem .8rem;cursor:pointer;align-self:flex-end;font-size:1rem">&#10005;</button>
          </div>
        </div>
        <button onclick="aiAddDebt()" class="ai-btn-sec" style="margin-bottom:1rem">+ Add Debt</button>
        <div class="ai-field"><label>Extra Monthly Payment Budget ($)</label><input type="number" id="debt-extra" value="300"></div>
        <div class="ai-field"><label>Strategy</label>
          <select id="debt-strategy">
            <option value="avalanche">&#127956;&#65039; Avalanche -- Highest Rate First (Saves Most Interest)</option>
            <option value="snowball">&#9924; Snowball -- Smallest Balance First (Best Motivation)</option>
          </select></div>
        <button class="ai-btn" onclick="calcDebtOptimizer()">&#129302; Optimize Debt Payoff</button>
      </div>
      <div id="debt-results" style="display:none">
        <div class="ai-card">
          <h3>&#128202; Payoff Comparison</h3>
          <div id="debt-comparison"></div>
        </div>
        <div class="ai-card">
          <h3>&#128197; Payoff Schedule</h3>
          <div style="overflow-x:auto"><table class="ai-table" id="debt-table">
            <thead><tr><th>Debt</th><th>Balance</th><th>Rate</th><th>Payoff Order</th><th>Months</th><th>Total Interest</th></tr></thead>
            <tbody id="debt-tbody"></tbody>
          </table></div>
        </div>
        <div class="ai-card">
          <div class="ai-insight">
            <div class="ai-insight__title">&#129302; AI Debt Strategy</div>
            <div class="ai-insight__text" id="debt-ai-text">--</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── INVESTMENT AI ── -->
    <div class="ai-panel" id="panel-invest">
      <div class="ai-section-title"><span>&#128200;</span> AI Investment Analyzer</div>
      <div class="ai-card">
        <h3>Portfolio & Goals</h3>
        <div class="ai-grid-2">
          <div>
            <div class="ai-field"><label>Current Portfolio Value ($)</label><input type="number" id="inv-current" value="50000"></div>
            <div class="ai-field"><label>Monthly Contribution ($)</label><input type="number" id="inv-monthly" value="800"></div>
            <div class="ai-field"><label>Investment Horizon (Years)</label><input type="number" id="inv-years" value="25"></div>
            <div class="ai-field"><label>Risk Tolerance</label>
              <select id="inv-risk">
                <option value="4">Conservative (4% avg return)</option>
                <option value="6">Moderate (6% avg return)</option>
                <option value="8" selected>Balanced (8% avg return)</option>
                <option value="10">Aggressive (10% avg return)</option>
                <option value="12">Very Aggressive (12% avg return)</option>
              </select></div>
          </div>
          <div>
            <div class="ai-field"><label>Stocks (%)</label><input type="number" id="inv-stocks" value="70" min="0" max="100"></div>
            <div class="ai-field"><label>Bonds (%)</label><input type="number" id="inv-bonds" value="20" min="0" max="100"></div>
            <div class="ai-field"><label>Real Estate/REITs (%)</label><input type="number" id="inv-reits" value="5" min="0" max="100"></div>
            <div class="ai-field"><label>Cash/Other (%)</label><input type="number" id="inv-cash" value="5" min="0" max="100"></div>
          </div>
        </div>
        <button class="ai-btn" onclick="calcInvestment()">&#128200; Analyze Portfolio</button>
      </div>
      <div id="invest-results" style="display:none">
        <div class="ai-card">
          <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:1rem">
            <div class="ai-stat"><div class="ai-stat__icon">&#127919;</div><div class="ai-stat__val" id="inv-proj">--</div><div class="ai-stat__lbl">Projected Value</div></div>
            <div class="ai-stat"><div class="ai-stat__icon">&#128176;</div><div class="ai-stat__val" id="inv-contrib">--</div><div class="ai-stat__lbl">Total Contributed</div></div>
            <div class="ai-stat"><div class="ai-stat__icon">&#128200;</div><div class="ai-stat__val" id="inv-gain">--</div><div class="ai-stat__lbl">Investment Gains</div></div>
            <div class="ai-stat"><div class="ai-stat__icon">&#128260;</div><div class="ai-stat__val" id="inv-mult">--</div><div class="ai-stat__lbl">Money Multiple</div></div>
          </div>
          <canvas id="inv-chart" height="120" style="margin-top:1rem"></canvas>
        </div>
        <div class="ai-card">
          <h3>&#129383; Portfolio Allocation</h3>
          <canvas id="inv-pie" height="200"></canvas>
        </div>
        <div class="ai-card">
          <div class="ai-insight">
            <div class="ai-insight__title">&#129302; AI Portfolio Recommendations</div>
            <div class="ai-insight__text" id="inv-ai-text">--</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── RETIREMENT ── -->
    <div class="ai-panel" id="panel-retire">
      <div class="ai-section-title"><span>&#127958;&#65039;</span> AI Retirement Planner</div>
      <div class="ai-card">
        <div class="ai-grid-2">
          <div>
            <div class="ai-field"><label>Current Age</label><input type="number" id="rp-age" value="35"></div>
            <div class="ai-field"><label>Desired Retirement Age</label><input type="number" id="rp-retire" value="65"></div>
            <div class="ai-field"><label>Current Retirement Savings ($)</label><input type="number" id="rp-saved" value="60000"></div>
            <div class="ai-field"><label>Monthly 401k/IRA Contribution ($)</label><input type="number" id="rp-contrib" value="1200"></div>
            <div class="ai-field"><label>Employer Match ($)</label><input type="number" id="rp-match" value="400"></div>
          </div>
          <div>
            <div class="ai-field"><label>Expected Annual Return (%)</label><input type="number" id="rp-return" value="7" step=".5"></div>
            <div class="ai-field"><label>Desired Monthly Income in Retirement ($)</label><input type="number" id="rp-income" value="6000"></div>
            <div class="ai-field"><label>Expected Social Security ($)</label><input type="number" id="rp-ss" value="1800"></div>
            <div class="ai-field"><label>Inflation Rate (%)</label><input type="number" id="rp-inf" value="3" step=".1"></div>
            <div class="ai-field"><label>Life Expectancy Age</label><input type="number" id="rp-life" value="90"></div>
          </div>
        </div>
        <button class="ai-btn" onclick="calcRetirePlan()">&#127958;&#65039; Generate Retirement Plan</button>
      </div>
      <div id="retire-results" style="display:none">
        <div class="ai-card">
          <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:1rem;margin-bottom:1rem">
            <div class="ai-stat"><div class="ai-stat__icon">&#127919;</div><div class="ai-stat__val" id="rp-nest">--</div><div class="ai-stat__lbl">Nest Egg</div></div>
            <div class="ai-stat"><div class="ai-stat__icon">&#128184;</div><div class="ai-stat__val" id="rp-monthly-inc">--</div><div class="ai-stat__lbl">Monthly Income</div></div>
            <div class="ai-stat"><div class="ai-stat__icon">&#128202;</div><div class="ai-stat__val" id="rp-gap">--</div><div class="ai-stat__lbl">Income Gap</div></div>
            <div class="ai-stat"><div class="ai-stat__icon">&#9889;</div><div class="ai-stat__val" id="rp-status">--</div><div class="ai-stat__lbl">Status</div></div>
          </div>
          <canvas id="retire-chart" height="120"></canvas>
        </div>
        <div class="ai-card">
          <div class="ai-insight">
            <div class="ai-insight__title">&#129302; AI Retirement Strategy</div>
            <div class="ai-insight__text" id="retire-ai-text">--</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── TAX OPTIMIZER ── -->
    <div class="ai-panel" id="panel-tax">
      <div class="ai-section-title"><span>&#129534;</span> AI Tax Optimizer</div>
      <div class="ai-card">
        <div class="ai-grid-2">
          <div>
            <div class="ai-field"><label>Annual Gross Income ($)</label><input type="number" id="tax-income" value="85000"></div>
            <div class="ai-field"><label>Filing Status</label>
              <select id="tax-status">
                <option value="single">Single</option>
                <option value="mfj" selected>Married Filing Jointly</option>
                <option value="mfs">Married Filing Separately</option>
                <option value="hoh">Head of Household</option>
              </select></div>
            <div class="ai-field"><label>401k Contribution ($)</label><input type="number" id="tax-401k" value="10000"></div>
            <div class="ai-field"><label>IRA Contribution ($)</label><input type="number" id="tax-ira" value="3000"></div>
            <div class="ai-field"><label>HSA Contribution ($)</label><input type="number" id="tax-hsa" value="1500"></div>
          </div>
          <div>
            <div class="ai-field"><label>Mortgage Interest ($)</label><input type="number" id="tax-mort" value="12000"></div>
            <div class="ai-field"><label>Charitable Donations ($)</label><input type="number" id="tax-charity" value="2000"></div>
            <div class="ai-field"><label>State & Local Taxes ($)</label><input type="number" id="tax-salt" value="8000"></div>
            <div class="ai-field"><label>Business Expenses ($)</label><input type="number" id="tax-biz" value="0"></div>
            <div class="ai-field"><label>Other Deductions ($)</label><input type="number" id="tax-other" value="0"></div>
          </div>
        </div>
        <button class="ai-btn" onclick="calcTaxOptimizer()">&#129534; Optimize Taxes</button>
      </div>
      <div id="tax-results" style="display:none">
        <div class="ai-card" id="tax-rows"></div>
        <div class="ai-card">
          <div class="ai-insight">
            <div class="ai-insight__title">&#129302; AI Tax Optimization Tips</div>
            <div class="ai-insight__text" id="tax-ai-text">--</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── EMERGENCY FUND ── -->
    <div class="ai-panel" id="panel-emergency">
      <div class="ai-section-title"><span>&#128737;&#65039;</span> Emergency Fund Planner</div>
      <div class="ai-card">
        <div class="ai-grid-2">
          <div>
            <div class="ai-field"><label>Monthly Essential Expenses ($)</label><input type="number" id="ef-expense" value="3500"></div>
            <div class="ai-field"><label>Job Stability</label>
              <select id="ef-stability">
                <option value="3">Very Stable (government, tenured)</option>
                <option value="4">Stable (large company)</option>
                <option value="6" selected>Moderate (private sector)</option>
                <option value="8">Variable (commission/sales)</option>
                <option value="12">Unstable (freelance/self-employed)</option>
              </select></div>
            <div class="ai-field"><label>Dependents</label>
              <select id="ef-deps">
                <option value="0">None</option>
                <option value="1">1 dependent</option>
                <option value="2" selected>2 dependents</option>
                <option value="3">3+ dependents</option>
              </select></div>
          </div>
          <div>
            <div class="ai-field"><label>Current Emergency Fund ($)</label><input type="number" id="ef-current" value="8000"></div>
            <div class="ai-field"><label>Monthly Amount You Can Save ($)</label><input type="number" id="ef-save" value="400"></div>
            <div class="ai-field"><label>HYSA Interest Rate (%)</label><input type="number" id="ef-rate" value="4.5" step=".1"></div>
          </div>
        </div>
        <button class="ai-btn" onclick="calcEmergencyFund()">&#128737;&#65039; Calculate Emergency Fund</button>
      </div>
      <div id="ef-results" style="display:none">
        <div class="ai-card" id="ef-result-content"></div>
        <div class="ai-card">
          <div class="ai-insight">
            <div class="ai-insight__title">&#129302; AI Emergency Fund Strategy</div>
            <div class="ai-insight__text" id="ef-ai-text">--</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── AI CHAT ── -->
    <div class="ai-panel" id="panel-chat">
      <div class="ai-section-title"><span>&#129302;</span> AI Financial Advisor</div>
      <div class="ai-card">
        <p style="font-size:.85rem;color:var(--ai-muted);margin-bottom:1rem">Ask me anything about personal finance, budgeting, investing, taxes, and retirement planning.</p>
        <div class="ai-chat" id="ai-chat-box">
          <div class="ai-chat-msg ai">&#128075; Hello! I'm your AI Financial Advisor. I can help with budgeting, investing, debt payoff, retirement planning, tax optimization, and more. What financial question can I help you with today?</div>
        </div>
        <div class="ai-chat-input">
          <input type="text" id="ai-chat-input" placeholder="e.g. How much should I save for retirement?" onkeydown="if(event.key==='Enter')aiChat()">
          <button onclick="aiChat()">Send &#x2192;</button>
        </div>
        <!-- Quick prompts -->
        <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.75rem">
          <button class="ai-btn-sec" style="font-size:.75rem;padding:.35rem .75rem" onclick="aiChatQ('How much emergency fund do I need?')">Emergency Fund?</button>
          <button class="ai-btn-sec" style="font-size:.75rem;padding:.35rem .75rem" onclick="aiChatQ('What is the 50/30/20 budget rule?')">50/30/20 Rule?</button>
          <button class="ai-btn-sec" style="font-size:.75rem;padding:.35rem .75rem" onclick="aiChatQ('Avalanche vs snowball debt method?')">Debt Strategy?</button>
          <button class="ai-btn-sec" style="font-size:.75rem;padding:.35rem .75rem" onclick="aiChatQ('How to start investing with $1000?')">Start Investing?</button>
          <button class="ai-btn-sec" style="font-size:.75rem;padding:.35rem .75rem" onclick="aiChatQ('How to reduce my tax bill?')">Tax Tips?</button>
          <button class="ai-btn-sec" style="font-size:.75rem;padding:.35rem .75rem" onclick="aiChatQ('What is a good savings rate?')">Savings Rate?</button>
        </div>
      </div>
    </div>

  </main><!-- /main -->
</div><!-- /layout -->

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
var fmt=function(v){return '$'+Math.round(v).toLocaleString();};
var pct=function(v){return v.toFixed(1)+'%';};
var ovChart=null,bgChart=null,nwChart=null,invChart=null,invPie=null,retChart=null;

/* ── Panel Nav ── */
function aiPanel(name){
  document.querySelectorAll('.ai-panel').forEach(function(p){p.classList.remove('active');});
  document.querySelectorAll('.ai-nav-item').forEach(function(b){b.classList.remove('active');});
  document.getElementById('panel-'+name).classList.add('active');
  event.currentTarget.classList.add('active');
}

/* ── Quick Profile Calc ── */
function aiQuickCalc(){
  var income=parseFloat(document.getElementById('qp-income').value)||0;
  var expense=parseFloat(document.getElementById('qp-expense').value)||0;
  var savings=parseFloat(document.getElementById('qp-savings').value)||0;
  var debt=parseFloat(document.getElementById('qp-debt').value)||0;
  var efund=parseFloat(document.getElementById('qp-efund').value)||0;
  var age=parseFloat(document.getElementById('qp-age').value)||30;
  var retAge=parseFloat(document.getElementById('qp-retire-age').value)||65;
  var invest=parseFloat(document.getElementById('qp-invest').value)||0;
  var surplus=income-expense;
  var sr=income>0?surplus/income*100:0;
  var dti=income>0?(expense/income)*100:0;
  var efMonths=expense>0?efund/expense:0;
  var years=Math.max(retAge-age,1);
  var projRetire=savings*Math.pow(1.07,years)+invest*(Math.pow(1.07,years)-1)/0.07*12;

  // Stats
  document.getElementById('ov-income').textContent=fmt(income);
  document.getElementById('ov-expense').textContent=fmt(expense);
  document.getElementById('ov-surplus').textContent=fmt(surplus);
  document.getElementById('ov-surplus-pct').textContent=(sr>=0?'&#x2191; '+pct(sr)+' savings rate':'&#x2193; Deficit');
  document.getElementById('ov-networth').textContent=fmt(savings-debt);
  document.getElementById('ov-debt').textContent=fmt(debt);
  document.getElementById('ov-efund').textContent=efMonths.toFixed(1)+' mo';

  // Health score
  var scores={ef:Math.min(efMonths/6*100,100),sr:Math.min(sr/20*100,100),dti:Math.max(100-(dti-28)*2,0),rt:Math.min(projRetire/(income*12*25)*100,100)};
  var total=Math.round((scores.ef*0.25+scores.sr*0.3+scores.dti*0.2+scores.rt*0.25));

  document.getElementById('bar-ef').style.width=scores.ef+'%';
  document.getElementById('bar-ef-lbl').textContent=efMonths.toFixed(1)+'/6 months ('+Math.round(scores.ef)+'%)';
  document.getElementById('bar-sr').style.width=scores.sr+'%';
  document.getElementById('bar-sr-lbl').textContent=pct(sr)+' savings rate (target: 20%)';
  document.getElementById('bar-dti').style.width=scores.dti+'%';
  document.getElementById('bar-dti-lbl').textContent=pct(dti)+' DTI (target: <28%)';
  document.getElementById('bar-rt').style.width=scores.rt+'%';
  document.getElementById('bar-rt-lbl').textContent=fmt(projRetire)+' projected ('+Math.round(scores.rt)+'% of target)';

  // Hero rings
  var circ=213.6;
  document.getElementById('hero-health').textContent=total;
  document.getElementById('hero-ring-1').style.strokeDashoffset=circ-(circ*total/100);
  document.getElementById('hero-savings').textContent=pct(sr);
  document.getElementById('hero-ring-2').style.strokeDashoffset=circ-(circ*Math.min(sr,100)/100);
  document.getElementById('hero-retire').textContent=Math.round(scores.rt)+'%';
  document.getElementById('hero-ring-3').style.strokeDashoffset=circ-(circ*scores.rt/100);

  // AI Insight
  var tips=[];
  if(efMonths<3) tips.push('&#9888;&#65039; Emergency fund covers only '+efMonths.toFixed(1)+' months -- build to 6 months as your #1 priority.');
  if(sr<10) tips.push('&#128179; Savings rate of '+pct(sr)+' is below 10% -- aim to cut discretionary spending to reach 20%.');
  if(dti>36) tips.push('&#128279; Debt-to-income of '+pct(dti)+' is above the 36% recommended limit -- accelerate debt payoff.');
  if(scores.rt<50) tips.push('&#127958;&#65039; Retirement is under-funded -- increase monthly investing by $'+(Math.round((income*0.15-invest)/100)*100)+' to get on track.');
  if(tips.length===0) tips.push('&#9989; Great financial health! Focus on optimizing investments and minimizing taxes to build long-term wealth.');
  document.getElementById('ai-insight-text').textContent=tips.join(' ');

  document.getElementById('ai-score-card').style.display='block';

  // Chart
  if(ovChart) ovChart.destroy();
  var ctx=document.getElementById('overview-chart').getContext('2d');
  ovChart=new Chart(ctx,{type:'bar',data:{
    labels:['Income','Expenses','Savings/Invest','Surplus'],
    datasets:[{data:[income,expense,invest,surplus],backgroundColor:['rgba(0,200,150,.7)','rgba(239,68,68,.7)','rgba(59,130,246,.7)','rgba(139,92,246,.7)'],borderRadius:6}]
  },options:{plugins:{legend:{display:false}},scales:{y:{ticks:{callback:function(v){return '$'+Math.round(v/1000)+'k';}}}}}});
  document.getElementById('overview-chart-card').style.display='block';
}
function aiScrollDown(){document.getElementById('ai-score-card').scrollIntoView({behavior:'smooth'});}

/* ── Health Score ── */
function calcHealthScore(){
  var ef=parseFloat(document.getElementById('fh-ef').value)||0;
  var sr=parseFloat(document.getElementById('fh-sr').value)||0;
  var dti=parseFloat(document.getElementById('fh-dti').value)||0;
  var div=parseFloat(document.getElementById('fh-div').value)||1;
  var budget=parseFloat(document.getElementById('fh-budget').value)||2;
  var ret=parseFloat(document.getElementById('fh-ret').value)||0;
  var credit=parseFloat(document.getElementById('fh-credit').value)||2;
  var ins=parseFloat(document.getElementById('fh-ins').value)||2;

  var scores={
    'Emergency Fund':Math.min(ef/6*10,10),
    'Savings Rate':Math.min(sr/20*10,10),
    'Debt-to-Income':Math.max(10-(dti-20)*0.2,0),
    'Diversification':div,
    'Budget Tracking':budget,
    'Retirement Saving':Math.min(ret/15*10,10),
    'Credit Score':credit,
    'Insurance':ins,
  };
  var total=Object.values(scores).reduce(function(a,b){return a+b;},0)/8;
  var score=Math.round(total*10);
  var grade=score>=90?'&#127942; Excellent':score>=75?'&#9989; Good':score>=60?'&#9888;&#65039; Fair':'&#10060; Needs Work';

  document.getElementById('health-score').textContent=score;
  document.getElementById('health-grade').textContent=grade;

  var rows='';
  Object.entries(scores).forEach(function(e){
    var s=e[1].toFixed(1);
    var color=e[1]>=7?'var(--ai-primary)':e[1]>=5?'var(--ai-orange)':'var(--ai-red)';
    rows+='<div class="ai-result-row"><div class="ai-result-row__lbl">'+e[0]+'</div><div style="display:flex;align-items:center;gap:.5rem;flex:1;margin:0 1rem"><div style="flex:1;height:6px;background:rgba(255,255,255,.1);border-radius:3px"><div style="width:'+e[1]*10+'%;height:100%;background:'+color+';border-radius:3px;transition:width .8s"></div></div></div><div class="ai-result-row__val" style="color:'+color+'">'+s+'/10</div></div>';
  });
  document.getElementById('health-breakdown').innerHTML=rows;

  var tips=[];
  if(ef<3) tips.push('&#128737;&#65039; Build emergency fund to 6 months of expenses first.');
  if(sr<15) tips.push('&#128176; Increase savings rate to at least 15-20% of income.');
  if(dti>36) tips.push('&#128179; Pay down high-interest debt to lower your DTI below 36%.');
  if(ret<10) tips.push('&#127958;&#65039; Aim to contribute 10-15% of salary to retirement accounts.');
  if(div<6) tips.push('&#128200; Diversify into more asset classes -- consider index funds + REITs.');
  if(credit<7) tips.push('&#128179; Improve credit score: pay on time, reduce utilization below 30%.');
  document.getElementById('health-tips').textContent=tips.length?tips.join(' '):'&#9989; You\'re in excellent financial shape! Consider fine-tuning your investment strategy.';

  document.getElementById('health-results').style.display='block';
}

/* ── AI Budget ── */
function calcAIBudget(){
  var income=parseFloat(document.getElementById('bg2-income').value)||1;
  var cats=[
    {n:'Housing',v:parseFloat(document.getElementById('bg2-housing').value)||0,rule:50,type:'need'},
    {n:'Food',v:parseFloat(document.getElementById('bg2-food').value)||0,rule:50,type:'need'},
    {n:'Transport',v:parseFloat(document.getElementById('bg2-transport').value)||0,rule:50,type:'need'},
    {n:'Utilities',v:parseFloat(document.getElementById('bg2-utils').value)||0,rule:50,type:'need'},
    {n:'Healthcare',v:parseFloat(document.getElementById('bg2-health').value)||0,rule:50,type:'need'},
    {n:'Entertainment',v:parseFloat(document.getElementById('bg2-fun').value)||0,rule:30,type:'want'},
    {n:'Debt Payments',v:parseFloat(document.getElementById('bg2-debt').value)||0,rule:50,type:'need'},
    {n:'Savings',v:parseFloat(document.getElementById('bg2-save').value)||0,rule:20,type:'save'},
    {n:'Other',v:parseFloat(document.getElementById('bg2-other').value)||0,rule:30,type:'want'},
  ];
  var total=cats.reduce(function(a,c){return a+c.v;},0);
  var surplus=income-total;
  var rows='';
  cats.forEach(function(c){var pct=c.v/income*100;var ok=pct<=(c.rule+2);rows+='<div class="ai-result-row"><div class="ai-result-row__lbl">'+c.n+'</div><div style="flex:1;margin:0 .75rem;display:flex;align-items:center;gap:.5rem"><div style="flex:1;height:5px;background:rgba(255,255,255,.1);border-radius:3px"><div style="width:'+Math.min(pct/c.rule*100,100)+'%;height:100%;background:'+(ok?'var(--ai-primary)':'var(--ai-red)')+';border-radius:3px"></div></div></div><div style="min-width:80px;text-align:right"><span class="ai-result-row__val">'+(fmt(c.v))+'</span> <span style="font-size:.75rem;color:var(--ai-muted)">'+pct.toFixed(0)+'%</span></div></div>';});
  rows+='<div class="ai-result-row" style="margin-top:.5rem;padding-top:.75rem;border-top:2px solid var(--ai-border)"><div class="ai-result-row__lbl" style="font-weight:700">Monthly Surplus</div><div class="ai-result-row__val '+(surplus>=0?'green':'red')+'">'+fmt(surplus)+'</div></div>';
  document.getElementById('budget-rows').innerHTML=rows;

  // Chart
  var needs=cats.filter(function(c){return c.type==='need';}).reduce(function(a,c){return a+c.v;},0);
  var wants=cats.filter(function(c){return c.type==='want';}).reduce(function(a,c){return a+c.v;},0);
  var saves=cats.filter(function(c){return c.type==='save';}).reduce(function(a,c){return a+c.v;},0);
  if(bgChart) bgChart.destroy();
  var ctx=document.getElementById('budget-chart').getContext('2d');
  bgChart=new Chart(ctx,{type:'bar',data:{
    labels:['Needs (50%)','Wants (30%)','Savings (20%)'],
    datasets:[
      {label:'Actual',data:[needs/income*100,wants/income*100,saves/income*100],backgroundColor:['rgba(59,130,246,.7)','rgba(245,158,11,.7)','rgba(0,200,150,.7)'],borderRadius:6},
      {label:'50/30/20 Target',data:[50,30,20],backgroundColor:['rgba(59,130,246,.2)','rgba(245,158,11,.2)','rgba(0,200,150,.2)'],borderRadius:6},
    ]
  },options:{plugins:{legend:{position:'top'}},scales:{y:{max:70,ticks:{callback:function(v){return v+'%';}}}}}});

  var tips=[];
  if(needs/income>0.55) tips.push('&#127968; Needs exceed 55% of income -- consider refinancing, finding cheaper housing, or reducing fixed costs.');
  if(wants/income>0.35) tips.push('&#127828; Discretionary spending is high -- set a weekly entertainment budget to stay in the 30% range.');
  if(saves/income<0.15) tips.push('&#128176; Savings rate below 15% -- automate savings transfers on payday to remove the temptation to spend.');
  if(surplus<0) tips.push('&#128680; You\'re spending more than you earn! Cut at least '+fmt(-surplus)+'/month immediately to avoid debt spiral.');
  document.getElementById('budget-ai-text').textContent=tips.length?tips.join(' '):'&#9989; Great budgeting! Your spending is well-balanced. Focus on investing the surplus for long-term wealth.';
  document.getElementById('budget-results').style.display='block';
}

/* ── Net Worth ── */
function calcNetWorth(){
  var assets=['nw-cash','nw-ret','nw-invest','nw-home','nw-car','nw-other'].reduce(function(a,id){return a+(parseFloat(document.getElementById(id).value)||0);},0);
  var liab=['nw-mortgage','nw-autoloan','nw-student','nw-cc','nw-personal','nw-otherliab'].reduce(function(a,id){return a+(parseFloat(document.getElementById(id).value)||0);},0);
  var net=assets-liab;
  document.getElementById('nw-total-assets').textContent=fmt(assets);
  document.getElementById('nw-total-liab').textContent=fmt(liab);
  document.getElementById('nw-net').textContent=fmt(net);
  if(nwChart) nwChart.destroy();
  var ctx=document.getElementById('nw-chart').getContext('2d');
  nwChart=new Chart(ctx,{type:'doughnut',data:{
    labels:['Assets','Liabilities'],
    datasets:[{data:[assets,liab],backgroundColor:['rgba(0,200,150,.7)','rgba(239,68,68,.7)'],borderWidth:0}]
  },options:{plugins:{legend:{position:'right'}},cutout:'55%'}});
  var age=parseFloat(document.getElementById('qp-age').value)||35;
  var income=parseFloat(document.getElementById('qp-income').value)||5000;
  var benchmark=income*12*age/10;
  var tips='Your net worth is '+fmt(net)+'. ';
  tips+=net<benchmark?'At age '+age+', the benchmark is '+fmt(benchmark)+' -- increase investments to close the gap. ':'You\'re ahead of the '+fmt(benchmark)+' age benchmark! ';
  tips+=liab/assets>0.7?'Debt represents over 70% of your assets -- prioritize paying down high-rate debt first. ':'Debt-to-asset ratio is healthy. ';
  document.getElementById('nw-ai-text').textContent=tips;
  document.getElementById('nw-results').style.display='block';
}

/* ── Debt Optimizer ── */
function aiAddDebt(){
  var row=document.createElement('div');
  row.className='debt-row';
  row.style.cssText='display:grid;grid-template-columns:2fr 1fr 1fr 1fr auto;gap:.5rem;align-items:end;margin-bottom:.5rem';
  row.innerHTML='<div class="ai-field" style="margin:0"><label>Debt Name</label><input type="text" value="New Debt" class="d-name"></div><div class="ai-field" style="margin:0"><label>Balance ($)</label><input type="number" value="5000" class="d-bal"></div><div class="ai-field" style="margin:0"><label>Rate (%)</label><input type="number" value="15" step=".1" class="d-rate"></div><div class="ai-field" style="margin:0"><label>Min Payment ($)</label><input type="number" value="100" class="d-min"></div><button onclick="this.closest(\'.debt-row\').remove()" style="background:rgba(239,68,68,.15);color:var(--ai-red);border:none;border-radius:8px;padding:.6rem .8rem;cursor:pointer;align-self:flex-end;font-size:1rem">&#10005;</button>';
  document.getElementById('debt-list').appendChild(row);
}

function calcDebtOptimizer(){
  var rows=document.querySelectorAll('.debt-row');
  var debts=[];
  rows.forEach(function(r){
    debts.push({name:r.querySelector('.d-name').value,bal:parseFloat(r.querySelector('.d-bal').value)||0,rate:parseFloat(r.querySelector('.d-rate').value)||0,min:parseFloat(r.querySelector('.d-min').value)||0});
  });
  var extra=parseFloat(document.getElementById('debt-extra').value)||0;
  var strategy=document.getElementById('debt-strategy').value;
  var sorted=[...debts];
  if(strategy==='avalanche') sorted.sort(function(a,b){return b.rate-a.rate;});
  else sorted.sort(function(a,b){return a.bal-b.bal;});

  var totalMinPay=debts.reduce(function(a,d){return a+d.min;},0);
  var totalPay=totalMinPay+extra;
  var totalInterest=0,months=0;
  var remaining=sorted.map(function(d){return {name:d.name,bal:d.bal,rate:d.rate/100/12,min:d.min,paid:0};});
  var m=0;
  while(remaining.some(function(d){return d.bal>0;})&&m<600){
    m++;
    var budget=totalPay;
    remaining.forEach(function(d){if(d.bal>0){var int=d.bal*d.rate;totalInterest+=int;var pay=Math.min(d.min,d.bal+int);d.bal=Math.max(d.bal+int-pay,0);budget-=pay;}});
    // Extra to first non-zero
    var first=remaining.find(function(d){return d.bal>0;});
    if(first&&budget>0){var pay2=Math.min(budget,first.bal);first.bal-=pay2;}
  }
  months=m;

  var tbody='';
  sorted.forEach(function(d,i){
    var r=d.rate/100/12;var n=d.bal>0?Math.log(d.min/(d.min-d.bal*r))/Math.log(1+r):0;
    var int=d.min*Math.max(n,1)-d.bal;
    tbody+='<tr><td>'+d.name+'</td><td>'+fmt(d.bal)+'</td><td>'+d.rate.toFixed(1)+'%</td><td>'+(i+1)+'</td><td>~'+Math.round(Math.max(n,1))+'</td><td>'+fmt(Math.max(int,0))+'</td></tr>';
  });
  document.getElementById('debt-tbody').innerHTML=tbody;

  var comparison='<div class="ai-result-row"><div class="ai-result-row__lbl">Strategy</div><div class="ai-result-row__val blue">'+document.getElementById('debt-strategy').options[document.getElementById('debt-strategy').selectedIndex].text.split('--')[0].trim()+'</div></div>';
  comparison+='<div class="ai-result-row"><div class="ai-result-row__lbl">Total Monthly Payment</div><div class="ai-result-row__val">'+fmt(totalPay)+'</div></div>';
  comparison+='<div class="ai-result-row"><div class="ai-result-row__lbl">Estimated Payoff</div><div class="ai-result-row__val green">'+months+' months ('+Math.round(months/12*10)/10+' years)</div></div>';
  comparison+='<div class="ai-result-row"><div class="ai-result-row__lbl">Total Interest Saved vs Min Only</div><div class="ai-result-row__val green">~'+fmt(extra*months*0.7)+'</div></div>';
  document.getElementById('debt-comparison').innerHTML=comparison;

  var totalDebt=debts.reduce(function(a,d){return a+d.bal;},0);
  document.getElementById('debt-ai-text').textContent='With '+fmt(extra)+'/month extra, you\'ll pay off '+fmt(totalDebt)+' in debt in about '+months+' months. '+(strategy==='avalanche'?'The Avalanche method saves the most interest -- stick with it even when progress feels slow initially.':'The Snowball method gives quick wins to stay motivated -- celebrate each debt you eliminate!')+' Consider moving high-rate debt to a 0% balance transfer card if your credit score is 700+.';
  document.getElementById('debt-results').style.display='block';
}

/* ── Investment ── */
function calcInvestment(){
  var cur=parseFloat(document.getElementById('inv-current').value)||0;
  var mo=parseFloat(document.getElementById('inv-monthly').value)||0;
  var yrs=parseFloat(document.getElementById('inv-years').value)||20;
  var r=parseFloat(document.getElementById('inv-risk').value)/100/12;
  var n=yrs*12;
  var proj=cur*Math.pow(1+r,n)+mo*(Math.pow(1+r,n)-1)/r;
  var contrib=cur+mo*n;
  var gain=proj-contrib;

  document.getElementById('inv-proj').textContent=fmt(proj);
  document.getElementById('inv-contrib').textContent=fmt(contrib);
  document.getElementById('inv-gain').textContent=fmt(gain);
  document.getElementById('inv-mult').textContent=(proj/Math.max(contrib,1)).toFixed(2)+'x';

  if(invChart) invChart.destroy();
  var labels=[],data1=[],data2=[];
  for(var yr=0;yr<=yrs;yr+=Math.max(1,Math.floor(yrs/10))){
    var nm=yr*12,b=cur*Math.pow(1+r,nm)+mo*(Math.pow(1+r,nm)-1)/Math.max(r,0.0001);
    labels.push(yr+'yr');data1.push(Math.round(b));data2.push(Math.round(cur+mo*nm));
  }
  var ctx=document.getElementById('inv-chart').getContext('2d');
  invChart=new Chart(ctx,{type:'line',data:{labels:labels,datasets:[{label:'Portfolio Value',data:data1,borderColor:'var(--ai-primary)',backgroundColor:'rgba(0,200,150,.1)',fill:true,tension:.3},{label:'Total Contributed',data:data2,borderColor:'var(--ai-blue)',borderDash:[5,5],fill:false}]},options:{plugins:{legend:{position:'top'}},scales:{y:{ticks:{callback:function(v){return '$'+Math.round(v/1000)+'k';}}}}}});

  var stocks=parseFloat(document.getElementById('inv-stocks').value)||0;
  var bonds=parseFloat(document.getElementById('inv-bonds').value)||0;
  var reits=parseFloat(document.getElementById('inv-reits').value)||0;
  var cash=parseFloat(document.getElementById('inv-cash').value)||0;
  if(invPie) invPie.destroy();
  var ctx2=document.getElementById('inv-pie').getContext('2d');
  invPie=new Chart(ctx2,{type:'doughnut',data:{labels:['Stocks','Bonds','REITs','Cash'],datasets:[{data:[stocks,bonds,reits,cash],backgroundColor:['rgba(0,200,150,.8)','rgba(59,130,246,.8)','rgba(245,158,11,.8)','rgba(139,92,246,.8)'],borderWidth:0}]},options:{plugins:{legend:{position:'right'}},cutout:'60%'}});

  var tips='Your '+fmt(cur)+' portfolio is projected to grow to '+fmt(proj)+' in '+yrs+' years. ';
  tips+=gain>contrib?'Investment gains ('+fmt(gain)+') exceed your total contributions -- compound interest is working!':'Continue contributing monthly to accelerate growth. ';
  if(stocks>80) tips+=' Consider adding bonds or REITs to reduce volatility as you approach your goal.';
  if(stocks<40) tips+=' For '+yrs+'-year horizons, a higher stock allocation typically outperforms over time.';
  document.getElementById('inv-ai-text').textContent=tips;
  document.getElementById('invest-results').style.display='block';
}

/* ── Retirement Plan ── */
function calcRetirePlan(){
  var age=parseFloat(document.getElementById('rp-age').value)||35;
  var ret=parseFloat(document.getElementById('rp-retire').value)||65;
  var saved=parseFloat(document.getElementById('rp-saved').value)||0;
  var mo=parseFloat(document.getElementById('rp-contrib').value)||0;
  var match=parseFloat(document.getElementById('rp-match').value)||0;
  var r=parseFloat(document.getElementById('rp-return').value)/100/12;
  var desired=parseFloat(document.getElementById('rp-income').value)||5000;
  var ss=parseFloat(document.getElementById('rp-ss').value)||0;
  var inf=parseFloat(document.getElementById('rp-inf').value)/100;
  var life=parseFloat(document.getElementById('rp-life').value)||90;
  var years=Math.max(ret-age,1),retYears=Math.max(life-ret,20);
  var n=years*12,totalMo=mo+match;
  var nest=saved*Math.pow(1+r,n)+totalMo*(Math.pow(1+r,n)-1)/Math.max(r,0.0001);
  var income4=nest*0.04/12;
  var totalIncome=income4+ss;
  var gap=desired-totalIncome;
  var needed=Math.max(desired-ss,0)*12*retYears;

  document.getElementById('rp-nest').textContent=fmt(nest);
  document.getElementById('rp-monthly-inc').textContent=fmt(totalIncome)+'/mo';
  document.getElementById('rp-gap').textContent=gap>0?'-'+fmt(gap)+'/mo':'+'+fmt(-gap)+'/mo';
  document.getElementById('rp-status').innerHTML=gap<=0?'<span style="color:var(--ai-primary)">&#9989; On Track</span>':'<span style="color:var(--ai-red)">&#9888;&#65039; Gap</span>';

  if(retChart) retChart.destroy();
  var labels=[],data=[];
  for(var yr=0;yr<=years;yr+=Math.max(1,Math.floor(years/12))){
    var m2=yr*12,b=saved*Math.pow(1+r,m2)+totalMo*(Math.pow(1+r,m2)-1)/Math.max(r,0.0001);
    labels.push((age+yr)+'');data.push(Math.round(b));
  }
  var ctx=document.getElementById('retire-chart').getContext('2d');
  retChart=new Chart(ctx,{type:'line',data:{labels:labels,datasets:[{label:'Retirement Savings',data:data,borderColor:'var(--ai-purple)',backgroundColor:'rgba(139,92,246,.1)',fill:true,tension:.3}]},options:{plugins:{legend:{display:false}},scales:{y:{ticks:{callback:function(v){return '$'+Math.round(v/1000)+'k';}}}}}});

  var tips='';
  if(gap>0) tips='&#9888;&#65039; You\'re short by '+fmt(gap)+'/month in retirement. Increase contributions by '+fmt(Math.max(gap*12/((Math.pow(1+r,n)-1)/r*12),100).toFixed(0))+'/month OR delay retirement by '+Math.ceil(gap/20)+' years. ';
  else tips='&#9989; You\'re on track! Your '+fmt(nest)+' nest egg should provide '+fmt(totalIncome)+'/month including Social Security. ';
  tips+='Consider a Roth conversion in low-income years to diversify tax exposure in retirement. Max out your 401k ($23,500 in 2026) and IRA ($7,000) first.';
  document.getElementById('retire-ai-text').textContent=tips;
  document.getElementById('retire-results').style.display='block';
}

/* ── Tax Optimizer ── */
function calcTaxOptimizer(){
  var income=parseFloat(document.getElementById('tax-income').value)||0;
  var status=document.getElementById('tax-status').value;
  var k401=parseFloat(document.getElementById('tax-401k').value)||0;
  var ira=parseFloat(document.getElementById('tax-ira').value)||0;
  var hsa=parseFloat(document.getElementById('tax-hsa').value)||0;
  var mort=parseFloat(document.getElementById('tax-mort').value)||0;
  var charity=parseFloat(document.getElementById('tax-charity').value)||0;
  var salt=parseFloat(document.getElementById('tax-salt').value)||0;
  var biz=parseFloat(document.getElementById('tax-biz').value)||0;
  var other=parseFloat(document.getElementById('tax-other').value)||0;

  var stdDed={single:14600,mfj:29200,mfs:14600,hoh:21900}[status]||14600;
  var aboveLine=k401+ira+hsa+biz;
  var agi=income-aboveLine;
  var itemized=Math.min(salt,10000)+mort+charity+other;
  var deduction=Math.max(itemized,stdDed);
  var taxable=Math.max(agi-deduction,0);

  var brackets_mfj=[[0,23200,0.10],[23200,94300,0.12],[94300,201050,0.22],[201050,383900,0.24],[383900,487450,0.32],[487450,731200,0.35],[731200,Infinity,0.37]];
  var brackets_single=[[0,11600,0.10],[11600,47150,0.12],[47150,100525,0.22],[100525,191950,0.24],[191950,243725,0.32],[243725,609350,0.35],[609350,Infinity,0.37]];
  var brks=status==='mfj'?brackets_mfj:brackets_single;
  var tax=0,marginal=0.10;
  brks.forEach(function(b){if(taxable>b[0]){var amt=Math.min(taxable,b[1])-b[0];tax+=amt*b[2];marginal=b[2];}});
  var effective=income>0?tax/income*100:0;

  var rows='<div class="ai-result-row"><div class="ai-result-row__lbl">Gross Income</div><div class="ai-result-row__val">'+fmt(income)+'</div></div>';
  rows+='<div class="ai-result-row"><div class="ai-result-row__lbl">Above-Line Deductions (401k/IRA/HSA)</div><div class="ai-result-row__val green">-'+fmt(aboveLine)+'</div></div>';
  rows+='<div class="ai-result-row"><div class="ai-result-row__lbl">Adjusted Gross Income</div><div class="ai-result-row__val">'+fmt(agi)+'</div></div>';
  rows+='<div class="ai-result-row"><div class="ai-result-row__lbl">'+(itemized>stdDed?'Itemized':'Standard')+' Deduction</div><div class="ai-result-row__val green">-'+fmt(deduction)+'</div></div>';
  rows+='<div class="ai-result-row"><div class="ai-result-row__lbl">Taxable Income</div><div class="ai-result-row__val">'+fmt(taxable)+'</div></div>';
  rows+='<div class="ai-result-row"><div class="ai-result-row__lbl">Federal Income Tax Owed</div><div class="ai-result-row__val red">'+fmt(tax)+'</div></div>';
  rows+='<div class="ai-result-row"><div class="ai-result-row__lbl">Effective Tax Rate</div><div class="ai-result-row__val">'+effective.toFixed(1)+'%</div></div>';
  rows+='<div class="ai-result-row"><div class="ai-result-row__lbl">Marginal Tax Rate</div><div class="ai-result-row__val">'+(marginal*100).toFixed(0)+'%</div></div>';
  document.getElementById('tax-rows').innerHTML='<h3>&#129534; Tax Calculation</h3>'+rows;

  var max401k=23500,maxIRA=7000,maxHSA=(status==='mfj'?8300:4150);
  var tips=[];
  if(k401<max401k) tips.push('&#128188; Increase 401k by '+fmt(max401k-k401)+' to max -- saves '+fmt((max401k-k401)*marginal)+' in taxes at your '+Math.round(marginal*100)+'% rate.');
  if(ira<maxIRA) tips.push('&#128202; Max out IRA ('+fmt(maxIRA-ira)+' more) for additional tax savings.');
  if(hsa<maxHSA) tips.push('&#127973; HSA contribution of '+fmt(maxHSA-hsa)+' more = triple tax advantage (deductible, grows tax-free, withdrawals tax-free for medical).');
  if(itemized<stdDed) tips.push('&#128203; You\'re using the standard deduction ('+fmt(stdDed)+'). Bundle charitable donations in alternate years to exceed standard deduction and itemize.');
  document.getElementById('tax-ai-text').textContent=tips.length?tips.join(' '):'&#9989; Your tax strategy is well-optimized. Consider tax-loss harvesting in taxable accounts.';
  document.getElementById('tax-results').style.display='block';
}

/* ── Emergency Fund ── */
function calcEmergencyFund(){
  var expense=parseFloat(document.getElementById('ef-expense').value)||0;
  var months=parseFloat(document.getElementById('ef-stability').value)||6;
  months+=parseFloat(document.getElementById('ef-deps').value)||0;
  var current=parseFloat(document.getElementById('ef-current').value)||0;
  var save=parseFloat(document.getElementById('ef-save').value)||0;
  var rate=parseFloat(document.getElementById('ef-rate').value)/100/12;
  var target=expense*months;
  var gap=Math.max(target-current,0);
  var mthsToGoal=gap>0&&save>0?Math.ceil(gap/save):0;
  var interest=current*Math.pow(1+rate,mthsToGoal)+(save>0?save*(Math.pow(1+rate,mthsToGoal)-1)/rate:0);

  var html='<h3>&#128737;&#65039; Emergency Fund Analysis</h3>';
  html+='<div class="ai-result-row"><div class="ai-result-row__lbl">Recommended Fund Size</div><div class="ai-result-row__val green">'+fmt(target)+' ('+months+' months)</div></div>';
  html+='<div class="ai-result-row"><div class="ai-result-row__lbl">Current Fund</div><div class="ai-result-row__val '+(current>=target?'green':'red')+'">'+fmt(current)+' ('+((current/expense)).toFixed(1)+' months)</div></div>';
  html+='<div class="ai-result-row"><div class="ai-result-row__lbl">Gap to Goal</div><div class="ai-result-row__val '+(gap<=0?'green':'red')+'">'+(gap<=0?'&#9989; Fully Funded!':fmt(gap)+' needed')+'</div></div>';
  if(gap>0){
    html+='<div class="ai-result-row"><div class="ai-result-row__lbl">Time to Full Fund</div><div class="ai-result-row__val blue">'+mthsToGoal+' months</div></div>';
    html+='<div class="ai-result-row"><div class="ai-result-row__lbl">Interest Earned in HYSA</div><div class="ai-result-row__val green">'+fmt(Math.max(interest-current-save*mthsToGoal,0))+'</div></div>';
  }
  document.getElementById('ef-result-content').innerHTML=html;
  var tips='Your emergency fund should cover '+months+' months ('+fmt(target)+') based on your job stability and dependents. ';
  tips+=current>=target?'&#9989; You\'re fully funded! Keep it in a high-yield savings account at 4%+ APY. ':'Save '+fmt(save)+'/month in a HYSA to reach your goal in '+mthsToGoal+' months. ';
  tips+='Never invest emergency funds -- keep them liquid in FDIC-insured accounts. Once funded, invest any surplus.';
  document.getElementById('ef-ai-text').textContent=tips;
  document.getElementById('ef-results').style.display='block';
}

/* ── AI Chat -- Real Claude API via WordPress AJAX ── */
var aiTyping=false;

function aiChat(){
  if(aiTyping)return;
  var input=document.getElementById('ai-chat-input');
  var q=input.value.trim();
  if(!q)return;
  var box=document.getElementById('ai-chat-box');
  box.innerHTML+='<div class="ai-chat-msg user">'+escHtml(q)+'</div>';
  input.value='';
  // Typing indicator
  var tid='ai-typing-'+Date.now();
  box.innerHTML+='<div class="ai-chat-msg ai" id="'+tid+'" style="opacity:.6">&#129302; <em>Thinking...</em></div>';
  box.scrollTop=box.scrollHeight;
  aiTyping=true;

  // Try real API first
  if(typeof fsAiAjax!=='undefined'){
    var fd=new FormData();
    fd.append('action','fs_ai_chat');
    fd.append('nonce',fsAiAjax.nonce);
    fd.append('message',q);
    fetch(fsAiAjax.url,{method:'POST',body:fd})
      .then(function(r){return r.json();})
      .then(function(data){
        var reply=data.success&&data.data&&data.data.reply?data.data.reply:aiFallback(q);
        document.getElementById(tid).innerHTML='&#129302; '+escHtml(reply);
        document.getElementById(tid).style.opacity='1';
        box.scrollTop=box.scrollHeight;
        aiTyping=false;
      })
      .catch(function(){
        document.getElementById(tid).innerHTML='&#129302; '+escHtml(aiFallback(q));
        document.getElementById(tid).style.opacity='1';
        box.scrollTop=box.scrollHeight;
        aiTyping=false;
      });
  } else {
    setTimeout(function(){
      document.getElementById(tid).innerHTML='&#129302; '+escHtml(aiFallback(q));
      document.getElementById(tid).style.opacity='1';
      box.scrollTop=box.scrollHeight;
      aiTyping=false;
    },700);
  }
}

function escHtml(s){return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');}

function aiFallback(msg){
  var m=msg.toLowerCase();
  if(m.match(/emergency|efund/)) return '&#128737;&#65039; Emergency fund = 3-6 months of essential expenses in a HYSA (4-5% APY). If self-employed or have dependents, aim for 6-12 months. Never invest it -- it must stay liquid and instantly accessible.';
  if(m.match(/budget|50.30.20|spending/)) return '&#128179; The 50/30/20 rule: 50% Needs (rent, food, utilities), 30% Wants (dining, entertainment), 20% Savings/Debt. Track every expense for 30 days to find spending leaks -- most people discover $200-500/month they can redirect.';
  if(m.match(/debt|loan|credit card/)) return '&#128279; Avalanche: Pay highest interest rate first (saves most money). Snowball: Pay smallest balance first (builds motivation). Both work -- pick one and automate it. Always pay at least the minimum on every debt.';
  if(m.match(/invest|stock|etf|index/)) return '&#128200; Order of operations: (1) Get full employer 401k match, (2) Max HSA if eligible, (3) Max Roth IRA $7,000/yr, (4) Max 401k $23,500/yr, (5) Taxable brokerage. Use VTI or VTSAX for simplicity. Time in market beats timing the market.';
  if(m.match(/tax|deduct|bracket/)) return '&#129534; Biggest tax reducers: 401k (pre-tax), HSA (triple tax benefit), business deductions. At 22% bracket, every $1,000 contributed to 401k saves you $220 in taxes. Bundle charitable donations in alternate years to exceed the standard deduction.';
  if(m.match(/retire|pension|401k|ira/)) return '&#127958;&#65039; The 4% rule: At retirement, withdraw 4% of portfolio annually. Need $5,000/month income &#x2192; need $1.5M saved. Max 401k ($23,500/yr) + IRA ($7,000/yr). Start early -- $300/mo at age 25 = ~$1M by 65 at 7% returns.';
  if(m.match(/crypto|bitcoin|btc/)) return '₿ Crypto = high-risk speculation. Limit to 5-10% max of your portfolio. Never invest emergency funds or retirement savings in crypto. If you do invest, stick to Bitcoin and Ethereum. Always use a hardware wallet for large amounts.';
  if(m.match(/mortgage|home|house|rent/)) return '&#127968; Rule: Housing costs should be under 28-30% of gross income. Save 20% down payment to avoid PMI. At 7% rate, a $350k mortgage costs ~$2,330/month. Run our mortgage calculator for your exact numbers.';
  if(m.match(/credit score|fico/)) return '&#128179; Credit score factors: Payment history (35%), Utilization (30% -- keep under 30%), Length of history (15%), Mix (10%), New credit (10%). Pay on time, pay down balances, and never close old accounts. Check your free report at AnnualCreditReport.com.';
  return '&#129302; Great question! Key financial principles: (1) Emergency fund first -- 3-6 months, (2) Get employer 401k match -- it\'s free money, (3) Pay debt over 7% interest, (4) Max Roth IRA $7k/yr, (5) Invest in low-cost index funds. Which area do you want to go deeper on? Try the panels on the left for your personalized numbers.';
}

function aiChatQ(q){
  document.getElementById('ai-chat-input').value=q;
  aiPanel('ai-chat');
  aiChat();
}

/* ── Export PDF ── */
function aiExportPDF(){
  if(typeof window.jspdf==='undefined'&&typeof window.jsPDF==='undefined'){alert('PDF loading… try again.');return;}
  var jsPDF=window.jspdf?window.jspdf.jsPDF:window.jsPDF;
  var doc=new jsPDF({orientation:'portrait',unit:'mm',format:'a4'});
  var W=doc.internal.pageSize.getWidth();
  doc.setFillColor(0,200,150);doc.rect(0,0,W,24,'F');
  doc.setTextColor(255,255,255);doc.setFontSize(14);doc.setFont('helvetica','bold');
  doc.text('FinanceSpots AI Dashboard Report',14,10);
  doc.setFontSize(9);doc.setFont('helvetica','normal');
  doc.text('Generated: '+new Date().toLocaleDateString('en-US',{year:'numeric',month:'long',day:'numeric'}),14,18);
  var y=32;
  var income=document.getElementById('ov-income').textContent;
  if(income!=='--'){
    doc.setFontSize(12);doc.setFont('helvetica','bold');doc.setTextColor(20,20,40);
    doc.text('Financial Overview',14,y);y+=7;
    [['Monthly Income',income],['Monthly Expenses',document.getElementById('ov-expense').textContent],['Net Worth',document.getElementById('ov-networth').textContent],['Emergency Fund',document.getElementById('ov-efund').textContent]].forEach(function(r,i){
      doc.setFillColor(i%2===0?248:255,i%2===0?250:255,i%2===0?252:255);doc.rect(14,y-4,W-28,10,'F');
      doc.setFont('helvetica','normal');doc.setFontSize(10);doc.setTextColor(80,80,100);doc.text(r[0],18,y+2);
      doc.setFont('helvetica','bold');doc.setTextColor(0,150,110);doc.text(r[1],W-18,y+2,{align:'right'});
      y+=11;
    });
  }
  doc.setFontSize(8);doc.setFont('helvetica','normal');doc.setTextColor(150,150,170);
  doc.text('Disclaimer: For educational purposes only. Consult a licensed financial advisor.',14,285);
  doc.text('© '+new Date().getFullYear()+' FinanceSpots.com',W-14,285,{align:'right'});
  doc.save('financespots_ai_dashboard_report.pdf');
}

/* ── Init ── */
window.addEventListener('load',function(){aiQuickCalc();});
</script>

<?php wp_footer(); ?>
</body>
</html>
