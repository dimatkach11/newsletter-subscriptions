<?php
function newsletter_subscriptions_enqueue_scripts()
{
  // Enqueue jQuery (if not already enqueued)
  wp_enqueue_script('jquery');

  // Enqueue your plugin's JavaScript
  wp_enqueue_script('newsletter-subscriptions-script', plugin_dir_url(__FILE__) . '../public/scripts/script.js', array('jquery'), '1.0', true);

  // Enqueue your plugin's CSS
  wp_enqueue_style('newsletter-subscriptions-style', plugin_dir_url(__FILE__) . '../public/styles/style.css', array(), '1.0');
}
add_action('admin_enqueue_scripts', 'newsletter_subscriptions_enqueue_scripts');