<?php
/** 
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 06.04.2018
 */

defined( 'ABSPATH' ) || exit;

/**
 * SCRM Admin Assets Class
 */
class SCRM_Admin_Assets {
    
    /**
     * Init
     */
    public function __construct() {
        
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
    }

    /**
     * Enqueue styles.
     */
    public function admin_styles() {
        
        $screen = get_current_screen();
        $screen_id = $screen ? $screen->id : '';
        
        wp_register_style( 'scrm-admin-meta-boxes', SCRM()->plugin_url() . '/assets/css/scrm-meta-boxes.css' );
        
        if ( in_array( $screen_id, scrm_get_screen_ids() ) ) {
            
            wp_enqueue_style( 'scrm-admin-meta-boxes' );
        }
    }

    /**
     * Enqueue scripts.
     */
    public function admin_scripts() {
        
        $screen = get_current_screen();
        $screen_id = $screen ? $screen->id : '';
        
        wp_register_script( 'scrm-admin-meta-boxes-lead', SCRM()->plugin_url() . '/assets/js/scrm-meta-boxes-lead.js' );
        
        // Meta boxes
        if ( in_array( $screen_id, [ 'scrm_lead' ] ) ) {
            
            wp_enqueue_script( 'scrm-admin-meta-boxes-lead' );
        }
    }
}

return new SCRM_Admin_Assets();
