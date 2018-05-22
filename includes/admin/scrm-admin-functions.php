<?php
/** 
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 10.04.2018
 * 
 * @package  SCRM
 * @subpackage Admin
 * @category Functions
 */

defined( 'ABSPATH' ) || exit;

/**
 * Create Lead 
 * 
 * @param array $args {
 *      @type   string  $title          Lead title
 *      @type   string  $status         Status : 0%, 1%, 25%, 50%, 75%, 100%, success, failure 
 *      @type   string  $source         Source : phone, email, other
 *      @type   int     $price          Price
 *      @type   string  $currency       Currency : euro, usd, rub, uah
 *      @type   string  $payment        Payment : cash, non-cash
 *      @type   string  $order          Order
 *      @type   int     $responsible    Responsible : User ID
 *      @type   bool    $access_for_all Access for all
 *      @type   string  $about_status   About status
 *      @type   string  $about_source   About source
 *      @type   string  $comment        Comment
 *      @type   string  $contact_id     Contact ID
 *      @type   int     $attachment_id  Attachment ID
 * }
 * @return int Lead ID
 */
function scrm_lead( $args = [] ) {
    
    $user_id = get_current_user_id();
    
    $lead_data = [
        'post_author'    => $user_id,
        'post_title'     => isset( $args[ 'title' ] ) ? $args[ 'title' ] : 'Лид №1',
        'post_status'    => 'publish',
        'post_type'      => 'scrm_lead',
        'comment_status' => 'closed',
        'ping_status'    => 'closed',
        'meta_input'     => [
            'status'         => isset( $args[ 'status' ] )          ? $args[ 'status' ]         : '1%',
            'source'         => isset( $args[ 'source' ] )          ? $args[ 'source' ]         :  'phone', 
            'price'          => isset( $args[ 'price' ] )           ? $args[ 'price' ]          : 100,
            'currency'       => isset( $args[ 'currency' ] )        ? $args[ 'currency' ]       : 'usd',
            'payment'        => isset( $args[ 'payment' ] )         ? $args[ 'payment' ]        : 'cash',
            'order'          => isset( $args[ 'order' ] )           ? $args[ 'order' ]          : '',
            'responsible'    => isset( $args[ 'responsible' ] )     ? $args[ 'responsible' ]    : $user_id,
            'access-for-all' => isset( $args[ 'access_for_all' ] )  ? $args[ 'access_for_all' ] : true,
            'about-status'   => isset( $args[ 'about_status' ] )    ? $args[ 'about_status' ]   : 'Start working',
            'about-source'   => isset( $args[ 'about_source' ] )    ? $args[ 'about_source' ]   : 'Only phone',
            'comment'        => isset( $args[ 'comment' ] )         ? $args[ 'comment' ]        : 'No comments',
            'contact-id'     => isset( $args[ 'contact_id' ] )      ? $args[ 'contact_id' ]     : 0,
        ],
    ];

    $lead_id = wp_insert_post( $lead_data );
    
    if ( isset( $args[ 'attachment_id' ] ) )
        set_post_thumbnail( $lead_id, $args[ 'attachment_id' ] );

    return $lead_id;
}

/**
 * Create contact
 * 
 * @param array $args {
 *      @type   string     $first_name      First name
 *      @type   string     $last_name       Last name
 *      @type   string     $middle_name     Middle name
 *      @type   string     $phone           Phone number
 *      @type   string     $email           Email address
 *      @type   string     $birthday        Year-Month-Day
 *      @type   string     $site            Site URL
 *      @type   string     $company         Company name
 *      @type   string     $position        Position in company
 *      @type   string     $facebook        Facebook link URL
 *      @type   string     $vk              VKontacte link URL
 *      @type   string     $twitter         Twitter link URL
 *      @type   string     $ok              Odnoklasniki link URL
 *      @type   string     $country         Country
 *      @type   string     $city            City
 *      @type   string     $street          Street
 *      @type   string     $building        Building
 *      @type   string     $office          Office
 *      @type   int        $attachment_id   Attachment ID
 * }
 * @return int Contact ID
 */
