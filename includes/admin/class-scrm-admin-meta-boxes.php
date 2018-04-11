<?php

/**
 * Created by Roman Hofman
 * Date: 06.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Admin_Meta_Boxes class
 */
class SCRM_Admin_Meta_Boxes {

    /**
     * Save meta boxes only once
     */
    private static $saved_meta_boxes = false;

    /**
     * Constructor
     */
    public function __construct() {

        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ], 30 );
        add_action( 'save_post', [ $this, 'save_meta_boxes' ], 1, 2 );

        // Save Lead Meta Boxes
        add_action( 'scrm_lead_save_meta_boxes', 'SCRM_Meta_Box_Lead_Primary::save', 10, 1 );
        add_action( 'scrm_lead_save_meta_boxes', 'SCRM_Meta_Box_Lead_Secondary::save', 20, 1 );
        add_action( 'scrm_lead_save_meta_boxes', 'SCRM_Meta_Box_Lead_Contact::save', 30, 1 );
        
        // Save Contact Meta Boxes
        add_action( 'scrm_contact_save_meta_boxes', 'SCRM_Meta_Box_Contact_Primary::save', 10, 1 );
        add_action( 'scrm_contact_save_meta_boxes', 'SCRM_Meta_Box_Contact_Secondary::save', 20, 1 );
        add_action( 'scrm_contact_save_meta_boxes', 'SCRM_Meta_Box_Contact_Address::save', 30, 1 );
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {

        // Lead
        add_meta_box( 'scrm-lead-primary', __( 'Primary', 'scrm' ), 'SCRM_Meta_Box_Lead_Primary::output', 'scrm_lead', 'normal', 'high' );
        add_meta_box( 'scrm-lead-secondary', __( 'Secondary', 'scrm' ), 'SCRM_Meta_Box_Lead_Secondary::output', 'scrm_lead', 'normal', 'high' );
        add_meta_box( 'scrm-lead-contact', __( 'Contact', 'scrm' ), 'SCRM_Meta_Box_Lead_Contact::output', 'scrm_lead', 'normal', 'high' );
        
        // Contact
        add_meta_box( 'scrm-contact-primary', __( 'Primary', 'scrm' ), 'SCRM_Meta_Box_Contact_Primary::output', 'scrm_contact', 'normal', 'high' );
        add_meta_box( 'scrm-contact-secondary', __( 'Secondary', 'scrm' ), 'SCRM_Meta_Box_Contact_Secondary::output', 'scrm_contact', 'normal', 'high' );
        add_meta_box( 'scrm-contact-address', __( 'Address', 'scrm' ), 'SCRM_Meta_Box_Contact_Address::output', 'scrm_contact', 'normal', 'high' );
    }

    /**
     * Save meta boxes
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
