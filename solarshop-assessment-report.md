# Solar Shop Africa — Scraped Site Assessment Report

**Source:** `C:\solarshop-scrape\mirror\solarshop.co.ke`
**Scraped site:** https://solarshop.co.ke (Solar Shop Africa, Nairobi, Kenya)
**Scan date:** 2026-09-10

---

## 1. Site Structure & Architecture

### 1.1 Platform identification
- **CMS:** WordPress (+ WooCommerce) — verified via `wp-includes/`, `wp-json/`, `wp-content/`, and `feed/` (RSS).
- **Front page:** page ID `page_on_front: 23915`, slug `homepage-2`, title "Homepage".
- **Site identity:** Site title **Solar Shop Africa**; tagline "Solar Panels, Water Heaters, Pumps, Inverters, Batteries, Lights & More | Solar Shop Africa".
- **REST API root present:** `wp-json/index.html` (full WordPress REST root, lists available namespaces).
- **robots.txt:** mirrors WordFence/security exclusions + standard WP disallows — critically it **disallows** `/cart/`, `/checkout/`, `/my-account/`, `/?add-to-cart=`, and product search. Sitemap referenced at `https://solarshop.co.ke/sitemap_index.xml`.
- **Hosting/edge:** Cloudflare (CDN email-protection `[email protected]` marker, `/cdn-cgi/` challenge-platform scripts, `__cf_` cookies). RSS feed and `feed/` mirror exist.

### 1.2 Mirrored page inventory
Top-level directories present in the mirror:

| Path | Meaning |
|---|---|
| `index.html` (1.76 MB) | Homepage (full rendered page) |
| `about-us/` | About US page |
| `contact/` | Contact page |
| `services/` | Services page |
| `shop/` | Shop (products) archive |
| `cart/` | Cart page (empty cart page) |
| `brand/` | Product archive by brand (65+ brand folders) |
| `capacity/` | Product archive by battery capacity |
| `wattage/` | Product archive by panel wattage |
| `application/` | Product archive by application |
| `solar-panels/`, `solar-batteries/`, `solar-inverters/` | Main product-category archives with sub-archive folders |
| `wp-json/`, `feed/`, `comments/` | REST / RSS / comments mirror |
| `wp-content/`, `wp-includes/` | Themes, plugins (some assets), uploads (partial) |

### 1.3 What was NOT mirrored
- **Individual product pages** (single product slugs like `/jinko-715-watts-bifacial-solar-panel/`). The shop grid links to them, but only *category / brand / capacity / wattage / application / shop* archives were captured.
- **Checkout page** (`/checkout/`), **My Account**, **Wishlist**, **Logistics**, **Order Tracking**, **FAQs**, **Privacy Policy**, **Terms & Conditions**, **Delivery & Return**, **Warranty & Support** content — linked from the footer but not captured (several are robots-disallowed).
- **Most media** in `wp-content/uploads/` (only a subset was downloaded; many referenced images/WebP source files on the origin server are absent).
- **Interactive/JS state:** search results, login drawer, filter overlays, the Kadence widget drawer, and the WooCommerce bottom menu markup are JS-rendered and not present in static HTML.

---

## 2. Design & UI

### 2.1 Theme
- **Theme: Kadence v1.4.5** (`wp-content/themes/kadence/`), the only installed theme.
- Theme stylesheets mirrored as escaped filenames: `global.min.css%3Fver=1.4.5.css`, `header.min.css%3Fver=1.4.5.css`, `footer.min.css%3Fver=1.4.5.css`, `content.min.css%3Fver=1.4.5.css`, `woocommerce.min.css%3Fver=1.4.5.css` (144,116 B — extensive WooCommerce customization), `rankmath.min.css%3Fver=1.4.5.css`.
- Layout is largely **full-width rows** wrapped in a **1290px content container** (`--global-content-width: 1290px`).
- Page-grid style: page content on a light background (`palette7/8`) with standard Kadence single-column ("single content") layout.

### 2.2 Color palette ("Kadence Base CSS", `kadence-global-inline-css`)
Full **15-color custom palette** defined as CSS variables:

