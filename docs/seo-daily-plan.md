# FinanceSpots: day-by-day plan, Tue 6 Oct to Tue 20 Oct 2026 (AdSense apply day)

Rule: every working day ends with a push, a green GitHub Action, and a live check.
Daily routine (about 10 minutes, user): GSC > URL inspection > Request indexing on up to 10 new/changed URLs.

## Tue 6 Oct (today): DONE in code, waiting for push
- 20 tools rebuilt (loan + money), 3 more (property tax, IRA, NFT ROI), 54 duplicates 301-redirected,
  4 placeholder tools noindexed, tool count corrected to 55+.
- User: push 5 commits (VS Code Source Control, up-arrow 5). Wait for green Action. Tell Claude.

## Wed 7 Oct: verify live + batch 3 (loans and tax)
Claude: verify live (redirects 301, sitemap, counts), then build/optimize: loan-payoff, loan-affordability,
interest-only, balloon-loan, bridge-loan, commercial-loan, va-loan, debt-to-income-ratio,
self-employment-tax, monthly-budget-planner.
User: Request indexing for the 10 blog posts + the 13 new tools. Rotate DB password, salts, FTP password.
Delete create-blogs.php and fix-uploads.php from the server. Edit public_html/llms.txt (110+ -> 55+).
Check: GSC sitemap status "Success"; redirected URLs start showing "Page with redirect".

## Thu 8 Oct: batch 4 (investing)
Claude: dividend, cagr, present-value, inflation, bond-yield, break-even, dollar-cost-averaging,
sharpe-ratio, options-profit, portfolio-analyzer.
User: send real bio (3 to 4 lines), photo, and LinkedIn/other profile link; decide homepage stats
(remove or give real numbers). Request indexing on batch 3 URLs.
Check: indexed count in GSC (was 40).

## Fri 9 Oct: trust pages (AdSense requirement)
Claude: About page with author bio, Editorial policy, Privacy Policy (ads + cookies), Terms, disclaimer
block on every tool and post, remove unverifiable homepage claims, author schema.
User: review the wording, confirm contact email, request indexing for the new pages.
Check: About/Contact/Privacy/Terms/Editorial all live and linked in the footer.

## Sat 10 Oct: batch 5 (crypto, currency, others) + 4 real calculators
Claude: live-currency-converter, crypto-converter, crypto-pl, staking-rewards, mining-profitability,
fire-calculator, 52-week-savings-challenge, risk-assessment-tool, ai-financial-dashboard; real calculators
for sales-tax, estate-tax, irs-penalty, gas-fee (then remove their noindex).
User: request indexing batch 4 + trust pages.
Check: all about 56 tools have content of 1,400+ words and a purpose-built calculator.

## Sun 11 Oct: speed + cookie consent
Claude: image compression, lazy loading, defer scripts, cache headers, Core Web Vitals check (PageSpeed),
cookie consent banner (Google certified CMP for UK/EEA).
User: turn on hosting page cache / CDN, purge cache. Request indexing batch 5.
Check: mobile load under about 3 s, TTFB under 1 s.

## Mon 12 Oct: blog posts 1 and 2 (1,500+ words)
Claude: two guides tied to top calculators (for example "How much house can I afford", "401(k) vs Roth IRA"),
with internal links to tools, FAQ and sources.
User: request indexing. Share the posts on LinkedIn/Facebook/Reddit (no spam).

## Tue 13 Oct: blog posts 3 and 4
Claude: "Emergency fund: how much", "How to pay off debt fastest" (links to calculators).
User: request indexing; run GSC URL inspection on 10 not-yet-indexed tools.

## Wed 14 Oct: blog posts 5 and 6 + internal linking pass
Claude: two more guides; add "related tools" and "related guides" blocks everywhere; hub pages for loans,
taxes, retirement, budgeting.
Check: indexed count; fix any canonical or "crawled, not indexed" patterns.

## Thu 15 Oct: full re-inspection
Claude: run URL Inspection on every URL, report by reason, fix technical causes.
User: Validate Fix in GSC where offered.

## Fri 16 Oct: blog posts 7 and 8
Claude: two more guides; refresh any pages that stay "crawled, not indexed" (more depth, examples, sources).

## Sat 17 Oct: quality pass
Claude: read every page for accuracy and sameness; fix thin or near-duplicate text; check schema validity.
User: Rich Results Test on 5 sample URLs.

## Sun 18 Oct: monitoring
Claude: GSC performance report (clicks, impressions, top pages), compare with 5 Oct.
User: request indexing for anything still not indexed.

## Mon 19 Oct: AdSense readiness audit
Claude: run the checklist below with evidence.

## Tue 20 Oct: apply for AdSense if the checklist passes
- [ ] 80 to 100 pages indexed (was 40 on 3 Oct)
- [ ] 20+ guides of 1,500+ words and about 56 optimized tools
- [ ] About, Contact, Privacy, Terms, Editorial policy are real and linked
- [ ] No misleading claims; disclaimers, author and dates on all pages
- [ ] GSC Manual actions: none; HTTPS clean; mobile fast
- [ ] Cookie consent working
- [ ] No duplicate tool pages, no empty categories
If fewer than 8 of 10 are green, wait one more week: a rejection forces a 1 to 2 week pause before re-applying.
