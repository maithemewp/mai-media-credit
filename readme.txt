=== Mai Media Credit ===
Contributors:      BizBudding
Tags:              block, image, media, attribution, credit
Tested up to:      6.7
Stable tag:        0.1.0
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Adds a media credit field to the Image block to store attribution text as attachment metadata.

== Description ==

Mai Media Credit extends the WordPress Image block with a media credit field that allows you to store attribution information for images. The media credit is saved as attachment metadata and can be accessed programmatically.

**Features:**
* Adds a "Media Credit" field to the Image block settings sidebar
* Stores media credit as `_media_credit` attachment meta field
* Integrates with the WordPress Media Library
* Automatic saving with debounced AJAX requests
* Proper security with nonce verification

**How it works:**
1. When you add an Image block to your content, you'll see a "Media Credit" panel in the block settings sidebar
2. Enter the attribution/credit information for the image
3. The media credit is automatically saved to the attachment metadata
4. The credit information is stored with the image and can be retrieved programmatically

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/mai-media-credit` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Run `npm install` and `npm run build` to build the JavaScript assets

== Frequently Asked Questions ==

= How do I access the media credit programmatically? =

You can retrieve the media credit using WordPress's `get_post_meta()` function:

```php
$media_credit = get_post_meta( $attachment_id, '_media_credit', true );
```

= Can I display the media credit on the frontend? =

Yes, you can retrieve the media credit for any image and display it however you need. The credit is stored as attachment metadata and can be accessed using the attachment ID.

= Is the media credit field available in the Media Library? =

Yes, the plugin also adds the media credit field to the Media Library edit form, so you can edit credits directly in the Media Library as well.

== Changelog ==

= 0.1.0 =
* Initial release
* Added media credit field to Image block
* Added Media Library integration
* AJAX-based saving with security measures
