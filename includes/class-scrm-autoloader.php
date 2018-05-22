<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 06.04.2018
 */

defined( 'ABSPATH' ) || exit;

/**
 * SCRM_Autoloader class
 * 
 * @package SCRM
 * @subpackage Core
 */
class SCRM_Autoloader {

    /**
     * Path to the includes directory
     * 
     * @var string 
     */
    private $include_path = '';
    
    /**
     * Constructor
     */
    public function __construct() {
        
        if ( function_exists( "__autoload" ) ) 
            spl_autoload_register( "__autoload" );

        spl_autoload_register( [ $this, 'autoload' ] );

        $this->include_path = untrailingslashit( plugin_dir_path( SCRM_PLUGIN_FILE ) ) . '/includes/';
    }
    
    /**
     * Take a class name and turn it into a file name
     * 
     * @param string $class 
     * @return string 
     */
    private function get_file_name_from_class( $class ) {
        
        return 'class-' . str_replace( '_', '-', $class ) . '.php';
    }
    
    /**
     * Include a class file
     * 
     * @param string $path 
     * @return bool 
     */
    private function load_file( $path ) {
        
        if ( $path && is_readable( $path ) ) {
            include_once( $path );
            return true;
        }
        return false;
    }

    /**
     * Auto-load SCRM classes on demand to reduce memory consumption
     * 
     * @param string $class 
     */
    public function autoload( $class ) {
        
        $class = strtolower( $class );

        if ( 0 !== strpos( $class, 'scrm_' ) ) 
            return;

        $file = $this->get_file_name_from_class( $class );
        $path = '';

        if ( 0 === strpos( $class, 'scrm_meta_box' ) ) 
            $path = $this->include_path . 'admin/meta-boxes/';

        if ( empty( $path ) || !$this->load_file( $path . $file ) ) 
            $this->load_file( $this->include_path . $file );
    }
}

new SCRM_Autoloader();
