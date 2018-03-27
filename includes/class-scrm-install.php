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
     * Default plugin options
     */
    private static function create_options()
    {
    }

    /**
     * Create tables in DB
     */
    private static function create_tables()
    {
    }


    /**
     * Create roles and capabilities.
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
    private static function create_default_data()
    {
    }

    /**
     * Show action links on the plugin screen.
     *
     * @param   mixed $links Plugin Action links.
     * @return  array
     */
    public static function plugin_action_links( $links ) {
        $action_links = array(
            'settings' => '<a href="' . admin_url( 'admin.php?page=scrm-settings' ) . '" aria-label="' . esc_attr__( 'View WP Simple CRM settings', 'scrm' ) . '">' . esc_html__( 'Settings', 'scrm' ) . '</a>',
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