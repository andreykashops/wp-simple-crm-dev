<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 06.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * Meta box block
 */
function scrm_metabox_block( $prefix, $block ) {
    
    printf( '%1$s-block %1$s-block-%2$s', $prefix, $block );
    
    return ++$block;
}

/**
 * Meta box group
 */
function scrm_metabox_group( $prefix, $group ) {
    
    printf( '%1$s-group %1$s-group-%2$s', $prefix, $group );
}

/**
 * Output a field box input
 */
function scrm_metabox_field_input( $prefix, $id, $value, $lable, $type = 'text', $other = '' ) {

    $id = esc_attr( $id );
    $value = esc_attr( $value );
    
    switch ( $type ) {
        case 'number':
            $value = !empty( $value ) ? $value : '0';
            break;
        case 'checkbox':
            $other .= ' ' . checked( $value, 1, false );
            $value = '1';
            break;
    }
    ?>

    <p id="<?php printf( '%s-field-%s', $prefix, $id ); ?>" 
       class="<?php printf( '%1$s-field %1$s-field-input', $prefix ); ?>">

        <label for="<?php printf( "%s-%s", $prefix, $id ); ?>">
            <?php _e( $lable, 'scrm' ); ?>
        </label>

        <input id="<?php printf( "%s-%s", $prefix, $id ); ?>" 
               class="<?php printf( "%s-input", $prefix );?>"
               name="<?php printf( "%s[%s]", str_replace( '-', '_', $prefix ), $id ); ?>"
               type="<?php echo $type; ?>" 
               value="<?php echo $value; ?>"
               <?php echo $other; ?>/>

    </p>

    <?php
}

/**
 * Option a field box radio
 */