| Token | Hex | Role |
|---|---|---|
| `--global-palette1` | **`#e47b02`** | Primary — orange (buttons, highlights, links) |
| `--global-palette2` | `#ed8f0c` | Secondary orange (hover) |
| `--global-palette3` | `#1f2933` | Headings / dark text |
| `--global-palette4` | `#3e4c59` | Body text |
| `--global-palette5` | `#52606d` | Muted text |
| `--global-palette6` | `#7b8794` | Meta / subtle text |
| `--global-palette7` | `#f3f4f7` | Pale section background |
| `--global-palette8` | `#f9f9fb` | Near-white background |
| `--global-palette9` | `#ffffff` | White (hero text on images) |
| `--global-palette11` | `#13612e` | Green (BADGE / success accents) |
| `--global-palette12` | `#1159af` | Blue |
| `--global-palette13` | `#b82105` | Red |
| `--global-palette14` | `#f7630c` | Bright orange (add-to-cart / hover) |
| `--global-palette15` | `#f5a524` | Amber |

Kadence Block "mark / highlight" color is `#f76a0c` (used in hero highlights).

### 2.3 Typography
- **Body:** `'Source Sans Pro'` (system fallback sans-serif).
- **Headings:** `Montserrat` (theme headings).
- **Navigation / top bar / search / status bars / many block components:** `Rubik` (used pervasively — mega-menu, header contact items, footer columns, filter widgets, product titles/price at 12–15px).
- Hero H1 rendered at **40px** desktop (`kt-adv-heading...23915_aa2867`), 18–20px on mobile, white (`palette9`).

### 2.4 Header (from `#masthead`)
Desktop header has **three stacked rows**:

1. **Top bar** (dark, small text, Rubik 13px, `palette9` text, `palette6` links):
   - Left: Email Us via Cloudflare-obfuscated link `[email protected]` (actual address hidden).
   - Right: Phone `+254722863668` and `+254 722 699 112`; quick links: About Us, Services, Shop, Contact Us.
2. **Main bar:**
   - Logo: `uploads/2026/04/cropped-SOLARSHOP_LOGO_2024.png` (see §7 for visual analysis; logo resize also at `cropped-SOLARSHOP_LOGO_2024-300x115.png`).
   - Search: **Advanced Woo Search (AWS)** with placeholder "I am shopping for ..."; expanded width (`.header-search-bar .aws-container`, `min-width:600px`, rounded corners 10px, black/`#0a0a0a` search button `#f7630c` icon, magnifying-glass).
   - Right: Shopping Cart with count (0), and **Account / Login** (opens Kadence login drawer with register link).
3. **Primary navigation bar** (Kadence mega-menus via `menu-addons.css`):

   - **Solar Panels** — "By Technology" (Monocrystalline, Polycrystalline, BiFacial, Half-Cell & TopCon, N-Type, P-Type, Flexible, Portable); "By Rating" (5W–99W, 100–199, 200–299, 300–399, 400–499, 500–599, 600–699, 700–800); "By Brands" (Jinko, JA, Trina, Renepv, TW, Longi, Amerisolar, Africell, Felicity, DayLiff, SolarMax, Suntech, Canadian, Chloride).
   - **Solar Inverters** — "By Technology" (Hybrid Off-Grid, Hybrid On-Grid, Grid-Tied, Power Backup, Water Pumping, 3-Phase Commercial, All in One ESS, Plug & Play Small); "By Application" (Residential Homes, Small/Medium Commercial, …).
   - **Solar Batteries** — "By Technology" / "By Capacity" (Below 2.4 KWh, 2.4–5, 5.1–10, 10–15, 15–25, 25–50, 50–100, 100 KWh+) / "By Brand".
   - **Solar Water Heaters** — "By Technology" / "Premium" / "Economy" / "By Application".
   - **Solar Outdoor Lights** — "By Type" / "By Application" / "By Brand".
   - **Solar Water Pumps** — "By Type" / "By Flow/Head" / "By Brand".
   - Global strapline under the nav: *"Buy at SolarShop at the Best Price, support & Genuine warrany. clean Energy is Now"*.

Mobile: Kadence mobile header + drawer; **wp-bottom-menu** plugin (v2.3.0) enqueued for a mobile bottom bar (menu items JS-rendered, not in this mirror).

