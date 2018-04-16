<?php
/**
 * Project manager: Andrey Pavluk
 * Created by Roman Hofman
 * Date: 06.04.2018
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
 * Meta box block
 */
function scrm_metabox_block( $prefix, $block ) {
    
    echo $prefix, '-block ', $prefix, '-block-', $block;
    
    return ++$block;
}

/**
 * Meta box group
 */
function scrm_metabox_group( $prefix, $group ) {
    
    echo $prefix, '-group ', $prefix, '-group-', $group;
}

/**
 * Output a field box input
 */
function scrm_metabox_field_input( $prefix, $id, $value, $lable, $type = 'text', $data = '' ) {

    $id = esc_attr( $id );
    $value = esc_attr( $value );
    
    if ( $type == 'number' ) {
        
        $value = !empty( $value ) ? $value : '0';
    } elseif ( $type == 'checkbox') {
        
        $data = checked( $value, 1, false );
        $value = '1';
    }
    ?>

    <p id="<?php echo $prefix; ?>-field-<?php echo $id; ?>" 
       class="<?php echo $prefix; ?>-field <?php echo $prefix; ?>-field-input">

        <label for="<?php echo $prefix, '-', $id; ?>">
            <?php _e( $lable, 'scrm' ); ?>
        </label>

        <input id="<?php echo $prefix, '-', $id; ?>" 
               class="<?php echo $prefix; ?>-input"
               name="<?php echo str_replace( '-', '_', $prefix ), '[', $id, ']'; ?>"
               type="<?php echo $type; ?>" 
               value="<?php echo $value; ?>"
               <?php echo $data; ?>/>

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
 * Output a meta info
 */
function scrm_get_meta_boxes( $post_id, $class, $block = 1, $hide = [] ) {
            
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
 * Save a meta info
 */
function scrm_set_meta_data( $post_id, $class, $meta ) {
    
    $fields = $class::fields();
    
    foreach ( $fields as $items ) {
        
        foreach ( $items as $id ) {
            
            $meta[ $id ] = isset( $meta[ $id ] ) ? sanitize_text_field( $meta[ $id ] ) : '';
        }
    }
    
    update_post_meta( $post_id, $class::$type, wp_parse_args( $meta, get_post_meta( $post_id, $class::$type, true ) ) );
}
