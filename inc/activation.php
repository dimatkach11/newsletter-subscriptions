<?php
function newsletter_subscriptions_activate()
{
  global $wpdb;

  $table_name = $wpdb->prefix . 'newsletter_subscriptions';
  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        email varchar(255) DEFAULT NULL,
        privacy tinyint(1) DEFAULT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

  $result = dbDelta($sql);
  if (is_wp_error($result)) {
    error_log('Error creating database table: ' . $result->get_error_message());
  }
}