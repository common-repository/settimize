<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function settimize_add_featured_image_column($columns) {
    $settings = get_option('settimize_settings');
    if (isset($settings['enable_featured_image']) && $settings['enable_featured_image']) {
        $columns['featured_image'] = esc_html__('Featured Image', 'settimize');
    }
    return $columns;
}
add_filter('manage_posts_columns', 'settimize_add_featured_image_column');
add_filter('manage_pages_columns', 'settimize_add_featured_image_column');

function settimize_display_featured_image_column($column, $post_id) {
    if ($column === 'featured_image') {
        $thumbnail = get_the_post_thumbnail($post_id, array(100, 100));
        echo $thumbnail ? $thumbnail : esc_html__('No Image', 'settimize');
    }
}
add_action('manage_posts_custom_column', 'settimize_display_featured_image_column', 10, 2);
add_action('manage_pages_custom_column', 'settimize_display_featured_image_column', 10, 2);
