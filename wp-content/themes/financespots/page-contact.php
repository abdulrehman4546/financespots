<?php
/**
 * Template Name: Contact Us
 */
get_header();
?>

<div class="fs-contact-page">

    <!-- Hero -->
    <section class="fs-contact-hero">
        <div class="container">
            <div class="fs-contact-hero__inner">
                <span class="fs-badge fs-badge--green">&#128172; Get In Touch</span>
                <h1 class="fs-contact-hero__title">Contact Us</h1>
                <p class="fs-contact-hero__desc">Have a question, suggestion, or just want to say hello? We would love to hear from you. We typically respond within 24 hours.</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="fs-contact-main">
        <div class="container">
            <div class="fs-contact-grid">

                <!-- LEFT: Form -->
                <div class="fs-contact-form-wrap">
                    <h2 class="fs-contact-form-title">Send Us a Message</h2>
                    <p class="fs-contact-form-sub">Fill out the form below and we will get back to you as soon as possible.</p>

                    <form class="fs-contact-form" id="fs-contact-form" novalidate>
                        <div class="fs-contact-row">
                            <div class="fs-contact-field">
                                <label for="fc-name">Your Name <span class="fs-req">*</span></label>
                                <input type="text" id="fc-name" name="name" placeholder="Abdul Rahman" required>
                            </div>
                            <div class="fs-contact-field">
                                <label for="fc-email">Email Address <span class="fs-req">*</span></label>
                                <input type="email" id="fc-email" name="email" placeholder="you@example.com" required>
                            </div>
                        </div>
                        <div class="fs-contact-field">
                            <label for="fc-subject">Subject <span class="fs-req">*</span></label>
                            <select id="fc-subject" name="subject" required>
                                <option value="" disabled selected>Select a topic...</option>
                                <option value="general">General Question</option>
                                <option value="tool">Tool / Calculator Issue</option>
                                <option value="suggestion">Feature Suggestion</option>
                                <option value="bug">Report a Bug</option>
                                <option value="partnership">Partnership / Collaboration</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="fs-contact-field">
                            <label for="fc-message">Message <span class="fs-req">*</span></label>
                            <textarea id="fc-message" name="message" rows="6" placeholder="Tell us how we can help you..." required></textarea>
                        </div>
                        <button type="submit" class="fs-contact-submit" id="fc-submit">
                            <span class="fc-submit-text">Send Message</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                        <div class="fs-contact-success" id="fc-success" style="display:none;">
                            <span>&#10003;</span> Thank you! Your message has been sent. We will reply within 24 hours.
                        </div>
                        <div class="fs-contact-error" id="fc-error" style="display:none;">
                            &#9888; Please fill in all required fields correctly.
                        </div>
                    </form>
                </div>

                <!-- RIGHT: Info -->
                <div class="fs-contact-info">

                    <div class="fs-contact-info-card">
                        <h3>Contact Information</h3>

                        <div class="fs-contact-info-item">
                            <div class="fs-contact-info-icon">&#9202;</div>
                            <div>
                                <span class="fs-contact-info-label">Response Time</span>
                                <span class="fs-contact-info-val">Within 24 hours</span>
                            </div>
                        </div>

                        <div class="fs-contact-info-item">
                            <div class="fs-contact-info-icon">&#127760;</div>
                            <div>
                                <span class="fs-contact-info-label">Website</span>
                                <span class="fs-contact-info-val">financespots.com</span>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ -->
                    <div class="fs-contact-faq">
                        <h3>Frequently Asked Questions</h3>

                        <div class="fs-faq-item">
                            <button class="fs-faq-q" onclick="fsFaqToggle(this)">
                                Are all tools really free?
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                            </button>
                            <div class="fs-faq-a">Yes -- every calculator and tool on FinanceSpots is 100% free. No account, no credit card, no hidden fees. Ever.</div>
                        </div>

                        <div class="fs-faq-item">
                            <button class="fs-faq-q" onclick="fsFaqToggle(this)">
                                Is my financial data saved?
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                            </button>
                            <div class="fs-faq-a">No. All calculations run entirely in your browser. We never see, store, or share your financial data.</div>
                        </div>

                        <div class="fs-faq-item">
                            <button class="fs-faq-q" onclick="fsFaqToggle(this)">
                                Can I suggest a new tool?
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                            </button>
                            <div class="fs-faq-a">Absolutely! Use the form on this page and select "Feature Suggestion". We read every message and add community-requested tools regularly.</div>
                        </div>

                        <div class="fs-faq-item">
                            <button class="fs-faq-q" onclick="fsFaqToggle(this)">
                                Who built FinanceSpots?
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                            </button>
                            <div class="fs-faq-a">FinanceSpots was built by Abdul Rahman, an independent developer passionate about making financial tools accessible to everyone.</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

</div>

