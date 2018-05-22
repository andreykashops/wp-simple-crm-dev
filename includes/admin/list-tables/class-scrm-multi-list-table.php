<?php
/**
 * Project manager: Andrey Pavluk
 * Updated by Roman Hofman
 * Date: 07.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * Class to displaying multi list table
 * 
 * @package SCRM
 * @subpackage Admin
 * @category List Tables
 */
class SCRM_Multi_List_Table extends WP_List_Table {

    /**
     * List enable columns
     * 
     * @var array
     */
    public $columns_enable = [];

    /**
     * List disable columns
     * 
     * @var array
     */
    public $columns_disable = [];

    /**
     * Settings for rows and columns
     * 
     * @var int
     */
    public $columns_settings = [];

    /**
     * Filters
     * 
     * @var array
     */
    private $filters = [];


    /**
     * Constructor
     * 
     * @param array $args
     */
    public function __construct( array $args = [] ) {

        $user_id = get_current_user_id();

        $columns = [
            'enable'   => [],
            'disable'  => [],
            'settings' => [],
        ];

        if ( metadata_exists( 'user', $user_id, 'scrm-multi-table' ) ) {

            $columns = get_user_meta( $user_id, 'scrm-multi-table', true );

            if ( !isset( $columns[ 'settings' ][ 'small_image' ] ) )
                $columns[ 'settings' ][ 'small_image' ] = 0;
        } else {

            $types = [
                'scrm_lead',
                'scrm_contact',
            ];

            $columns[ 'enable' ] = [
                'lead_name'           => 'Lead',
                'lead_status'         => 'Status',
                'contact_name'        => 'Contact',
                'contact_phone'       => 'Phone',
                'contact_email'       => 'Email',
                'lead_price_currency' => 'Price/Currency',
                'lead_responsible'    => 'Responsible',
            ];

            foreach ( $types as $type ) {

                $fields = get_option( str_replace( '_', '_settings_', $type ) );
                $prefix = str_replace( 'scrm_', '', $type );

                foreach ( $fields[ $type ] as $field ) {

                    $key = sprintf( '%s_%s', $prefix, $field[ 'name' ] );

                    if ( in_array( $key, array_keys( $columns[ 'enable' ] ) ) )
                        continue;

                    $value = $field[ 'label' ];

                    $columns[ 'disable' ][ $key ] = $value;
                }
            }

            $columns[ 'settings' ] = [
                'per_page'    => 3,
                'small_image' => 1,
            ];

            update_user_meta( $user_id, 'scrm-multi-table', $columns );

            $default = [];
            $default = wp_parse_args( $default, $columns[ 'enable' ] );
            $default = wp_parse_args( $default, $columns[ 'disable' ] );

            update_option( 'scrm_settings_default_names', $default );
        }

        $default = get_option( 'scrm_settings_default_names' );

        foreach ( $columns as $state => $column ) {

            if ( $state != 'settings' ) {

                foreach ( $column as $column_id => $column_name ) {

                    if ( empty( $column_name ) )
                        $columns[ $state ][ $column_id ] = $default[ $column_id ];
                }
            }
        }

        $this->columns_enable = !empty( $columns[ 'enable' ] ) ? $columns[ 'enable' ] : [];
        $this->columns_disable = !empty( $columns[ 'disable' ] ) ? $columns[ 'disable' ] : [];
        $this->columns_settings = !empty( $columns[ 'settings' ] ) ? $columns[ 'settings' ] : [];

        $this->filters[ 'contact-id' ] = '0';
        $this->filters[ 'price-low' ] = 1;
        $this->filters[ 'price-hight' ] = 10000;
        
        parent::__construct( [
            'singular' => 'scrm',
            'plural'   => 'scrms',
            'screen'   => isset( $args[ 'screen' ] ) ? $args[ 'screen' ] : null,
        ] );
    }

