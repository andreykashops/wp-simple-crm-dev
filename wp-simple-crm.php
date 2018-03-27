<?php
/*
Plugin Name: WP Simple CRM
Plugin URI: http://wpsimplecrm.com
Description: A simple CRM and Lead manager for your site.
Version: 1.0.0
Author: Andrey Pavluk
Author URI: http://qcust.com
License: GPL2
*/


// Define SCRM_PLUGIN_FILE.
if ( ! defined( 'SCRM_PLUGIN_FILE' ) ) {
    define( 'SCRM_PLUGIN_FILE', __FILE__ );
}

// Include the main SCRM class.
if ( ! class_exists( 'SCRM' ) ) {
    include_once dirname( __FILE__ ) . '/includes/class-scrm.php';
}

/**
 * Main instance of SCRM.
 *
 * Returns the main instance of SCRM to prevent the need to use globals.
 *
 * @since  1.0
 * @return SCRM
 */
function SCRM() {
    return SCRM::instance();
}

/**
 * Perfect start WP Simple CRM
 */
SCRM();