### 2.5 Homepage content rows (top to bottom)
1. **Hero slider** (Kadence Blocks Pro slider, `kb-slider-ratio-3-2`, auto-play 7s): three slides with white text over a backgroud image:
   - Slide 1 (bg `2026/07/rooftop-solar-installation-2.jpg`): **"Power Your Home With Africa's Best Solar Panels"** / Tier-1 brands: Jinko, JA Solar, Trina, Longi & more. Genuine warranty. Best price guaranteed. → outline button **"Shop Solar Panels"**.
   - Slide 2 (bg `2026/05/solar-batteries.jpg`): **"Never Run Out of Power Again"** — Hybrid inverters + LiFePO4 batteries. Seamless solar-battery-grid switching. In stock now. → **"Shop Inverters & Batteries"**.
   - Slide 3 (bg `2026/07/solarshop-africa-team-on-commercial-project-site-o-2.png`): **"From Design to O&M, We Handle Everything"** — 10+ years. 1,000s of projects. Solar, water heating, pumping, lighting, all under one roof. → **"Request Free Consultation"**.
2. **Category cards strip** (4 image cards, white text, "View More" outline buttons):
   - **SOLAR PUMPING INVERTERS** — *Water Your Way, Powered by the Sun* (bg `blue-solar-pumping-inverters.jpg`).
   - **SOLAR PANELS** — *Cut your Electricity Bills Starting Today* (bg `gree-bg-panels.jpg`).
   - **SOLAR WATER HEATERS** — *Hot water every day, powered entirely by the sun* (bg `wh.jpg`).
   - **LITHIUM BATTERIES-LiFePO4** — *Store your solar energy and use it anytime you need* (bg `solar-batteries.jpg`).
3. **Value proposition bar** (4 icon boxes, 60px icons, left-aligned media):
   - **FREE DELIVERY** — For orders within Nairobi (icon `shipping_01.png`).
   - **SAFE PAYMENT** — 100% secure payment (icon `money-bac-2.png`).
   - **GENUINE PRODUCTS** — Trusted to offer legit products (icon `thumbs-up-1.png`).
   - **FRIENDLY SERVICES** — 30 day satisfaction guarantee (icon `non-toxic.png`).
4. **Top Deals of the Day** (product section, "See All" fill button, tab "Top Deals of the Day").
5. **Bestseller Products** — *Great Deals Recommended for you* (orange `palette1` heading, "View More" outline button).
6. **Featured Products** — *Save Smart with Second Chances* (outline button "View More").
7. **Portable Power Stations** (Ecoflow/Hinen/Bluetti product grid; banner bg `2026/07/growatt.jpg` alt; "See All").
8. **Solar Inverters** (product section, bg `solar-inverters.jpg`).
9. **SOLAR FLASH SALES** banner (bg `flash-sales.jpg`): *"Blazing flash deals gone before sun sets"* → "View More".
10. **BLACK FRIDAY DEALS** banner (bg `black-friday.jpg`): *"Harvest massive savings this Black Friday"*.
11. **Recommended for You** (product section).
12. **Our Partners** — brand-logo gallery (advanced gallery, 8-column grid with `0.1px solid lightgrey` cell borders). Logos referenced: Felicity, Sungrow, Goodwe, Huawei, Trina, Jinko, Longi, JA, Canadian Solar, Amerisolar, Renepv, Ritar, Ecoflow, Haier, Chromagen, Fronius, Growatt, Deye, Dyness, Must, SRNE, TBB, Vestwood, Victron, WeCo, Solax, Hinen, Hober, Veichi, Suntree, Sosen, Taico, Uhome, Livoltek, Lithium Valley, EVE/ETek, Solis, Luminous, Alltop, Bluetti, Modi, Freedom, Kastom, Megatank, Termico, LIFELYNK, Solarlight, Trusun, Difful, Seven SS Stars, Yro, + "Solarshop Africa Verified Solar Products" badge.
13. **FOUR VALUE PILLAR CARDS** (shared with About page): ONLINE + PHYSICAL / 10+ YEARS EXPERIENCE / GENUINE WARRANTY / FULL PROJECT SERVICE.
14. **Long SEO body** (brand/process/FAQ-style copy; see §2.6) then footer.

### 2.6 Category pages (SEO-enriched)
Every product category archive ends with **SEO blog-style content** targeting Kenya — e.g. **Solar Panels** (112 products): *"Buy Solar Panels in Kenya in All Types & Brands"*, *"How to Choose the Right Solar Panel for Your Home or Business"*, *"Solar Panel Installation, Warranty & After-Sales Support in Kenya"*. Same pattern on **Solar Batteries** (211) and **Solar Inverters** (512). This is clearly Rank Math + template-driven category copy.

