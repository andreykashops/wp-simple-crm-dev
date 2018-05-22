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
 * 
 * @package SCRM
 * @subpackage Admin
 * @category List Tables
 */
class SCRM_Admin_List_Table_Leads extends SCRM_Admin_List_Table {

    /**
     * Post type
     * 
     * @var string
     */
    protected $list_table_type = 'scrm_lead';

    /**
     * Table columns
     * 
     * @var array
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
     * 
     * @param array $query_vars
     * @return array $qurey_vars
     */
    protected function query_filters( $query_vars ) {
        
        
        if ( isset( $query_vars[ 'orderby' ] ) && $query_vars[ 'orderby' ] != 'title' ) {
            
            $column = $query_vars[ 'orderby' ];
            
            $tmp_vars = [];
            
            switch ( $column ) {
                case 'price':
                case 'status':
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
     * 
     * @return array
     */
    protected function define_ignored_columns() {
        
        return [
            'currency',
            'order',
            'access-for-all',
        ];
    }
    
    /**
     * Define hidden columns
     * 
     * @return array
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
     * 
     * @param string $column
     * @param int $post_id
     */
    protected function render_column( $column, $post_id ) {
        
        $meta = get_post_meta( $post_id, $column, true );
        
        $content = '';
        
        switch ( $column ){
            case 'status':
                $list = scrm_list_status();
                ?>

                <select class="edit-status">
                    
                    <?php foreach ( $list as $key => $value ) : ?>
                                            
                        <option value="<?php echo $key; ?>" <?php selected( $value, $list[ $meta ] ) ?>>
                            <?php echo $value; ?>
                        </option>
                        
                    <?php endforeach; ?>
                    
                </select>

                <?php
                return;
            case 'source':
                $list = scrm_list_source();
                $content = $list[ $meta ];
                break;
            case 'price':
                $content = sprintf( '%s <span class="currency">%s</span>', $meta, strtoupper( get_post_meta( $post_id, 'currency', true ) ) );
                break;
            case 'payment':
                $list = scrm_list_payment();
                $content = $list[ $meta ];
                break;
            case 'responsible':
                $user_name = get_user_by( 'id', $meta )->data->display_name;
                $user_link = get_edit_user_link( $meta );
                $content = sprintf( '<a href="%s">%s</a>', $user_link, $user_name );
                break;
            case 'image':
                $content = $this->get_image( $post_id );
                break;
            case 'contact-id':
                $data = get_post_meta( $meta, '', true );
                ?>

                <ul>
                    
                    <li>
                        <?php printf( '%s %s %s', $data[ 'first-name' ][0], $data[ 'last-name' ][0], $data[ 'middle-name' ][0] ); ?>
                    </li>
                    
                    <?php $source = get_post_meta( $post_id, 'source', true ); ?>
                    
                    <?php if ( $source == 'phone' || $source == 'email' || $source == 'site' ) : ?>
                    
                    <li>
                        <?php echo $data[ $source ][0]; ?>
                    </li>
                    
                    <?php endif; ?>
                    
                </ul>
                
                <?php
                return;
            default :
                $content = $meta;
                break;
        }
        ?>

            <span class="na">
                <?php echo $content; ?>
            </span>

        <?php
    }
}
