<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add settings page
function settimize_settings_page() {
    add_menu_page(
        'SettiMize Settings',      // Page title
        'SettiMize',               // Menu title
        'manage_options',          // Capability
        'settimize-settings',      // Menu slug
        'settimize_settings_page_html', // Function to display page content
        'dashicons-admin-generic', // Icon URL
        59                         // Position (before 'Settings')
    );
}
add_action('admin_menu', 'settimize_settings_page');

function settimize_settings_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Ensure nonce is verified and sanitize the settings data
    if (isset($_POST['settimize_settings_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['settimize_settings_nonce'])), 'settimize_settings_nonce')) {

        if (isset($_POST['settimize_settings'])) {
            // Sanitize each setting
            $sanitized_settings = array_map(function($setting) {
                return sanitize_text_field($setting);
            }, $_POST['settimize_settings']);

            // Update the options with sanitized data
            update_option('settimize_settings', $sanitized_settings);

            // Display success message
            echo '<div class="updated"><p>' . esc_html__('Settings updated', 'settimize') . '</p></div>';
        }
    }

    // Fetch sanitized settings
    $settings = get_option('settimize_settings', array());

    ?>

    <div class="wrap">
        <h1><?php echo esc_html__('SettiMize Settings', 'settimize'); ?></h1>
        <form method="POST">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Enable Word Count', 'settimize'); ?></th>
                    <td>
                        <input type="checkbox" name="settimize_settings[enable_word_count]" value="1" <?php checked(isset($settings['enable_word_count']) && $settings['enable_word_count'], 1); ?>>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Enable Plugin/Theme Download Text', 'settimize'); ?></th>
                    <td>
                        <input type="checkbox" name="settimize_settings[enable_download_text]" value="1" <?php checked(isset($settings['enable_download_text']) && $settings['enable_download_text'], 1); ?>>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Enable Duplicate Post', 'settimize'); ?></th>
                    <td>
                        <input type="checkbox" name="settimize_settings[enable_duplicate_post]" value="1" <?php checked(isset($settings['enable_duplicate_post']) && $settings['enable_duplicate_post'], 1); ?>>
                    </td>
                </tr>
            </table>
            <?php wp_nonce_field('settimize_settings_nonce'); ?>
            <p><input type="submit" value="<?php echo esc_attr__('Save Settings', 'settimize'); ?>" class="button-primary"></p>
        </form>
    </div>
    <?php
}
?>
