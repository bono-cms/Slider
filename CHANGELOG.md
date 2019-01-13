CHANGELOG
=========

1.3
---

 * In image form - show first tab with image options, not with description
 * Grab slides by last category ID in main grid, not all
 * Support complete internalization
 * Added shortcut in administration panel
 * Added `quality` option for categories
 * Automatically category pre-selection when adding a slide image
 * Removed `TaskManager` service
 * In category entity added `getSlidesCount()` that returns a count of attached slides
 * Since now categories support dynamic attributes
 * In `\Slider\Service\SiteService` added `getRandom()` to fetch random image entity by category class
 * Switched to two column grid layout in main module page
 * Fixed issue with quote escaping
 * Added getImageUrl() shortcut in `ImageEntity`
 * Fixed bug with non-ability to render from same category with different languages
 * Added support for table prefix
 * Changed module icon
 * Improved internal structure

1.2
---

 * Improved internals

1.1
---

 * Added "link" field in slide image's form, and update its bag accordingly
 * Added new service called "block". Now rendering can be done even much easier. 
   To do so, you'd only call `$slider->render($class)` inside templates.

1.0
---

 * First public version