<div class="wrap">
    <h2><?php _e( 'admin.settingsPageTitle', 'affilinet-product-widgets' ) ?></h2>

	<?php
	settings_errors('affilinet-product-widgets-settings-group');
	?>

    <h2 class="title"><?php _e('admin.headlineSettings', 'affilinet-product-widgets'); ?></h2>
    <form method="post" action="options.php">
		<?php settings_fields( 'affilinet-product-widgets-settings-group' ); ?>
		<?php do_settings_sections( 'affilinet-product-widgets-settings-group' );



		?>
        <table class="form-table">

            <tr valign="top">
                <th scope="row"><label for="affilinet_product_widgets_publisher_id"><?php _e('admin.labelPublisherId', 'affilinet-product-widgets'); ?></label></th>
                <td><input type="text" id="affilinet_product_widgets_publisher_id" style="min-width: 250px;" name="affilinet_product_widgets_publisher_id" value="<?php echo esc_attr( get_option('affilinet_product_widgets_publisher_id') ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="affilinet_product_widgets_publisher_webservice_password"><?php _e('admin.labelPassword', 'affilinet-product-widgets'); ?></label></th>
                <td><input type="password"
                           style="min-width: 250px;"
                           name="affilinet_product_widgets_publisher_webservice_password"
                           id="affilinet_product_widgets_publisher_webservice_password"
                           value="<?php echo esc_attr( get_option('affilinet_product_widgets_publisher_webservice_password') ); ?>" />
                    <br><small><a href="https://publisher.affili.net/Account/techSettingsPublisherWS.aspx?nr=1&pnp=54" target="_blank"><?php _e('admin.linkPassword', 'affilinet-product-widgets');?></small></td>
            </tr>

        </table>
        <input type="hidden" name="affilinet_product_widgets_last_credential_change" value="<?php echo time();?>"/>
		<?php
		if ( function_exists('submit_button')) {
			submit_button();
		} else {
			?><button class="button-primary" type="submit"><?php _e('admin.btnSave', 'affilinet-product-widgets');?></button><?php
		}

		?>
    </form>





    <h3 class="title"><?php _e('admin.headlineInstallExtension', 'affilinet-product-widgets'); ?></h3>
    <p>
	    <?php _e('admin.extensionText', 'affilinet-product-widgets'); ?>
    </p>


    <div class="affilinet-browser-tile" style="text-align: center; border: 1px solid #333333; width: 150px; float: left; margin: 10px 20px 10px 0; padding:20px">
        <a href="https://chrome.google.com/webstore/detail/affilinet/inagpakammaoppabncecekaojkgfpknc" target="_blank">
            <i class="fa fa-chrome fa-4x"></i>
            <br>
            <br>
            <strong>Chrome</strong>
        </a>

        </div>

        <div class="affilinet-browser-tile" style="text-align: center; border: 1px solid #333333; width: 150px; float: left; margin: 10px 20px 10px 0; padding:20px">
            <a href="https://addons.mozilla.org/de/firefox/addon/affilinet-extension/" target="_blank">
                <i class="fa fa-firefox fa-4x"></i>
                <br>
                <br>
                <strong>Firefox</strong>
            </a>
        </div>

    <!--

    <div class="affilinet-browser-tile" style="text-align: center; border: 1px solid #333333; width: 150px; float: left; margin: 10px 20px 10px 0; padding:20px">
        <i class="fa fa-opera fa-4x"></i>
        <br>
        <br>
        <strong>Opera<strong>
    </div>

    <div class="affilinet-browser-tile" style="text-align: center; border: 1px solid #333333; width: 150px; float: left; margin: 10px 20px 10px 0; padding:20px">
        <i class="fa fa-safari fa-4x"></i>
        <br>
        <br>
        <strong>Safari<strong>
    </div>

    -->


    <div style="clear: both"></div>



    <div class="affilinet-browser-extension-show-v2" >

        <h2><?php _e('admin.extensionDetected', 'affilinet-product-widgets'); ?></h2>
        <p>

        </p>
        <button class="button-primary" onclick="openInWidgetsPageInBrowserExtension(null)"><?php _e('admin.openExtension', 'affilinet-product-widgets');?></button>
    </div>




</div>
