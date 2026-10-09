# Esnine Theme: Development Notes

Esnine is a WordPress theme I built from the "Estatein" real estate design in Figma. It covers three screens of that design: Home, Properties and Property Details. The About Us, Services and Contact pages are not built yet.

These notes describe how I worked, what I chose and why, and where the theme still falls short.

## How I worked

I started with the design tokens. I read the colors, font and breakpoints from the Figma "Template Guide" and wrote them once as CSS variables (`--purple-60`, `--grey-08` and so on) in `assets/css/main.css`, with the same names in `theme.json`. Styles use those variables instead of raw hex values.

Next I built each section to match the three Figma breakpoints (1920, 1440 and 390 px) and split the markup into template parts. Properties, testimonials and FAQs became custom post types, and property data became ACF fields.

My first version was wrong in one respect. I had left the design's sample content inside the PHP templates as a fallback, so the site looked finished even with an empty database. That hides problems from whoever edits the site later, so I removed it. All text, images and links now come from the database, and a section with no content does not render.

To check the result against the design I exported the Figma frames and compared them with screenshots of the running site. That comparison caught several mistakes I had missed by eye: chips wrapping onto two rows on property cards, a 194 px blank gap in the mobile pricing section, the Area stat breaking onto two lines, and the hero title breaking at the wrong word.

I tested behaviour in a real browser with Playwright: the carousels, auto-slide, the "Read More" toggle, the active menu item and each new page, at widths from 390 to 1920 px.

Last, I scored the site against the six criteria in the brief. Performance and SEO came out weakest, so I spent a final pass there. The results are in the section below.

## Theme choices

The theme is a classic PHP theme with no build step. The design is a fixed marketing layout with dynamic listings, and template parts fit that directly. It has one stylesheet and one script, written in plain CSS and vanilla JavaScript without jQuery or a bundler. I kept `theme.json` so the block editor shows the same palette and font.

| Path | Purpose |
| --- | --- |
| `inc/post-types.php` | `property`, `testimonial`, `faq` and `inquiry` post types, the `property_type` taxonomy, admin list columns |
| `inc/settings.php` | `esnine_mod()`, which reads site copy from ACF fields on the front page |
| `inc/data.php` | Getters that turn posts into plain arrays for the templates |
| `inc/property-search.php` | Search and filter query for the Properties page |
| `inc/inquiry.php` | Inquiry form handler (nonce, honeypot, saved as private posts, emailed to the admin) |
| `template-parts/` | One file per section |
| `acf-json/` | ACF field groups, kept in version control with the theme |

Editors change content through ACF. The free version of ACF has no options page, so I attached the site-wide copy to the static front page in two field groups, "Home Page" and "Site-wide Content". It works, but it is a workaround: banner and footer text live on the Home page, which an editor would not guess. With ACF Pro I would move them to an options page.

Where WordPress already had a feature, I used it. The header and footer links are navigation menus. Photos are media library attachments, which gives responsive `srcset` output. The Properties page is the post type archive, filtered through `pre_get_posts`.

For motion, the carousels page by breakpoint (3, 2 or 1 cards), and the testimonial and FAQ carousels slide on their own, pausing on hover, on focus and when the tab is hidden. Sections fade in on scroll. Visitors without JavaScript still see all content, and animations switch off under `prefers-reduced-motion`, except the small rotating badge in the hero.

Output is escaped where it is printed, forms use nonces and a honeypot field, and all strings are translatable.

## Plugins

| Plugin | Why |
| --- | --- |
| Advanced Custom Fields (free) | Property data and site copy |
| ACF Photo Gallery Field | A gallery field for free ACF. It replaced ten separate image fields, which were awkward to edit. |
| Simple Custom Post Order | Drag-and-drop ordering of properties, testimonials and FAQs |
| Yoast Duplicate Post | Start a new listing from an existing one |
| Yoast SEO | Meta descriptions, Open Graph tags, JSON-LD schema and indexing rules. I used a plugin instead of theme code so the settings survive a theme change. |
| WP Fastest Cache, WP-Optimize | Page caching and database clean-up |

## Tools

- Local (by Flywheel) and WP-CLI for the development site, plugin installs and content seeding.
- Figma Desktop with figma-cli, to export frames and SVG icons from the source file.
- Playwright with headless Chromium, for screenshots and interaction tests.
- Claude Code, an AI coding assistant. I used it for much of the implementation, the design comparison and the test scripts.
- `php -l` and `node --check` after each change.

## Performance and SEO pass

Before this pass the home page weighed 4.8 MB and a property page 8.6 MB, almost all of it PNG photos. I converted the photos to WebP and regenerated the sizes, which took the media library from 24.7 MB to 2.7 MB. I added lazy loading to card images and avatars, and kept carousel cards beyond the first page out of layout until the script runs, so their images are not requested early.

| Page | Before | After |
| --- | --- | --- |
| Home, 1440 px | 4,846 KB | 287 KB |
| Home, 390 px | 2,791 KB | 283 KB |
| Property details, 1440 px | 8,608 KB | 1,062 KB |
| Property details, 390 px | 5,168 KB | 625 KB |

I measured these on my local machine. I have not run Lighthouse or PageSpeed on a public host, so I can't claim real-world load times.

For SEO, Yoast now outputs a meta description, Open Graph tags and schema on every page. Property and FAQ pages use their excerpt as the description. Single testimonials and inquiries are set to `noindex`. The theme supplies one `<h1>` per page, `alt` text on images and clean permalinks.

On phones, 22 text links were shorter than 24 px. I gave footer, banner and form links a taller touch area. I also published the Privacy Policy & Terms page so those links lead somewhere.

## What is not done

- The About Us, Services and Contact pages are missing. Their menu items point to sections of the home page.
- I compared the laptop frames with Figma closely. The mobile and 1920 px layouts I only checked for breakage.
- Demo listings, testimonials, FAQ articles and the privacy text are generated sample content. Photos come from Unsplash and avatars from pravatar.cc. All of it needs replacing before launch, and the privacy text needs legal review.
- Pricing tables are typed as `Label | Value | Note` lines, because free ACF has no repeater field. It is easy to mistype.
- The post types are registered in the theme. A plugin would be the better home, since content disappears from the admin if the theme changes.
- `main.css` is one 61 KB file, and some later fixes override earlier rules instead of replacing them. It needs a clean-up.
- The inquiry form saves to the admin, but I have not tested email delivery. The newsletter form only fires a hook and stores nothing.
- There is no `RealEstateListing` or `FAQPage` schema, and I have not done a screen reader or keyboard-only pass.
- Price and size filter ranges are set in code (`esnine_price_ranges`, `esnine_size_ranges` filters).