function scrm_metabox_field_radio( $prefix, $id, $label, $value, $values ) {
    
    $value = !empty( $value ) ? $value : '0';
    ?>
    
    <p class="<?php echo $prefix; ?>-field-<?php echo $id; ?>"
       class="<?php echo $prefix; ?>-field <?php echo $prefix; ?>-field-radio">
        
        <label for="<?php printf( "%s-%s", $prefix, $id ); ?>">
            
            <?php _e( $label, 'scrm' ); ?>
            
        </label>
        
        <?php foreach ( $values as $item => $text ) : ?>
        
            <input id="<?php printf( "%s-%s-%s", $prefix, $id, strtolower( $text ) ); ?>"
                   class="<?php printf( "%s-radio", $prefix ); ?>" 
                   type="radio" 
                   name="<?php printf( "%s[%s]", str_replace( '-', '_', $prefix ), $id ); ?>" 
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
 * Output a field box textarea
 */
function scrm_metabox_field_textarea( $prefix, $id, $value, $lable ) {

    $id = esc_attr( $id );
    $value = esc_attr( $value );
    $data = ''; // placeholder, required and more attributes...

    switch ( $id ) {
        case 'comment':
            $data = 'rows="4"';
            break;
        default :
            break;
    }
    ?>

    <p class="<?php echo $prefix; ?>-field <?php echo $prefix; ?>-field-textarea">

        <label for="<?php echo $prefix, '-', $id; ?>">
            <?php _e( $lable, 'scrm' ); ?>
        </label>

        <textarea id="<?php echo $prefix, '-', $id; ?>" 
                  class="<?php echo $prefix; ?>-textarea"
                  name="<?php echo str_replace( '-', '_', $prefix ), '[', $id, ']'; ?>"
                  <?php echo $data; ?>><?php echo $value; ?></textarea>

    </p>

    <?php
}

/**
 * Output a field box select
 */
function scrm_metabox_field_select( $prefix, $id, $value, $lable, $items, $indexed = true ) {
    
    $id = esc_attr( $id );
    $value = esc_attr( $value );
    ?>

    <p id="<?php echo $prefix; ?>-field-<?php echo $id; ?>" 
       class="<?php echo $prefix; ?>-field <?php echo $prefix; ?>-field-select">

        <label for="<?php echo $prefix, '-', $id; ?>">
            <?php _e( $lable, 'scrm' ); ?>
        </label>

        <select id="<?php echo $prefix, '-', $id; ?>" 
                class="<?php echo $prefix; ?>-select" 
                name="<?php echo str_replace( '-', '_', $prefix ), '[', $id, ']'; ?>">

            <?php
            if ( $indexed ) {
                
                foreach ( $items as $item ) {
                    ?>
            
                    <option value="<?php echo $item; ?>" <?php selected( $value, $item, true ); ?>>
                        <?php echo $item; ?>
                    </option>

                    <?php
                }
            } else {
                
                foreach ( $items as $key => $item ) {
                    ?>
            
                    <option value="<?php echo $key; ?>" <?php selected( $value, $key, true ); ?>>
                        <?php echo $item; ?>
                    </option>

                    <?php
                }
            }
            ?>

        </select>

    </p>

    <?php
}

/**
 * Output thumbnail meta-box
 */
function scrm_metabox_field_thumbnail( $prefix, $post_id ) {
    
    $post  = get_post( $post_id );

    $post_type_object = get_post_type_object( $prefix );
    
    $image_id = get_post_meta( $post_id, '_thumbnail_id', true );
        
    if ( $image_id && get_post( $image_id ) ) {
        
        $image = wp_get_attachment_image_src( $image_id, 'full' );
    }
    ?>
    
    <img class="attachment-post-thumbnail size-post-thumbnail" 
         src="<?php echo isset( $image ) ? esc_attr( $image[0] ) : ''; ?>" 
         width="<?php echo isset( $image ) ? esc_attr( $image[1] ) : '0'; ?>" 
         height="<?php echo isset( $image ) ? esc_attr( $image[2] ) : '0'; ?>" 
         style="border:0; display:<?php echo isset( $image ) ? 'inline-block' : 'none'; ?>;" />
    
    <p class="hide-if-no-js">
        
        <a id="<?php echo isset( $image ) ? 'remove_lead_contact_image' : 'upload_lead_contact_image'; ?>" 
           title ="Contact image" 
           href="javascript:;">
            
            <?php
            if ( isset( $image ) ) {
                
                echo $post_type_object->labels->remove_featured_image; 
            } else {
                
                echo $post_type_object->labels->set_featured_image;
            }
            ?>
            
        </a>
            
    </p>
        
    <input id="scrm-contact-thumbnail-id" 
           type="hidden" 
           name="<?php echo $prefix; ?>[thumbnail-id]" 
           value="<?php echo esc_attr( $image_id ); ?>" />
    
    <?php
}

/**
 * Metabox fields load
 */
function scrm_metabox_fields_load( $post_id, $class, $block = 1, $hide = [] ) {
            
    $meta = get_post_meta( $post_id, $class::$type, true );
        
    $prefix = scrm_prefix( $class::$type );
    
    $fields = $class::fields();
    ?>
    
    <div class="<?php $block = scrm_metabox_block( $prefix, $block ); ?>">

        <?php foreach ( $fields as $group => $items ) : ?>
                
            <?php if ( in_array( $group, $hide ) ) continue; ?>
        
            <div class="<?php scrm_metabox_group( $prefix, $group ); ?>">

                <?php
                foreach ( $items as $lable => $id ) 
                    $class::metabox( $prefix, $id, isset( $meta[ $id ] ) ? $meta[ $id ] : '', $lable );
                ?>

            </div>

        <?php endforeach; ?>

    </div>
    
    <?php
    
    return $block;
}

/**
 * Metabox fields save
 */
function scrm_metabox_fields_save( $post_id, $class, $meta ) {
    
    $fields = $class::fields();
    
    foreach ( $fields as $items ) {
        
        foreach ( $items as $id ) {
            
            $meta[ $id ] = isset( $meta[ $id ] ) ? sanitize_text_field( $meta[ $id ] ) : '';
        }
    }
    
    update_post_meta( $post_id, $class::$type, wp_parse_args( $meta, get_post_meta( $post_id, $class::$type, true ) ) );
}

/**
 * Metabox custom fields load
 */
function scrm_metabox_custom_fields_load( $post_id, $prefix, $block = 1 ) {

    $meta = get_post_meta( $post_id, $prefix, true );
    
    $option = get_option( str_replace( '_', '_settings_', $prefix ) );
    
    $prefix = scrm_prefix( $prefix );
    
    if ( !empty( $option ) ) :
    ?>
    
        <div class="<?php $block = scrm_metabox_block( $prefix, $block ); ?>">

            <?php foreach ( $option as $group => $fields ) : ?>

                <div class="<?php scrm_metabox_group( $prefix, $group ); ?>">

                    <?php
                    foreach ( $fields as $field ) {

                        if ( isset( $field[ 'show' ] ) ) {

                            $label = $field[ 'label' ];
                            $id = $field[ 'name' ];
                            $type = $field[ 'type' ];
                            $value = isset( $meta[ $id ] ) ? $meta[ $id ] : $field[ 'value' ];
                            $other = '';

                            if ( $field[ 'required' ] )
                                $other .= ' required=""';

                            if ( isset( $field[ 'placeholder' ] ) && !empty( $field[ 'placeholder' ] ) ) 
                                $other .= sprintf( ' placeholder="%s"', $field[ 'placeholder' ] );

                            switch ( $type ) {
                                case 'text':
                                case 'date':
                                    scrm_metabox_field_input( $prefix, $id, $value, $label, $type, $other );
                                    break;
                                case 'number':
                                    scrm_metabox_field_input( $prefix, $id, $value, $label, $type );
                                    break;
                                case 'textarea':
                                    scrm_metabox_field_textarea( $prefix, $id, $value, $label );
                                    break;
                                case 'select':
                                    $items = $field[ 'values' ];
                                    scrm_metabox_field_select( $prefix, $id, $value, $label, $items, false );
                                    break;
                                case 'radio':
                                    $values = $field[ 'values' ];
                                    scrm_metabox_field_radio( $prefix, $id, $label, $value, $values );
                                    break;
                                case 'checkbox':
                                    scrm_metabox_field_input( $prefix, $id, $value, $label, $type );
                                    break;
                                case 'users':
                                    $items = scrm_get_users();
                                    scrm_metabox_field_select( $prefix, $id, $value, $label, $items, false );
                                    break;
                            }
                        }
                    }
                    ?>

                </div>

            <?php endforeach; ?>

        </div>
        
    <?php
    else:
    ?>    
        <p>
            <a href="<?php echo admin_url( 'admin.php?page=scrm_settings&tab=' . explode( '-', $prefix )[1] ); ?>">
                <?php _e( 'Please create and save custom fields.', 'scrm' ); ?>
            </a>
        </p>
    <?php
    endif;
    
    return $block;
}

/**
 * Metabox custom fields save
 */
function scrm_metabox_custom_fields_save( $post_id, $prefix, $meta ) {
    
    foreach ( $meta as $key => $value ) {
        
        $meta[ $key ] = sanitize_text_field( $value );
    }
    
    update_post_meta( $post_id, $prefix, $meta );
}
