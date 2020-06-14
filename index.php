<?php
/*
Plugin Name: Woocommerce Custom Tab
Plugin URI: http://nirab.xyz
Description: A WordPress Task
Author: Istiaq Nirab
Version: 1.0.0
Author URI: http://nirab.xyz/
Text Domain: wctab
*/

If ( !function_exists('add_action') ) {
    echo "Hi There ! I'm just a plugin not much i can do when called directly .";
    exit;
}

include "check.php";

// Load Vue & Assets
function wctb_load_vuescripts() {
	wp_register_style("alert_css", plugin_dir_url( __FILE__ ).'public/alert.css' );
	wp_register_script('app', plugin_dir_url( __FILE__ ).'public/app.js', true);
}

add_action("admin_enqueue_scripts", "wctb_load_vuescripts");

// Admin Area
function wctb_admin($tabs)
{
    wp_enqueue_style("alert_css");
	wp_enqueue_script('app');
	$tabs['custom_text'] = array(
		'label' 	=> __('Custom Text', 'wctab'),
		'target' 	=> 'wctab_form'
	);
	return $tabs;
}
add_filter('woocommerce_product_data_tabs', 'wctb_admin');

/**
 * Contents of the custom text product tab. [ Admin ]
 */
function wctab_admin_form()
{
    global $post;
    $meta = get_post_meta( $post->ID, 'wctab_custom_txt', true );
	?>
	<div id='wctab_form' class='panel woocommerce_options_panel'>
		<div id='app'>
			<tab :product_id='<?php echo $post->ID; ?>' />
		</div>
	</div>
	<?php
}
add_filter('woocommerce_product_data_panels', 'wctab_admin_form');

// Register Rest Route For Vue Axios
function wctb_rest_api()
{
	register_rest_route( 'wctb/v1', '/wctb-post-data', array(
		'methods' => 'POST',
		'callback' => 'wctb_post_meta',
    ));

    register_rest_route( 'wctb/v1', '/get-wctb-post-data', array(
		'methods' => 'POST',
		'callback' => 'get_wctb_post_meta',
	));
}
add_action('rest_api_init', 'wctb_rest_api');

// Save Post Meta
function wctb_post_meta(WP_REST_Request $request)
{
    update_post_meta($request['product_id'],'wctab_custom_txt', $request["custom_txt"]);
    return "success";
}

function get_wctb_post_meta(WP_REST_Request $request)
{
    $meta = get_post_meta($request['product_id'], 'wctab_custom_txt', true);
    return $meta;
}


/**
 * Add the custom tab [ Front ]
 */
function wctb_custom_product_tab( $tabs ) {
    $tabs['wctb_custom_tab'] = array(
        'title'    => __( 'Custom Text', 'textdomain' ),
        'callback' => 'wctb_custom_tab_content',
        'priority' => 20,
    );
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'wctb_custom_product_tab' );

/**
 * Function that displays output for the custom tab. [ Front ]
 */
function wctb_custom_tab_content( $slug, $tab ) {
    global $post;
    $meta = get_post_meta($post->ID, 'wctab_custom_txt', true);
	?>
        <h2><?php echo wp_kses_post( $tab['title'] ); ?></h2>
        <p><?php echo $meta; ?></p>
    <?php
}