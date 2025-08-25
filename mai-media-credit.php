<?php
/**
 * Plugin Name:       Mai Media Credit
 * Description:       Adds a media credit field to the Image block to store the attribution text as attachment metadata.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      8.2
 * Author:            BizBudding
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mai-media-credit
 */

namespace Mai\MediaCredit;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Must be at the top of the file.
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// Include files.
require_once __DIR__ . '/inc/ajax.php';
require_once __DIR__ . '/inc/meta.php';
require_once __DIR__ . '/inc/editor.php';
require_once __DIR__ . '/inc/frontend.php';
require_once __DIR__ . '/inc/admin.php';

add_action( 'plugins_loaded', __NAMESPACE__ . '\updater' );
/**
 * Setup the updater.
 *
 * composer require yahnis-elsts/plugin-update-checker
 *
 * @since 0.1.0
 *
 * @uses https://github.com/YahnisElsts/plugin-update-checker/
 *
 * @return void
 */
function updater() {
	// Bail if plugin updater is not loaded.
	if ( ! class_exists( 'YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
		return;
	}

	// Setup the updater.
	$updater = PucFactory::buildUpdateChecker( 'https://github.com/maithemewp/mai-media-credit/', __FILE__, 'mai-media-credit' );

	// Maybe set github api token.
	if ( defined( 'MAI_GITHUB_API_TOKEN' ) ) {
		$updater->setAuthentication( MAI_GITHUB_API_TOKEN );
	}

	// Add icons for Dashboard > Updates screen.
	if ( function_exists( 'mai_get_updater_icons' ) && $icons = \mai_get_updater_icons() ) {
		$updater->addResultFilter(
			function ( $info ) use ( $icons ) {
				$info->icons = $icons;
				return $info;
			}
		);
	}
}