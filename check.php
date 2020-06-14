<?php
// check if WooCommerce is activated
function wctb_wc_check(){

	if ( class_exists( 'woocommerce' ) ) {

		global $wctb_wc_active;
		$wctb_wc_active = 'yes';

	} else {

		global  $wctb_wc_active;
		$wctb_wc_active = 'no';

	}

}
add_action( 'admin_init', 'wctb_wc_check' );

// show admin notice if WooCommerce is not activated
function wctb_wc_admin_notice(){

	global  $wctb_wc_active;

	if ( $wctb_wc_active == 'no' ){
		?>

		<div class="notice notice-error is-dismissible">
			<p>WooCommerce is not activated, please activate it to use <b>Woocommerce Custom Tab Plugin</b></p>
		</div>
		<?php

	}

}
add_action('admin_notices', 'wctb_wc_admin_notice');