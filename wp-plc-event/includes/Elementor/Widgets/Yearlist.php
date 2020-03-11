<?php

/**
 * Elementor Year List Widget
 *
 * @package   OnePlace\Event\Modules
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/event
 * @since 1.0.0
 */

class WPPLC_Event_Yearlist extends \Elementor\Widget_Base {
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
    }

    public function get_name() {
        return 'wpplceventyearlist';
    }

    public function get_title() {
        return __('Year List', 'wp-plc-event');
    }

    public function get_icon() {
        return 'fa fa-list';
    }

    public function get_categories() {
        return ['wpplc-event'];
    }

    protected function render() {
        $aSettings = $this->get_settings_for_display();


        # Get Articles from onePlace API
        $aParams = [
            'listmode' => 'entity',
            'listmodefilter' => 'webonly',
            'filter' => 'onlycurrent',
            'filtervalue' => 'date_start',
            'filterlimit' => date('Y-m-d H:i:s',time()+(((3600*24)*30)*10)),
        ];
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/event/api/list/0', $aParams);

        if ($oAPIResponse->state == 'success') {
            echo $oAPIResponse->message;
            $aEvents = $oAPIResponse->results;
            $sHost = \OnePlace\Connect\Plugin::getCDNServerAddress();

            require WPPLC_EVENT_PLUGIN_MAIN_DIR.'/includes/view/partial/widget_yearlist.php';
        } else {
            echo 'ERROR CONNECTING TO EVENT SERVER';
        }

    }

    protected function _content_template() {

    }

    protected function _register_controls() {
        /**
         * Year List General Settings - START
         */
        $this->start_controls_section(
            'yearlist_general_settings',
            [
                'label' => __('Year List - General Settings', 'wp-plc-event'),
            ]
        );

        // List Mode
        $this->add_control(
            'yearlist_general_mode',
            [
                'label' => __('Event Filter', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'all' => 'All',
                    'onlybookable' => 'Only Bookable',
                    'onlyevents' => 'Only Non-Bookable',
                ],
                'default' => 'all',
            ]
        );

        $this->add_control(
            'list_item_height',
            [
                'label' => __( 'Height', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 400,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 90,
                ],
                'selectors' => [
                    '{{WRAPPER}} .plc-calendar-list-date, {{WRAPPER}} .plc-calendar-list-describe, {{WRAPPER}} .plc-calendar-list-img, {{WRAPPER}} .plc-calendar-list-item' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'yearlist_item_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-calendar-list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Year List General Settings - END
         */

        /**
         * Year List Image Settings - START
         */
        $this->start_controls_section(
            'yearlist_image_settings',
            [
                'label' => __('Year List - Image Settings', 'wp-plc-event'),
            ]
        );

        // Show Image
        $this->add_control(
            'yearlist_show_list_item_image',
            [
                'label' => __( 'Show Image', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-event' ),
                'label_off' => __( 'Hide', 'wp-plc-event' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // List Mode
        $this->add_control(
            'yearlist_image_width',
            [
                'label' => __('Width', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '100%' => 'Fullwidth',
                    '90px' => 'Box 90px',
                ],
                'default' => '100%',
            ]
        );

        $this->add_responsive_control(
            'yearlist_image_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-calendar-list-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Year List Image Settings - END
         */

        /**
         * Year List Date Settings - START
         */
        $this->start_controls_section(
            'yearlist_date_settings',
            [
                'label' => __('Year List - Date Settings', 'wp-plc-event'),
            ]
        );

        // List Mode
        $this->add_control(
            'yearlist_date_width',
            [
                'label' => __('Width', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '100%' => 'Fullwidth',
                    '90px' => 'Box 90px',
                ],
                'default' => ['100%'],
            ]
        );

        $this->add_responsive_control(
            'yearlist_date_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-calendar-list-date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Year List Date Settings - END
         */

        /**
         * Year List Title Settings - START
         */
        $this->start_controls_section(
            'yearlist_title_settings',
            [
                'label' => __('Year List - Title Settings', 'wp-plc-event'),
            ]
        );

        // List Mode
        $this->add_control(
            'yearlist_title_width',
            [
                'label' => __('Width', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '100%' => 'Fullwidth',
                    'calc(100% - ##OTHERWIDTH##)' => 'Box Auto',
                ],
                'default' => ['100%'],
            ]
        );

        $this->add_responsive_control(
            'yearlist_title_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-calendar-list-describe' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Year List Title Settings - END
         */

        /**
         * "Buy" Button Settings - START
         */
        $this->start_controls_section(
            'section_button_buy',
            [
                'label' => __('List Item - Button "Buy"', 'wp-plc-event'),
            ]
        );

        // Show Button
        $this->add_control(
            'yearlist_show_button_buy',
            [
                'label' => __( 'Show Buy Button', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-event' ),
                'label_off' => __( 'Hide', 'wp-plc-event' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Show Button
        $this->add_control(
            'yearlist_show_popup_basket',
            [
                'label' => __( 'Show Popup for Amount', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-event' ),
                'label_off' => __( 'Hide', 'wp-plc-event' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Button Text
        $this->add_control(
            'btn1_text',
            [
                'label' => __('Text', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Buy', 'wp-plc-event'),
                'placeholder' => __('Buy', 'wp-plc-event'),
            ]
        );

        // Button Icon
        $this->add_control(
            'btn1_selected_icon',
            [
                'label' => __('Icon', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'fa4compatibility' => 'icon',
            ]
        );

        $this->add_responsive_control(
            'button_buy_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-additem-tobasket' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * "Buy" Button Settings - END
         */

        /**
         * "Gift" Button Settings - START
         */
        $this->start_controls_section(
            'section_button_gift',
            [
                'label' => __('List Item - Button "Gift"', 'wp-plc-event'),
            ]
        );

        // Show Button
        $this->add_control(
            'yearlist_show_button_gift',
            [
                'label' => __( 'Show Gift Button', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-event' ),
                'label_off' => __( 'Hide', 'wp-plc-event' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Button Text
        $this->add_control(
            'btn2_text',
            [
                'label' => __('Text', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Gift', 'wp-plc-event'),
                'placeholder' => __('Gift', 'wp-plc-event'),
            ]
        );

        // Button Icon
        $this->add_control(
            'btn2_selected_icon',
            [
                'label' => __('Icon', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'fa4compatibility' => 'icon',
            ]
        );

        $this->add_responsive_control(
            'button_gift_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-shop-giftitem-tobasket' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * "Gift" Button Settings - END
         */

        /**
         * "More" Button Settings - START
         */
        $this->start_controls_section(
            'section_button_more',
            [
                'label' => __('List Item - Button "More"', 'wp-plc-event'),
            ]
        );

        // Show Button
        $this->add_control(
            'yearlist_show_button_more',
            [
                'label' => __( 'Show More Button', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-event' ),
                'label_off' => __( 'Hide', 'wp-plc-event' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Button Text
        $this->add_control(
            'btn3_text',
            [
                'label' => __('Text', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('More', 'wp-plc-event'),
                'placeholder' => __('More', 'wp-plc-event'),
            ]
        );

        // Button Icon
        $this->add_control(
            'btn3_selected_icon',
            [
                'label' => __('Icon', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'fa4compatibility' => 'icon',
            ]
        );

        $this->add_responsive_control(
            'button_more_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-event-show-popup' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * "More" Button Settings - END
         */

        /**
         * List - Month Title Style - START
         */
        $this->start_controls_section(
            'list_month_title',
            [
                'label' => __( 'Title - Months', 'elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'list_month_title_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} h3.plc-calendar-list-month',
            ]
        );

        $this->add_responsive_control(
            'list_month_title_padding',
            [
                'label' => __( 'Padding', 'oneplace' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} h3.plc-calendar-list-month' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'list_month_title_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} h3.plc-calendar-list-month' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * List - Month Title Style - END
         */

        /**
         * List Item - Date Style - START
         */
        $this->start_controls_section(
            'list_event_date',
            [
                'label' => __( 'List - Date', 'elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'list_event_date_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} div.plc-calendar-list-date',
            ]
        );

        $this->add_control(
            'list_event_date_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} div.plc-calendar-list-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'list_event_date_padding',
            [
                'label' => __( 'Padding', 'oneplace' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} div.plc-calendar-list-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'list_event_date_background',
                'label' => __( 'Background', 'oneplace' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} div.plc-calendar-list-date',
            ]
        );

        $this->end_controls_section();
        /**
         * List Item - Date Style - END
         */

        /**
         * List Item - Title Style - START
         */
        $this->start_controls_section(
            'list_event_title',
            [
                'label' => __( 'List - Title', 'elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'list_event_title_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} h4.plc-calendar-list-title',
            ]
        );

        $this->add_control(
            'list_event_title_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} h4.plc-calendar-list-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'list_event_title_padding',
            [
                'label' => __( 'Padding', 'oneplace' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} h4.plc-calendar-list-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'list_event_title_background',
                'label' => __( 'Background', 'oneplace' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} div.plc-calendar-list-describe',
            ]
        );

        $this->end_controls_section();
        /**
         * List Item - Title Style - END
         */

        /**
         * Buttons Style - START
         */
        $this->start_controls_section(
            'yearlist_buttons_style',
            [
                'label' => __( 'Slides - Buttons', 'wp-plc-shop' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Button Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'yearlist_button_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-slider-button,{{WRAPPER}} .plc-article-slider-more-lnk',
            ]
        );

        // Text Color for normal/hover
        $this->start_controls_tabs( 'tabs_button_style' );
        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'wp-plc-shop' ),
            ]
        );
        $this->add_control(
            'button_text_color',
            [
                'label' => __( 'Text Color', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_background_color',
            [
                'label' => __( 'Background Color', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'wp-plc-shop' ),
            ]
        );
        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Text Color', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_background_hover_color',
            [
                'label' => __( 'Background Color', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_hover_animation',
            [
                'label' => __( 'Hover Animation', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        // Button Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'yearlist_buttons_border',
                'selector' => '{{WRAPPER}} .plc-slider-button',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'yearlist_buttons_border_radius',
            [
                'label' => __( 'Border Radius', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'yearlist_buttons_box_shadow',
                'selector' => '{{WRAPPER}} .plc-slider-button',
            ]
        );
        $this->add_responsive_control(
            'yearlist_buttons_text_padding',
            [
                'label' => __( 'Padding', 'wp-plc-shop' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        // Slide Buttons Width
        $this->add_control(
            'buttons_slide_width',
            [
                'label' => __('Width', 'wp-plc-shop'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '100%' => 'Full Width',
                    'auto' => 'Auto',
                ],
                'default' => 'auto',
            ]
        );
        $this->end_controls_section();
        /**
         * Buttons Style - END
         */
    }
}