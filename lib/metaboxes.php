<?php
// Include & setup custom metabox and fields
$prefix = '_gfi_'; // start with an underscore to hide fields from custom fields list
add_filter( 'cmb_meta_boxes', 'gfi_sample_metaboxes' , 55 );
function gfi_sample_metaboxes( $meta_boxes ) {
	global $prefix;
	
	$types = array();
	$post_types = get_post_types( array( 'public' => true ) );
	foreach ( $post_types as $post_type ) {
		if ( post_type_supports( $post_type, 'thumbnail' ) )
			$types[] = $post_type;
	}
	
	$sizes = genesis_get_image_sizes();
	$count = 0;
	foreach ( $sizes as $name => $size ) {
		unset( $sizes[$name] );
		$sizes[] = array(
			'name' => ucfirst( $name ) . ' (' . $size['width'] . 'x' . $size['height'] . ')',
			'value' => $name,
		);
	}
	$empty = array(
			'name' => __ ( 'Select', GFI_DOMAIN ),
			'value' => '',
		);
	array_unshift ( $sizes , $empty ); 
	
	$meta_boxes[] = array(
		'id' => 'genesis_post_image',
		'title' => __( 'Post Thumbnail Size', GFI_DOMAIN ),
		'pages' => $types, // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __ ( 'Post Featured Image Size', GFI_DOMAIN ),
				'desc' => __ ( 'Set a custom size for your featured image.', GFI_DOMAIN ),
				'type' => 'title',
				'id' => $prefix . 'post_image_title',
			),
			array(
				'name' => 'Featured Image Select',
				'desc' => 'Select a Featured Image Size',
				'id' => $prefix . 'custom_feat_img',
				'type' => 'select',
				'options' => $sizes,
			),
			/*
			array(
				'name' => __ ( 'Post Image', GFI_DOMAIN ),
				'desc' => __( 'post image <acronym title="Uniform Resource Locator">URL</acronym> (including <code>http://</code>)', GFI_DOMAIN ),
				'id' => $prefix . 'post_image',
				'type' => 'text',
				'default' => 'http://',
			),
			array(
				'name' => __ ( 'Post Image', GFI_DOMAIN ),
				'desc' => __( 'post image <code>alt</code> text', GFI_DOMAIN ),
				'id' => $prefix . 'post_image_alt',
				'type' => 'text',
				'default' => 'http://',
			),
			array(
				'name' => '',
				'desc' => __( 'add a frame to this post image', GFI_DOMAIN ),
				'id' => $prefix . 'post_image_frame',
				'type' => 'radio_inline',
				'options' => array(
					array ( 'name' => __( 'frame this post image' , GFI_DOMAIN ) , 'value' => 'frame' ),
					array ( 'name' => __( 'do not frame this post image' , GFI_DOMAIN ), 'value' => '' ),
				),
			),
			array(
				'name' => __( 'Horizontal Position', GFI_DOMAIN ),
				'desc' => '',
				'id' => $prefix . 'post_image_horizontal',
				'type' => 'radio',
				'options' => array(
					array( 'name' => __( 'flush left with no text wrap', GFI_DOMAIN ) , 'value' => 'flush' ),
					array( 'name' => __( 'left with text wrap', GFI_DOMAIN ) , 'value' => 'left' ),
					array( 'name' => __( 'right with text wrap', GFI_DOMAIN ) , 'value' => 'right' ),				
					array( 'name' => __( 'centered (no wrap)', GFI_DOMAIN ) , 'value' => 'center' ),
				),
			),
			array(
				'name' => __( 'Vertical Position', GFI_DOMAIN ),
				'desc' => '',
				'id' => $prefix . 'post_image_vertical',
				'type' => 'radio',
				'options' => array(
					array( 'name' => __( 'above/before headline', GFI_DOMAIN ) , 'value' => 'before-headline' ),
					array( 'name' => __( 'below headline', GFI_DOMAIN ) , 'value' => 'after-headline' ),
					array( 'name' => __( 'before post/page content', GFI_DOMAIN ) , 'value' => 'before-post' ),				
				)
			),
			array(
				'name' => __ ( 'Thumbnail Image', GFI_DOMAIN ),
				'desc' => __( 'If you like, you can supply your own thumbnail image. If you do this, the new thumbnail image will not be cropped (unless you choose a cropped version to insert), so make sure that you size the image appropriately before adding it here. If you supply a post image for this post but have not supplied your own thumbnail image, Thesis will auto-crop your post image into a thumbnail. The resulting thumbnail will be cropped to the dimensions specified below. If you&#8217;d like to change the default crop dimensions, you can do so on the <a href="%1$s">Thesis Options</a> page.', GFI_DOMAIN ),
				'type' => 'title',
				'id' => $prefix . 'thumb_title',
			),
			array(
				'name' => __( 'Thumbnail Image', GFI_DOMAIN ),
				'desc' => '',
				'id' => $prefix . 'test_image',
				'type' => 'file'
			),
			array(
				'name' => 'Width (px)',
				'desc' => __( 'Enter number of pixels', GFI_DOMAIN ),
				'id' => $prefix . 'thumb_width',
				'type' => 'text_small'
			),
			array(
				'name' => 'Height (px)',
				'desc' => __( 'Enter number of pixels', GFI_DOMAIN ),
				'id' => $prefix . 'thumb_height',
				'type' => 'text_small'
			),
			*/
		),
	);
	
	return $meta_boxes;
}

// Initialize the metabox class
add_action( 'init', 'gfi_initialize_cmb_meta_boxes', 9990 );
function gfi_initialize_cmb_meta_boxes() {
	if ( !class_exists( 'cmb_Meta_Box' ) ) {
		require_once(GFI_PLUGIN_DIR . '/lib/metaboxes/init.php');
	}
}