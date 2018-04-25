<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 13.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Meta_Box_Lead_Contact_Image Class
 */
class SCRM_Meta_Box_Lead_Contact_Image {
    
    /**
     * Type
     */
    public static $type = 'scrm_lead';
    
    /**
     * Output the metabox
     */
    public static function output( $post ) {

        $lead = get_post_meta( $post->ID, self::$type, true );
        
        $contact_id = !empty( $lead[ 'contact-id' ] ) ? $lead[ 'contact-id' ] : null;
        
        scrm_metabox_field_thumbnail( SCRM_Meta_Box_Contact::$type, $contact_id );
    }

    /**
     * Save meta box data
     */
    public static function save( $post_id ) {
        
        $lead = get_post_meta( $post_id, self::$type, true );
        
        $contact = $_POST[ SCRM_Meta_Box_Contact::$type ];
        
        $contact[ 'thumbnail-id' ] = sanitize_key( $contact[ 'thumbnail-id' ] );
        
        update_post_meta( $lead[ 'contact-id' ], '_thumbnail_id', $contact[ 'thumbnail-id' ] );
    }
}
