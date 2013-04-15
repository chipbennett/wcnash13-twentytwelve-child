<?php
/**
 * WCNash13 TwentyTwelve Child Theme Functions
 */


/**
 * Get Custom Layout
 * 
 * Returns the custom layout for the
 * current context, as a string, or 'default'
 * if no custom layout is defined.
 * 
 * @param	none
 * @return	string	$layout	custom layout for the current context; default 'default'
 */
function wcnash13_twentytwelve_child_get_layout() {
	$layout = 'default';
	global $post;
	$post_layout = get_post_meta( $post->ID, '_wcnash13_twentytwelve_child_layout', true );
	if ( '' != $post_layout ) {
		$layout = $post_layout;
	}
    return $layout;
}

/**
 * Add layout to body class
 * 
 * Filters the body_class array of classes
 * to include the 'layout-$layout' class
 */
function wcnash13_twentytwelve_child_filter_body_class( $classes ) {
	$classes[] = 'layout-' . wcnash13_twentytwelve_child_get_layout();
	return $classes;
}
add_filter( 'body_class', 'wcnash13_twentytwelve_child_filter_body_class' );

/**
 * Get Valid Custom Layouts
 */
function wcnash13_twentytwelve_child_get_layouts() {
	$layouts = array(
		'default' => __( 'Default', 'wcnash13_twentytwelve_child' ),
		'full' => __( 'Full-Width', 'wcnash13_twentytwelve_child' )
	);
	return $layouts;
}

/**
 * Add Layout Meta Box
 * 
 * @link	http://codex.wordpress.org/Function_Reference/_2			__()
 * @link	http://codex.wordpress.org/Function_Reference/add_meta_box	add_meta_box()
 */
function wcnash13_twentytwelve_child_add_layout_meta_box( $post ) {
    global $wp_meta_boxes;

    add_meta_box( 
		'wcnash13_twentytwelve_child_layout', 
		__( 'Static Page Layout', 'wcnash13_twentytwelve_child' ), 
		'wcnash13_twentytwelve_child_layout_meta_box', 
		'page', 
		'side', 
		'default' 
	);

}
// Hook meta boxes into 'add_meta_boxes'
add_action( 'add_meta_boxes_page', 'wcnash13_twentytwelve_child_add_layout_meta_box' );

/**
 * Define Layout Meta Box
 * 
 * Define the markup for the meta box
 * for the "layout" post custom meta
 * data. The metabox will consist of
 * radio selection options for "default"
 * and each defined, valid layout
 * option for single blog posts or
 * static pages, depending on the 
 * context.
 * 
 * @uses	wcnash13_twentytwelve_child_get_option_parameters()	Defined in \functions\options.php
 * @uses	checked()
 * @uses	get_post_custom()
 */
function wcnash13_twentytwelve_child_layout_meta_box() {
	global $post;
	$custom = get_post_meta( $post->ID, '_wcnash13_twentytwelve_child_layout', true );
	$layout = ( '' != $custom ? $custom : 'default' );
	$valid_layouts = wcnash13_twentytwelve_child_get_layouts();
	?>
	<p>
	<?php foreach ( $valid_layouts as $name => $description ) { ?>
		<input type="radio" name="_wcnash13_twentytwelve_child_layout" <?php checked( $name == $layout ); ?> value="<?php echo $name; ?>" /> 
		<label><?php echo $description; ?></label><br />
	<?php } ?>
	</p>
	<?php
}

/**
 * Validate, sanitize, and save post metadata.
 * 
 * Validates the user-submitted post custom 
 * meta data, ensuring that the selected layout 
 * option is in the array of valid layout 
 * options; otherwise, it returns 'default'.
 * 
 * @link	http://codex.wordpress.org/Function_Reference/update_post_meta	update_post_meta()
 * @link	http://php.net/manual/en/function.array-key-exists.php			array_key_exists()
 */
function wcnash13_twentytwelve_child_save_layout_post_metadata(){
	global $post;
	if ( ! isset( $post ) || ! is_object( $post ) ) {
		return;
	}
	$valid_layouts = wcnash13_twentytwelve_child_get_layouts();
	$layout = ( isset( $_POST['_wcnash13_twentytwelve_child_layout'] ) && array_key_exists( $_POST['_wcnash13_twentytwelve_child_layout'], $valid_layouts ) ? $_POST['_wcnash13_twentytwelve_child_layout'] : 'default' );

	update_post_meta( $post->ID, '_wcnash13_twentytwelve_child_layout', $layout );
}
// Hook the save layout post custom meta data into
// publish_{post-type}, draft_{post-type}, and future_{post-type}
add_action( 'publish_page', 'wcnash13_twentytwelve_child_save_layout_post_metadata' );
add_action( 'draft_page', 'wcnash13_twentytwelve_child_save_layout_post_metadata' );