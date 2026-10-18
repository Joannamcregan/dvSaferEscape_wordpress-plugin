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
    }

    function registerScripts(){
        wp_register_style('dv-safer-escape-styles', plugins_url('css/styles.css', __FILE__), false, '1.0', 'all');
    }

    function pluginFiles(){
        wp_enqueue_style('dv-safer-escape-styles');
        wp_enqueue_script('pwf-services-js', plugin_dir_url(__FILE__) . '/build/index.js', array('jquery'), '1.0', true);
    }

    function addSaferEscapeInstructionsOverlay(){
        ?><div id="dvSaferEscape_safety-overlay" >
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
                        <button id="dvSaferEscape_google" class="dvSaferEscape_search-options dvSaferEscape_search-option-selected" aria-label="selected">Google</button>
                        <button id="dvSaferEscape_bing" class="dvSaferEscape_search-options" aria-label="not selected">Bing</button>
                        <button id="dvSaferEscape_duckduckgo" class="dvSaferEscape_search-options" aria-label="not selected">DuckDuckGo</button>
                    </div>
                    <input type="text" id="dvSaferSearch_search-input" placeholder="baby sloths">
                </div>                
                <p>
                    We will redirect you to a search results page if we detect any sudden jerking motions from your mobile phone. If you are using an iPhone, please tap <span id="dvSaferEscape_iphone-prompt">here</span> so we can ask you for permission to access information about your device’s motion.
                </p>
                <p>
                    We will also redirect you if you hide or minimize this browser tab.
                </p>
                <button id="dvSaferEscape_save-settings">
                    save settings
                </button>
            </div>
            </div>
            <div id="dvSaferEscape_safety-exit">
            <p>
            EXIT
            </p>  
            </div>
    <?php }
}
$dvSaferEscapePlugin = new DVSaferEscapePlugin();