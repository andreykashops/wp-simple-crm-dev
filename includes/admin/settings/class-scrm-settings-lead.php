<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 16.03.2018
 */

defined('ABSPATH') || exit;

/**
 * SCRM_Settings_Lead
 */
class SCRM_Settings_Lead extends SCRM_Settings_Page {
    
    /**
     * Constructor
     */
    public function __construct() {
        
        $this->id = 'lead';
        $this->label = __( 'Lead', 'scrm' );

        parent::__construct();
    }

    /**
     * Get settings array
     */
    public function get_settings() {
        
        $status = [
            'not_processed' => 'Not Processed',
            'start'         => 'Start',
            'progress_25'   => 'Progress 25%',
            'progress_50'   => 'Progress 50%',
            'progress_75'   => 'Progress 75%',
            'end'           => 'End',
            'success'       => 'Success',
            'failure'       => 'Failure',
        ];
        
        $source = [
            'phone' => 'Phone',
            'email' => 'Email',
            'other' => 'Other',
        ];
        
        $currency = [
            'euro'  => 'EURO',
            'usd'   => 'USD',
            'uah'   => 'UAH',
            'rub'   => 'RUB',
        ];

        $responsible = scrm_get_users();
        
        $settings = [
            [
                'title' => __( 'Lead settings', 'scrm' ),
                'type'  => 'title',
                'desc'  => __( 'This is lead settings tab.', 'scrm' ),
            ],
            [
                'type'    => 'custom-fields',
                'label'   => __( '' ),
                'desc'    => __ ( '' ),
                'id'      => SCRM_Meta_Box_Lead::$type,
                'fields' => [
                    [
                        'label'     => 'Status',
                        'name'      => 'status',
                        'type'      => 'select',
                        'value'     => 'not_processed',
                        'values'    => $status,
                        'required'  => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Source',
                        'name'      => 'source',
                        'type'      => 'select',
                        'value'     => 'phone',
                        'values'    => $source,
                        'required'  => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Price',
                        'name'      => 'price',
                        'type'      => 'number',
                        'value'     => '0',
                        'min'       => '0',
                        'max'       => '100000',
                        'required'  => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Currency',
                        'name'      => 'currency',
                        'type'      => 'select',
                        'value'     => 'usd',
                        'values'    => $currency,
                        'required'  => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Responsible',
                        'name'      => 'responsible',
                        'type'      => 'users',
                        'value'     => '1',
                        'values'    => $responsible,
                        'required'  => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Access for all',
                        'name'      => 'access-for-all',
                        'type'      => 'checkbox',
                        'value'     => '0',
                        'required'  => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'About status',
                        'name'      => 'about-status',
                        'type'      => 'text',
                        'value'     => '...',
                        'required'  => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'About source',
                        'name'      => 'about-source',
                        'type'      => 'text',
                        'value'     => '...',
                        'required'  => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Comment',
                        'name'      => 'comment',
                        'type'      => 'textarea',
                        'value'     => '...',
                        'required'  => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                ],
            ],
            [ 
                'type' => 'end', 
            ],
        ];
        
        return apply_filters( 'scrm_get_settings_' . $this->id, $settings );
    }
}

return new SCRM_Settings_Lead();