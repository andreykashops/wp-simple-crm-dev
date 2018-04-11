<?php
/* 
 * Created by Roman Hofman
 * Date: 10.04.2018
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get all SCRM screen ids.
 */
function scrm_get_screen_ids() {
    
    $screen_ids = [
        'scrm_lead',
        'scrm_contact',
    ];
    
    return apply_filters( 'scrm_screen_ids', $screen_ids );
}
