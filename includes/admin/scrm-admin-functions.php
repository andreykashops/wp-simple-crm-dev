<?php
/** 
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 10.04.2018
 */

defined( 'ABSPATH' ) || exit;

/**
 * Meta box prefix
 */
function scrm_prefix( $prefix ) {
    
    $prefix = str_replace( '_', '-', $prefix );
    
    return $prefix;
}

/**
 * Get contacts
 */
function scrm_get_contacts() {
    
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
 */
function scrm_get_users() {
    
    $list = [];
    
    $users = get_users();
    foreach ( $users as $user ) 
        $list[ $user->data->ID ] = $user->data->display_name;
    
    wp_reset_postdata();
    
    return $list;
}

/**
 * Option start section
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
 */
function scrm_option_custom_field_input( $label, $name, $type, $value ){
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
               value="<?php echo $value; ?>" />
    </p>
        
    <?php
}

/**
 * Option custom field radio
 */
function scrm_option_custom_field_radio( $label, $name, $value, $values ) {
    
    $value = !empty( $value ) ? $value : '0';
    ?>
    
    <p class="<?php printf( "field-%s", strtolower( $label ) ); ?>">
        
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
 */
function scrm_option_custom_field_select_group( $label, $name, $value, $values ) {
    ?>
        
    <p class="<?php printf( "field-%s", strtolower( $label ) ); ?>">
            
        <label for="<?php echo $name; ?>">
            
            <?php _e( $label, 'scrm' ); ?>
            
        </label>
            
        <select name="<?php echo $name; ?>" >
                
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
                    $values = scrm_get_users();
                    scrm_option_custom_field_choices( $prefix, $id, $i, $value, $values, true );
                    $help = 'For users field';
                    break;
            }
            ?>
            
            <p class="help-field-values">

                <span class="help-label">
                    <?php _e( 'Help', 'scrm' ); ?>
                </span>

                <span class="help-text">
                    <?php _e( $help, 'scrm' ); ?>
                </span>

            </p>
            
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
                $name = sprintf( "%s[%s][label][%u]", $prefix, $id, $i );
                scrm_option_custom_field_input( 'Label', $name, 'text', $label_value );
                        
                $name = sprintf( "%s[%s][name][%u]", $prefix, $id, $i );
                scrm_option_custom_field_input( 'Name', $name, 'text', $name_value );

                $name = sprintf( "%s[%s][type][%u]", $prefix, $id, $i );
                scrm_option_custom_field_select_group( 'Type', $name, $type_value, $type_values );

                // values
                scrm_option_custom_field_values( $prefix, $id, $i, $field );
                
                $name = sprintf( "%s[%s][required][%u]", $prefix, $id, $i );
                $value = isset( $field[ 'required' ] ) ? $field[ 'required' ] : 0;
                scrm_option_custom_field_radio( 'Required', $name, $value, $required_values );
                
                $name = sprintf( "%s[%s][sorted][%u]", $prefix, $id, $i );
                $value = isset( $field[ 'sorted' ] ) ? $field[ 'sorted' ] : 0;
                scrm_option_custom_field_radio( 'Sorted', $name, $value, $sorted_values );
                
                $name = sprintf( "%s[%s][show][%u]", $prefix, $id, $i );
                $value = isset( $field[ 'show' ] ) ? $field[ 'show' ] : '';
                scrm_option_custom_field_input( 'Show', $name, 'checkbox', $value );
                
                $value = isset( $field[ 'built-in' ] ) ? $field[ 'built-in' ] : '';
                if ( !empty( $value ) ) {
                    
                    $name = sprintf( "%s[%s][built-in][%u]", $prefix, $id, $i );
                    scrm_option_custom_field_hidden( $name, $value );
                }
                ?>
                
            </div>

        </td>
    </tr>
    
    <?php
}

/**
 * Option custom fields
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
