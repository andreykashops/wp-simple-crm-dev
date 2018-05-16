<?php
/**
 * Created by Andrey Pavluk
 * Date: 24.03.2018
 */

defined('ABSPATH') || exit;

class SCRM_Admin
{
    /**
     * SCRM_Admin constructor.
     */
    public function __construct()
    {
        add_action('init', array($this, 'includes'));
    }

    /**
     * Includes admin core
     */
    public function includes()
    {
        include_once( dirname( __FILE__ ) . '/scrm-admin-functions.php' );
        include_once( dirname( __FILE__ ) . '/scrm-meta-box-functions.php' );
        include_once( dirname( __FILE__ ) . '/class-scrm-admin-assets.php' );
        include_once( dirname( __FILE__ ) . '/class-scrm-admin-leads-page.php' );
        include_once( dirname( __FILE__ ) . '/class-scrm-admin-contacts-page.php' );
        include_once( dirname( __FILE__ ) . '/class-scrm-admin-main-page.php' );
        include_once( dirname( __FILE__ ) . '/class-scrm-admin-settings-page.php' );
        include_once( dirname( __FILE__ ) . '/class-scrm-admin-menu.php' );
        include_once( dirname( __FILE__ ) . '/class-scrm-admin-post-types.php' );
    }

}

return new SCRM_Admin();
