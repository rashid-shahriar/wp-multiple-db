<?php

/**
 * Plugin Name: WP Two Plugin
 * Description: A plugin to manage posts from wp-two database with CRUD functionality.
 * Version: 1.1
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

// Display posts in wp-two and manage CRUD
function wp_two_posts_page()
{
    global $mydb;

    // Handle form submissions
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add_post') {
            $title = sanitize_text_field($_POST['title']);
            $content = sanitize_textarea_field($_POST['content']);
            $mydb->insert('post', array('title' => $title, 'des' => $content));
            echo '<div class="updated"><p>Post added successfully.</p></div>';
        }

        if ($_POST['action'] === 'update_post' && !empty($_POST['id'])) {
            $id = intval($_POST['id']);
            $title = sanitize_text_field($_POST['title']);
            $content = sanitize_textarea_field($_POST['content']);
            $mydb->update('post', array('title' => $title, 'des' => $content), array('id' => $id));
            echo '<div class="updated"><p>Post updated successfully.</p></div>';
        }

        if ($_POST['action'] === 'delete_post' && !empty($_POST['id'])) {
            $id = intval($_POST['id']);
            $mydb->delete('post', array('id' => $id));
            echo '<div class="updated"><p>Post deleted successfully.</p></div>';
        }
    }

    // Display the add/update form
?>
    <h2>WP Two Posts</h2>
    <form method="post">
        <input type="hidden" name="action" value="add_post">
        <label for="title">Title:</label><br>
        <input type="text" name="title" id="title" required><br><br>
        <label for="content">Content:</label><br>
        <textarea name="content" id="content" required></textarea><br><br>
        <input type="submit" value="Add Post">
    </form>
    <hr>
    <?php

    // Display existing posts with edit and delete options
    $rows = $mydb->get_results("SELECT * FROM post");

    if (!empty($rows)) {
        foreach ($rows as $row) {
    ?>
            <div>
                <strong>Post ID:</strong> <?php echo $row->id; ?><br>
                <strong>Title:</strong> <?php echo $row->title; ?><br>
                <strong>Content:</strong> <?php echo $row->des; ?><br>
                <form method="post" style="display:inline-block;">
                    <input type="hidden" name="action" value="update_post">
                    <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                    <input type="text" name="title" value="<?php echo esc_attr($row->title); ?>" required>
                    <textarea name="content" required><?php echo esc_textarea($row->des); ?></textarea>
                    <input type="submit" value="Update">
                </form>
                <form method="post" style="display:inline-block;">
                    <input type="hidden" name="action" value="delete_post">
                    <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                    <input type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this post?');">
                </form>
                <hr>
            </div>
<?php
        }
    } else {
        echo '<p>No posts found in the wp-two database.</p>';
    }
}
