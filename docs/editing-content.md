# Editing content

Everything on the site is edited in the WordPress admin. This page shows where each piece lives.

## Properties

Properties > Add New.

| Where | What to enter | Where it shows |
| --- | --- | --- |
| Title | Name of the listing | Cards and the detail page |
| Content | Full description | Detail page, and the expanded "Read More" text on cards |
| Excerpt | One sentence | Card summary and the search engine description |
| Featured image | Main photo | Cards, and first in the gallery |
| Property Types | Villa, Apartment and so on | Card chip and the type filter |

Property Details fields:

| Tab | Fields |
| --- | --- |
| Overview | Card tag, location, price (numbers only), bedrooms, bathrooms, area, build year, "Featured on home page" |
| Gallery | Photos. Add any number and drag to reorder |
| Features | Key features, one per line |
| Pricing | Four tables and an optional note |

Write the area as text, for example `2,500 Square Feet`. The size filter reads the number from it.

### Pricing tables

Each line is one row, written as `Label | Value | Note`. The note is optional.

```
Property Transfer Tax | $25,000 | Based on the sale price and local regulations
Legal Fees | $3,000 | Approximate cost for legal services
Mortgage Fees | Varies
```

A table with no lines is left out. If all four are empty, the pricing section is hidden.

### Home page selection and order

A property appears under Featured Properties when "Featured on home page" is on. Drag rows in the Properties list to change the order (needs Simple Custom Post Order).

## Testimonials

Testimonials > Add New.

- Title: the headline, such as "Exceptional Service!"
- Content: the quote
- Featured image: the client's photo, square works best
- Client name, location and rating (1 to 5 stars)

The home page shows three at a time and slides on its own. All of them are listed at `/testimonials/`.

## FAQs

FAQs > Add New.

- Title: the question
- Excerpt: the short answer on the card
- Content: the full article. Headings and lists are styled.

"Read More" on a card opens the article.

## Home page and site-wide text

Pages > Home. The "Home Page" group holds the hero, stats, service cards and section headings. The "Site-wide Content" group holds the banner, call to action, footer and the texts of the property pages.

To hide something, empty its field. An empty banner text removes the banner, an empty button label removes the button.

## Menus and footer

Appearance > Menus. The header uses "Primary Menu". The footer columns use "Footer Menu", where each top-level item is a column title and its sub items are the links.

Social icons appear when their URL is filled in under Pages > Home > Site-wide Content > Footer.

## Inquiries

Messages from the property inquiry form and the request form on the Properties page are saved under Inquiries and emailed to the site admin. They cannot be created by hand.

## Search filters

The Location and Build Year filters list the values used by published properties. Property Type lists the types that have at least one property.

Price and size ranges are fixed in code. A developer can change them with the `esnine_price_ranges` and `esnine_size_ranges` filters.
