<?php if (!defined('ABSPATH')) die('No direct access allowed!'); ?>

<?php
/**
* The template for displaying BuddyPress Classifieds component include - custom fields.
* You can override this file in your active theme ( not very advisable if you don't know what you are doing ).
*
* @package Classifieds
* @subpackage UI Front BuddyPress
* @since Classifieds 2.0
*/
?>

<?php
if ( ! isset( $post->ID ) ) {
	$post->ID = 0;
}

$duration_meta_key = '_cf_duration';
$cost_meta_key     = '_cf_cost';

$duration_value = get_post_meta( $post->ID, $duration_meta_key, true );
$cost_value     = get_post_meta( $post->ID, $cost_meta_key, true );

$duration_options = array(
	'1 week'  => '1 Woche',
	'2 weeks' => '2 Wochen',
	'3 weeks' => '3 Wochen',
	'4 weeks' => '4 Wochen',
);
?>

<div class="editfield">
	<label for="<?php echo esc_attr( $duration_meta_key ); ?>"><?php _e( 'Laufzeit', $this->text_domain ); ?></label>
	<select name="<?php echo esc_attr( $duration_meta_key ); ?>" id="<?php echo esc_attr( $duration_meta_key ); ?>">
		<option value=""><?php _e( 'Bitte waehlen', $this->text_domain ); ?></option>
		<?php foreach ( $duration_options as $option_value => $option_label ): ?>
		<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $duration_value, $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
		<?php endforeach; ?>
	</select>
	<p class="description"><?php _e( 'Waehle, wie lange deine Anzeige laufen soll.', $this->text_domain ); ?></p>
</div>

<div class="editfield">
	<label for="<?php echo esc_attr( $cost_meta_key ); ?>"><?php _e( 'Preis', $this->text_domain ); ?></label>
	<input type="text" name="<?php echo esc_attr( $cost_meta_key ); ?>" id="<?php echo esc_attr( $cost_meta_key ); ?>" value="<?php echo esc_attr( $cost_value ); ?>" />
	<p class="description"><?php _e( 'Trag den Preis fuer deine Anzeige ein.', $this->text_domain ); ?></p>
</div>