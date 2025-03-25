<?php
/**
 * Shortcode: AGenerator (Elementor support)
 *
 * @package ThemeREX Addons
 * @since v2.31.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

use TrxAddons\AiHelper\Lists;
use TrxAddons\AiHelper\Utils;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Modules\DynamicTags\Module as TagsModule;


// Elementor Widget
//------------------------------------------------------
if ( ! function_exists('trx_addons_sc_agenerator_add_in_elementor')) {
	add_action( trx_addons_elementor_get_action_for_widgets_registration(), 'trx_addons_sc_agenerator_add_in_elementor' );
	function trx_addons_sc_agenerator_add_in_elementor() {
		
		if ( ! class_exists( 'TRX_Addons_Elementor_Widget' ) ) return;	

		class TRX_Addons_Elementor_Widget_AGenerator extends TRX_Addons_Elementor_Widget {

			var $is_edit_mode = false;

			/**
			 * Widget base constructor.
			 *
			 * Initializing the widget base class.
			 *
			 * @since 1.6.41
			 * @access public
			 *
			 * @param array      $data Widget data. Default is an empty array.
			 * @param array|null $args Optional. Widget default arguments. Default is null.
			 */
			public function __construct( $data = array(), $args = null ) {
				parent::__construct( $data, $args );
				$this->add_plain_params( array(
					'prompt_width' => 'size',
					'button_image' => 'url',
				) );
			}

			/**
			 * Retrieve widget name.
			 *
			 * @since 1.6.41
			 * @access public
			 *
			 * @return string Widget name.
			 */
			public function get_name() {
				return 'trx_sc_agenerator';
			}

			/**
			 * Retrieve widget title.
			 *
			 * @since 1.6.41
			 * @access public
			 *
			 * @return string Widget title.
			 */
			public function get_title() {
				return __( 'AI Helper Audio Generator', 'trx_addons' );
			}

			/**
			 * Get widget keywords.
			 *
			 * Retrieve the list of keywords the widget belongs to.
			 *
			 * @since 2.27.2
			 * @access public
			 *
			 * @return array Widget keywords.
			 */
			public function get_keywords() {
				return [ 'ai', 'helper', 'generator', 'agenerator', 'audio', 'voice', 'speech', 'ai audio', 'ai generator' ];
			}

			/**
			 * Retrieve widget icon.
			 *
			 * @since 1.6.41
			 * @access public
			 *
			 * @return string Widget icon.
			 */
			public function get_icon() {
				return 'eicon-play trx_addons_elementor_widget_icon';
			}

			/**
			 * Retrieve the list of categories the widget belongs to.
			 *
			 * Used to determine where to display the widget in the editor.
			 *
			 * @since 1.6.41
			 * @access public
			 *
			 * @return array Widget categories.
			 */
			public function get_categories() {
				return ['trx_addons-elements'];
			}

			/**
			 * Register widget controls.
			 *
			 * Adds different input fields to allow the user to change and customize the widget settings.
			 *
			 * @since 1.6.41
			 * @access protected
			 */
			protected function register_controls() {
				$this->is_edit_mode = trx_addons_elm_is_edit_mode();

				$this->register_controls_content_general();
				$this->register_controls_content_generator_settings();
				$this->register_controls_content_demo_audio();

				$this->register_controls_style_sc_content();
				$this->register_controls_style_sc_form();
				$this->register_controls_style_tabs();
				$this->register_controls_style_sc_form_field();
				$this->register_controls_style_button_generate();
				$this->register_controls_style_settings_button();
				$this->register_controls_style_settings_popup();
				$this->register_controls_style_settings_field();
				$this->register_controls_style_limits();
				$this->register_controls_style_message();
				$this->register_controls_style_audio_preview();
				$this->register_controls_style_single_audio();
				$this->register_controls_style_audio_player();
				$this->register_controls_style_button_download();

				if ( apply_filters( 'trx_addons_filter_add_title_param', true, $this->get_name() ) ) {
					$this->add_title_param();
				}
			}

			/**
			 * Register widget controls: tab 'Content' section 'AI Helper Audio Generator'
			 */
			protected function register_controls_content_general() {

				// Register controls
				$this->start_controls_section(
					'section_sc_agenerator',
					[
						'label' => __( 'AI Helper Audio Generator', 'trx_addons' ),
					]
				);

				$this->add_control(
					'type',
					[
						'label' => __( 'Layout', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::SELECT,
						'options' => apply_filters('trx_addons_sc_type', Lists::get_list_sc_audio_generator_layouts(), 'trx_sc_agenerator'),
						'default' => 'default'
					]
				);

				$this->add_control(
					'placeholder_text',
					[
						'label' => __( 'Placeholder', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::TEXT,
						'default' => ''
					]
				);

				$this->add_control(
					'button_text',
					[
						'label' => __( 'Button text', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::TEXT,
						'default' => ''
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Content' section 'Generator Settings'
			 */
			protected function register_controls_content_generator_settings() {

				// Section: Generator settings
				$this->start_controls_section(
					'section_sc_agenerator_settings',
					[
						'label' => __( 'Generator Settings', 'trx_addons' ),
					]
				);

				$this->add_control(
					'premium',
					[
						'label' => __( 'Premium Mode', 'trx_addons' ),
						'label_block' => false,
						'description' => __( 'Enables you to set a broader range of limits for image generation, which can be used for a paid image generation service. The limits are configured in the global settings.', 'trx_addons' ),
						'type' => Controls_Manager::SWITCHER,
						'return_value' => '1',
					]
				);

				$this->add_control(
					'show_limits',
					[
						'label' => __( 'Show limits', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::SWITCHER,
						'return_value' => '1',
					]
				);

				$this->add_control(
					'show_settings',
					[
						'label' => __( 'Show button "Settings"', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::SWITCHER,
						'return_value' => '1'
					]
				);

				$this->add_control(
					'show_download',
					[
						'label' => __( 'Show button "Download"', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::SWITCHER,
						'return_value' => '1',
					]
				);

				$this->add_control(
					'base64',
					[
						'label' => __( 'Use Base64', 'trx_addons' ),
						'label_block' => false,
						'description' => __( "Pass the original audio/voice to the generation server inside a query (using Base64 encoding) or via a temporary URL (the file will be cached on your server for some time, not suitable for local installations inaccessible from the Internet)", 'trx_addons' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => '',
						'return_value' => '1',
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Content' section 'Demo Audio'
			 */
			protected function register_controls_content_demo_audio() {

				// Section: Demo audio
				$this->start_controls_section(
					'section_sc_agenerator_demo',
					[
						'label' => __( 'Demo Audio', 'trx_addons' ),
					]
				);

				$repeater = new Repeater();
		
				$repeater->add_control(
					'audio',
					[
						'label' => __( 'Audio', 'trx_addons' ),
						'description' => wp_kses_data( __("Selected files will be used instead of the audio generator as a demo mode when limits are reached", 'trx_addons') ),
						'type' => Controls_Manager::MEDIA,
						'dynamic' => [
							'active' => true,
							'categories' => [
								TagsModule::MEDIA_CATEGORY,
							],
						],
						'media_types' => [
							'audio',
						],
						'default' => [],
					]
				);

				$this->add_control(
					'demo_audio',
					[
						'type'        => Controls_Manager::REPEATER,
						'fields'      => $repeater->get_controls(),
						'title_field' => '{{{trx_addons_get_file_name(audio.url,false)}}}',
					]
				);
		
				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Audio Generator'
			 */
			protected function register_controls_style_sc_content() {

				$this->start_controls_section(
					'section_sc_agenerator_content_style',
					[
						'label' => __( 'Audio Generator', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'sc_content_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_content'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'sc_content_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_content',
					)
				);
		
				$this->add_responsive_control(
					'sc_content_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_responsive_control(
					'sc_content_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'sc_content_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
		
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'sc_content_box_shadow',
						'selector' => '{{WRAPPER}} .sc_agenerator_content',
					]
				);
		
				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Form'
			 */
			protected function register_controls_style_sc_form() {

				$this->start_controls_section(
					'section_sc_agenerator_form_style',
					[
						'label' => __( 'Form', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->add_responsive_control(
					'sc_form_fields_spacing',
					[
						'label' => __( 'Fields Spacing', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'em'
						],
						'size_units' => [ 'px', 'em', 'rem', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'sc_form_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_form'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'sc_form_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form',
					)
				);
		
				$this->add_responsive_control(
					'sc_form_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);
		
				$this->add_responsive_control(
					'sc_form_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'sc_form_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
		
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'sc_form_box_shadow',
						'selector' => '{{WRAPPER}} .sc_agenerator_form',
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Tabs'
			 */
			protected function register_controls_style_tabs() {

				$this->start_controls_section(
					'section_sc_agenerator_tabs_style',
					[
						'label' => __( 'Tabs', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					]
				);

				$this->add_responsive_control(
					'tab_slider_heading',
					[
						'label' => __( 'Slider', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
					]
				);

				$this->add_responsive_control(
					'tab_slider_height',
					[
						'label' => __( 'Height', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::NUMBER,
						'default' => 2,
						'min' => 0,
						'max' => 10,
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_actions_list:after,
							 {{WRAPPER}} .sc_agenerator_form_actions_slider' => 'height: {{VALUE}}px;',
						],
					]
				);

				$this->add_responsive_control(
					'tab_slider_offset',
					[
						'label' => __( 'Offset', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::NUMBER,
						'default' => '',
						'min' => -20,
						'max' => 20,
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_actions_list:after,
							 {{WRAPPER}} .sc_agenerator_form_actions_slider' => 'bottom: {{VALUE}}px;',
						],
					]
				);

				$this->add_control(
					"tab_slider_color",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_actions_slider' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"tab_slider_line_color",
					[
						'label' => __( 'Line Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_actions_list:after' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'tab_items_heading',
					[
						'label' => __( 'Tabs', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'tab_typography',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_actions_item > a'
					]
				);

				$this->add_responsive_control(
					'tab_gap',
					[
						'label' => __( 'Gap', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'em'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vw', '%', 'custom' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_actions_list' => 'gap: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->start_controls_tabs( 'tabs_sc_agenerator_tabs_style' );

				$this->start_controls_tab(
					'tab_sc_agenerator_tab_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					"tab_text_color",
					[
						'label' => __( 'Text Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_actions_item > a' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'tab_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_actions_item > a'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'tab_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_actions_item > a',
					)
				);
		
				$this->add_responsive_control(
					'tab_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_actions_item > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'tab_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_actions_item > a',
					]
				);

				$this->add_responsive_control(
					'tab_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form_actions_item > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
						'separator'             => 'before',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_agenerator_tab_hover',
					[
						'label' => __( 'Hover', 'trx_addons' ),
					]
				);

				$this->add_control(
					"tab_text_color_hover",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_actions_item:not(.sc_agenerator_form_actions_item_active) > a:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'tab_background_hover',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_actions_item:not(.sc_agenerator_form_actions_item_active) > a:hover'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'tab_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_actions_item:not(.sc_agenerator_form_actions_item_active) > a:hover',
					)
				);
		
				$this->add_responsive_control(
					'tab_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_actions_item:not(.sc_agenerator_form_actions_item_active) > a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'tab_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_actions_item:not(.sc_agenerator_form_actions_item_active) > a:hover',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_agenerator_tab_active',
					[
						'label' => __( 'Active', 'trx_addons' ),
					]
				);

				$this->add_control(
					"tab_text_color_active",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_actions_item_active > a' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'tab_background_active',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_actions_item_active > a'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'tab_border_active',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_actions_item_active > a',
					)
				);
		
				$this->add_responsive_control(
					'tab_border_radius_active',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_actions_item_active > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'tab_shadow_active',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_actions_item_active > a',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Form field'
			 */
			protected function register_controls_style_sc_form_field() {

				$this->start_controls_section(
					'section_sc_agenerator_form_field_style',
					[
						'label' => __( 'Form Field', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'sc_form_field_typography',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field input,
										{{WRAPPER}} .sc_agenerator_form_field select,
										{{WRAPPER}} .sc_agenerator_form_field .select_container,
										{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator,
										{{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator'
					]
				);

				$this->start_controls_tabs( 'tabs_sc_agenerator_form_field_style' );

				$this->start_controls_tab(
					'tab_sc_agenerator_form_field_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					"sc_form_field_text_color",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field input,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator,
							 {{WRAPPER}} .sc_agenerator_form_field .select_container:after,
							 {{WRAPPER}} .sc_agenerator_form_field select,
							 {{WRAPPER}} .sc_agenerator_form_field .sc_agenerator_form_field_numeric_wrap_button:before' => 'color: {{VALUE}};',
							// Additional rule to override the select field background with !important
							// '{{WRAPPER}} .sc_agenerator_form_field .select_container select' => 'background-color: {{sc_form_field_background_color.VALUE}} !important;',
						],
					]
				);

				$this->add_control(
					"sc_form_field_placeholder_color",
					[
						'label' => __( 'Placeholder color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field input[placeholder]::placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field input[placeholder]::-moz-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field input[placeholder]::-webkit-input-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator .sc_agenerator_form_field_upload_audio_text.theme_form_field_placeholder,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator .sc_agenerator_form_field_upload_audio_text.theme_form_field_placeholder' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"sc_form_field_browse_color",
					[
						'label' => __( 'Button "Browse" color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field_upload_audio_button,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_button' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'sc_form_field_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field input,
										{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator,
										{{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator,
										{{WRAPPER}} .sc_agenerator_form_field select,
										{{WRAPPER}} .sc_agenerator_form_field .select_container:before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'sc_form_field_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_field input,
										{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator,
										{{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator,
										{{WRAPPER}} .sc_agenerator_form_field select',
					)
				);
		
				$this->add_responsive_control(
					'sc_form_field_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_field input,
										 {{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator,
										 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator,
										 {{WRAPPER}} .sc_agenerator_form_field select,
										 {{WRAPPER}} .sc_agenerator_form_field .select_container,
										 {{WRAPPER}} .sc_agenerator_form_field .select_container:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
					)
				);

				$this->add_responsive_control(
					'sc_form_field_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form_field input,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator,
							 {{WRAPPER}} .sc_agenerator_form_field select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'sc_form_field_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field input,
										{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator,
										{{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator,
										{{WRAPPER}} .sc_agenerator_form_field :not(.select_container) > select,
										{{WRAPPER}} .sc_agenerator_form_field .select_container',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_agenerator_form_field_focus',
					[
						'label' => __( 'Focus', 'trx_addons' ),
					]
				);

				$this->add_control(
					"sc_form_field_text_color_focus",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field input:focus,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:focus,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:hover,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:focus,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:hover,
							 {{WRAPPER}} .sc_agenerator_form_field select:focus,
							 {{WRAPPER}} .sc_agenerator_form_field input:focus + .sc_agenerator_form_field_numeric_wrap_buttons .sc_agenerator_form_field_numeric_wrap_button:before' => 'color: {{VALUE}};',
							// Additional rule to override the select field background with !important
							// '{{WRAPPER}} .sc_agenerator_form_field .select_container select:focus' => 'background-color: {{sc_form_field_background_color.VALUE}} !important;',
						],
					]
				);

				$this->add_control(
					"sc_form_field_placeholder_color_focus",
					[
						'label' => __( 'Placeholder color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field input[placeholder]:focus::placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field input[placeholder]:focus::-moz-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field input[placeholder]:focus::-webkit-input-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:focus .sc_agenerator_form_field_upload_audio_text.theme_form_field_placeholder,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:focus .sc_agenerator_form_field_upload_audio_text.theme_form_field_placeholder' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"sc_form_field_browse_color_hover",
					[
						'label' => __( 'Button "Browse" color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:focus .sc_agenerator_form_field_upload_audio_button,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:hover .sc_agenerator_form_field_upload_audio_button,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:focus .sc_agenerator_form_field_upload_voice_modelslab_button,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:hover .sc_agenerator_form_field_upload_voice_modelslab_button' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'sc_form_field_background_focus',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field input:focus,
										{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:focus,
							 			{{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:focus,
										{{WRAPPER}} .sc_agenerator_form_field select:focus',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'sc_form_field_border_focus',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_field input:focus,
										{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:focus,
							 			{{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:focus,
										{{WRAPPER}} .sc_agenerator_form_field select:focus',
					)
				);
		
				$this->add_responsive_control(
					'sc_form_field_border_radius_focus',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_field input:focus,
										 {{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:focus,
							 			 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:focus,
										 {{WRAPPER}} .sc_agenerator_form_field select:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'sc_form_field_shadow_focus',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field input:focus,
										{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:focus,
							 			{{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:focus,
										{{WRAPPER}} .sc_agenerator_form_field select:focus',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->add_control(
					"sc_form_field_label_heading",
					[
						'label' => __( 'Fields Label', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'sc_form_field_label_typography',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field label,
										{{WRAPPER}} .sc_agenerator_form_field_tags_label'
					]
				);

				$this->add_control(
					"sc_form_field_label_color",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field label,
							 {{WRAPPER}} .sc_agenerator_form_field_tags_label' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'sc_form_field_label_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form_field label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					"sc_form_field_description_heading",
					[
						'label' => __( 'Fields Description', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'sc_form_field_description_typography',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field_description'
					]
				);

				$this->add_control(
					"sc_form_field_description_color",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field_description' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'sc_form_field_description_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form_field_description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Button Generate'
			 */
			protected function register_controls_style_button_generate() {

				$this->start_controls_section(
					'section_sc_agenerator_prompt_button_style',
					[
						'label' => __( 'Button "Generate"', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'button_typography',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field_generate_button'
					]
				);

				$this->add_control( 'button_image',
					[
						'label' => esc_html__( 'Image', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::MEDIA,
						'media_types' => [ 'image', 'svg' ],
					]
				);

				$params = trx_addons_get_icon_param( 'button_icon' );
				$params = trx_addons_array_get_first_value( $params );
				unset( $params['name'] );
				$params['condition'] = [
					'button_image[url]' => '',
				];
				$this->add_control( 'button_icon', $params );

				$this->add_responsive_control(
					'button_icon_size',
					[
						'label' => __( 'Icon Size', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'em'
						],
						'size_units' => [ 'px', 'em', 'rem', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field_generate_button .sc_button_icon' => 'font-size: {{SIZE}}{{UNIT}};',
						],
						'conditions' => array(
							'relation' => 'or',
							'terms'    => array(
								array(
									'name'     => 'button_icon',
									'operator' => '!==',
									'value'    => array( '', 'none' ),
								),
								array(
									'name'     => 'button_image[url]',
									'operator' => '!==',
									'value'    => '',
								),
							),
						),
					]
				);

				$this->add_responsive_control(
					'button_icon_margin',
					[
						'label'                 => esc_html__( 'Icon Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form_field_generate_button .sc_button_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->start_controls_tabs( 'tabs_sc_agenerator_button_generate_style' );

				$this->start_controls_tab(
					'tab_sc_agenerator_button_generate_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					"button_text_color",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field_generate_button' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"button_icon_color",
					[
						'label' => __( 'Icon color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field_generate_button .sc_button_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field_generate_button .sc_button_icon svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'button_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field_generate_button'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'button_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_field_generate_button',
					)
				);
		
				$this->add_responsive_control(
					'button_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_field_generate_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field_generate_button',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_agenerator_button_generate_hover',
					[
						'label' => __( 'Hover', 'trx_addons' ),
					]
				);

				$this->add_control(
					"button_text_color_hover",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field_generate_button:not(.sc_agenerator_form_field_disabled):hover,
							 {{WRAPPER}} .sc_agenerator_form_field_generate_button:not(.sc_agenerator_form_field_disabled):focus' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"button_icon_color_hover",
					[
						'label' => __( 'Icon color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field_generate_button:not(.sc_agenerator_form_field_disabled):hover .sc_button_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field_generate_button:not(.sc_agenerator_form_field_disabled):focus .sc_button_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field_generate_button:not(.sc_agenerator_form_field_disabled):hover .sc_button_icon svg' => 'fill: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field_generate_button:not(.sc_agenerator_form_field_disabled):focus .sc_button_icon svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'button_background_hover',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field_generate_button:not(.sc_agenerator_form_field_disabled):hover,
										{{WRAPPER}} .sc_agenerator_form_field_generate_button:not(.sc_agenerator_form_field_disabled):focus'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'button_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_field_generate_button:not(.sc_agenerator_form_field_disabled):hover,
										{{WRAPPER}} .sc_agenerator_form_field_generate_button:not(.sc_agenerator_form_field_disabled):focus',
					)
				);
		
				$this->add_responsive_control(
					'button_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_field_generate_button:not(.sc_agenerator_form_field_disabled):hover,
										{{WRAPPER}} .sc_agenerator_form_field_generate_button:not(.sc_agenerator_form_field_disabled):focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field_generate_button:not(.sc_agenerator_form_field_disabled):hover,
										{{WRAPPER}} .sc_agenerator_form_field_generate_button:not(.sc_agenerator_form_field_disabled):focus',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_agenerator_button_generate_disabled',
					[
						'label' => __( 'Disabled', 'trx_addons' ),
					]
				);

				$this->add_control(
					"button_text_color_disabled",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field_generate_button.sc_agenerator_form_field_disabled' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"button_icon_color_disabled",
					[
						'label' => __( 'Icon color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field_generate_button.sc_agenerator_form_field_disabled .sc_button_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field_generate_button.sc_agenerator_form_field_disabled .sc_button_icon svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'button_background_disabled',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field_generate_button.sc_agenerator_form_field_disabled'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'button_border_disabled',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_field_generate_button.sc_agenerator_form_field_disabled',
					)
				);
		
				$this->add_responsive_control(
					'button_border_radius_disabled',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_field_generate_button.sc_agenerator_form_field_disabled' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_shadow_disabled',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field_generate_button.sc_agenerator_form_field_disabled',
					]
				);

				$this->add_responsive_control(
					'button_opacity_disabled',
					[
						'label' => __( 'Opacity', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1,
								'step' => 0.01
							]
						],
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field_generate_button.sc_agenerator_form_field_disabled' => 'opacity: {{SIZE}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Button Settings'
			 */
			protected function register_controls_style_settings_button() {

				$this->start_controls_section(
					'section_sc_agenerator_settings_button_style',
					[
						'label' => __( 'Button "Settings"', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
						'condition' => [
							'show_settings' => '1'
						]
					]
				);

				$params = trx_addons_get_icon_param( 'settings_button_icon' );
				$params = trx_addons_array_get_first_value( $params );
				unset( $params['name'] );
				$this->add_control( 'settings_button_icon', $params );

				$this->add_responsive_control(
					'settings_button_icon_size',
					[
						'label' => __( 'Icon Size', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'em'
						],
						'size_units' => [ 'px', 'em', 'rem', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_settings_button:before' => 'font-size: {{SIZE}}{{UNIT}};',
						],
						'condition' => [
							'settings_button_icon!' => ['', 'none'],
						],
					]
				);

				// $this->add_control( 'settings_button_image',
				// 	[
				// 		'label' => esc_html__( 'Image', 'trx_addons' ),
				// 		'type' => \Elementor\Controls_Manager::MEDIA,
				// 		'media_types' => [ 'image', 'svg' ],
				// 		'condition' => [
				// 			'settings_button_icon' => ['', 'none'],
				// 		],
				// 	]
				// );

				$this->start_controls_tabs( 'tabs_sc_agenerator_settings_button_style' );

				$this->start_controls_tab(
					'tab_sc_agenerator_settings_button_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					"settings_button_icon_color",
					[
						'label' => __( 'Icon color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_settings_button' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_settings_button svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'settings_button_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings_button'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'settings_button_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_settings_button',
					)
				);
		
				$this->add_responsive_control(
					'settings_button_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_settings_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'settings_button_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings_button',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_agenerator_settings_button_hover',
					[
						'label' => __( 'Hover', 'trx_addons' ),
					]
				);

				$this->add_control(
					"settings_button_icon_color_hover",
					[
						'label' => __( 'Icon color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_settings_button:hover' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_settings_button:hover svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'settings_button_background_hover',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings_button:hover'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'settings_button_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_settings_button:hover',
					)
				);
		
				$this->add_responsive_control(
					'settings_button_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_settings_button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'settings_button_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings_button:hover',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Popup Settings'
			 */
			protected function register_controls_style_settings_popup() {

				$this->start_controls_section(
					'section_sc_agenerator_settings_popup_style',
					[
						'label' => __( 'Popup "Settings"', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
						'condition' => [
							'show_settings' => '1'
						]
					]
				);

				$this->add_responsive_control(
					'settings_fields_spacing',
					[
						'label' => __( 'Fields Spacing', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'em'
						],
						'size_units' => [ 'px', 'em', 'rem', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_settings_field + .sc_agenerator_form_settings_field' => 'margin-top: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'settings_popup_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'settings_popup_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_settings',
					)
				);
		
				$this->add_responsive_control(
					'settings_popup_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_settings' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);
		
				$this->add_responsive_control(
					'settings_popup_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form_settings' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'settings_popup_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form_settings' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
		
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'settings_popup_box_shadow',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings',
					]
				);

				$this->add_control(
					"settings_popup_scrollbar_color",
					[
						'label' => __( 'Scrollbar Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_settings' => 'scrollbar-color: {{VALUE}} {{settings_popup_scrollbar_slider.VALUE}};',
						],
					]
				);

				$this->add_control(
					"settings_popup_scrollbar_slider",
					[
						'label' => __( 'Scrollbar Slider', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_settings' => 'scrollbar-color: {{settings_popup_scrollbar_color.VALUE}} {{VALUE}};',
						],
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Field Settings'
			 */
			protected function register_controls_style_settings_field() {

				$this->start_controls_section(
					'section_sc_agenerator_settings_field_style',
					[
						'label' => __( 'Fields in "Settings"', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'settings_field_typography',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings_field input,
										{{WRAPPER}} .sc_agenerator_form_settings_field select,
										{{WRAPPER}} .sc_agenerator_form_settings_field .select_container'
					]
				);

				$this->start_controls_tabs( 'tabs_sc_agenerator_form_settings_field_style' );

				$this->start_controls_tab(
					'tab_sc_agenerator_form_settings_field_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					"settings_field_text_color",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_settings_field input,
							 {{WRAPPER}} .sc_agenerator_form_settings_field .select_container:after,
							 {{WRAPPER}} .sc_agenerator_form_settings_field select,
							 {{WRAPPER}} .sc_agenerator_form_settings_field .sc_agenerator_form_settings_field_numeric_wrap_button:before' => 'color: {{VALUE}};',
							// Additional rule to override the select field background with !important
							// '{{WRAPPER}} .sc_agenerator_form_settings_field .select_container select' => 'background-color: {{settings_field_background_color.VALUE}} !important;',
						],
					]
				);

				$this->add_control(
					"settings_field_placeholder_color",
					[
						'label' => __( 'Placeholder color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_settings_field input[placeholder]::placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_settings_field input[placeholder]::-moz-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_settings_field input[placeholder]::-webkit-input-placeholder' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'settings_field_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings_field input,
										{{WRAPPER}} .sc_agenerator_form_settings_field select,
										{{WRAPPER}} .sc_agenerator_form_settings_field .select_container:before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'settings_field_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_settings_field input,
										{{WRAPPER}} .sc_agenerator_form_settings_field select',
					)
				);
		
				$this->add_responsive_control(
					'settings_field_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_settings_field input,
										 {{WRAPPER}} .sc_agenerator_form_settings_field select,
										 {{WRAPPER}} .sc_agenerator_form_settings_field .select_container,
										 {{WRAPPER}} .sc_agenerator_form_settings_field .select_container:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
					)
				);

				$this->add_responsive_control(
					'settings_field_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form_settings_field input,
							 {{WRAPPER}} .sc_agenerator_form_settings_field select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'settings_field_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings_field input,
										{{WRAPPER}} .sc_agenerator_form_settings_field :not(.select_container) > select,
										{{WRAPPER}} .sc_agenerator_form_settings_field .select_container',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_agenerator_form_settings_field_focus',
					[
						'label' => __( 'Focus', 'trx_addons' ),
					]
				);

				$this->add_control(
					"settings_field_text_color_focus",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_settings_field input:focus,
							 {{WRAPPER}} .sc_agenerator_form_settings_field select:focus,
							 {{WRAPPER}} .sc_agenerator_form_settings_field input:focus + .sc_agenerator_form_settings_field_numeric_wrap_buttons .sc_agenerator_form_settings_field_numeric_wrap_button:before' => 'color: {{VALUE}};',
							// Additional rule to override the select field background with !important
							// '{{WRAPPER}} .sc_agenerator_form_settings_field .select_container select:focus' => 'background-color: {{settings_field_background_color.VALUE}} !important;',
						],
					]
				);

				$this->add_control(
					"settings_field_placeholder_color_focus",
					[
						'label' => __( 'Placeholder color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_settings_field input[placeholder]:focus::placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_settings_field input[placeholder]:focus::-moz-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_settings_field input[placeholder]:focus::-webkit-input-placeholder' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'settings_field_background_focus',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings_field input:focus,
										{{WRAPPER}} .sc_agenerator_form_settings_field_upload_image_decorator:focus,
										{{WRAPPER}} .sc_agenerator_form_settings_field select:focus',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'settings_field_border_focus',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_settings_field input:focus,
										{{WRAPPER}} .sc_agenerator_form_settings_field_upload_image_decorator:focus,
										{{WRAPPER}} .sc_agenerator_form_settings_field select:focus',
					)
				);
		
				$this->add_responsive_control(
					'settings_field_border_radius_focus',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_settings_field input:focus,
										 {{WRAPPER}} .sc_agenerator_form_settings_field_upload_image_decorator:focus,
										 {{WRAPPER}} .sc_agenerator_form_settings_field select:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'settings_field_shadow_focus',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings_field input:focus,
										{{WRAPPER}} .sc_agenerator_form_settings_field_upload_image_decorator:focus,
										{{WRAPPER}} .sc_agenerator_form_settings_field select:focus',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->add_control(
					"settings_field_label_heading",
					[
						'label' => __( 'Fields Label', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'settings_field_label_typography',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings_field label'
					]
				);

				$this->add_control(
					"settings_field_label_color",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_settings_field label' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'settings_field_label_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form_settings_field label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					"settings_field_description_heading",
					[
						'label' => __( 'Fields Description', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'settings_field_description_typography',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings_field_description'
					]
				);

				$this->add_control(
					"settings_field_description_color",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_settings_field_description' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'settings_field_description_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form_settings_field_description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Limits'
			 */
			protected function register_controls_style_limits() {

				$this->start_controls_section(
					'section_sc_agenerator_limits_style',
					[
						'label' => __( 'Limits', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
						'condition' => [
							'show_limits' => '1'
						]
					]
				);

				$this->add_responsive_control(
					'limits_width',
					[
						'label' => __( 'Limits width (in %)', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 50,
								'max' => 100
							]
						],
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_limits' => 'width: {{SIZE}}%;',
						]
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'limits_typography',
						'selector' => '{{WRAPPER}} .sc_agenerator_limits'
					]
				);

				$this->add_control(
					"limits_text_color",
					[
						'label' => __( 'Text Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_limits' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'limits_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_limits'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'limits_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_limits',
					)
				);
		
				$this->add_responsive_control(
					'limits_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_limits' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'limits_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_limits',
					]
				);

				$this->add_responsive_control(
					'limits_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_limits' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'limits_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_limits' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					"limits_values_heading",
					[
						'label' => __( 'Limit Values', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'limits_values_typography',
						'selector' => '{{WRAPPER}} .sc_agenerator_limits_total_value,
										{{WRAPPER}} .sc_agenerator_limits_total_requests,
										{{WRAPPER}} .sc_agenerator_limits_used_value,
										{{WRAPPER}} .sc_agenerator_limits_used_requests'
					]
				);

				$this->add_control(
					"limits_values_color",
					[
						'label' => __( 'Text Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_limits_total_value,
							 {{WRAPPER}} .sc_agenerator_limits_total_requests,
							 {{WRAPPER}} .sc_agenerator_limits_used_value,
							 {{WRAPPER}} .sc_agenerator_limits_used_requests' => 'color: {{VALUE}};',
						],
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Message'
			 */
			protected function register_controls_style_message() {

				$this->start_controls_section(
					'section_sc_agenerator_message_style',
					[
						'label' => __( 'Message', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					]
				);

				$this->add_control(
					"message_popup_heading",
					[
						'label' => __( 'Message Popup', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'after',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'message_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_message'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'message_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_message',
					)
				);
		
				$this->add_responsive_control(
					'message_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'message_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_message',
					]
				);

				$this->add_responsive_control(
					'message_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'message_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					"message_close_heading",
					[
						'label' => __( 'Button "Close"', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'after',
					]
				);

				$this->add_control(
					"message_close_color",
					[
						'label' => __( 'Close Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_button_close .trx_addons_button_close_icon:before,
							 {{WRAPPER}} .trx_addons_button_close .trx_addons_button_close_icon:after' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"message_close_hover",
					[
						'label' => __( 'Close Hover', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_button_close:hover .trx_addons_button_close_icon:before,
							 {{WRAPPER}} .trx_addons_button_close:hover .trx_addons_button_close_icon:after' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"message_header_heading",
					[
						'label' => __( 'Header', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'after',
					]
				);
				
				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'message_header_typography',
						'label' => __( 'Header Typography', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_message h5'
					]
				);

				$this->add_control(
					"message_header_color",
					[
						'label' => __( 'Header Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_message h5' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"message_text_heading",
					[
						'label' => __( 'Text', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'after',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'message_typography',
						'label' => __( 'Text Typography', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_message p'
					]
				);

				$this->add_control(
					"message_text_color",
					[
						'label' => __( 'Text Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_message p' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"message_link_heading",
					[
						'label' => __( 'Button', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'after',
					]
				);

				$this->start_controls_tabs( 'tabs_sc_agenerator_link_style' );

				$this->start_controls_tab(
					'tab_sc_agenerator_link_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'message_link_typography',
						'label' => __( 'Link Typography', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_message_inner a'
					]
				);

				$this->add_control(
					"message_link_color",
					[
						'label' => __( 'Link Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_message_inner a' => 'color: {{VALUE}}; border-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'message_link_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_message_inner a'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'message_link_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_message_inner a',
					)
				);
		
				$this->add_responsive_control(
					'message_link_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_message_inner a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'message_link_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_message_inner a',
					]
				);

				$this->add_responsive_control(
					'message_link_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_message_inner a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'message_link_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_message_inner a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_agenerator_link_hover',
					[
						'label' => __( 'Hover', 'trx_addons' ),
					]
				);
				
				$this->add_control(
					"message_link_hover",
					[
						'label' => __( 'Link Hover', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_message_inner a:hover,
							 {{WRAPPER}} .sc_agenerator_message_inner a:focus' => 'color: {{VALUE}}; border-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'message_link_background_hover',
						'selector' => '{{WRAPPER}} .sc_agenerator_message_inner a:hover,
									  {{WRAPPER}} .sc_agenerator_message_inner a:focus'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'message_link_border_hover_',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_message_inner a:hover,
									  {{WRAPPER}} .sc_agenerator_message_inner a:focus',
					)
				);
		
				$this->add_responsive_control(
					'message_link_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_message_inner a:hover,
										 {{WRAPPER}} .sc_agenerator_message_inner a:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'message_link_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_message_inner a:hover,
									  {{WRAPPER}} .sc_agenerator_message_inner a:focus',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Audio Preview'
			 */
			protected function register_controls_style_audio_preview() {

				$this->start_controls_section(
					'section_sc_agenerator_audio_preview_style',
					[
						'label' => __( 'Preview Area', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'audio_preview_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_audio'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'audio_preview_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_audio',
					)
				);
		
				$this->add_responsive_control(
					'audio_preview_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_audio' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'audio_preview_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_audio',
					]
				);

				$this->add_responsive_control(
					'audio_preview_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_audio' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'audio_preview_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_audio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Audio Container'
			 */
			protected function register_controls_style_single_audio() {

				$this->start_controls_section(
					'section_sc_agenerator_single_audio_style',
					[
						'label' => __( 'Audio Container', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'single_audio_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_audio_wrap'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'single_audio_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_audio_wrap',
					)
				);
		
				$this->add_responsive_control(
					'single_audio_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_audio_wrap,
										 {{WRAPPER}} .sc_agenerator_audio_wrap img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'single_audio_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_audio_wrap',
					]
				);

				$this->add_responsive_control(
					'single_audio_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_audio_wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Audio Player'
			 */
			protected function register_controls_style_audio_player() {

				$this->start_controls_section(
					'section_sc_agenerator_audio_player_style',
					[
						'label' => __( 'Audio Player', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					]
				);

				$this->add_responsive_control(
					'audio_player_height',
					[
						'label' => __( 'Height', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vh', '%', 'custom' ],
						'range' => [
							'px' => [
								'min' => 30,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 2,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 2,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'{{WRAPPER}} .mejs-container,
							 {{WRAPPER}} .mejs-controls' => 'height: {{SIZE}}{{UNIT}} !important;',
						],
					]
				);

				$this->add_responsive_control(
					'audio_player_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .mejs-controls' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'audio_player_background',
						'selector' => '{{WRAPPER}} .mejs-controls'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'audio_player_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .mejs-controls',
					)
				);
		
				$this->add_responsive_control(
					'audio_player_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .mejs-container,
										 {{WRAPPER}} .mejs-controls' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_control(
					"audio_player_text_color",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .mejs-controls .mejs-time' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_buttons_color",
					[
						'label' => __( 'Buttons color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .mejs-controls .mejs-button > button' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_buttons_hover",
					[
						'label' => __( 'Buttons hover', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .mejs-controls .mejs-button > button:hover,
							 {{WRAPPER}} .mejs-controls .mejs-button > button:focus' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_slider_bg",
					[
						'label' => __( 'Sliders Background', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .mejs-controls .mejs-time-rail .mejs-time-total,
							 {{WRAPPER}} .mejs-controls .mejs-time-rail .mejs-time-loaded,
							 {{WRAPPER}} .mejs-controls .mejs-time-rail .mejs-time-hovered,
							 {{WRAPPER}} .mejs-controls .mejs-volume-slider .mejs-volume-total,
							 {{WRAPPER}} .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-total' => 'background: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_slider_filled",
					[
						'label' => __( 'Sliders Filled', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .mejs-controls .mejs-time-rail .mejs-time-current,
							 {{WRAPPER}} .mejs-controls .mejs-volume-slider .mejs-volume-current,
							 {{WRAPPER}} .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current' => 'background: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_slider_handle",
					[
						'label' => __( 'Sliders Handle', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .mejs-controls .mejs-time-rail .mejs-time-handle-content' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_time_float_color",
					[
						'label' => __( 'Time Float Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .mejs-controls .mejs-time-rail .mejs-time-float' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_time_float_bg",
					[
						'label' => __( 'Time Float Background', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .mejs-controls .mejs-time-rail .mejs-time-float' => 'background: {{VALUE}};',
							'{{WRAPPER}} .mejs-controls .mejs-time-rail .mejs-time-float-corner' => 'border-top-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_time_float_bd",
					[
						'label' => __( 'Time Float Border', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .mejs-controls .mejs-time-rail .mejs-time-float' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Button Download'
			 */
			protected function register_controls_style_button_download() {

				$this->start_controls_section(
					'section_sc_agenerator_button_download_style',
					[
						'label' => __( 'Button "Download"', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
						'condition' => [
							'show_download' => '1'
						]
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'button_download_typography',
						'selector' => '{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default'
					]
				);

				$params = trx_addons_get_icon_param( 'button_download_icon' );
				$params = trx_addons_array_get_first_value( $params );
				unset( $params['name'] );
				$this->add_control( 'button_download_icon', $params );

				$this->add_control( 'button_download_image',
					[
						'label' => esc_html__( 'Image', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::MEDIA,
						'media_types' => [ 'image', 'svg' ],
						'condition' => [
							'button_download_icon' => ['', 'none'],
						],
					]
				);

				$this->start_controls_tabs( 'tabs_sc_agenerator_button_download_style' );

				$this->start_controls_tab(
					'tab_sc_agenerator_button_download_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					"button_download_text_color",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"button_download_icon_color",
					[
						'label' => __( 'Icon color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default .sc_button_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default .sc_button_icon svg' => 'fill: {{VALUE}};',
						],
						'condition' => [
							'button_download_icon!' => ['', 'none'],
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'button_download_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'button_download_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default',
					)
				);
		
				$this->add_responsive_control(
					'button_download_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_download_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_agenerator_button_download_hover',
					[
						'label' => __( 'Hover', 'trx_addons' ),
					]
				);

				$this->add_control(
					"button_download_text_color_hover",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default:hover,
							 {{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default:focus' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"button_download_icon_color_hover",
					[
						'label' => __( 'Icon color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default:hover .sc_button_icon,
							 {{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default:focus .sc_button_icon' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'button_download_background_hover',
						'selector' => '{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default:hover,
										{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default:focus'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'button_download_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default:hover,
											{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default:focus',
					)
				);
		
				$this->add_responsive_control(
					'button_download_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default:hover,
										 {{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_download_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default:hover,
										{{WRAPPER}} .sc_agenerator_audio_link.sc_button.sc_button_default:focus',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Render widget's template for the editor.
			 *
			 * Written as a Backbone JavaScript template and used to generate the live preview.
			 *
			 * @since 1.6.41
			 * @access protected
			 */
			protected function content_template() {
				if ( ! Utils::is_audio_api_available() ) {
					trx_addons_get_template_part( 'templates/tpe.sc_placeholder.php',
						'trx_addons_args_sc_placeholder',
						apply_filters( 'trx_addons_filter_sc_placeholder_args', array(
							'sc' => 'trx_sc_agenerator',
							'title' => __('AI Audio Generator is not available - token for access to the API for audio generation is not specified', 'trx_addons'),
							'class' => 'sc_placeholder_with_title'
						) )
					);
				} else {
					trx_addons_get_template_part(TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/shortcodes/agenerator/tpe.agenerator.php',
						'trx_addons_args_sc_agenerator',
						array('element' => $this)
					);
				}
			}
		}
		
		// Register widget
		trx_addons_elm_register_widget( 'TRX_Addons_Elementor_Widget_AGenerator' );
	}
}
