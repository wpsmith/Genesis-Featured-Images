<?php

/*
 * Sets defaults in the Genesis Theme Settings
 */

add_filter('genesis_theme_settings_defaults', 'genesis_featimg_defaults');
function genesis_featimg_defaults($defaults) {
	$defaults['featimg_default_enable'] = 0;
	$defaults['featimg_url'] = '';
	
	return $defaults;
}

add_action( 'genesis_theme_settings_metaboxes', 'gfi_theme_settings_boxes' );
/**
 * Adds a Genesis Featured Images Metabox to Genesis > Theme Settings
 * 
 */
function gfi_theme_settings_boxes( $pagehook ) {
    add_meta_box('genesis-theme-settings-featimg', __('Featured Image Settings', 'gfi'), 'genesis_theme_settings_featimg_box', $pagehook, 'main');
}

function genesis_theme_settings_featimg_box() { ?>
	<p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[featimg_default_enable]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[featimg_default_enable]" value="1" <?php checked(1, genesis_get_option('featimg_default_enable')); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[featimg_default_enable]"><?php _e('Enable the default featured image?', 'genfeatimg'); ?></label></p>
		
	<input type="text" size="100" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[featimg_url]" id="upload_image" value="<?php echo (genesis_get_option('featimg_url')) ? esc_attr( genesis_get_option('featimg_url') ) : ''; ?>" />
	<input type="button" name="upload_image_button" id="upload_image_button" value="<?php echo (genesis_get_option('featimg_url')) ? __("Change", 'genesis') : __("Add New", 'genesis'); ?>" />

	<p><span class="description"><?php printf( __('Use the Media Uploader to upload your default image. Then click Insert into Post to pull the url into the textbox.', 'genesis') ); ?></span></p>
	
<?php
}

/**
 * Adds necessary scripts and styles to load only on Genesis Settings Page
 * 
 */ 
add_action( 'init' , 'genesis_featimg_admin' );
function genesis_featimg_admin() {
	if (isset($_GET['page']) && $_GET['page'] == 'genesis') {
		add_action( 'admin_print_scripts' , 'genesis_featimg_admin_script' );
		add_action( 'admin_print_styles' , 'genesis_featimg_admin_style' );
	}
}

function genesis_featimg_admin_script() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('my-upload', GFI_URL . '/js/my-script.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('my-upload');
}

function genesis_featimg_admin_style() {
	wp_enqueue_style('thickbox');
}

/**
 * Obtains the attachment id from url
 * 
 */ 
function get_attachment_id_from_url ($image_url) {

	global $wpdb;
	
	$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='%s'";
	$id = $wpdb->get_var( $wpdb->prepare( $query, $image_url ) );
	
	return $id;

}

/**
 * Filters genesis_get_image() returning the default image if html or url are empty.
 * 
 */ 
add_filter( 'genesis_get_image' , 'genesis_get_image_default' , 10 , 6 );
function genesis_get_image_default($output, $args, $id, $html, $url, $src) {
	
	if ( ( !$html || !$url ) && (genesis_get_option('featimg_default_enable')) ) {
		$featimg_url = genesis_get_option('featimg_url');
		$id = get_attachment_id_from_url($featimg_url);
		
		$html = wp_get_attachment_image($id, $args['size'], false, $args['attr']); 
		list($url) = wp_get_attachment_image_src($id, $args['size'], false, $args['attr']); 
	}
	
		// determine output
	if ( strtolower($args['format']) == 'html' )
		$output = $html;
	elseif ( strtolower($args['format']) == 'url' )
		$output = $url;
	else
		$output = $src;
		
	// return FALSE if $url is blank
	if ( empty($url) ) $output = FALSE;
	
	return $output;
	
}


