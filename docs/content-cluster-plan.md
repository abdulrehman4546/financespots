# Content cluster plan, 20 to 31 October 2026 (two posts a day)

Built from the seven Ahrefs exports in `~/Downloads/financesports/` (US, pulled 19 Oct 2026). Each planned post owns its own keywords, so no two URLs compete for the same query. "Now" is the Oct 2026 monthly estimate; KD is Ahrefs difficulty; CPC is Ahrefs cost per click.

## How the clusters are built

Each cluster has one **hub** (the broad guide) and several **spokes** (one question or sub-topic each). Rules:

1. One primary keyword per URL. A keyword assigned to a spoke is never reused in the hub title.
2. Every spoke links up to its hub and to the matching calculator; the hub links down to every spoke (a "Related guides" list at the end).
3. Each guide also links to two related guides in other clusters and to the calculator page, which carries a "Related guides" block back.
4. New calculators are built where the data shows calculator searches (mortgage points, no tax on overtime and tips, tax loss harvesting, BNPL cost).

| Cluster | Hub | Spokes | Done already |
|---|---|---|---|
| C1 Trump Accounts | `trump-accounts-explained` | vs 529, how to open, $250/Dell/employer, withdrawal rules | Hub and calculator live |
| C2 Social Security | `social-security-2027-cola-increase` | average check by age, COLA chart | Hub and calculator live |
| C3 Investing taxes | `tax-loss-harvesting` | year-end tax planning | none |
| C4 Home buying | `how-much-house-can-i-afford` | by salary, mortgage points, refinance mortgage, refinance car | none |
| C5 Buy now pay later | `buy-now-pay-later-guide` | best apps, no credit check, flights, tires, furniture/electronics, PayPal/Walmart/Amazon | none |
| C6 2026 tax law changes | `no-tax-on-overtime` | tips, car loan interest, senior deduction + standard deduction | none |

Dropped after the keyword mapping: a separate wash-sale post (its keywords belong to the tax loss harvesting hub) and a standalone standard deduction post (no separate demand in the data; it is folded into the senior deduction post).

## SEO, AEO and GEO standard for every post

**SEO**
- Primary keyword in the title (60 characters or fewer), H1, first 100 words and slug; supporting keywords in H2s and FAQ questions.
- Meta description 120 to 155 characters, one worked example with real numbers, at least 1,500 words, a table or checklist, and image alt text.
- Schema: BlogPosting with a real headline and image, FAQPage, BreadcrumbList. Dates shown, plus "last updated".
- Internal links as described above; sources linked to primary publishers.

**AEO (answer engines and featured snippets)**
- A **Quick answer** box of 40 to 60 words at the top that answers the primary query directly.
- Question-style H2/H3 that match "People also ask" phrasing; each answer starts with the direct answer in one or two sentences.
- A **Key takeaways** list of four bullets, a definition sentence ("X is ..."), numbered steps for how-to queries, and tables for comparisons.
- FAQ block of six to eight questions with 40 to 60 word answers, marked up as FAQPage.

**GEO (generative engines such as AI Overviews, ChatGPT, Perplexity, Claude)**
- Facts with dates and named sources in the sentence ("According to the SSA on October 14, 2026 ...") so a model can quote and cite them.
- Original, quotable numbers from our own worked examples and calculators.
- Clear entities and authorship: publisher, About, Editorial Policy, "last updated", consistent product and program names.
- `llms.txt` is generated from the live site (guides and calculators with one-line summaries) and refreshed whenever a post is published; robots.txt allows AI crawlers.
- Updates: figures that change yearly are re-verified and the post is re-dated.

## Facts policy
Every statistic, limit or date must be confirmed on a primary source (IRS, SSA, CFPB, Federal Reserve, Federal Student Aid, FTC) before publishing. Where sources conflict or are not final, the post says so. Nothing is copied from blogs. YMYL topics carry the educational disclaimer and link to the editorial policy.

## Publishing mechanics
Posts are written once in code with a `publish_at` date and released automatically at 09:00 and 15:00 site time (WordPress scheduled posts). Each deploy needs FTP unlocked. The host should run `wp-cron.php` every five minutes (Scheduled Tasks in the panel) so releases are on time on a low-traffic site.

## Calendar