function scrm_contact( $args = [] ) {
    
    $title = '';
        
    if ( isset( $args[ 'title' ] ) ) {
        
        $title = $args[ 'title' ];
    } else {
        
        $title .= isset( $args[ 'first_name' ] ) ? $args[ 'first_name' ] : '';
        $title .= isset( $args[ 'last_name' ] ) ? ' ' . $args[ 'last_name' ] : '';
        $title .= isset( $args[ 'middle_name' ] ) ? ' ' . $args[ 'middle_name' ] : '';
    }
    
    $contact_data = [
        'post_author'    => get_current_user_id(),
        'post_title'     => !empty( $title ) ? $title : 'Имя Фамилия Отчество',
        'post_status'    => 'publish',
        'post_type'      => 'scrm_contact',
        'comment_status' => 'closed',
        'ping_status'    => 'closed',
        'meta_input'     => [
            'first-name'  => isset( $args[ 'first_name' ] )     ? $args[ 'first_name' ]     : 'Имя',
            'last-name'   => isset( $args[ 'last_name' ] )      ? $args[ 'last_name' ]      : 'Фамилия',
            'middle-name' => isset( $args[ 'middle_name' ] )    ? $args[ 'middle_name' ]    : 'Отчество',
            'phone'       => isset( $args[ 'phone' ] )          ? $args[ 'phone' ]          : 'Телефон',
            'email'       => isset( $args[ 'email' ] )          ? $args[ 'email' ]          : 'Почта',
            'birthday'    => isset( $args[ 'birthday' ] )       ? $args[ 'birthday' ]       : '1970-01-01',
            'site'        => isset( $args[ 'site' ] )           ? $args[ 'site' ]           : 'Сайт',
            'company'     => isset( $args[ 'company' ] )        ? $args[ 'company' ]        : 'Компания',
            'position'    => isset( $args[ 'position' ] )       ? $args[ 'position' ]       : 'Должность',
            'facebook'    => isset( $args[ 'facebook' ] )       ? $args[ 'facebook' ]       : 'Фейсбук',
            'vk'          => isset( $args[ 'vk' ] )             ? $args[ 'vk' ]             : 'Вконтакте',
            'twitter'     => isset( $args[ 'twitter' ] )        ? $args[ 'twitter' ]        : 'Твиттер',
            'ok'          => isset( $args[ 'ok' ] )             ? $args[ 'ok' ]             : 'Однокласники',
            'country'     => isset( $args[ 'country' ] )        ? $args[ 'country' ]        : 'Страна',
            'city'        => isset( $args[ 'city' ] )           ? $args[ 'city' ]           : 'Город',
            'street'      => isset( $args[ 'street' ] )         ? $args[ 'street' ]         : 'Улица',
            'building'    => isset( $args[ 'building' ] )       ? $args[ 'building' ]       : 'Здание',
            'office'      => isset( $args[ 'office' ] )         ? $args[ 'office' ]         : 'Офис',
        ],
    ];

    $contact_id = wp_insert_post( $contact_data );

    if ( isset( $args[ 'attachment_id' ] ) )
        set_post_thumbnail( $contact_id, $args[ 'attachment_id' ] );
    
    return $contact_id;
}

