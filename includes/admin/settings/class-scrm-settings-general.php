<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 16.03.2018
 */

defined('ABSPATH') || exit;

/**
 * SCRM_Settings_General
 * 
 * @package SCRM
 * @subpackage Admin
 * @category Page
 */
class SCRM_Settings_General extends SCRM_Settings_Page {
    
    /**
     * Constructor
     */
    public function __construct() {
        
        $this->id = 'general';
        $this->label = __( 'General', 'scrm' );

        parent::__construct();
    }

    /**
     * Get settings array
     * 
     * @return array 
     */
    public function get_settings() {
        
        $settings = [
            [
                'type'  => 'title',
                'title' => __( 'General settings', 'scrm' ),
                'desc'  => __( 'This is general settings tab.', 'scrm' ),
            ],
            [
                'type'    => 'text',
                'label'   => __( 'Currency', 'scrm' ),
                'desc'    => __ ( 'Input default currency type.', 'scrm' ),
                'id'      => 'currency',
                'value' => 'USD',
            ],
            [
                'type'    => 'checkbox',
                'label'   => __( 'Uninstall control', 'scrm' ),
                'desc'    => __ ( 'Remove all plugin data on uninstall.', 'scrm' ),
                'id'      => 'remove-all',
                'value' => '1',
            ],
            [
                'type' => 'end',
            ],
        ];

        return apply_filters( 'scrm_get_settings_' . $this->id, $settings );
    }
}

return new SCRM_Settings_General();