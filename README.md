# Mai Media Credit

A WordPress plugin that adds a media credit field to the Image block to store attribution text as attachment metadata.

## Description

Mai Media Credit enhances the WordPress Image block by adding a dedicated "Media Credit" field in the block settings sidebar. This allows content creators to easily add proper attribution to images directly within the block editor, with the credit information being stored as attachment metadata for consistent display across your site.

## Features

- **Block Editor Integration**: Adds a "Media Credit" field to the Image block settings sidebar
- **Attachment Metadata Storage**: Stores media credits as attachment metadata (`_media_credit`)
- **Automatic Display**: Automatically displays media credits on the frontend for:
  - Image blocks in the block editor
  - Classic editor images (converted to blocks)
  - Genesis framework entry images (single posts)
- **Real-time Saving**: Credits are saved automatically with debounced AJAX requests
- **Admin Interface**: Media credit field available in the WordPress Media Library attachment edit screen

## Requirements

- WordPress 6.7 or higher
- PHP 8.2 or higher
- Block editor (Gutenberg)

## Installation

### Manual Installation

1. Download the plugin files
2. Upload the `mai-media-credit` folder to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

### Development Installation

1. Clone the repository to your `/wp-content/plugins/` directory
2. Run `npm install` to install dependencies
3. Run `npm run build` to build the assets
4. Activate the plugin through the 'Plugins' menu in WordPress

## Usage

### Adding Media Credits in the Block Editor

1. Insert an Image block into your post or page
2. Select the image block
3. In the block settings sidebar, you'll see a "Media Credit" field
4. Enter the attribution/credit information (e.g., "Photo by John Doe", "Courtesy of Company Name")
5. The credit will be automatically saved and displayed on the frontend

### Adding Media Credits in the Media Library

1. Go to Media Library
2. Edit any image attachment
3. You'll see a "Media Credit" field in the attachment details
4. Enter the attribution information
5. Update the attachment

### Frontend Display

Media credits are automatically displayed on the frontend in the following formats:

- **Image Blocks**: Credits appear below the image with the format "Photo: [credit]"
- **Classic Editor Images**: Credits are added to images in classic editor content
- **Mai Theme**: Credits are displayed for entry images on single posts

## Development

### Building Assets

```bash
# Install dependencies
npm install

# Build for production
npm run build

# Start development mode with hot reloading
npm run start

### Customization

#### Styling Media Credits

You can customize the appearance of media credits by adding CSS to your theme:

```css
.media-credit {
	display: block;
	font-size: 0.875rem;
	color: #666;
	font-style: italic;
	margin-top: 0.5rem;
}
```

#### Modifying Credit Format

To change the credit display format, you can filter the credit text in your theme's `functions.php`:

```php
add_filter( 'mai_media_credit_prefix', function( $prefix, $attachment_id ) {
	$prefix = 'Attribution: ';
	return $prefix;
}, 10, 2 );
```

## Support

For support, feature requests, or bug reports, please visit the [GitHub repository](https://github.com/maithemewp/mai-media-credit).

## License

This plugin is licensed under the GPL v2 or later.

## Credits

Developed by [BizBudding](https://bizbudding.com/).
