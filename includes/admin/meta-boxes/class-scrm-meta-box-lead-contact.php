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
                $posts = get_posts( [ 'posts_per_page' => -1, 'post_type' => 'scrm_contact' ] );
                $items[ 0 ] = __( 'Create New', 'scrm' );
                foreach ( $posts as $post ) {

                    $items[ $post->ID ] = $post->post_title;
                }
                wp_reset_postdata();
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

        $lead = get_post_meta( $post->ID, self::$type, true );
        
        $contact_id = isset( $lead[ 'contact-id' ] ) ? $lead[ 'contact-id' ] : null;
        
        scrm_get_meta_boxes( $post->ID, __CLASS__, 0 );
        
        scrm_get_meta_boxes( $contact_id, 'SCRM_Meta_Box_Contact' );
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
            
            scrm_set_meta_data( $post_id, __CLASS__, $lead );
        } 
        
        scrm_set_meta_data( $lead[ 'contact-id' ], 'SCRM_Meta_Box_Contact', $contact );
    }
}
