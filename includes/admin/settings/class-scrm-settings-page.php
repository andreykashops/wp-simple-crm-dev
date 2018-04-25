<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 16.03.2018
 */

defined('ABSPATH') || exit;

/**
 * SCRM_Settings_Page
 */
abstract class SCRM_Settings_Page {
    
    /**
     * Setting page id
     */
    protected $id = '';

    /**
     * Setting page label
     */
    protected $label = '';

    /**
     * Constructor
     */
    public function __construct() {
        
        add_filter( 'scrm_settings_tabs_array', [ $this, 'add_settings_page' ], 20 );
        add_action( 'scrm_settings_' . $this->id, [ $this, 'output' ] );
        add_action( 'scrm_settings_save_' . $this->id, [ $this, 'save' ] );
    }
    
    /**
     * Add this page to settings
     */
    public function add_settings_page( $pages ) {
        
        $pages[ $this->id ] = $this->label;

        return $pages;
    }

    /**
     * Get settings array.
     *
     * @return array
     */
    public function get_settings() {
        
        return apply_filters( 'scrm_get_settings_' . $this->id, [] );
    }

    /**
     * Output the settings
     */
    public function output() {
        
        $settings = $this->get_settings();

        SCRM_Admin_Settings_Page::load_fields( $settings );
    }

    /**
     * Save settings
     */
    public function save() {
        
        $settings = $this->get_settings();
        
        SCRM_Admin_Settings_Page::save_fields( $settings );
    }
}