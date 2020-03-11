<?php

/**
 * Event Settings Main
 *
 * @package   OnePlace\Event\Modules
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/event
 * @since 1.0.0
 */

namespace OnePlace\Event\Modules;

use OnePlace\Event\Plugin;

final class Settings {
    /**
     * Main instance of the module
     *
     * @var Plugin|null
     * @since 1.0.0
     */
    private static $instance = null;

    /**
     * Event Settings
     *
     *  @since 1.0.0
     */
    public function register() {
        // add custom scripts for admin section
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueueScripts' ] );

        // Add submenu page for settings
        add_action("admin_menu", [ $this, 'addSubMenuPage' ]);

        // Register Settings
        add_action( 'admin_init', [ $this, 'registerSettings' ] );

        // Add Plugin Languages
        add_action('plugins_loaded', [ $this, 'loadTextDomain' ] );
    }

    /**
     * load text domain (translations)
     *
     * @since 1.0.0
     */
    public function loadTextDomain() {
        load_plugin_textdomain( 'wp-plc-event', false, dirname( plugin_basename(WPPLC_EVENT_PLUGIN_MAIN_FILE) ) . '/language/' );
    }

    /**
     * Register Plugin Settings in Wordpress
     *
     * @since 1.0.0
     */
    public function registerSettings() {
        // Core Settings
        register_setting( 'wpplc_event', 'plcevent_elementor_active', false );
    }

    /**
     * Enqueue Style and Javascript for Admin Panel
     *
     * @since 1.0.0
     */
    public function enqueueScripts() {
    }

    /**
     * Add Submenu Page to OnePlace Settings Menu
     *
     * @since 1.0.0
     */
    public function addSubMenuPage() {
        $page_title = 'OnePlace Event';
        $menu_title = 'Event';
        $capability = 'manage_options';
        $menu_slug  = 'oneplace-connect';
        $function   = [$this,'AdminMenu'];
        $icon_url   = 'dashicons-media-code';
        $position   = 5;

        add_submenu_page( $menu_slug, $page_title, $menu_title,
            $capability, 'oneplace-event',  [$this,'renderSettingsPage'] );
    }

    /**
     * Render Settings Page for Plugin
     *
     * @since 1.0.0
     */
    public function renderSettingsPage() {
        require_once __DIR__.'/../view/settings.php';
    }

    /**
     * Loads the module main instance and initializes it.

     * @return bool True if the plugin main instance could be loaded, false otherwise.
     * @since 1.0.0
     */
    public static function load() {
        if ( null !== static::$instance ) {
            return false;
        }
        static::$instance = new self();
        static::$instance->register();
        return true;
    }
}