    /**
     * Prepare the items list for display
     */
    public function prepare_items() {

        $per_page = $this->columns_settings[ 'per_page' ];
        $paged = $this->get_pagenum();

        $args = [
            'posts_per_page' => $per_page,
            'offset'         => ( $paged - 1 ) * $per_page,
            'post_type'      => 'scrm_lead',
        ];

        if ( isset( $_REQUEST[ 'orderby' ] ) ) {

            $orderby = $_REQUEST[ 'orderby' ];

            switch ( $orderby ) {

                case 'lead_name':
                    $args[ 'orderby' ] = 'title';
                    break;
                case 'price':
                case 'status':
                    $args[ 'orderby' ] = 'price';
                    $args[ 'meta_query' ] = [
                        'price' => [
                            'type' => 'NUMERIC',
                            'key'  => $orderby,
                        ],
                    ];
                    break;
                default :
                    $args[ 'orderby' ] = 'mix';
                    $args[ 'meta_query' ] = [
                        'mix' => [
                            'type' => 'BINARY',
                            'key'  => $orderby,
                        ]
                    ];
                    break;
            }
        }
        
        switch ( $this->current_action() ) {

            case 'compare':
                if ( isset( $_REQUEST[ 'items' ] ) && count( $_REQUEST[ 'items' ] ) != 0 )
                    $args[ 'post__in' ] = $_REQUEST[ 'items' ];
                break;
            case 'filter':
                if ( !isset( $args[ 'orderby' ] ) )
                    $args[ 'orderby' ] = 'title';
                
                $this->filters = $_REQUEST[ 'filters' ];
                
                if (  $this->filters[ 'contact-id' ] != 0 ) {

                    $args[ 'meta_query' ][ 'contact-id' ] = [
                        'type'    => 'BINARY',
                        'key'     => 'contact-id',
                        'value'   => $this->filters[ 'contact-id' ],
                        'compare' => '=',
                    ];
                }
                
                if ( $this->filters[ 'price-low' ] <= 0 || $this->filters[ 'price-low' ] >= $this->filters[ 'price-hight' ] ) {

                    $this->filters[ 'price-low' ] = 1;
                    $this->filters[ 'price-hight' ] = 10000;
                } else {
                    
                    $args[ 'meta_query' ][ 'price-range' ] = [
                        'type'    => 'NUMERIC',
                        'key'     => 'price',
                        'value'   => [
                            $this->filters[ 'price-low' ],
                            $this->filters[ 'price-hight' ],
                        ],
                        'compare' => 'BETWEEN',
                    ];
                }
                break;
        }
        
        if ( isset( $_REQUEST[ 'order' ] ) )
            $args[ 'order' ] = $_REQUEST[ 'order' ];

        if ( isset( $_REQUEST[ 's' ] ) && !empty( $_REQUEST[ 's' ] ) ) {
            
            $search = wp_unslash( trim( $_REQUEST[ 's' ] ) );
            
            $tmp = [
                'posts_per_page' => -1,
                'post_type'      => 'scrm_contact',
                'meta_query'     => [
                    'relation' => 'OR',
                    [
                        'key' => 'phone',
                        'value' => $search,
                        'compare' => 'LIKE'
                    ],
                    [
                        'key' => 'email',
                        'value' => $search,
                        'compare' => 'LIKE'
                    ]
                ]
            ];
            
            $contacts = get_posts( $tmp );
            wp_reset_postdata();
            
            $args[ 'meta_query' ][ 'contact-id' ] = [
                    'key'     => 'contact-id',
                    'value'   => [],
                    'compare' => 'IN',
            ];
            foreach ( $contacts as $contact ) 
                $args[ 'meta_query' ][ 'contact-id' ][ 'value' ][] = $contact->ID;
         }
        
        $query = new WP_Query( $args );
        wp_reset_postdata();
        
        $show_small_image = $this->columns_settings[ 'small_image' ]; 

        foreach ( $query->posts as $lead ) {

            $lead_meta = get_post_meta( $lead->ID, '', false );
            
            $contact = get_post( $lead_meta[ 'contact-id' ][ 0 ] );
            $contact_meta = get_post_meta( $contact->ID, '', false );

            $item = [];
            $item[ 'id' ] = $lead->ID;
            
            foreach ( $this->columns_enable as $column_id => $column_name ) {

                $data = '';

                switch ( $column_id ) {
                    case 'lead_name';
                        $data = $lead->post_title;
                        break;
                    case 'contact_name':
                        $data = $contact->post_title;
                        break;
                    case 'lead_source':
                        $data = scrm_list_source( $lead_meta[ 'source' ][ 0 ] );
                        break;
                    case 'lead_currency':
                        $data = scrm_list_currency( $lead_meta[ 'currency' ][ 0 ] );
                        break;
                    case 'lead_price_currency':
                        $data = sprintf( '%s / <i>%s</i>', $lead_meta[ 'price' ][ 0 ], scrm_list_currency( $lead_meta[ 'currency' ][ 0 ] ) );
                        break;
                    default:
                        $prefix = explode( '_', $column_id );
                        if ( $prefix[ 0 ] == 'lead' )
                            $data = $lead_meta[ $prefix[ 1 ] ][ 0 ];
                        else
                            $data = $contact_meta[ $prefix[ 1 ] ][ 0 ];
                        break;
                }

                $item[ $column_id ] = $data;
            }
            $this->items[] = ( object ) $item;
        }

        $this->set_pagination_args( array(
            'total_items' => $query->found_posts,
            'per_page'    => $per_page,
        ) );
    }

