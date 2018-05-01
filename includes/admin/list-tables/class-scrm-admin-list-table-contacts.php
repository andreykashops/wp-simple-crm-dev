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
 * SCRM_Admin_List_Table_Contacts Class
 */
class SCRM_Admin_List_Table_Contacts extends SCRM_Admin_List_Table {

    /**
     * Post type
     */
    protected $list_table_type = 'scrm_contact';

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
                case 'phone':
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
            'first-name',
            'last-name',
            'middle-name',
        ];
    }

    /**
     * Define hidden columns
     */
    protected function define_hidden_columns() {
        
        return [
            'position',
            'facebook',
            'vk',
            'twitter',
            'ok',
            'city',
            'street',
            'building',
            'office',
            'description',
            'date',
        ];
    }
    
    /**
     * Render column
     */
    protected function render_column( $column, $post_id ) {
        
        $value = '';
        
        switch ( $column ){
            case 'image':
                $value = $this->get_image( $post_id );
                break;
            default :
                $value = get_post_meta( $post_id, $column, true );
                break;
        }
        ?>
            
            <span class="na">
                <?php echo $value; ?>
            </span>

        <?php
    }
}
