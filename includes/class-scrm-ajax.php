<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 10.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM AJAX Class
 */
class SCRM_AJAX {

    /**
     * Ajax init
     */
    public static function init() {

        self::add_ajax_events();
    }

    /**
     * Ajax events
     */
    public static function add_ajax_events() {
        
        $ajax_events = [
            'refresh_contact_info' => false,
            'refresh_contact_image' => false,
        ];
        
        foreach ( $ajax_events as $ajax_event => $nopriv ) {
            
            add_action( 'wp_ajax_scrm_' . $ajax_event, [ __CLASS__, $ajax_event ] );
            
            if ( $nopriv ) {
                
                add_action( 'wp_ajax_nopriv_scrm_' . $ajax_event, [ __CLASS__, $ajax_event ] );
                
                // Front-end ajax
                add_action( 'wp_ajax_' . $ajax_event, [ __CLASS__, $ajax_event ] );
            }
        }
    }
    
    /**
     * Refresh contact info
     */
    public static function refresh_contact_info() {
            
        $post_id = sanitize_key( $_POST[ 'post_id' ] );
        
        scrm_get_meta_boxes( $post_id, 'SCRM_Meta_Box_Contact' );
        
        wp_die();
    }
    
    /**
     * Refresh contact image
     */
    public static function refresh_contact_image() {
        
        $post_id = sanitize_key( $_POST[ 'post_id' ] );
        
        scrm_metabox_field_thumbnail( SCRM_Meta_Box_Contact::$type, $post_id );
        
        wp_die();
    }
}

SCRM_AJAX::init();
