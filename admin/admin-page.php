<?php
function newsletter_subscriptions_menu()
{
  add_menu_page(
    'Newsletter Subscriptions',
    'Subscriptions',
    'manage_options',
    'newsletter-subscriptions',
    'newsletter_subscriptions_page'
  );
}
add_action('admin_menu', 'newsletter_subscriptions_menu');

function newsletter_subscriptions_page()
{
  global $wpdb;

  // Retrieve total number of entries based on filtered data
  $table_name = $wpdb->prefix . 'newsletter_subscriptions';

  // Handle date filtering
  $where_conditions = array();
  if (isset($_GET['start_date']) && $_GET['start_date']) {
    $start_date = sanitize_text_field($_GET['start_date']);
    $where_conditions[] = $wpdb->prepare("date >= %s", $start_date);
  }
  if (isset($_GET['end_date']) && $_GET['end_date']) {
    $end_date = sanitize_text_field($_GET['end_date']);
    $where_conditions[] = $wpdb->prepare("date <= %s", $end_date . ' 23:59:59');
  }
  $where_clause = '';
  if (!empty($where_conditions)) {
    $where_clause = ' WHERE ' . implode(' AND ', $where_conditions);
  }

  // Retrieve data from the database with pagination and filters
  $entries_per_page = isset($_GET['entries_per_page']) ? intval($_GET['entries_per_page']) : 10;
  $total_entries = $wpdb->get_var("SELECT COUNT(*) FROM $table_name $where_clause");
  $total_pages = ceil($total_entries / $entries_per_page);

  // Current page
  $current_page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;

  // Calculate OFFSET for SQL query
  $offset = ($current_page - 1) * $entries_per_page;

  // Construct the current URL with query parameters
  $current_url = admin_url('admin.php?page=newsletter-subscriptions');
  if (!empty($_GET)) {
    $filtered_get = $_GET;
    unset($filtered_get['action']);
    unset($filtered_get['id']);

    $current_url = add_query_arg($filtered_get, $current_url);
  }

  // Retrieve data from the database with pagination and filters
  $query = "SELECT * FROM $table_name $where_clause ORDER BY date DESC LIMIT $entries_per_page OFFSET $offset";
  $data = $wpdb->get_results($query);

  // Display filter form
  ?>
  <!-- Display filter form -->
  <div class="wrap newsletter-filter-form">
    <h2>Newsletter Subscriptions Actions</h2>
    <form method="GET" action="">
      <input type="hidden" name="page" value="newsletter-subscriptions">
      <div class="block">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date"
          value="<?php echo isset($_GET['start_date']) ? esc_attr($_GET['start_date']) : ''; ?>">
      </div>
      <div class="block">
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date"
          value="<?php echo isset($_GET['end_date']) ? esc_attr($_GET['end_date']) : ''; ?>">
      </div>
      <div class="block">
        <label for="entries_per_page">Entries per Page:</label>
        <select name="entries_per_page" class="newsletter-filter-select">
          <option value="10" <?php selected($entries_per_page, 10); ?>>10</option>
          <option value="50" <?php selected($entries_per_page, 50); ?>>50</option>
          <option value="100" <?php selected($entries_per_page, 100); ?>>100</option>
          <option value="250" <?php selected($entries_per_page, 250); ?>>250</option>
          <option value="500" <?php selected($entries_per_page, 500); ?>>500</option>
        </select>
      </div>
      <div class="form-buttons">
        <input type="submit" value="Filter" class="button button-primary">
        <input type="button" value="Reset" class="button" onclick="resetFilters()">
        <input type="submit" name="export" value="Export" class="button">
      </div>
    </form>
  </div>



  <!-- Display data table -->
  <div class="wrap">
    <h2>Newsletter Subscriptions Data</h2>
    <table class="wp-list-table widefat fixed">
      <thead>
        <tr>
          <th>Email</th>
          <th>Privacy Consent</th>
          <th>Date</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $entry): ?>
          <tr>
            <td>
              <?php echo esc_html($entry->email); ?>
            </td>
            <td>
              <?php echo $entry->privacy ? 'Yes' : 'No'; ?>
            </td>
            <td>
              <?php echo esc_html($entry->date); ?>
            </td>
            <td>
              <?php if (current_user_can('manage_options')): ?>
                <a href="<?php echo esc_url(add_query_arg(array('action' => 'delete', 'id' => $entry->id))); ?>">Delete</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Display pagination links -->
  <div class="pagination-center">
    <div class="tablenav bottom">
      <div class="tablenav-pages">
        <?php
        $pagination_args = array(
          'base' => add_query_arg('paged', '%#%'),
          'format' => '?paged=%#%',
          'total' => $total_pages,
          'current' => $current_page,
          'show_all' => false,
          'end_size' => 1,
          'mid_size' => 2,
          'prev_next' => true,
          'prev_text' => __('&laquo;'),
          'next_text' => __('&raquo;'),
        );
        echo paginate_links($pagination_args);
        ?>
      </div>
    </div>
  </div>
  <?php

  // Handle entry deletion
  if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    if (!current_user_can('manage_options')) {
      wp_die('You do not have permission to delete entries.');
    }

    $id = intval($_GET['id']);
    $wpdb->delete($table_name, array('id' => $id));
    // Use JavaScript to redirect back to the submissions page after deletion
    echo '<script>window.location.href = "' . $current_url . '";</script>';
    exit;
  }
}