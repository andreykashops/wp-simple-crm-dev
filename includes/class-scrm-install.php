<?php
/**
 * Installation related functions and actions.
 *
 * Created by Andrey Pavluk
 * Date: 23.03.2018
 */

defined('ABSPATH') || exit;

/**
 * Class SCRM_Install
 * 
 * @package SCRM
 * @subpackage Core
 */
class SCRM_Install
{
    /**
     * Hook in tabs.
     */
    public static function init()
    {
        add_filter('plugin_action_links_' . SCRM_PLUGIN_BASENAME, array(__CLASS__, 'plugin_action_links'));
        add_filter('plugin_row_meta', array(__CLASS__, 'plugin_row_meta'), 10, 2);
    }

    /**
     * Install SCRM.
     */
    public static function install()
    {
        if (!is_blog_installed()) {
            return;
        }

        // Check if we are not already running this routine.
        if ('yes' === get_transient('scrm_installing')) {
            return;
        }

        // If we made it till here nothing is running yet, lets set the transient now.
        set_transient('scrm_installing', 'yes', MINUTE_IN_SECONDS * 10);

        self::create_options();
        self::create_tables();
        self::create_roles();
        self::setup_environment();
        self::create_default_data();

        delete_transient('scrm_installing');

        do_action('scrm_flush_rewrite_rules');
        do_action('scrm_installed');
    }

    /**
     * Deactivate SCRM this function is temporary
     * 
     * @todo in final version move to uninstall function
     */
    public static function deactivation() {
        
        if ( get_transient( 'scrm_installing' ) ) 
            delete_transient( 'scrm_installing' );
        
        $options = get_option( 'scrm_settings_general' );
        
        if ( !isset( $options[ 'remove-all' ] ) || !$options[ 'remove-all' ] ) 
            return false;
        
        $options = [
            'general', 
            'lead', 
            'contact', 
            'order',
            'default_names',
        ];
        
        foreach ( $options as $option ) 
            delete_option( sprintf( 'scrm_settings_%s', $option ) );
        
        $options = [
            'screen_layout_scrm_lead',
            'screen_layout_scrm_contact',
            'edit_scrm_lead_per_page',
            'edit_scrm_contact_per_page',
            'manageedit-scrm_leadcolumnshidden',
            'manageedit-scrm_contactcolumnshidden',
            'meta-box-order_scrm_lead',
            'meta-box-order_scrm_contact',
            'metaboxhidden_scrm_lead',
            'metaboxhidden_scrm_contact',
            'closedpostboxes_scrm_lead',
            'closedpostboxes_scrm_contact',
            'managetoplevel_page_scrmcolumnshidden',
            'scrm-multi-table',
        ];
        
        $users = get_users();
        
        foreach ( $users as $user ) {
            
            foreach ( $options as $option ) 
                delete_user_meta( $user->data->ID, $option );
        }
        
        $types = [
            'scrm_lead',
            'scrm_contact',
        ];
        
        foreach ( $types as $type ) {
            
            $posts = get_posts( [ 'numberposts' => -1, 'post_type' => $type ] );
            wp_reset_postdata();

            foreach ( $posts as $post ) {
                
                $attachment_id = get_post_thumbnail_id( $post->ID );
                wp_delete_post( $attachment_id );
                wp_delete_post( $post->ID );
            }
        }
    }
    
    /**
     * Default plugin options
     */
    private static function create_options() {
        
        // Include settings so that we can run through defaults.
        include_once dirname( __FILE__ ) . '/admin/scrm-admin-functions.php';
        include_once dirname( __FILE__ ) . '/admin/class-scrm-admin-settings-page.php';

        $settings = SCRM_Admin_Settings_Page::settings_pages();
        
        foreach ( $settings as $options ) {
            
            if ( ! method_exists( $options, 'get_settings' ) ) 
                    continue;
            
            $data = [];
            
            foreach ( $options->get_settings() as $option ) {
                    
                switch ( $option[ 'type' ] ) {
                    case 'text':
                    case 'checkbox':
                        $data[ $option[ 'id' ] ] = $option[ 'value' ];
                        break;
                    case 'custom-fields':
                        $data[ $option[ 'id' ] ] = $option[ 'fields' ];
                        break;
                }
            }
            
            add_option( 'scrm_settings_' . $options->id, $data );
        }
    }

    /**
     * Create tables in DB
     */
    private static function create_tables()
    {
        
    }

