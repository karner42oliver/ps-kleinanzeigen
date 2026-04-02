<?php
/**
 * Classifieds Post Types & Taxonomies Registration
 * 

 * Handles registration of post types and taxonomies.
 *
 * @package Classifieds
 * @subpackage Core
 */

if ( ! class_exists( 'Classifieds_Post_Types' ) ) :
	class Classifieds_Post_Types {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_classifieds_post_type' ), 10 );
			add_action( 'init', array( $this, 'register_classifieds_taxonomies' ), 11 );
		}

		/**
		 * Register Classifieds Custom Post Type.
		 *
		 * @return void
		 */
		public function register_classifieds_post_type() {
			if ( post_type_exists( 'classifieds' ) ) {
				return;
			}

			$labels = array(
				'name'                  => __( 'Classifieds', CF_TEXT_DOMAIN ),
				'singular_name'         => __( 'Classified', CF_TEXT_DOMAIN ),
				'menu_name'             => __( 'Classifieds', CF_TEXT_DOMAIN ),
				'all_items'             => __( 'All Classifieds', CF_TEXT_DOMAIN ),
				'add_new'               => __( 'Add New', CF_TEXT_DOMAIN ),
				'add_new_item'          => __( 'Add New Classified', CF_TEXT_DOMAIN ),
				'edit_item'             => __( 'Edit Classified', CF_TEXT_DOMAIN ),
				'new_item'              => __( 'New Classified', CF_TEXT_DOMAIN ),
				'view_item'             => __( 'View Classified', CF_TEXT_DOMAIN ),
				'search_items'          => __( 'Search Classifieds', CF_TEXT_DOMAIN ),
				'not_found'             => __( 'No Classifieds Found', CF_TEXT_DOMAIN ),
				'not_found_in_trash'    => __( 'No Classifieds Found In Trash', CF_TEXT_DOMAIN ),
				'parent_item_colon'     => __( 'Parent Classified:', CF_TEXT_DOMAIN ),
				'archives'              => __( 'Classifieds Archives', CF_TEXT_DOMAIN ),
				'filter_by_date'        => __( 'Filter by date', CF_TEXT_DOMAIN ),
				'items_list'            => __( 'Classifieds List', CF_TEXT_DOMAIN ),
				'items_list_navigation' => __( 'Classifieds List Navigation', CF_TEXT_DOMAIN ),
			);

			$args = array(
				'labels'              => $labels,
				'description'         => __( 'Classifieds post type.', CF_TEXT_DOMAIN ),
				'public'              => true,
				'publicly_queryable'  => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_rest'        => true,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => array(
					'slug'       => 'classified',
					'with_front' => false,
					'pages'      => true,
				),
				'menu_position'       => 20,
				'has_archive'         => 'classifieds',
				'hierarchical'        => false,
				'supports'            => array(
					'title',
					'editor',
					'author',
					'thumbnail',
					'excerpt',
					'custom-fields',
					'comments',
					'revisions',
				),
				'taxonomies'          => array( 'classifieds_categories', 'classifieds_tags' ),
				'capability_type'     => 'classified',
				'map_meta_cap'        => true,
			);

			register_post_type( 'classifieds', $args );
		}

		/**
		 * Register Classifieds Taxonomies.
		 *
		 * @return void
		 */
		public function register_classifieds_taxonomies() {
			// Register Categories Taxonomy
			$this->register_taxonomy( 'classifieds_categories' );

			// Register Tags Taxonomy
			$this->register_taxonomy( 'classifieds_tags', array(
				'hierarchical' => false,
				'slug'         => 'classified-tag',
			) );
		}

		/**
		 * Helper method to register a taxonomy.
		 *
		 * @param string $taxonomy The taxonomy slug.
		 * @param array  $args Additional arguments.
		 * @return void
		 */
		private function register_taxonomy( $taxonomy, $args = array() ) {
			if ( taxonomy_exists( $taxonomy ) ) {
				// Ensure taxonomy is associated with post type if not already
				if ( ! is_object_in_taxonomy( 'classifieds', $taxonomy ) ) {
					register_taxonomy_for_object_type( $taxonomy, 'classifieds' );
				}
				return;
			}

			$is_hierarchical = isset( $args['hierarchical'] ) ? $args['hierarchical'] : true;
			$slug = isset( $args['slug'] ) ? $args['slug'] : 'classified-category';

			// Use translated singular/plural for labels
			if ( 'classifieds_categories' === $taxonomy ) {
				$singular = __( 'Category', CF_TEXT_DOMAIN );
				$plural = __( 'Categories', CF_TEXT_DOMAIN );
			} else {
				$singular = __( 'Tag', CF_TEXT_DOMAIN );
				$plural = __( 'Tags', CF_TEXT_DOMAIN );
			}

			$labels = array(
				'name'                       => $plural,
				'singular_name'              => $singular,
				'menu_name'                  => $plural,
				'search_items'               => sprintf( __( 'Search %s', CF_TEXT_DOMAIN ), $plural ),
				'all_items'                  => sprintf( __( 'All %s', CF_TEXT_DOMAIN ), $plural ),
				'parent_item'                => sprintf( __( 'Parent %s', CF_TEXT_DOMAIN ), $singular ),
				'parent_item_colon'          => sprintf( __( 'Parent %s:', CF_TEXT_DOMAIN ), $singular ),
				'edit_item'                  => sprintf( __( 'Edit %s', CF_TEXT_DOMAIN ), $singular ),
				'update_item'                => sprintf( __( 'Update %s', CF_TEXT_DOMAIN ), $singular ),
				'add_new_item'               => sprintf( __( 'Add New %s', CF_TEXT_DOMAIN ), $singular ),
				'new_item_name'              => sprintf( __( 'New %s Name', CF_TEXT_DOMAIN ), $singular ),
				'not_found'                  => sprintf( __( 'No %s found.', CF_TEXT_DOMAIN ), $plural ),
				'no_terms'                   => sprintf( __( 'No %s', CF_TEXT_DOMAIN ), $plural ),
				'items_list'                 => sprintf( __( '%s List', CF_TEXT_DOMAIN ), $plural ),
				'items_list_navigation'      => sprintf( __( '%s List Navigation', CF_TEXT_DOMAIN ), $plural ),
				'back_to_items'              => sprintf( __( 'Back to %s', CF_TEXT_DOMAIN ), $plural ),
			);

			$register_args = array(
				'labels'            => $labels,
				'hierarchical'      => $is_hierarchical,
				'public'            => true,
				'publicly_queryable' => true,
				'show_ui'           => true,
				'show_in_menu'      => true,
				'show_in_nav_menus' => true,
				'show_in_rest'      => true,
				'query_var'         => true,
				'rewrite'           => array(
					'slug'       => $slug,
					'with_front' => false,
				),
			);

			register_taxonomy( $taxonomy, 'classifieds', $register_args );
		}
	}
endif;
