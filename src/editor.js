/**
 * Mai Media Credit - Image Block Extension
 * Adds a media credit field to the Image block settings sidebar.
 */

import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';
import { InspectorControls } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState, useEffect, useRef } from '@wordpress/element';

// Import editor styles
import './editor.scss';

/**
 * Register mediaCredit attribute with Image block
 */
const registerMediaCreditAttribute = ( settings, name ) => {
	if ( name === 'core/image' ) {
		settings.attributes = {
			...settings.attributes,
			mediaCredit: {
				type: 'string',
				default: '',
			},
		};
	}

	return settings;
};

addFilter( 'blocks.registerBlockType', 'mai-media-credit/register-attribute', registerMediaCreditAttribute );

/**
 * Extend the Image block with media credit functionality.
 */
const withMediaCredit = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		// Only apply to Image blocks
		if ( props.name !== 'core/image' ) {
			return <BlockEdit { ...props } />;
		}

		const { attributes, setAttributes } = props;
		const [ mediaCredit, setMediaCredit ] = useState( '' );
		const [ isLoading, setIsLoading ] = useState( false );
		const saveTimeoutRef = useRef( null );

		// Get attachment ID from the ID attribute
		const getAttachmentId = () => {
			return attributes.id || null;
		};

		const attachmentId = getAttachmentId();

		// Load media credit from attachment meta when attachment ID changes
		useEffect( () => {
			if ( attachmentId ) {
				loadMediaCredit( attachmentId );
			} else {
				// Clear media credit when no attachment is selected
				setMediaCredit( '' );
				setAttributes( { mediaCredit: '' } );
			}
		}, [ attachmentId ] );

		// Load media credit on initial render if not already set
		useEffect( () => {
			if ( attachmentId && ! attributes.mediaCredit ) {
				loadMediaCredit( attachmentId );
			}
		}, [] );

		// Cleanup timeout on unmount
		useEffect( () => {
			return () => {
				if ( saveTimeoutRef.current ) {
					clearTimeout( saveTimeoutRef.current );
				}
			};
		}, [] );

		/**
		 * Load media credit from server.
		 */
		const loadMediaCredit = async ( id ) => {
			if ( ! id ) return;

			setIsLoading( true );

			try {
				const formData = new FormData();
				formData.append( 'action', 'get_media_credit' );
				formData.append( 'attachment_id', id );
				formData.append( 'nonce', maiMediaCredit.nonce );

				const response = await fetch( maiMediaCredit.ajaxUrl, {
					method: 'POST',
					body: formData,
				} );

				const data = await response.json();

				if ( data.success ) {
					const creditValue = data.data.media_credit || '';
					setMediaCredit( creditValue );
					setAttributes( { mediaCredit: creditValue } );
				}
			} catch ( error ) {
				console.error( 'Error loading media credit:', error );
				setMediaCredit( '' );
				setAttributes( { mediaCredit: '' } );
			} finally {
				setIsLoading( false );
			}
		};

		/**
		 * Save media credit to server.
		 */
		const saveMediaCredit = async ( value ) => {
			if ( ! attachmentId ) return;

			setIsLoading( true );

			try {
				const formData = new FormData();
				formData.append( 'action', 'save_media_credit' );
				formData.append( 'attachment_id', attachmentId );
				formData.append( 'media_credit', value );
				formData.append( 'nonce', maiMediaCredit.nonce );

				const response = await fetch( maiMediaCredit.ajaxUrl, {
					method: 'POST',
					body: formData,
				} );

				const data = await response.json();

				if ( ! data.success ) {
					console.error( 'Error saving media credit:', data );
				}
			} catch ( error ) {
				console.error( 'Error saving media credit:', error );
			} finally {
				setIsLoading( false );
			}
		};

		/**
		 * Handle media credit change.
		 */
		const handleMediaCreditChange = ( value ) => {
			setMediaCredit( value );
			setAttributes( { mediaCredit: value } );

			// Debounced save to attachment meta
			if ( saveTimeoutRef.current ) {
				clearTimeout( saveTimeoutRef.current );
			}

			saveTimeoutRef.current = setTimeout( () => {
				if ( attachmentId ) {
					saveMediaCredit( value );
				}
			}, 500 );
		};

		return (
			<Fragment>
				<BlockEdit { ...props } />
				<InspectorControls>
					<div style={ { paddingLeft: '16px', paddingRight: '16px' } }>
						<TextControl
							label={ __( 'Media Credit', 'mai-media-credit' ) }
							help={ __( 'Enter the attribution/credit for this image.', 'mai-media-credit' ) }
							value={ mediaCredit || '' }
							onChange={ handleMediaCreditChange }
							disabled={ ! attachmentId }
							placeholder={ attachmentId ? __( 'Enter media credit...', 'mai-media-credit' ) : __( 'Select an image first', 'mai-media-credit' ) }
						/>
					</div>
				</InspectorControls>
			</Fragment>
		);
	};
}, 'withMediaCredit' );

addFilter( 'editor.BlockEdit', 'mai-media-credit/with-media-credit', withMediaCredit );

/**
 * Add data-media-credit attribute to Image block figure element in editor
 */
const addMediaCreditAttribute = createHigherOrderComponent( ( BlockListBlock ) => {
	return ( props ) => {
		if ( props.name !== 'core/image' ) {
			return <BlockListBlock { ...props } />;
		}

		// If no media credit, return normal block
		if ( ! props.attributes.mediaCredit ) {
			return <BlockListBlock { ...props } />;
		}

		// Add data-media-credit attribute to wrapperProps
		const wrapperProps = {
			...props.wrapperProps,
			'data-media-credit': props.attributes.mediaCredit,
		};

		return <BlockListBlock { ...props } wrapperProps={ wrapperProps } />;
	};
}, 'addMediaCreditAttribute' );

addFilter( 'editor.BlockListBlock', 'mai-media-credit/add-media-credit-attribute', addMediaCreditAttribute );
