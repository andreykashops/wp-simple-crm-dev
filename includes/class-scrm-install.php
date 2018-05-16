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
     * Deactivate SCRM this function is temporary
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
        
        $attachment_id = [];
        $attachment_id[ 'tortoise' ] = self::create_attachment( 'tortoise.jpg' );
        $attachment_id[ 'stopwatch' ] = self::create_attachment( 'stopwatch.jpg' );
        
        $user_id = get_current_user_id();
        
        $contact_id = [];
        $contact_id[ 1 ] = self::create_contact( $user_id, 1, '+7 123-456-789', 'client@mail.ru' );
        set_post_thumbnail( $contact_id[ 1 ], $attachment_id[ 'tortoise' ] );
        $contact_id[ 2 ] = self::create_contact( $user_id, 2, '+1 123-456-789', 'client@gmail.com' );
        set_post_thumbnail( $contact_id[ 2 ], $attachment_id[ 'tortoise' ] );
        $contact_id[ 3 ] = self::create_contact( $user_id, 3, '+38 123-456-789', 'client@ukr.net' );
        set_post_thumbnail( $contact_id[ 3 ], $attachment_id[ 'tortoise' ] );
        $contact_id[ 4 ] = self::create_contact( $user_id, 4, '+375 123-456-789', 'client@tut.by' );
        set_post_thumbnail( $contact_id[ 4 ], $attachment_id[ 'tortoise' ] );
        $contact_id[ 5 ] = self::create_contact( $user_id, 5, '+48 123-456-789', 'client@mail.com' );
        set_post_thumbnail( $contact_id[ 5 ], $attachment_id[ 'tortoise' ] );

        $lead_id = self::create_lead( $user_id, 1, '100%', 'rub', $contact_id[ 1 ] );
        set_post_thumbnail( $lead_id, $attachment_id[ 'stopwatch' ] );
        $lead_id = self::create_lead( $user_id, 2, '25%', 'usd', $contact_id[ 2 ] );
        set_post_thumbnail( $lead_id, $attachment_id[ 'stopwatch' ] );
        $lead_id = self::create_lead( $user_id, 3, '1%', 'uah', $contact_id[ 3 ] );
        set_post_thumbnail( $lead_id, $attachment_id[ 'stopwatch' ] );
        $lead_id = self::create_lead( $user_id, 4, '50%', 'rub', $contact_id[ 4 ] );
        set_post_thumbnail( $lead_id, $attachment_id[ 'stopwatch' ] );
        $lead_id = self::create_lead( $user_id, 5, '75%', 'usd', $contact_id[ 2 ] );
        set_post_thumbnail( $lead_id, $attachment_id[ 'stopwatch' ] );
        $lead_id = self::create_lead( $user_id, 6, '0%', 'euro', $contact_id[ 5 ] );
        set_post_thumbnail( $lead_id, $attachment_id[ 'stopwatch' ] );
        $lead_id = self::create_lead( $user_id, 7, 'success', 'rub', $contact_id[ 1 ] );
        set_post_thumbnail( $lead_id, $attachment_id[ 'stopwatch' ] );
        $lead_id = self::create_lead( $user_id, 8, 'failure', 'rub', $contact_id[ 4 ] );
        set_post_thumbnail( $lead_id, $attachment_id[ 'stopwatch' ] );
    }
    
    /**
     * Create attachment
     * 
     * @param string $name
     * @param int $post_id
     * 
     * @return int
     */
    protected static function create_attachment( $name, $post_id = null ) {
        
        $file = SCRM_ABSPATH . 'assets/images/' . $name;
        
        $upload = wp_upload_bits( basename( $file ), null, file_get_contents( $file ) );
            
        if ( !empty( $upload[ 'error' ] ) ) 
            return false;
        
        $file_path = $upload[ 'file' ];
        $file_name = basename( $file_path );
        $file_type = $upload[ 'type' ];

        $wp_upload_dir = wp_upload_dir();

        $attachment = [
            'guid'           => $wp_upload_dir[ 'url' ] . '/' . $file_name,
            'post_mime_type' => $file_type,
            'post_title'     => preg_replace( '/\.[^.]+$/', '', $file_name ),
            'post_contatn'   => '',
            'post_status'    => 'inherit',
        ];

        $attachment_id = wp_insert_attachment( $attachment, $file_path );

        require_once ABSPATH . 'wp-admin/includes/image.php';

        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $file_path );

        wp_update_attachment_metadata( $attachment_id, $attachment_data );
        
        return $attachment_id;
    }

    /**
     * Create contact
     * 
     * @param int $user_id
     * @param int $index
     * @param string $phone
     * @param string $email
     * 
     * @return int
     */
    protected static function create_contact( $user_id, $index, $phone, $email ) {
        
        $contact_data = [
		'post_author' => $user_id,
		'post_title' => 'Имя Фамилия Отчество ' . $index,
		'post_status' => 'publish',
		'post_type' => 'scrm_contact',
		'comment_status' => 'closed',
		'ping_status' => 'closed',
                'meta_input' => [
                    'first-name' => 'Имя' . $index,
                    'last-name' => 'Фамилия' . $index,
                    'middle-name' => 'Отчество' . $index,
                    'phone' => $phone,
                    'email' => $email,
                    'birthday' => '1970-01-01',
                    'site' => 'Сайт' . $index,
                    'company' => 'Компания' . $index,
                    'position' => 'Должность' . $index,
                    'facebook' => 'Фейсбук' . $index,
                    'vk' => 'Вконтакте' . $index,
                    'twitter' => 'Твиттер' . $index,
                    'ok' => 'Однокласники' . $index,
                    'country' => 'Страна' . $index,
                    'city' => 'Город' . $index,
                    'street' => 'Улица' . $index,
                    'building' => 'Здание' . $index,
                    'office' => 'Офис' . $index,
                ],
	];
        
        $contact_id = wp_insert_post( $contact_data );
        
        return $contact_id;
    }
    
    /**
     * Create lead
     * 
     * @param int $user_id
     * @param int $index
     * @param string $status
     * @param string $currency 
     * @param int $contact_id
     * 
     * @return int
     */
    protected static function create_lead( $user_id, $index, $status, $currency, $contact_id ) {
        
        $lead_data = [
            'post_author'    => $user_id,
            'post_title'     => sprintf( 'Лид №%s', $index ),
            'post_status'    => 'publish',
            'post_type'      => 'scrm_lead',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
            'meta_input'     => [
                'status'         => $status,
                'source'         => !empty( random_int( 0, 1 ) ) ? 'phone' : 'email',
                'price'          => random_int( 100, 1000 ),
                'currency'       => $currency,
                'responsible'    => $user_id,
                'access-for-all' => '1',
                'about-status'   => 'Start working',
                'about-source'   => 'Only phone',
                'comment'        => 'No comments',
                'contact-id'     => $contact_id,
            ],
        ];

        $lead_id = wp_insert_post( $lead_data );
        
        return $lead_id;
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