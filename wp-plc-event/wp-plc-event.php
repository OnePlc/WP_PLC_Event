<?php
/**
 * Plugin main file.
 *
 * @package   OnePlace\Event
 * @copyright 2020 OnePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch
 * @since 1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: WP PLC Event
 * Plugin URI:  https://1plc.ch/wordpress-plugins/event
 * Description: Show Events and sell Tickets with onePlace as Backend. Supports Elementor.
 * Version:     1.0.0
 * Author:      onePlace
 * Author URI:  https://1plc.ch
 * License:     GNU General Public License, version 2
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 * Text Domain: wp-plc-event
 */

// Some basic security
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if(!session_id()) {
    session_start();
}

// Define global constants
define( 'WPPLC_EVENT_VERSION', '1.0.0' );
define( 'WPPLC_EVENT_PLUGIN_MAIN_FILE', __FILE__ );
define( 'WPPLC_EVENT_PLUGIN_MAIN_DIR', __DIR__ );

/**
 * Handles plugin activation.
 *
 * Throws an error if the plugin is activated on an older version than PHP 5.4.
 *
 * @access private
 *
 * @param bool $network_wide Whether to activate network-wide.
 */
function wpplcevent_activate_plugin( $network_wide ) {
    if ( version_compare( PHP_VERSION, '5.4.0', '<' ) ) {
        wp_die(
            esc_html__( 'WP PLC Events requires PHP version 5.4.', 'wp-plc-event' ),
            esc_html__( 'Error Activating', 'wp-plc-event' )
        );
    }

    // check if oneplace connect is already loaded
    if ( ! defined('WPPLC_CONNECT_VERSION') ) {
        // show error if version cannot be determined
        wp_die(
            esc_html__( 'WP PLC Event requires WP PLC Connect', 'wp-plc-event' ),
            esc_html__( 'Error Activating', 'wp-plc-event' )
        );
    }
}

register_activation_hook( __FILE__, 'wpplcevent_activate_plugin' );

if ( version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/loader.php';
}