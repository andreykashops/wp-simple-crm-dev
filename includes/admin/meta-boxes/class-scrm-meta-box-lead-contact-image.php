<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 13.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Meta_Box_Lead_Contact_Image Class
 * 
 * @package SCRM
 * @subpackage Admin
 * @category Meta Boxes
 */
class SCRM_Meta_Box_Lead_Contact_Image {
    
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

        $lead_contact_id = get_post_meta( $post->ID, 'contact-id', true );
        
        $contact_id = isset( $lead_contact_id ) ? $lead_contact_id : null;
        
        scrm_metabox_field_thumbnail( SCRM_Meta_Box_Contact::$type, $contact_id );
    }

    /**
     * Save meta box data
     * 
     * @param int $post_id 
     */
    public static function save( $post_id ) {
        
        $lead_contact_id = get_post_meta( $post_id, 'contact-id', true );
        
        $contact = $_POST[ SCRM_Meta_Box_Contact::$type ];
        
        $contact[ 'thumbnail-id' ] = sanitize_key( $contact[ 'thumbnail-id' ] );
        
        update_post_meta( $lead_contact_id, '_thumbnail_id', $contact[ 'thumbnail-id' ] );
    }
}
