<?php
const AW_CPN_PRIMARY_FIELD_NAME = 'primary_phone';

function aw_cpn_getPhoneFields() {
    return array('aw_mobile_phone' => 'Mobile Phone', 'aw_home_phone' => 'Home Phone', 'aw_work_phone' => 'Work Phone');
}

function aw_custom_phone_numbers_add_user_meta_fields() {
    $curr_user = wp_get_current_user();
    $user_id = $curr_user->ID;
    $aw_phone_fields = aw_cpn_getPhoneFields();
    $phone_numbers = array();

    foreach ($aw_phone_fields as $phone_key => $phone_name){
        add_user_meta($user_id, $phone_key, '', true );
        add_user_meta($user_id, $phone_key . '_country' , '', true );
        if($phone_key == 'aw_work_phone'){
            add_user_meta($user_id, 'aw_work_ext', '', true );
        }
    }
    add_user_meta($user_id, AW_CPN_PRIMARY_FIELD_NAME, '', true );
}
add_action('edit_user_profile_update', 'aw_custom_phone_numbers_add_user_meta_fields');
add_action('person_options_update', 'aw_custom_phone_numbers_add_user_meta_fields');

function aw_custom_phone_numbers_add_user_meta_form() {
    $curr_user = wp_get_current_user();
    $user_id = $curr_user->ID;
    $aw_phone_fields = aw_cpn_getPhoneFields();
    $phone_numbers = array();
    ?>
    <fieldset class="woocommerce_edit_account_form aw-custom-phone-numbers">
			<h3 class="aw-custom-phone-numbers">Phone Numbers</h3>
        <table class="woocommerce-EditAccountForm edit-account">
        <?php
        foreach ($aw_phone_fields as $phone_key => $phone_name){
                $phone_numbers[$phone_key] = get_user_meta($user_id, $phone_key, true );
                $primary_phone = get_user_meta($user_id, AW_CPN_PRIMARY_FIELD_NAME, true );
                $country = get_user_meta($user_id, $phone_key . '_country' , true );
                $ext = get_user_meta($user_id, 'aw_work_ext', true );
        ?>
            <tr class="woocommerce-form-row" >
							<td class="country-select">
		            <select name=<?php echo $phone_key . '_country' ?> class="country" data-value=<?php echo $phone_key ?>  >
		                <option id="usa" value="+1"   <?php if($country == '+1'){echo 'selected="selected" ' ; } ?> >USA (+1)</option>
		                <option id="uk" value="+44"  <?php if($country == '+44'){echo 'selected="selected" ' ; } ?> >UK (+44)</option>
		                <option id="china" value="+86"  <?php if($country == '+86'){echo 'selected="selected" ' ; } ?> >China (+86)</option>
		            </select>
                <label for=<?php echo $phone_key . '_country' ?> >Choose A Country</label>
            	</td>
							<td id="parent" class="phone-number-input" >
								<label for=<?php echo $phone_key; ?> class="phone-name-header"><?php echo $phone_name; ?> </label>
	                <input type="tel"
	                    id=<?php echo $phone_key; ?>
	                    class="phone-number woocommerce-Input"
	                    value="<?= esc_attr($phone_numbers[$phone_key]); ?>"
	                    name=<?php echo $phone_key; ?>
	                    placeholder="<?= " " . $phone_name . " Number " ; ?>"
	                    value="<?= esc_attr($phone_numbers[$phone_key]); ?>"
	                    data-mask-clearifnotmatch="true"
	                    data-value=<?php echo $country ?>
	                />
	                <div id="phone-err-msg" class="phone-err-msg hidden" >
	                    Please enter a valid phone number.
	                </div>
	                <?php if($phone_key == 'aw_work_phone'){ ?>
	                    <td class="work_ext">
												<label for="aw_work_ext" >EXT</label>
	                    <input type="text"
	                        class="woocommerce-Input"
	                        id='work_ext'
	                        name='aw_work_ext'
	                        value=<?php echo $ext ?>
	                    /> </td>
	                <?php } else { echo "<td class='blank'></td>"; }?>
                 </td>
                 <td class="primary-phone">
									 <label for="primary_phone"> Primary </label>
										<input type="radio"
                        class="primary_phone woocommerce-Input"
                      	name=<?php echo AW_CPN_PRIMARY_FIELD_NAME; ?>
                        id=<?php echo $phone_key . '_' . AW_CPN_PRIMARY_FIELD_NAME; ?>
                        <?php if(isset($phone_key) && ($phone_key == $primary_phone)) echo 'checked'; ?>
                        value=<?php echo $phone_key; ?>
                    />
                </td>
            </tr>
        <?php
        } // end foreach
        ?>
    </table>
    </fieldset>
    <?php
}
add_action('show_user_profile', 'aw_custom_phone_numbers_add_user_meta_form');
add_action('edit_user_profile', 'aw_custom_phone_numbers_add_user_meta_form');
add_action('woocommerce_edit_account_form', 'aw_custom_phone_numbers_add_user_meta_form');

function aw_custom_phone_numbers_form_update(){
    $curr_user = wp_get_current_user();
    $user_id = $curr_user->ID;
    $aw_phone_fields = aw_cpn_getPhoneFields();
    $primary_orig = get_user_meta($user_id, AW_CPN_PRIMARY_FIELD_NAME, true );

    foreach ($aw_phone_fields as $phone_key => $phone_name) {
        $country_data = $phone_key . '_country';
        if (isset($_POST[$phone_key])) {
            update_user_meta($user_id, $phone_key, $_POST[$phone_key]);

            if($phone_key == 'aw_work_phone'){
							if(isset($_POST['aw_work_ext']) && is_int($_POST['aw_work_ext']) ){
                update_user_meta($user_id, 'aw_work_ext', $_POST['aw_work_ext'] );
							}
            }
        } // end if
        if(isset($_POST[$country_data])) {
            update_user_meta($user_id, $phone_key . '_country' , $_POST[$country_data] );
        }
    }
    if( isset($_POST[AW_CPN_PRIMARY_FIELD_NAME]) ) {
        if( $_POST[AW_CPN_PRIMARY_FIELD_NAME] != $primary_orig ){
            update_user_meta($user_id, AW_CPN_PRIMARY_FIELD_NAME, $_POST[AW_CPN_PRIMARY_FIELD_NAME] );
        }
    }
}
add_action('profile_update', 'aw_custom_phone_numbers_form_update');
add_action('woocommerce_save_account_details', 'aw_custom_phone_numbers_form_update');
