<?php

namespace Mai\MediaCredit;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action( 'init', __NAMESPACE__ . '\register_meta' );
/**
 * Register media credit meta.
 *
 * @since 0.1.0
 *
 * @return void
 */
function register_meta() {
	register_post_meta( 'attachment', '_media_credit', [
		'label'             => __( 'Media Credit', 'mai-media-credit' ),
		'sanitize_callback' => 'sanitize_text_field',
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
	] );
}

add_filter( 'attachment_fields_to_edit', __NAMESPACE__ . '\add_media_credit_field', 10, 2 );
/**
 * Add media credit field to attachment edit form.
 *
 * @since 0.1.0
 *
 * @param array $form_fields The form fields array.
 * @param object $post The post object.
 *
 * @return array The form fields array.
 */
function add_media_credit_field( $form_fields, $post ) {
	$form_fields['media_credit'] = [
		'label' => __( 'Media Credit', 'mai-media-credit' ),
		'input' => 'text',
		'value' => (string) get_post_meta( $post->ID, '_media_credit', true ),
		'helps' => __( 'Enter the attribution/credit for this media.', 'mai-media-credit' ),
	];

	return $form_fields;
}

add_filter( 'attachment_fields_to_save', __NAMESPACE__ . '\save_media_credit_field', 10, 2 );
/**
 * Save media credit field from attachment edit form.
 *
 * @since 0.1.0
 *
 * @param array $post       The post array.
 * @param array $attachment The attachment array.
 *
 * @return array The post array.
 */
function save_media_credit_field( $post, $attachment ) {
	$media_credit = $attachment['media_credit'] ?? '';

	// Delete media credit if it's empty.
	if ( empty( $media_credit ) ) {
		delete_post_meta( $post['ID'], '_media_credit' );
		return $post;
	}

	// Update media credit.
	update_post_meta( $post['ID'], '_media_credit', sanitize_text_field( $media_credit ) );

	return $post;
}