/**
 * Create lead with contact
 * 
 * @see SCRM_Install::create_default_data()
 * 
 * @param array $args {
 *      @type   array   $lead {
 *          @type   string  $title          Lead title
 *          @type   string  $status         Status : 0%, 1%, 25%, 50%, 75%, 100%, success, failure 
 *          @type   string  $source         Source : phone, email, other
 *          @type   int     $price          Price
 *          @type   string  $currency       Currency : euro, usd, rub, uah
 *          @type   string  $payment        Payment : cash, non-cash
 *          @type   string  $order          Order
 *          @type   int     $responsible    Responsible : User ID
 *          @type   bool    $access_for_all Access for all
 *          @type   string  $about_status   About status
 *          @type   string  $about_source   About source
 *          @type   string  $comment        Comment
 *          @type   string  $contact_id     Contact ID
 *          @type   int     $attachment_id  Attachment ID
 *      },
 *      @type   array   $contact {
 *          @type   string     $first_name      First name
 *          @type   string     $last_name       Last name
 *          @type   string     $middle_name     Middle name
 *          @type   string     $phone           Phone number
 *          @type   string     $email           Email address
 *          @type   string     $birthday        Year-Month-Day
 *          @type   string     $site            Site URL
 *          @type   string     $company         Company name
 *          @type   string     $position        Position in company
 *          @type   string     $facebook        Facebook link URL
 *          @type   string     $vk              VKontacte link URL
 *          @type   string     $twitter         Twitter link URL
 *          @type   string     $ok              Odnoklasniki link URL
 *          @type   string     $country         Country
 *          @type   string     $city            City
 *          @type   string     $street          Street
 *          @type   string     $building        Building
 *          @type   string     $office          Office
 *          @type   int        $attachment_id   Attachment ID
 *      }
 * }
 * @return array 
 */
function scrm_lead_contact( $args = [] ) {
    
    $contact_id = scrm_contact( $args[ 'contact' ] );
    
    $args[ 'lead' ][ 'contact_id' ] = $contact_id;
    
    $lead_id = scrm_lead( $args[ 'lead' ] );
    
    return [
        'lead_id'    => $lead_id,
        'contact_id' => $contact_id,
    ];
}

/**
 * Create attachment
 * 
 * @see SCRM_Install::create_default_data()
 * 
 * @param string $file
 * @return int|bool 
 */
function scrm_attachment( $file ) {
    
    $upload = wp_upload_bits( basename( $file ), null, file_get_contents( $file ) );

    if ( !empty( $upload[ 'error' ] ) )
        return false;

    $file_path = $upload[ 'file' ];
    $file_name = basename( $file_path );
    $file_type = $upload[ 'type' ];

    $wp_upload_dir = wp_upload_dir();

    $attachment = [
        'guid'           => $wp_upload_dir[ 'url' ] . '/' . $file_name,
        'post_mime_type' => $file_type,
        'post_title'     => preg_replace( '/\.[^.]+$/', '', $file_name ),
        'post_status'    => 'inherit',
    ];

    $attachment_id = wp_insert_attachment( $attachment, $file_path );

    require_once ABSPATH . 'wp-admin/includes/image.php';

    $attachment_data = wp_generate_attachment_metadata( $attachment_id, $file_path );

    wp_update_attachment_metadata( $attachment_id, $attachment_data );

    return $attachment_id;
}

/**
 * Meta box prefix
 * 
 * @param string $prefix
 * @return string
 */
function scrm_prefix( $prefix ) {
    
    $prefix = str_replace( '_', '-', $prefix );
    
    return $prefix;
}

/**
 * Get Lists 
 * 
 * @param string $values
 * @return array
 */
function scrm_lead_lists( $field ) {
    
    $options = get_option( 'scrm_settings_lead' );
    
    if ( !$options )
        return false;
    
    foreach ( $options[ 'scrm_lead' ] as $fields ) {
        
        if ( $fields[ 'name' ] == $field )
            return $fields[ 'values' ];
    }
}

/**
 * Status list
 * 
 * @param string|null $value
 * @return array|string
 */
function scrm_list_status( $value = null ) {

    $list = scrm_lead_lists( 'status' );
    
    if ( !$list ) {
    
        $list = [
            '0%'      => 'Not Processed',
            '1%'      => 'Start',
            '25%'     => 'Progress 25%',
            '50%'     => 'Progress 50%',
            '75%'     => 'Progress 75%',
            '100%'    => 'End',
            'success' => 'Success',
            'failure' => 'Failure',
        ];
    }

    if ( is_null( $value ) )
        return $list;
    else
        return $list[ $value ];
}

/**
 * Source list
 * 
 * @param string|null $value
 * @return array|string
 */
