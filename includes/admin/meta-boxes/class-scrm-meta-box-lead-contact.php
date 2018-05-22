<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 09.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Meta_Box_Lead_Contact Class
 * 
 * @package SCRM
 * @subpackage Admin
 * @category Meta Boxes
 */
class SCRM_Meta_Box_Lead_Contact {
    
    /**
     * Type
     * 
     * @var string 
     */
    public static $type = 'scrm_lead';

    /**
     *  Get metabox fields
     * 
     * @return array 
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
     * 
     * @param int $id 
     * @return array 
     */
    public static function values( $id ) {
        
        $items = '';

        switch ( $id ) {
            case 'contact-id':
                $items = scrm_list_contacts();
                break;
        }
        
        return $items;
    }
    
    /**
     * Router field boxes
     * 
     * @param string $prefix 
     * @param string $id 
     * @param string|int $value 
     * @param string $label 
     */
    public static function metabox( $prefix, $id, $value, $label ) {

        switch ( $id ) {
            case 'contact-id':
                scrm_metabox_field_select( $prefix, $id, $value, $label, self::values( $id ), false );
                break;
        }
    }
    
    /**
     * Output the metabox
     * 
     * @param object $post 
     */
    public static function output( $post ) {

        $lead_contact_id = get_post_meta( $post->ID, 'contact-id', true );
        
        $contact_id = isset( $lead_contact_id ) ? $lead_contact_id : null;
        
        scrm_metabox_fields_load( $post->ID, __CLASS__, 0 );
        
        scrm_metabox_custom_fields_load( $contact_id, SCRM_Meta_Box_Contact::$type );
    }

    /**
     * Save meta box data
     * 
     * @param int $post_id 
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
            
            scrm_metabox_custom_fields_save( $post_id, $lead, self::$type );
        } 
        
        scrm_metabox_custom_fields_save( $lead[ 'contact-id' ], $contact, SCRM_Meta_Box_Contact::$type );
    }
}
