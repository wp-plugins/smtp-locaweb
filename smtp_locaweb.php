<?php
/**
 * Plugin Name:  SmtpLocaweb
 * Plugin URI:   http://wordpress.org/extend/plugins/smtp_locaweb/
 * Description:  SMTP Locaweb integration for WordPress
 * Version:      0.1.0
 * Author:       Fabio Perrella
 * Author URI:   http://github.com/fabioperrella
 * License:      GPLv2 or later
 * Text Domain:  smtp_locaweb
 * Domain Path:  /languages/
 */

class SmtpLocaweb {

  /**
   * Setup shared functionality for Admin and Front End
   *
   * @return none
   * @since 0.1
   */
  function __construct() {
    $this->options = get_option( 'smtp_locaweb' );
    $this->plugin_file = __FILE__;
    $this->plugin_basename = plugin_basename( $this->plugin_file );

    // Either override the wp_mail function or configure PHPMailer to use the
    // SmtpLocaweb SMTP servers
    add_action( 'phpmailer_init', array( &$this, 'phpmailer_init' ) );
  }

  /**
   * Get specific option from the options table
   *
   * @param string $option Name of option to be used as array key for retrieving the specific value
   * @return mixed
   * @since 0.1
   */
  function get_option( $option, $options = null ) {
    if ( is_null( $options ) )
      $options = &$this->options;
    if ( isset( $options[$option] ) )
      return $options[$option];
    else
      return false;
  }

  /**
   * Hook into phpmailer to override SMTP based configurations
   * to use the SmtpLocaweb SMTP server
   *
   * @param object $phpmailer The PHPMailer object to modify by reference
   * @return none
   * @since 0.1
   */
  function phpmailer_init( &$phpmailer ) {
    $username = ( defined( 'SMTP_LOCAWEB_USERNAME' ) && SMTP_LOCAWEB_USERNAME ) ? SMTP_LOCAWEB_USERNAME : $this->get_option( 'username' );
    $password = ( defined( 'SMTP_LOCAWEB_PASSWORD' ) && SMTP_LOCAWEB_PASSWORD ) ? SMTP_LOCAWEB_PASSWORD : $this->get_option('password');
    $secure = ( defined( 'SMTP_LOCAWEB_SECURE' ) && SMTP_LOCAWEB_SECURE ) ? SMTP_LOCAWEB_SECURE : $this->get_option('secure');
    $from_email = ( defined( 'SMTP_LOCAWEB_FROM_EMAIL' ) && SMTP_LOCAWEB_FROM_EMAIL ) ? SMTP_LOCAWEB_FROM_EMAIL : $this->get_option('from_email');
    $from_name = ( defined( 'SMTP_LOCAWEB_FROM_NAME' ) && SMTP_LOCAWEB_FROM_NAME ) ? SMTP_LOCAWEB_FROM_NAME : $this->get_option('from_name');

    $phpmailer->Mailer = 'smtp';
    $phpmailer->SMTPSecure = (bool) $secure ? 'ssl' : 'none';
    $phpmailer->Host = 'smtplw.com.br';
    $phpmailer->Port = (bool) $secure ? 465 : 587;
    $phpmailer->SMTPAuth = true;
    $phpmailer->Username = $username;
    $phpmailer->Password = $password;
    if($GLOBALS['smtp_debug']){
      $phpmailer->SMTPDebug = 1;
    }
    $phpmailer->SetFrom($from_email, $from_name);
  }

  /**
   * Deactivate this plugin and die
   *
   * Used to deactivate the plugin when files critical to it's operation can not be loaded
   *
   * @since 0.1
   * @return none
   */
  function deactivate_and_die( $file ) {
    load_plugin_textdomain( 'smtp_locaweb', false, 'smtp_locaweb/languages' );
    $message = sprintf( __( "SmtpLocaweb has been automatically deactivated because the file <strong>%s</strong> is missing. Please reinstall the plugin and reactivate." ), $file );
    if ( ! function_exists( 'deactivate_plugins' ) )
      include( ABSPATH . 'wp-admin/includes/plugin.php' );
    deactivate_plugins( __FILE__ );
    wp_die( $message );
  }

}

if ( is_admin() ) {
  if ( @include( dirname( __FILE__ ) . '/includes/admin.php' ) ) {
    new SmtpLocawebAdmin();
  } else {
    SmtpLocaweb::deactivate_and_die( dirname( __FILE__ ) . '/includes/admin.php' );
  }
} else {
  new SmtpLocaweb();
}