function scrm_list_source( $value = null ) {

    $list = scrm_lead_lists( 'source' );
    
    if ( !$list ) {
    
        $list = [
            'phone' => 'Phone',
            'email' => 'Email',
            'site'  => 'Site',
            'other' => 'Other',
        ];
    }

    if ( is_null( $value ) )
        return $list;
    else
        return $list[ $value ];
}

/**
 * Currency list
 * 
 * @param string|null $value
 * @return array|string
 */
function scrm_list_currency( $value = null ) {

    $list = scrm_lead_lists( 'currency' );
    
    if ( !$list ) {
    
        $list = [
            'usd'  => 'USD',
            'rub'  => 'RUB',
        ];
    }

    if ( is_null( $value ) )
        return $list;
    else
        return $list[ $value ];
}

/**
 * Payment list
 * 
 * @param string|null $value
 * @return array|string
 */
function scrm_list_payment( $value = null ) {

    $list = scrm_lead_lists( 'payment' );
    
    if ( !$list ) {
    
        $list = [
            'cash' => 'Cash',
            'non-cash'  => 'Non-Cash',
        ];
    }

    if ( is_null( $value ) )
        return $list;
    else
        return $list[ $value ];
}

/**
 * Country list
 * 
 * @param string|null $value
 * @return array|string
 */
function scrm_list_country( $value = null ) {
    
    $list = [
        
    ];
    
    if ( is_null( $value ) )
        return $list;
    else
        return $list[ $value ];
}

/**
 * Get contacts
 * 
 * @return array
 */
function scrm_list_contacts() {
    
    $list[ 0 ] = __( 'Create New', 'scrm' );
    
    $posts = get_posts( [ 'posts_per_page' => -1, 'post_type' => 'scrm_contact' ] );
    foreach ( $posts as $post ) {

        $list[ $post->ID ] = $post->post_title;
    }
    wp_reset_postdata();
    
    return $list;
}

/**
 * Get users
 * 
 * @return array
 */
function scrm_list_users() {
    
    $list = [];
    
    $users = get_users();
    foreach ( $users as $user ) 
        $list[ $user->data->ID ] = $user->data->display_name;
    
    wp_reset_postdata();
    
    return $list;
}

/**
 * Option start section
 * 
 * @param array $option
 */
function scrm_optoin_section_begin( $option ) { 
    ?>
    
    <!-- section start -->

    <?php if ( ! empty( $option['title'] ) ) : ?>

        <h2>
            <?php echo esc_html( $option['title'] ) ?>
        </h2>
        
    <?php endif; ?>

    <?php if ( ! empty( $option['desc'] ) ) : ?>

        <p>
            <?php echo wp_kses_post( wptexturize( $option['desc'] ) ); ?>
        </p>

    <?php endif; ?>
        
    <table class="form-table">
            
    <?php
}

/**
 * Option end section
 */
function scrm_option_section_end() {
    ?>
        
    </table>
        
    <!-- section end -->
        
    <?php
}

/**
 * Option field input
 * 
 * @param string $prefix
 * @param string $id
 * @param string $type
 * @param string|int $value
 * @param string $label
 * @param string $desc 
 * @param string $other
 */
function scrm_option_field_input( $prefix, $id, $type, $value, $label, $desc, $other) { 
    
    $name = sprintf( '%s[%s]', $prefix, $id );
    
    $prefix = scrm_prefix( $prefix );
    
    $id = sprintf( '%s-%s', $prefix, $id ); 
    ?>
    
    <tr valign="top">
        
        <th scope="row" class="titledesc">
            
            <label for="<?php echo $id; ?>">
                
                <?php _e( $label, 'scrm' ); ?>
                
            </label>
            
            <?php if ( !empty( $desc ) ) : ?>
            
                <p id="<?php printf( '%s-description', $id ); ?>" 
                   class="description" 
                   style="font-weight: normal;">

                    <?php _e( $desc, 'scrm' ); ?>

                </p>
            
            <?php endif; ?>
            
        </th>
        
        <td class="forminp">
            
            <input id="<?php echo $id; ?>" 
                   type="<?php echo $type; ?>" 
                   name="<?php echo $name; ?>" 
                   <?php 
                   if ( $type == 'checkbox') {
                       
                       checked( $value, 1); 
                       $value = '1';
                   }
                   ?>
                   value="<?php echo $value; ?>" 
                   <?php echo $other; ?>/>
            
        </td>
        
    </tr>
    
    <?php
}

