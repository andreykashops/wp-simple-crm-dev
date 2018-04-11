<?php
/**
 * Created by Roman Hofman
 * Date: 09.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Meta_Box_Lead_Secondary Class
 */
class SCRM_Meta_Box_Lead_Secondary {

    /**
     * Output the metabox
     */
    public static function output( $post ) {

        $scrm_lead = get_post_meta( $post->ID, 'scrm_lead', true );

        $about_status = !empty( $scrm_lead[ 'about-status' ] ) ? $scrm_lead[ 'about-status' ] : '';
        $about_source = !empty( $scrm_lead[ 'about-source' ] ) ? $scrm_lead[ 'about-source' ] : '';
        $access_for_all = !empty( $scrm_lead[ 'access-for-all' ] ) ? $scrm_lead[ 'access-for-all' ] : '';

        $comment = !empty( $scrm_lead[ 'comment' ] ) ? $scrm_lead[ 'comment' ] : '';

        $block = 1;
        ?>

        <div class="<?php $block = scrm_metabox_block( $block ); ?>">

            <?php scrm_metabox_field( 'about-status', $about_status, 'About status' ); ?>

            <?php scrm_metabox_field( 'about-source', $about_source, 'About source' ); ?>

            <?php scrm_metabox_field( 'access-for-all', $access_for_all, 'Access for all' ); ?>

        </div>

        <div class="<?php $block = scrm_metabox_block( $block ); ?>">

            <?php scrm_metabox_field_textarea( 'comment', $comment, 'Comment' ); ?>

        </div>

        <?php
    }

    /**
     * Save meta box data
     */
    public static function save( $post_id ) {
        
        $scrm_lead = $_POST[ 'scrm_lead' ];

        $scrm_lead[ 'about-status' ] = sanitize_text_field( $scrm_lead[ 'about-status' ] );
        $scrm_lead[ 'about-source' ] = sanitize_text_field( $scrm_lead[ 'about-source' ] );
        
        $scrm_lead[ 'access-for-all' ] = isset( $scrm_lead[ 'access-for-all' ] ) ? '1' : '0';
        $scrm_lead[ 'comment' ] = sanitize_textarea_field( $scrm_lead[ 'comment' ] );
        
        update_post_meta( $post_id, 'scrm_lead', wp_parse_args( $scrm_lead, get_post_meta( $post_id, 'scrm_lead', true ) ) );
    }

}
