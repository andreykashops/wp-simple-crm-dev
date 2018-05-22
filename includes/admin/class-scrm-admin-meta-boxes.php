<?php

/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 06.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Admin_Meta_Boxes class
 * 
 * @package SCRM
 * @subpackage Admin
 * @category Meta Boxes
 */
class SCRM_Admin_Meta_Boxes {

    /**
     * Save meta boxes only once
     * 
     * @var bool 
     */
    private static $saved_meta_boxes = false;

    /**
     * Constructor
     */
    public function __construct() {

        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ], 30 );
        add_action( 'save_post', [ $this, 'save_meta_boxes' ], 1, 2 );

        // Save Lead Meta Boxes
        add_action( 'scrm_lead_save_meta_boxes', 'SCRM_Meta_Box_Lead::save', 10, 1 );
        add_action( 'scrm_lead_save_meta_boxes', 'SCRM_Meta_Box_Lead_Contact::save', 20, 1 );
        add_action( 'scrm_lead_save_meta_boxes', 'SCRM_Meta_Box_Lead_Contact_Image::save', 30, 1 );
        
        // Save Contact Meta Boxes
        add_action( 'scrm_contact_save_meta_boxes', 'SCRM_Meta_Box_Contact::save', 10, 1 );
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        
        // Lead
        add_meta_box( 'scrm-lead', __( 'Lead info', 'scrm' ), 'SCRM_Meta_Box_Lead::output', 'scrm_lead', 'normal', 'high' );
        add_meta_box( 'scrm-lead-contact', __( 'Contact info', 'scrm' ), 'SCRM_Meta_Box_Lead_Contact::output', 'scrm_lead', 'normal', 'high' );
        add_meta_box( 'scrm-lead-contact-image', __( 'Contact image', 'scrm' ), 'SCRM_Meta_Box_Lead_Contact_Image::output', 'scrm_lead', 'side', 'low' );
        #add_meta_box( 'scrm-lead-order', __( 'Order info', 'scrm' ), 'SCRM_Meta_Box_Lead_Order::output', 'scrm_lead', 'side', 'low' );
        
        // Contact
        add_meta_box( 'scrm-contact', __( 'Contact Info', 'scrm' ), 'SCRM_Meta_Box_Contact::output', 'scrm_contact', 'normal', 'high' );
    }

    /**
     * Save meta boxes
     * 
     * @param int $post_id 
     * @param object $post
     */
    public function save_meta_boxes( $post_id, $post ) { 

        // $post_id and $post are required
        if ( empty( $post_id ) || empty( $post ) || self::$saved_meta_boxes )
            return;

        // Dont' save meta boxes for revisions or autosaves
        if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) )
            return;

        // Check the nonce
        if ( empty( $_POST[ 'scrm_meta_nonce' ] ) || !wp_verify_nonce( $_POST[ 'scrm_meta_nonce' ], 'scrm_save_data' ) )
            return;

        // Check the post being saved == the $post_id to prevent triggering this call for other save_post events
        if ( empty( $_POST[ 'post_ID' ] ) || $_POST[ 'post_ID' ] != $post_id )
            return;

        // Check user has permission to edit
        if ( !current_user_can( 'edit_post', $post_id ) )
            return;

        // We need this save event to run once to avoid potential endless loops. 
        self::$saved_meta_boxes = true;

        do_action( $post->post_type . '_save_meta_boxes', $post_id, $post );
    }
}

new SCRM_Admin_Meta_Boxes();
