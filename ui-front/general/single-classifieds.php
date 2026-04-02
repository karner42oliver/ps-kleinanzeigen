<?php
/**
* The Template for displaying all single classifieds posts.
* You can override this file in your active theme.
*
* @package Classifieds
* @subpackage UI Front
* @since Classifieds 2.0
*/

global $post, $wp_query;
$options = $this->get_options( 'general' );
$field_image = (empty($options['field_image_def'])) ? $this->plugin_url . 'ui-front/general/images/blank.gif' : $options['field_image_def'];

$duration = get_post_meta( $post->ID, '_cf_duration', true );
$cost     = get_post_meta( $post->ID, '_cf_cost', true );
$cost_display = is_numeric( $cost ) ? number_format_i18n( (float) $cost, 2 ) : $cost;

/**
* $content is already filled with the database html.
* This template just adds classifieds specfic code around it.
*/
?>

<script type="text/javascript" src="<?php echo $this->plugin_url . 'ui-front/js/ui-front.js'; ?>" >
</script>

<?php if ( isset( $_POST['_wpnonce'] ) ): ?>
<br clear="all" />
<div id="cf-message-error">
	<?php _e( "Das Senden der Nachricht ist fehlgeschlagen: Du hast im Kontaktformular nicht alle erforderlichen Felder korrekt ausgefüllt!", $this->text_domain ); ?>
</div>
<br clear="all" />

<?php elseif ( isset( $_GET['sent'] ) && 1 == $_GET['sent'] ): ?>
<br clear="all" />
<div id="cf-message">
	<?php _e( 'Nachricht wird gesendet!', $this->text_domain ); ?>
</div>
<br clear="all" />

<?php elseif ( isset( $_GET['sent'] ) && 0 == $_GET['sent'] ): ?>
<br clear="all" />
<div id="cf-message-error">
	<?php _e( 'Der E-Mail-Dienst antwortet nicht!', $this->text_domain ); ?>
