# Getting Started
Out-of-the-box the templates within this module are compatible with [Bootstrap 4](https://getbootstrap.com).

If you are not a fan of using bootstrap then you will have to override the relevant templates in your own theme.

## 1. Installation
Installation is _only_ supported via composer:
```bash
composer require vulcandigital/silverstripe-userdocs
```

## 2. Require the resources
This module does ship with CSS and JS by default.  _However do not inject these requirements for you._

> Warning: If you do not include at least the JavaScript resources then syntax highlighting will not be available.

1. Open **PageController.php**
2. In the `public function init()` method call the resource loader where suitable: `\Vulcan\UserDocs\Pages\UserDocsController::addResourceRequirements();`
3. Save

You can toggle the inclusion of the CSS and/or JavaScript by either ignoring the above steps entirely (you will sacrifice syntax highlighting) or via a YML configuration file.

If you would like to toggle these, along with the syntax highlighter theme:
```yml
---
after
---
Vulcan\UserDocs\Pages\UserDoc:
  require_js: true
  require_css: true
  highlight_theme: "monokai-sublime"
```

For a full list of available themes see [highlight.js CDN](https://cdnjs.com/libraries/highlight.js/) and choose from any of the stylesheets within the list.

If you wanted https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/darcula.min.css then you would remove the path from the URL and `.min.css` and change `highlight_theme` to `darcula`.

You can preview themes [here](https://highlightjs.org/static/demo/).

## 3. Creating the root page
After installation, you will have to manually create the `UserDocs` root page in the Pages section of the CMS.

1. Click the "**Add New**" button
2. **Step 1**: You can either select "Top level" or "Under another page"
3. **Step 2**: Select the `UserDocs` page type
4. Name your page and give it an appropriate **URL Segment** (eg. Documentation)
5. Save

## 4. Begin structuring your documentation
You can now begin adding `UserDocsCategory` and `UserDocsPage` pages as children under the root page you created earlier.

> Note: The thought of implementing `Lumberjack` to the `UserDocsCategory` didn't feel appealing. If this is something you would like to see please raise an issue.