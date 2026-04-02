<?php
/**
 * Native WordPress Post Types Handler

 * 
 * @package PS_Kleinanzeigen
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PS_Native_Post_Types {
	
	/**
	 * Initialize post types
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 0 );
	}
	
	/**
	 * Register native WordPress post types
	
	 */
	public static function register_post_types() {
		// Register 'classifieds' post type
		self::register_classifieds_post_type();
	}
	
	/**
	 * Register classifieds post type
	 */
	private static function register_classifieds_post_type() {
		$args = array(
			'labels'              => array(
				'name'               => __( 'Kleinanzeigen', 'ps-kleinanzeigen' ),
				'singular_name'      => __( 'Kleinanzeige', 'ps-kleinanzeigen' ),
				'menu_name'          => __( 'Kleinanzeigen', 'ps-kleinanzeigen' ),
				'all_items'          => __( 'Alle Kleinanzeigen', 'ps-kleinanzeigen' ),
				'add_new'            => __( 'Neue Kleinanzeige', 'ps-kleinanzeigen' ),
				'add_new_item'       => __( 'Neue Kleinanzeige erstellen', 'ps-kleinanzeigen' ),
				'edit_item'          => __( 'Kleinanzeige bearbeiten', 'ps-kleinanzeigen' ),
				'new_item'           => __( 'Neue Kleinanzeige', 'ps-kleinanzeigen' ),
				'view_item'          => __( 'Kleinanzeige anzeigen', 'ps-kleinanzeigen' ),
				'search_items'       => __( 'Kleinanzeigen durchsuchen', 'ps-kleinanzeigen' ),
				'not_found'          => __( 'Keine Kleinanzeigen gefunden', 'ps-kleinanzeigen' ),
				'not_found_in_trash' => __( 'Keine Kleinanzeigen im Papierkorb', 'ps-kleinanzeigen' ),
			),
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'rewrite'             => array( 'slug' => 'kleinanzeige' ),
			'capability_type'     => 'post',
			'has_archive'         => true,
			'hierarchical'        => false,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-admin-post',
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'comments' ),
			'show_in_rest'        => true,
		);
		
		register_post_type( 'classifieds', $args );
	}
	
	/**
	 * Get all registered post types (for migration compatibility)

	 */
	public static function get_post_types() {
		return array(
			'classifieds' => array(
				'name'        => 'classifieds',
				'label'       => __( 'Kleinanzeigen', 'ps-kleinanzeigen' ),
				'description' => __( 'Kleinanzeigen Listings', 'ps-kleinanzeigen' ),
			),
		);
	}
	
	/**
	 * Check if post type exists

	 */
	public static function post_type_exists( $post_type ) {
		$types = self::get_post_types();
		return isset( $types[ $post_type ] );
	}
}

// Initialize
PS_Native_Post_Types::init();