</div>
<br clear="all" />
<?php endif; ?>
<div class="cf-post">

	<div class="cf-image">
		<?php
		if(has_post_thumbnail()){
			$thumbnail = get_the_post_thumbnail( $post->ID, array( 300, 300 ) );
		} else {
			$thumbnail = '<img title="no image" alt="no image" class="cf-no-image wp-post-image" src="' . $field_image . '">';
		}
		?>
		<a href="<?php the_permalink(); ?>" ><?php echo $thumbnail; ?></a>
	</div>
	<div class="clear"></div>
	<div class="cf-ad-info">
		<table>
			<tr>
				<th><?php _e( 'Angeboten von', $this->text_domain ); ?></th>
				<td>
					<?php echo the_author_classifieds_link(); ?>
				</td>
			</tr>
			<tr>
				<th><?php _e( 'Kategorien', $this->text_domain ); ?></th>
				<td>
					<?php $taxonomies = get_object_taxonomies( 'classifieds', 'names' ); ?>
					<?php foreach ( $taxonomies as $taxonomy ): ?>
					<?php echo get_the_term_list( $post->ID, $taxonomy, '', ', ', '' ) . ' '; ?>
					<?php endforeach; ?>
				</td>
			</tr>
			<tr>
				<th><?php _e( 'Kategorisiert in', $this->text_domain ); ?></th>
				<td><?php the_date(); ?></td>
			</tr>
			<tr>
				<th><?php _e( 'Läuft aus am', $this->text_domain ); ?></th>
				<td><?php if ( class_exists('Classifieds_Core') ) echo Classifieds_Core::get_expiration_date( get_the_ID() ); ?></td>
			</tr>
		</table>
		<div class="clear"></div>
		<div class="cf-custom-block">
			<table>
				<?php if ( '' !== $duration ): ?>
				<tr>
					<th><?php _e( 'Laufzeit', $this->text_domain ); ?></th>
					<td><?php echo esc_html( $duration ); ?></td>
				</tr>
				<?php endif; ?>
				<?php if ( '' !== $cost_display ): ?>
				<tr>
					<th><?php _e( 'Preis', $this->text_domain ); ?></th>
					<td><?php echo esc_html( $cost_display ); ?></td>
				</tr>
				<?php endif; ?>
			</table>
		</div>
	</div>
	<div class="clear"></div>

	<?php if( empty( $options['disable_contact_form'] ) ): ?>
	<form method="post" action="#" class="contact-user-btn action-form" id="action-form">
		<input type="submit" name="contact_user" value="<?php _e('Kontaktiere den Anbieter', $this->text_domain ); ?>" onclick="classifieds.toggle_contact_form(); return false;" />
	</form>
	<div class="clear"></div>

	<form method="post" action="#" class="standard-form base cf-contact-form" id="confirm-form">
		<?php
		global $current_user;

		$name   = ( isset( $current_user->display_name ) && '' != $current_user->display_name ) ? $current_user->display_name :
		( ( isset( $current_user->first_name ) && '' != $current_user->first_name ) ? $current_user->first_name : '' );
		$email  = ( isset( $current_user->user_email ) && '' != $current_user->user_email ) ? $current_user->user_email : '';
		?>
		<div class="editfield">
			<label for="name"><?php _e( 'Name', $this->text_domain ); ?> (<?php _e( 'erforderlich', $this->text_domain ); ?>)</label>
			<input type="text" id="name" name ="name" value="<?php echo ( isset( $_POST['name'] ) ) ? $_POST['name'] : $name; ?>" />
			<p class="description"><?php _e( 'Gib Deinen vollständigen Namen ein.', $this->text_domain ); ?></p>
		</div>
		<div class="editfield">
			<label for="email"><?php _e( 'Email', $this->text_domain ); ?> (<?php _e( 'erforderlich', $this->text_domain ); ?>)</label>
			<input type="text" id="email" name ="email" value="<?php echo ( isset( $_POST['email'] ) ) ? $_POST['email'] : $email; ?>" />
			<p class="description"><?php _e( 'Gib hier eine gültige E-Mail-Adresse ein.', $this->text_domain ); ?></p>
		</div>
		<div class="editfield">
			<label for="subject"><?php _e( 'Betreff', $this->text_domain ); ?> (<?php _e( 'erforderlich', $this->text_domain ); ?>)</label>
			<input type="text" id="subject" name ="subject" value="<?php echo ( isset( $_POST['subject'] ) ) ? $_POST['subject'] : ''; ?>" />
			<p class="description"><?php _e( 'Gib den Betreff Deiner Anfrage ein.', $this->text_domain ); ?></p>
		</div>
		<div class="editfield">
			<label for="message"><?php _e( 'Nachricht', $this->text_domain ); ?> (<?php _e( 'erforderlich', $this->text_domain ); ?>)</label>
			<textarea id="message" name="message"><?php echo ( isset( $_POST['message'] ) ) ? $_POST['message'] : ''; ?></textarea>
			<p class="description"><?php _e( 'Gib den Grund Deiner Anfrage ein.', $this->text_domain ); ?></p>
		</div>

		<div class="editfield">
			<label for="cf_random_value"><?php _e( 'Sicherheitsbild', $this->text_domain ); ?> (<?php _e( 'erforderlich', $this->text_domain ); ?>)</label>
			<img class="captcha" src="<?php echo admin_url('admin-ajax.php?action=cf-captcha');?>" />
			<input type="text" id="cf_random_value" name ="cf_random_value" value="" size="8" />
			<p class="description"><?php _e( 'Gib die Zeichen aus dem Bild ein.', $this->text_domain ); ?></p>
		</div>

		<div class="submit">
			<p>
				<?php wp_nonce_field( 'send_message' ); ?>
				<input type="submit" class="button confirm" value="<?php _e( 'Senden', $this->text_domain ); ?>" name="contact_form_send" />
				<input type="submit" class="button cancel"  value="<?php _e( 'Abbrechen', $this->text_domain ); ?>" onclick="classifieds.cancel_contact_form(); return false;" />
			</p>
		</div>
	</form>

	<?php endif; ?>
<?php
//print_r( session_get_cookie_params()  );

?>
	<div class="clear"></div>

	<table class="cf-description">
		<thead>
			<tr>
				<th><?php _e( 'Beschreibung', $this->text_domain ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<?php
					//$content is already filled with the database text. This just add classified specfic code around it.
					echo wp_kses($content, cf_wp_kses_allowed_html());
					?>
				</td>
			</tr>
		</tbody>
	</table>
</div>