/**
 * Option custom field hidden
 * 
 * @param string $name
 * @param string $value
 */
function scrm_option_custom_field_hidden( $name, $value ) {
    ?>
    <input type="hidden" 
           name="<?php echo $name; ?>" 
           value="<?php echo $value; ?>" />
    <?php
}

/**
 * Option custom field input
 * 
 * @param string $label
 * @param string $name
 * @param string $type
 * @param string|int $value
 * @param string $other
 */
function scrm_option_custom_field_input( $label, $name, $type, $value, $other = '' ){
    ?>
    
    <p class="<?php printf( "field-%s", strtolower( $label ) ); ?>">
        
        <label for="<?php echo $name; ?>">
            
            <?php _e( $label, 'scrm' ); ?>
        
        </label>
        
        <input type="<?php echo $type; ?>" 
               name="<?php echo $name; ?>" 
               <?php 
               if ( $type == 'checkbox') {
                   
                   checked( $value, 1);
                   $value = '1';
               }
               ?>
               value="<?php echo $value; ?>" 
               <?php echo $other; ?>/>
    </p>
        
    <?php
}

/**
 * Option custom field radio
 * 
 * @param string $label
 * @param string $name
 * @param string|int $value
 * @param array $values
 */
function scrm_option_custom_field_radio( $label, $name, $value, $values,  $hidden = false ) {
    
    $value = !empty( $value ) ? $value : '0';
    ?>
    
    <p class="<?php printf( "field-%s", strtolower( $label ) ); ?>" <?php if ( $hidden ) echo 'style="display: none;"' ?>>
        
        <label for="<?php echo $name; ?>">
            
            <?php _e( $label, 'scrm' ); ?>
            
        </label>
        
        <?php foreach ( $values as $text => $item ) : ?>
        
            <input type="radio" 
                   name="<?php echo $name; ?>" 
                   value="<?php echo $item; ?>" 
                   <?php checked( $value, $item ); ?>/>
            <span>
                
                <?php _e( $text, 'scrm' ); ?>
                
            </span>
        
        <?php endforeach; ?>
        
    </p>
    
    <?php
}

/**
 * Option custom field textarea
 * 
 * @param string $label
 * @param string $name
 * @param string|int $value
 * @param string|null $other
 */
function scrm_option_custom_field_textarea( $label, $name, $value, $other = null ) {
    ?>
            
    <p class="<?php printf( "field-%s", strtolower( $label ) ); ?>">
        
        <label for="<?php echo $name; ?>">
            
            <?php _e( $label, 'scrm' ); ?>
            
        </label>
        
        <textarea name="<?php echo $name; ?>"<?php echo is_null( $other ) ? '' : $other; ?>><?php echo $value; ?></textarea>
    
    </p>
    
    <?php
}

/**
 * Option custom field select
 * 
 * @param string $label
 * @param string $name
 * @param string|int $value
 * @param array $values
 */
function scrm_option_custom_field_select( $label, $name, $value, $values ) {
    ?>
        
    <p class="<?php printf( "field-%s", strtolower( $label ) ); ?>">
            
        <label for="<?php echo $name; ?>">
            
            <?php _e( $label, 'scrm' ); ?>
            
        </label>
            
        <select name="<?php echo $name; ?>" >
                
            <?php scrm_option_custom_field_select_option( $value, $values ) ?>
                        
        </select>
            
    </p>
    
    <?php
}

/**
 * Option custom field select with groups
 * 
 * @param string $label
 * @param string $name
 * @param string|int $value
 * @param array $values
 * @param string $other
 */
