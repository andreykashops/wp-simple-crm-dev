<?php
/**
 * Created by Roman Hofman
 * Date: 06.04.2018
 */
defined( 'ABSPATH' ) || exit;

/**
 * Meta box prefix
 */
function scrm_prefix() {
    
    $prefix = str_replace( '_', '-', get_post_type() );
    
    return $prefix;
}

/**
 * Meta box block
 */
function scrm_metabox_block( $block ) {
    
    $prefix = scrm_prefix();
    
    echo $prefix, '-block ', $prefix, '-block-', $block;
    
    return ++$block;
}

/**
 * Output a field box
 */
function scrm_metabox_field( $id, $value, $lable ) {

    $prefix = scrm_prefix();
    $id = esc_attr( $id );
    $value = esc_attr( $value );
    $type = 'text';
    $data = ''; // placeholder, required and more attributes...

    switch ( $id ) {
        case 'first-name':
        case 'phone':
            $data = 'required=""';
            break;
        case 'possible-amount':
            $type = 'number';
            $data = 'min="0" step="1" max="100000"';
            break;
        case 'access-for-all':
            $type = 'checkbox';
            break;
        default :
            break;
    }
    
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
function scrm_metabox_field_textarea( $id, $value, $lable ) {

    $prefix = scrm_prefix();
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
function scrm_metabox_field_select( $id, $value, $lable ) {

    $prefix = scrm_prefix();
    $id = esc_attr( $id );
    $value = esc_attr( $value );
    $items = '';
    $data = '';

    switch ( $id ) {
        case 'status':
            $items = [ 'Not Processed', 'Start', 'Progress 25%', 'Progress 50%', 'Progress 75%', 'End', 'Success', 'Failure' ];
            // get_option( 'scrm_status_list' );
            break;
        case 'source':
            $items = [ 'phone', 'email', 'other' ];
            // get_option( 'scrm_source_list' );
            break;
        case 'responsible':
            $users = get_users();
            foreach ( $users as $user ) {

                $items[ $user->data->ID ] = $user->data->display_name;
            }
            wp_reset_postdata();
            break;
        case 'currency':
            $items = [ 'EURO', 'USD', 'UAH', 'RUB' ];
            // get_option( 'scrm_currency_list' );
            break;
        case 'contact':
            $posts = get_posts( [ 'posts_per_page' => -1, 'post_type' => 'scrm_contact' ] );
            foreach ( $posts as $post ) {

                $items[ $post->ID ] = $post->post_title;
            }
            wp_reset_postdata();
            break;
        default :
            break;
    }
    ?>

    <p id="<?php echo $prefix; ?>-field-<?php echo $id; ?>" 
       class="<?php echo $prefix; ?>-field <?php echo $prefix; ?>-field-select">

        <label for="<?php echo $prefix, '-', $id; ?>">
            <?php _e( $lable, 'scrm' ); ?>
        </label>

        <select id="<?php echo $prefix, '-', $id; ?>-select" 
                class="<?php echo $prefix; ?>-input" 
                name="<?php echo str_replace( '-', '_', $prefix ), '[', $id, ']'; ?>">

            <?php
            switch ( $id ) {

                case 'responsible':
                case 'contact':
                    foreach ( $items as $key => $item ) {
                        
                        if ( selected( $value, $key, false ) ) {
                            $data = $key;
                        }
                        ?>

                        <option value="<?php echo $key; ?>" <?php selected( $value, $key, true ); ?>>
                            <?php echo $item; ?>
                        </option>

                        <?php
                    }
                    break;

                default :
                    foreach ( $items as $item ) {
                        ?>

                        <option value="<?php echo $item; ?>" <?php selected( $value, $item, true ); ?>>
                            <?php echo $item; ?>
                        </option>

                        <?php
                    }
                    break;
            }
            ?>

        </select>

    </p>

    <?php
    
    return $data;
}

/**
 * Output a contact info
 */
function scrm_meta_contact_info( $post_id ) {
    
    $contact_info = get_post_meta( $post_id, 'scrm_contact', true );
    
    $fields = [
        'Primary'   => [
            'First name'    => 'first-name',
            'Last name'     => 'last-name',
            'Middle name'   => 'middle-name',
            'Phone'         => 'phone',
            'Email'         => 'email',
            'Birthday'      => 'birthday',
        ],
        'Secondary' => [
            'Site'          => 'site',
            'Company'       => 'company',
            'Position'      => 'position',
            'Facebook'      => 'facebook',
            'Vkontakte'     => 'vk',
            'Twitter'       => 'twitter',
            'Odnoklasniki'  => 'ok',
        ],
        'Address'   => [
            'Country'       => 'country',
            'City'          => 'city',
            'Street'        => 'street',
            'Building'      => 'building',
            'Office'        => 'office',
        ],
    ];

    foreach ( $fields as $title => $items ) : 
    ?>
    
        <article>
            
            <h2><?php _e( $title, 'scrm' ); ?></h2>
        
            <ul>

                <?php foreach ( $items as $key => $value ) : ?>

                    <li>
                        <span><?php _e( $key, 'scrm' ); ?> : </span><?php echo $contact_info[ $value ]; ?>
                    </li>

                <?php endforeach; ?>

            </ul>
            
        </article>

    <?php 
    endforeach;
}
