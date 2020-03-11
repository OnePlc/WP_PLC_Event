<?php

/**
 * Event Elementor Widgets
 *
 * @package   OnePlace\Event\Modules
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/shop
 * @since 1.0.0
 */

namespace OnePlace\Event\Modules;

use OnePlace\Event\Plugin;

final class Elementor {
    /**
     * Main instance of the module
     *
     * @var Plugin|null
     * @since 1.0.0
     */
    private static $instance = null;

    /**
     * Event Elementor Integration
     *
     * @since 1.0.0
     */
    public function register() {
        // Register Settings
        add_action( 'admin_init', [ $this, 'registerSettings' ] );

        // create category for elementor
        add_action( 'elementor/elements/categories_registered', [$this,'addElementorWidgetCategories'] );

        // register elementor widgets
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'initElementorWidgets' ] );

        // enqueue slider custom scripts for frontend
        add_action( 'wp_enqueue_scripts', [$this,'enqueueScripts'] );
    }

    /**
     * Enqueue Elementor Widget Frontend Custom Scripts
     *
     * @since 1.0.0
     */
    public function enqueueScripts() {
        if(get_option('plcevent_elementor_widget_calendar_year_list_active') == 1) {
            wp_enqueue_style( 'event-yearlist-style', plugins_url('assets/css/widget-yearlist.css', WPPLC_EVENT_PLUGIN_MAIN_FILE));

        }
            /**
        if(get_option('plcevent_elementor_widget_article_slider_active') == 1) {
            wp_enqueue_script('shop-article-slider', plugins_url('assets/js/article-slider.js', WPPLC_SHOP_PLUGIN_MAIN_FILE), ['jquery']);
            //wp_enqueue_style( 'shop-article-slider-style', '/wp-content/plugins/wp-plc-shop/assets/css/article-slider.css');
        }
        wp_enqueue_style( 'shop-base-style', plugins_url('assets/css/shop-base-style.css', WPPLC_SHOP_PLUGIN_MAIN_FILE));
         * */
    }

    /**
     * Initialize Elementor Widgets if activated
     *
     * @since 1.0.0
     */
    public function initElementorWidgets() {
        // Event Slider Widget
        if(get_option('plcevent_elementor_widget_event_slider_active') == 1) {
            require_once(__DIR__ . '/../Elementor/Widgets/Slider.php');
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WPPLC_Event_Slider());
        }

        // Calendar Year List Widget
        if(get_option('plcevent_elementor_widget_calendar_year_list_active') == 1) {
            require_once(__DIR__ . '/../Elementor/Widgets/Yearlist.php');
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WPPLC_Event_Yearlist());
        }

        // Calendar Compact List Widget
        if(get_option('plcevent_elementor_widget_calendar_list_small_active') == 1) {
            require_once(__DIR__ . '/../Elementor/Widgets/Compactlist.php');
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \WPPLC_Event_Compactlist());
        }

        // Set Language for all Date functions
        $sLang = 'en_US';
        if (defined('ICL_LANGUAGE_CODE')) {
            if (ICL_LANGUAGE_CODE == 'en') {
                $sLang = 'en_US';
            }
            if (ICL_LANGUAGE_CODE == 'de') {
                $sLang = 'de_DE';
            }
            $aParams['lang'] = $sLang;
        }
        setlocale(LC_TIME, $sLang);
    }

    /**
     * Create Category for our Elementor
     * Widgets
     *
     * @since 1.0.0
     */
    public function addElementorWidgetCategories( $elements_manager ) {
        $elements_manager->add_category(
            'wpplc-event',
            [
                'title' => __( 'onePlace Event', 'wp-plc-shop' ),
                'icon' => 'fa fa-calendar',
            ]
        );
    }

    /**
     * Register Elementor specific settings
     *
     * @since 1.0.0
     */
    public function registerSettings() {
        // Widgets
        register_setting( 'wpplc_event_elementor', 'plcevent_elementor_widget_event_slider_active', false );
        register_setting( 'wpplc_event_elementor', 'plcevent_elementor_widget_calendar_year_list_active', false );
        register_setting( 'wpplc_event_elementor', 'plcevent_elementor_widget_calendar_list_small_active', false );
    }

    /**
     * Loads the module main instance and initializes it.
     *
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