function scrm_option_custom_field_select_group( $label, $name, $value, $values, $hidden = false ) {
    ?>
        
    <p class="<?php printf( "field-%s", strtolower( $label ) ); ?>">
            
        <label for="<?php echo $name; ?>">
            
            <?php _e( $label, 'scrm' ); ?>
            
        </label>
            
        <?php if ( $hidden ) : ?>
        
        <span class="type-built-in">
            <?php _e( ucfirst( $value ), 'scrm' ); ?>
        </span>
        
        <?php endif; ?>
        
        <select name="<?php echo $name; ?>" <?php if ( $hidden ) echo 'style="display: none;"'; ?>>
                
            <?php foreach ( $values as $group => $items ) : ?>
                
                <optgroup label="<?php _e( ucfirst( $group ), 'scrm' ); ?>">
                        
                    <?php scrm_option_custom_field_select_option( $value, $items ); ?>
                        
                </optgroup>
                    
            <?php endforeach; ?>
                        
        </select>
            
    </p>
    
    <?php
}

/**
 * Option custom field select option
 * 
 * @param string $value
 * @param array $values
 */
function scrm_option_custom_field_select_option( $value, $values ) {
                        
    // true - indexed 
    // false - associative
    $type = empty( preg_grep( '/[^0-9]/', array_keys( $values ) ) ) ? true : false;
    
    if ( $type ) {

        foreach ( $values as $item ) {
            ?>

            <option value="<?php echo $item; ?>" <?php selected( $value, $item, true ); ?>>
                
                <?php _e( ucfirst( $item ), 'scrm' ); ?>
                
            </option>

            <?php
        }
    } else {

        foreach ( $values as $key => $item ) {
            ?>

            <option value="<?php echo $key; ?>" <?php selected( $value, $key, true ); ?>>
                
                <?php _e( ucfirst( $item ), 'scrm' ); ?>
                
            </option>

            <?php
        }
    }
}

/**
 * Option custom field choices values
 * 
 * @param string $prefix
 * @param string $id
 * @param int $i
 * @param string $value
 * @param array $values
 * @param bool $locked
 */
function scrm_option_custom_field_choices( $prefix, $id, $i, $value, $values, $locked = false ) {
        
    $name = sprintf( "%s[%s][value][%u]", $prefix, $id, $i );
    scrm_option_custom_field_input( 'Value', $name, 'text', $value );
    
    $values = str_replace( [ '{', ',', '"', '}' ], [ "", "\n", "", "" ], json_encode( $values ) );
    
    $name = sprintf( "%s[%s][values][%u]", $prefix, $id, $i );
    $other = ( $locked ) ? ' readonly=""' : '';
    scrm_option_custom_field_textarea( 'Choices', $name, $values, $other );
}

/**
 * Option custom field values
 * 
 * @todo Help temporarily disables
 * 
 * @param string $prefix
 * @param string $id 
 * @param int $i
 * @param array $field
 */
