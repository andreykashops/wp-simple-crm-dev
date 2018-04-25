<?php
/**
 * Project manager: Andrey Pavluk
 * Updated by Roman Hofman
 * Date: 24.03.2018
 */

defined('ABSPATH') || exit;

/**
 * Class SCRM_Admin_Settings_Page
 */
class SCRM_Admin_Settings_Page
{
    /**
     * 
     */
    private static $saved = false;
    
    /**
     * Setting pages
     */
    private static $settings = [];

    /**
     * Include the settings page classes.
     */
    public static function settings_pages() {
        
        include_once( dirname( __FILE__ ) . '/settings/class-scrm-settings-page.php' );
        
        $settings = [];
        
        $settings[] = include( 'settings/class-scrm-settings-general.php' );
        $settings[] = include( 'settings/class-scrm-settings-lead.php' );
        $settings[] = include( 'settings/class-scrm-settings-contact.php' );
        $settings[] = include( 'settings/class-scrm-settings-order.php' );
        
        self::$settings = apply_filters( 'scrm_get_settings_pages', $settings );
        
        return self::$settings;
    }
    
    /**
     * Save the settings.
     */
    public static function save() {
        
        global $current_tab;
        
        do_action( 'scrm_settings_save_' . $current_tab );
    }

    /**
     * Generate and output settings page
     */
    public static function output(){
        
        global $current_tab;
        
        // Get tabs for the settings page
        $tabs = apply_filters( 'scrm_settings_tabs_array', [] );
        ?>

        <div class="wrap woocommerce">
            <form method="POST" id="mainform" action="" enctype="multipart/form-data">
                
                <nav class="nav-tab-wrapper woo-nav-tab-wrapper">
                    
                    <?php foreach ( $tabs as $slug => $label ) : ?>
                    
                        <a href="<?php echo esc_html( admin_url( 'admin.php?page=scrm_settings&tab=' . esc_attr( $slug ) ) ); ?>" 
                           class="nav-tab <?php echo ( $current_tab === $slug ? 'nav-tab-active' : '' ); ?>">
                               <?php echo esc_html( $label ); ?>
                        </a>
                    
                    <?php endforeach; ?>
                    
                </nav>
                
                <?php do_action( 'scrm_settings_' . $current_tab ); ?>
                
                <p class="submit">
                    
                    <button name="save" 
                            class="button-primary woocommerce-save-button" 
                            type="submit" 
                            value="<?php esc_attr_e( 'Save changes', 'scrm' ); ?>">
                                <?php esc_html_e( 'Save changes', 'scrm' ); ?>
                    </button>
                    
                    <?php wp_nonce_field( 'scrm-settings' ); ?>
                    
                </p>
                
            </form>
        </div>

        <?php
    }
    
    /**
     * Get prefix
     */
    public static function get_prefix() {
        
        global $current_page, $current_tab;
        
        $prefix = $current_page . '_' . $current_tab;
        
        return $prefix;
    }
    
    /**
     * Output admin fields
     */
    public static function load_fields( $options ) {
        
        self::$saved = false;
        
        $prefix = self::get_prefix();
        $temp = get_option( $prefix );
        
        foreach ( $options as $option ) {
            
            if ( isset( $option[ 'id' ] ) ) {
                
                $id = esc_attr( $option[ 'id' ] );
                
                $type = esc_attr( $option[ 'type' ] );
                
                $label = esc_html( $option['label'] );
                $desc = !empty( $option[ 'desc' ] ) ? esc_html( $option[ 'desc' ] ) : '';
                $other = '';
            }
            
            switch ( $option[ 'type' ] ) {
                case 'title':
                    scrm_optoin_section_begin( $option );
                    break;
                case 'text':
                    $value = !empty( $temp[ $id ] ) ? $temp[ $id ] : $option[ 'value' ];
                    scrm_option_field_input($prefix, $id, $type, $value, $label, $desc, $other);
                    break;
                case 'custom-fields':
                    $fields = !empty( $temp[ $id ] ) ? $temp[ $id ] : $option[ 'fields' ];
                    scrm_option_custom_fields( $prefix, $id, $fields, $label, $desc );
                    break;
                case 'end':
                    scrm_option_section_end();
                    break;
            }
        }
    }

    /**
     * Save admin fields
     */
    public static function save_fields( $options ) {
        
        if ( self::$saved )
            return;
        
        $prefix = self::get_prefix();
            
        $temp = isset( $_POST[ $prefix ] ) ? $_POST[ $prefix ] : null; 
        
        if ( is_null( $temp ) ) 
            return false;
        
        $data = [];
        
        foreach ( $options as $option ) {
            
            if ( isset( $option[ 'id' ] ) )
                $id = $option[ 'id' ];
            else
                continue;
            
            switch ( $option[ 'type' ] ) {
                
                case 'text':
                    $data[ $id ] = $temp[ $id ];
                    break;
                
                case 'custom-fields':
                    foreach ( $temp[ $id ] as $key => $values ) {
                        
                        foreach ( $values as $index => $value ) {
                            
                            switch ( $key ) {
                                case 'label':
                                case 'name':
                                case 'type':
                                case 'value':
                                case 'placeholder':
                                    $value = sanitize_text_field( $value );
                                    break;
                                case 'values':
                                    $value = sanitize_textarea_field( $value );
                                    break;
                                default :
                                    $value = sanitize_key( $value );
                                    break;
                            }
                            
                            $data[ $id ][ sprintf( '_%s', $index ) ][ $key ] = $value;
                        }
                    }
                    $i = 0;
                    foreach ( $data[ $id ] as $key => $value ) {
                        
                        $type = $value[ 'type' ];
                        
                        if ( $type == 'select' || $type == 'radio' ) {
                            
                            $value[ 'values' ] = json_decode( '{"' . str_replace( [ "\r\n", ":" ], [ '","', '":"' ] , $value[ 'values' ] ) . '"}', true );
                        }

                        $data[ $id ][ $i ] = $value;
                        ++$i;
                        unset( $data[ $id ][ $key ] );
                    }
                    break;
            }
        }
        
        self::$saved = update_option( $prefix, $data);
    }
}
