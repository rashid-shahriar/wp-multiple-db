<?php

/**
 * Plugin Name: WP One Plugin
 * Description: A plugin to manage posts from wp-two database with related post_meta from wp-one, including CRUD for meta data.
 * Version: 1.5
 * Author: Your Name
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Function to create admin menu
function wp_one_plugin_menu()
{
    add_menu_page('WP One Posts', 'WP One Posts', 'manage_options', 'wp-one-posts', 'wp_one_posts_page');
}

add_action('admin_menu', 'wp_one_plugin_menu');

function wp_one_posts_page()
{
    global $wpdb;

    // Connect to the secondary database (wp-two)
    $mydb = new wpdb('root', '', 'wp-two', 'localhost');

    // Handle form submissions for CRUD operations on meta data
    if (isset($_POST['meta_action'])) {
        $selected_post_id = intval($_POST['post_id']);

        // Check if keys exist before accessing them
        $meta_key = isset($_POST['meta_key']) ? sanitize_text_field($_POST['meta_key']) : '';
        $meta_value = isset($_POST['meta_value']) ? sanitize_textarea_field($_POST['meta_value']) : '';

        if ($_POST['meta_action'] === 'add_meta') {
            // Add new meta data
            $wpdb->insert(
                'post_meta',
                array(
                    'post_id' => $selected_post_id,
                    'meta_key' => $meta_key,
                    'meta_value' => $meta_value,
                )
            );
            echo '<div class="updated"><p>Meta data added successfully.</p></div>';
        } elseif ($_POST['meta_action'] === 'edit_meta' && isset($_POST['meta_id'])) {
            // Edit existing meta data
            $meta_id = intval($_POST['meta_id']);
            $wpdb->update(
                'post_meta',
                array(
                    'meta_key' => $meta_key,
                    'meta_value' => $meta_value,
                ),
                array('meta_id' => $meta_id)
            );
            echo '<div class="updated"><p>Meta data updated successfully.</p></div>';
        } elseif ($_POST['meta_action'] === 'delete_meta' && isset($_POST['meta_id'])) {
            // Delete existing meta data
            $meta_id = intval($_POST['meta_id']);
            $wpdb->delete('post_meta', array('meta_id' => $meta_id));
            echo '<div class="updated"><p>Meta data deleted successfully.</p></div>';
            // Clear out any post id meta field values to prevent showing them in the form
            $meta_key = '';
            $meta_value = '';
            unset($_POST['meta_id']); // Unset to prevent the edit form from showing after deletion
        }
    }

    // Retrieve posts from the 'post' table in 'wp-two'
    $posts = $mydb->get_results("SELECT * FROM post");

    // Display the dropdown form to select a post
    $selected_post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : null;
?>
    <h2>WP One Posts - Meta Data Manager</h2>
    <form method="post">
        <label for="post_id">Select a Post ID:</label>
        <select name="post_id" id="post_id" required>
            <option value="">-- Select Post ID --</option>
            <?php
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    echo '<option value="' . esc_attr($post->id) . '" ' . selected($selected_post_id, $post->id, false) . '>';
                    echo 'ID ' . esc_html($post->id) . ': ' . esc_html($post->title);
                    echo '</option>';
                }
            }
            ?>
        </select>
        <input type="submit" value="View Meta Data">
    </form>
    <hr>
    <?php

    // If a post ID is selected, display related meta data and form for adding/updating meta data
    if ($selected_post_id) {
        echo '<h3>Meta Data for Post ID ' . esc_html($selected_post_id) . ':</h3>';

        // Query the 'post_meta' table in 'wp-one' for related meta data
        $meta_data = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM post_meta WHERE post_id = %d",
            $selected_post_id
        ));

        if (!empty($meta_data)) {
            echo '<table border="1" cellspacing="0" cellpadding="5">';
            echo '<tr><th>Meta Key</th><th>Meta Value</th><th>Actions</th></tr>';
            foreach ($meta_data as $meta) {
    ?>
                <tr>
                    <td><?php echo esc_html($meta->meta_key); ?></td>
                    <td><?php echo esc_html($meta->meta_value); ?></td>
                    <td>
                        <!-- Form for editing -->
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="post_id" value="<?php echo esc_attr($selected_post_id); ?>">
                            <input type="hidden" name="meta_id" value="<?php echo esc_attr($meta->meta_id); ?>">
                            <input type="hidden" name="meta_action" value="edit_meta">
                            <input type="hidden" name="meta_key" value="<?php echo esc_attr($meta->meta_key); ?>">
                            <input type="hidden" name="meta_value" value="<?php echo esc_textarea($meta->meta_value); ?>">
                            <input type="submit" value="Edit"
                                onclick="return confirm('Are you sure you want to edit this meta data?');">
                        </form>
                        <!-- Form for deleting -->
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="post_id" value="<?php echo esc_attr($selected_post_id); ?>">
                            <input type="hidden" name="meta_id" value="<?php echo esc_attr($meta->meta_id); ?>">
                            <input type="hidden" name="meta_action" value="delete_meta">
                            <input type="submit" value="Delete"
                                onclick="return confirm('Are you sure you want to delete this meta data?');">
                        </form>
                    </td>
                </tr>
        <?php
            }
            echo '</table>';
        } else {
            echo '<p>No meta data found for this post.</p>';
        }

        // Display form for adding/updating meta data
        ?>
        <h4><?php echo isset($_POST['meta_id']) ? 'Edit' : 'Add New'; ?> Meta Data</h4>
        <form method="post">
            <input type="hidden" name="meta_action" value="<?php echo isset($_POST['meta_id']) ? 'edit_meta' : 'add_meta'; ?>">
            <input type="hidden" name="post_id" value="<?php echo esc_attr($selected_post_id); ?>">
            <?php if (isset($_POST['meta_id'])): ?>
                <input type="hidden" name="meta_id" value="<?php echo esc_attr($_POST['meta_id']); ?>">
                <label for="meta_key">Meta Key:</label><br>
                <input type="text" name="meta_key" id="meta_key"
                    value="<?php echo isset($_POST['meta_key']) ? esc_attr($_POST['meta_key']) : ''; ?>" required><br><br>
                <label for="meta_value">Meta Value:</label><br>
                <textarea name="meta_value" id="meta_value"
                    required><?php echo isset($_POST['meta_value']) ? esc_textarea($_POST['meta_value']) : ''; ?></textarea><br><br>
            <?php else: ?>
                <label for="meta_key">Meta Key:</label><br>
                <input type="text" name="meta_key" id="meta_key" required><br><br>
                <label for="meta_value">Meta Value:</label><br>
                <textarea name="meta_value" id="meta_value" required></textarea><br><br>
            <?php endif; ?>
            <input type="submit" value="<?php echo isset($_POST['meta_id']) ? 'Update Meta Data' : 'Add Meta Data'; ?>">
        </form>
<?php
    }
}
