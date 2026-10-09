# Installing Esnine

This guide takes a fresh WordPress site to a working Esnine site. Allow about 30 minutes, most of it entering content.

## 1. Install the plugins

Go to Plugins > Add New, then search for, install and activate each one.

| Plugin | Needed? | What it does here |
| --- | --- | --- |
| Advanced Custom Fields | Required | Provides the fields for properties and site text |
| ACF Photo Gallery Field | Required for galleries | The "Photos" field on a property |
| Simple Custom Post Order | Recommended | Drag listings into the order you want |
| Yoast Duplicate Post | Optional | Copy a listing to start a new one |
| Yoast SEO | Recommended | Meta descriptions, social sharing tags, schema |
| A caching plugin | Recommended | The demo site uses WP Fastest Cache |

Activate Advanced Custom Fields before the theme. Without it the theme shows a notice in the admin and the field screens are missing.

With WP-CLI the same step is one command:

```bash
wp plugin install advanced-custom-fields navz-photo-gallery simple-custom-post-order duplicate-post wordpress-seo --activate
```

## 2. Install the theme

Copy the `esnine` folder into `wp-content/themes/`, or zip the folder and upload it under Appearance > Themes > Add New > Upload Theme.

Activate it under Appearance > Themes. On activation the theme registers its post types and refreshes the permalink rules. You will see four new items in the admin menu: Properties, Testimonials, FAQs and Inquiries.

The ACF field groups ship in the theme's `acf-json` folder and load on their own. There is nothing to import.

## 3. Set the permalinks

Go to Settings > Permalinks, choose "Post name" and save. The theme's URLs depend on this:

- `/properties/` for the listing page
- `/properties/your-listing/` for a single property
- `/faqs/` and `/testimonials/` for the archives

## 4. Create the home page

1. Pages > Add New. Title it "Home" and publish it. Leave the content empty.
2. Settings > Reading. Under "Your homepage displays" choose "A static page" and pick "Home". Save.
3. Open the Home page for editing again. Two field groups now appear below the title: "Home Page" and "Site-wide Content".

The site reads its banner, footer and call-to-action text from this page, so this step is needed even if you only want the Properties page. Until it is done, the admin shows a reminder.

## 5. Fill in the Home page fields

Home Page group:

| Tab | Fields |
| --- | --- |
| Hero | Title (press Enter where the line should break), text, image, round badge text, two buttons |
| Stats | Three value and label pairs, for example "200+" and "Happy Customers" |
| Service cards | Up to four cards, each with an icon, label and link |
| Section headings | Title, text and button label for the Properties, Testimonials and FAQ sections |

Site-wide Content group:

| Tab | Fields |
| --- | --- |
| Top banner | Text and link. Leave the text empty to hide the banner |
| Call to action | Title, text and button above the footer |
| Footer | Copyright line, newsletter placeholder, four social links |
| Properties page | Headings and text of the listing page and its request form |
| Property detail | Inquiry and pricing headings. `{property}` is replaced by the property name |

Empty fields are skipped on the site. Nothing shows a placeholder.

## 6. Create the menus

Go to Appearance > Menus.

Header menu:

1. Create a menu and assign it to the "Primary Menu" location.
2. Add the items you want. For Properties, open "Screen Options" at the top, tick "Properties", then add the "All Properties" archive link.

The "Contact Us" button is added by the theme. Do not add it to the menu.

Footer menu:

1. Create a second menu and assign it to "Footer Menu".
2. Add a custom link for each column title, for example "Home" with the URL `#`.
3. Add the column's links and drag each one slightly to the right, under its title, so it becomes a sub item.

Each top-level item becomes a column. Its sub items become the links in that column.

## 7. Add content

See [editing-content.md](editing-content.md) for the details. In short:

1. Properties > Property Types: add the types you sell, such as Villa and Apartment.
2. Properties > Add New: title, description, excerpt, featured image, then the Property Details fields.
3. Testimonials > Add New: the title is the headline, the content is the quote, the featured image is the avatar.
4. FAQs > Add New: the title is the question, the excerpt is the short answer on the card, the content is the full article.

## 8. Final settings

- Logo: Appearance > Customize > Site Identity. Without a logo the theme shows its built-in Estatein mark.
- Privacy page: Settings > Privacy. Choose a published page. The footer "Terms & Conditions" link and the form checkboxes point to it.
- Ordering: Settings > SCPOrder. Tick Properties, Testimonials and FAQs, then drag rows in each list.
- Inquiry email: inquiries are saved under Inquiries and emailed to the address in Settings > General. Most hosts need an SMTP plugin for mail to arrive.

## Troubleshooting

**The Properties page or a property shows "Page not found".**
Open Settings > Permalinks and press Save without changing anything.

**The home page is empty.**
Check that a static home page is set (step 4) and that its fields have content (step 5).

**No field groups appear when editing the Home page.**
Advanced Custom Fields is not active, or the page is not the one chosen under Settings > Reading.

**A section is missing on the home page.**
It has no content. Featured Properties needs at least one published property with "Featured on home page" switched on.

**The footer has no link columns.**
No menu is assigned to "Footer Menu", or its links are not nested under column titles.

**Changes do not show on the site.**
Clear the page cache. With WP Fastest Cache: the "Delete Cache" item in the admin bar.

**Animations do not play.**
The theme turns motion off when the device has "Reduce motion" enabled in its accessibility settings.
