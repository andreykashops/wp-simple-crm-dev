<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 09.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Meta_Box_Lead_Contact Class
 */
class SCRM_Meta_Box_Lead_Contact {
    
    /**
     * Type
     */
    public static $type = 'scrm_lead';

    /**
     *  Get metabox fields
     */
    public static function fields() {

        $fields = [
            'primary'        => [
                'Contact ID'            => 'contact-id',
            ],
        ];

        return $fields;
    }
    
    /**
     * Lead values
     */
    public static function values( $id ) {
        
        $items = '';

        switch ( $id ) {
            case 'contact-id':
                $items = scrm_get_contacts();
                break;
        }
        
        return $items;
    }
    
    /**
     * Router field boxes
     */
    public static function metabox( $prefix, $id, $value, $lable ) {

        switch ( $id ) {
            case 'contact-id':
                scrm_metabox_field_select( $prefix, $id, $value, $lable, self::values( $id ), false );
                break;
        }
    }
    
    /**
     * Output the metabox
     */
    public static function output( $post ) {

        $lead_contact_id = get_post_meta( $post->ID, 'contact-id', true );
        
        $contact_id = isset( $lead_contact_id ) ? $lead_contact_id : null;
        
        scrm_metabox_fields_load( $post->ID, __CLASS__, 0 );
        
        scrm_metabox_custom_fields_load( $contact_id, SCRM_Meta_Box_Contact::$type );
    }

    /**
     * Save meta box data
     */
    public static function save( $post_id ) {
        
        $lead = $_POST[ self::$type ];
        
        $lead[ 'contact-id' ] = sanitize_key( $lead[ 'contact-id' ] );
        
        $contact = $_POST[ SCRM_Meta_Box_Contact::$type ];
        
        if ( $lead[ 'contact-id' ] == 0 ) {
            
            $title = sanitize_text_field( $contact[ 'first-name' ] );
            $title .= !empty( $contact[ 'last-name' ] ) ? ' ' . sanitize_text_field( $contact[ 'last-name' ] ) : '';
            $title .= !empty( $contact[ 'middle-name' ] ) ? ' ' . sanitize_text_field( $contact[ 'middle-name' ] ) : '';
            
            $args = [
                'post_type'     => SCRM_Meta_Box_Contact::$type,
                'post_status'   => 'publish',
                'post_title'    => $title,
            ];
            
            $lead[ 'contact-id' ] = wp_insert_post( $args );
            
            scrm_metabox_custom_fields_save( $post_id, $lead );
        } 
        
        scrm_metabox_custom_fields_save( $lead[ 'contact-id' ], $contact );
    }
}
