<?php
/*
Plugin Name: Newsletter Subscriptions
Description: A plugin to save newsletter subscriptions data to a database table newsletter.
Version: 1.0
Author: Dmytro Tkach
*/

// Include files
require_once plugin_dir_path(__FILE__) . 'inc/activation.php';
require_once plugin_dir_path(__FILE__) . 'inc/deactivation.php';
require_once plugin_dir_path(__FILE__) . 'inc/enqueue.php';
require_once plugin_dir_path(__FILE__) . 'inc/form-handler.php';
require_once plugin_dir_path(__FILE__) . 'inc/database.php';
require_once plugin_dir_path(__FILE__) . 'admin/admin-page.php';
require_once plugin_dir_path(__FILE__) . 'admin/export-csv.php';

// Activation hook
register_activation_hook(__FILE__, 'newsletter_subscriptions_activate');
// Deactivation hook
register_deactivation_hook(__FILE__, 'newsletter_subscriptions_deactivate');