<?php

class SmtpLocawebAdmin extends SmtpLocaweb {
	/**
	 * Setup backend functionality in WordPress
	 *
	 * @return none
	 * @since 0.1
	 */
	function __construct() {
		SmtpLocaweb::__construct();

		// Load localizations if available
		load_plugin_textdomain( 'smtp_locaweb' , false , 'smtp_locaweb/languages' );

		// Activation hook
		register_activation_hook( $this->plugin_file, array( &$this, 'init' ) );

		// Hook into admin_init and register settings and potentially register an admin_notice
		add_action( 'admin_init', array( &$this, 'admin_init' ) );

		// Activate the options page
		add_action( 'admin_menu', array( &$this , 'admin_menu' ) );

		// Register an AJAX action for testing mail sending capabilities
		add_action( 'wp_ajax_smtp_locaweb-test', array( &$this, 'ajax_send_test' ) );
	}

	/**
	 * Initialize the default options during plugin activation
	 *
	 * @return none
	 * @since 0.1
	 */
	function init() {
		$defaults = array(
			'username' => '',
			'password' => '',
			'secure' => '1',
			'from_email' => '',
			'from_name' => '',
		);
		if ( ! $this->options ) {
			$this->options = $defaults;
			add_option( 'smtp_locaweb', $this->options );
		}
	}

	/**
	 * Add the options page
	 *
	 * @return none
	 * @since 0.1
	 */
	function admin_menu() {
		if ( current_user_can( 'manage_options' ) ) {
			$this->hook_suffix = add_options_page( __( 'SmtpLocaweb', 'smtp_locaweb' ), __( 'SmtpLocaweb', 'smtp_locaweb' ), 'manage_options', 'smtp_locaweb', array( &$this , 'options_page' ) );
			add_action( "admin_print_scripts-{$this->hook_suffix}" , array( &$this , 'admin_js' ) );
			add_filter( "plugin_action_links_{$this->plugin_basename}" , array( &$this , 'filter_plugin_actions' ) );
			add_action( "admin_footer-{$this->hook_suffix}" , array( &$this , 'admin_footer_js' ) );
		}
	}

	/**
	 * Enqueue javascript required for the admin settings page
	 *
	 * @return none
	 * @since 0.1
	 */
	function admin_js() {
		wp_enqueue_script( 'jquery' );
	}

	/**
	 * Output JS to footer for enhanced admin page functionality
	 *
	 * @since 0.1
	 */
	function admin_footer_js() {
		?>
		<script type="text/javascript">
		/* <![CDATA[ */
			var formModified = false;
			jQuery().ready(function() {
				jQuery('#smtp_locaweb-test').click(function(e) {
					e.preventDefault();
					if ( formModified ) {
						var doTest = confirm('<?php _e( 'The SmtpLocaweb plugin configuration has changed since you last saved. Do you wish to test anyway?\n\nClick "Cancel" and then "Save Changes" if you wish to save your changes.', 'smtp_locaweb'); ?>');
						if ( ! doTest ) {
							return false;
						}
					}
					jQuery(this).val('<?php _e( 'Testing...', 'smtp_locaweb' ); ?>');
					jQuery("#smtp_locaweb-test-result").text('');
					jQuery.get(
						ajaxurl,
						{
							action: 'smtp_locaweb-test',
							_wpnonce: '<?php echo wp_create_nonce(); ?>'
						}
					)
					.complete(function() {
						jQuery("#smtp_locaweb-test").val('<?php _e( 'Test Configuration', 'smtp_locaweb' ); ?>');
					})
					.success(function(data) {
						alert('SmtpLocaweb ' + data.method + ' Test ' + data.message);
					})
					.error(function() {
						alert('SmtpLocaweb Test <?php _e( 'Failure', 'smtp_locaweb' ); ?>');
					});
				});
				jQuery("#smtp_locaweb-form").change(function() {
					formModified = true;
				});
			});
		/* ]]> */
		</script>
		<?php
	}

	/**
	 * Output the options page
	 *
	 * @return none
	 * @since 0.1
	 */
	function options_page() {
		if ( ! @include( 'options-page.php' ) ) {
			printf( __( '<div id="message" class="updated fade"><p>The options page for the <strong>SmtpLocaweb</strong>'.
				'plugin cannot be displayed. The file <strong>%s</strong> is missing.  Please reinstall the plugin.'.
				'</p></div>', 'smtp_locaweb'), dirname( __FILE__ ) . '/options-page.php' );
		}
	}

