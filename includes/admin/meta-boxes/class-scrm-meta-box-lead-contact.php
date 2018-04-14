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
     * Output the metabox
     */
    public static function output( $post ) {

        $meta = get_post_meta( $post->ID, SCRM_Meta_Box_Lead::$type, true );
        
        $post_id = !empty( $meta[ 'contact' ] ) ? $meta[ 'contact' ] : '0';
        
        scrm_get_meta_boxes( $post_id, 'SCRM_Meta_Box_Contact' );
    }

    /**
     * Save meta box data
     */
    public static function save( $post_id ) {
        
        $lead = $_POST[ SCRM_Meta_Box_Lead::$type ];
            
        $contact = $_POST[ SCRM_Meta_Box_Contact::$type ];
        
        $lead[ 'contact' ] = sanitize_key( $lead[ 'contact' ] );
        
        if ( $lead[ 'contact' ] == 0 ) {
            
            $args = [
                'post_type'     => SCRM_Meta_Box_Contact::$type,
                'post_status'   => 'publish',
                'post_title'    => $contact[ 'title' ],
            ];
            
            $lead[ 'contact' ] = wp_insert_post( $args );
            
            scrm_set_meta_data( $post_id, 'SCRM_Meta_Box_Lead', $lead );
        }

        scrm_set_meta_data( $lead[ 'contact' ], 'SCRM_Meta_Box_Contact', $contact );
    }
}
