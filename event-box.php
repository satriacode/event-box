<?php

/*
    Plugin Name: Event Box
    Plugin URI: https://satriacode.org
    Description: Only thing you need to manage your events in WordPress
    Version: 1.0
    Author: Satriacode
    Author URI: https://satriacode.org
*/

global $wpdb;

define('PLUGIN_PREFIX', 'event_box_');
define('TABLE_REGISTRATION', $wpdb->prefix . PLUGIN_PREFIX . 'attendee_registration');
define('TABLE_SETUP', $wpdb->prefix . PLUGIN_PREFIX . 'event_setup');

require_once('event-box-init.php');
require_once('event-box-admin.php');
require_once('event-box-front.php');

register_activation_hook(__FILE__, array(new EventBoxInit, 'event_box_create_plugin_activation'));

add_action('admin_menu', 'event_box_add_admin_menu');

function event_box_add_admin_menu()
{
    error_log('add menu');
    add_menu_page('Event Box Page', 'Event Box', 'manage_options', __FILE__, array(new EventBoxAdmin, 'event_box_admin_page'), 'dashicons-calendar');
}

add_shortcode('event_box', array(new EventBoxFront, 'event_box_shortcode'));

function event_box_enqueue_admin_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('event-box-admin', plugins_url('js/admin.js', __FILE__), array('jquery'), '1.0.0', true);
}

add_action('admin_enqueue_scripts', 'event_box_enqueue_admin_scripts');