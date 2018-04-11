<?php
/**
 * Created by Andrey Pavluk
 * Date: 24.03.2018
 */

defined('ABSPATH') || exit;

/**
 * Class SCRM_Admin_Post_Types
 *
 * Handles the edit posts views and some functionality on the edit post screen for SCRM post types.
 */
class SCRM_Admin_Post_Types
{
    /**
     * SCRM_Admin_Post_Types constructor.
     */
    public function __construct()
    {
        include_once( dirname( __FILE__ ) . '/class-scrm-admin-meta-boxes.php' );
        
        // Extra post data and screen elements.
        add_filter('default_hidden_meta_boxes', array($this, 'hidden_meta_boxes'), 10, 2);

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