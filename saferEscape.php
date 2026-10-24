<?php
/* 
    Plugin Name: DV Safer Escape
    Version: 1.0
    Author: Joanna
    Description: Provides a safer way for abuse victims to quickly exit a site by allowing visitors to enter a phrase they would normally search for so that if they need to escape the site they're browsing, they can be redirected to a search results page that won't raise their abuser's suspicion.
*/
if( ! defined('ABSPATH') ) exit;

class DVSaferEscapePlugin {
    function __construct() {
        add_action('init', array($this, 'registerScripts'));
        add_action('wp_enqueue_scripts', array($this, 'pluginFiles'), 10, 0);
        add_action('wp_head', array($this, 'addSaferEscapeInstructionsOverlay'));
        add_action('wp_footer', array($this, 'addBottomPaddingForSaferEscape'));
        add_action('admin_menu', array($this, 'dvSaferEscape_settingsMenu'));
        add_action('admin_init', array($this, 'register_dvSaferEscape_plugin_settings'));
        add_action( 'admin_enqueue_scripts', array($this, 'dvSaferEscape_admin_color_picker_assets'));
    }

    function registerScripts(){
        wp_register_style('dv-safer-escape-styles', plugins_url('css/styles.css', __FILE__), false, '1.0', 'all');
    }

    function pluginFiles(){
        wp_enqueue_style('dv-safer-escape-styles');
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('dv-safer-escape-js', plugin_dir_url(__FILE__) . '/build/index.js', array('jquery'), '1.0', true);
    }

    function addBottomPaddingForSaferEscape(){
        ?><div id="dvSaferEscape_bottom-spacer"></div>
    <?php }

    function addSaferEscapeInstructionsOverlay(){
        ?><div id="dvSaferEscape_safety-overlay" class="dvSaferEscape_hidden">
            <span id="dvSaferEscape_safety-overlay-close">close</span>
            <div id="dvSaferEscape_safety-info">
                <p>
                    Victims of any type of abuse are at an increased risk of being killed by their abusers when the abuser suspects that the victim is thinking about leaving. <em>This is true even if the abuser has never been physically violent before.</em>
                </p>
                <p>
                    This site was built with safety precautions to keep visitors safe. Pressing the “EXIT” button on the bottom left corner of the screen will redirect you to a search results page.
                </p>
                <p>
                    To minimize suspicion, we want to redirect you to a search results page that wouldn't be out of the ordinary for you. Please select your favorite search engine and type a phrase that you would normally search for.  
                </p>
                <div id="dvSaferEscape_search-section">
                    <div id="dvSaferEscape_search-options-section">
                        <button id="dvSaferEscape_google" class="dvSaferEscape_search-options dvSaferEscape_search-option-selected" aria-label="selected" data-name="google">Google</button>
                        <button id="dvSaferEscape_bing" class="dvSaferEscape_search-options" aria-label="not selected" data-name="bing">Bing</button>
                        <button id="dvSaferEscape_duckduckgo" class="dvSaferEscape_search-options" aria-label="not selected" data-name="duckduckgo">DuckDuckGo</button>
                    </div>
                    <input type="text" id="dvSaferSearch_search-input" placeholder="baby sloths">
                </div>                
                <p>
                    We will redirect you to a search results page if we detect any sudden jerking motions from your mobile phone. If you are using an iPhone, please tap <span id="dvSaferEscape_iphone-prompt">here</span> so we can ask you for permission to access information about your device’s motion. (Note: the motion-based redirection has only been tested with Chrome, Edge, Safari, and Firefox browsers.)
                </p>
                <p>
                    We will also redirect you after twenty seconds if you hide or minimize this browser tab.
                </p>
                <button id="dvSaferEscape_save-settings">
                    save settings
                </button>
            </div>
            </div>
            <div id="dvSaferEscape_safety-exit">
                <p>
                    <strong>EXIT</strong>
                </p>
                <button id="dvSaferEscape_settings-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><title>settings</title><path fill="currentColor" d="m9.25 22l-.4-3.2q-.325-.125-.612-.3t-.563-.375L4.7 19.375l-2.75-4.75l2.575-1.95Q4.5 12.5 4.5 12.338v-.675q0-.163.025-.338L1.95 9.375l2.75-4.75l2.975 1.25q.275-.2.575-.375t.6-.3l.4-3.2h5.5l.4 3.2q.325.125.613.3t.562.375l2.975-1.25l2.75 4.75l-2.575 1.95q.025.175.025.338v.674q0 .163-.05.338l2.575 1.95l-2.75 4.75l-2.95-1.25q-.275.2-.575.375t-.6.3l-.4 3.2zm2.8-6.5q1.45 0 2.475-1.025T15.55 12t-1.025-2.475T12.05 8.5q-1.475 0-2.488 1.025T8.55 12t1.013 2.475T12.05 15.5"/></svg></button>
            </div>
    <?php }

    function dvSaferEscape_settingsMenu() {
        add_options_page(
            'DV Safer Escape Settings',        // Page title
            'DV Safer Escape',                 // Menu title
            'manage_options',            // Capability
            'dv-safer-escape-settings',        // Menu slug
            array($this, 'dvSaferEscape_settingsPage')    // Callback function
        );
    }

    function dvSaferEscape_settingsPage() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div>
            <h1>DV Safer Escape Settings</h1>
            <br>
            <h2>Escape Button Styling</h2>
            <form method="post" action="options.php">
                <?php 
                settings_fields( 'dvSaferEscape-settings-group' );
                do_settings_sections( 'dvSaferEscape-settings-group' );
                $backgroundColor = get_option('escape_button_background_color', '#32153f');
                $textColor = get_option('escape_button_text_color', '#faf6ea');
                ?><table class="form-table">
                    <tr>
                        <th scope="row"><label for="escape_button_background_color">Background Color</label></th>
                        <td data-default="#32153f">
                            <input type="text" name="escape_button_background_color" value="<?php echo esc_attr($backgroundColor); ?>" class="dvSaferEscape_color-field" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="escape_button_text_color">Text Color</label></th>
                        <td data-default="#faf6ea">
                            <input type="text" name="escape_button_text_color" value="<?php echo esc_attr($textColor); ?>" class="dvSaferEscape_color-field" />
                        </td>
                    </tr>
                </table>
            <?php submit_button(); ?>
            </form>
        </div>
    <?php }

    function dvSaferEscape_admin_color_picker_assets( $hook_suffix ) {
        // Optional: Only load on your specific plugin/setting page
        // if ( 'settings_page_my-plugin' !== $hook_suffix ) { return; }
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script(
            'my-admin-picker-js',
            plugins_url( 'admin-js/admin-picker.js', __FILE__ ), // Adjust path to your JS file
            array( 'jquery', 'wp-color-picker' ),          // Set dependencies
            '1.0.0',
            true                                           // Load in footer
        );
    }

    function register_dvSaferEscape_plugin_settings() {
        register_setting( 'dvSaferEscape-settings-group', 'escape_button_background_color' );
        register_setting( 'dvSaferEscape-settings-group', 'escape_button_text_color' );
    }
}
$dvSaferEscapePlugin = new DVSaferEscapePlugin();