### 2.7 Footer design
Dark footer built with **Kadence Blocks elements**, three rows:

- **Top footer row** (`element-19362`, 5 equal columns):
  1. **FOLLOW US** — social links (Facebook `facebook.com/solarshopafrica`, Instagram, YouTube, LinkedIn `linkedin.com/company/kastom-technologies-limited/` → company = **Kastom Technologies Limited**, TikTok `@solarshopafrica1`); caption "SolarShop Africa is now available on all Socials."
  2. **Informations** — New Arrivals, Special Products, Privacy Policy, Delivery & Return, Terms & Conditions, Warranty & Support.
  3. **My Account** — Product Support, Checkout, Shopping Cart, Wishlist, Logistics, Order Tracking.
  4. **Customer Care** — Contact Us, Return Policy, Terms & Conditions, Privacy Policy, Report Abuse, FAQs.
  5. **Sign Up To Newsletter** — "Join 1000+ subscribers and get a new discount coupon on every Saturday." Inline email field ("Your Email ...") + **SIGNUP** button (dark `palette3`, hover orange `palette1`, radius 0) + notice about Privacy Policy / Terms of Service acceptance.
- **Middle footer row** (`element-19768`, background **`#c66c05`** orange): 9 "help" columns with title + one-line description:
  - Customer Feedback — *Your voice helps us serve you better*
  - VAT Exemptions — *Understand your taxes, save more money*
  - SolarShop Express — *Get your solar order delivered super fast*
  - How to Place Order — *Ordering solar energy has never been easier*
  - Warranty & Support — *We back every product we sell fully*
  - Our Wholesale — *Big orders, better prices for you*
  - Payment & Delivery — *Pay your way, delivered right to you*
  - Returns & Refunds — *Not happy? We make it right fast*
  - SolarShop Corporate — *Clean energy solutions built for your business*
  (Also contains payment/logos imagery `payment.png`, `shipping_01.png`.)
- **Bottom footer row:** dark, `palette7` text: **"Copyright © SolarShop Africa. All Rights Reserved. Powered by [SoftwaresKe Ltd](https://www.softwareske.com/)"** (agency credit).

### 2.8 Custom CSS (`wp-custom-css`, `/shop/` header) — notable rules
- `.header-search-bar` min-width 600px; AWS search rounded 10px; button `#0a0a0a` bg.
- Product loop add-to-cart / price: font-size `.7em`; product titles small; `.woocommerce ul.products li.product .button` bg **`#f7630c`** (orange).
- Brand gallery: 8-column grid, `0.1px solid lightgrey` borders.
- Kadence form submit buttons: `border-radius: 0`.
- Footer text: Rubik 15px; footer middle bg `#c66c05`.
- Widget drawer: `max-width: 400px`, white bg, black text, hover `#f7630c`.
- Mobile: product grid becomes sticky 2 columns (`repeat(2,1fr)` <720px), container padding tightened.
- Spec tables inside product description tabs get `1px solid #ddd` borders + shaded first column (StickyTable-era convention).
- Yay-Currency switcher made transparent; `.select2-container` margins; masonry/fluid width tweaks.

---

## 3. Product Data Structure

### 3.1 Catalog scale
- **Shop page: 1,481 results** — "Showing 1 – 24 of 1481 results", sorted by latest.
- Category counts (top three): **Solar Panels 112**, **Solar Batteries 211**, **Solar Inverters 512** (other categories — water heaters, lights, pumps, portable power, accessories — account for the rest).

### 3.2 Archive / grid behaviour
- Grid: `<ul class="products content-wrap product-archive grid-cols grid-ss-col-2 grid-sm-col-3 grid-lg-col-4 products-grid-view woo-archive-action-on-hover woo-archive-btn-text woo-archive-loop woo-archive-image-hover-none">` — 2/3/4 columns responsive; add-to-cart appears on hover; infinite-scroll configured (`kadence-shop-kit`).
- Product card markup (`solar-panels` page, e.g. post-18444 "Jinko 715 Watts Bifacial Solar Panel"):
  `<li class="entry content-bg loop-entry product product_cat-… instock sale purchasable product-type-simple">` containing: image link (`woocommerce_thumbnail` 230×230 WebP `srcset`), `<span class="onsale">Sale!</span>`, `h2.woocommerce-loop-product__title`, and the **dual price block**:
  `<span class="price"><del>KSh14,000.00</del> <ins>KSh13,500.00</ins></span>` plus screen-reader "Original price was: … / Current price is: …".
