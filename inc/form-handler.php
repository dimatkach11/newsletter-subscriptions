<?php
function handle_newsletter_subscription_form()
{
  if (isset($_POST['newsletter_form_nonce']) && wp_verify_nonce($_POST['newsletter_form_nonce'], 'newsletter_form_nonce')) {
    $email = sanitize_email($_POST['email']);
    $privacy = isset($_POST['privacy']) ? 1 : 0;
    $errors = array();

    if (!is_email($email)) {
      $errors[] = 'Invalid email address.';
    }

    if ($privacy !== 1) {
      $errors[] = 'You must agree to the privacy policy.';
    }

    if (empty($errors)) {
      insert_subscription_data($email, $privacy);
      echo "Data submitted successfully!";
    } else {
      echo "<ul>";
      foreach ($errors as $error) {
        echo "<li>$error</li>";
      }
      echo "</ul>";
    }
  }
}
add_action('init', 'handle_newsletter_subscription_form');