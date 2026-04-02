<?php

/**
* The template for displaying the custom fields.
* You can override this file in your active theme.
*
* @package Classifieds
* @subpackage UI Front
* @since Classifieds 2.0
*/

global $post;

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
	<label for="<?php echo esc_attr( $duration_meta_key ); ?>"><?php _e( 'Laufzeit', CF_TEXT_DOMAIN ); ?></label>
	<select name="<?php echo esc_attr( $duration_meta_key ); ?>" id="<?php echo esc_attr( $duration_meta_key ); ?>">
		<option value=""><?php _e( 'Bitte waehlen', CF_TEXT_DOMAIN ); ?></option>
		<?php foreach ( $duration_options as $option_value => $option_label ): ?>
		<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $duration_value, $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
		<?php endforeach; ?>
	</select>
	<p class="description"><?php _e( 'Waehle, wie lange deine Anzeige laufen soll.', CF_TEXT_DOMAIN ); ?></p>
</div>

<div class="editfield">
	<label for="<?php echo esc_attr( $cost_meta_key ); ?>"><?php _e( 'Preis', CF_TEXT_DOMAIN ); ?></label>
	<input type="text" name="<?php echo esc_attr( $cost_meta_key ); ?>" id="<?php echo esc_attr( $cost_meta_key ); ?>" value="<?php echo esc_attr( $cost_value ); ?>" />
	<p class="description"><?php _e( 'Trag den Preis fuer deine Anzeige ein.', CF_TEXT_DOMAIN ); ?></p>
</div>