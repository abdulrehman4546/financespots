# FinanceSpots: 1-week SEO plan (5 Oct to 11 Oct 2026), then AdSense on 20 Oct

Starting point (Search Console, 3 Oct): 140 URLs checked, 40 indexed, 36 unknown to Google,
35 crawled-not-indexed, 25 discovered-not-indexed. 6 clicks / 114 impressions in 90 days.
After the content upgrade, 9 of the first 10 optimized tools were indexed within days.

## Day 1 (Mon 5 Oct): push + sitemap
- [ ] Push local `main` (sitemap cache fix, `75fb067`). GitHub Action must go green.
- [ ] Check live: `post-sitemap.xml` lists the 10 real posts, not the old `*-guide` URLs.
- [ ] GSC: Request indexing for the 10 blog posts.
- [ ] Rotate DB password, salts, FTP password. Delete `create-blogs.php`, `fix-uploads.php` from the server.

## Day 2 (Tue 6 Oct): batch 2 tools (done in code, needs push)
income tax, capital gains, compound interest, retirement savings, 401(k), savings goal,
emergency fund, ROI, net worth, 50/30/20. New calculators + content + keywords.
- [ ] Push, then Request indexing for these 10 URLs.

## Done so far (6 Oct)
- 20 tools optimized (10 loan, 10 money) with real calculators and content.
- 54 duplicate tools 301-redirected, 4 noindexed, tool count corrected to 55+.
- Property tax, IRA and NFT ROI got real calculators + content (they already rank).
- Remaining distinct tools to optimize: about 34 (see list below).

## Day 3 (Wed 7 Oct): batch 3 tools
Pick distinct calculators only. Many tools reuse one generic calculator (income_tax: 9 tools,
compound: 10, retirement: 8, savings_goal: 7). Do not publish more copies; build a real calculator
for each or merge/noindex the duplicates.

## Day 4 (Thu 8 Oct): trust pages
- [ ] About page with author bio and photo (real, verifiable).
- [ ] Editorial policy page, updated Privacy Policy (ads, cookies), Terms, Contact.
- [ ] Remove unverifiable homepage stats ("2,500,000 users" etc.) or replace with real numbers.

## Day 5 (Fri 9 Oct): 4 new posts (1,500+ words), internal links into tools

## Day 6 (Sat 10 Oct): speed + consent
- [ ] TTFB (was about 3 s): page cache / CDN. Compress images. Check PageSpeed.
- [ ] Cookie consent banner (Google certified CMP) for UK/EEA traffic.

## Day 7 (Sun 11 Oct): re-check
- [ ] Re-run URL Inspection on all URLs; fix what is left.

## Days 8 to 14: build and monitor
- Daily: Request indexing on about 10 URLs (new pages first).
- Publish 3 to 4 more posts and the next tool batches. No big design changes.
- Re-check indexing on day 10 and day 14.

## Day 15 (Tue 20 Oct): AdSense readiness checklist
- [ ] 80 to 100 pages indexed (was 40)
- [ ] 20+ original guides of 1,500+ words, 40+ optimized tools
- [ ] About, Contact, Privacy, Terms, Editorial policy live and real
- [ ] No misleading claims on any page
- [ ] Disclaimer, author and date on every tool and post
- [ ] GSC Manual actions: none; HTTPS clean
- [ ] Mobile load under about 3 s
- [ ] Cookie consent working
- [ ] No duplicated tool pages
