		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br /></div>
			<span class="alignright"><a target="_blank" href="http://www.smtplw.com.br/"><img src="https://2e6874288eee3bf7ca22-d122329f808928cff1e9967578106854.ssl.cf1.rackcdn.com/smtp_locaweb-logo.png" alt="SmtpLocaweb" /></a></span>
			<h2><?php _e( 'SmtpLocaweb' , 'smtp_locaweb' ); ?></h2>
			<p>A <a target="_blank" href="http://www.smtplw.com.br/">SmtpLocaweb</a> account is required to use this plugin and the SmtpLocaweb service.</p>
			<p>If you need to register for an account, you can do so at <a target="_blank" href="http://www.smtplw.com.br/">http://www.smtplw.com.br/</a>.</p>
			<form id="smtp_locaweb-form" action="options.php" method="post">
				<?php settings_fields( 'smtp_locaweb' ); ?>
				<h3><?php _e( 'Configuration' , 'smtp_locaweb' ); ?></h3>
				<table class="form-table">
					<tr valign="top" class="smtp_locaweb-smtp">
						<th scope="row">
							<?php _e( 'Username' , 'smtp_locaweb' ); ?>
						</th>
						<td>
							<input type="text" class="regular-text" name="smtp_locaweb[username]" value="<?php esc_attr_e( $this->get_option( 'username' ) ); ?>" placeholder="postmaster" />
							<p class="description"><?php _e( 'Your SmtpLocaweb SMTP username. Only valid for use with SMTP.', 'smtp_locaweb' ); ?></p>
						</td>
					</tr>
					<tr valign="top" class="smtp_locaweb-smtp">
						<th scope="row">
							<?php _e( 'Password' , 'smtp_locaweb' ); ?>
						</th>
						<td>
							<input type="text" class="regular-text" name="smtp_locaweb[password]" value="<?php esc_attr_e( $this->get_option( 'password' ) ); ?>" placeholder="my-password" />
							<p class="description"><?php _e( 'Your SmtpLocaweb SMTP password that goes with the above username. Only valid for use with SMTP.', 'smtp_locaweb' ); ?></p>
						</td>
					</tr>
					<tr valign="top" class="smtp_locaweb-smtp">
						<th scope="row">
							<?php _e( 'Use Secure SMTP' , 'smtp_locaweb' ); ?>
						</th>
						<td>
							<select name="smtp_locaweb[secure]">
								<option value="1"<?php selected( '1' , $this->get_option( 'secure' ) ); ?>><?php _e( 'Yes' , 'smtp_locaweb' ); ?></option>
								<option value="0"<?php selected( '0' , $this->get_option( 'secure' ) ); ?>><?php _e( 'No' , 'smtp_locaweb' ); ?></option>
							</select>
							<p class="description"><?php _e( 'Set this to "No" if your server cannot establish SSL SMTP connections or if emails are not being delivered. If you set this to "No" your password will be sent in plain text. Only valid for use with SMTP. Default "Yes".', 'smtp_locaweb' ); ?></p>
						</td>
					</tr>
					<tr valign="top" class="smtp_locaweb-smtp">
						<th scope="row">
							<?php _e( 'From e-mail' , 'smtp_locaweb' ); ?>
						</th>
						<td>
							<input type="text" class="regular-text" name="smtp_locaweb[from_email]" value="<?php esc_attr_e( $this->get_option( 'from_email' ) ); ?>" placeholder="postmaster" />
							<p class="description"><?php _e( 'Your SmtpLocaweb SMTP from_email. Only valid for use with SMTP.', 'smtp_locaweb' ); ?></p>
						</td>
					</tr>
					<tr valign="top" class="smtp_locaweb-smtp">
						<th scope="row">
							<?php _e( 'From name' , 'smtp_locaweb' ); ?>
						</th>
						<td>
							<input type="text" class="regular-text" name="smtp_locaweb[from_name]" value="<?php esc_attr_e( $this->get_option( 'from_name' ) ); ?>" placeholder="postmaster" />
							<p class="description"><?php _e( 'Your SmtpLocaweb SMTP from_name. Only valid for use with SMTP.', 'smtp_locaweb' ); ?></p>
						</td>
					</tr>
				</table>
				<p><?php _e( 'Before attempting to test the configuration, please click "Save Changes".', 'smtp_locaweb' ); ?></p>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' , 'smtp_locaweb' ); ?>" />
					<input type="button" id="smtp_locaweb-test" class="button-secondary" value="<?php _e( 'Test Configuration', 'smtp_locaweb' ); ?>" />
				</p>
			</form>
		</div>