- Sort dropdown yields: popularity, average rating, latest (default), price low→high, price high→low.

### 3.3 Price & currency
- Symbol: **KSh**, decimal settings `0.00`, thousands separator comma; applies discount via WooCommerce sale price (most items are "Sale!").
- Storage/rendered themeating: GTM ecommerce layer uses `"currency":"KES"`.
- **Filter drawer price ranges** (max slider values seen): shop/blog `KSh0–94,500` (panels), batteries `KSh700–14,500,000`, inverters `KSh0–3,890,000`.

### 3.4 Sidebar filters (shop + category pages)
- Price (range slider), Rating, Color, **Category**, **Status** (In stock / Out of stock, "Apply" button), **Availability**.
- Custom widget **"Filter by Brand"** (`kadence-filter-by-brand`, white card, `select2` dropdown) with **69 brands** — includes: Africell Solar, Agrosol, AllTop, Amerisolar, Bosch Solar, BYD, Chromagen, DayLiff, Deye, Dyness, Eastman, Ecoflow, Epever, Eraslan, Felicity, Fronius, Generic, GOODHEAT, GoodWe, Growatt, GRUNDFOS, Hinen, Hober, Huawei, INVT, Ja Solar, Jinko, Kastom, Lithium Valley, Livoltek, Longi, Luminous, Megasun, Megatank, Modi, Must, Neelux, Phaeton, Prostar, Renepv, Ritar, Seven, SS Stars, Shiyuan, Solareon, SolarMax, Solartech, Solis, SOSEN, SRNE, Sunrain, SUNSYNK, Suntree, Supreme, SVC, Taico, TBB Power, TKDZ, TNT, Trina, TW, Uhome, Ultrasun, Veichi, Veikong, Vestwoods, Victron Energy, WeCo.

### 3.5 Attribute taxonomy archives (product attributes, no single-product pages captured)
- `brand/` — 65+ brand archive folders (e.g. jinko, ja, trina, longi, deye, growatt, victron, huawei, lithium-valley, vestwoods, …).
- `capacity/` — e.g. below-2-4-kwh, 2-4-5-kwh, 5-1-10-kwh, 10-15-kwh, 25-50-kwh, 50-100-kwh, 100-kwh.
- `wattage/` — e.g. 5-99-watts, 100-199-watts, … 700-800-watts.
- `application/` — commercial-industrial (heavy/medium/small load), emobility-ev-stations, office-power-backup, residential-homes, solar-water-pumping, three-phase-systems.

### 3.6 Representative product examples (name → strikethrough → sale price)
**Solar Panels** (`KSh`):
| Product | Was | Now |
|---|---|---|
| Jinko 715W Bifacial Solar Panel | 14,000 | **13,500** |
| JA Solar 565W Mono | Bifacial | 12,300 | **10,100** |
| Trina Solar 450W Mono | Bifacial | 11,900 | **10,900** |
| Longi Solar 605W Mono | Bifacial | 11,900 | **10,900** |
| Jinko 585W Mono | Monofacial | 11,400 | **10,700** |
| Dayliff 200W 43.5V monocrystalline | 10,800 | **8,600** |
| EcoFlow Portable Solar Panel 400W | 87,000 | **86,300** |

**Solar Batteries** (211 items):
| Product | Was | Now |
|---|---|---|
| Lithium Valley 25.6V 100Ah 2.56kWh Wall Mount (6,000+ cycles) | 65,900 | **60,700** |
| Lithium Valley 12.8V 200Ah 2.56kWh LiFePO4 (3,000+ cycles) | 45,500 | **40,150** |
| Lithium Valley 51.2V 100Ah 5.12kWh Rack Mount (6,000+ cycles) | 98,900 | **94,650** |
| Lithium Valley 51.2V 300Ah 15.36kWh Wall Mount | 235,700 | **227,450** |
| Lithium Valley 51.2V 314Ah 16.07kWh (8,000+ cycles, LCD) | 279,900 | **274,600** |
| Deye 5.12kWh BOS-G Pro 100Ah HV Lithium | 125,000 | (no sale) |
| Deye 16kWh 51.2V Lithium (Low Voltage) | 296,500 | **289,000** |
| Taico 5kWh 48V TKPW-S5000 | 109,500 | (no sale) |
| BYD 5kWh 48V LV 5.0 Lithium box-LFP | 152,900 | **146,500** |
| Vestwoods 4.8kWh Low Voltage VE48100 | 95,000 / 69,500 | (no sale) |

