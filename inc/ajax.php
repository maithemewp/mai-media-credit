<?php

namespace Mai\MediaCredit;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_save_media_credit', __NAMESPACE__ . '\save_media_credit' );
/**
 * AJAX handler to save media credit.
 *
 * @since 0.1.0
 *
 * @return void
 */
function save_media_credit() {
	// Verify nonce
	if ( ! wp_verify_nonce( $_POST['nonce'], 'mai_media_credit_nonce' ) ) {
		wp_die( 'Security check failed' );
	}

	// Check permissions
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_die( 'Insufficient permissions' );
	}

	// Get attachment ID.
	$attachment_id = intval( $_POST['attachment_id'] ?? 0 );

	// Bail if attachment ID is not set.
	if ( ! $attachment_id ) {
		wp_send_json_error( 'Attachment ID is not set' );
	}

	// Get media credit.
	$media_credit = sanitize_text_field( $_POST['media_credit'] ?? '' );

	// Handle media credit.
	if ( empty( $media_credit ) ) {
		delete_post_meta( $attachment_id, '_media_credit' );
	} else {
		update_post_meta( $attachment_id, '_media_credit', $media_credit );
	}

	// Send success response.
	wp_send_json_success( [
		'media_credit' => $media_credit,
	] );
}

add_action( 'wp_ajax_get_media_credit', __NAMESPACE__ . '\get_media_credit' );
/**
 * AJAX handler to get media credit.
 *
 * @since 0.1.0
 *
 * @return void
 */
function get_media_credit() {
	// Verify nonce
	if ( ! wp_verify_nonce( $_POST['nonce'], 'mai_media_credit_nonce' ) ) {
		wp_die( 'Security check failed' );
	}

	// Get attachment ID.
	$attachment_id = intval( $_POST['attachment_id'] ?? 0 );

	// Bail if attachment ID is not set.
	if ( ! $attachment_id ) {
		wp_send_json_error( 'Attachment ID is not set' );
	}

	// Get media credit.
	$media_credit = get_post_meta( $attachment_id, '_media_credit', true );

	// Send success response.
	wp_send_json_success( [
		'media_credit' => $media_credit,
	] );
}