<?php if (!defined('ABSPATH')) die('No direct access allowed!'); ?>

<?php $options = $this->get_options('payments'); ?>

<div class="wrap">

	<?php $this->render_admin( 'navigation', array( 'page' => 'classifieds_settings', 'tab' => 'payments' ) ); ?>

	<?php $this->render_admin( 'message' ); ?>

	<h1><?php _e( 'Zahlungs-Einstellungen', $this->text_domain ); ?></h1>

	<form action="#" method="post">
		<div class="postbox">
			<h3 class='hndle'><span><?php _e( 'Wiederkehrende Zahlungen', $this->text_domain ) ?></span></h3>
			<div class="inside">
				<table class="form-table" id="recurring_table">
					<tr id="enable_recurring_tr">
						<th>
							<label for "enable_recurring"><?php _e( 'Wiederkehrende Zahlungen aktivieren', $this->text_domain ) ?></label>
						</th>
						<td>
							<input type="checkbox" id="enable_recurring" name="enable_recurring" value="1" <?php checked( ! empty($options['enable_recurring'] ) ); ?> />
							<label for="enable_recurring"><?php _e('Wiederkehrende Zahlungen nutzen', $this->text_domain) ?></label>
						</td>
					</tr>
					<tr>
						<th>
							<label for="recurring_cost"><?php _e('Kosten des Angebots', $this->text_domain) ?></label>
						</th>
						<td>
							<input type="text" class="small-text" id="recurring_cost" name="recurring_cost" value="<?php echo ( empty( $options['recurring_cost'] ) ) ? '0.00' : $options['recurring_cost']; ?>" />
							<span class="description"><?php _e('Betrag pro Abrechnungszyklus.', $this->text_domain); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="recurring_name"><?php _e('Name des Angebots', $this->text_domain) ?></label>
						</th>
						<td>
							<input type="text" name="recurring_name" id="recurring_name" value="<?php echo ( empty( $options['recurring_name'] ) ) ? '' : $options['recurring_name']; ?>" />
							<span class="description"><?php _e('Bezeichnung des Angebots.', $this->text_domain); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="billing_period"><?php _e('Abrechnungszeitraum', $this->text_domain) ?></label>
						</th>
						<td>
							<select id="billing_period" name="billing_period"  >
								<option value="Day" <?php selected( isset( $options['billing_period'] ) && $options['billing_period'] == 'Day' ); ?>><?php _e( 'Day', $this->text_domain ); ?></option>
								<option value="Week" <?php selected( isset( $options['billing_period'] ) && $options['billing_period'] == 'Week' ); ?>><?php _e( 'Week', $this->text_domain ); ?></option>
<!--
								<option value="SemiMonth" <?php selected( isset( $options['billing_period'] ) && $options['billing_period'] == 'SemiMonth' ); ?>><?php _e( 'Semi Monthly', $this->text_domain ); ?></option>