	/**
	 * Wrapper function hooked into admin_init to register settings
	 * and potentially register an admin notice if the plugin hasn't
	 * been configured yet
	 *
	 * @return none
	 * @since 0.1
	 */
	function admin_init() {
		$this->register_settings();
		$password = $this->get_option( 'password' );
		if ( empty( $password ) ) {
			add_action( 'admin_notices', array( &$this, 'admin_notices' ) );
		}
	}

	/**
	 * Whitelist the smtp_locaweb options
	 *
	 * @since 0.1
	 * @return none
	 */
	function register_settings() {
		register_setting( 'smtp_locaweb', 'smtp_locaweb', array( &$this, 'validation' ) );
	}

	/**
	 * Data validation callback function for options
	 *
	 * @param array $options An array of options posted from the options page
	 * @return array
	 * @since 0.1
	 */
	function validation( $options ) {
		$username = trim( $options['username'] );

		if ( ! empty( $username ) ) {
			$username = preg_replace( '/@.+$/', '', $username );
			$options['username'] = $username;
		}

		foreach ( $options as $key => $value )
			$options[$key] = trim( $value );

		$this->options = $options;
		return $options;
	}

	/**
	 * Function to output an admin notice when the plugin has not
	 * been configured yet
	 *
	 * @return none
	 * @since 0.1
	 */
	function admin_notices() {
		$screen = get_current_screen();
		if ( $screen->id == $this->hook_suffix )
			return;
?>
		<div id='smtp_locaweb-warning' class='updated fade'><p><strong><?php _e( 'SmtpLocaweb is almost ready. ', 'smtp_locaweb' ); ?></strong><?php printf( __( 'You must <a href="%1$s">configure SmtpLocaweb</a> for it to work.', 'smtp_locaweb' ), menu_page_url( 'smtp_locaweb' , false ) ); ?></p></div>
<?php
	}

	/**
	 * Add a settings link to the plugin actions
	 *
	 * @param array $links Array of the plugin action links
	 * @return array
	 * @since 0.1
	 */
	function filter_plugin_actions( $links ) {
		$settings_link = '<a href="' . menu_page_url( 'smtp_locaweb', false ) . '">' . __( 'Settings', 'smtp_locaweb' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * AJAX callback function to test mail sending functionality
	 *
	 * @return string
	 * @since 0.1
	 */
	function ajax_send_test() {
		nocache_headers();
		header( 'Content-Type: application/json' );

		if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_GET[ '_wpnonce' ] ) ) {
			die(
				json_encode(
					array(
						'message' => __( 'Unauthorized', 'smtp_locaweb' ),
						'method' => null
					)
				)
			);
		}

		$secure = ( defined( 'SMTP_LOCAWEB_SECURE' ) && SMTP_LOCAWEB_SECURE ) ? SMTP_LOCAWEB_SECURE : $this->get_option( 'secure' );
		$method = ( (bool) $secure ) ? __( 'Secure SMTP', 'smtp_locaweb' ) : __( 'SMTP', 'smtp_locaweb' );

		$admin_email = get_option( 'admin_email' );
		ob_start();
		$GLOBALS['smtp_debug'] = true;
		$result = wp_mail(
			$admin_email,
			__( 'SmtpLocaweb WordPress Plugin Test', 'smtp_locaweb' ),
			sprintf( __( "This is a test email generated by the SmtpLocaweb WordPress plugin.\n\nIf you have received this message, the requested test has succeeded.\n\nThe method used to send this email was: %s.", 'smtp_locaweb' ), $method )
		);
		$GLOBALS['phpmailer']->smtpClose();
		$output = ob_get_clean();

		if ( $result ) {
			die(
				json_encode(
					array(
						'message' => __( 'Success', 'smtp_locaweb' ),
						'method'  => $method
					)
				)
			);
		} else {
			die(
				json_encode(
					array(
						'message' => __( 'Failure', 'smtp_locaweb' ) .", debug: ".  $output,
						'method'  => $method
					)
				)
			);
		}
	}
}
