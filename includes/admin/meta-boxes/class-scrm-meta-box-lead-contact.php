<?php
/**
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

        $scrm_lead = get_post_meta( $post->ID, 'scrm_lead', true );

        $contact = !empty( $scrm_lead[ 'contact' ] ) ? $scrm_lead[ 'contact' ] : '';

        $block = 1;
        ?>

        <div class="<?php $block = scrm_metabox_block( $block ); ?>">

            <?php $selected =  scrm_metabox_field_select( 'contact', $contact, 'Contact' ); ?>
            
        </div>
            
        <div class="<?php $block = scrm_metabox_block( $block ); ?>">
            
            <div id="<?php echo $prefix; ?>-contact-info">
            
                <?php                        
                if ( ! $selected ) {
                    
                    $selected = wp_get_recent_posts( [ 'numberposts' => 1, 'post_type' => 'scrm_contact' ] )[0][ 'ID' ];
                    wp_reset_query();
                }
                
                scrm_meta_contact_info( $selected ); 
                ?>
                
            </div>

        </div>

        <?php
    }

    /**
     * Save meta box data
     */
    public static function save( $post_id ) {
        
        $scrm_lead = $_POST[ 'scrm_lead' ];
        
        $scrm_lead[ 'contact' ] = sanitize_text_field( $scrm_lead[ 'contact' ] );
        
        update_post_meta( $post_id, 'scrm_lead', wp_parse_args( $scrm_lead, get_post_meta( $post_id, 'scrm_lead', true ) ) );
    }
}
