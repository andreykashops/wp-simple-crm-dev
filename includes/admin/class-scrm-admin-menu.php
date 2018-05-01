<?php

/**
 * Created by Andrey Pavluk
 * Date: 24.03.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * Class SCRM_Admin_Menu
 *
 * Register SCRM menu pages
 */
class SCRM_Admin_Menu {

    /**
     * Admin menu init
     */
    public function __construct() {
        
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 10 ); // If 9 Main first in menu
    }

    /**
     * Add menu items.
     */
    public function admin_menu() {
        
        add_menu_page( __( 'CRM', 'scrm' ), __( 'CRM', 'scrm' ), 'scrm_manage', 'scrm', null, 'dashicons-schedule', '55.4' );

        #$main_page = add_submenu_page( 'scrm', __( 'Main', 'scrm' ), __( 'Main', 'scrm' ), 'scrm_manage', 'scrm', [ $this, 'scrm_main_page' ] );
        #add_action( 'load-' . $main_page, [ $this, 'main_page_init' ] );
        
        #$leads_page = add_submenu_page( 'scrm', __( 'Leads', 'scrm' ), __( 'Leads', 'scrm' ), 'scrm_manage', 'scrm_leads', [ $this, 'scrm_leads_page' ] );
        #add_action( 'load-' . $leads_page, [ $this, 'leads_page_init' ] );

        #$contacts_page = add_submenu_page( 'scrm', __( 'Contacts', 'scrm' ), __( 'Contacts', 'scrm' ), 'scrm_manage', 'scrm_contacts', [ $this, 'scrm_contacts_page' ] );
        #add_action( 'load-' . $contacts_page, [ $this, 'contacts_page_init' ] );

        $settings_page = add_submenu_page( 'scrm', __( 'Settings', 'scrm' ), __( 'Settings', 'scrm' ), 'scrm_manage', 'scrm_settings', [ $this, 'scrm_settings_page' ] );
        add_action( 'load-' . $settings_page, [ $this, 'settings_page_init' ] );
    }

    /**
     * Scrm page init
     */
    public function main_page_init() {
        
        
    }

    /**
     * Scrm page output
     */
    public function scrm_main_page() {

        
    }
    
    /**
     * Leads page init
     */
    public function lead_page_init() {
        
        SCRM_Admin_Leads_Page::init();
    }

    /**
     * Leads page output
     */
    public function scrm_leads_page() {

        SCRM_Admin_Leads_Page::output();
    }

    /**
     * Contacts page init
     */
    public function contacts_page_init() {
        
        SCRM_Admin_Contacts_Page::init();
    }

    /**
     * Contacts page output
     */
    public function scrm_contacts_page() {

        SCRM_Admin_Contacts_Page::output();
    }

    /**
     * SCRM settings
     */
    public function settings_page_init() {

        global $current_page, $current_tab;

        SCRM_Admin_Settings_Page::settings_pages();

        $current_page = sanitize_title( wp_unslash( $_GET[ 'page' ] ) );

        $current_tab = empty( $_GET[ 'tab' ] ) ? 'general' : sanitize_title( wp_unslash( $_GET[ 'tab' ] ) );

        SCRM_Admin_Settings_Page::save();
    }

    /**
     * SCRM settings page
     */
    public function scrm_settings_page() {
        
        SCRM_Admin_Settings_Page::output();
    }
}

new SCRM_Admin_Menu();
