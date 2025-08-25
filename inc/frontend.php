<?php

namespace Mai\MediaCredit;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_filter( 'genesis_markup_entry-image-link_content', __NAMESPACE__ . '\add_media_credit_to_entry_image', 10, 2 );
/**
 * Add media credit in the image wrap.
 *
 * @since 0.1.0
 *
 * @param string $content The content of the image wrap.
 * @param array  $args    The data.
 *
 * @return string The content of the image wrap.
 */
function add_media_credit_to_entry_image( $content, $args ) {
	$context = $args['params']['args']['context'] ?? '';

	if ( 'single' !== $context ) {
		return $content;
	}

	$image_id = $args['params']['args']['image_id'] ?? null;
	$credit   = $image_id ? get_post_meta( $image_id, '_media_credit', true ) : '';

	if ( $credit ) {
		$credit   = sprintf( '%s%s', get_media_credit_prefix(), $credit );
		$credit   = '<span class="media-credit">' . $credit . '</span>';
		$content .= $credit;
	}

	return $content;
}

add_filter( 'render_block_core/image', __NAMESPACE__ . '\add_media_credit_to_image_block', 10, 2 );
/**
 * Add media credit in the image block.
 *
 * @since 0.1.0
 *
 * @param string $block_content The block content.
 * @param array  $block         The block.
 *
 * @return string The block content.
 */
function add_media_credit_to_image_block( $block_content, $block ) {
	$image_id = $block['attrs']['id'] ?? null;
	$credit   = $image_id ? get_post_meta( $image_id, '_media_credit', true ) : '';

	if ( $credit ) {
		$credit        = sprintf( '%s%s', get_media_credit_prefix(), $credit );
		$credit        = '<span class="media-credit">' . $credit . '</span>';
		$block_content = str_replace( '</figure>', $credit . '</figure>', $block_content );
	}

	return $block_content;
}

add_filter( 'the_content', __NAMESPACE__ . '\add_media_credit_to_image_content', 8 );
/**
 * Add media credit to images in content that doesn't have blocks (classic editor).
 * Runs before blocks are parsed.
 *
 * @since 0.1.0
 *
 * @param string $content The content of the image.
 *
 * @return string The content of the image.
 */
function add_media_credit_to_image_content( $content ) {
	// Bail if not the main query or not a single post.
	if ( ! is_singular() || ! is_main_query() ) {
		return $content;
	}

	// Bail if post already has blocks.
	if ( has_blocks( $content ) ) {
		return $content;
	}

	// This is wild, let's just convert to blocks ;P
	$content = preg_replace_callback(
		'/<figure([^>]*)>(.*?)<img([^>]*)class="([^"]*wp-image-(\d+)[^"]*)"([^>]*)>(.*?)<\/figure>/is',
		function( $m ) {
			$class = $m[4] ?? '';
			$class = explode( ' ', $class );
			$class = array_filter( $class, function( $class ) {
				return str_contains( $class, 'wp-image-' );
			});
			$class = reset( $class );
			$class = str_replace( 'wp-image-', '', $class );
			$class = absint( $class );
			$credit = get_post_meta( $class, '_media_credit', true );
			if ( $credit ) {
				$credit = sprintf( '%s%s', get_media_credit_prefix(), $credit );
				$credit = '<span class="media-credit">' . $credit . '</span>';
				$m[0]   = str_replace( '</figure>', $credit . '</figure>', $m[0] );
			}
			return $m[0];
		},
		$content
	);

	return $content;
}

/**
 * Get the media credit prefix.
 *
 * @since 0.1.0
 *
 * @return string The media credit prefix.
 */
function get_media_credit_prefix() {
	$prefix = apply_filters( 'mai_media_credit_prefix', __( 'Credit: ', 'mai-media-credit' ) );

	return wp_kses_post( $prefix );
}