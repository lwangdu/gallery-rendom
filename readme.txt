=== Gallery Random ===
Contributors: Lobsang Wangdu
Tags: gallery, random gallery, images
Requires at least: 6.0
Tested up to: 6.8
Stable tag: 1.0.24
License: GPLv2 or later

Displays one randomized full-width hero image with title, description, buttons, and a hidden caption opened from an info icon.

== Usage ==
1. Activate Gallery Random.
2. Go to Gallery Random in wp-admin.
3. Add a Gallery Random Item for each image.
4. Set the image as the Featured Image.
5. Add title, description, image focal position, hidden caption, and button fields.
6. Add this shortcode to a page or post. Each page load displays one random published gallery item:

[gallery_random]

The hyphen shortcode also works:

[gallery-random]

The original misspelled shortcodes are kept as compatibility aliases for existing content:

[gallery_rendom]

[gallery-rendom]

You can also add the Gallery Random block in the block editor. The block uses the same renderer as the shortcode so front-end output stays consistent.

== Settings ==
Go to Gallery Random > Settings to change the text area background, title color, description color, and button colors with six-digit hex values such as #004a89 or 004a89.
Use Reset to Default Colors to restore the built-in color defaults.

== Caching ==
The shortcode defines DONOTCACHEPAGE during render and stores the published Gallery Random item ID list in a transient that is cleared when items are saved, trashed, untrashed, or deleted. Some full-page cache plugins decide whether to cache before shortcode rendering, so pages that use Gallery Random may still need to be excluded manually in the active cache plugin.

== Uninstall ==
Deleting the plugin removes its color settings and transient cache. Gallery Random Item posts are left in place because they are editorial content.