function scrm_option_custom_field_values( $prefix, $id, $i, $field ) {
    
    $type = isset( $field[ 'type' ] ) ? $field[ 'type' ] : 'text';
    $name = sprintf( "%s[%s][value][%u]", $prefix, $id, $i );
    $value = !empty( $field[ 'value' ] ) ? $field[ 'value' ] : '';
    $values = !empty( $field[ 'values' ] ) ? $field[ 'values' ] : '';
    $help = '';
    $placeholder = false;
    ?>
                            
        <div class="field-values">
                                
            <?php
            switch ( $type ) {

                case 'text':
                    scrm_option_custom_field_input( 'Value', $name, 'text', $value );
                    $help = 'For text field';
                    $placeholder = true;
                    break;
                
                case 'date':
                    scrm_option_custom_field_input( 'Value', $name, 'date', $value );
                    $help = 'For date field';
                    break;

                case 'number':
                    if ( empty( $value ) )
                        $value = 0;
                    scrm_option_custom_field_input( 'Value', $name, 'number', $value );

                    $name = sprintf( "%s[%s][min][%u]", $prefix, $id, $i );
                    $min = isset( $field[ 'min' ] ) ? $field[ 'min' ] : '';
                    scrm_option_custom_field_input( 'Min', $name, 'number', $min );

                    $name = sprintf( "%s[%s][max][%u]", $prefix, $id, $i );
                    $max = isset( $field[ 'max' ] ) ? $field[ 'max' ] : '';
                    scrm_option_custom_field_input( 'Max', $name, 'number', $max );

                    $name = sprintf( "%s[%s][step][%u]", $prefix, $id, $i );
                    $step = isset( $field[ 'step' ] ) ? $field[ 'step' ] : '';
                    scrm_option_custom_field_input( 'Step', $name, 'number', $step );

                    $help = 'For number field';
                    break;

                case 'textarea':
                    scrm_option_custom_field_textarea( 'Value', $name, $value);
                    $help = 'For textarea field';
                    $placeholder = true;
                    break;

                case 'select':
                    scrm_option_custom_field_choices( $prefix, $id, $i, $value, $values );
                    $help = 'For select field';
                    break;

                case 'radio':
                    scrm_option_custom_field_choices( $prefix, $id, $i, $value, $values );
                    $help = 'For radio button field';
                    break;

                case 'checkbox':
                    $values = [
                        'Enable',
                        'Disable',
                    ];
                    scrm_option_custom_field_select( 'Value', $name, $value, $values );
                    $help = 'For checkbox field';
                    break;
                
                case 'users':
                    $values = scrm_list_users();
                    scrm_option_custom_field_choices( $prefix, $id, $i, $value, $values, true );
                    $help = 'For users field';
                    break;
            }
            ?>
<!--            
            <p class="help-field-values">

                <span class="help-label">
                    <?php #_e( 'Help', 'scrm' ); ?>
                </span>

                <span class="help-text">
                    <?php #_e( $help, 'scrm' ); ?>
                </span>

            </p>
-->            
            <?php
            if ( $placeholder ) {
                
                $name = sprintf( "%s[%s][placeholder][%u]", $prefix, $id, $i );
                $value = isset( $field[ 'placeholder' ] ) ? $field[ 'placeholder' ] : '';
                scrm_option_custom_field_input( 'Placeholder', $name, 'text', $value );
            }
            ?>
                    
        </div>
                            
    <?php
}

/**
 * Option custom field
 * 
 * @param string $prefix
 * @param string $id
 * @param int $i
 * @param array $field
 */
