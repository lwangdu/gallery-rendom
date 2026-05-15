<?php
/**
 * Plugin Name: Gallery Rendom
 * Description: Displays one randomized hero gallery image with title, description, buttons, and click-to-view captions.
 * Version: 1.0.15
 * Author: Lobsang Wangdu
 * Text Domain: gallery-rendom
 *
 * @package GalleryRendom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GALLERY_RENDOM_VERSION', '1.0.15' );
define( 'GALLERY_RENDOM_LAST_COOKIE', 'gallery_rendom_last_item' );
define( 'GALLERY_RENDOM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GALLERY_RENDOM_DEFAULT_CONTENT_BACKGROUND', '#f6f4ef' );
define( 'GALLERY_RENDOM_DEFAULT_TITLE_COLOR', '#1f1f1f' );
define( 'GALLERY_RENDOM_DEFAULT_DESCRIPTION_COLOR', '#3f3f3f' );
define( 'GALLERY_RENDOM_DEFAULT_BUTTON_BACKGROUND', '#004a89' );
define( 'GALLERY_RENDOM_DEFAULT_BUTTON_TEXT', '#ffffff' );
define( 'GALLERY_RENDOM_DEFAULT_BUTTON_HOVER_BACKGROUND', '#003764' );
define( 'GALLERY_RENDOM_DEFAULT_BUTTON_HOVER_TEXT', '#ffffff' );

/**
 * Register gallery item post type.
 */
