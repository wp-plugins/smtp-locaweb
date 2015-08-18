=== SMTP Locaweb for Wordpress ===
Contributors: fabioperrella
Tags: locaweb, smtp, mail, email
Requires at least: 3.3
Tested up to: 4.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily send email from your WordPress site through SMTP Locaweb using SMTP.

== Description ==

SMTP Locaweb is a platform to send emails and notifications, it is simple and easy to use. It has a lot of IPs
with good reputation and it is possible to send emails fast with a good rate of inbox delivery.

Visit http://www.locaweb.com.br/produtos/smtp-locaweb.html for more informations.

This plugin helps to configure wordpress to send emails through SMTP Locaweb.

It is possible to contribute sending a pull request in https://github.com/fabioperrella/wp_smtp_locaweb

This plugin was based on plugin Mailgun for Wordpress.

== Installation ==

1. Upload the `smtp_locaweb` folder to the `/wp-content/plugins/` directory or install directly through the plugin installer
1. Activate the plugin through the 'Plugins' menu in WordPress or by using the link provided by the plugin installer
1. Visit the settings page in the Admin at `Settings -> SmtpLocaweb` and configure the plugin with your account details

== Frequently Asked Questions ==

= Testing the configuration fails when using SMTP =

Your web server may not allow outbound SMTP connections on port 465 for secure connections or 587 for unsecured connections. Try changing `Use Secure SMTP` to "No" or "Yes" depending on your current configuration and testing again.

Your sender email should be authorized in SMTP Locaweb, you can authorize visiting the admin panel in https://smtplw.com.br/panel/settings/emails

= Can this be configured globally for WordPress Multisite? =

Yes, using the following constants that can be placed in wp-config.php:

`
SMTP_LOCAWEB_USERNAME    Type: string
SMTP_LOCAWEB_PASSWORD    Type: string
SMTP_LOCAWEB_SECURE      Type: boolean
SMTP_LOCAWEB_FROM_EMAIL  Type: string
SMTP_LOCAWEB_FROM_NAME   Type: string
`

== Screenshots ==

1. Configuration options for using the SmtpLocaweb SMTP servers

== Upgrade Notice ==

= 1.0 =

Re-release to update versioning to start at 1.0 instead of 0.1

= 0.1 =

Initial Release

== ChangeLog ==

= 0.1 (2015-08-11): =
* Initial Release
