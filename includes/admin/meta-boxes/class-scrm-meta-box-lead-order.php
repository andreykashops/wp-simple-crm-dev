<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 16.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Meta_Box_Lead_Order Class
 * 
 * @package SCRM
 * @subpackage Admin
 * @category Meta Boxes
 */
class SCRM_Meta_Box_Lead_Order {

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
            'primary' => [
                'List' => 'list'
            ],
        ];

        return $fields;
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
            case 'list':
                $other = 'placeholder="' . __( 'Order list', 'scrm' ) . '"';
                scrm_metabox_field_textarea( $prefix, $id, $value, $label, $other );
                break;
        }
    }

    /**
     * Output the metabox
     * 
     * @param object $post 
     */
    public static function output( $post ) {
        
        scrm_metabox_fields_load( $post->ID, __CLASS__, 0 );
    }

    /**
     * Save meta box data
     * 
     * @param int $post_id 
     */
    public static function save( $post_id ) {
        
    }

}
