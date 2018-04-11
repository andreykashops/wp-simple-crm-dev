<?php
/**
 * Created by Roman Hofman
 * Date: 06.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Meta_Box_Contact_Address Class
 */
class SCRM_Meta_Box_Contact_Address {

    /**
     * Output the metabox
     */
    public static function output( $post ) {

        $scrm_contact = get_post_meta( $post->ID, 'scrm_contact', true );

        $country = !empty( $scrm_contact[ 'country' ] ) ? $scrm_contact[ 'country' ] : '';
        $city = !empty( $scrm_contact[ 'city' ] ) ? $scrm_contact[ 'city' ] : '';


        $street = !empty( $scrm_contact[ 'street' ] ) ? $scrm_contact[ 'street' ] : '';
        $building = !empty( $scrm_contact[ 'building' ] ) ? $scrm_contact[ 'building' ] : '';
        $office = !empty( $scrm_contact[ 'office' ] ) ? $scrm_contact[ 'office' ] : '';
        
        $block = 1;
        ?>

        <div class="<?php $block = scrm_metabox_block( $block ); ?>">
            
            <?php scrm_metabox_field( 'country', $country, 'Country' ); ?>
            
            <?php scrm_metabox_field( 'city', $city, 'City' ); ?>

        </div>

        <div class="<?php $block = scrm_metabox_block( $block ); ?>">
            
            <?php scrm_metabox_field( 'street', $street, 'Street' ); ?>
            
            <?php scrm_metabox_field( 'building', $building, 'Building' ); ?>

            <?php scrm_metabox_field( 'office', $office, 'Office' ); ?>
            
        </div>

        <?php
    }

    /**
     * Save meta box data
     */
    public static function save( $post_id ) {
        
        $scrm_contact = $_POST[ 'scrm_contact' ];

        $scrm_contact[ 'country' ] = sanitize_text_field( $scrm_contact[ 'country' ] );
        $scrm_contact[ 'city' ] = sanitize_text_field( $scrm_contact[ 'city' ] );

        $scrm_contact[ 'street' ] = sanitize_text_field( $scrm_contact[ 'street' ] );
        $scrm_contact[ 'building' ] = sanitize_text_field( $scrm_contact[ 'building' ] );
        $scrm_contact[ 'office' ] = sanitize_text_field( $scrm_contact[ 'office' ] );
        
        update_post_meta( $post_id, 'scrm_contact', wp_parse_args( $scrm_contact, get_post_meta( $post_id, 'scrm_contact', true ) ) );
    }
}