| Date | Post 1 (09:00) | Post 2 (15:00) |
|---|---|---|
| 2026-10-20 | `tax-loss-harvesting` | `how-much-house-can-i-afford` |
| 2026-10-21 | `average-social-security-check-by-age` | `trump-account-vs-529` |
| 2026-10-22 | `how-to-open-a-trump-account` | `mortgage-points-explained` |
| 2026-10-23 | `should-i-refinance-my-car` | `should-i-refinance-my-mortgage` |
| 2026-10-24 | `how-much-house-can-i-afford-by-salary` | `year-end-tax-planning-checklist` |
| 2026-10-25 | `trump-account-250-dell-employer` | `social-security-2027-cola-chart` |
| 2026-10-26 | `buy-now-pay-later-guide` | `best-buy-now-pay-later-apps` |
| 2026-10-27 | `buy-now-pay-later-no-credit-check` | `buy-now-pay-later-flights` |
| 2026-10-28 | `paypal-pay-later-walmart-amazon` | `buy-now-pay-later-tires-car-repair` |
| 2026-10-29 | `buy-now-pay-later-furniture-electronics` | `trump-account-withdrawal-rules` |
| 2026-10-30 | `no-tax-on-overtime` | `no-tax-on-tips` |
| 2026-10-31 | `car-loan-interest-deduction` | `senior-deduction-2026` |

Tools to build alongside: mortgage points calculator (with the points post), tax loss harvesting calculator, BNPL cost calculator, no tax on overtime calculator and no tax on tips calculator.

## Post briefs

### Tax Loss Harvesting: How It Works, Rules and Examples
- Date: 2026-10-20 | Cluster: C3 Investing taxes (hub) | Slug: `tax-loss-harvesting`
- Link to: tax loss harvesting calculator (new tool)
- **Primary keyword:** tax loss harvesting (KD 3.0, now 14,159/month, CPC $3.00)
- Supporting keywords: what is tax loss harvesting (KD 28.0, now 2,591); what is tax-loss harvesting (KD 15.0, now 2,052); tax loss harvesting rules (KD 17.0, now 1,294); tax-loss harvesting (KD 3.0, now 1,046); tax loss harvesting explained (KD 0.0, now 788); direct indexing tax loss harvesting (KD 5.0, now 737); tax harvesting loss (KD 19.0, now 553); fidelity tax loss harvesting (KD 3.0, now 457)
- Keywords mapped to this post: 16, combined monthly demand about 25,561

### How Much House Can I Afford? The 28/36 Rule Explained
- Date: 2026-10-20 | Cluster: C4 Home buying (hub) | Slug: `how-much-house-can-i-afford`
- Link to: loan affordability, mortgage, DTI calculators
- **Primary keyword:** how much house can i afford (KD 7.0, now 81,980/month, CPC $0.45)
- Supporting keywords: i make $70,000 a year how much house can i afford (KD 0.0, now 4,420); i make $45,000 a year how much house can i afford (KD 23.0, now 1,534); how much house can i afford va loan (KD 12.0, now 893); if i make 100k a year how much house can i afford (KD 1.0, now 837); how much house can i afford zillow (KD 28.0, now 747); i make $60,000 a year how much house can i afford (KD 0.0, now 667); how much can i afford on a house (KD 0.0, now 481); dave ramsey how much house can i afford (KD 2.0, now 399)
- Keywords mapped to this post: 31, combined monthly demand about 96,974

### Average Social Security Check by Age (62, 65, 67, 70)
- Date: 2026-10-21 | Cluster: C2 Social Security (spoke) | Slug: `average-social-security-check-by-age`
- Link to: social security COLA calculator, retirement calculator
- **Primary keyword:** average social security check (KD 21.0, now 10,669/month, CPC $0.01)
- Supporting keywords: average social security benefit increase 2027 (KD n/a, now 4,741); average social security check at age 66 (KD 32.0, now 3,452); average social security check at age 65 (KD 1.0, now 3,061); average social security check at age 62 (KD 24.0, now 2,900); what is the average social security check (KD 21.0, now 2,259); average social security check at age 67 (KD 15.0, now 1,772); average social security check at age 70 (KD 31.0, now 1,141); how much is the average social security check (KD 23.0, now 746)
- Keywords mapped to this post: 15, combined monthly demand about 31,834

