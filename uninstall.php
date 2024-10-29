<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit ();
}

/**
 * Delete all defined options
 */
delete_option( 'affilinet_product_widgets_publisher_id' );
delete_option( 'affilinet_product_widgets_publisher_webservice_password' );
delete_option( 'affilinet_product_widgets_last_credential_change' );
delete_option( 'affilinet_product_widgets_webservice_login_is_correct' );




/**
 * unregister settings
 */
unregister_setting( 'affilinet-product-widgets-settings-group', 'affilinet_product_widgets_publisher_id' );
unregister_setting( 'affilinet-product-widgets-settings-group', 'affilinet_product_widgets_publisher_webservice_password' );
unregister_setting( 'affilinet-product-widgets-settings-group', 'affilinet_product_widgets_last_credential_change' );





