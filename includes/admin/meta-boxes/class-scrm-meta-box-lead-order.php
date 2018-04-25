<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 16.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Meta_Box_Lead_Order Class
 */
class SCRM_Meta_Box_Lead_Order {
    
    /**
     * Type
     */
    public static $type = 'scrm_lead';
    
    /**
     * Output the metabox
     */
    public static function output( $post ) {
        ?>
        <div class="scrm-lead-block">
            <div class="scrm-lead-group">
                <p>
                    Site: Blog
                    <br/>
                    System: WordPress
                    <br/>
                    Price: 50$
                </p>
                <p>
                    Theme: Personal
                    <br/>
                    System: WordPres
                    <br/>
                    Price: 30%
                </p>
                <p>
                    Plugins: Contact Form
                    <br/>
                    System: WordPres
                    <br/>
                    Price: 20$
                </p>
                <p>
                    Content: 10 pages
                    <br/>
                    Format: html
                    <br/>
                    Price: 10$
                </p>
            </div>
            <div class="scrm-lead-group">
                <p>
                    Total: 110$
                </p>
            </div>
        </div>
        <?php
    }

    /**
     * Save meta box data
     */
    public static function save( $post_id ) {
        
        
    }
}
