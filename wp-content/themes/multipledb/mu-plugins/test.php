<?php
// Load WordPress functions and environment
require_once(dirname(__FILE__) . '/wp-load.php');

// Establish a new connection to the secondary database
$secondary_db = new wpdb('root', '', 'wp-two', 'localhost');

// Check if the connection was successful
if (!empty($secondary_db->error)) {
    die('Secondary database connection failed: ' . $secondary_db->error);
}

// Query the 'post' table in the 'wp-two' database
$results = $secondary_db->get_results("SELECT * FROM post");

// Display the data retrieved from the 'post' table
if (!empty($results)) {
    foreach ($results as $row) {
        echo 'Post ID: ' . $row->ID . '<br>';
        echo 'Post Title: ' . $row->post_title . '<br>';
        echo 'Post Content: ' . $row->post_content . '<br><br>';
    }
} else {
    echo 'No posts found in the wp-two database.';
}
