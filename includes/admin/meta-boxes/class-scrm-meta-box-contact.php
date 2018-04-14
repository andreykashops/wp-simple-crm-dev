<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 11.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Meta_Box_Contact Class
 */
class SCRM_Meta_Box_Contact {
    
    /**
     * Type
     */
    public static $type = 'scrm_contact';

    /**
     *  Get metabox fields
     */
    public static function fields() {

        $fields = [
            'primary'      => [
                'First name'    => 'first-name',
                'Last name'     => 'last-name',
                'Middle name'   => 'middle-name',
                'Phone'         => 'phone',
                'Email'         => 'email',
                'Birthday'      => 'birthday',
            ],
            'secondary'    => [
                'Site'          => 'site',
                'Company'       => 'company',
                'Position'      => 'position',
            ],
            'social_links' => [
                'Facebook'      => 'facebook',
                'Vkontakte'     => 'vk',
                'Twitter'       => 'twitter',
                'Odnoklasniki'  => 'ok',
            ],
            'address'      => [
                'Country'       => 'country',
                'City'          => 'city',
                'Street'        => 'street',
                'Building'      => 'building',
                'Office'        => 'office',
            ],
        ];

        return $fields;
    }

    /**
     * Router field boxes
     */
    public static function metabox( $prefix, $id, $value, $lable ) {

        switch ( $id ) {
            case 'title':
            case 'first-name':
            case 'phone':
                $type = 'text';
                $data = 'required=""';
                scrm_metabox_field_input( $prefix, $id, $value, $lable, $type, $data );
                break;
            case 'birthday':
                $type = 'date';
                scrm_metabox_field_input( $prefix, $id, $value, $lable, $type );
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
        
        scrm_set_meta_data( $post_id, __CLASS__, $meta );
    }
}
