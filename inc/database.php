<?php
function insert_subscription_data($email, $privacy)
{
  global $wpdb;
  $table_name = $wpdb->prefix . 'newsletter_subscriptions';

  $wpdb->insert(
    $table_name,
    array(
      'email' => $email,
      'privacy' => $privacy,
    ),
    array(
      '%s',
      '%d',
    )
  );
}