function scrm_option_custom_field( $prefix, $id, $i, $field = [] ) {
    
    $type_values = [ 
        'basic'     => [
            'text', 
            'date',
            'number', 
        ],
        'content'   => [
            'textarea',
        ],
        'choise'    => [
            'radio',
            'select',
            'checkbox',
        ],
        'relation'  => [
            'users',
        ],
    ];
    $required_values = $sorted_values = [
        'Yes'   => '1',
        'No'    => '0',
    ];
    ?>
        
    <tr class="custom-field<?php echo !empty( $field[ 'built-in' ] ) ? ' built-in' : ''; ?>">
        
        <td class="order">
            
            <span>*</span>
            
        </td>
        
        <td class="field">
            
            <?php
            $label_value = isset( $field[ 'label' ] ) ? $field[ 'label' ] : '';
            $name_value = isset( $field[ 'name' ] ) ? $field[ 'name' ] : '';
            $type_value = isset( $field[ 'type' ] ) ? $field[ 'type' ] : 'text';
            ?>
            
            <div class='field-title'>
                <p>
                    
                    <span class="label-info">
                        
                        <?php echo !empty( $label_value ) ? $label_value : '...'; ?>
                    
                    </span>
                    &nbsp; &HorizontalLine; &nbsp;
                    <span class="type-info">
                        
                        <?php _e( ucfirst( $type_value), 'scrm' ); ?>
                        
                    </span>
                    
                </p>
            </div>

            <div class="field-data" <?php echo !empty( $field ) ? 'style="display: none;"' : 'style="display: block;"'; ?>>
                
                <?php
                $built_in = isset( $field[ 'built-in' ] ) ? $field[ 'built-in' ] : '';
                
                $name = sprintf( "%s[%s][label][%u]", $prefix, $id, $i );
                scrm_option_custom_field_input( 'Label', $name, 'text', $label_value );
                        
                $name = sprintf( "%s[%s][name][%u]", $prefix, $id, $i );
                scrm_option_custom_field_input( 'Name', $name, 'text', $name_value, !empty( $built_in ) ? 'readonly=""' : '' );

                $name = sprintf( "%s[%s][type][%u]", $prefix, $id, $i );
                scrm_option_custom_field_select_group( 'Type', $name, $type_value, $type_values, !empty( $built_in ) ? true : false );

                // values
                scrm_option_custom_field_values( $prefix, $id, $i, $field );
                
                $name = sprintf( "%s[%s][required][%u]", $prefix, $id, $i );
                $value = isset( $field[ 'required' ] ) ? $field[ 'required' ] : 0;
                scrm_option_custom_field_radio( 'Required', $name, $value, $required_values );
                
                $name = sprintf( "%s[%s][sorted][%u]", $prefix, $id, $i );
                $value = isset( $field[ 'sorted' ] ) ? $field[ 'sorted' ] : 0;
                scrm_option_custom_field_radio( 'Sorted', $name, $value, $sorted_values, empty( $built_in ) ? true : false );
                
                $name = sprintf( "%s[%s][show][%u]", $prefix, $id, $i );
                $value = isset( $field[ 'show' ] ) ? $field[ 'show' ] : '';
                scrm_option_custom_field_input( 'Show', $name, 'checkbox', $value );
                
                if ( !empty( $built_in ) ) {
                    
                    $name = sprintf( "%s[%s][built-in][%u]", $prefix, $id, $i );
                    scrm_option_custom_field_hidden( $name, $built_in );
                }
                ?>
                
            </div>

        </td>
    </tr>
    
    <?php
}

/**
 * Option custom fields
 * 
 * @param string $prefix
 * @param string $id
 * @param array $fields
 * @param string $label
 * @param string $desc
 */
function scrm_option_custom_fields( $prefix, $id, $fields, $label, $desc ) {
    
    $i = 0;
    ?>
    
    <tr valign="top">
        
        <th scope="row" class="titledesc">
            
            <label>
                
                <?php _e( $label, 'scrm' ); ?>
                
            </label>
            
            <?php if ( !empty( $desc ) ) : ?>
            
                <p class="description" style="font-weight: normal;">

                    <?php _e( $desc, 'scrm' ); ?>

                </p>
            
            <?php endif; ?>
            
        </th>
        
        <td class="forminp" id="<?php echo $id; ?>">
            
            <table class="widefat scrm-fields-table" cellspacing="0">
                
                    <thead>
                        
                        <tr>
                            <th class="order">
                                <?php _e( 'Order', 'scrm' ); ?>
                            </th>
                            <th class="field">
                                <?php _e( 'Fields', 'scrm' ); ?>
                            </th>
                        </tr>
                        
                    </thead>
                    
                    <tbody id="sortable">
                        
                        <?php foreach ( $fields as $field ) : ?>
                                
                            <?php scrm_option_custom_field( $prefix, $id, $i++, $field ); ?>
                        
                        <?php endforeach; ?>
                        
                    </tbody>
                    
                    <tfoot>
                        
                        <tr>
                            
                            <th colspan="2">
                                <a href="#" class="add_row button">
                                    <?php _e( 'Add new field', 'scrm' ); ?>
                                </a> 
                                <a href="#" class="remove_row button">
                                    <?php _e( 'Remove selected field', 'scrm' ); ?>
                                </a>
                            </th>
                            
                        </tr>
                        
                    </tfoot>
                
            </table>
            
        </td>
        
    </tr>
    
    <script>
        
        var prefix = '<?php echo $prefix; ?>';
        var id = '<?php echo $id; ?>';
    
    </script>
    
    <?php
}
