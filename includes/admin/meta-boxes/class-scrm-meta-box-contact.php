<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 11.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Meta_Box_Contact Class
 */
class SCRM_Meta_Box_Contact {
    
    /**
     * Type
     */
    public static $type = 'scrm_contact';

    /**
     * Output the metabox
     */
    public static function output( $post ) {
        
        // Nonce
        wp_nonce_field( 'scrm_save_data', 'scrm_meta_nonce' );
        
        scrm_metabox_custom_fields_load( $post->ID, self::$type );
    }
    
    /**
     * Save meta box data
     */
    public static function save( $post_id ) {
        
        $meta = $_POST[ self::$type ];
        
        scrm_metabox_custom_fields_save( $post_id, $meta );
    }
}