### Trump Account vs 529: Which Is Better for Your Child?
- Date: 2026-10-21 | Cluster: C1 Trump Accounts (spoke) | Slug: `trump-account-vs-529`
- Link to: trump account calculator
- **Primary keyword:** trump account vs 529 (KD 3.0, now 6,457/month, CPC $0.00)
- Supporting keywords: 529 vs trump account (KD n/a, now 2,398); trump account vs utma (KD n/a, now 603); trump account for kids vs 529 (KD n/a, now 463); trump account vs roth ira (KD n/a, now 447); 529 plan vs trump account (KD n/a, now 442); trump account vs roth ira for kids (KD n/a, now 385); trump account vs custodial account (KD n/a, now 381); difference between trump account and 529 (KD n/a, now 371)
- Keywords mapped to this post: 18, combined monthly demand about 14,154

### How to Open a Trump Account: Form 4547 Step by Step
- Date: 2026-10-22 | Cluster: C1 Trump Accounts (spoke) | Slug: `how-to-open-a-trump-account`
- Link to: trump account calculator
- **Primary keyword:** how to open a trump account (KD 24.0, now 4,825/month, CPC $0.00)
- Supporting keywords: how to sign up for trump account (KD n/a, now 3,373); should i open a trump account for my child (KD n/a, now 2,891); how to open trump account for kids (KD n/a, now 2,490); open trump account (KD n/a, now 1,901); how to open trump account (KD 0.0, now 1,633); how to set up a trump account for my child (KD n/a, now 1,549); trump account sign up (KD n/a, now 1,453); how to set up trump account (KD n/a, now 1,335)
- Keywords mapped to this post: 56, combined monthly demand about 44,093

### Mortgage Points Explained: Are They Worth It?
- Date: 2026-10-22 | Cluster: C4 Home buying (spoke) | Slug: `mortgage-points-explained`
- Link to: mortgage points calculator (new tool), mortgage calculator
- **Primary keyword:** what are points on a mortgage (KD 40.0, now 4,285/month, CPC $0.03)
- Supporting keywords: mortgage points (KD 24.0, now 4,086); what are mortgage points (KD 31.0, now 2,605); mortgage points calculator (KD 0.0, now 2,007); is it a good idea to buy points on a mortgage (KD 11.0, now 1,831); points on a mortgage (KD 0.0, now 1,145); discount points mortgage (KD 12.0, now 1,136); how do mortgage points work (KD 0.0, now 542); what do points mean on a mortgage (KD 39.0, now 490)
- Keywords mapped to this post: 24, combined monthly demand about 21,527

### Should I Refinance My Car? When It Saves Money
- Date: 2026-10-23 | Cluster: C4 Home buying (spoke) | Slug: `should-i-refinance-my-car`
- Link to: refinance calculator, auto loan calculator
- **Primary keyword:** should i refinance my car (KD 2.0, now 2,328/month, CPC $1.70)
- Supporting keywords: when should i refinance my car (KD 23.0, now 1,344); how long should i wait to refinance my car (KD 20.0, now 549); should i refinance my car loan (KD 28.0, now 462)
- Keywords mapped to this post: 4, combined monthly demand about 4,683

### Should I Refinance My Mortgage? A Break-Even Guide
- Date: 2026-10-23 | Cluster: C4 Home buying (spoke) | Slug: `should-i-refinance-my-mortgage`
- Link to: refinance calculator, mortgage calculator
- **Primary keyword:** should i refinance my mortgage (KD 14.0, now 1,255/month, CPC $5.00)
- Supporting keywords: when should i refinance my home (KD 7.0, now 1,053); should i refinance my student loans (KD 23.0, now 835); when should i refinance my mortgage (KD 0.0, now 773); when should i refinance (KD 16.0, now 716); should i refinance (KD 27.0, now 581); should i refinance to a 15 year mortgage (KD 0.0, now 528); should i refinance my auto loan (KD 38.0, now 449); should i refinance my home (KD 15.0, now 398)
- Keywords mapped to this post: 16, combined monthly demand about 8,495

### How Much House Can I Afford by Salary ($45K to $200K)
- Date: 2026-10-24 | Cluster: C4 Home buying (spoke) | Slug: `how-much-house-can-i-afford-by-salary`
- Link to: loan affordability calculator
- **Primary keyword:** how much house can i afford with 200k salary (KD 0.0, now 2,717/month, CPC $0.50)
- Supporting keywords: how much house can i afford with 80k salary (KD 0.0, now 1,698); how much house can i afford with 100k salary (KD 0.0, now 942); how much house can i afford with 150k salary (KD 0.0, now 601); how much house can i afford with 60k salary (KD 0.0, now 538); how much house can i afford with 75k salary (KD 3.0, now 354); how much house can i afford with 70k salary (KD 0.0, now 273); how much house can i afford with 50k salary (KD 0.0, now 196); how much house can i afford with 120k salary (KD 0.0, now 179)
- Keywords mapped to this post: 11, combined monthly demand about 7,808

