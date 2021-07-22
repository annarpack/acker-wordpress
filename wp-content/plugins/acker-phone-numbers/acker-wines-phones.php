<?php
/*
Plugin Name: Acker Wines // Custom Phone Numbers
Plugin URI: https://www.ackerwines.com/
Description: Acker Wines WooCommerce
Version: 1.0
Author: Acker Wines // Anna
Author URI: https://www.ackerwines.com/
*/
function aw_data_mask_init() {
    aw_shared_plugin_init();
    wp_enqueue_script('aw-chosen-jquery-js');
    wp_enqueue_script('aw-jquery-ui-js-1.12.1');
    wp_enqueue_script('aw-jquery-mask-js');
    wp_register_script('aw-data-mask-js', plugins_url('js/aw_data_mask.js', __FILE__), array('jquery', 'aw-jquery-mask-js'));
    wp_enqueue_script('aw-data-mask-js');

    wp_enqueue_style('aw-jquery-ui-css-1.12.1');
    wp_enqueue_style('aw-jquery-dataTables-css');
}
add_action('init', 'aw_data_mask_init');

function aw_get_phone_fields() {
    return array('home_phone' => 'Home Phone', 'mobile_phone' => 'Mobile Phone', 'work_phone' => 'Work Phone');
}

function aw_usermeta_phones_add() {
    $curr_user = wp_get_current_user();
    $user_id = $curr_user->ID;
    $aw_phone_fields = aw_get_phone_fields();
    $phone_numbers = [];

    foreach ($aw_phone_fields as $phone_key => $phone_name){
        add_user_meta($user_id, $phone_key, '', true );
    }
}
add_action('edit_user_profile_update', 'aw_usermeta_phones_add');
add_action('person_options_update', 'aw_usermeta_phones_add');


function aw_usermeta_phones_form() {
    $curr_user = wp_get_current_user();
    $user_id = $curr_user->ID;
    $aw_phone_fields = aw_get_phone_fields();
    $phone_numbers = [];
?>
    <style>
    .phone-error-msg {
        color: red;
    }
    .hidden {
        display: none;
    }
    .show {
        height: 15px;
        display: block;
    }
    .ui-state-default,  .ui-widget, span.ui-selectmenu-button {
        display: inline-block;
        min-width: 300px;
        font-size: 1.25em;
        padding: 6px 0px 6px 6px;
        line-height: 1.5em;
    }
     .ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active, a.ui-button:active, .ui-button:active, .ui-button.ui-state-active:hover, .ui-state-hover, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus, .ui-widget-header .ui-state-focus, .ui-button:hover, .ui-button:focus {
        color: #FFF;
        border: 0;
        background: #6f263d;
        background-color: #6f263d;
    }
    .ui-selectmenu-button, .ui-corner-top, .ui-state-hover, .ui-state-focus {
        border: 0;
        background: #e4e4e4;
        color: #000;
        background-color: #e4e4e4;
    }
    .ui-state-default .ui-icon {
        color: #000;
        background: url('./arrow-down-solid.svg');
    }
    </style>
     <h3> Add Extra Phone Numbers</h3>

     <fieldset>
         <table class="form-table">
         <div class="user-phone-dropdown">
         <tr><th>
         <label for="phone-country">Choose A Country</label></br>
         </th><td>
         <select name="country" id="country">
             <option value="+1" selected="selected" >USA (+1)</option>
             <option value="+44" >UK (+44)</option>
             <option value="+86" >China (+86)</option>
         </select>
        </div>
        <?php
        foreach ($aw_phone_fields as $phone_key => $phone_name){
            $phone_numbers[$phone_key] = get_user_meta($user_id, $phone_key, true );
        ?>
        <tr>
            <th>
            <label for=<?php echo $phone_key; ?>><?php echo $phone_name; ?> </label>
            </th>
            <td>
            <input type="tel"
                id=<?php echo $phone_key; ?>
                class="phone-number"
                value="<?= esc_attr($phone_numbers[$phone_key]); ?>"
                name=<?php echo $phone_key; ?>
                placeholder="<?= " " . $phone_name . " Number " ; ?>"
                value="<?= esc_attr($phone_numbers[$phone_key]); ?>"
                data-mask-clearifnotmatch="true"
                data-mask='+1 (999) 999-9999'
            />
             </td>
             <th>
            <input type="radio"
                id="primary-phone"
                name="primary-phone"
                value=<?php echo $phone_key; ?>
                data=<?php //if( $is_primary!== false ){ echo $checked; } else{ echo '' ; } ?>
            />
            <label for="primary-phone-number"> Primary </label>
             </th>
        </tr>
        <?php
        }
        ?>
        </table>
    </fieldset>
<!-- <div id="phone-err-msg" class='phone-err-msg' style="color:red; font-size: 18px; padding: 0.5em; margin-bottom: 0.5em;">
    Please enter a valid phone number.
</div> -->
<?php

}
add_action('show_user_profile', 'aw_usermeta_phones_form');
add_action('edit_user_profile', 'aw_usermeta_phones_form');
add_action('woocommerce_edit_account_form', 'aw_usermeta_phones_form');


function aw_usermeta_phones_form_update(){
    $curr_user = wp_get_current_user();
    $user_id = $curr_user->ID;
    $aw_phone_fields = aw_get_phone_fields();
    foreach ($aw_phone_fields as $phone_key => $phone_name) {
    if (isset($_POST[$phone_key])) {
        update_user_meta($user_id, $phone_key, $_POST[$phone_key]);
    }
}

}
add_action('profile_update', 'aw_usermeta_phones_form_update');
add_action('woocommerce_save_account_details', 'aw_usermeta_phones_form_update');
