<?php
		function aw_location_get_fields(){
			return array(
				'aw_us_location' => 'United States',
				'aw_hk_location' => 'Hong Kong'
			);

		}
		function aw_location_add_user_meta_fields() {
		    $curr_user = wp_get_current_user();
		    $user_id = $curr_user->ID;
				add_user_meta($user_id, 'aw_profile_location', 'default', true );
		}

		function aw_location_add_user_meta_form() {
		    $curr_user = wp_get_current_user();
		    $user_id = $curr_user->ID;
				$location_fields = aw_location_get_fields();
				$locations = array();
				echo '<p class="woocommerce_edit_account_form aw-location">
					<label>Location Region Display</label>
					<select name="aw_profile_location" >';
					foreach($location_fields as $location_key => $location_name){
						$locations[$location_key] = get_user_meta($user_id, $location_key, true );
						//var_dump(get_user_meta($user_id, $location_key, true ));
						echo '<option name="' . $location_key . '" name="' . $location_key . '" value="' . $location_key . '"> ' . $location_name . ' </option>';
					}
					echo '</select>
				</p>';
		}

		function aw_location_user_meta_update() {
			$curr_user = wp_get_current_user();
			$user_id = $curr_user->ID;
				if (isset($_POST['aw_profile_location'])){
					update_user_meta($user_id, 'aw_profile_location', $_POST['aw_profile_location'] );
				}
		}
		add_action('edit_user_profile_update', 'aw_location_add_user_meta_fields', 10, 1);
		add_action('person_options_update', 'aw_location_add_user_meta_fields', 10, 1);

		add_action('show_user_profile', 'aw_location_add_user_meta_form', 10, 1);
		add_action('edit_user_profile_update', 'aw_location_add_user_meta_form', 10, 1);
		add_action('woocommerce_edit_account_form', 'aw_location_add_user_meta_form', 10, 1);

		add_action('profile_update', 'aw_location_user_meta_update');
		add_action('woocommerce_save_account_details', 'aw_location_user_meta_update');


		function aw_email_preferences_get_fields(){
			return array(
				'aw_wine_workshop_email' => 'Wine Workshop',
				'aw_acker_asia_email' => 'Acker Asia',
				'aw_fine_rare_email' => 'Fine & Rare Retail',
				'aw_vintage_tastings_email' => 'Vintage Tastings',
				'aw_auction_highlights_email' => 'Auction Highlights',
				'aw_specials_email' => 'Store Specials'
			);
		}
		function aw_email_preferences_get_defaults(){
			return array(
				'aw_wine_workshop_email' => '0',
				'aw_acker_asia_email' => '0',
				'aw_fine_rare_email' => '0',
				'aw_vintage_tastings_email' => '0',
				'aw_auction_highlights_email' => '0',
				'aw_specials_email' => '0'
			);
		}
		function aw_email_preferences_add_user_meta_fields() {
		    $curr_user = wp_get_current_user();
		    $user_id = $curr_user->ID;
				$email_pref_fields = aw_email_preferences_get_fields();
				$pref = array();
				foreach($email_pref_fields as $email_key => $email_name){
					$option = array($email_key => '0');
					array_push($pref, $option);
				}
				$pref = json_encode($pref);
				add_user_meta($user_id, 'aw_email_notifications', $pref, true );
				// $email_pref_defaults = aw_email_preferences_get_defaults();
				// $pref = json_encode($email_pref_defaults);
				//add_user_meta($user_id, 'aw_email_notifications', $pref, true );
		}

		function aw_email_preferences_add_user_meta_form() {
		    $curr_user = wp_get_current_user();
		    $user_id = $curr_user->ID;
				$email_pref_fields = aw_email_preferences_get_fields();
				//$pref = aw_email_preferences_get_defaults();
				$pref = array();
				$user_meta = get_user_meta($user_id);
				$checked = null; $check = 0;
				echo '<div id="aw-email-preferences">';
				echo '<fieldset class="woocommerce_edit_account_form aw-email-preferences">
					<h3>Email preferences</h3>';
					$email_meta = get_user_meta($user_id, 'aw_email_notifications', true );
					$email_data = json_decode($email_meta);
					$i = 0;
					foreach($email_pref_fields as $email_key => $email_name){
						if(!empty($email_data)){
							$d = $email_data[$i];
							if(isset($d) && property_exists($d, $email_key)){
								$check = $email_data[$i]->$email_key;
								if($check == 1){
									$checked = 'checked';
								}
								$i++;
							}
						}
						echo '<div class="preference-checkbox">
							<input type="checkbox" name="' . $email_key . '" value="' . esc_attr($check) . '" ' . $checked . ' />
							<label>' . $email_name . '</label>
						</div>';
					}
				echo '</fieldset>';
				//echo var_dump($user_meta);
				echo '</div>';
		}
		function aw_email_preferences_user_meta_update() {
			$curr_user = wp_get_current_user();
			$user_id = $curr_user->ID;
			$email_pref_fields = aw_email_preferences_get_fields();
			$pref = array();
			foreach($email_pref_fields as $email_key => $email_name){
				$option = array($email_key => $_POST[$email_key]);
				array_push($pref, $option);
			}
			$pref = json_encode($pref);
			update_user_meta($user_id, 'aw_email_notifications', $pref);
		}

		add_action('edit_user_profile_update', 'aw_email_preferences_add_user_meta_fields', 10, 1);
		add_action('person_options_update', 'aw_email_preferences_add_user_meta_fields', 10, 1);

		add_action('show_user_profile', 'aw_email_preferences_add_user_meta_form', 10, 1);
		add_action('edit_user_profile', 'aw_email_preferences_add_user_meta_form', 10, 1);
		add_action('edit_user_profile_update', 'aw_email_preferences_add_user_meta_form', 10, 1);
		add_action('woocommerce_edit_account_form', 'aw_email_preferences_add_user_meta_form', 10, 1);

		add_action('profile_update', 'aw_email_preferences_user_meta_update', 10, 1);
		add_action('edit_user_profile_update', 'aw_email_preferences_user_meta_update', 10, 1);
		add_action('woocommerce_save_account_details', 'aw_email_preferences_user_meta_update', 10, 1);

		function aw_account_notifications_get_fields(){
			return array(
				'aw_outbid_notification' => 'Auction Outbid',
				'aw_favorites_notification' => 'Wish List & Favorite Wines',
				'aw_shipping_notification' => 'Shipping & Tracking'
			);
		}
		 function aw_account_notifications_get_defaults(){
		 	return array(
		 		array('aw_outbid_notification_email' => '0', 'aw_outbid_notification_sms' => '0'),
		 		array('aw_favorites_notification_email' => '0', 'aw_favorites_notification_sms' => '0'),
		 		array('aw_shipping_notification_email' => '0', 'aw_shipping_notification_sms' => '0')
		 	);
		 }
		function aw_account_notifications_add_user_meta_fields() {
		    $curr_user = wp_get_current_user();
		    $user_id = $curr_user->ID;
				$notification_fields = aw_account_notifications_get_fields();
				$pref = array();
				foreach($notification_fields as $notif_key => $notif_name){
					$notif_key_email = $notif_key . '_email';
					$notif_key_sms = $notif_key . '_sms';
					$arr1 = array( $notif_key_email => '0');
					$arr2 = array( $notif_key_sms => '0');
					//$option = array($notif_key => array($arr1, $arr2));
					$option = array($arr1, $arr2);
					array_push($pref, $option);
				}
				$pref = json_encode($pref);
				// $defaults = aw_account_notifications_get_defaults();
				// $pref = json_encode($defaults);
				add_user_meta($user_id, 'aw_notifications', $pref, true );
		}

		function aw_account_notifications_add_user_meta_form() {
		    $curr_user = wp_get_current_user();
		    $user_id = $curr_user->ID;
				$notification_fields = aw_account_notifications_get_fields();
				// $notification_defaults = aw_account_notifications_get_defaults();
				// echo var_dump($notification_defaults);
				$pref = array();
				$notification_meta = get_user_meta($user_id, 'aw_notifications', true );
				$notification_data = json_decode($notification_meta);
				$meta = get_user_meta($user_id, '', false);
				$checked; $check; $check_email; $check_sms; $checked_email; $checked_sms;
				echo '<div id="aw-account-notifications">
				<fieldset class="woocommerce_edit_account_form aw-account-notifications">
						<h3>Account Notifications</h3>';
						$i = 0;
						foreach($notification_fields as $notif_key => $notif_name){
							$notif_key_email = $notif_key . '_email';
							$notif_key_sms = $notif_key . '_sms';
							$checked_email = null; $check_email = null; $checked_sms = null; $check_sms = null;
							if(!empty($notification_data) && is_array($notification_data)){
								$d = $notification_data[$i];
								if(isset($d) && is_object($d) && property_exists($d, $notif_key_email)){
									$check_email = $d->$notif_key_email;
									if($check_email == '1'){$checked_email = 'checked'; }
									if($i < 5){$i++;}
								}
								if($i < 5){ $d = $notification_data[$i]; }
								if(isset($d) && is_object($d) && property_exists($d, $notif_key_sms)){
									$check_sms = $d->$notif_key_sms;
									if($check_sms == '1'){$checked_sms = 'checked'; }
									if($i < 5){$i++;}
								}
							}
							// echo '<div class="notification-checkbox">
							// 	<h5>' . $notif_name . '</h5>
							// 	<input type="checkbox" name="' . $notif_key . '_email' . '"  value="' . $check_email . '" ' . $checked_email . ' />
							// 	<label for="' . $notif_key . '_email' . '" >Email</label>
							// 	<input type="checkbox" name="' . $notif_key . '_sms' . '"  value="' . $check_sms . '"  ' . $checked_sms . ' />
							// 	<label for="' . $notif_key . '_sms' . '" >SMS</label>
							// </div>';
							echo '<div class="notification-checkbox">
								<h5>' . $notif_name . '</h5>
								<input type="checkbox" name="' . $notif_key . '_email' . '"  value="' . $check_email . '" ' . $checked_email . ' />
								<label for="' . $notif_key . '_email' . '" >Email</label>
								<input type="hidden" name="' . $notif_key . '_sms' . '"  value="' . $check_sms . '"  ' . $checked_sms . ' />
							</div>';

					}
				echo '</fieldset>
				</div>';

		}
		function aw_account_notifications_user_meta_update() {
			$curr_user = wp_get_current_user();
			$user_id = $curr_user->ID;
			$notification_fields = aw_account_notifications_get_fields();
			$notifications_name = 'aw_notifications';
			$pref = aw_account_notifications_get_defaults();
			$pref =  array(); $options = array();
			foreach($notification_fields as $notif_key => $notif_name){
				$notif_key_email = $notif_key . '_email';
				$notif_key_sms = $notif_key . '_sms';
				if (isset($_POST[$notif_key_email])){
					$option = array($notif_key_email => $_POST[$notif_key_email]);
					array_push($options, $option);
				}
				if (isset($_POST[$notif_key_sms])){
					$option = array($notif_key_sms => $_POST[$notif_key_sms]);
					array_push($options, $option);
				}
				//array_push($pref, $options);
			}
			$pref = json_encode($options);
			update_user_meta($user_id, 'aw_notifications', $pref );

		}

		add_action('edit_user_profile_update', 'aw_account_notifications_add_user_meta_fields', 10, 1);
		add_action('person_options_update', 'aw_account_notifications_add_user_meta_fields', 10, 1);

		add_action('show_user_profile', 'aw_account_notifications_add_user_meta_form', 10, 1);
		add_action('edit_user_profile', 'aw_account_notifications_add_user_meta_form', 10, 1);
		add_action('woocommerce_edit_account_form', 'aw_account_notifications_add_user_meta_form');

		add_action('edit_user_profile_update', 'aw_account_notifications_user_meta_update', 10, 1);
		add_action('profile_update', 'aw_account_notifications_user_meta_update');
		add_action('woocommerce_save_account_details', 'aw_account_notifications_user_meta_update');

		function aw_conditions_get_fields(){
			return array(
				'aw_age_conditions' => 'age',
				'aw_terms_conditions' => 'terms',
				'aw_emails_conditions' => 'emails'
			);
		}
		function aw_conditions_add_user_meta_fields() {
		    $curr_user = wp_get_current_user();
		    $user_id = $curr_user->ID;
				$conditions_fields = aw_conditions_get_fields();
				$pref = array();
				foreach($conditions_fields as $cond_key => $cond_name){
					$option = array($cond_key => '0');
					array_push($pref, $option);
				}
				$pref = json_encode($pref);
				add_user_meta($user_id, 'aw_conditions', $pref, true );

		}
		function aw_conditions_add_user_meta_form() {
		    $curr_user = wp_get_current_user();
		    $user_id = $curr_user->ID;
				$conditions_fields = aw_conditions_get_fields();
				$pref = array();
				$conditions_meta = get_user_meta($user_id, 'aw_conditions', true );
				$conditions_data = json_decode($conditions_meta);
				$meta = get_user_meta($user_id, '', false);
				$i = 0;
				echo '<div id="aw-conditions">';
				echo	'<fieldset class="woocommerce_edit_account_form aw-conditions">';
				$check; $checked;
				foreach($conditions_fields as $cond_key => $cond_name){
					if(is_array($conditions_data)){
						$cond_data = $conditions_data[$i];
						if(isset($cond_data)){
							$check = $cond_data->$cond_key;
							if($check == 1){
								$checked = 'checked';
							}
						}
					}
					echo '<div class="condition-item">';
					switch($cond_name){
						case 'age':
							echo '<input type="checkbox" name="' . $cond_key . '" value="' . esc_attr($check) . '" ' . $checked . ' />
									<label>I am at least 21 years of age and I understand that any person accepting delivery of alcoholic beverages must also be at least 21 years of age.</label>';
							break;
						case 'terms':
							echo '<input type="checkbox" name="' . $cond_key . '" value="' . esc_attr($check) . '" ' . $checked . ' />
							<label>I have read and agree to the <a href="' . get_home_url() . '/terms-conditions/" >Terms of Conditions</a>, <a href="' . get_home_url() . '/terms-conditions/" >Retail Conditions of Sale</a>, <a href="' . get_home_url() . '/terms-conditions/" >Terms & Condtions</a>, and <a href="' . get_home_url() . '/terms-conditions/" >Privacy Policy</a>.</label>';
							break;
						case 'emails':
							echo '<input type="checkbox" name="' . $cond_key . '" value="' . esc_attr($check) . '" ' . $checked . ' />
							<label>I consent to terms of recieving emails on Acker special retail offers.</label>';
							break;
					} // end switch
					echo '</div>';
					$i++;
				} // end for each
				echo '</fieldset></div>';
		}
		add_action('edit_user_profile_update', 'aw_conditions_add_user_meta_fields', 10, 1);
		add_action('person_options_update', 'aw_conditions_add_user_meta_fields', 10, 1);
		add_action('show_user_profile', 'aw_conditions_add_user_meta_form', 10, 1);
		add_action('edit_user_profile_update', 'aw_conditions_add_user_meta_form', 10, 1);
		add_action('woocommerce_edit_account_form', 'aw_conditions_add_user_meta_form');

		function aw_conditions_user_meta_update() {
			$curr_user = wp_get_current_user();
			$user_id = $curr_user->ID;
			$conditions_fields = aw_conditions_get_fields();
			$pref = array();
			foreach($conditions_fields as $cond_key => $cond_name){
				if (isset($_POST[$cond_key])){
					$option = array($cond_key => $_POST[$cond_key]);
					array_push($pref, $option);
				}
			}
			$pref = json_encode($pref);
			update_user_meta($user_id, 'aw_conditions', $pref );
		}
		add_action('edit_user_profile_update', 'aw_conditions_user_meta_update', 10, 1);
		add_action('profile_update', 'aw_conditions_user_meta_update');
		add_action('woocommerce_save_account_details', 'aw_conditions_user_meta_update');


 ?>
