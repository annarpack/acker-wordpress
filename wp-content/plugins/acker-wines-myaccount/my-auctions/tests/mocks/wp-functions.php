<?php

use AckerWines\WpUser;

if (!function_exists('get_userdata')) {
    function get_userdata() {
        $user = new WpUser();
        $user->aw_apcid = '10180';
        $user->ID = 7;
        $user->first_name = 'Test';
        $user->last_name = 'User 1';
        return $user;
    }
}

if (!function_exists('get_user_meta')) {
    function get_user_meta($user_id, $key, $single) {
        return '10180';
    }
}

if (!function_exists('get_current_user_id')) {
    function get_current_user_id() {
        return 7;
    }
}

if (!function_exists('wp_die')) {
    function wp_die() {
        return;
    }
}

if (!function_exists('add_action')) {
    function add_action() {
        return;
    }
}
