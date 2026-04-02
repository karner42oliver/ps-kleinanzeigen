<?php
/**
 * Native WordPress Taxonomies Handler

 * 
 * @package PS_Kleinanzeigen
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PS_Native_Taxonomies {
	
	/**
	 * Initialize taxonomies
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 1 );
	}
	
	/**
	 * Register native WordPress taxonomies
	
	 */
	public static function register_taxonomies() {
		self::register_categories_taxonomy();
		self::register_regions_taxonomy();
	}
	
	/**
	 * Register categories taxonomy
	 */
	private static function register_categories_taxonomy() {
		$args = array(
			'labels'            => array(
				'name'                       => __( 'Kategorien', 'ps-kleinanzeigen' ),
				'singular_name'              => __( 'Kategorie', 'ps-kleinanzeigen' ),
				'menu_name'                  => __( 'Kategorien', 'ps-kleinanzeigen' ),
				'all_items'                  => __( 'Alle Kategorien', 'ps-kleinanzeigen' ),
				'add_new_item'               => __( 'Neue Kategorie', 'ps-kleinanzeigen' ),
				'edit_item'                  => __( 'Kategorie bearbeiten', 'ps-kleinanzeigen' ),
				'new_item_name'              => __( 'Neue Kategorie Name', 'ps-kleinanzeigen' ),
				'search_items'               => __( 'Kategorien durchsuchen', 'ps-kleinanzeigen' ),
				'parent_item'                => __( 'Übergeordnete Kategorie', 'ps-kleinanzeigen' ),
				'parent_item_colon'          => __( 'Übergeordnete Kategorie:', 'ps-kleinanzeigen' ),
				'not_found'                  => __( 'Keine Kategorien gefunden', 'ps-kleinanzeigen' ),
				'popular_items'              => __( 'Beliebte Kategorien', 'ps-kleinanzeigen' ),
				'back_to_items'              => __( 'Zurück zu Kategorien', 'ps-kleinanzeigen' ),
			),
			'hierarchical'      => true,
			'labels_description' => __( 'Kategorien für Kleinanzeigen', 'ps-kleinanzeigen' ),
			'public'            => true,
			'publicly_queryable' => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'kleinanzeigen-kategorie' ),
		);
		
		register_taxonomy( 'kleinenanzeigen-cat', array( 'classifieds' ), $args );
	}
	
	/**
	 * Register regions taxonomy
	 */
	private static function register_regions_taxonomy() {
		$args = array(
			'labels'            => array(
				'name'                       => __( 'Regionen', 'ps-kleinanzeigen' ),
				'singular_name'              => __( 'Region', 'ps-kleinanzeigen' ),
				'menu_name'                  => __( 'Regionen', 'ps-kleinanzeigen' ),
				'all_items'                  => __( 'Alle Regionen', 'ps-kleinanzeigen' ),
				'add_new_item'               => __( 'Neue Region', 'ps-kleinanzeigen' ),
				'edit_item'                  => __( 'Region bearbeiten', 'ps-kleinanzeigen' ),
				'new_item_name'              => __( 'Neue Region Name', 'ps-kleinanzeigen' ),
				'search_items'               => __( 'Regionen durchsuchen', 'ps-kleinanzeigen' ),
				'parent_item'                => __( 'Übergeordnete Region', 'ps-kleinanzeigen' ),
				'parent_item_colon'          => __( 'Übergeordnete Region:', 'ps-kleinanzeigen' ),
				'not_found'                  => __( 'Keine Regionen gefunden', 'ps-kleinanzeigen' ),
				'back_to_items'              => __( 'Zurück zu Regionen', 'ps-kleinanzeigen' ),
			),
			'hierarchical'      => true,
			'labels_description' => __( 'Regionen für Kleinanzeigen', 'ps-kleinanzeigen' ),
			'public'            => true,
			'publicly_queryable' => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'region' ),
		);
		
		register_taxonomy( 'kleinanzeigen-region', array( 'classifieds' ), $args );
	}
	
	/**
	 * Get all registered taxonomies (for migration compatibility)

	 */
	public static function get_taxonomies() {
		return array(
			'kleinenanzeigen-cat' => array(
				'name'        => 'kleinenanzeigen-cat',
				'label'       => __( 'Kategorien', 'ps-kleinanzeigen' ),
				'description' => __( 'Kategorien für Kleinanzeigen', 'ps-kleinanzeigen' ),
				'hierarchical' => true,
			),
			'kleinanzeigen-region' => array(
				'name'        => 'kleinanzeigen-region',
				'label'       => __( 'Regionen', 'ps-kleinanzeigen' ),
				'description' => __( 'Regionen für Kleinanzeigen', 'ps-kleinanzeigen' ),
				'hierarchical' => true,
			),
		);
	}
	
	/**
	 * Check if taxonomy exists

	 */
	public static function taxonomy_exists( $taxonomy ) {
		$taxonomies = self::get_taxonomies();
		return isset( $taxonomies[ $taxonomy ] );
	}
}

// Initialize
PS_Native_Taxonomies::init();