### Year-End Tax Planning Checklist for 2026
- Date: 2026-10-24 | Cluster: C3 Investing taxes (spoke) | Slug: `year-end-tax-planning-checklist`
- Link to: income tax calculator, capital gains calculator
- **Primary keyword:** year end tax planning (KD 28.0, now 387/month, CPC $3.00)
- Supporting keywords: year-end tax planning (KD 3.0, now 159)
- Keywords mapped to this post: 2, combined monthly demand about 546

### Trump Account $250: Dell Gift and Employer Contributions
- Date: 2026-10-25 | Cluster: C1 Trump Accounts (spoke) | Slug: `trump-account-250-dell-employer`
- Link to: trump account calculator
- **Primary keyword:** trump account $250 (KD 0.0, now 7,261/month, CPC $0.00)
- Supporting keywords: trump account 530a employer match (KD n/a, now 5,321); are trump account contributions tax deductible (KD n/a, now 2,346); dell foundation trump account (KD n/a, now 928); dell trump account donation (KD n/a, now 924); trump account max contribution (KD n/a, now 882); dell trump account (KD 18.0, now 818); trump account contribution limit (KD n/a, now 745); $250 trump account (KD n/a, now 674)
- Keywords mapped to this post: 35, combined monthly demand about 28,602

### Social Security 2027 COLA Chart: New Check Amounts
- Date: 2026-10-25 | Cluster: C2 Social Security (spoke) | Slug: `social-security-2027-cola-chart`
- Link to: social security COLA calculator
- **Primary keyword:** social security 2027 benefit increase states (KD n/a, now 121,737/month, CPC $0.00)
- Supporting keywords: 2027 social security payment increase (KD n/a, now 23,340); social security 2027 cola increase chart (KD n/a, now 7,037); 2027 social security cola spousal benefits (KD n/a, now 5,491); social security cola 2027 disability (KD 24.0, now 1,592); 2027 social security benefit increase (KD n/a, now 1,423); social security benefit increase 2027 (KD n/a, now 1,213); social security 2027 cola increase chart pdf (KD n/a, now 854); social security 2027 benefit increase states map (KD n/a, now 438)
- Keywords mapped to this post: 13, combined monthly demand about 163,930

### Buy Now Pay Later (BNPL): How It Works, Fees and Risks
- Date: 2026-10-26 | Cluster: C5 BNPL (hub) | Slug: `buy-now-pay-later-guide`
- Link to: loan payoff, budget planner, BNPL cost calculator (new tool)
- **Primary keyword:** what does buy now pay later mean (KD 0.0, now 1,119/month, CPC $0.06)
- Keywords mapped to this post: 1, combined monthly demand about 1,119

### Best Buy Now Pay Later Apps and Sites
- Date: 2026-10-26 | Cluster: C5 BNPL (spoke) | Slug: `best-buy-now-pay-later-apps`
- Link to: budget planner
- **Primary keyword:** buy now pay later sites (KD 0.0, now 1,884/month, CPC $2.00)
- Supporting keywords: buy now pay later companies (KD 0.0, now 1,718); best buy now pay later apps (KD 26.0, now 1,639); buy now, pay later apps no credit check (KD 0.0, now 1,096); top 10 buy now, pay later apps (KD n/a, now 1,024); buy now pay later websites no credit check (KD 33.0, now 717); buy now pay later sites for bad credit (KD 1.0, now 693); bad credit buy now pay later online stores (KD 31.0, now 663); best buy now pay later (KD 10.0, now 448)
- Keywords mapped to this post: 14, combined monthly demand about 11,073

### Buy Now Pay Later With No Credit Check or Bad Credit
- Date: 2026-10-27 | Cluster: C5 BNPL (spoke) | Slug: `buy-now-pay-later-no-credit-check`
- Link to: debt-to-income calculator
- **Primary keyword:** buy now pay later no credit check (KD 39.0, now 6,643/month, CPC $2.50)
- Supporting keywords: buy now pay later guaranteed approval (KD 8.0, now 1,930); buy now pay later no credit check instant approval (KD 1.0, now 1,761); buy now pay later no down payment (KD 30.0, now 1,698); buy now pay later no down payment instant approval (KD 38.0, now 860); buy now, pay later no down payment (KD 1.0, now 840); buy now pay later bad credit (KD 33.0, now 768); buy now pay later catalogs instant approval (KD 8.0, now 723); walmart buy now, pay later guaranteed approval (KD 4.0, now 577)
- Keywords mapped to this post: 30, combined monthly demand about 21,573

