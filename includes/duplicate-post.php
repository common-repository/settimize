<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add duplicate post feature
function settimize_duplicate_post() {
    $settings = get_option('settimize_settings');
    if (isset($settings['enable_duplicate_post']) && $settings['enable_duplicate_post']) {
        add_filter('post_row_actions', 'settimize_add_duplicate_link', 10, 2);
        add_action('admin_action_settimize_duplicate_post', 'settimize_duplicate_post_action');
    }
}
add_action('init', 'settimize_duplicate_post');

function settimize_add_duplicate_link($actions, $post) {
    if (current_user_can('edit_posts')) {
        $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=settimize_duplicate_post&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce') . '" title="Duplicate this post" rel="permalink">Duplicate</a>';
    }
    return $actions;
}

function settimize_duplicate_post_action() {
    if (!isset($_GET['post']) || !isset($_GET['duplicate_nonce']) || !wp_verify_nonce($_GET['duplicate_nonce'], basename(__FILE__))) {
        return;
    }

    $post_id = absint($_GET['post']);
    $post = get_post($post_id);

    if (isset($post) && $post != null) {
        $new_post = array(
            'post_title' => $post->post_title . ' (Copy)',
            'post_content' => $post->post_content,
            'post_status' => 'draft',
            'post_type' => $post->post_type,
            'post_author' => $post->post_author,
        );

        $new_post_id = wp_insert_post($new_post);

        // Copy taxonomies
        $taxonomies = get_object_taxonomies($post->post_type);
        foreach ($taxonomies as $taxonomy) {
            $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
        }

        // Copy meta data
        $post_meta = get_post_meta($post_id);
        foreach ($post_meta as $meta_key => $meta_values) {
            foreach ($meta_values as $meta_value) {
                update_post_meta($new_post_id, $meta_key, maybe_unserialize($meta_value));
            }
        }

        wp_redirect(admin_url('edit.php?post_type=' . $post->post_type));
        exit;
    }
}
?>