**Solar Inverters** (512 items):
| Product | Was | Now |
|---|---|---|
| Lithium Valley 6kW Single Phase Hybrid Inverter | 110,800 | **100,800** |
| 8kW / 48V Eastman Hybrid Solar Inverter | 109,500 | (no sale) |
| Vestwoods 6kW Hybrid SE6KHB | 79,500 | (no sale) |
| TBB Matrix II 10kVA 48V Hybrid + WiFi | 255,000 | **245,000** |
| TBB Matrix II 15kVA | 455,000 | **445,000** |
| Veichi 90kW Hybrid Solar Water Pumping (3-ph) | 316,000 | **305,000** |
| Hober 110kW 3-Phase Hybrid Solar WP (HSPH110KH) | 755,000 | **745,000** |
| Fronius Primo 8.2-1 (8.2kW) grid-tie | 380,000 | (no sale) |
| Fronius Symo 20.0-3-M (20kW) 3P grid-tie | 490,000 | (no sale) |
| Taico / SVC / Sunsynk / Growatt / Deye — long-tail SKUs with prices up to ~**KSh 3,890,000** | | |

> **Data-quality note:** the latest-products page also contains obvious **test/junk products** — e.g. "Everest Series", "Rockies Line", "Kilimanjaro Set", "Andes Collection", "Alps Edition", "Himalaya Range", "Sierra Series" priced in cents (KSh 84.99), polluting the default "Latest" sort near the bottom of the catalog.

### 3.7 Extended category copy key facts (from archive SEO bodies)
- Panels: Tier-1 sources Jinko, JA, Trina, Longi, RenePV, TW Solar; 10–12 yr product warranty, 25–30 yr linear power guarantee; efficiency 18–23% mono, 15–17% poly.
- Batteries: LiFePO4 3,000–10,000 cycles, 80–100% DoD; HV stacks 96–384V; brands Felicity, Vestwoods, TBB Power, Deye, Hinen, Eastman, Ritar; 5–10 yr warranty; free sizing above 5kWh.
- Inverters: 1kVA–200kVA; hybrid/off-grid/grid-tied/pump; brands Growatt, Deye, Victron, Huawei, Livoltek, GoodWe, Must; local in-country servicing.

---

## 4. Pages Content

### 4.1 Homepage (23915)
Sections as listed in §2.5; hero, category cards, value props, deal grids, partners, pillars, and SEO copy. OG image: `uploads/2025/04/SolarShop-Logo-2025.jpg` (not mirrored).

### 4.2 About Us (`/about-us/`)
- Breadcrumb Home » About Us; H1 **"Solar Energy Solutions for Everyone"**.
- **Who We Are:** *"We are SolarShop Africa, a Nairobi-based solar energy company with over ten years of hands-on experience delivering residential and commercial solar projects across Kenya and East Africa. … We are not just an online shop. We have a physical presence across the Nairobi Metropolitan region, a team of certified solar engineers, and the technical capability to take your project from first enquiry through financial analysis, procurement, professional installation, and lifetime operations and maintenance."*
- FOUR VALUE PILLAR CARDS — ONLINE+PHYSICAL; 10+ YEARS EXPERIENCE; GENUINE WARRANTY; FULL PROJECT SERVICE.
- **What We Offer:** *"We stock over 1,500 products from more than 40 brands, the widest choice in Kenya…"* — lists Jinko/JA/Trina/Longi panels, Growatt/Deye/Victron/Huawei inverters, lithium & lead-acid storage, Chromagen→Kastom water heaters, lighting, pumps, portable stations, BOS accessories.
- **Why Choose SolarShop Africa:** value-chain positioning, ROI analysis, certified installation, 25+ year support.
- CTA: **Go Solar Today!** / *Take the first step towards sustainable energy today* → **Contact Us Today**.