### Buy Now Pay Later for Flights and Travel
- Date: 2026-10-27 | Cluster: C5 BNPL (spoke) | Slug: `buy-now-pay-later-flights`
- Link to: budget planner
- **Primary keyword:** buy now pay later flights (KD 0.0, now 5,967/month, CPC $1.10)
- Supporting keywords: buy now pay later hotels (KD 5.0, now 669); buy now pay later travel (KD 16.0, now 430); buy now pay later plane tickets (KD 5.0, now 399); buy now pay later airline tickets (KD 4.0, now 392); buy now pay later hotel (KD 5.0, now 214); buy flights now pay later (KD 5.0, now 196); hotels buy now pay later (KD 7.0, now 195); buy now pay later flight tickets (KD 0.0, now 185)
- Keywords mapped to this post: 12, combined monthly demand about 9,148

### PayPal Pay Later, Walmart and Amazon: Store-by-Store Guide
- Date: 2026-10-28 | Cluster: C5 BNPL (spoke) | Slug: `paypal-pay-later-walmart-amazon`
- Link to: budget planner
- **Primary keyword:** paypal buy now pay later (KD 12.0, now 3,818/month, CPC $1.60)
- Supporting keywords: walmart buy now pay later (KD 1.0, now 2,037); zip buy now pay later (KD 37.0, now 1,930); amazon buy now pay later (KD 12.0, now 1,177); buy now pay later paypal (KD 0.0, now 927); how does klarna buy now pay later work (KD 1.0, now 923); buy now pay later walmart (KD 2.0, now 870); buy now pay later amazon (KD 1.0, now 687); sezzle buy now pay later (KD 26.0, now 203)
- Keywords mapped to this post: 11, combined monthly demand about 12,730

### Buy Now Pay Later for Tires and Car Repairs
- Date: 2026-10-28 | Cluster: C5 BNPL (spoke) | Slug: `buy-now-pay-later-tires-car-repair`
- Link to: auto loan calculator
- **Primary keyword:** buy now pay later tires (KD 0.0, now 2,367/month, CPC $3.00)
- Supporting keywords: tires buy now pay later (KD 0.0, now 1,148); buy now pay later tires near me (KD 0.0, now 659); buy now pay later mattress (KD 23.0, now 481); tire rack buy now pay later (KD n/a, now 223); buy now pay later car repair (KD n/a, now 220); buy now pay later auto parts (KD 0.0, now 191); mattress buy now pay later (KD 24.0, now 168); buy tires now pay later (KD 5.0, now 152)
- Keywords mapped to this post: 9, combined monthly demand about 5,609

### Buy Now Pay Later for Furniture, Phones and Electronics
- Date: 2026-10-29 | Cluster: C5 BNPL (spoke) | Slug: `buy-now-pay-later-furniture-electronics`
- Link to: budget planner
- **Primary keyword:** buy now pay later baby items (KD 0.0, now 3,126/month, CPC $0.00)
- Supporting keywords: buy now pay later phones (KD 0.0, now 1,808); buy now pay later furniture (KD 10.0, now 1,619); buy now pay later electronics (KD 3.0, now 1,419); buy now pay later iphone (KD 18.0, now 1,225); buy now pay later tv (KD 0.0, now 1,063); buy now pay later groceries (KD 33.0, now 943); buy now pay later shoes (KD 0.0, now 924); buy groceries now pay later (KD 0.0, now 700)
- Keywords mapped to this post: 30, combined monthly demand about 19,161

### Trump Account Withdrawal Rules: Age 18, Taxes and Penalties
- Date: 2026-10-29 | Cluster: C1 Trump Accounts (spoke) | Slug: `trump-account-withdrawal-rules`
- Link to: trump account calculator, ira calculator
- **Primary keyword:** trump investment account for kids (KD n/a, now 2,651/month, CPC $0.00)
- Supporting keywords: trump investment account (KD n/a, now 2,266); trump ira account (KD 0.0, now 2,184); trump account withdrawal rules (KD n/a, now 1,584); trump child investment account (KD n/a, now 1,118); trump account investment options (KD n/a, now 972); is trump account tax free (KD n/a, now 433); trump account tax benefit (KD n/a, now 422); can you withdraw money from trump account (KD n/a, now 368)
- Keywords mapped to this post: 27, combined monthly demand about 16,002

