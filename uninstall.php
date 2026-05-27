<?php
/**
 * Plugin uninstall cleanup.
 *
 * @package GalleryRendom
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$gallery_rendom_options = array(
	'gallery_rendom_content_background',
	'gallery_rendom_title_color',
	'gallery_rendom_description_color',
	'gallery_rendom_button_background',
	'gallery_rendom_button_text',
	'gallery_rendom_button_hover_background',
	'gallery_rendom_button_hover_text',
);

foreach ( $gallery_rendom_options as $gallery_rendom_option ) {
	delete_option( $gallery_rendom_option );
}

delete_transient( 'gallery_rendom_item_ids' );

// Gallery Rendom Item posts are user content, so uninstall intentionally leaves them in place.
