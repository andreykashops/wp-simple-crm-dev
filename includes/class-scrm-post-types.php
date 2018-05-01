<?php
/**
 * Created by Andrey Pavluk
 * Date: 22.03.2018
 */

defined('ABSPATH') || exit;

class SCRM_Post_Types
{

    public static function init()
    {
        add_action('init', array(__CLASS__, 'register_post_types'), 5);
    }


    /**
     * Register core post types.
     */
    public static function register_post_types()
    {
        if (!is_blog_installed()) {
            return;
        }

        do_action('scrm_register_post_type');

        $supports = array('title', 'thumbnail', 'custom-fields');

        register_post_type('scrm_lead',
            apply_filters('scrm_register_post_type_lead',
                array(
                    'labels' => array(
                        'name' => __('Leads', 'scrm'),
                        'singular_name' => __('Lead', 'scrm'),
                        'all_items' => __('Leads', 'scrm'),
                        'menu_name' => _x('Leads', 'Admin menu name', 'scrm'),
                        'add_new' => __('Add New', 'scrm'),
                        'add_new_item' => __('Add new lead', 'scrm'),
                        'edit' => __('Edit', 'scrm'),
                        'edit_item' => __('Edit lead', 'scrm'),
                        'new_item' => __('New lead', 'scrm'),
                        'view_item' => __('View lead', 'scrm'),
                        'view_items' => __('View leads', 'scrm'),
                        'search_items' => __('Search leads', 'scrm'),
                        'not_found' => __('No leads found', 'scrm'),
                        'not_found_in_trash' => __('No leads found in trash', 'scrm'),
                        'parent' => __('Parent lead', 'scrm'),
                        'featured_image' => __('Lead image', 'scrm'),
                        'set_featured_image' => __('Set lead image', 'scrm'),
                        'remove_featured_image' => __('Remove lead image', 'scrm'),
                        'use_featured_image' => __('Use as lead image', 'scrm'),
                        'insert_into_item' => __('Insert into lead', 'scrm'),
                        'uploaded_to_this_item' => __('Uploaded to this lead', 'scrm'),
                        'filter_items_list' => __('Filter leads', 'scrm'),
                        'items_list_navigation' => __('Leads navigation', 'scrm'),
                        'items_list' => __('Leads list', 'scrm'),
                    ),
                    'description' => __('This is where you can add new lead to your crm.', 'scrm'),
                    'public'              => true,
                    'show_ui'             => true,
                    'capability_type'     => 'scrm',
                    'map_meta_cap'        => true,
                    'publicly_queryable'  => true,
                    'exclude_from_search' => false,
                    'hierarchical'        => false, // Hierarchical causes memory issues - WP loads all records!
                    'rewrite'             => false,
                    'query_var'           => true,
                    'supports'            => $supports,
                    'has_archive'         => false,
                    'show_in_menu'        => current_user_can( 'scrm_manage' ) ? 'scrm' : true,
                    'show_in_nav_menus'   => true,
                    'show_in_rest'        => true,
                )
            )
        );

        register_post_type('scrm_contact',
            apply_filters('scrm_register_post_type_contact',
                array(
                    'labels' => array(
                        'name' => __('Contacts', 'scrm'),
                        'singular_name' => __('Contact', 'scrm'),
                        'all_items' => __('Contacts', 'scrm'),
                        'menu_name' => _x('Contacts', 'Admin menu name', 'scrm'),
                        'add_new' => __('Add New', 'scrm'),
                        'add_new_item' => __('Add new contact', 'scrm'),
                        'edit' => __('Edit', 'scrm'),
                        'edit_item' => __('Edit contact', 'scrm'),
                        'new_item' => __('New contact', 'scrm'),
                        'view_item' => __('View contact', 'scrm'),
                        'view_items' => __('View contacts', 'scrm'),
                        'search_items' => __('Search contacts', 'scrm'),
                        'not_found' => __('No contacts found', 'scrm'),
                        'not_found_in_trash' => __('No contacts found in trash', 'scrm'),
                        'parent' => __('Parent contact', 'scrm'),
                        'featured_image' => __('Contact image', 'scrm'),
                        'set_featured_image' => __('Set contact image', 'scrm'),
                        'remove_featured_image' => __('Remove contact image', 'scrm'),
                        'use_featured_image' => __('Use as contact image', 'scrm'),
                        'insert_into_item' => __('Insert into contact', 'scrm'),
                        'uploaded_to_this_item' => __('Uploaded to this contact', 'scrm'),
                        'filter_items_list' => __('Filter contacts', 'scrm'),
                        'items_list_navigation' => __('Contacts navigation', 'scrm'),
                        'items_list' => __('Contacts list', 'scrm'),
                    ),
                    'description' => __('This is where you can add new contact to your crm.', 'scrm'),
                    'public' => true,
                    'show_ui' => true,
                    'capability_type' => 'scrm',
                    'map_meta_cap' => true,
                    'publicly_queryable' => false,
                    'exclude_from_search' => true,
                    'hierarchical' => false, // Hierarchical causes memory issues - WP loads all records!
                    'rewrite' => false,
                    'query_var' => false,
                    'supports' => $supports,
                    'has_archive' => false,
                    'show_in_menu' => current_user_can( 'scrm_manage' ) ? 'scrm' : true,
                    'show_in_nav_menus' => true,
                    'show_in_rest' => true,
                )
            )
        );

        do_action('scrm_after_register_post_type');
    }


}

SCRM_Post_Types::init();