<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 16.03.2018
 */

defined('ABSPATH') || exit;

/**
 * SCRM_Settings_Contact
 */
class SCRM_Settings_Contact extends SCRM_Settings_Page {
    
    /**
     * Constructor
     */
    public function __construct() {
        
        $this->id = 'contact';
        $this->label = __( 'Contact', 'scrm' );

        parent::__construct();
    }

    /**
     * Get settings array
     */
    public function get_settings() {
        
        $settings = [
            [
                'title' => __( 'Contact settings', 'scrm' ),
                'type'  => 'title',
                'desc'  => __( 'This is contact settings tab.', 'scrm' ),
            ],
            [
                'type'    => 'custom-fields',
                'label'   => __( '' ),
                'desc'    => __ ( '' ),
                'id'      => SCRM_Meta_Box_Contact::$type,
                'fields' => [
                    [
                        'label'     => 'First name',
                        'name'      => 'first-name',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '1',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Last name',
                        'name'      => 'last-name',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Middle name',
                        'name'      => 'middle-name',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Phone',
                        'name'      => 'phone',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '1',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Email',
                        'name'      => 'email',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Birthday',
                        'name'      => 'birthday',
                        'type'      => 'date',
                        'value'     => '',
                        'required'   => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Site',
                        'name'      => 'site',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Company',
                        'name'      => 'company',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Position',
                        'name'      => 'position',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Facebook',
                        'name'      => 'facebook',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Vkontakte',
                        'name'      => 'vk',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Twitter',
                        'name'      => 'twitter',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Odnoklasniki',
                        'name'      => 'ok',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Country',
                        'name'      => 'country',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'City',
                        'name'      => 'city',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Street',
                        'name'      => 'street',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Building',
                        'name'      => 'building',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '0',
                        'show'      => '1',
                        'built-in'   => true,
                    ],
                    [
                        'label'     => 'Office',
                        'name'      => 'office',
                        'type'      => 'text',
                        'value'     => '',
                        'required'   => '0',
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

return new SCRM_Settings_Contact();