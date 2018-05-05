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
            'refresh_contact_info'                  => false,
            'refresh_contact_image'                 => false,
            'get_custom_field'                      => false,
            'get_custom_field_input'                => false,
            'refresh_custom_field_values'           => false,
            'update_post_meta'                      => false,
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
        
        scrm_metabox_custom_fields_load( $post_id, SCRM_Meta_Box_Contact::$type );
        #scrm_get_meta_boxes( $post_id, 'SCRM_Meta_Box_Contact' );
        
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
    
    /**
     * Get custom field
     */
    public static function get_custom_field() {
        
        $prefix = sanitize_key( $_POST[ 'prefix' ] );
        $id = sanitize_key( $_POST[ 'id' ] );
        $i = sanitize_key( $_POST[ 'i' ] );

        scrm_option_custom_field( $prefix, $id, $i );
        
        wp_die();
    }
    
    /**
     * Refresh custom field values
     */
    public static function refresh_custom_field_values() {
        
        $prefix = sanitize_key( $_POST[ 'prefix' ] );
        $id = sanitize_key( $_POST[ 'id' ] );
        $i = sanitize_key( $_POST[ 'i' ] );
        $field[ 'type' ] = sanitize_key( $_POST[ 'type' ] );
        
        scrm_option_custom_field_values( $prefix, $id, $i, $field );
        
        wp_die();
    }
    
    /**
     * Change lead status
     */
    public static function update_post_meta() {
        
        $data = isset( $_POST[ 'data' ] ) ? $_POST[ 'data' ] : '';
        
        if ( !empty( $data ) ) {
        
            $post_id = sanitize_key( $data[ 'post_id' ] );
            $status = sanitize_text_field( $data[ 'status' ] );
            
            update_post_meta( $post_id, 'status', $status );

            wp_die(1);
        } else {
            
            wp_die(0);
        }
    } 
}

SCRM_AJAX::init();
