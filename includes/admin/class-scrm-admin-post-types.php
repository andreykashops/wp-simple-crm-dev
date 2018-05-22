<?php
/**
 * Project manager: Andrey Pavluk
 * Update by Roman Hofman
 * Date: 24.03.2018
 */

defined('ABSPATH') || exit;

/**
 * Class SCRM_Admin_Post_Types
 *
 * Handles the edit posts views and some functionality on the edit post screen for SCRM post types.
 * 
 * @package SCRM
 * @subpackage Admin
 */
class SCRM_Admin_Post_Types
{
    /**
     * SCRM_Admin_Post_Types constructor.
     */
    public function __construct()
    {
        include_once( dirname( __FILE__ ) . '/class-scrm-admin-meta-boxes.php' );
        
        // Load correct list table classes for current screen
	add_action( 'current_screen', [ $this, 'setup_screen' ] );
	add_action( 'check_ajax_referer', [ $this, 'setup_screen' ] );
        
        // Extra post data and screen elements.
        add_filter('default_hidden_meta_boxes', array($this, 'hidden_meta_boxes'), 10, 2);

    }
    
    /**
     * Looks at the current screen and loads the correct list table handler
     */
    public function setup_screen() {
        
        $screen_id = '';

        if ( function_exists( 'get_current_screen' ) ) {
            $screen = get_current_screen();
            $screen_id = isset( $screen->id ) ? $screen->id : '';
        }
        
        switch ( $screen_id ) {
            case 'edit-scrm_lead' :
                include_once( 'list-tables/class-scrm-admin-list-table-leads.php' );
                new SCRM_Admin_List_Table_Leads();
                break;
            case 'edit-scrm_contact' :
                include_once( 'list-tables/class-scrm-admin-list-table-contacts.php' );
                new SCRM_Admin_List_Table_Contacts();
                break;
        }

        // Ensure the table handler is only loaded once
        remove_action( 'current_screen', [ $this, 'setup_screen' ] );
        remove_action( 'check_ajax_referer', [ $this, 'setup_screen' ] );
    }

    /**
     * Hidden default Meta-Boxes.
     *
     * @param  array $hidden Hidden boxes.
     * @param  object $screen Current screen.
     * @return array
     */
    public function hidden_meta_boxes($hidden, $screen)
    {
        $post_types = array('scrm_lead', 'scrm_contact');

        if (in_array($screen->post_type, $post_types) && 'post' === $screen->base) {
            $hidden = array_merge($hidden, array('postcustom'));
        }

        return $hidden;
    }

}

new SCRM_Admin_Post_Types();