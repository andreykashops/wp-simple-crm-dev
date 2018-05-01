<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 30.04.2018
 */
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'SCRM_Admin_List_Table', false ) )
    include_once 'abstract-class-scrm-admin-list-table.php';

/**
 * SCRM_Admin_List_Table_Leads Class
 */
class SCRM_Admin_List_Table_Leads extends SCRM_Admin_List_Table {

    /**
     * Post type
     */
    protected $list_table_type = 'scrm_lead';

    /**
     * Table columns
     */
    protected $list_table_columns = [];
    
    /**
     * Constructor
     */
    public function __construct() {

        parent::__construct();
    }

    /**
     * Handle any custom filters
     */
    protected function query_filters( $query_vars ) {
        
        
        if ( isset( $query_vars[ 'orderby' ] ) && $query_vars[ 'orderby' ] != 'title' ) {
            
            $column = $query_vars[ 'orderby' ];
            
            $tmp_vars = [];
            
            switch ( $column ) {
                case 'price':
                    $tmp_vars = [
                        'meta_key' => $column,
                        'orderby'  => 'meta_value_num',
                    ];
                    break;
                default :
                    $tmp_vars = [
                        'meta_key' => $column,
                        'orderby'  => 'meta_value',
                    ];
                    break;
            }
            
            return array_merge( $query_vars, $tmp_vars );
        }
        
        return $query_vars;
    }

    /**
     * Define ignored columns
     */
    protected function define_ignored_columns() {
        
        return [
            'access-for-all',
        ];
    }
    
    /**
     * Define hidden columns
     */
    protected function define_hidden_columns() {
        
        return [
            'about-status',
            'about-source',
            'comment',
            'description',
            'date',
        ];
    }

    /**
     * Render column
     */
    protected function render_column( $column, $post_id ) {
        
        $meta = get_post_meta( $post_id, $column, true );
        
        $value = '';
        
        switch ( $column ){
            case 'status':
            case 'source':
            case 'currency':
                $settings = get_option( str_replace( '_', '_settings_', $this->list_table_type ) );
        
                foreach ( $settings[ $this->list_table_type ] as $field ) {
                    
                    if ( $field[ 'name' ] == $column ) 
                        $value = $field[ 'values' ][ $meta ];
                }
                break;
            case 'responsible':
                $value = get_user_by( 'id', $meta )->data->display_name;
                break;
            case 'image':
                $value = $this->get_image( $post_id );
                break;
            default :
                $value = $meta;
                break;
        }
        ?>

            <span class="na">
                <?php echo $value; ?>
            </span>

        <?php
    }
}
