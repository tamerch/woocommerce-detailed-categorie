<?php

/**
* Override template functions from woocommerce/content-product_cat.php 
* with our own template functions file
*/
function wc_detailed_cat_include_template_functions() {
	global $plugin_dir;
	$plugin_dir = plugin_dir_url( __FILE__ );
	
	// get current category	
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	isset($term->term_id) ? $is_detailed_category = get_woocommerce_term_meta( $term->term_id, '_woocommerce_detailed_category', true ) : $is_detailed_category = 0;
	
	// replace template only if is_detailed
	if ($is_detailed_category & !is_single()){ 
		$template_file = plugin_dir_path( __FILE__ ) . 'templates/content-product_cat.php';
		include_once ($template_file);
		exit;
	}	
}
add_action( 'template_redirect', 'wc_detailed_cat_include_template_functions', 1 );

/**
* enqueue scripts and style
*/
function script_enqueue() {
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	isset($term->term_id) ? $is_detailed_category = get_woocommerce_term_meta( $term->term_id, '_woocommerce_detailed_category', true ) : $is_detailed_category = 0;
	if ($is_detailed_category) {
		// only load ad-gallery if is post : prevent from loading on other pages
		wp_register_script('wc_dc_sortable', plugins_url('/assets/js/sortable.min.js', __FILE__));
		wp_enqueue_script('wc_dc_sortable');
		wp_register_script('wc_dc_loading_cart', plugins_url('/assets/js/loading-cart.min.js', __FILE__));
		wp_enqueue_script('wc_dc_loading_cart');
		wp_register_style( 'wc_dc_sortable_style',  plugins_url('/assets/css/style.css', __FILE__) );
		wp_enqueue_style( 'wc_dc_sortable_style' );
	}
}
add_action('wp_enqueue_scripts', 'script_enqueue'); // For use on the Front end (ie. Theme)	
		