    /**
     * Create roles and capabilities.
     * 
     * @global object $wp_roles
     */
    public static function create_roles()
    {
        global $wp_roles;

        if (!class_exists('WP_Roles')) {
            return;
        }

        if (!isset($wp_roles)) {
            $wp_roles = new WP_Roles();
        }

        $capabilities = self::get_core_capabilities();

        foreach ($capabilities as $cap_group) {
            foreach ($cap_group as $cap) {
                $wp_roles->add_cap('administrator', $cap);
            }
        }

        /**
         * Global access to SCRM cap
         */
        $wp_roles->add_cap('administrator', 'scrm_manage');
    }

    /**
     * Get capabilities for SCRM - these are assigned to admin during installation or reset.
     *
     * @return array
     */
    private static function get_core_capabilities() {
        $capabilities = array();

        $capability_types = array( 'scrm' );

        foreach ( $capability_types as $capability_type ) {

            $capabilities[ $capability_type ] = array(
                // Post type.
                "edit_{$capability_type}",
                "read_{$capability_type}",
                "delete_{$capability_type}",
                "edit_{$capability_type}s",
                "edit_others_{$capability_type}s",
                "publish_{$capability_type}s",
                "read_private_{$capability_type}s",
                "delete_{$capability_type}s",
                "delete_private_{$capability_type}s",
                "delete_published_{$capability_type}s",
                "delete_others_{$capability_type}s",
                "edit_private_{$capability_type}s",
                "edit_published_{$capability_type}s",

                // Terms.
                "manage_{$capability_type}_terms",
                "edit_{$capability_type}_terms",
                "delete_{$capability_type}_terms",
                "assign_{$capability_type}_terms",
            );
        }

        return $capabilities;
    }

    /**
     * Setup SCRM environment - post types.
     */
    private static function setup_environment()
    {
        SCRM_Post_Types::register_post_types();
    }

    /**
     * Crate default leads and contacts for example.
     */
    private static function create_default_data() {
        
        $tortoise = scrm_attachment( SCRM_ABSPATH . 'assets/images/tortoise.jpg' );
        $stopwatch = scrm_attachment( SCRM_ABSPATH . 'assets/images/stopwatch.jpg' );
        
        $user_id = get_current_user_id();
        
        $args = [
            'lead'    => [
                'title'          => 'Lead #1',
                'status'         => '1%',
                'source'         => 'phone',
                'price'          => '100',
                'currency'       => 'usd',
                'payment'        => 'cash',
                'order'          => 'Simple order list',
                'responsible'    => $user_id,
                'access_for_all' => true,
                'about_status'   => 'First start',
                'about_source'   => 'First phone number',
                'comment'        => 'My first lead with first contact ',
               #'contact_id'     => '',
                'attachment_id'  => $stopwatch,
            ],
            'contact' => [
                'title'         => 'Contact #1',
                'first_name'    => 'World',
                'last_name'     => 'Wide',
                'middle_name'   => 'Web',
                'phone'         => '123-456-789',
                'email'         => 'client@mail.com',
                'birthday'      => '1970-01-01',
                'site'          => 'www.client.site',
                'company'       => '...',
                'position'      => '...',
                'facebook'      => 'http://facebook.com/client',
                'vk'            => 'http://vk.com/client',
                'twitter'       => 'http://twitter.com/client',
                'ok'            => 'http://ok.ru/client',
                'country'       => '...',
                'city'          => '...',
                'street'        => '...',
                'building'      => '...',
                'office'        => '...',
                'attachment_id' => $tortoise,
            ]
        ];

        $ids = scrm_lead_contact( $args );
        
        return $ids;
    }
    
    /**
     * Show action links on the plugin screen.
     *
     * @param   mixed $links Plugin Action links.
     * @return  array
     */
    public static function plugin_action_links( $links ) {
        $action_links = array(
            'settings' => '<a href="' . admin_url( 'admin.php?page=scrm_settings' ) . '" aria-label="' . esc_attr__( 'View WP Simple CRM settings', 'scrm' ) . '">' . esc_html__( 'Settings', 'scrm' ) . '</a>',
        );

        return array_merge( $action_links, $links );
    }

    /**
     * Show row meta on the plugin screen.
     *
     * @param   mixed $links Plugin Row Meta.
     * @param   mixed $file  Plugin Base file.
     * @return  array
     */
    public static function plugin_row_meta( $links, $file ) {
        if ( SCRM_PLUGIN_BASENAME == $file ) {
            $row_meta = array(
                'author'    => '<a href="' . esc_url( apply_filters( 'scrm_author_url', 'https://qcust.com/' ) ) . '" aria-label="' . esc_attr__( 'View WP Simple CRM author', 'scrm' ) . '">' . esc_html__( 'Author', 'scrm' ) . '</a>',
             );

            return array_merge( $links, $row_meta );
        }

        return (array) $links;
    }
}

SCRM_Install::init();