<?php /** Template Name: Privacy Policy */ get_header(); ?>
<div style="background:#0A0F1E;min-height:100vh;padding:80px 0;">
<div class="container" style="max-width:800px;">

<div style="text-align:center;margin-bottom:48px;">
    <span style="display:inline-block;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);color:#10B981;padding:6px 18px;border-radius:50px;font-size:.82rem;font-weight:700;margin-bottom:16px;">Legal</span>
    <h1 style="font-size:2.2rem;font-weight:900;color:#fff;margin:0 0 12px;">Privacy Policy</h1>
    <p style="color:#64748B;font-size:.9rem;">Last updated: <?php echo date('F j, Y'); ?></p>
</div>

<div style="background:#131929;border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:40px;">
<?php
$sections = [
  ['Introduction', 'FinanceSpots ("we", "our", or "us") operates the website financespots.com. This Privacy Policy explains how we collect, use, and protect your information when you use our website. By using FinanceSpots, you agree to the collection and use of information in accordance with this policy.'],
  ['Information We Collect', '<strong style="color:#CBD5E1;">Information you provide:</strong><br>&#x2022; Account registration: name and email address<br>&#x2022; Contact form submissions: name, email, and message<br>&#x2022; Newsletter subscriptions: email address<br><br><strong style="color:#CBD5E1;">Automatically collected information:</strong><br>&#x2022; Log data: IP address, browser type, pages visited, time and date of visit<br>&#x2022; Cookies and similar tracking technologies (see Cookie Policy below)'],
  ['How We Use Your Information', '&#x2022; To provide and maintain our services<br>&#x2022; To send you newsletters you have subscribed to<br>&#x2022; To respond to your inquiries and support requests<br>&#x2022; To analyze usage and improve our website<br>&#x2022; To display relevant advertisements via Google AdSense<br>&#x2022; To comply with legal obligations'],
  ['Cookies & Google AdSense', 'We use cookies to enhance your experience. Google AdSense, our advertising partner, uses cookies to serve ads based on your prior visits to our website and other sites on the internet. Google\'s use of advertising cookies enables it and its partners to serve ads based on your visit to our site.<br><br>You may opt out of personalized advertising by visiting <a href="https://www.google.com/settings/ads" style="color:#10B981;">Google Ads Settings</a>. You can also opt out via the <a href="http://www.aboutads.info/choices/" style="color:#10B981;">Network Advertising Initiative opt-out page</a>.<br><br>For more information about how Google uses your data, visit <a href="https://policies.google.com/technologies/partner-sites" style="color:#10B981;">Google Privacy & Terms</a>.'],
  ['Calculator Data', 'All calculations performed on FinanceSpots run entirely in your browser. We do <strong style="color:#fff;">not</strong> collect, store, transmit, or share any financial data you enter into our calculators. Your financial information never leaves your device.'],
  ['Third-Party Services', 'We use the following third-party services that may collect information:<br>&#x2022; <strong style="color:#CBD5E1;">Google AdSense</strong> -- advertising (see above)<br>&#x2022; <strong style="color:#CBD5E1;">Google Analytics</strong> -- website analytics<br>&#x2022; <strong style="color:#CBD5E1;">Stripe</strong> -- payment processing for PRO subscriptions (we do not store card details)<br>Each third-party service has its own privacy policy governing its use of your information.'],
  ['Data Retention', 'We retain your personal data only for as long as necessary. Account information is retained while your account is active. You may request deletion of your account and associated data at any time by contacting us.'],
  ['Your Rights', 'You have the right to:<br>&#x2022; Access the personal data we hold about you<br>&#x2022; Request correction of inaccurate data<br>&#x2022; Request deletion of your data<br>&#x2022; Opt out of marketing communications at any time<br>&#x2022; Lodge a complaint with your local data protection authority'],
  ['Children\'s Privacy', 'FinanceSpots is not directed to children under 13. We do not knowingly collect personal information from children under 13. If you believe we have collected information from a child, please contact us immediately.'],
  ['Changes to This Policy', 'We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last updated" date. Your continued use of the site after changes constitutes acceptance of the new policy.'],
  ['Contact Us', 'If you have questions about this Privacy Policy or how we handle your data, please contact us via our <a href="' . home_url('/contact/') . '" style="color:#10B981;">Contact page</a>.'],
];
foreach($sections as [$title,$body]):
?>
<div style="margin-bottom:36px;">
    <h2 style="font-size:1.1rem;font-weight:800;color:#10B981;margin:0 0 12px;padding-bottom:10px;border-bottom:1px solid rgba(255,255,255,.06);"><?php echo $title; ?></h2>
    <div style="color:#94A3B8;font-size:.9rem;line-height:1.85;"><?php echo $body; ?></div>
</div>
<?php endforeach; ?>
</div>

</div>
</div>
<?php get_footer(); ?>