function gallery_rendom_register_post_type() {
	$labels = array(
		'name'               => __( 'Gallery Rendom Items', 'gallery-rendom' ),
		'singular_name'      => __( 'Gallery Rendom Item', 'gallery-rendom' ),
		'add_new_item'       => __( 'Add New Gallery Item', 'gallery-rendom' ),
		'edit_item'          => __( 'Edit Gallery Item', 'gallery-rendom' ),
		'new_item'           => __( 'New Gallery Item', 'gallery-rendom' ),
		'view_item'          => __( 'View Gallery Item', 'gallery-rendom' ),
		'search_items'       => __( 'Search Gallery Items', 'gallery-rendom' ),
		'not_found'          => __( 'No gallery items found.', 'gallery-rendom' ),
		'not_found_in_trash' => __( 'No gallery items found in Trash.', 'gallery-rendom' ),
		'menu_name'          => __( 'Gallery Rendom', 'gallery-rendom' ),
	);

	register_post_type(
		'gallery_rendom_item',
		array(
			'labels'       => $labels,
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-format-gallery',
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'gallery_rendom_register_post_type' );

/**
 * Add item settings meta box.
 */
function gallery_rendom_add_meta_boxes() {
	add_meta_box(
		'gallery_rendom_details',
		__( 'Gallery Item Details', 'gallery-rendom' ),
		'gallery_rendom_render_meta_box',
		'gallery_rendom_item',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'gallery_rendom_add_meta_boxes' );

/**
 * Render meta box fields.
 *
 * @param WP_Post $post Current post.
 */
function gallery_rendom_render_meta_box( $post ) {
	wp_nonce_field( 'gallery_rendom_save_meta', 'gallery_rendom_meta_nonce' );

	$caption            = get_post_meta( $post->ID, '_gallery_rendom_caption', true );
	$primary_label      = get_post_meta( $post->ID, '_gallery_rendom_primary_label', true );
	$primary_url        = get_post_meta( $post->ID, '_gallery_rendom_primary_url', true );
	$secondary_label    = get_post_meta( $post->ID, '_gallery_rendom_secondary_label', true );
	$secondary_url      = get_post_meta( $post->ID, '_gallery_rendom_secondary_url', true );
	$image_position     = get_post_meta( $post->ID, '_gallery_rendom_image_position', true );
	$featured_image_tip = __( 'Use the Featured Image box for this hero image. The post title and editor content are displayed over the image.', 'gallery-rendom' );

	if ( ! $image_position ) {
		$image_position = 'center center';
	}
	?>
	<p><?php echo esc_html( $featured_image_tip ); ?></p>

	<p>
		<label for="gallery_rendom_image_position"><strong><?php esc_html_e( 'Image Focal Position', 'gallery-rendom' ); ?></strong></label>
		<select class="widefat" id="gallery_rendom_image_position" name="gallery_rendom_image_position">
			<?php
			$image_positions = array(
				'center center' => __( 'Center', 'gallery-rendom' ),
				'center top'    => __( 'Top', 'gallery-rendom' ),
				'center bottom' => __( 'Bottom', 'gallery-rendom' ),
				'left center'   => __( 'Left', 'gallery-rendom' ),
				'right center'  => __( 'Right', 'gallery-rendom' ),
			);

			foreach ( $image_positions as $value => $label ) :
				?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $image_position, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>

	<p>
		<label for="gallery_rendom_caption"><strong><?php esc_html_e( 'Hidden Caption', 'gallery-rendom' ); ?></strong></label>
		<textarea class="widefat" id="gallery_rendom_caption" name="gallery_rendom_caption" rows="3"><?php echo esc_textarea( $caption ); ?></textarea>
	</p>

	<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
		<p>
			<label for="gallery_rendom_primary_label"><strong><?php esc_html_e( 'Primary Button Text', 'gallery-rendom' ); ?></strong></label>
			<input class="widefat" id="gallery_rendom_primary_label" name="gallery_rendom_primary_label" type="text" value="<?php echo esc_attr( $primary_label ); ?>">
		</p>
		<p>
			<label for="gallery_rendom_primary_url"><strong><?php esc_html_e( 'Primary Button URL', 'gallery-rendom' ); ?></strong></label>
			<input class="widefat" id="gallery_rendom_primary_url" name="gallery_rendom_primary_url" type="url" value="<?php echo esc_url( $primary_url ); ?>">
		</p>
		<p>
			<label for="gallery_rendom_secondary_label"><strong><?php esc_html_e( 'Secondary Button Text', 'gallery-rendom' ); ?></strong></label>
			<input class="widefat" id="gallery_rendom_secondary_label" name="gallery_rendom_secondary_label" type="text" value="<?php echo esc_attr( $secondary_label ); ?>">
		</p>
		<p>
			<label for="gallery_rendom_secondary_url"><strong><?php esc_html_e( 'Secondary Button URL', 'gallery-rendom' ); ?></strong></label>
			<input class="widefat" id="gallery_rendom_secondary_url" name="gallery_rendom_secondary_url" type="url" value="<?php echo esc_url( $secondary_url ); ?>">
		</p>
	</div>
	<?php
}

/**
 * Save gallery item meta.
 *
 * @param int $post_id Current post ID.
 */
function gallery_rendom_save_meta( $post_id ) {
	if ( ! isset( $_POST['gallery_rendom_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gallery_rendom_meta_nonce'] ) ), 'gallery_rendom_save_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'_gallery_rendom_caption'         => array( 'gallery_rendom_caption', 'sanitize_textarea_field' ),
		'_gallery_rendom_primary_label'   => array( 'gallery_rendom_primary_label', 'sanitize_text_field' ),
		'_gallery_rendom_primary_url'     => array( 'gallery_rendom_primary_url', 'esc_url_raw' ),
		'_gallery_rendom_secondary_label' => array( 'gallery_rendom_secondary_label', 'sanitize_text_field' ),
		'_gallery_rendom_secondary_url'   => array( 'gallery_rendom_secondary_url', 'esc_url_raw' ),
		'_gallery_rendom_image_position'  => array( 'gallery_rendom_image_position', 'gallery_rendom_sanitize_image_position' ),
	);

	foreach ( $fields as $meta_key => $field ) {
		$field_name = $field[0];
		$callback   = $field[1];
		$value      = isset( $_POST[ $field_name ] ) ? call_user_func( $callback, wp_unslash( $_POST[ $field_name ] ) ) : '';

		if ( '' === $value ) {
			delete_post_meta( $post_id, $meta_key );
		} else {
			update_post_meta( $post_id, $meta_key, $value );
		}
	}
}
add_action( 'save_post_gallery_rendom_item', 'gallery_rendom_save_meta' );

/**
 * Add plugin settings page.
 */
function gallery_rendom_add_settings_page() {
	add_submenu_page(
		'edit.php?post_type=gallery_rendom_item',
		__( 'Gallery Rendom Settings', 'gallery-rendom' ),
		__( 'Settings', 'gallery-rendom' ),
		'manage_options',
		'gallery-rendom-settings',
		'gallery_rendom_render_settings_page'
	);
}
add_action( 'admin_menu', 'gallery_rendom_add_settings_page' );

/**
 * Register plugin settings.
 */
function gallery_rendom_register_settings() {
	$settings = array(
		'gallery_rendom_content_background'        => GALLERY_RENDOM_DEFAULT_CONTENT_BACKGROUND,
		'gallery_rendom_title_color'               => GALLERY_RENDOM_DEFAULT_TITLE_COLOR,
		'gallery_rendom_description_color'         => GALLERY_RENDOM_DEFAULT_DESCRIPTION_COLOR,
		'gallery_rendom_button_background'         => GALLERY_RENDOM_DEFAULT_BUTTON_BACKGROUND,
		'gallery_rendom_button_text'               => GALLERY_RENDOM_DEFAULT_BUTTON_TEXT,
		'gallery_rendom_button_hover_background'   => GALLERY_RENDOM_DEFAULT_BUTTON_HOVER_BACKGROUND,
		'gallery_rendom_button_hover_text'         => GALLERY_RENDOM_DEFAULT_BUTTON_HOVER_TEXT,
	);

	foreach ( $settings as $setting_name => $default ) {
		register_setting(
			'gallery_rendom_settings',
			$setting_name,
			array(
				'type'              => 'string',
				'sanitize_callback' => 'gallery_rendom_sanitize_hex_color',
				'default'           => $default,
			)
		);
	}
}
add_action( 'admin_init', 'gallery_rendom_register_settings' );

/**
 * Render plugin settings page.
 */
function gallery_rendom_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$content_background = gallery_rendom_get_content_background();
	$text_colors        = gallery_rendom_get_text_colors();
	$button_colors      = gallery_rendom_get_button_colors();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Gallery Rendom Settings', 'gallery-rendom' ); ?></h1>
		<?php if ( isset( $_GET['gallery-rendom-reset'] ) ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><?php esc_html_e( 'Gallery Rendom color settings were reset to defaults.', 'gallery-rendom' ); ?></p>
			</div>
		<?php endif; ?>
		<form method="post" action="options.php">
			<?php settings_fields( 'gallery_rendom_settings' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">
						<label for="gallery_rendom_content_background"><?php esc_html_e( 'Text Area Background Color', 'gallery-rendom' ); ?></label>
					</th>
					<td>
						<input class="regular-text" id="gallery_rendom_content_background" name="gallery_rendom_content_background" type="text" value="<?php echo esc_attr( $content_background ); ?>" pattern="^#?[0-9a-fA-F]{6}$" placeholder="#f6f4ef">
						<p class="description"><?php esc_html_e( 'Enter a 6-digit hex color, for example #004a89 or 004a89. This controls the right-side title, description, and button area background.', 'gallery-rendom' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="gallery_rendom_title_color"><?php esc_html_e( 'Title Text Color', 'gallery-rendom' ); ?></label>
					</th>
					<td>
						<input class="regular-text" id="gallery_rendom_title_color" name="gallery_rendom_title_color" type="text" value="<?php echo esc_attr( $text_colors['title'] ); ?>" pattern="^#?[0-9a-fA-F]{6}$" placeholder="#1f1f1f">
						<p class="description"><?php esc_html_e( 'Title color for the text area.', 'gallery-rendom' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="gallery_rendom_description_color"><?php esc_html_e( 'Description Text Color', 'gallery-rendom' ); ?></label>
					</th>
					<td>
						<input class="regular-text" id="gallery_rendom_description_color" name="gallery_rendom_description_color" type="text" value="<?php echo esc_attr( $text_colors['description'] ); ?>" pattern="^#?[0-9a-fA-F]{6}$" placeholder="#3f3f3f">
						<p class="description"><?php esc_html_e( 'Description color for the text area.', 'gallery-rendom' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="gallery_rendom_button_background"><?php esc_html_e( 'Button Background Color', 'gallery-rendom' ); ?></label>
					</th>
					<td>
						<input class="regular-text" id="gallery_rendom_button_background" name="gallery_rendom_button_background" type="text" value="<?php echo esc_attr( $button_colors['background'] ); ?>" pattern="^#?[0-9a-fA-F]{6}$" placeholder="#004a89">
						<p class="description"><?php esc_html_e( 'Primary button background and secondary button border/text color.', 'gallery-rendom' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="gallery_rendom_button_text"><?php esc_html_e( 'Button Text Color', 'gallery-rendom' ); ?></label>
					</th>
					<td>
						<input class="regular-text" id="gallery_rendom_button_text" name="gallery_rendom_button_text" type="text" value="<?php echo esc_attr( $button_colors['text'] ); ?>" pattern="^#?[0-9a-fA-F]{6}$" placeholder="#ffffff">
						<p class="description"><?php esc_html_e( 'Primary button text color.', 'gallery-rendom' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="gallery_rendom_button_hover_background"><?php esc_html_e( 'Button Hover Background Color', 'gallery-rendom' ); ?></label>
					</th>
					<td>
						<input class="regular-text" id="gallery_rendom_button_hover_background" name="gallery_rendom_button_hover_background" type="text" value="<?php echo esc_attr( $button_colors['hover_background'] ); ?>" pattern="^#?[0-9a-fA-F]{6}$" placeholder="#003764">
						<p class="description"><?php esc_html_e( 'Primary button hover background and secondary button hover background.', 'gallery-rendom' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="gallery_rendom_button_hover_text"><?php esc_html_e( 'Button Hover Text Color', 'gallery-rendom' ); ?></label>
					</th>
					<td>
						<input class="regular-text" id="gallery_rendom_button_hover_text" name="gallery_rendom_button_hover_text" type="text" value="<?php echo esc_attr( $button_colors['hover_text'] ); ?>" pattern="^#?[0-9a-fA-F]{6}$" placeholder="#ffffff">
						<p class="description"><?php esc_html_e( 'Button text color on hover and focus.', 'gallery-rendom' ); ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<hr>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="gallery_rendom_reset_colors">
			<?php wp_nonce_field( 'gallery_rendom_reset_colors' ); ?>
			<p><?php esc_html_e( 'Reset all Gallery Rendom color settings to their default values.', 'gallery-rendom' ); ?></p>
			<?php submit_button( __( 'Reset to Default Colors', 'gallery-rendom' ), 'secondary', 'submit', false ); ?>
		</form>
	</div>
	<?php
}

/**
 * Reset plugin color settings to defaults.
 */
function gallery_rendom_reset_colors() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to reset these settings.', 'gallery-rendom' ) );
	}

	check_admin_referer( 'gallery_rendom_reset_colors' );

	update_option( 'gallery_rendom_content_background', GALLERY_RENDOM_DEFAULT_CONTENT_BACKGROUND );
	update_option( 'gallery_rendom_title_color', GALLERY_RENDOM_DEFAULT_TITLE_COLOR );
	update_option( 'gallery_rendom_description_color', GALLERY_RENDOM_DEFAULT_DESCRIPTION_COLOR );
	update_option( 'gallery_rendom_button_background', GALLERY_RENDOM_DEFAULT_BUTTON_BACKGROUND );
	update_option( 'gallery_rendom_button_text', GALLERY_RENDOM_DEFAULT_BUTTON_TEXT );
	update_option( 'gallery_rendom_button_hover_background', GALLERY_RENDOM_DEFAULT_BUTTON_HOVER_BACKGROUND );
	update_option( 'gallery_rendom_button_hover_text', GALLERY_RENDOM_DEFAULT_BUTTON_HOVER_TEXT );

	wp_safe_redirect(
		add_query_arg(
			'gallery-rendom-reset',
			'1',
			admin_url( 'edit.php?post_type=gallery_rendom_item&page=gallery-rendom-settings' )
		)
	);
	exit;
}
add_action( 'admin_post_gallery_rendom_reset_colors', 'gallery_rendom_reset_colors' );

/**
 * Sanitize a hex color value.
 *
 * @param string $value Raw color.
 * @return string
 */
function gallery_rendom_sanitize_hex_color( $value ) {
	$value = trim( (string) $value );

	if ( $value && '#' !== $value[0] ) {
		$value = '#' . $value;
	}

	$color = sanitize_hex_color( $value );

	return $color ? $color : '';
}

/**
 * Get an option color with fallback.
 *
 * @param string $option_name Option name.
 * @param string $default     Default hex color.
 * @return string
 */
function gallery_rendom_get_color_option( $option_name, $default ) {
	$color = gallery_rendom_sanitize_hex_color( get_option( $option_name, $default ) );

	return $color ? $color : $default;
}

/**
 * Get configured content background color.
 *
 * @return string
 */
function gallery_rendom_get_content_background() {
	return gallery_rendom_get_color_option( 'gallery_rendom_content_background', GALLERY_RENDOM_DEFAULT_CONTENT_BACKGROUND );
}

/**
 * Get configured text colors.
 *
 * @return array
 */
function gallery_rendom_get_text_colors() {
	return array(
		'title'       => gallery_rendom_get_color_option( 'gallery_rendom_title_color', GALLERY_RENDOM_DEFAULT_TITLE_COLOR ),
		'description' => gallery_rendom_get_color_option( 'gallery_rendom_description_color', GALLERY_RENDOM_DEFAULT_DESCRIPTION_COLOR ),
	);
}

/**
 * Get configured button colors.
 *
 * @return array
 */
function gallery_rendom_get_button_colors() {
	return array(
		'background'       => gallery_rendom_get_color_option( 'gallery_rendom_button_background', GALLERY_RENDOM_DEFAULT_BUTTON_BACKGROUND ),
		'text'             => gallery_rendom_get_color_option( 'gallery_rendom_button_text', GALLERY_RENDOM_DEFAULT_BUTTON_TEXT ),
		'hover_background' => gallery_rendom_get_color_option( 'gallery_rendom_button_hover_background', GALLERY_RENDOM_DEFAULT_BUTTON_HOVER_BACKGROUND ),
		'hover_text'       => gallery_rendom_get_color_option( 'gallery_rendom_button_hover_text', GALLERY_RENDOM_DEFAULT_BUTTON_HOVER_TEXT ),
	);
}

/**
 * Sanitize the object-position value used for hero image cropping.
 *
 * @param string $value Raw object-position value.
 * @return string
 */
function gallery_rendom_sanitize_image_position( $value ) {
	$allowed_positions = array(
		'center center',
		'center top',
		'center bottom',
		'left center',
		'right center',
	);

	return in_array( $value, $allowed_positions, true ) ? $value : 'center center';
}

/**
 * Register front-end assets.
 */
function gallery_rendom_register_assets() {
	wp_register_style(
		'gallery-rendom',
		GALLERY_RENDOM_PLUGIN_URL . 'assets/gallery-rendom.css',
		array(),
		GALLERY_RENDOM_VERSION
	);

	if ( function_exists( 'wp_register_script_module' ) ) {
		wp_register_script_module(
			'gallery-rendom/view',
			GALLERY_RENDOM_PLUGIN_URL . 'assets/gallery-rendom-view.js',
			array( '@wordpress/interactivity' ),
			GALLERY_RENDOM_VERSION
		);
	} else {
		wp_register_script(
			'gallery-rendom',
			GALLERY_RENDOM_PLUGIN_URL . 'assets/gallery-rendom.js',
			array(),
			GALLERY_RENDOM_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'gallery_rendom_register_assets' );

/**
 * Enqueue the best available frontend behavior script.
 */
function gallery_rendom_enqueue_frontend_behavior() {
	if ( function_exists( 'wp_enqueue_script_module' ) ) {
		wp_enqueue_script_module( 'gallery-rendom/view' );
	} else {
		wp_enqueue_script( 'gallery-rendom' );
	}
}

/**
 * Choose one random gallery item, avoiding the item shown on the previous load.
 *
 * @return int
 */
function gallery_rendom_get_random_item_id() {
	$item_ids = get_posts(
		array(
			'post_type'              => 'gallery_rendom_item',
			'post_status'            => 'publish',
			'posts_per_page'         => -1,
			'fields'                 => 'ids',
			'orderby'                => 'menu_order',
			'order'                  => 'ASC',
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'cache_results'          => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( ! $item_ids ) {
		return 0;
	}

	$last_item_id = isset( $_COOKIE[ GALLERY_RENDOM_LAST_COOKIE ] ) ? absint( wp_unslash( $_COOKIE[ GALLERY_RENDOM_LAST_COOKIE ] ) ) : 0;
	$choices      = array_values( array_diff( array_map( 'absint', $item_ids ), array( $last_item_id ) ) );

	if ( ! $choices ) {
		$choices = array_map( 'absint', $item_ids );
	}

	return $choices[ array_rand( $choices ) ];
}

/**
 * Store the item that was rendered so the next page load can avoid repeating it.
 *
 * @param int $item_id Rendered gallery item ID.
 */
function gallery_rendom_remember_rendered_item( $item_id ) {
	if ( headers_sent() || ! $item_id ) {
		return;
	}

	setcookie(
		GALLERY_RENDOM_LAST_COOKIE,
		(string) absint( $item_id ),
		array(
			'expires'  => time() + DAY_IN_SECONDS,
			'path'     => defined( 'COOKIEPATH' ) && COOKIEPATH ? COOKIEPATH : '/',
			'domain'   => defined( 'COOKIE_DOMAIN' ) ? COOKIE_DOMAIN : '',
			'secure'   => is_ssl(),
			'httponly' => false,
			'samesite' => 'Lax',
		)
	);
}

/**
 * Render one randomized gallery hero shortcode.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function gallery_rendom_render_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'heading_level' => 2,
			'orderby'       => 'rand',
		),
		$atts,
		'gallery_rendom'
	);

	$heading_level = min( 6, max( 1, absint( $atts['heading_level'] ) ) );
	$heading_tag   = 'h' . $heading_level;
	$selected_item_id = gallery_rendom_get_random_item_id();

	if ( ! $selected_item_id ) {
		return '';
	}

	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( 'DONOTCACHEPAGE', true );
	}

	gallery_rendom_remember_rendered_item( $selected_item_id );

	$query = new WP_Query(
		array(
			'post_type'           => 'gallery_rendom_item',
			'post_status'         => 'publish',
			'posts_per_page'      => 1,
			'p'                   => $selected_item_id,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	if ( ! $query->have_posts() ) {
		return '';
	}

	wp_enqueue_style( 'gallery-rendom' );
	gallery_rendom_enqueue_frontend_behavior();

	ob_start();
	?>
	<div class="gallery-rendom gallery-rendom--hero">
		<?php
		while ( $query->have_posts() ) :
			$query->the_post();

			$post_id          = get_the_ID();
			$caption            = get_post_meta( $post_id, '_gallery_rendom_caption', true );
			$image_position     = gallery_rendom_sanitize_image_position( get_post_meta( $post_id, '_gallery_rendom_image_position', true ) );
			$content_background = gallery_rendom_get_content_background();
			$text_colors        = gallery_rendom_get_text_colors();
			$button_colors      = gallery_rendom_get_button_colors();
			$style              = sprintf(
				'--gallery-rendom-content-background:%1$s;--gallery-rendom-title-color:%2$s;--gallery-rendom-description-color:%3$s;--gallery-rendom-button-background:%4$s;--gallery-rendom-button-text:%5$s;--gallery-rendom-button-hover-background:%6$s;--gallery-rendom-button-hover-text:%7$s;',
				esc_attr( $content_background ),
				esc_attr( $text_colors['title'] ),
				esc_attr( $text_colors['description'] ),
				esc_attr( $button_colors['background'] ),
				esc_attr( $button_colors['text'] ),
				esc_attr( $button_colors['hover_background'] ),
				esc_attr( $button_colors['hover_text'] )
			);
			$primary_label      = get_post_meta( $post_id, '_gallery_rendom_primary_label', true );
			$primary_url        = get_post_meta( $post_id, '_gallery_rendom_primary_url', true );
			$secondary_label    = get_post_meta( $post_id, '_gallery_rendom_secondary_label', true );
			$secondary_url      = get_post_meta( $post_id, '_gallery_rendom_secondary_url', true );
			$title_id           = 'gallery-rendom-title-' . $post_id;
			$description_id     = 'gallery-rendom-description-' . $post_id;
			$caption_id         = 'gallery-rendom-caption-' . $post_id;
			$description        = get_the_excerpt() ? get_the_excerpt() : wp_strip_all_tags( get_the_content() );
			$description_html   = wpautop( wp_trim_words( $description, 32, '&hellip;' ) );
			$describedby        = array();

			if ( $description_html ) {
				$describedby[] = $description_id;
			}

			if ( $caption ) {
				$describedby[] = $caption_id;
			}

			$context = array(
				'isCaptionOpen'  => false,
				'showCaptionText' => __( 'Show image caption', 'gallery-rendom' ),
				'hideCaptionText' => __( 'Hide image caption', 'gallery-rendom' ),
			);
			?>
			<article class="gallery-rendom__item" style="<?php echo esc_attr( $style ); ?>" data-wp-interactive="galleryRendom" data-wp-context="<?php echo esc_attr( wp_json_encode( $context ) ); ?>" data-wp-on-document--keydown="actions.closeCaptionOnEscape" aria-labelledby="<?php echo esc_attr( $title_id ); ?>"<?php echo $describedby ? ' aria-describedby="' . esc_attr( implode( ' ', $describedby ) ) . '"' : ''; ?>>
				<div class="gallery-rendom__media">
					<?php
					if ( has_post_thumbnail() ) {
						$image_id  = get_post_thumbnail_id();
						$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

						echo wp_get_attachment_image(
							$image_id,
							'full',
							false,
							array(
								'alt'   => $image_alt ? $image_alt : get_the_title(),
								'class' => 'gallery-rendom__image',
								'style' => '--gallery-rendom-object-position:' . esc_attr( $image_position ) . ';',
							)
						);
					}
					?>
					<?php if ( $caption ) : ?>
						<button class="gallery-rendom__info" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $caption_id ); ?>" data-wp-on--click="actions.toggleCaption" data-wp-bind--aria-expanded="context.isCaptionOpen">
							<span aria-hidden="true">i</span>
							<span class="screen-reader-text" data-wp-text="state.captionButtonLabel"><?php esc_html_e( 'Show image caption', 'gallery-rendom' ); ?></span>
						</button>
					<?php endif; ?>
				</div>

				<div class="gallery-rendom__body">
					<<?php echo esc_html( $heading_tag ); ?> class="gallery-rendom__title" id="<?php echo esc_attr( $title_id ); ?>"><?php the_title(); ?></<?php echo esc_html( $heading_tag ); ?>>
					<?php if ( $description_html ) : ?>
						<div class="gallery-rendom__description" id="<?php echo esc_attr( $description_id ); ?>"><?php echo wp_kses_post( $description_html ); ?></div>
					<?php endif; ?>

					<?php if ( ( $primary_label && $primary_url ) || ( $secondary_label && $secondary_url ) ) : ?>
						<div class="gallery-rendom__actions">
							<?php if ( $primary_label && $primary_url ) : ?>
								<a class="gallery-rendom__button gallery-rendom__button--primary" href="<?php echo esc_url( $primary_url ); ?>"><?php echo esc_html( $primary_label ); ?></a>
							<?php endif; ?>
							<?php if ( $secondary_label && $secondary_url ) : ?>
								<a class="gallery-rendom__button gallery-rendom__button--secondary" href="<?php echo esc_url( $secondary_url ); ?>"><?php echo esc_html( $secondary_label ); ?></a>
							<?php endif; ?>
						</div>
					<?php endif; ?>

				</div>
				<?php if ( $caption ) : ?>
					<p class="gallery-rendom__caption" id="<?php echo esc_attr( $caption_id ); ?>" role="note" aria-live="polite" data-wp-bind--hidden="!context.isCaptionOpen" hidden><?php echo esc_html( $caption ); ?></p>
				<?php endif; ?>
			</article>
		<?php endwhile; ?>
	</div>
	<?php
	wp_reset_postdata();

	return ob_get_clean();
}
add_shortcode( 'gallery_rendom', 'gallery_rendom_render_shortcode' );
add_shortcode( 'gallery-rendom', 'gallery_rendom_render_shortcode' );
