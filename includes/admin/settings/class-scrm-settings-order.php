<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 16.03.2018
 */

defined('ABSPATH') || exit;

/**
 * SCRM_Settings_Order
 */
class SCRM_Settings_Order extends SCRM_Settings_Page {
    
    /**
     * Constructor
     */
    public function __construct() {
        
        $this->id = 'order';
        $this->label = __( 'Order', 'scrm' );

        parent::__construct();
    }

    /**
     * Get settings array
     */
    public function get_settings() {
        
        $settings = [
            [
                'title' => __( 'Order settings', 'scrm' ),
                'type'  => 'title',
                'desc'  => __( 'This is order settings tab.', 'scrm' ),
            ],
            [ 
                'type' => 'end', 
            ],
        ];
        
        return apply_filters( 'scrm_get_settings_' . $this->id, $settings );
    }
}

return new SCRM_Settings_Order();