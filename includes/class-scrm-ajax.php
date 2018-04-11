<?php
/**
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
            
        $post_id = $_POST[ 'data' ];

        scrm_meta_contact_info( $post_id );
        
        wp_die();
    }
}

SCRM_AJAX::init();