### 4.3 Services (`/services/`)
- H1 **"Solar Energy Solutions for Everyone"**.
- Trust strip: Established in **2010** · Over **10,000 installations** · Partnerships with top brands · Award-winning service.
- Services grid: High-Efficiency Solar Panels; Advanced Energy Storage Solutions; Powerful Solar Inverters; Complete Solar Energy Kits.
- "Our Differentiators" numbered 01/02/03; "How to Acquire Our Solar Products" 4-step flow (Visit Website → Select Products → Add to Cart → Checkout Process).

### 4.4 Contact (`/contact/`)
- H1 **"Get in touch with Solar Shop Africa."**
- Kadence advanced contact form (email field + submit; front-end render requires JS, so **form body empty in the static mirror** — only the "Please enable JavaScript…" placeholder).
- Physical addresses: **Milestone Business Center, Northern Bypass Rd, Nairobi** and **Shop C48, Jamii Business Center, Sheik karume RD** (2nd branch).
- Social links: Facebook, Instagram, YouTube, LinkedIn, TikTok.

### 4.5 Support/info pages (linked but not mirrored)
`/faqs/`, `/privacy-policy/`, `/terms-conditions/`, `/delivery-return/`, `/warranty-support/`, `/customer-feedback/`, `/logistics/`, `/order-tracking/`, `/wishlist/`, `/new-arrivals/`, `/special-products/`, `/vat-exemptions/`.

---

## 5. Cart & Checkout

- **Cart page mirrored** (`/cart/`): WooCommerce shortcode output — *"Your Shopping Cart"* / **"Your cart is currently empty."** / button "Return to shop". No coupon or shipping UI present (cart had no items at scrape time).
- **Checkout NOT mirrored** (`/checkout/` styled as footer link "Checkout"). `robots.txt` disallows `/cart/`, `/checkout/`, `/my-account/`, `/?add-to-cart=`.
- Loyalty/e-comm UX signals: Facebook Pixel / GTM conversion wiring (see §6), WooCommerce AJAX add-to-cart ("Add to cart / Continue / Loading / Done"), snackbar notices, sale badges.

---

## 6. WordPress Content & Plugin Inventory (`wp-content/`)

### 6.1 Theme
- `themes/kadence/` (v1.4.5) + Kadence Pro add-on `kadence-pro` (menu-addons css `?ver=1.2.5`) + `kadence-shop-kit` (v3.0.9: sales-booster, snackbar).

### 6.2 Plugins (mirrored folders + enqueued asset versions)
| Plugin | Version (from asset URL) | Purpose |
|---|---|---|
| to `closed` woocommerce | wc-**11.1.0** (body class) | e-commerce engine |
| `advanced-woo-search` | **3.70** | AJAX search bar |
| `gtm-ecommerce-woo-pro` | (inline JS) | GA4/GTM e-commerce layer, currency **KES** |
| `kadence-blocks` | **3.7.10** | row/column/btn/heading/infobox/gallery/accordion/form blocks |
| `kadence-blocks-pro` | **2.8.18** | camera slider (Splide v3), other pro blocks |
| `kadence-pro` | 1.2.5 | mega-menus, header/footer elements |
| `kadence-shop-kit` | 3.0.9 | product grids, sales booster, snackbar, infinite scroll |
| `wp-bottom-menu` | 2.3.0 | mobile bottom nav bar (items JS-rendered) |
| `wp-whatsapp` | 3.8.2 | WhatsApp contact/chat (CSS enqueued; button markup JS-rendered) |

### 6.3 REST namespaces implied by the REST root (features present)
`rankmath/*` (SEO, also `rankmath.min.css`), `yay-currency` (multi-currency switcher — inline CSS makes it transparent), `jetpack`, `mailerlite` / `getresponse` / `fluentcrm` (kadence contact-form integrations), `wpgmza/v1` (WP Google Maps), `wc-facebook`, `kb/{offcanvas-block}`. GTM `dataLayer` wiring uses product-id lookup + `add_to_cart`/`remove_from_cart` events, currency "KES".

