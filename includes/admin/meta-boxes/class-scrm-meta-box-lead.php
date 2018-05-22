
<?php

/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 11.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Meta_Box_Lead Class
 * 
 * @package SCRM
 * @subpackage Admin
 * @category Meta Boxes
 */
class SCRM_Meta_Box_Lead {
    
    /**
     * Type
     * 
     * @var string 
     */
    public static $type = 'scrm_lead';

    /**
     * Output the metabox
     * 
     * @param object $post 
     */
    public static function output( $post ) {
        
        // Nonce
        wp_nonce_field( 'scrm_save_data', 'scrm_meta_nonce' );
        
        scrm_metabox_custom_fields_load( $post->ID, self::$type );
    }
    
    /**
     * Save meta box data
     * 
     * @param int $post_id 
     */
    public static function save( $post_id ) {
        
        $meta = $_POST[ self::$type ];
        
        if ( $meta[ 'contact-id' ] != 0 ) {
        
            scrm_metabox_custom_fields_save( $post_id, $meta, self::$type );
        }
    }
}