### No Tax on Overtime: Who Qualifies and How It Works (2025 to 2028)
- Date: 2026-10-30 | Cluster: C6 2026 tax law changes (hub) | Slug: `no-tax-on-overtime`
- Link to: no tax on overtime calculator (new tool), income tax calculator
- **Primary keyword:** no tax on overtime (KD 12.0, now 5,459/month, CPC $0.01)
- Supporting keywords: no tax on overtime calculator (KD 2.0, now 1,346); how does no tax on overtime work (KD 9.0, now 885); how does the no tax on overtime work (KD 8.0, now 831); is there no tax on overtime (KD 0.0, now 434); trump no tax on overtime (KD 33.0, now 417); no tax on overtime rules (KD 16.0, now 365); did trump pass no tax on overtime (KD n/a, now 346); no tax on overtime income limit (KD 19.0, now 219)
- Keywords mapped to this post: 62, combined monthly demand about 12,703

### No Tax on Tips: Eligibility, Limits and Tipped Occupations
- Date: 2026-10-30 | Cluster: C6 2026 tax law changes (spoke) | Slug: `no-tax-on-tips`
- Link to: no tax on tips calculator (new tool)
- **Primary keyword:** no tax on tips rules (KD 39.0, now 871/month, CPC $0.01)
- Supporting keywords: what does no tax on tips mean (KD 40.0, now 553); when does no tax on tips expire (KD n/a, now 471); trump no tax on tips (KD n/a, now 289); when does no tax on tips end (KD n/a, now 212); how does no tax on tips work (KD 39.0, now 192); is no tax on tips in effect (KD 0.0, now 187); no tax on tips calculator (KD 0.0, now 115); is there no tax on tips (KD n/a, now 101)
- Keywords mapped to this post: 21, combined monthly demand about 3,550

### Car Loan Interest Deduction: Rules, Limits and Who Qualifies
- Date: 2026-10-31 | Cluster: C6 2026 tax law changes (spoke) | Slug: `car-loan-interest-deduction`
- Link to: auto loan calculator
- **Primary keyword:** car loan interest deduction (KD 6.0, now 436/month, CPC $0.01)
- Supporting keywords: tax deduction for car loan interest (KD n/a, now 188); car loan interest deduction 2025 (KD 9.0, now 98); new car loan interest deduction (KD 7.0, now 27); car loan interest deduction phase out (KD n/a, now 23); interest deduction on car loan (KD n/a, now 21); no tax on car loan interest deduction (KD n/a, now 21); irs car loan interest deduction (KD 40.0, now 16); car loan interest tax deduction (KD n/a, now 16)
- Keywords mapped to this post: 12, combined monthly demand about 878

### Senior Deduction 2026: The Extra $6,000 and the Standard Deduction
- Date: 2026-10-31 | Cluster: C6 2026 tax law changes (spoke) | Slug: `senior-deduction-2026`
- Link to: income tax calculator
- **Primary keyword:** senior tax deduction 2026 (KD 31.0, now 707/month, CPC $0.02)
- Supporting keywords: senior bonus deduction 2026 (KD n/a, now 280); 2026 senior tax deduction (KD n/a, now 74); senior standard deduction 2026 (KD n/a, now 21); senior deduction 2026 (KD n/a, now 16)
- Keywords mapped to this post: 5, combined monthly demand about 1,098

## Next data pulls needed (phase 2, from 1 November)
Export from Ahrefs (US) matching terms for: `401k limits 2027`, `roth vs traditional ira`, `hysa vs cd`, `i bond rate`, `credit card interest calculator`, `paycheck calculator`, `student loan forgiveness 2026`, `pslf`, `medicare part b premium 2027`. These keep the schedule full after the first 24 posts.

## Honest limits
- Ranking depends on links as well as content, and the site has few. The edge is being early on new topics, calculators and clearly sourced answers.
- Many of these topics have a CPC near zero (Social Security, Trump Accounts, tax rules). The revenue topics are refinancing, BNPL, mortgage and tax loss harvesting.
- Two posts a day only works if each is checked against sources. If quality slips, the schedule slows rather than the standard.
