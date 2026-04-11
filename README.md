# Industrial Welding

Industrial Welding is a WordPress + WooCommerce theme for overseas welding and cutting equipment sales sites.

The theme is built around a consistent buyer journey:

1. Enter through Finder, a category landing, or the full catalog.
2. Build and persist a shortlist across archive and product pages.
3. Compare decision-critical machine specs side by side.
4. Move into quote, support, or purchase with a cleaner decision context.

## Runtime Characteristics

- WooCommerce-powered machine catalog and product detail pages
- Finder-guided product selection
- Persistent compare shortlist using local storage + URL sync
- Local Tailwind build output bundled with the theme
- Local self-hosted fonts, with no runtime dependency on Google Fonts or Tailwind CDN

## Local Tooling

The theme uses Node.js for frontend asset packaging and Composer scripts for PHP linting and POT generation.

### Requirements

- [Node.js](https://nodejs.org/)
- [Composer](https://getcomposer.org/)
- [WP-CLI](https://wp-cli.org/) for `make-pot`

### Install

```sh
npm install
composer install
```

## Available Commands

- `npm run build` rebuilds local Tailwind CSS and `style-rtl.css`.
- `npm run build:css` regenerates `assets/css/tailwind.css`.
- `npm run build:rtl` regenerates `style-rtl.css`.
- `npm run lint:css` runs the WordPress style linter against theme CSS files.
- `npm run lint:js` runs the WordPress JavaScript linter against theme scripts.
- `npm run package` creates `../industrial-welding.zip` for distribution.
- `composer lint:wpcs` runs PHP_CodeSniffer with the theme ruleset.
- `composer lint:php` runs parallel PHP syntax checks.
- `composer make-pot` regenerates `languages/industrial-welding.pot`.

## Theme Identity

- Slug: `industrial-welding`
- Text domain: `industrial-welding`
- Prefix: `industrial_welding`

Keep those identifiers aligned in runtime code, packaging, lint configuration, and translation assets.
