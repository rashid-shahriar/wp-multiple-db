<?php
//GLOBAL $wpdb;

$mydb = new wpdb('root', '', 'wp-two', 'localhost');
$rows = $mydb->get_results(query: "select * from post");

// Display the data retrieved from the 'post' table
//get post table from secondary db
if (!empty($rows)) {
    foreach ($rows as $row) {
        // Display data from the 'post' table in 'wp-two'
        echo 'Post ID: ' . $row->id . '<br>';
        echo 'row Title: ' . $row->title . '<br>';
        echo 'Post Content: ' . $row->des . '<br>';


        // Query the 'post_meta' table in 'wp-one' for related meta data
        $meta_data = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM post_meta WHERE post_id = %d",
            $row->id
        ));

        // Display meta data if available
        if (!empty($meta_data)) {
            foreach ($meta_data as $meta) {
                echo 'Meta Key: ' . $meta->meta_key . '<br>';
                echo 'Meta Value: ' . $meta->meta_value . '<br>';
            }
        } else {
            echo 'No meta data found for this post.<br>';
        }

        echo '<br>';
    }
} else {
    echo 'No posts found in the wp-two database.';
}

// Fetch and display posts from both databases
echo '<h2>Posts from WP One Database</h2>';
echo get_wp_one_posts();

echo '<h2>Posts from WP Two Database</h2>';
echo get_wp_two_posts();
