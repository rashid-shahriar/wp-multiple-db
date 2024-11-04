<?php

/**
 * Plugin Name: WP Two Plugin
 * Description: A plugin to manage posts from wp-two database.
 * Version: 1.0
 * Author: Your Name
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Custom wpdb for wp-two
$mydb = new wpdb('root', '', 'wp-two', 'localhost');

// Function to create admin menu
function wp_two_plugin_menu()
{
    add_menu_page('WP Two Posts', 'WP Two Posts', 'manage_options', 'wp-two-posts', 'wp_two_posts_page');
}

add_action('admin_menu', 'wp_two_plugin_menu');

// Display posts in wp-two
function wp_two_posts_page()
{
    global $mydb;
    $rows = $mydb->get_results("SELECT * FROM post");

    if (!empty($rows)) {
        foreach ($rows as $row) {
            echo 'Post ID: ' . $row->id . '<br>';
            echo 'Title: ' . $row->title . '<br>';
            echo 'Content: ' . $row->des . '<br><br>';
        }
    } else {
        echo 'No posts found in the wp-two database.';
    }
}


function get_wp_two_posts()
{
    global $mydb;
    $output = '';

    $rows = $mydb->get_results("SELECT * FROM post");

    if (!empty($rows)) {
        foreach ($rows as $row) {
            $output .= 'Post ID: ' . $row->id . '<br>';
            $output .= 'Title: ' . $row->title . '<br>';
            $output .= 'Content: ' . $row->des . '<br><br>';
        }
    } else {
        $output .= 'No posts found in the wp-two database.';
    }

    return $output;
}