<style>
.fs-contact-page{background:#0A0F1E;min-height:100vh;}

/* Hero */
.fs-contact-hero{padding:80px 0 60px;background:linear-gradient(135deg,#0A0F1E 0%,#131929 100%);border-bottom:1px solid rgba(255,255,255,.07);}
.fs-contact-hero__inner{text-align:center;max-width:600px;margin:0 auto;}
.fs-contact-hero__title{font-size:clamp(2rem,4vw,2.8rem);font-weight:900;color:#fff;margin:16px 0 12px;}
.fs-contact-hero__desc{font-size:1.05rem;color:#94A3B8;line-height:1.7;}

/* Main grid */
.fs-contact-main{padding:60px 0 80px;}
.fs-contact-grid{display:grid;grid-template-columns:1fr 420px;gap:48px;align-items:start;}
@media(max-width:960px){.fs-contact-grid{grid-template-columns:1fr;gap:36px;}}

/* Form */
.fs-contact-form-wrap{background:#131929;border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:36px;}
.fs-contact-form-title{font-size:1.4rem;font-weight:800;color:#fff;margin:0 0 6px;}
.fs-contact-form-sub{color:#64748B;font-size:.875rem;margin-bottom:28px;}
.fs-contact-form{display:flex;flex-direction:column;gap:20px;}
.fs-contact-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
@media(max-width:560px){.fs-contact-row{grid-template-columns:1fr;}}
.fs-contact-field{display:flex;flex-direction:column;gap:7px;}
.fs-contact-field label{font-size:.82rem;font-weight:600;color:#94A3B8;letter-spacing:.03em;}
.fs-req{color:#EF4444;}
.fs-contact-field input,
.fs-contact-field select,
.fs-contact-field textarea{background:#0A0F1E;border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:12px 16px;color:#F1F5F9;font-size:.9rem;outline:none;transition:border-color .2s;width:100%;font-family:inherit;}
.fs-contact-field input:focus,
.fs-contact-field select:focus,
.fs-contact-field textarea:focus{border-color:#10B981;box-shadow:0 0 0 3px rgba(16,185,129,.12);}
.fs-contact-field select{cursor:pointer;}
.fs-contact-field select option{background:#131929;}
.fs-contact-field textarea{resize:vertical;min-height:140px;}
.fs-contact-submit{display:flex;align-items:center;justify-content:center;gap:8px;background:linear-gradient(135deg,#10B981,#0d9e6f);color:#fff;border:none;border-radius:12px;padding:14px 28px;font-size:1rem;font-weight:700;cursor:pointer;transition:all .25s;box-shadow:0 4px 20px rgba(16,185,129,.25);}
.fs-contact-submit:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(16,185,129,.4);}
.fs-contact-success{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);border-radius:10px;padding:14px 18px;color:#10B981;font-size:.9rem;font-weight:600;}
.fs-contact-error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);border-radius:10px;padding:14px 18px;color:#EF4444;font-size:.9rem;font-weight:600;}

/* Info card */
.fs-contact-info{display:flex;flex-direction:column;gap:24px;}
.fs-contact-info-card{background:#131929;border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:28px;}
.fs-contact-info-card h3,.fs-contact-faq h3{font-size:1.05rem;font-weight:800;color:#fff;margin:0 0 20px;}
.fs-contact-info-item{display:flex;align-items:flex-start;gap:14px;padding:12px 0;border-bottom:1px solid rgba(255,255,255,.05);}
.fs-contact-info-item:last-child{border-bottom:none;padding-bottom:0;}
.fs-contact-info-icon{width:38px;height:38px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.2);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
.fs-contact-info-label{display:block;font-size:.75rem;color:#64748B;font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;}
.fs-contact-info-val{display:block;color:#CBD5E1;font-size:.9rem;font-weight:500;text-decoration:none;}
a.fs-contact-info-val:hover{color:#10B981;}

/* FAQ */
.fs-contact-faq{background:#131929;border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:28px;}
.fs-faq-item{border-bottom:1px solid rgba(255,255,255,.06);}
.fs-faq-item:last-child{border-bottom:none;}
.fs-faq-q{width:100%;display:flex;align-items:center;justify-content:space-between;gap:12px;background:none;border:none;color:#CBD5E1;font-size:.88rem;font-weight:600;padding:14px 0;cursor:pointer;text-align:left;transition:color .2s;}
.fs-faq-q:hover{color:#10B981;}
.fs-faq-q svg{flex-shrink:0;transition:transform .25s;}
.fs-faq-q.open{color:#10B981;}
.fs-faq-q.open svg{transform:rotate(180deg);}
.fs-faq-a{font-size:.85rem;color:#64748B;line-height:1.65;padding:0 0 14px;display:none;}
.fs-faq-a.open{display:block;}
</style>

<script>
function fsFaqToggle(btn){
    var a = btn.nextElementSibling;
    var isOpen = btn.classList.contains('open');
    // Close all
    document.querySelectorAll('.fs-faq-q').forEach(function(b){ b.classList.remove('open'); });
    document.querySelectorAll('.fs-faq-a').forEach(function(a){ a.classList.remove('open'); });
    if(!isOpen){ btn.classList.add('open'); a.classList.add('open'); }
}

// Form submit
document.getElementById('fs-contact-form').addEventListener('submit', function(e){
    e.preventDefault();
    var name    = document.getElementById('fc-name').value.trim();
    var email   = document.getElementById('fc-email').value.trim();
    var subject = document.getElementById('fc-subject').value;
    var message = document.getElementById('fc-message').value.trim();
    var errEl   = document.getElementById('fc-error');
    var sucEl   = document.getElementById('fc-success');
    var btn     = document.getElementById('fc-submit');

    errEl.style.display = 'none';
    sucEl.style.display = 'none';

    if(!name || !email || !subject || !message){
        errEl.style.display = 'block';
        return;
    }
    if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){
        errEl.style.display = 'block';
        errEl.textContent = '&#9888; Please enter a valid email address.';
        return;
    }

    // Simulate send
    btn.disabled = true;
    btn.querySelector('.fc-submit-text').textContent = 'Sending...';
    setTimeout(function(){
        btn.disabled = false;
        btn.querySelector('.fc-submit-text').textContent = 'Send Message';
        sucEl.style.display = 'block';
        document.getElementById('fs-contact-form').reset();
    }, 1200);
});
</script>

<?php get_footer(); ?>
