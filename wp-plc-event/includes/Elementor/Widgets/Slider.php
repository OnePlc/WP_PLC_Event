<?php
/**
 * Elementor Event Slider Widget
 *
 * @package   OnePlace\Event\Modules
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/event
 * @since 1.0.0
 */

class WPPLC_Event_Slider extends \Elementor\Widget_Base {
    /**
     * WPPLC_Event_Slider constructor.
     *
     * @param array $data
     * @param null $args
     * @since 1.0.0
     */
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
    }

    /**
     * Unique Name for Elementor Editor (internal)
     *
     * @return string
     * @since 1.0.0
     */
    public function get_name() {
        return 'wpplceventslider';
    }

    /**
     * Display Name for Elementor Editor
     *
     * @return mixed
     * @since 1.0.0
     */
    public function get_title() {
        return __('Event Slider', 'wp-plc-event');
    }

    /**
     * Icon for Elementor Editor
     *
     * @return string
     * @since 1.0.0
     */
    public function get_icon() {
        return 'fa fa-images';
    }

    /**
     * Category for Elementor Editor
     *
     * @return array
     * @since 1.0.0
     */
    public function get_categories() {
        return ['wpplc-event'];
    }

    /**
     * Render Elementor Widget
     *
     * @since 1.0.0
     */
    protected function render() {
        $aSettings = $this->get_settings_for_display();

        # Get Events from onePlace API
        $aParams = ['listmode'=>'entity','listmodefilter'=>'webonly'];
        if(is_numeric($aSettings['slider_base_category'])) {
            if($aSettings['slider_base_category'] > 0) {
                $aParams['filter'] = 'category';
                $aParams['filtervalue'] = (int)$aSettings['slider_base_category'];
            }
        } else {
            if($aSettings['slider_base_category'] == 'highlights') {
                $aParams['filter'] = 'highlights';
            }
        }
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/event/api/list/0', $aParams);

        if ($oAPIResponse->state == 'success') {
            $sHost = \OnePlace\Connect\Plugin::getCDNServerAddress();
            $sSliderID = \Elementor\Utils::generate_random_string();

            require WPPLC_EVENT_PLUGIN_MAIN_DIR.'/includes/view/partial/event_slider.php';
        } else {
            var_dump($oAPIResponse);
            echo 'ERROR CONNECTING TO SHOP SERVER';
        }

    }

    /**
     * Elementor Editor Template
     *
     * @since 1.0.0
     */
    protected function _content_template() {

    }

    /**
     * Elementor Widget Controls
     *
     * @since 1.0.0
     */
    protected function _register_controls() {
        /**
         * Get Data from onePlace API
         */
        $aOptions = [
            'all' => 'Alle',
            'highlights' => 'Highlights',
        ];
        $oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/tag/api/list/event-single/category', []);
        if(is_object($oAPIResponse)) {
            if($oAPIResponse->state == 'success') {
                foreach($oAPIResponse->results as $oCat) {
                    $aOptions[$oCat->id] = $oCat->text;
                }
            }
        }


        /**
         * Slider General Settings - START
         */
        $this->start_controls_section(
            'section_slider_settings',
            [
                'label' => __('Slider - General Settings', 'wp-plc-event'),
            ]
        );

        // Slides per View
        $this->add_control(
            'slider_slides_per_view',
            [
                'label' => __('Slides', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'default' => '3',
            ]
        );

        // Slider Base Category
        $this->add_control(
            'slider_base_category',
            [
                'label' => __('Kategorie', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $aOptions,
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * Slider General Settings - END
         */

        /**
         * Slide Content Settings - START
         */
        $this->start_controls_section(
            'section_slider_content_settings',
            [
                'label' => __('Slide - Content Settings', 'wp-plc-event'),
            ]
        );

        $this->add_responsive_control(
            'slider_slide_content_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-slide-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * Slide Content Settings - END
         */

        /**
        * Slider Slide Settings - START
        */
        $this->start_controls_section(
            'section_slider_slide_settings',
            [
                'label' => __('Slider - Slide Settings', 'wp-plc-event'),
            ]
        );

        // Show Image
        $this->add_control(
            'slider_slide_show_featuredimage',
            [
                'label' => __( 'Show Featured Image', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-event' ),
                'label_off' => __( 'Hide', 'wp-plc-event' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Show Date
        $this->add_control(
            'slider_slide_show_date',
            [
                'label' => __( 'Show Date', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-event' ),
                'label_off' => __( 'Hide', 'wp-plc-event' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Show Title
        $this->add_control(
            'slider_slide_show_title',
            [
                'label' => __( 'Show Title', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-event' ),
                'label_off' => __( 'Hide', 'wp-plc-event' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Show Description
        $this->add_control(
            'slider_slide_show_excerpt',
            [
                'label' => __( 'Show Excerpt', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-event' ),
                'label_off' => __( 'Hide', 'wp-plc-event' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Show Description
        $this->add_control(
            'slider_slide_show_description',
            [
                'label' => __( 'Show Description', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-event' ),
                'label_off' => __( 'Hide', 'wp-plc-event' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Show Price
        $this->add_control(
            'slider_slide_show_price',
            [
                'label' => __( 'Show Price', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-event' ),
                'label_off' => __( 'Hide', 'wp-plc-event' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * Slider Slide Settings - END
         */

        /**
         * Slider Slide Date Settings - START
         */
        $this->start_controls_section(
            'section_slider_slide_date_settings',
            [
                'label' => __('Slide - Date Settings', 'wp-plc-event'),
            ]
        );

        $this->add_responsive_control(
            'slider_slide_date_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-slide-date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * Slider Slide Date Settings - END
         */

        /**
         * Slider Slide Title Settings - START
         */
        $this->start_controls_section(
            'section_slider_slide_title_settings',
            [
                'label' => __('Slide - Title Settings', 'wp-plc-event'),
            ]
        );

        // Show Price
        $this->add_control(
            'slider_slide_show_title_hr',
            [
                'label' => __( 'Show Linebreak', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'wp-plc-event' ),
                'label_off' => __( 'Hide', 'wp-plc-event' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'slider_slide_title_hr_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} hr.plc-slider-slide-title-divider' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_slide_title_hr_margin',
            [
                'label' => __( 'Linebreak Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} hr.plc-slider-slide-title-divider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * Slider Slide Title Settings - END
         */

        /**
         * Slider Slide Price Settings - START
         */
        $this->start_controls_section(
            'section_slider_slide_price_settings',
            [
                'label' => __('Slide - Price Settings', 'wp-plc-event'),
            ]
        );

        $this->add_responsive_control(
            'slider_slide_price_margin',
            [
                'label' => __( 'Margin', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} select.plc-slider-slide-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        // End Section
        $this->end_controls_section();
        /**
         * Slider Slide Price Settings - END
         */

        /**
         * "Buy" Button Settings - START
         */
        $this->start_controls_section(
            'section_button_buy',
            [
                'label' => __('Slide - Button "Buy"', 'wp-plc-event'),
            ]
        );

        // Show Button
        $this->add_control(
            'slider_slide_show_button_buy',
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
            'slider_slide_show_popup_basket',
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
                    '{{WRAPPER}} .plc-event-additem-tobasket' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'label' => __('Slide - Button "Gift"', 'wp-plc-event'),
            ]
        );

        // Show Button
        $this->add_control(
            'slider_slide_show_button_gift',
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
                    '{{WRAPPER}} .plc-event-giftitem-tobasket' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
         * Slide Slide Settings - START
         */
        $this->start_controls_section(
            'slider_slide_style',
            [
                'label' => __( 'Slides', 'wp-plc-event' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'event_slider_slide_background',
                'label' => __( 'Background', 'oneplace' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .plc-slider-slide-content',
            ]
        );

        $this->add_responsive_control(
            'slider_slide_padding',
            [
                'label' => __( 'Padding', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-slide-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Slide Slide Settings - END
         */

        /**
         * Slider Slide Date - START
         */
        $this->start_controls_section(
            'slider_slide_date_style',
            [
                'label' => __( 'Slides - Date', 'wp-plc-event' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Height
        $this->add_control(
            'slider_slide_date_height',
            [
                'label' => __('Height', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '120px' => 'Fixed 90px',
                    'auto' => 'Auto',
                ],
                'default' => 'auto',
            ]
        );

        $this->add_control(
            'slider_slide_date_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} hr.plc-slider-slide-title-divider' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'slider_slide_date_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-slider-slide-date',
            ]
        );

        $this->add_control(
            'slider_slide_date_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-slide-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_slide_date_align',
            [
                'label' => __( 'Alignment', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'wp-plc-event' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'wp-plc-event' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'wp-plc-event' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'devices' => [ 'desktop', 'tablet' ],
                'prefix_class' => 'content-align-%s',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'event_slider_slide_date_background',
                'label' => __( 'Background', 'oneplace' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .plc-slider-slide-date',
            ]
        );

        $this->add_responsive_control(
            'slider_slide_date_padding',
            [
                'label' => __( 'Padding', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-slide-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Slider Slide Date - END
         */

        /**
         * Slider Slide Title - START
         */
        $this->start_controls_section(
            'slider_slide_title_style',
            [
                'label' => __( 'Slides - Title', 'wp-plc-event' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Height
        $this->add_control(
            'slider_slide_title_height',
            [
                'label' => __('Height', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '80px' => 'Fixed 80px',
                    '120px' => 'Fixed 120px',
                    'auto' => 'Auto',
                ],
                'default' => 'auto',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'slider_slide_title_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} h3.plc-slider-slide-title',
            ]
        );

        $this->add_control(
            'slider_slide_title_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} h3.plc-slider-slide-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_slide_title_align',
            [
                'label' => __( 'Alignment', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'wp-plc-event' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'wp-plc-event' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'wp-plc-event' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'devices' => [ 'desktop', 'tablet' ],
                'prefix_class' => 'content-align-%s',
            ]
        );

        $this->add_responsive_control(
            'slider_slide_title_padding',
            [
                'label' => __( 'Padding', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} h3.plc-slider-slide-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Slider Slide Title - END
         */

        /**
         * Slider Slide Excerpt - START
         */
        $this->start_controls_section(
            'slider_slide_excerpt_style',
            [
                'label' => __( 'Slides - Excerpt', 'wp-plc-event' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Height
        $this->add_control(
            'slider_slide_excerpt_height',
            [
                'label' => __('Height', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '120px' => 'Fixed 120px',
                    '160px' => 'Fixed 160px',
                    'auto' => 'Auto',
                ],
                'default' => 'auto',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'slider_slide_excerpt_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-slider-slide-excerpt',
            ]
        );

        $this->add_control(
            'slider_slide_excerpt_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-slide-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_slide_excerpt_align',
            [
                'label' => __( 'Alignment', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'wp-plc-event' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'wp-plc-event' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'wp-plc-event' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'devices' => [ 'desktop', 'tablet' ],
                'prefix_class' => 'content-align-%s',
            ]
        );

        $this->add_responsive_control(
            'slider_slide_excerpt_padding',
            [
                'label' => __( 'Padding', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-slide-excerpt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Slider Slide Excerpt - END
         */

        /**
         * Slider Slide Description - START
         */
        $this->start_controls_section(
            'slider_slide_description_style',
            [
                'label' => __( 'Slides - Description', 'wp-plc-event' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Height
        $this->add_control(
            'slider_slide_desc_height',
            [
                'label' => __('Height', 'wp-plc-event'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '120px' => 'Fixed 120px',
                    'auto' => 'Auto',
                ],
                'default' => 'auto',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'slider_slide_desc_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} div.plc-slider-slide-description',
            ]
        );

        $this->add_control(
            'slider_slide_desc_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} div.plc-slider-slide-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_slide_desc_align',
            [
                'label' => __( 'Alignment', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'wp-plc-event' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'wp-plc-event' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'wp-plc-event' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'devices' => [ 'desktop', 'tablet' ],
                'prefix_class' => 'content-align-%s',
            ]
        );

        $this->add_responsive_control(
            'slider_slide_desc_padding',
            [
                'label' => __( 'Padding', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} div.plc-slider-slide-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Slider Slide Description - END
         */

        /**
         * Slider Slide Price - START
         */
        $this->start_controls_section(
            'slider_slide_price_style',
            [
                'label' => __( 'Slides - Price', 'wp-plc-event' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'slider_slide_price_typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} select.plc-slider-slide-price',
            ]
        );

        $this->add_control(
            'slider_slide_price_color',
            [
                'label' => __( 'Textfarbe', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#575756',
                'selectors' => [
                    '{{WRAPPER}} select.plc-slider-slide-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'slider_slide_price_background_color',
            [
                'label' => __( 'Background Color', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} select.plc-slider-slide-price' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_slide_price_align',
            [
                'label' => __( 'Alignment', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'wp-plc-event' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'wp-plc-event' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'wp-plc-event' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'devices' => [ 'desktop', 'tablet' ],
                'prefix_class' => 'content-align-%s',
            ]
        );

        $this->add_responsive_control(
            'slider_slide_price_padding',
            [
                'label' => __( 'Padding', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} select.plc-slider-slide-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Button Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'slider_slide_price_border',
                'selector' => '{{WRAPPER}} select.plc-slider-slide-price',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'slider_slide_price_border_radius',
            [
                'label' => __( 'Border Radius', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} select.plc-slider-slide-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /**
         * Slider Slide Price - END
         */

        /**
         * Buttons Style - START
         */
        $this->start_controls_section(
            'slider_slide_buttons_style',
            [
                'label' => __( 'Slides - Buttons', 'wp-plc-event' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Button Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .plc-slider-button,{{WRAPPER}} .plc-event-slider-more-lnk',
            ]
        );

        // Text Color for normal/hover
        $this->start_controls_tabs( 'tabs_button_style' );
        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'wp-plc-event' ),
            ]
        );
        $this->add_control(
            'button_text_color',
            [
                'label' => __( 'Text Color', 'wp-plc-event' ),
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
                'label' => __( 'Background Color', 'wp-plc-event' ),
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
                'label' => __( 'Hover', 'wp-plc-event' ),
            ]
        );
        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Text Color', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_background_hover_color',
            [
                'label' => __( 'Background Color', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'wp-plc-event' ),
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
                'label' => __( 'Hover Animation', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        // Button Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'slider_slide_buttons_border',
                'selector' => '{{WRAPPER}} .plc-slider-button',
                'separator' => 'before',
            ]
        );
        // Buttons Border Radius
        $this->add_control(
            'slider_slide_buttons_border_radius',
            [
                'label' => __( 'Border Radius', 'wp-plc-event' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .plc-slider-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        // Buttons Box Shadow
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'slider_slide_buttons_box_shadow',
                'selector' => '{{WRAPPER}} .plc-slider-button',
            ]
        );
        // Buttons Padding
        $this->add_responsive_control(
            'slider_slide_buttons_text_padding',
            [
                'label' => __( 'Padding', 'wp-plc-event' ),
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
                'label' => __('Width', 'wp-plc-event'),
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