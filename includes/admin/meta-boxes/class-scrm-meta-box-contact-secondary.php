<?php
/**
 * Created by Roman Hofman
 * Date: 06.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Meta_Box_Contact_Secondary Class
 */
class SCRM_Meta_Box_Contact_Secondary {

    /**
     * Output the metabox
     */
    public static function output( $post ) {

        $scrm_contact = get_post_meta( $post->ID, 'scrm_contact', true );

        $site = !empty( $scrm_contact[ 'site' ] ) ? $scrm_contact[ 'site' ] : '';
        $company = !empty( $scrm_contact[ 'company' ] ) ? $scrm_contact[ 'company' ] : '';
        $position = !empty( $scrm_contact[ 'position' ] ) ? $scrm_contact[ 'position' ] : '';

        // get_option( 'scrm_social_link_list' );
        
        $facebook = !empty( $scrm_contact[ 'facebook' ] ) ? $scrm_contact[ 'facebook' ] : '';
        $vk = !empty( $scrm_contact[ 'vk' ] ) ? $scrm_contact[ 'vk' ] : '';
        $twitter = !empty( $scrm_contact[ 'twitter' ] ) ? $scrm_contact[ 'twitter' ] : '';
        $ok = !empty( $scrm_contact[ 'ok' ] ) ? $scrm_contact[ 'ok' ] : '';

        $block = 1;
        ?>

        <div class="<?php $block = scrm_metabox_block( $block ); ?>">

            <?php scrm_metabox_field( 'site', $site, 'Site' ); ?>

            <?php scrm_metabox_field( 'company', $company, 'Company' ); ?>

            <?php scrm_metabox_field( 'position', $position, 'Position' ); ?>

        </div>

        <div class="<?php $block = scrm_metabox_block( $block ); ?>">
            
            <?php scrm_metabox_field( 'facebook', $facebook, 'Facebook' ); ?>

            <?php scrm_metabox_field( 'vk', $vk, 'Vkontakte' ); ?>

            <?php scrm_metabox_field( 'twitter', $twitter, 'Twitter' ); ?>

            <?php scrm_metabox_field( 'ok', $ok, 'Odnoklasniki' ); ?>

        </div>

        <?php
    }

    /**
     * Save meta box data
     */
    public static function save( $post_id ) {

        $scrm_contact = $_POST[ 'scrm_contact' ];

        $scrm_contact[ 'site' ] = sanitize_text_field( $scrm_contact[ 'site' ] );
        $scrm_contact[ 'company' ] = sanitize_text_field( $scrm_contact[ 'company' ] );
        $scrm_contact[ 'position' ] = sanitize_text_field( $scrm_contact[ 'position' ] );

        $scrm_contact[ 'facebook' ] = sanitize_text_field( $scrm_contact[ 'facebook' ] );
        $scrm_contact[ 'vk' ] = sanitize_text_field( $scrm_contact[ 'vk' ] );
        $scrm_contact[ 'twitter' ] = sanitize_text_field( $scrm_contact[ 'twitter' ] );
        $scrm_contact[ 'ok' ] = sanitize_text_field( $scrm_contact[ 'ok' ] );

        update_post_meta( $post_id, 'scrm_contact', wp_parse_args( $scrm_contact, get_post_meta( $post_id, 'scrm_contact', true ) ) );
    }
}
