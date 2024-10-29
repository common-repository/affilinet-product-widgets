<?php


class AffilinetWidgetsHelper
{
	/**
	 * Helper to display an error message
	 *
	 * @param $message
	 * @param string $type
	 * @param bool $icon
	 */
    public static function displayHugeAdminMessage($message, $type = 'error', $icon = false)
    {
	    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	    add_action( 'admin_notices', function() use ($message , $type, $icon ){

		    ?>
            <div class="notice-<?php echo $type?> notice" style="min-height:75px;">

			    <?php
			    if ($icon !== false) {
				    switch ($type) {
					    case'error' : $color = 'rgb(230, 73, 64)';break;
					    case'warning' : $color = 'rgb(255, 197, 2)';break;
					    case'success' : $color = 'rgb(84, 190, 100)';break;
					    case'info' :
					    default:
						    $color = 'rgb(23, 175, 218)';
				    }
				    ?>
                    <div style="width: 50px;padding: 10px 20px;display: inline-block;">
                        <i class="fa <?php echo $icon; ?>" style="font-size: 40px; color: <?php echo $color;?>; position:absolute; margin-top:10px;"></i>
                    </div>
				    <?php
			    }
			    ?>


                <p style="display: inline-block;position: absolute; margin-top: 18px;">
                    <strong>affilinet Product Widgets</strong> <br>
					    <?php echo $message;?>
                </p>
                <div class="clearfix"></div>
            </div>
		    <?php

        });

    }


    /**
     * Returns current plugin version.
     *
     * @return string Plugin version
     */
    public static function get_plugin_version()
    {
        if ( ! function_exists( 'get_plugins' ) )
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        $plugin_folder = get_plugin_data( AFFILINET_PRODUCT_WIDGETS_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'affilinet.php')  ;

        return $plugin_folder['Version'];
    }
}
