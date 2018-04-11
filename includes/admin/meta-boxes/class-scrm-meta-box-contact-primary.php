<?php
/**
 * Created by Roman Hofman
 * Date: 06.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Meta_Box_Contact_Primary Class
 */
class SCRM_Meta_Box_Contact_Primary {

    /**
     * Output the metabox
     */
    public static function output( $post ) {

        $scrm_contact = get_post_meta( $post->ID, 'scrm_contact', true );

        $first_name = !empty( $scrm_contact[ 'first-name' ] ) ? $scrm_contact[ 'first-name' ] : '';
        $last_name = !empty( $scrm_contact[ 'last-name' ] ) ? $scrm_contact[ 'last-name' ] : '';
        $middle_name = !empty( $scrm_contact[ 'middle-name' ] ) ? $scrm_contact[ 'middle-name' ] : '';

        $phone = !empty( $scrm_contact[ 'phone' ] ) ? $scrm_contact[ 'phone' ] : '';
        $email = !empty( $scrm_contact[ 'email' ] ) ? $scrm_contact[ 'email' ] : '';

        $birthday = !empty( $scrm_contact[ 'birthday' ] ) ? $scrm_contact[ 'birthday' ] : '';

        // Nonce
        wp_nonce_field( 'scrm_save_data', 'scrm_meta_nonce' );

        $block = 1;
        ?>

        <div class="<?php $block = scrm_metabox_block( $block ); ?>">

            <?php scrm_metabox_field( 'first-name', $first_name, 'First name' ); ?>

            <?php scrm_metabox_field( 'last-name', $last_name, 'Last name' ); ?>

            <?php scrm_metabox_field( 'middle-name', $middle_name, 'Middle name' ); ?>

        </div>

        <div class="<?php $block = scrm_metabox_block( $block ); ?>">

            <?php scrm_metabox_field( 'phone', $phone, 'Phone' ); ?>

            <?php scrm_metabox_field( 'email', $email, 'Email' ); ?>
            
            <?php scrm_metabox_field( 'birthday', $birthday, 'Birthday' ); ?>

        </div>

        <?php
    }

    /**
     * Save meta box data
     */
    public static function save( $post_id ) {
        
        $scrm_contact = $_POST[ 'scrm_contact' ];

        $scrm_contact[ 'first-name' ] = sanitize_text_field( $scrm_contact[ 'first-name' ] );
        $scrm_contact[ 'last-name' ] = sanitize_text_field( $scrm_contact[ 'last-name' ] );
        $scrm_contact[ 'middle-name' ] = sanitize_text_field( $scrm_contact[ 'middle-name' ] );

        $scrm_contact[ 'phone' ] = sanitize_text_field( $scrm_contact[ 'phone' ] );
        $scrm_contact[ 'email' ] = sanitize_text_field( $scrm_contact[ 'email' ] );

        $scrm_contact[ 'birthday' ] = sanitize_text_field( $scrm_contact[ 'birthday' ] );
        
        update_post_meta( $post_id, 'scrm_contact', wp_parse_args( $scrm_contact, get_post_meta( $post_id, 'scrm_contact', true ) ) );
    }
}
