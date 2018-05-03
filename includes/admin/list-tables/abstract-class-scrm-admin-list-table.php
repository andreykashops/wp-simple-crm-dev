<?php

/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 30.04.2018
 */
defined( 'ABSPATH' ) || exit;

if ( class_exists( 'SCRM_Admin_List_Table', false ) )
    return;

/**
 * SCRM_Admin_List_Table Class
 */
abstract class SCRM_Admin_List_Table {

    /**
     * Post type
     */
    protected $list_table_type = '';
    
    /**
     * Table columns
     */
    protected $list_table_columns = [];

    /**
     * Object being shown on the row
     */
    protected $object = null;

    /**
     * Constructor
     */
    public function __construct() {

        if ( $this->list_table_type ) {

            $settings = get_option( str_replace( '_', '_settings_', $this->list_table_type ) );
            
            if ( empty( $settings ) )
                return;
            
            $ignored = $this->define_ignored_columns();

            $this->list_table_columns[ 'image' ] = __( 'Image', 'scrm' );

            foreach ( $settings[ $this->list_table_type ] as $field ) {

                if ( $field[ 'show' ] == 1 && !in_array( $field[ 'name' ], $ignored ) )
                    $this->list_table_columns[ $field[ 'name' ] ] = $field[ 'label' ];
            }
            
            add_filter( 'view_mode_post_types', [ $this, 'disable_view_mode' ] );
            add_filter( 'request', [ $this, 'request_query' ] );
            add_filter( 'post_row_actions', [ $this, 'row_actions' ], 100, 2 );
            add_filter( 'default_hidden_columns', [ $this, 'default_hidden_columns' ], 10, 2 );
            add_filter( 'manage_edit-' . $this->list_table_type . '_sortable_columns', [ $this, 'define_sortable_columns' ] );
            add_filter( 'manage_' . $this->list_table_type . '_posts_columns', [ $this, 'define_columns' ] );
            add_action( 'manage_' . $this->list_table_type . '_posts_custom_column', [ $this, 'render_columns' ], 10, 2 );
        }
    }
    
    /**
     * Remove support "View Mode" switching
     */
    public function disable_view_mode( $post_types ) {
        
        unset( $post_types[ $this->list_table_type ] );
        
        return $post_types;
    }

    /**
     * Handle any filters
     */
    public function request_query( $query_vars ) {
        
        global $typenow;
        
        if ( $this->list_table_type == $typenow ) {
            return $this->query_filters( $query_vars );
        }

        return $query_vars;
    }

    /**
     * Set row actions
     */
    public function row_actions( $actions, $post ) {

        unset( $actions[ 'inline hide-if-no-js]' ], $actions[ 'view' ] );
        
        return $actions;
    }

    /**
     * Adjust which columns are displayed by default
     */
    public function default_hidden_columns( $hidden, $screen ) {
        
        if ( isset( $screen->id ) && 'edit-' . $this->list_table_type === $screen->id ) 
            $hidden = array_merge( $hidden, $this->define_hidden_columns() );
        
        return $hidden;
    }

    /**
     * Define which columns are sortable
     */
    public function define_sortable_columns( $columns ) {

        $settings = get_option( str_replace( '_', '_settings_', $this->list_table_type ) );
        
        $ignored = $this->define_ignored_columns();
        
        $args = [];
        
        foreach ( $settings[ $this->list_table_type ] as $field ) {
            
            if ( $field[ 'sorted' ] == 1 )
                $args[ $field[ 'name' ] ] = $field[ 'name' ];
        }

        return wp_parse_args( $args, $columns );
    }

    /**
     * Define which columns to show on this screen
     */
    public function define_columns( $columns ) {

        if ( empty( $columns ) && !is_array( $columns ) )
            $columns = [];

        unset( $columns[ 'comments' ], $columns[ 'date' ] );
        
        foreach ( $this->list_table_columns as $key => $value ) {
            
            $columns[ $key ] = __( $value, 'scrm' );
        }
        
        $columns[ 'date' ] = __( 'Date', 'scrm' );

        return $columns;
    }

    /**
     * Render individual columns
     */
    public function render_columns( $column, $post_id ) {
        
        if ( in_array( $column, array_keys( $this->list_table_columns ) ) ) {
            
            $this->render_column( $column, $post_id );
        }
    }
    
    /**
     * Get image
     */
    protected function get_image( $post_id ) {
        
        $img_url = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
        
        if ( !empty( $img_url ) ) 
            $image = '<img src="' . $img_url . '" alt="..." height="75"/>';
        else 
            $image = 'No Image';
        
        return $image;
    }
}
