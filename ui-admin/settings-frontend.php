<?php if (!defined('ABSPATH')) die('No direct access allowed!');

$options = $this->get_options( 'frontend' );
$general_options = $this->get_options( 'general' );

$archive_intro = isset( $options['archive_intro'] ) ? $options['archive_intro'] : '';
$user_intro = isset( $options['user_intro'] ) ? $options['user_intro'] : '';
$trust_block_content = isset( $options['trust_block_content'] ) ? $options['trust_block_content'] : '';
if ( '' === trim( $trust_block_content ) && isset( $general_options['trust_block_content'] ) ) {
	$trust_block_content = $general_options['trust_block_content'];
}
$archive_auto_restore = isset( $options['archive_auto_restore'] ) ? (int) $options['archive_auto_restore'] : 1;
?>

<div class="wrap">

	<?php $this->render_admin( 'navigation', array( 'page' => 'classifieds_settings', 'tab' => 'frontend' ) ); ?>
	<?php $this->render_admin( 'message' ); ?>

	<h1><?php _e( 'Frontend-Einstellungen', $this->text_domain ); ?></h1>

	<form action="#" method="post">
		<div class="postbox">
			<h3 class="hndle"><span><?php _e( 'Archiv', $this->text_domain ); ?></span></h3>
			<div class="inside">
				<p><?php _e( 'Optionen fuer die Uebersichtsseite mit allen Anzeigen.', $this->text_domain ); ?></p>
				<table class="form-table">
					<tr>
						<th><label for="archive_intro"><?php _e( 'Info-Text ueber den Anzeigen', $this->text_domain ); ?></label></th>
						<td>
							<?php
							wp_editor(
								$archive_intro,
								'archive_intro',
								array(
									'textarea_name' => 'archive_intro',
									'media_buttons' => true,
									'teeny'         => false,
									'textarea_rows' => 5,
								)
							);
							?>
						</td>
					</tr>
					<tr>
						<th><label for="archive_auto_restore"><?php _e( 'Letzten Filter automatisch laden', $this->text_domain ); ?></label></th>
						<td>
							<input type="hidden" name="archive_auto_restore" value="0" />
							<label>
								<input type="checkbox" id="archive_auto_restore" name="archive_auto_restore" value="1" <?php checked( 1 === $archive_auto_restore ); ?> />
								<span class="description"><?php _e( 'Wenn aktiv, werden die zuletzt genutzten Filter beim naechsten Aufruf automatisch wiederhergestellt.', $this->text_domain ); ?></span>
							</label>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="postbox">
			<h3 class="hndle"><span><?php _e( 'Single', $this->text_domain ); ?></span></h3>
			<div class="inside">
				<p><?php _e( 'Optionen fuer die Detailseite einer Anzeige.', $this->text_domain ); ?></p>
				<table class="form-table">
					<tr>
						<th><label for="trust_block_content"><?php _e( 'Freier Info-Block', $this->text_domain ); ?></label></th>
						<td>
							<?php
							wp_editor(
								$trust_block_content,
								'trust_block_content',
								array(
									'textarea_name' => 'trust_block_content',
									'media_buttons' => true,
									'teeny'         => false,
									'textarea_rows' => 8,
								)
							);
							?>
							<p class="description"><?php _e( 'Hier kannst Du Text, Bilder, Logo oder Grafiken einfuegen. Der Block wird auf der Anzeigen-Detailseite ausgegeben.', $this->text_domain ); ?></p>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="postbox">
			<h3 class="hndle"><span><?php _e( 'User', $this->text_domain ); ?></span></h3>
			<div class="inside">
				<p><?php _e( 'Optionen fuer den Nutzerbereich (z. B. Meine Anzeigen).', $this->text_domain ); ?></p>
				<table class="form-table">
					<tr>
						<th><label for="user_intro"><?php _e( 'Info-Text im Userbereich', $this->text_domain ); ?></label></th>
						<td>
							<?php
							wp_editor(
								$user_intro,
								'user_intro',
								array(
									'textarea_name' => 'user_intro',
									'media_buttons' => true,
									'teeny'         => false,
									'textarea_rows' => 5,
								)
							);
							?>
							<p class="description"><?php _e( 'Optionaler Einleitungstext fuer den Userbereich.', $this->text_domain ); ?></p>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<p class="submit">
			<?php wp_nonce_field( 'verify' ); ?>
			<input type="hidden" name="key" value="frontend" />
			<input type="submit" class="button-primary" name="save" value="<?php _e( 'Aenderungen speichern', $this->text_domain ); ?>" />
		</p>
	</form>

</div>
