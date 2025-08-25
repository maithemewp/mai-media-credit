<?php

namespace Mai\MediaCredit;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action( 'admin_head', __NAMESPACE__ . '\add_admin_styles' );
/**
 * Add admin styles for media credit field.
 *
 * @since 0.1.0
 *
 * @return void
 */
function add_admin_styles() {
	// Only add styles on media library pages.
	$screen = get_current_screen();
	if ( ! $screen || 'attachment' !== $screen->id ) {
		return;
	}
	echo '<style>.compat-field-media_credit input[type="text"] { width: 100%; max-width: none; }</style>';
}
