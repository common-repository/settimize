<?php

/**
Plugin Name: Advanced Custom WordPress Optimization Settings - SettiMize
Plugin URI: https://wordpress.org/plugins/settimize/
Description: SettiMize is your go-to plugin for advanced WordPress settings, custom fields, and additional optimization features on your dashboard.
Version: 1.0.0
Requires at least: 6.0
Requires PHP: 7.4
Author: SettiMize
Text Domain: settimize
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
**/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


// Include the featured image functionality
require_once plugin_dir_path(__FILE__) . 'includes/featured-img.php';

// Add settings page
function settimize_add_settings_page() {
    add_menu_page(
        'SettiMize Settings',
        'SettiMize',
        'manage_options',
        'settimize',
        'settimize_settings_page',
        'dashicons-admin-generic',
        59
    );
}
add_action('admin_menu', 'settimize_add_settings_page');

function settimize_settings_page() {
    ?>
    <div class="wrap">
        <h1>SettiMize Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('settimize_settings');
            do_settings_sections('settimize');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function settimize_register_settings() {
    register_setting('settimize_settings', 'settimize_settings');

    add_settings_section(
        'settimize_main_section',
        'Main Settings',
        'settimize_main_section_cb',
        'settimize'
    );

    add_settings_field(
        'enable_word_count',
        'Enable Word Count',
        'settimize_enable_word_count_cb',
        'settimize',
        'settimize_main_section'
    );

    add_settings_field(
        'enable_featured_image',
        'Enable Featured Image',
        'settimize_enable_featured_image_cb',
        'settimize',
        'settimize_main_section'
    );
}
add_action('admin_init', 'settimize_register_settings');

function settimize_main_section_cb() {
    echo '<p>Main settings for the SettiMize plugin.</p>';
}

function settimize_enable_word_count_cb() {
    $options = get_option('settimize_settings');
    $checked = isset($options['enable_word_count']) ? 'checked' : '';
    echo "<input type='checkbox' name='settimize_settings[enable_word_count]' " . esc_attr($checked) . " />";
}

function settimize_enable_featured_image_cb() {
    $options = get_option('settimize_settings');
    $checked = isset($options['enable_featured_image']) ? 'checked' : '';
    echo "<input type='checkbox' name='settimize_settings[enable_featured_image]' " . esc_attr($checked) . " />";
}

// Conditionally include word count functionality
function settimize_word_count_functionality() {
    $settings = get_option('settimize_settings');
    if (isset($settings['enable_word_count']) && $settings['enable_word_count']) {
        require_once plugin_dir_path(__FILE__) . 'includes/word-count.php'; // Adjust path as needed
    }
}
add_action('init', 'settimize_word_count_functionality');

?>
