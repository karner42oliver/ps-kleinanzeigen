<?php
/**
 * Classifieds Custom Fields Definition & Helper Methods
 * 

 * Provides field schema definition and helper methods for field access.
 *
 * @package Classifieds
 * @subpackage Core
 */

if ( ! class_exists( 'Classifieds_Fields' ) ) :
	class Classifieds_Fields {

		/**
		 * Custom fields definition.
		 * Maps friendly field names to their meta storage and display properties.
		 *
		 * @var array
		 */
		private static $custom_fields = array(
			'duration' => array(
				'meta_key'        => '_cf_duration',
				'label'           => 'Duration',
				'type'            => 'select',
				'field_type'      => 'selectbox',
				'description'     => 'Extend the duration of this ad.',
				'options'         => array(
					'1 week'  => '1 Week',
					'2 weeks' => '2 Weeks',
					'3 weeks' => '3 Weeks',
					'4 weeks' => '4 Weeks',
				),
				'default'         => '1 week',
				'required'        => false,
				'sanitize'        => 'sanitize_text_field',
			),
			'cost' => array(
				'meta_key'        => '_cf_cost',
				'label'           => 'Cost',
				'type'            => 'text',
				'field_type'      => 'text',
				'description'     => 'The cost of the item.',
				'options'         => array(),
				'default'         => '',
				'required'        => false,
				'sanitize'        => 'sanitize_text_field',
			),
		);

		/**
		 * Mapping from old CustomPress field IDs to new field keys.
		 * Used during data migration.
		 *
		 * @var array
		 */
		private static $legacy_field_mapping = array(
			'selectbox_4cf582bd61fa4' => 'duration',
			'text_4cfeb3eac6f1f'      => 'cost',
		);

		/**
		 * Get all custom field definitions.
		 *
		 * @return array
		 */
		public static function get_all_fields() {
			return apply_filters( 'classifieds_custom_fields', self::$custom_fields );
		}

		/**
		 * Get a single field definition by field key.
		 *
		 * @param string $field_key The field key (e.g., 'duration', 'cost').
		 * @return array|null Field definition array or null if not found.
		 */
		public static function get_field( $field_key ) {
			$fields = self::get_all_fields();
			return isset( $fields[ $field_key ] ) ? $fields[ $field_key ] : null;
		}

		/**
		 * Get field options for a select/selectbox field.
		 *
		 * @param string $field_key The field key (e.g., 'duration').
		 * @return array Field options array or empty array if not found.
		 */
		public static function get_field_options( $field_key ) {
			$field = self::get_field( $field_key );
			return ( $field && isset( $field['options'] ) ) ? $field['options'] : array();
		}

		/**
		 * Get the meta key for a field.
		 *
		 * @param string $field_key The field key.
		 * @return string|null The meta key or null if not found.
		 */
		public static function get_meta_key( $field_key ) {
			$field = self::get_field( $field_key );
			return ( $field && isset( $field['meta_key'] ) ) ? $field['meta_key'] : null;
		}

		/**
		 * Get a post's custom field value.
		 *
		 * @param int    $post_id The post ID.
		 * @param string $field_key The field key.
		 * @param mixed  $default Default value if not found.
		 * @return mixed
		 */
		public static function get_post_field( $post_id, $field_key, $default = '' ) {
			$meta_key = self::get_meta_key( $field_key );
			if ( ! $meta_key ) {
				return $default;
			}

			$value = get_post_meta( $post_id, $meta_key, true );
			return $value !== '' ? $value : $default;
		}

		/**
		 * Set a post's custom field value.
		 *
		 * @param int    $post_id The post ID.
		 * @param string $field_key The field key.
		 * @param mixed  $value The field value.
		 * @return int|bool Meta ID on success, false on failure.
		 */
		public static function set_post_field( $post_id, $field_key, $value ) {
			$field = self::get_field( $field_key );
			if ( ! $field ) {
				return false;
			}

			$meta_key = $field['meta_key'];

			// Sanitize the value if a sanitize function is defined
			if ( isset( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ) {
				$value = call_user_func( $field['sanitize'], $value );
			}

			return update_post_meta( $post_id, $meta_key, $value );
		}

		/**
		 * Delete a post's custom field value.
		 *
		 * @param int    $post_id The post ID.
		 * @param string $field_key The field key.
		 * @return bool
		 */
		public static function delete_post_field( $post_id, $field_key ) {
			$meta_key = self::get_meta_key( $field_key );
			if ( ! $meta_key ) {
				return false;
			}

			return delete_post_meta( $post_id, $meta_key );
		}

		/**
		 * Save multiple custom fields for a post.
		 *
		 * @param int   $post_id The post ID.
		 * @param array $fields_data Associative array of field_key => value.
		 * @return array Results array with field_key => success boolean.
		 */
		public static function save_post_fields( $post_id, $fields_data = array() ) {
			$results = array();

			foreach ( $fields_data as $field_key => $value ) {
				if ( ! empty( $value ) || $value === '0' ) { // Allow '0' as valid value
					$results[ $field_key ] = self::set_post_field( $post_id, $field_key, $value );
				}
			}

			return $results;
		}

		/**
		 * Get all custom fields for a post.
		 *
		 * @param int $post_id The post ID.
		 * @return array Associative array of field_key => field_value.
		 */
		public static function get_post_fields( $post_id ) {
			$fields = self::get_all_fields();
			$values = array();

			foreach ( array_keys( $fields ) as $field_key ) {
				$values[ $field_key ] = self::get_post_field( $post_id, $field_key );
			}

			return $values;
		}

		/**
		 * Migrate legacy CustomPress field values to new meta keys.
		 * 
		 * Should be called during plugin update/activation.
		 *
		 * @param int $post_id The post ID to migrate.
		 * @return array Migration results.
		 */
		public static function migrate_legacy_fields( $post_id ) {
			$results = array();

			foreach ( self::$legacy_field_mapping as $legacy_field_id => $new_field_key ) {
				// Get old CustomPress meta value
				$legacy_meta_key = '_ct_' . $legacy_field_id;
				$legacy_value = get_post_meta( $post_id, $legacy_meta_key, true );

				if ( $legacy_value ) {
					// Save to new meta key
					$results[ $new_field_key ] = self::set_post_field( $post_id, $new_field_key, $legacy_value );
				}
			}

			return $results;
		}

		/**
		 * Get legacy field ID by new field key.
		 * Inverse lookup of legacy_field_mapping.
		 *
		 * @param string $field_key The new field key.
		 * @return string|null The legacy field ID or null if not found.
		 */
		public static function get_legacy_field_id( $field_key ) {
			return array_search( $field_key, self::$legacy_field_mapping, true ) ?: null;
		}

		/**
		 * Render a field's HTML for admin/frontend display.
		 *
		 * @param string $field_key The field key.
		 * @param mixed  $value The current field value.
		 * @param array  $args Additional rendering arguments.
		 * @return string HTML field markup.
		 */
		public static function render_field( $field_key, $value = '', $args = array() ) {
			$field = self::get_field( $field_key );
			if ( ! $field ) {
				return '';
			}

			$field_type = $field['field_type'] ?? $field['type'];
			$method_name = 'render_' . $field_type;

			if ( method_exists( __CLASS__, $method_name ) ) {
				return self::$method_name( $field, $value, $args );
			}

			// Default: render as text field
			return self::render_text( $field, $value, $args );
		}

		/**
		 * Render a select/selectbox field.
		 *
		 * @param array $field Field definition.
		 * @param mixed $value Current value.
		 * @param array $args Additional arguments.
		 * @return string HTML markup.
		 */
		private static function render_selectbox( $field, $value = '', $args = array() ) {
			$html = '<select name="' . esc_attr( $field['meta_key'] ) . '" id="' . esc_attr( $field['meta_key'] ) . '">';

			// Add empty option if not required
			if ( ! ( $field['required'] ?? false ) ) {
				$html .= '<option value="">' . esc_html__( 'Select...', CF_TEXT_DOMAIN ) . '</option>';
			}

			// Render options
			foreach ( $field['options'] as $option_value => $option_label ) {
				$selected = ( $value === $option_value ) ? ' selected="selected"' : '';
				$html .= '<option value="' . esc_attr( $option_value ) . '"' . $selected . '>' . esc_html( $option_label ) . '</option>';
			}

			$html .= '</select>';
			return $html;
		}

		/**
		 * Render a select field (alias for selectbox).
		 *
		 * @param array $field Field definition.
		 * @param mixed $value Current value.
		 * @param array $args Additional arguments.
		 * @return string HTML markup.
		 */
		private static function render_select( $field, $value = '', $args = array() ) {
			return self::render_selectbox( $field, $value, $args );
		}

		/**
		 * Render a text field.
		 *
		 * @param array $field Field definition.
		 * @param mixed $value Current value.
		 * @param array $args Additional arguments.
		 * @return string HTML markup.
		 */
		private static function render_text( $field, $value = '', $args = array() ) {
			$html = '<input type="text" ';
			$html .= 'name="' . esc_attr( $field['meta_key'] ) . '" ';
			$html .= 'id="' . esc_attr( $field['meta_key'] ) . '" ';
			$html .= 'value="' . esc_attr( $value ) . '" ';
			$html .= 'class="regular-text"';
			$html .= ' />';
			return $html;
		}

		/**
		 * Render a textarea field.
		 *
		 * @param array $field Field definition.
		 * @param mixed $value Current value.
		 * @param array $args Additional arguments.
		 * @return string HTML markup.
		 */
		private static function render_textarea( $field, $value = '', $args = array() ) {
			$html = '<textarea ';
			$html .= 'name="' . esc_attr( $field['meta_key'] ) . '" ';
			$html .= 'id="' . esc_attr( $field['meta_key'] ) . '" ';
			$html .= 'class="large-text" rows="5"';
			$html .= '>' . esc_textarea( $value ) . '</textarea>';
			return $html;
		}
	}
endif;