### 6.4 Uploads (notable media referenced/on disk)
- **Hero/backgrounds:** `2026/07/rooftop-solar-installation-2.jpg`, `2026/07/solarshop-africa-team-on-commercial-project-site-o-2.png`, `2026/05/{panels-bg,solar-inverters,solar-batteries,blue-solar-pumping-inverters,gree-bg-panels,wh,flash-sales,black-friday}.jpg`, `2026/07/growatt.jpg`.
- **Brand/icon assets:** `2026/05/{shipping_01,money-bac-2,thumbs-up-1,non-toxic,payment}.png`, `2026/05/Solarshop-Africa-Verified-Solar-Products-e1714434266987.png`.
- **Logo:** `2026/04/cropped-SOLARSHOP_LOGO_2024.png` (= **WebP data mislabeled `.png`**; readable PNG resize `-300x115.png` — see §7.2).
- **Product images included in catalog pages:** WebP/JPG product shots for Jinko, JA, Trina, Longi, Deye, Growatt, SRNE, BYD, Hober, EcoFlow, Taico, Hinen, Luminous, etc.
- Most individual product photos and the Kadence partner-logo folders (`2026/05|06/*.png`) are **not on disk** (only referenced in HTML).

### 6.5 Other platform notes
- **Rank Math SEO** active (category SEO copy + `rankmath.min.css`).
- **Cloudflare** in front; email address protected via CF; container challenge platform present.
- **Site credits:** built by **SoftwaresKe Ltd**.
- RSS/feed and comments endpoints mirrored; search disabled by robots.

---

## 7. WhatsApp Image — `C:\solarshop-scrape\WhatsApp Image 2026-09-10 at 8.10.04 PM.jpeg`

> Note: the model's image-reading was unavailable for the JPEG, so this description is derived from **programmatic pixel analysis** (dimensions, color histograms, and spatial sampling), not from visual inspection. Treat layout labels as best-effort.

- **File:** 135,950 B, JPEG/JFIF (FF D8 FF E0 …). Dimensions **1024 × 1536 px (portrait)**, so it is a vertical, poster-style image.
- **Background:** ~98% of pixels are near-white `#FEFEFE / #FDFDFD / #FFFFFF` — a clean white canvas.
- **Content layout (spatial analysis over a 6×6 grid):**
  - Row 1 (top band) ~29% saturated pixels; Row 2 ~47–59%; Row 3 ~15–26%; rows 4–6 <3% — i.e. the visual content is concentrated in the **center/upper-center**, consistent with a logo lockup or title text on white.
  - Column-wise the color falls in the **middle columns**, reinforcing a centered composition.
- **Brand colors detected:**
  - **Royal / navy blue** dominant (~11% of samples): cluster around `#013878`, `#013777`, `#01478D`, `#014B92`, `#015198` — a deep royal-blue corporate tone (blue family: 60–80 RGB range, saturation ~60%+).
  - **Orange accent `#FEA901`** (vivid amber-orange) — small but reliably present.
  - Minor presence of green.
- **Interpretation:** *A vertical, white-background brand graphic — most likely the Solar Shop Africa logo or a logo/photo poster — carrying royal-blue branding with an amber-orange accent, centered on a plain white field.* This matches the site's own identity (light site background + orange `#e47b02`/`#f7630c` accents) and its logo treatment (dark/indigo + orange, see below).

### 7.1 Brand logo color analysis (`cropped-SOLARSHOP_LOGO_2024-300x115.png`)
- **63.84% black (`#000000`)** background, plus **dark indigo `#18132A`** and near-white text.
- Orange/bronze accent cluster `#CB7135 / #C5753A` — consistent with an orange stylized "solar/flame" mark on the wordmark.
- OG image variant `SolarShop-Logo-2025.jpg` (1600×1461) not available locally.

---

## 8. Assessment Summary

- **A complete, high-quality e-commerce crawl** of a Kadence + WooCommerce store: full homepage, key pages (about/services/contact/cart/robots/REST/RSS), the whole shop + all taxonomy archive structure (brand, capacity, wattage, application, solar-panels/batteries/inverters), theme CSS and palette, plugin list, and dozens of product data points across 1,481 products.
- **Gaps:** individual product pages, checkout/account pages, most media files (partner logos, product WebP originals), and JS-rendered components (search results, login drawer, bottom menu, WhatsApp chat button, contact-form fields) are absent from the mirror.
- **Recommended next steps:** (1) crop/skip list to grab single-product pages (or query `wp-json/wp/v2/products`) to recover full product attributes/spec tables; (2) download the referenced `uploads/` partner-logo WebP files if the logo wall is needed; (3) capture `/checkout/` and `/my-account/` manually if needed for conversion-flow recreation; (4) note the stray junk/test products (Everest/Rockies/… series at KSh 84.99) polluting the "Latest" sort — worth excluding when rebuilding a clean catalog.