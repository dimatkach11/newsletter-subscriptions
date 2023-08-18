<?php
function export_csv()
{
  global $wpdb;

  // Retrieve filtered data from the database based on start_date and end_date
  $start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : '';
  $end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : '';

  // Construct the WHERE clause based on the filtered dates
  $where_conditions = array();
  $file_name = 'newsletter_subscriptions';
  if (!empty($start_date)) {
    $where_conditions[] = "date >= '$start_date'";
    $file_name = $file_name . "_from_" . $start_date;
  }
  if (!empty($end_date)) {
    $where_conditions[] = "date <= '$end_date 23:59:59'";
    $file_name = $file_name . "_to_" . $end_date;
  }
  $where_clause = !empty($where_conditions) ? "WHERE " . implode(' AND ', $where_conditions) : '';

  // Retrieve data from the database
  $table_name = $wpdb->prefix . 'newsletter_subscriptions';
  $data = $wpdb->get_results("SELECT * FROM $table_name $where_clause ORDER BY date DESC");

  // Set headers for CSV export
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="' . $file_name . '.csv"');

  // Open the output stream for writing CSV data
  $output = fopen('php://output', 'w');

  // Write headers
  fputcsv($output, array('Email', 'Privacy Consent', 'Date'));

  // Write data rows
  foreach ($data as $entry) {
    fputcsv($output, array($entry->email, $entry->privacy ? 'Yes' : 'No', $entry->date));
  }

  // Close the output stream
  fclose($output);

  // Exit to prevent further output
  exit;
}
if (isset($_GET['export'])) {
  export_csv();
}