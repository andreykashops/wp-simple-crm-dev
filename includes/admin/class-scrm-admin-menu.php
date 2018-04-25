<?php
/**
 * Created by Andrey Pavluk
 * Date: 24.03.2018
 */

defined('ABSPATH') || exit;

/**
 * Class SCRM_Admin_Menu
 *
 * Register SCRM menu pages
 */
class SCRM_Admin_Menu
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'admin_menu'), 9);
    }


    /**
     * Add menu items.
     */
    public function admin_menu()
    {
        add_menu_page(__('CRM', 'scrm'), __('CRM', 'scrm'), 'scrm_manage', 'scrm', null, 'dashicons-schedule', '55.4');

        $leads_page = add_submenu_page('scrm', __('Leads', 'scrm'), __('Leads', 'scrm'), 'scrm_manage', 'scrm', array($this, 'scrm_leads_page'));

        $settings_page = add_submenu_page('scrm', __('Settings', 'scrm'), __('Settings', 'scrm'), 'scrm_manage', 'scrm_settings', array($this, 'scrm_settings_page'));
        
        add_action( 'load-' . $settings_page, array( $this, 'settings_page_init' ) );
    }

    /**
     * Leads page output
     */
    public function scrm_leads_page(){ 
        SCRM_Admin_Leads_Page::output();
    }

    /**
     * SCRM settings page
     */
    public function scrm_settings_page(){
        SCRM_Admin_Settings_Page::output();
    }

    /**
     * SCRM settings
     */
    public function settings_page_init() {
        
        global $current_page, $current_tab;
        
        SCRM_Admin_Settings_Page::settings_pages();
        
        $current_page = sanitize_title( wp_unslash( $_GET['page'] ) );
        
        $current_tab = empty( $_GET['tab'] ) ? 'general' : sanitize_title( wp_unslash( $_GET['tab'] ) );
        
        SCRM_Admin_Settings_Page::save();
    }
}

new SCRM_Admin_Menu();