    /**
     * Displays the search box
     * 
     * @param string $text 
     * @param string $input_id
     */
    public function search_box( $text, $input_id ) {
        
        if ( empty( $_REQUEST['s'] ) && !$this->has_items() )
            return;
        
        if ( !empty( $_REQUEST[ 'orderby' ] ) )
            echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST[ 'orderby' ] ) . '" />';
        if ( !empty( $_REQUEST[ 'order' ] ) )
            echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST[ 'order' ] ) . '" />';
        ?>

        <p class="search-box">
            
            <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>">
                <?php echo $text; ?>:
            </label>
            
            <input id="<?php echo esc_attr( $input_id ); ?>" 
                   type="search" 
                   name="s" 
                   value="<?php _admin_search_query(); ?>" 
                   placeholder="Phone or Email"/>

            <?php submit_button( $text, '', '', false, array( 'id' => 'search-submit' ) ); ?>
            
        </p>
        
        <?php
    }

    /**
     * Get the current action 
     *
     * @return string|false 
     */
    public function current_action() {

        if ( isset( $_REQUEST[ 'filter_action' ] ) && !empty( $_REQUEST[ 'filter_action' ] ) )
            return strtolower( $_REQUEST[ 'filter_action' ] );

        return parent::current_action();
    }

    /**
     * Return an associative array listing all links
     *
     * @return array 
     */
    protected function get_views() {

        $count = wp_count_posts( 'scrm_lead' );

        $links = [];
        $links[ 'all' ] = '<a href="admin.php?page=scrm" class="current" aria-current="page">All <span class="count">(' . $count->publish . ')</span></a>';

        return $links;
    }

    /**
     * Bulk actions available 
     *
     * @return array 
     */
    protected function get_bulk_actions() {

        $actions = [];

        if ( current_user_can( 'delete_posts' ) )
            $actions[ 'compare' ] = __( 'Compare' );

        return $actions;
    }

    /**
     * Output the controls to allow items
     *
     * @param string $which 
     */
    protected function extra_tablenav( $which ) {

        if ( $which == 'bottom' )
            return;

        $args = [
            'posts_per_page' => -1,
            'post_type'      => 'scrm_contact',
        ];
        
        $contacts = get_posts( $args );
        wp_reset_postdata();
       
        $contact_items = [
            '0'         => __( 'All contacts&hellip;' , 'scrm' ),
        ];
        
        foreach ( $contacts as $contact ) {
            
            $contact_meta = get_post_meta( $contact->ID );
            
            $name = [];
            $name[ 'first' ] = $contact_meta[ 'first-name' ][ 0 ];
            $name[ 'last' ] = $contact_meta[ 'last-name' ][ 0 ];
            $name[ 'middle' ] = $contact_meta[ 'middle-name' ][ 0 ];
            
            $contact_items[ $contact->ID ] = sprintf( '%s %s %s', $name[ 'first' ], $name[ 'last' ], $name[ 'middle' ] );
        }
        ?>

        <div class="alignleft actions">

            <label for="filter-contact-id" class="screen-reader-text">
                <?php _e( 'Filter by contact and price&hellip;' ); ?>
            </label>

            <select id="filter-contact-id" name="filters[contact-id]">

                <?php foreach ( $contact_items as $value => $name ) : ?>

                    <option value="<?php echo $value; ?>" <?php selected( $value, $this->filters[ 'contact-id' ] ) ?>>

                        <?php _e( $name, 'scrm' ); ?>

                    </option>

                <?php endforeach; ?>

            </select>
            
            <lable>
                Price 
            </lable>
            
            <input id="filter-price-low" 
                   type="number" 
                   name="filters[price-low]"
                   value="<?php echo $this->filters[ 'price-low' ]; ?>"/>
            
            <span> - </span>
            
            <input id="filter-price-hight" 
                   type="number" 
                   name="filters[price-hight]"
                   value="<?php echo $this->filters[ 'price-hight' ]; ?>"/>

            <?php submit_button( __( 'Filter' ), '', 'filter_action', false ); ?>

        </div>

        <?php
    }

    /**
     * Get a list of columns for the list table
     * 
     * @return array 
     */
    public function get_columns(): array {

        $columns = [
            'cb' => '<input type="checkbox" />',
        ];

        foreach ( $this->columns_enable as $column => $name ) {
            $columns[ $column ] = __( $name, 'scrm' );
        }

        return $columns;
    }

    /**
     * Get a list of sortable columns
     *
     * @return array 
     */
    protected function get_sortable_columns() {

        $columns = [
            'lead_name'           => 'lead_name',
            'lead_status'         => 'status',
            'contact_name'        => 'contact-id',
            'lead_price'          => 'price',
            'lead_currency'       => 'currency',
            'lead_price_currency' => 'price',
            'lead_responsible'    => 'responsible',
        ];

        return $columns;
    }
    
    /**
     * Output 'nothing found' message
     */
    public function no_items() {

        _e( 'Nothing found.' );
    }

    /**
     * Generates content for a single row of the table
     *
     * @param object $item 
     */
    public function single_row( $item ) {
        ?>

        <tr id="<?php printf( 'post-%s', $item->id ); ?>">

            <?php $this->single_row_columns( $item ); ?>

        </tr>

        <?php
    }

    /**
     * Output HTML for callback
     * 
     * @param object $item
     */
    protected function column_cb( $item ) {
        ?>

        <label class="screen-reader-text" for="item_<?php echo $item->id; ?>">

            <?php printf( __( 'Select %s' ), $item->id ); ?>

        </label>

        <input id="item_<?php echo $item->id; ?>" 
               class="items" 
               type="checkbox" 
               name="items[]" 
               value="<?php echo $item->id; ?>" />

        <?php
    }

    /**
     * Output HTML for column lead
     * 
     * @param object $item
     */
    protected function column_lead_name( $item ) {
        
        $show_small_image = $this->columns_settings[ 'small_image' ]; 
        
        if ( $show_small_image ) 
            $img_url = get_the_post_thumbnail_url( $item->id, 'thumbnail' );
        else
            $img_url = false;  
        ?>
        
        <p>
            <?php echo $item->lead_name; ?>
        </p>
            
        <?php if ( $img_url ) : ?>
            
            <img src="<?php echo $img_url; ?>" alt="..." width="35" height="35" />
            
        <?php endif; 
    }
    
    /**
     * Output HTML for column contact
     * 
     * @param object $item
     */
    protected function column_contact_name( $item ) {
        
        $show_small_image = $this->columns_settings[ 'small_image' ]; 
        
        $contact = get_post( get_post_meta( $item->id, '', false )[ 'contact-id' ][ 0 ] );
        
        if ( $show_small_image ) 
            $img_url = get_the_post_thumbnail_url( $contact->ID, 'thumbnail' );
        else
            $img_url = false; 
        
        $meta = get_post_meta( $contact->ID, '', false );
        ?>
            
        <?php if ( $img_url ) : ?>
            
            <img src="<?php echo $img_url; ?>" alt="..." width="40" height="40" />
            
        <?php endif; ?>
        
        <ul>
            <li>
                <?php echo $meta[ 'first-name' ][ 0 ]; ?>
            </li>
            <li>
                <?php echo $meta[ 'last-name' ][ 0 ]; ?>
            </li>
            <li>
                <?php echo $meta[ 'middle-name' ][ 0 ]; ?>
            </li>
        </ul>
            
        <?php
    }
    
    /**
     * Output HTML for column status
     * 
     * @param object $item
     */
    protected function column_lead_status( $item ) {

        $list = scrm_list_status();
        ?>

        <select class="edit-status">

            <?php foreach ( $list as $key => $value ) : ?>

                <option value="<?php echo $key; ?>" <?php selected( $value, $list[ $item->lead_status ] ) ?>>

                    <?php echo $value; ?>

                </option>

            <?php endforeach; ?>

        </select>

        <?php
    }

    /**
     * Output HTML for column responsible
     * 
     * @param object $item
     */
    protected function column_lead_responsible( $item ) {
        $user = get_user_by( 'id', $item->lead_responsible )->data;
        ?>

        <a href="<?php echo get_edit_user_link( $item->lead_responsible ); ?>">
            <b>
                <?php echo $user->display_name; ?>
            </b>
        </a>

        <br/>

        <address>
            <a href="<?php printf( 'mailto:%s', $user->user_email ); ?>">
                <?php echo $user->user_email; ?>
            </a>
        </address>

        <?php
    }

    /**
     * Output HTML fol table data
     * 
     * @param object $item
     * @param string $column_name
     */
    protected function column_default( $item, $column_name ) {

        echo isset( $item->{$column_name} ) ? $item->{$column_name} : '';
    }
}
