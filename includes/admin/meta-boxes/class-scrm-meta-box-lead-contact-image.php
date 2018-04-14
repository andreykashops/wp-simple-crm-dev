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
     * Output the metabox
     */
    public static function output( $post ) {

        $lead = get_post_meta( $post->ID, SCRM_Meta_Box_Lead::$type, true );
        
        $post_id = !empty( $lead[ 'contact' ] ) ? $lead[ 'contact' ] : 0;
        
        scrm_metabox_field_thumbnail( SCRM_Meta_Box_Contact::$type, $post_id );
    }

    /**
     * Save meta box data
     */
    public static function save( $post_id ) {
        
        $lead = get_post_meta( $post_id, SCRM_Meta_Box_Lead::$type, true );
        
        $contact = $_POST[ SCRM_Meta_Box_Contact::$type ];
        
        $contact[ 'thumbnail-id' ] = sanitize_key( $contact[ 'thumbnail-id' ] );
        
        update_post_meta( $lead[ 'contact' ], '_thumbnail_id', $contact[ 'thumbnail-id' ] );
    }
}
