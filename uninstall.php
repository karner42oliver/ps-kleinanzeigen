<?php
/**
* Uninstall Classifieds plugin
* @package Classifieds
* @version 1.0.0
* @copyright Incsub 2007-2011 {@link http://incsub.com}
* @author Arnold Bailey (Incsub)
* @license GNU General Public License (Version 2 - GPLv2) {@link http://www.gnu.org/licenses/gpl-2.0.html}
*/

// Sicherheitscheck: nur über WordPress-Deinstallation aufrufen
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

global $wpdb;

// Alle Kleinanzeigen-Posts inkl. Meta löschen
$posts = get_posts( [
    'post_type'      => 'classifieds',
    'post_status'    => 'any',
    'numberposts'    => -1,
    'fields'         => 'ids',
] );

foreach ( $posts as $post_id ) {
    wp_delete_post( $post_id, true );
}

// Plugin-Optionen entfernen
$options = [
    'classifieds_settings',
    'classifieds_version',
    'ps_kleinanzeigen_settings',
    'ps_kleinanzeigen_version',
];

foreach ( $options as $option ) {
    delete_option( $option );
    delete_site_option( $option );
}

// Rewrite Rules neu aufbauen
flush_rewrite_rules();
