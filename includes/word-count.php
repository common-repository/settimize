<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add a custom column to the post and page lists
function settimize_add_word_count_column($columns) {
    $columns['word_count'] = esc_html__('Word Count', 'settimize');
    return $columns;
}
add_filter('manage_posts_columns', 'settimize_add_word_count_column');
add_filter('manage_pages_columns', 'settimize_add_word_count_column');

// Populate the custom column with the word count for each post and page
function settimize_display_word_count_column($column_name, $post_id) {
    if ($column_name == 'word_count') {
        // Get the post or page content
        $content = get_post_field('post_content', $post_id);

        // Count the number of words in the post or page
        $word_count = str_word_count(strip_tags($content));

        // Display the word count in the column
        echo esc_html($word_count);
    }
}
add_action('manage_posts_custom_column', 'settimize_display_word_count_column', 10, 2);
add_action('manage_pages_custom_column', 'settimize_display_word_count_column', 10, 2);

// Make the word count column sortable
function settimize_sortable_word_count_column($columns) {
    $columns['word_count'] = 'word_count';
    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'settimize_sortable_word_count_column');
add_filter('manage_edit-page_sortable_columns', 'settimize_sortable_word_count_column');
?>
