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
        
        $suffix = ''; // Need min version of styles
        
        wp_register_style( 'scrm-admin-list-tables', SCRM()->plugin_url() . '/assets/css/scrm-list-tables' . $suffix . '.css' );
        wp_register_style( 'scrm-admin-meta-boxes', SCRM()->plugin_url() . '/assets/css/scrm-meta-boxes' . $suffix . '.css' );
        wp_register_style( 'scrm-admin-settings-page', SCRM()->plugin_url() . '/assets/css/scrm-settings-page' . $suffix . '.css' );
        
        switch ( $screen_id ) {
            
            case 'edit-scrm_lead':
            case 'edit-scrm_contact':
                wp_enqueue_style( 'scrm-admin-list-tables' );
                break;
            
            case 'scrm_lead':
            case 'scrm_contact':
                wp_enqueue_style( 'scrm-admin-meta-boxes' );
                break;
            
            case 'crm_page_scrm_settings':
                wp_enqueue_style( 'scrm-admin-settings-page' );
                break;
        }
    }

    /**
     * Enqueue scripts.
     */
    public function admin_scripts() {
        
        $screen = get_current_screen();
        $screen_id = $screen ? $screen->id : '';
        
        $suffix = ''; // Need min version of scripts
        
        wp_register_script( 'scrm-admin-edit-lead', SCRM()->plugin_url() . '/assets/js/scrm-edit-lead' . $suffix . '.js' );
        wp_register_script( 'scrm-admin-lead-page', SCRM()->plugin_url() . '/assets/js/scrm-lead-page' . $suffix . '.js' );
        wp_register_script( 'scrm-admin-settings-page', SCRM()->plugin_url() . '/assets/js/scrm-settings-page' . $suffix . '.js', [ 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'iris' ], SCRM()->version, true );
        
        switch ( $screen_id ) {
            case 'edit-scrm_lead':
                wp_enqueue_script( 'scrm-admin-edit-lead' );
                break;
            case 'scrm_lead':
                wp_enqueue_script( 'scrm-admin-lead-page' );
                break;
            case 'crm_page_scrm_settings':
                wp_enqueue_script( 'scrm-admin-settings-page' );
                break;
        }
    }
}

return new SCRM_Admin_Assets();
