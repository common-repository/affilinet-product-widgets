<?php

class AffilinetWidgetsPlugin {

	public function __construct() {


		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_shortcode( 'affilinet_widget', array( $this, 'affilinet_product_widgets_shortcode' ) );
		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		add_filter( 'plugin_action_links_' .plugin_basename(AFFILINET_PRODUCT_WIDGETS_PLUGIN_FILE ), array( $this, 'plugin_add_settings_link' ) );
	}


	/**
	 * Register Settings for admin area
	 */
	public function admin_init() {
		register_setting( 'affilinet-product-widgets-settings-group', 'affilinet_product_widgets_publisher_id' );
		register_setting( 'affilinet-product-widgets-settings-group', 'affilinet_product_widgets_publisher_webservice_password' );
		// this value will change with every update and can be used to hook the password change
		register_setting( 'affilinet-product-widgets-settings-group', 'affilinet_product_widgets_last_credential_change', array(
			'sanitize_callback' => array(
				$this,
				'validate_credentials'
			)
		) );
	}


	function validate_credentials( $timestamp ) {
		// clear the cache to get the new values
		wp_cache_delete( 'alloptions', 'options' );

		// check credentials
		if ( AffilinetWidgetsApi::logon() === false ) {
			add_settings_error( 'affilinet_product_widgets_publisher_webservice_password', '1', __( 'errors.invalidCredentials', 'affilinet-product-widgets' ) );
			update_option( 'affilinet_product_widgets_webservice_login_is_correct', 'false', true );
			wp_cache_delete( 'alloptions', 'options' );

			return $timestamp;
		}

		return $timestamp;
	}

	function admin_notice() {

		if ( get_option( 'affilinet_product_widgets_webservice_login_is_correct', 'false' ) === 'false' ) {
			?>
            <div class="notice notice-warning is-dismissible">
                <p><?php _e( 'errors.loginIncorrect', 'affilinet-product-widgets' ); ?>
                    <a class="button"
                       href="<?php echo admin_url( 'options-general.php?page=affilinet-product-widgets-settings' ); ?>"><?php _e( 'errors.btnCheckSettings', 'affilinet-product-widgets' ); ?></a>

                </p>
            </div>
			<?php
		}
	}


	/**
	 * Create the admin Menu
	 */
	public function admin_menu() {
		// options menu
		add_options_page( 'affilinet Widgets Settings', 'affilinet Widgets', 'manage_options', 'affilinet-product-widgets-settings', 'AffilinetWidgetsView::settings' );
	}

	/**
	 * Register the widget
	 */
	public function register_widget() {
		register_widget( 'AffilinetWidgetsWidget' );
	}


	/**
	 * Load Admin scripts
	 *
	 * @param $hook string
	 */
	public function admin_enqueue_scripts( $hook ) {
		// on post page add the editor button for affilinet plugin
		if ( $hook === 'post.php' || $hook == 'post-new.php' ) {
			add_action( 'admin_head', array( $this, 'register_tiny_mce_buttons' ) );
			add_action( "admin_head-$hook", array( $this, 'add_tiny_mce_variables' ) );
		}

		// on settings page integrate font awesome


		if ( $hook == 'settings_page_affilinet-product-widgets-settings' ) {
			wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
			wp_enqueue_script('affilinet-webextension-bridge',  plugin_dir_url(dirname(__FILE__)) . 'js/affilinet_webextension_bridge.js');
			wp_enqueue_style('affilinet-webextension-style',  plugin_dir_url(dirname(__FILE__)) . 'css/admin.css');

		}

		elseif ($hook === 'widgets.php') {
			wp_enqueue_script('affilinet-webextension-bridge',  plugin_dir_url(dirname(__FILE__)) . 'js/affilinet_webextension_bridge.js');
			wp_enqueue_style('affilinet-webextension-style',  plugin_dir_url(dirname(__FILE__)) . 'css/admin.css');
        }

	}


	public function plugin_add_settings_link( $links ) {

		$settings_link = '<a href="' . admin_url( 'options-general.php?page=affilinet-product-widgets-settings' ) . '">' . __( 'admin.linkSettings', 'affilinet-product-widgets' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}


	/**
	 * Shortcode
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	public function affilinet_product_widgets_shortcode( $params = array() ) {
		// default $widget_id parameter
		/**
		 * @var String $size
		 */
		extract( shortcode_atts( array(
			'id' => 'notset',
		), $params ) );

		return AffilinetWidgetsWidget::getAdCode( $params['id'] );
	}

	/**
	 * TRANSLATION
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'affilinet-product-widgets', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages' );
	}

	/**
	 * TinyMCE Editor Button
	 */
	public function register_tiny_mce_buttons() {
		// check user permissions
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}
		// check if WYSIWYG is enabled
		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter( 'mce_external_plugins', array( $this, 'add_buttons' ) );
			add_filter( 'mce_buttons', array( $this, 'register_buttons' ) );
		}
	}

	/**
	 * Load TinyMCE Variables
	 */
	public function add_tiny_mce_variables() {
		$img               = plugin_dir_url( plugin_basename( dirname( __FILE__ ) ) ) . 'images/';
		$widgets           = AffilinetWidgetsApi::getMyWidgets();
		$widgetsForTinyMce = [];
		foreach ( $widgets as $widget ) {
			$widgetsForTinyMce[] = [
				'value' => $widget['id'],
				'id'    => $widget['id'],
				'text'  => $widget['widgetName'],
			];
		}
		?>
        <!-- TinyMCE Shortcode Plugin -->
        <script type='text/javascript'>
            var affilinet_product_widgets_mce_variables = {
                'image_path': '<?php echo $img; ?>',
                'choose_widget': 'Choose widget',
                'widgets': <?php echo json_encode( $widgetsForTinyMce, JSON_PRETTY_PRINT ); ?>,
            };
        </script>
        <!-- TinyMCE Shortcode Plugin -->
		<?php
	}

	public function add_buttons( $plugin_array ) {
		$plugin_array['affilinet_product_widgets_mce_button'] = plugin_dir_url( plugin_basename( dirname( __FILE__ ) ) ) . 'js/affilinet_product_widgets_mce_button.js';

		return $plugin_array;
	}

	public function register_buttons( $buttons ) {
		array_push( $buttons, 'affilinet_product_widgets_mce_button' );

		return $buttons;
	}

}
