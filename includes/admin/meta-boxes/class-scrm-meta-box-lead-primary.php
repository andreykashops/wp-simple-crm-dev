<?php
/**
 * Created by Roman Hofman
 * Date: 09.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Meta_Box_Lead_Primary Class
 */
class SCRM_Meta_Box_Lead_Primary {

    /**
     * Output the metabox
     */
    public static function output( $post ) {

        $scrm_lead = get_post_meta( $post->ID, 'scrm_lead', true );

        $status = !empty( $scrm_lead[ 'status' ] ) ? $scrm_lead[ 'status' ] : '';
        $source = !empty( $scrm_lead[ 'source' ] ) ? $scrm_lead[ 'source' ] : '';

        $possible_amount = !empty( $scrm_lead[ 'possible-amount' ] ) ? $scrm_lead[ 'possible-amount' ] : '';
        $currency = !empty( $scrm_lead[ 'currency' ] ) ? $scrm_lead[ 'currency' ] : '';

        $responsible = !empty( $scrm_lead[ 'responsible' ] ) ? $scrm_lead[ 'responsible' ] : '';

        // Nonce
        wp_nonce_field( 'scrm_save_data', 'scrm_meta_nonce' );

        $block = 1;
        ?>

        <div class="<?php $block = scrm_metabox_block( $block ); ?>">

            <?php scrm_metabox_field_select( 'status', $status, 'Status' ); ?>

            <?php scrm_metabox_field_select( 'source', $source, 'Source' ); ?>

        </div>

        <div class="<?php $block = scrm_metabox_block( $block ); ?>">

            <?php scrm_metabox_field( 'possible-amount', $possible_amount, 'Possible amount' ); ?>

            <?php scrm_metabox_field_select( 'currency', $currency, 'Currency' ); ?>

        </div>

        <div class="<?php $block = scrm_metabox_block( $block ); ?>">
            
            <?php scrm_metabox_field_select( 'responsible', $responsible, 'Responsible' ); ?>

        </div>

        <?php
    }

    /**
     * Save meta box data
     */
    public static function save( $post_id ) {
        
        $scrm_lead = $_POST[ 'scrm_lead' ];

        $scrm_lead[ 'status' ] = sanitize_text_field( $scrm_lead[ 'status' ] );
        $scrm_lead[ 'source' ] = sanitize_text_field( $scrm_lead[ 'source' ] );

        $scrm_lead[ 'possible-amount' ] = sanitize_text_field( $scrm_lead[ 'possible-amount' ] );
        $scrm_lead[ 'currency' ] = sanitize_text_field( $scrm_lead[ 'currency' ] );
        
        $scrm_lead[ 'responsible' ] = sanitize_text_field( $scrm_lead[ 'responsible' ] );
        
        update_post_meta( $post_id, 'scrm_lead', wp_parse_args( $scrm_lead, get_post_meta( $post_id, 'scrm_lead', true ) ) );
    }

}
