<?php

/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 11.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Meta_Box_Lead Class
 */
class SCRM_Meta_Box_Lead {
    
    /**
     * Type
     */
    public static $type = 'scrm_lead';

    /**
     *  Get metabox fields
     */
    public static function fields() {

        $fields = [
            'primary'           => [
                'Status'            => 'status',
                'Source'            => 'source',
                'Possible amount'   => 'possible-amount',
                'Currency'          => 'currency',
                'Responsible'       => 'responsible',
                'Access for all'    => 'access-for-all',
            ],
            'secondary'         => [
                'About status'      => 'about-status',
                'About source'      => 'about-source',
                'Comment'           => 'comment',
            ],
            'contact'           => [
                'Contact'           => 'contact',
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
            case 'status':
                $items = [
                    'Not Processed',
                    'Start',
                    'Progress 25%',
                    'Progress 50%',
                    'Progress 75%',
                    'End',
                    'Success',
                    'Failure'
                ];
                break;
            case 'source':
                $items = [
                    'phone',
                    'email',
                    'other'
                ];
                break;
            case 'currency':
                $items = [
                    'EURO',
                    'USD',
                    'UAH',
                    'RUB'
                ];
                break;
            case 'responsible':
                $users = get_users();
                foreach ( $users as $user ) {

                    $items[ $user->data->ID ] = $user->data->display_name;
                }
                wp_reset_postdata();
                break;
            case 'contact':
                $posts = get_posts( [ 'posts_per_page' => -1, 'post_type' => 'scrm_contact' ] );
                $items[ 0 ] = 'New Contact';
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
            case 'status':
            case 'source':
            case 'currency':
                scrm_metabox_field_select( $prefix, $id, $value, $lable, self::values( $id ) );
                break;
            case 'responsible':
                scrm_metabox_field_select( $prefix, $id, $value, $lable, self::values( $id ), false );
                break;
            case 'possible-amount':
                $type = 'number';
                $data = 'min="0" step="1" max="100000"';
                scrm_metabox_field_input( $prefix, $id, $value, $lable, $type, $data );
                break;
            case 'access-for-all':
                $type = 'checkbox';
                scrm_metabox_field_input( $prefix, $id, $value, $lable, $type, '' );
                break;
            case 'comment':
                scrm_metabox_field_textarea( $prefix, $id, $value, $lable );
                break;
            case 'contact':
                scrm_metabox_field_select( $prefix, $id, $value, $lable, self::values( $id ), false );
                break;
            default :
                scrm_metabox_field_input( $prefix, $id, $value, $lable );
                break;
        }
    }

    /**
     * Output the metabox
     */
    public static function output( $post ) {
        
        // Nonce
        wp_nonce_field( 'scrm_save_data', 'scrm_meta_nonce' );
        
        scrm_get_meta_boxes( $post->ID, __CLASS__ );
    }
    
    /**
     * Save meta box data
     */
    public static function save( $post_id ) {
        
        $meta = $_POST[ self::$type ];
        
        $meta[ 'contact' ] = sanitize_key( $meta[ 'contact' ] );
        
        if ( $meta[ 'contact' ] != 0 ) {
            
            scrm_set_meta_data( $post_id, __CLASS__, $meta );
        }
    }
}
