<?php
/**
 * Native WordPress Custom Fields Handler

 * 
 * @package PS_Kleinanzeigen
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PS_Native_Custom_Fields {
	
	/**
	 * Standard custom fields for classifieds
	 */
	public static $fields = array(
		'cf_duration' => array(
			'name'        => 'Duration',
			'label'       => 'Duration (in days)',
			'type'        => 'selectbox',
			'description' => 'How long should the listing be active',
			'options'     => array( 7, 14, 30, 60, 90 ),
		),
		'cf_cost' => array(
			'name'        => 'Cost',
			'label'       => 'Cost (numeric)',
			'type'        => 'text',
			'description' => 'Cost of the listing',
		),
		'cf_category' => array(
			'name'        => 'Category',
			'label'       => 'Category',
			'type'        => 'selectbox',
			'description' => 'Listing category',
		),
		'cf_region' => array(
			'name'        => 'Region',
			'label'       => 'Region',
			'type'        => 'selectbox',
			'description' => 'Listing region',
		),
		'cf_image' => array(
			'name'        => 'Image',
			'label'       => 'Image',
			'type'        => 'file',
			'description' => 'Listing image',
		),
	);
	
	/**
	 * Initialize custom fields
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_meta_fields' ) );
		add_action( 'save_post_classifieds', array( __CLASS__, 'save_custom_fields_on_post_save' ), 10, 2 );
	}
	
	/**
	 * Register meta fields for REST API
	 */
	public static function register_meta_fields() {
		foreach ( self::$fields as $field_key => $field_config ) {
			register_post_meta(
				'classifieds',
				$field_key,
				array(
					'type'           => 'string',
					'single'         => true,
					'show_in_rest'   => true,
					'auth_callback'  => function() {
						return current_user_can( 'edit_posts' );
					},
				)
			);
		}
	}
	
	/**
	 * Get all custom field definitions

	 */
	public static function get_fields( $post_type = 'classifieds' ) {
		if ( 'classifieds' === $post_type ) {
			return self::$fields;
		}
		return array();
	}
	
	/**
	 * Get single field definition
	 */
	public static function get_field( $field_name ) {
		return isset( self::$fields[ $field_name ] ) ? self::$fields[ $field_name ] : null;
	}
	
	/**
	 * Save custom fields for a post

	 * 
	 * @param int   $post_id Post ID
	 * @param array $fields  Key-value pairs: array( 'cf_duration' => 30, 'cf_cost' => 99 )
	 */
	public static function save_fields( $post_id, $fields ) {
		if ( ! $post_id || ! is_array( $fields ) ) {
			return false;
		}
		
		foreach ( $fields as $field_name => $field_value ) {
			if ( self::field_exists( $field_name ) ) {
				if ( empty( $field_value ) ) {
					delete_post_meta( $post_id, $field_name );
				} else {
					update_post_meta( $post_id, $field_name, sanitize_text_field( $field_value ) );
				}
			}
		}
		
		return true;
	}
	
	/**
	 * Get custom fields for a post

	 * 
	 * @param int $post_id Post ID
	 * @return array Custom fields as key-value pairs
	 */
	public static function get_post_fields( $post_id ) {
		if ( ! $post_id ) {
			return array();
		}
		
		$fields = array();
		foreach ( array_keys( self::$fields ) as $field_name ) {
			$value = get_post_meta( $post_id, $field_name, true );
			if ( '' !== $value ) {
				$fields[ $field_name ] = $value;
			}
		}
		
		return $fields;
	}
	
	/**
	 * Get single field value for a post

	 * 
	 * @param int    $post_id Post ID
	 * @param string $field_name Field name (e.g., 'cf_duration')
	 * @return mixed Field value or empty string
	 */
	public static function get_post_field( $post_id, $field_name ) {
		if ( ! $post_id || ! $field_name ) {
			return '';
		}
		
		return get_post_meta( $post_id, $field_name, true );
	}
	
	/**
	 * Check if field exists
	 */
	public static function field_exists( $field_name ) {
		return isset( self::$fields[ $field_name ] );
	}
	
	/**
	 * Hooked on save_post_classifieds to auto-save fields from POST data
	 * Only called if form includes $_POST['custom_fields'] array
	 * 
	 * @param int     $post_id Post ID
	 * @param WP_Post $post    Post object
	 */
	public static function save_custom_fields_on_post_save( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		if ( ! isset( $_POST['custom_fields'] ) || ! is_array( $_POST['custom_fields'] ) ) {
			return;
		}
		
		// Verify nonce if present
		if ( isset( $_POST['ps_kleinanzeigen_nonce'] ) ) {
			if ( ! wp_verify_nonce( $_POST['ps_kleinanzeigen_nonce'], 'ps_kleinanzeigen_action' ) ) {
				return;
			}
		}
		
		// Save fields
		self::save_fields( $post_id, $_POST['custom_fields'] );
	}
	
	/**
	 * Render field HTML for admin/frontend forms
	 * helper method to replace CustomPress field rendering
	 * 
	 * @param string $field_name Field name
	 * @param mixed  $value      Current value
	 * @param int    $post_id    Post ID (optional)
	 * @return string HTML
	 */
	public static function render_field( $field_name, $value = '', $post_id = 0 ) {
		$field = self::get_field( $field_name );
		if ( ! $field ) {
			return '';
		}
		
		$html = '<div class="ps-field ps-field-' . esc_attr( $field_name ) . '">';
		$html .= '<label for="' . esc_attr( $field_name ) . '">' . esc_html( $field['label'] ) . '</label>';
		
		switch ( $field['type'] ) {
			case 'text':
				$html .= '<input type="text" name="custom_fields[' . esc_attr( $field_name ) . ']" id="' . esc_attr( $field_name ) . '" value="' . esc_attr( $value ) . '" />';
				break;
			case 'selectbox':
				$html .= '<select name="custom_fields[' . esc_attr( $field_name ) . ']" id="' . esc_attr( $field_name ) . '">';
				$html .= '<option value="">' . esc_html__( 'Select...', 'ps-kleinanzeigen' ) . '</option>';
				foreach ( $field['options'] as $option ) {
					$html .= '<option value="' . esc_attr( $option ) . '" ' . selected( $value, $option, false ) . '>' . esc_html( $option ) . '</option>';
				}
				$html .= '</select>';
				break;
			case 'file':
				$html .= '<input type="file" name="custom_fields[' . esc_attr( $field_name ) . ']" id="' . esc_attr( $field_name ) . '" />';
				break;
		}
		
		$html .= '</div>';
		return $html;
	}
}

// Initialize
PS_Native_Custom_Fields::init();