-->
								<option value="Month" <?php selected( isset( $options['billing_period'] ) && $options['billing_period'] == 'Month' ); ?>><?php _e( 'Month', $this->text_domain ); ?></option>
								<option value="Year" <?php selected( isset( $options['billing_period'] ) && $options['billing_period'] == 'Year' ); ?>><?php _e( 'Year', $this->text_domain ); ?></option>
							</select>
							<span class="description"><?php _e('Zeiteinheit fuer den Abrechnungszyklus.', $this->text_domain); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="billing_frequency"><?php _e('Abrechnungsintervall', $this->text_domain) ?></label>
						</th>
						<td>
							<input type="text" class="small-text" id="billing_frequency" name="billing_frequency" value="<?php echo ( empty( $options['billing_frequency'] ) ) ? '0' : $options['billing_frequency']; ?>" />
							<span class="description"><?php _e('Anzahl der Zeitraeume pro Abrechnungszyklus. Zusammen mit dem Abrechnungszeitraum darf das insgesamt maximal ein Jahr sein. Bei Halbmonat muss das Intervall 1 sein.', $this->text_domain); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="billing_agreement"><?php _e('Abrechnungsvereinbarung', $this->text_domain) ?></label>
						</th>
						<td>
							<input class="cf-full" type="text" name="billing_agreement" id="billing_agreement" value="<?php echo ( empty( $options['billing_agreement'] ) ) ? '' :esc_attr( $options['billing_agreement']); ?>" />
							<br /><span class="description"><?php _e('Beschreibung der Leistung fuer diese Abrechnungsvereinbarung. Am besten kurz und klar, z. B. "$9.99 pro Monat fuer 2 Jahre".', $this->text_domain); ?></span>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="postbox">
			<h3 class='hndle'><span><?php _e( 'Einmalzahlung', $this->text_domain ) ?></span></h3>
			<div class="inside">

				<table class="form-table">
					<tr>
						<th><label for="enable_one_time"><?php _e( 'Einmalzahlung aktivieren', $this->text_domain ); ?></label></th>
						<td>
							<label>
								<input type="checkbox" id="enable_one_time" name="enable_one_time" value="1" <?php checked( ! empty( $options['enable_one_time'] ) );  ?> />
								<?php _e( 'Einmalzahlung fuer das Veroeffentlichen aktivieren.', $this->text_domain ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th>
							<label for="one_time_cost"><?php _e( 'Einmalzahlungs-Option', $this->text_domain ) ?></label>
						</th>
						<td>
							<input type="text" id="one_time_cost" class="small-text" name="one_time_cost" value="<?php echo ( empty( $options['one_time_cost'] ) ) ? '0' : $options['one_time_cost']; ?>" />
							<span class="description"><?php _e( 'Preis fuer die Einmalzahlung.', $this->text_domain ); ?></span>
							<br /><br />
							<input class="cf-full" type="text" name="one_time_txt" value="<?php echo (empty( $options['one_time_txt'] ) ) ? '' : $options['one_time_txt']; ?>" />
							<span class="description"><?php _e( 'Text fuer die Einmalzahlungs-Option.', $this->text_domain ); ?></span>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="postbox">
			<h3 class='hndle'><span><?php _e( 'Credits verwenden', $this->text_domain ) ?></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr>
						<th><label for="enable_credits"><?php _e( 'Credits aktivieren', $this->text_domain ); ?></label></th>
						<td>
							<label>
								<input type="checkbox" id="enable_credits" name="enable_credits" value="1" <?php checked( ! empty( $options['enable_credits'] ) );  ?> />
								<?php _e( 'Credits fuer das Veroeffentlichen aktivieren.', $this->text_domain ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th><label for="cost_credit"><?php _e( 'Kosten pro Credit', $this->text_domain ); ?></label></th>
						<td>
							<input type="text" id="cost_credit" name="cost_credit" value="<?php echo ( empty( $options['cost_credit'] ) ) ? '0' : $options['cost_credit']; ?>" class="small-text" />
							<span class="description"><?php _e( 'Preis pro Credit.', $this->text_domain ); ?></span>
						</td>
					</tr>
					<tr>
						<th><label for="credits_per_week"><?php _e( 'Credits pro Woche', $this->text_domain ); ?></label></th>
						<td>
							<input type="text" id="credits_per_week" name="credits_per_week" value="<?php echo ( empty( $options['credits_per_week'] ) ) ? '0' : $options['credits_per_week']; ?>" class="small-text" />
							<span class="description"><?php _e( 'So viele Credits brauchst du fuer eine Woche Anzeige.', $this->text_domain ); ?></span>
						</td>
					</tr>
					<tr>
						<th><label for="signup_credits"><?php _e( 'Start-Credits', $this->text_domain ); ?></label></th>
						<td>
							<input type="text" id="signup_credits" name="signup_credits" value="<?php echo ( empty( $options['signup_credits'] ) ) ? '0' : $options['signup_credits']; ?>" class="small-text" />
							<span class="description"><?php _e( 'So viele Credits bekommt ein User bei der Registrierung.', $this->text_domain ); ?></span>
						</td>
					</tr>
					<tr>
						<th><label for="description"><?php _e( 'Beschreibung', $this->text_domain ); ?></label></th>
						<td>
							<textarea class="cf-full" id="description" name="description" rows="1" ><?php echo ( empty( $options['description'] ) ) ? '' : sanitize_text_field($options['description']); ?></textarea>
							<br />
							<span class="description"><?php _e( 'Beschreibung von Kosten und Laufzeiten fuer Anzeigen. Wird im Admin-Bereich angezeigt.', $this->text_domain ); ?></span>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="postbox">
			<h3 class='hndle'><span><?php _e( 'AGB-Text', $this->text_domain ) ?></span></h3>
			<div class="inside">

				<table class="form-table">
					<tr>
						<th>
							<label for="tos_txt"><?php _e('AGB-Text', $this->text_domain ) ?></label>
						</th>
						<td>
							<textarea name="tos_txt" id="tos_txt" rows="15" class="cf-full"><?php echo ( empty( $options['tos_txt'] ) ) ? '' : sanitize_text_field($options['tos_txt']); ?></textarea>
							<br />
							<span class="description"><?php _e( 'Text fuer die AGB.', $this->text_domain ); ?></span>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<p class="submit">
			<?php wp_nonce_field('verify'); ?>
			<input type="hidden" name="key" value="payments" />
			<input type="submit" class="button-primary" name="save" value="<?php _e( 'Aenderungen speichern', $this->text_domain ); ?>" />
		</p>

	</form>

</div>