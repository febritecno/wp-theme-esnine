# Esnine

A dark real estate theme for WordPress, built from the Estatein design. It has a home page, a searchable Properties page and a Property Details page with gallery, pricing breakdown and inquiry form.

Properties, testimonials and FAQs are post types. Every text, image and link on the site is edited in the WordPress admin.

## Requirements

- WordPress 6.0 or newer
- PHP 7.4 or newer
- Advanced Custom Fields (free version)
- ACF Photo Gallery Field, for the property photo gallery

## Quick start

1. Install and activate Advanced Custom Fields and ACF Photo Gallery Field.
2. Copy the `esnine` folder to `wp-content/themes/` and activate the theme.
3. Set Settings > Permalinks to "Post name".
4. Create a page called "Home" and choose it as the static home page under Settings > Reading.
5. Open the Home page for editing. The fields arrive filled with starter text from `acf-json`. Add a hero image, adjust what you like and press Update.
6. Create the header and footer menus under Appearance > Menus.
7. Add properties, testimonials and FAQs.

A section appears on the site once it has content. The step-by-step version is in [docs/installation.md](docs/installation.md).

## Folder structure

```
esnine/
├── acf-json/            ACF field groups (see below)
├── assets/
│   ├── css/main.css     All styles, design tokens at the top
│   ├── js/main.js       Carousels, gallery, menu, scroll reveal
│   └── images/          Icons, logo and background patterns
├── docs/                Installation and editing guides
├── inc/
│   ├── acf.php          ACF helpers and the acf-json load/save paths
│   ├── settings.php     esnine_mod(): site text from the front page fields
│   ├── post-types.php   Post types, taxonomy, admin columns
│   ├── data.php         Getters used by the templates
│   ├── property-search.php  Search and filters on the Properties page
│   ├── inquiry.php      Inquiry form handler
│   └── template-tags.php    Shared markup helpers
├── template-parts/      One file per section
├── archive-property.php Properties page
├── single-property.php  Property Details page
├── front-page.php       Home page
├── functions.php
├── style.css            Theme header
└── theme.json           Editor palette and font
```

## The acf-json folder

`acf-json` holds the definitions of every custom field the theme uses: which fields exist, their labels, types, tabs, help text and default values. It is the theme's copy of the ACF setup, kept in files so it travels with the theme.

| File | Field group | Shown when editing | Fields |
| --- | --- | --- | --- |
| `group_esnine_property.json` | Property Details | A property | 15: card tag, location, price, bedrooms, bathrooms, area, build year, featured switch, photo gallery, key features, four pricing tables, pricing note |
| `group_esnine_testimonial.json` | Testimonial Details | A testimonial | 3: client name, location, rating |
| `group_esnine_home.json` | Home Page | The static home page | 35: hero, three stats, four service cards, section headings |
| `group_esnine_site.json` | Site-wide Content | The static home page | 24: top banner, call to action, footer, texts of the Properties and Property Details pages |

### It loads on its own

ACF reads this folder on every request. Activate the theme and the fields are there. You do not need to import anything or press Sync for the site to work. The theme sets the folder in `inc/acf.php` with the `acf/settings/load_json` and `acf/settings/save_json` filters.

### What Sync does

On a new site the four groups exist only as files. Under ACF > Field Groups they are listed on the "Sync available" tab.

Sync copies a group from the JSON file into the database. Do this when you want to change a field in the ACF screen:

1. Go to ACF > Field Groups and open the "Sync available" tab.
2. Tick the groups and choose "Sync changes" from the Bulk actions menu.
3. The groups move to the main list and can be edited.

When you save a group in that screen, ACF writes the change back to its file in `acf-json`. Commit the file and the next site gets the same fields.

If a JSON file is newer than the database copy, for example after pulling an update of the theme, the group shows up under "Sync available" again. Sync it to bring the database copy up to date. Until then ACF keeps using the JSON version on the front end, so the site does not break.

### Initial data

The JSON files carry starter text for a new site. The 50 text fields of the Home Page and Site-wide Content groups have a `default_value`: hero title and text, stats, service card labels and icons, section headings, banner, call to action, copyright line and the texts of the property pages.

On a fresh install, open the Home page for editing and those fields are already filled in. Press Update to save them, and the site shows the text. Until the page is saved once, the values are only suggestions in the form and the front end stays empty.

Three kinds of data are not in the JSON and cannot be:

- Images. The hero image is a media library upload. Choose one in the Hero tab.
- Links to your own site, such as the banner link and service card links. They depend on your domain.
- Content: properties, testimonials, FAQs and their photos. These are posts in the database. ACF JSON describes fields, it does not store posts.

To copy the demo content to another site, use Tools > Export on the source and Tools > Import on the target, or a database migration plugin.

### Rules when editing the JSON by hand

- Every field `key` must be unique across all four files. The theme uses `field_esnine_<name>`, and `field_esnine_t_<name>` for testimonials.
- Do not rename a field `name` on a site that already has content. Saved values are stored under that name.
- Raise the `modified` timestamp when you change a file, so ACF offers the change under "Sync available".

## Documentation

| File | What it covers |
| --- | --- |
| [docs/installation.md](docs/installation.md) | Full setup, step by step, plus troubleshooting |
| [docs/editing-content.md](docs/editing-content.md) | Adding properties, changing home page text, menus and the footer |
| [DEVELOPMENT.md](DEVELOPMENT.md) | How the theme was built and what is not finished |

## License

GPL v2 or later. See [readme.txt](readme.txt) for third-party credits.
