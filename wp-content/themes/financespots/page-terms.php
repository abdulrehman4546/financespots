<?php /** Template Name: Terms of Service */ get_header(); ?>
<div style="background:#0A0F1E;min-height:100vh;padding:80px 0;">
<div class="container" style="max-width:800px;">

<div style="text-align:center;margin-bottom:48px;">
    <span style="display:inline-block;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);color:#10B981;padding:6px 18px;border-radius:50px;font-size:.82rem;font-weight:700;margin-bottom:16px;">Legal</span>
    <h1 style="font-size:2.2rem;font-weight:900;color:#fff;margin:0 0 12px;">Terms of Service</h1>
    <p style="color:#64748B;font-size:.9rem;">Last updated: <?php echo date('F j, Y'); ?></p>
</div>

<div style="background:#131929;border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:40px;">
<?php
$sections = [
  ['Acceptance of Terms', 'By accessing and using FinanceSpots ("the Site"), you accept and agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use the Site.'],
  ['Description of Service', 'FinanceSpots provides free online financial calculators and tools for educational and informational purposes only. We also offer a PRO subscription that unlocks advanced features.'],
  ['Not Financial Advice', '<strong style="color:#EF4444;">IMPORTANT DISCLAIMER:</strong> All calculators, tools, and content on FinanceSpots are for <strong style="color:#fff;">educational and informational purposes only</strong>. Nothing on this site constitutes financial, investment, tax, or legal advice. Always consult a qualified financial professional before making financial decisions. We are not responsible for any financial decisions made based on information from this site.'],
  ['PRO Subscription', '&#x2022; PRO subscriptions are billed monthly, yearly, or as a one-time lifetime payment<br>&#x2022; Monthly and yearly plans can be cancelled at any time -- access continues until the end of the billing period<br>&#x2022; Lifetime plans are a one-time purchase with no refunds after 14 days<br>&#x2022; Payments are processed securely by Stripe<br>&#x2022; We reserve the right to modify PRO pricing with 30 days notice'],
  ['Refund Policy', 'Monthly subscriptions: Cancel anytime, no refunds for partial months. Yearly subscriptions: Full refund within 14 days of purchase. Lifetime: Full refund within 14 days of purchase. To request a refund, contact us via the Contact page.'],
  ['User Accounts', '&#x2022; You must provide accurate information when creating an account<br>&#x2022; You are responsible for maintaining the security of your account<br>&#x2022; You must not share your account credentials<br>&#x2022; We reserve the right to terminate accounts that violate these terms'],
  ['Acceptable Use', 'You agree not to:<br>&#x2022; Use the site for any unlawful purpose<br>&#x2022; Attempt to gain unauthorized access to any part of the site<br>&#x2022; Scrape, copy, or redistribute our content without permission<br>&#x2022; Use automated tools to access the site at scale<br>&#x2022; Reverse engineer or copy our calculators'],
  ['Intellectual Property', 'All content on FinanceSpots, including calculators, tools, text, graphics, and code, is the property of FinanceSpots and is protected by copyright law. You may not reproduce, distribute, or create derivative works without written permission.'],
  ['Advertising', 'FinanceSpots displays advertisements through Google AdSense. We are not responsible for the content of third-party advertisements. Clicking on ads may take you to third-party websites governed by their own terms.'],
  ['Limitation of Liability', 'FinanceSpots and its owner shall not be liable for any direct, indirect, incidental, or consequential damages arising from your use of the site or any financial decisions made based on information obtained from the site. The site is provided "as is" without warranties of any kind.'],
  ['Accuracy of Calculations', 'While we strive for accuracy, our calculators are for estimation purposes only. Results may vary from actual financial outcomes due to changing rates, taxes, fees, and other factors. Always verify calculations with a qualified professional.'],
  ['Changes to Terms', 'We reserve the right to modify these Terms of Service at any time. Changes will be posted on this page with an updated date. Continued use of the site constitutes acceptance of the revised terms.'],
  ['Governing Law', 'These Terms shall be governed by applicable law. Any disputes shall be resolved through binding arbitration or in courts of competent jurisdiction.'],
  ['Contact', 'For questions about these Terms, please contact us via our <a href="' . home_url('/contact/') . '" style="color:#10B981;">Contact page</a>.'],
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
