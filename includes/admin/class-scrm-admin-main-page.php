<?php
/**
 * Project manager: Andrey Pavluk
 * Updated by Roman Hofman
 * Date: 07.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * Class SCRM_Admin_Main_Page
 * 
 * @package SCRM
 * @subpackage Admin
 * @category Pages
 */
class SCRM_Admin_Main_Page {

    /**
     * Init
     * 
     * @global object $scrm_multi_list_table
     */
    public static function init() {

        global $scrm_multi_list_table;

        require_once( dirname( __FILE__ ) . '/list-tables/class-scrm-multi-list-table.php' );
        $scrm_multi_list_table = new SCRM_Multi_List_Table();

        add_filter( 'screen_options_show_screen', '__return_false', 100, 1 );
        add_action( 'user_admin_notices', 'control_panel', 1 );
    }

    /**
     * Output control columns items
     * 
     * @param array $items
     */
    protected static function control_columns_item( $items ) {
        
        foreach ( $items as $id => $name ) :
            ?>

            <li id="<?php echo $id; ?>" class="column">
                <span class="column-name">
                    <?php echo $name; ?>
                </span>
                <input class ="column-name-edit" 
                       type="text" 
                       name="<?php sprintf( 'edit-%s', $id ); ?>" 
                       value="<?php echo $name; ?>" 
                       placeholder="default name" 
                       style="display: none;"/>
                <button class="button-link">
                    <span class="dashicons dashicons-edit"></span>
                </button>
            </li>
            
            <?php
        endforeach;
    }

    /**
     * Output leads page
     * 
     * @global object $scrm_multi_list_table
     */
    public static function output() {

        global $scrm_multi_list_table;

        $title = __( 'Main' );

        $scrm_multi_list_table->prepare_items();
        ?>

        <div class="table-control" style="display: none;">

            <div class="control-columns">

                <ul id="sortable1" class="columns-enable columns-sortable">
                    
                    <?php self::control_columns_item( $scrm_multi_list_table->columns_enable ); ?>
                    
                </ul>

                <ul id="sortable2" class="columns-disable columns-sortable">
                    
                    <?php self::control_columns_item( $scrm_multi_list_table->columns_disable ); ?>
                    
                </ul>

            </div>

            <div class="control-settings">

                <label for="edit-scrm-per-page">
                    <?php _e( 'Items in list:', 'scrm' ); ?>
                </label>
                <input id="edit-scrm-per-page"
                       type="number" 
                       name="edit_scrm_per_page" 
                       min="1" max="999" step="1" 
                       value="<?php echo $scrm_multi_list_table->columns_settings[ 'per_page' ]; ?>"/>
                <br/>
                <label for="edit-scrm-small-image">
                    <?php _e( 'Show small image:', 'scrm' ); ?>
                </label>
                <input id="edit-scrm-small-image" 
                       type="checkbox" 
                       name="edit_scrm_small_image" 
                       <?php checked( $scrm_multi_list_table->columns_settings[ 'small_image' ], 1 ); ?>
                       value="1"/>
                
                <input id="current-user" 
                       type="hidden" 
                       value="<?php echo get_current_user_id(); ?>"/>

                <button id="control-submit" class="button">
                    <?php _e( 'Apply', 'scrm' ); ?>
                </button>

            </div>

        </div>

        <div class="toggle-control">
            <p>
                <?php _e( 'Multi-Table Settings', 'scrm' ); ?>
            </p>
        </div>

        <div class="wrap">
            <h1 class="wp-heading-inline">
                <?php echo esc_html( $title ); ?>
            </h1>

            <hr class="wp-header-end">

            <?php $scrm_multi_list_table->views(); ?>

            <form id="scrm-multi-table" method="get">

                <?php $scrm_multi_list_table->search_box( __( 'Search Items' ), 'scrm' ); ?>

                <input type="hidden" name="page" value="scrm" />

                <?php $scrm_multi_list_table->display(); ?>

            </form>

            <br class="clear" />
        </div>

        <?php
    }
}
