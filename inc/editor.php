<?php

namespace Mai\MediaCredit;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_editor_assets' );
/**
 * Enqueue editor assets.
 *
 * @since 0.1.0
 *
 * @return void
 */
function enqueue_editor_assets() {
	// Get asset file.
	$asset_file = include plugin_dir_path( dirname( __FILE__ ) ) . 'build/editor.asset.php';

	// Enqueue editor styles.
	wp_enqueue_style(
		'mai-media-credit-editor',
		plugin_dir_url( dirname( __FILE__ ) ) . 'build/editor.css',
		[],
		$asset_file['version']
	);

	// Enqueue editor script.
	wp_enqueue_script(
		'mai-media-credit-editor',
		plugin_dir_url( dirname( __FILE__ ) ) . 'build/editor.js',
		$asset_file['dependencies'],
		$asset_file['version'],
		true
	);

	// Localize script.
	wp_localize_script(
		'mai-media-credit-editor',
		'maiMediaCredit',
		[
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'mai_media_credit_nonce' ),
		]
	);
}
