<?php
/**
 * Shortcode: IGenerator (Elementor support)
 *
 * @package ThemeREX Addons
 * @since v2.20.2
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

use TrxAddons\AiHelper\Lists;
use TrxAddons\AiHelper\Utils;

// Elementor Widget
//------------------------------------------------------
if ( ! function_exists('trx_addons_sc_igenerator_add_in_elementor')) {
	add_action( trx_addons_elementor_get_action_for_widgets_registration(), 'trx_addons_sc_igenerator_add_in_elementor' );
	function trx_addons_sc_igenerator_add_in_elementor() {
		
		if ( ! class_exists( 'TRX_Addons_Elementor_Widget' ) ) return;	

		class TRX_Addons_Elementor_Widget_IGenerator extends TRX_Addons_Elementor_Widget {

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
			public function __construct( $data = [], $args = null ) {
				parent::__construct( $data, $args );
				$this->add_plain_params([
					'number' => 'size',
					'prompt_width' => 'size',
					'width' => 'size',
					'height' => 'size',
					'button_image' => 'url',
				]);
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
				return 'trx_sc_igenerator';
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
				return __( 'AI Helper Image Generator', 'trx_addons' );
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
				return [ 'ai', 'helper', 'generator', 'igenerator', 'image', 'ai image', 'ai generator' ];
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
				return 'eicon-image trx_addons_elementor_widget_icon';
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
				$this->register_controls_content_demo_images();

				$this->register_controls_style_sc_content();
				$this->register_controls_style_sc_form();
				$this->register_controls_style_tabs();
				$this->register_controls_style_sc_form_field();
				$this->register_controls_style_button_generate();
				$this->register_controls_style_settings_button();
				$this->register_controls_style_settings_popup();
				$this->register_controls_style_settings_field();
				$this->register_controls_style_tags();
				$this->register_controls_style_limits();
				$this->register_controls_style_message();
				$this->register_controls_style_images();
				$this->register_controls_style_single_image();
				$this->register_controls_style_button_download();

				if ( apply_filters( 'trx_addons_filter_add_title_param', true, $this->get_name() ) ) {
					$this->add_title_param();
				}
			}

			/**
			 * Register widget controls: tab 'Content' section 'AI Helper Image Generator'
			 */
			protected function register_controls_content_general() {

				// Register controls
				$this->start_controls_section(
					'section_sc_igenerator',
					[
						'label' => __( 'AI Helper Image Generator', 'trx_addons' ),
					]
				);

				$this->add_control(
					'type',
					[
						'label' => __( 'Layout', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::SELECT,
						'options' => apply_filters('trx_addons_sc_type', Lists::get_list_sc_image_generator_layouts(), 'trx_sc_igenerator'),
						'default' => 'default'
					]
				);

				$this->add_control(
					'prompt',
					[
						'label' => __( 'Default prompt', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'default' => ''
					]
				);

				$this->add_control(
					'placeholder_text',
					[
						'label' => __( 'Placeholder', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'default' => ''
					]
				);

				$this->add_control(
					'button_text',
					[
						'label' => __( 'Button text', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'default' => ''
					]
				);

				$this->add_control(
					'show_negative_prompt',
					[
						'label' => __( 'Add Negative Prompt', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'default' => '',
						'return_value' => '1',
					]
				);

				$this->add_control(
					'negative_prompt',
					[
						'label' => __( 'Negative Prompt', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'default' => '',
						'condition' => [
							'show_negative_prompt' => '1'
						]
					]
				);

				$this->add_control(
					'negative_placeholder_text',
					[
						'label' => __( 'Negative Placeholder', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'default' => '',
						'condition' => [
							'show_negative_prompt' => '1'
						]
					]
				);

				$this->add_control(
					'show_prompt_translated',
					[
						'label' => __( 'Show "Prompt translated"', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'default' => '1',
						'return_value' => '1',
					]
				);

				$this->add_responsive_control(
					'prompt_width',
					[
						'label' => __( 'Prompt field width', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => 100,
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
							'{{WRAPPER}} .sc_igenerator_form_inner' => 'width: {{SIZE}}%;',
							'{{WRAPPER}} .sc_igenerator_message' => 'max-width: {{SIZE}}%;',
							'{{WRAPPER}} .sc_igenerator_limits' => 'max-width: {{SIZE}}%;',
						],
						'condition' => [
							'type' => 'default'
						]
					]
				);

				$this->add_control(
					'show_upload',
					[
						'label' => __( 'Allow upload image', 'trx_addons' ),
						'label_block' => false,
						'description' => wp_kses_data( __("Allow users to upload their own images for generation variations. The image will be temporary uploaded to the server and will be available for generation only for the current user.", 'trx_addons') ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'default' => '',
						'return_value' => '1',
						'condition' => [
							'type' => 'default'
						]
					]
				);

				$this->add_responsive_control(
					'align',
					[
						'label' => esc_html__( 'Alignment', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::CHOOSE,
						'options' => trx_addons_get_list_sc_flex_aligns_for_elementor(),
						'default' => '',
						'render_type' => 'template',
						'selectors' => [
							'{{WRAPPER}} .sc_igenerator_form' => 'align-items: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_inner' => 'align-items: {{VALUE}};',
						],
						'condition' => [
							'type' => 'default'
						]
					]
				);

				$this->add_control(
					'tags_label',
					[
						'label' => __( 'Tags label', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'default' => __( 'Popular Tags:', 'trx_addons' )
					]
				);

				$this->add_control(
					'tags',
					[
						'label' => __( 'Tags', 'trx_addons' ),
						'label_block' => true,
						'type' => \Elementor\Controls_Manager::REPEATER,
						'default' => apply_filters('trx_addons_sc_param_group_value', [
							[
								'title' => esc_html__( 'Creative', 'trx_addons' ),
								'prompt' => esc_html__( 'creative images with ...', 'trx_addons' ),
							],
							[
								'title' => esc_html__( 'Design', 'trx_addons' ),
								'prompt' => esc_html__( 'design of the ...', 'trx_addons' ),
							],
							[
								'title' => esc_html__( 'Illustration', 'trx_addons' ),
								'prompt' => esc_html__( 'illustration about ...', 'trx_addons' ),
							],
						], 'trx_sc_igenerator'),
						'fields' => apply_filters('trx_addons_sc_param_group_params', [
							[
								'name' => 'title',
								'label' => __( 'Title', 'trx_addons' ),
								'label_block' => false,
								'type' => \Elementor\Controls_Manager::TEXT,
								'placeholder' => __( "Tag's title", 'trx_addons' ),
								'default' => ''
							],
							[
								'name' => 'prompt',
								'label' => __( 'Prompt', 'trx_addons' ),
								'label_block' => false,
								'type' => \Elementor\Controls_Manager::TEXT,
								'placeholder' => __( "Prompt", 'trx_addons' ),
								'default' => ''
							],
						], 'trx_sc_igenerator' ),
						'title_field' => '{{{ title }}}'
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Content' section 'Generator Settings'
			 */
			protected function register_controls_content_generator_settings() {

				// Detect edit mode
				$models = ! $this->is_edit_mode ? array() : Lists::get_list_ai_image_models( false );
				$models_dim = ! $this->is_edit_mode ? array() : array_values( array_filter( array_keys( $models ), function( $key ) { return Utils::is_model_support_image_dimensions( $key ); } ) );
				$models_sd = ! $this->is_edit_mode ? array() : array_values( array_filter( array_keys( $models ), function( $key ) { return Utils::is_stable_diffusion_model( $key ); } ) );
				$models_stability = ! $this->is_edit_mode ? array() : array_values( array_filter( array_keys( $models ), function( $key ) { return Utils::is_stability_ai_model( $key ); } ) );
				$models_dall_e_3 = ! $this->is_edit_mode ? array() : array_values( array_filter( array_keys( $models ), function( $key ) { return Utils::is_openai_dall_e_3_model( $key ); } ) );

				// Section: Generator settings
				$this->start_controls_section(
					'section_sc_igenerator_settings',
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
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'return_value' => '1',
					]
				);

				$this->add_control(
					'show_limits',
					[
						'label' => __( 'Show limits', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'return_value' => '1',
					]
				);

				$this->add_control(
					'model',
					[
						'label' => __( 'Default model', 'trx_addons' ),
						'label_block' => false,
						'separator' => 'before',
						'type' => \Elementor\Controls_Manager::SELECT,
						'options' => $models,
						'default' => Utils::get_default_image_model()
					]
				);

				$this->add_control(
					'style',
					[
						'label' => __( 'Default style', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::SELECT,
						'options' => Lists::get_list_stability_ai_styles(),
						'default' => '',
						'condition' => [
							'model' => $models_stability,
						]
					]
				);

				$this->add_control(
					'style_openai',
					[
						'label' => __( 'Default style', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::SELECT,
						'options' => Lists::get_list_openai_styles(),
						'default' => '',
						'condition' => [
							'model' => $models_dall_e_3,
						]
					]
				);

				$this->add_control(
					'quality',
					[
						'label' => __( 'Quality', 'trx_addons' ),
						'label_on' => __( 'HD', 'trx_addons' ),
						'label_off' => __( 'Std', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'return_value' => 'hd',
						'condition' => [
							'model' => $models_dall_e_3,
						]
					]
				);

				$this->add_control(
					'safety_checker',
					[
						'label' => __( 'Safety checker', 'trx_addons' ),
						'label_block' => false,
						'description' => __( 'A checker for NSFW images. If such an image is detected, it will be replaced by a blank image or blured. Supported by ModelsLab (ex Stable Diffusion) models only!', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'options' => Lists::get_sd_safety_checkers(),
						'default' => 'none',
						'condition' => [
							'model' => $models_sd
						]
					]
				);

				$this->add_control(
					'system_prompt',
					[
						'label' => __( 'System prompt (Context)', 'trx_addons' ),
						'label_block' => true,
						'description' => __( 'These are instructions for the AI Model describing how it should generate text. If you leave this field empty - the System Prompt specified in the plugin options will be used.', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::TEXTAREA,
						'rows' => 5,
						'default' => ''
					]
				);

				$this->add_control(
					'show_settings',
					[
						'label' => __( 'Show button "Settings"', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'return_value' => '1'
					]
				);

				$this->add_control(
					'show_settings_size',
					[
						'label' => __( 'Image dimensions picker', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'return_value' => '1',
						'condition' => [
							'type' => 'default',
							'show_settings' => '1'
						]
					]
				);

				$this->add_control(
					'show_download',
					[
						'label' => __( 'Show button "Download"', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'return_value' => '1',
					]
				);

				$this->add_control(
					'show_popup',
					[
						'label' => __( 'Open images in the popup', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'return_value' => '1',
					]
				);

				$this->add_control(
					'number',
					[
						'label' => __( 'Generate at once', 'trx_addons' ),
						'description' => wp_kses_data( __("Specify the number of images to be generated at once (from 1 to 10)", 'trx_addons') ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => 3,
							'unit' => 'px'
						],
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 1,
								'max' => 10
							]
						]
					]
				);

				$this->add_responsive_control(
					'columns',
					[
						'label' => __( 'Columns', 'trx_addons' ),
						'description' => wp_kses_data( __("Specify the number of columns. If left empty or assigned the value '0' - auto detect by the number of items.", 'trx_addons') ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => 0,
							'unit' => 'px'
						],
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 12
							]
						]
					]
				);

				$this->add_control(
					'size',
					[
						'label' => __( 'Image size', 'trx_addons' ),
						'label_block' => false,
						'description' => wp_kses_data( __("Select the size of generated images.", 'trx_addons') ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'options' => Lists::get_list_ai_image_sizes(),
						'default' => '256x256',
					]
				);

				$this->add_control(
					'width',
					[
						'label' => __( 'Image width', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => 0,
							'unit' => 'px'
						],
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => Utils::get_max_image_width(),
								'step' => 8

							]
						],
						'condition' => [
							'model' => $models_dim,
							'size' => 'custom'
						]
					]
				);

				$this->add_control(
					'height',
					[
						'label' => __( 'Image height', 'trx_addons' ),
						'description' => wp_kses_data( __("Specify the image width and height for ModelsLab (ex Stable Diffusion) models only. If 0 or empty - a size from the field above will be used.", 'trx_addons') ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => 0,
							'unit' => 'px'
						],
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => Utils::get_max_image_height(),
								'step' => 8
							]
						],
						'condition' => [
							'model' => $models_dim,
							'size' => 'custom'
						]
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Content' section 'Demo Images'
			 */
			protected function register_controls_content_demo_images() {

				// Section: Demo images
				$this->start_controls_section(
					'section_sc_igenerator_demo',
					[
						'label' => __( 'Demo Images', 'trx_addons' ),
					]
				);

				$this->add_control(
					'demo_images',
					[
						'label' => '',
						'description' => wp_kses_data( __("Selected images will be used instead of the image generator as a demo mode when limits are reached", 'trx_addons') ),
						'type' => \Elementor\Controls_Manager::GALLERY,
						'default' => [],
					]
				);

				$this->add_control(
					'demo_thumb_size',
					[
						'label' => __( 'Demo thumb size', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::SELECT,
						'options' => ! $this->is_edit_mode ? array() : trx_addons_get_list_thumbnail_sizes(),
						'default' => apply_filters( 'trx_addons_filter_thumb_size',
													trx_addons_get_thumb_size( 'avatar' ),
													'trx_addons_sc_igenerator',
													array()
												)
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Image Generator'
			 */
			protected function register_controls_style_sc_content() {

				$this->start_controls_section(
					'section_sc_igenerator_content_style',
					[
						'label' => __( 'Image Generator', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'sc_content_background',
						'selector' => '{{WRAPPER}} .sc_igenerator_content'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'sc_content_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_content',
					)
				);
		
				$this->add_responsive_control(
					'sc_content_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .sc_igenerator_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .sc_igenerator_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
		
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'sc_content_box_shadow',
						'selector' => '{{WRAPPER}} .sc_igenerator_content',
					]
				);
		
				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Form'
			 */
			protected function register_controls_style_sc_form() {

				$this->start_controls_section(
					'section_sc_igenerator_form_style',
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
							'{{WRAPPER}} .sc_igenerator_form_field + .sc_igenerator_form_field' => 'margin-top: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'sc_form_background',
						'selector' => '{{WRAPPER}} .sc_igenerator_form'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'sc_form_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form',
					)
				);
		
				$this->add_responsive_control(
					'sc_form_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .sc_igenerator_form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .sc_igenerator_form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
		
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'sc_form_box_shadow',
						'selector' => '{{WRAPPER}} .sc_igenerator_form',
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Tabs'
			 */
			protected function register_controls_style_tabs() {

				$this->start_controls_section(
					'section_sc_igenerator_tabs_style',
					[
						'label' => __( 'Tabs', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
						'condition' => [
							'type' => 'extended'
						]
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
							'{{WRAPPER}} .sc_igenerator_form_actions_list:after,
							 {{WRAPPER}} .sc_igenerator_form_actions_slider' => 'height: {{VALUE}}px;',
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
							'{{WRAPPER}} .sc_igenerator_form_actions_list:after,
							 {{WRAPPER}} .sc_igenerator_form_actions_slider' => 'bottom: {{VALUE}}px;',
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
							'{{WRAPPER}} .sc_igenerator_form_actions_slider' => 'background-color: {{VALUE}};',
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
							'{{WRAPPER}} .sc_igenerator_form_actions_list:after' => 'background-color: {{VALUE}};',
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
						'selector' => '{{WRAPPER}} .sc_igenerator_form_actions_item > a'
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
							'{{WRAPPER}} .sc_igenerator_form_actions_list' => 'gap: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->start_controls_tabs( 'tabs_sc_igenerator_tabs_style' );

				$this->start_controls_tab(
					'tab_sc_igenerator_tab_normal',
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
							'{{WRAPPER}} .sc_igenerator_form_actions_item > a' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'tab_background',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_actions_item > a'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'tab_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form_actions_item > a',
					)
				);
		
				$this->add_responsive_control(
					'tab_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form_actions_item > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'tab_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_form_actions_item > a',
					]
				);

				$this->add_responsive_control(
					'tab_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_igenerator_form_actions_item > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
						'separator'             => 'before',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_igenerator_tab_hover',
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
							'{{WRAPPER}} .sc_igenerator_form_actions_item:not(.sc_igenerator_form_actions_item_active) > a:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'tab_background_hover',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_actions_item:not(.sc_igenerator_form_actions_item_active) > a:hover'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'tab_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form_actions_item:not(.sc_igenerator_form_actions_item_active) > a:hover',
					)
				);
		
				$this->add_responsive_control(
					'tab_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form_actions_item:not(.sc_igenerator_form_actions_item_active) > a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'tab_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_form_actions_item:not(.sc_igenerator_form_actions_item_active) > a:hover',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_igenerator_tab_active',
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
							'{{WRAPPER}} .sc_igenerator_form_actions_item_active > a' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'tab_background_active',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_actions_item_active > a'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'tab_border_active',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form_actions_item_active > a',
					)
				);
		
				$this->add_responsive_control(
					'tab_border_radius_active',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form_actions_item_active > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'tab_shadow_active',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_form_actions_item_active > a',
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
					'section_sc_igenerator_form_field_style',
					[
						'label' => __( 'Form Field', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'sc_form_field_typography',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field input,
										{{WRAPPER}} .sc_igenerator_form_field select,
										{{WRAPPER}} .sc_igenerator_form_field .select_container,
										{{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator'
					]
				);

				$this->start_controls_tabs( 'tabs_sc_igenerator_form_field_style' );

				$this->start_controls_tab(
					'tab_sc_igenerator_form_field_normal',
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
							'{{WRAPPER}} .sc_igenerator_form_field input,
							 {{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator,
							 {{WRAPPER}} .sc_igenerator_form_field .select_container:after,
							 {{WRAPPER}} .sc_igenerator_form_field select,
							 {{WRAPPER}} .sc_igenerator_form_field .sc_igenerator_form_field_numeric_wrap_button:before' => 'color: {{VALUE}};',
							// Additional rule to override the select field background with !important
							// '{{WRAPPER}} .sc_igenerator_form_field .select_container select' => 'background-color: {{sc_form_field_background_color.VALUE}} !important;',
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
							'{{WRAPPER}} .sc_igenerator_form_field input[placeholder]::placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_field input[placeholder]::-moz-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_field input[placeholder]::-webkit-input-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_field_upload_image_text.theme_form_field_placeholder' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .sc_igenerator_form_field_upload_image_button' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'sc_form_field_background',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field input,
										{{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator,
										{{WRAPPER}} .sc_igenerator_form_field select,
										{{WRAPPER}} .sc_igenerator_form_field .select_container:before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'sc_form_field_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form_field input,
										{{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator,
										{{WRAPPER}} .sc_igenerator_form_field select',
					)
				);
		
				$this->add_responsive_control(
					'sc_form_field_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form_field input,
										 {{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator,
										 {{WRAPPER}} .sc_igenerator_form_field select,
										 {{WRAPPER}} .sc_igenerator_form_field .select_container,
										 {{WRAPPER}} .sc_igenerator_form_field .select_container:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .sc_igenerator_form_field input,
							 {{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator,
							 {{WRAPPER}} .sc_igenerator_form_field select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'sc_form_field_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field input,
										{{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator,
										{{WRAPPER}} .sc_igenerator_form_field :not(.select_container) > select,
										{{WRAPPER}} .sc_igenerator_form_field .select_container',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_igenerator_form_field_focus',
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
							'{{WRAPPER}} .sc_igenerator_form_field input:focus,
							 {{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator:focus,
							 {{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator:hover,
							 {{WRAPPER}} .sc_igenerator_form_field select:focus,
							 {{WRAPPER}} .sc_igenerator_form_field input:focus + .sc_igenerator_form_field_numeric_wrap_buttons .sc_igenerator_form_field_numeric_wrap_button:before' => 'color: {{VALUE}};',
							// Additional rule to override the select field background with !important
							// '{{WRAPPER}} .sc_igenerator_form_field .select_container select:focus' => 'background-color: {{sc_form_field_background_color.VALUE}} !important;',
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
							'{{WRAPPER}} .sc_igenerator_form_field input[placeholder]:focus::placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_field input[placeholder]:focus::-moz-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_field input[placeholder]:focus::-webkit-input-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator:focus .sc_igenerator_form_field_upload_image_text.theme_form_field_placeholder' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator:focus .sc_igenerator_form_field_upload_image_button,
							 {{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator:hover .sc_igenerator_form_field_upload_image_button' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'sc_form_field_background_focus',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field input:focus,
										{{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator:focus,
										{{WRAPPER}} .sc_igenerator_form_field select:focus',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'sc_form_field_border_focus',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form_field input:focus,
										{{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator:focus,
										{{WRAPPER}} .sc_igenerator_form_field select:focus',
					)
				);
		
				$this->add_responsive_control(
					'sc_form_field_border_radius_focus',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form_field input:focus,
										 {{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator:focus,
										 {{WRAPPER}} .sc_igenerator_form_field select:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'sc_form_field_shadow_focus',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field input:focus,
										{{WRAPPER}} .sc_igenerator_form_field_upload_image_decorator:focus,
										{{WRAPPER}} .sc_igenerator_form_field select:focus',
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
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field label,
										{{WRAPPER}} .sc_igenerator_form_field_tags_label'
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
							'{{WRAPPER}} .sc_igenerator_form_field label,
							 {{WRAPPER}} .sc_igenerator_form_field_tags_label' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .sc_igenerator_form_field label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field_description'
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
							'{{WRAPPER}} .sc_igenerator_form_field_description' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .sc_igenerator_form_field_description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'section_sc_igenerator_prompt_button_style',
					[
						'label' => __( 'Button "Generate"', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'button_typography',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field_prompt_button,
										{{WRAPPER}} .sc_igenerator_form_field_generate_button'
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
							'{{WRAPPER}} .sc_igenerator_form_field_prompt_button .sc_igenerator_form_field_prompt_button_icon,
							 {{WRAPPER}} .sc_igenerator_form_field_prompt_button .sc_igenerator_form_field_prompt_button_svg,
							 {{WRAPPER}} .sc_igenerator_form_field_prompt_button .sc_igenerator_form_field_prompt_button_image,
							 {{WRAPPER}} .sc_igenerator_form_field_generate_button .sc_button_icon' => 'font-size: {{SIZE}}{{UNIT}};',
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
							'{{WRAPPER}} .sc_igenerator_form_field_prompt_button .sc_igenerator_form_field_prompt_button_icon,
							 {{WRAPPER}} .sc_igenerator_form_field_prompt_button .sc_igenerator_form_field_prompt_button_svg,
							 {{WRAPPER}} .sc_igenerator_form_field_prompt_button .sc_igenerator_form_field_prompt_button_image,
							 {{WRAPPER}} .sc_igenerator_form_field_generate_button .sc_button_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->start_controls_tabs( 'tabs_sc_igenerator_button_generate_style' );

				$this->start_controls_tab(
					'tab_sc_igenerator_button_generate_normal',
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
							'{{WRAPPER}} .sc_igenerator_form_field_prompt_button,
							 {{WRAPPER}} .sc_igenerator_form_field_generate_button' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .sc_igenerator_form_field_prompt_button .sc_igenerator_form_field_prompt_button_icon,
							 {{WRAPPER}} .sc_igenerator_form_field_generate_button .sc_button_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_field_prompt_button .sc_igenerator_form_field_prompt_button_svg svg,
							 {{WRAPPER}} .sc_igenerator_form_field_generate_button .sc_button_icon svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'button_background',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field_prompt_button,
										{{WRAPPER}} .sc_igenerator_form_field_generate_button'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'button_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form_field_prompt_button,
											{{WRAPPER}} .sc_igenerator_form_field_generate_button',
					)
				);
		
				$this->add_responsive_control(
					'button_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form_field_prompt_button,
										 {{WRAPPER}} .sc_igenerator_form_field_generate_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field_prompt_button,
										{{WRAPPER}} .sc_igenerator_form_field_generate_button',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_igenerator_button_generate_hover',
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
							'{{WRAPPER}} .sc_igenerator_form_field_prompt_button:not(.sc_igenerator_form_field_prompt_button_disabled):hover,
							 {{WRAPPER}} .sc_igenerator_form_field_prompt_button:not(.sc_igenerator_form_field_prompt_button_disabled):focus,
							 {{WRAPPER}} .sc_igenerator_form_field_generate_button:not(.sc_igenerator_form_field_prompt_button_disabled):hover,
							 {{WRAPPER}} .sc_igenerator_form_field_generate_button:not(.sc_igenerator_form_field_prompt_button_disabled):focus' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .sc_igenerator_form_field_prompt_button:not(.sc_igenerator_form_field_prompt_button_disabled):hover .sc_igenerator_form_field_prompt_button_icon,
							 {{WRAPPER}} .sc_igenerator_form_field_generate_button:not(.sc_igenerator_form_field_prompt_button_disabled):hover .sc_button_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_field_prompt_button:not(.sc_igenerator_form_field_prompt_button_disabled):focus .sc_igenerator_form_field_prompt_button_icon,
							 {{WRAPPER}} .sc_igenerator_form_field_generate_button:not(.sc_igenerator_form_field_prompt_button_disabled):focus .sc_button_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_field_prompt_button:not(.sc_igenerator_form_field_prompt_button_disabled):hover .sc_igenerator_form_field_prompt_button_svg svg,
							 {{WRAPPER}} .sc_igenerator_form_field_generate_button:not(.sc_igenerator_form_field_prompt_button_disabled):hover .sc_button_icon svg' => 'fill: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_field_prompt_button:not(.sc_igenerator_form_field_prompt_button_disabled):focus .sc_igenerator_form_field_prompt_button_svg svg,
							 {{WRAPPER}} .sc_igenerator_form_field_generate_button:not(.sc_igenerator_form_field_prompt_button_disabled):focus .sc_button_icon svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'button_background_hover',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field_prompt_button:not(.sc_igenerator_form_field_prompt_button_disabled):hover,
										{{WRAPPER}} .sc_igenerator_form_field_prompt_button:not(.sc_igenerator_form_field_prompt_button_disabled):focus,
										{{WRAPPER}} .sc_igenerator_form_field_generate_button:not(.sc_igenerator_form_field_prompt_button_disabled):hover,
										{{WRAPPER}} .sc_igenerator_form_field_generate_button:not(.sc_igenerator_form_field_prompt_button_disabled):focus'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'button_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form_field_prompt_button:not(.sc_igenerator_form_field_prompt_button_disabled):hover,
										{{WRAPPER}} .sc_igenerator_form_field_prompt_button:not(.sc_igenerator_form_field_prompt_button_disabled):focus,
										{{WRAPPER}} .sc_igenerator_form_field_generate_button:not(.sc_igenerator_form_field_prompt_button_disabled):hover,
										{{WRAPPER}} .sc_igenerator_form_field_generate_button:not(.sc_igenerator_form_field_prompt_button_disabled):focus',
					)
				);
		
				$this->add_responsive_control(
					'button_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form_field_prompt_button:not(.sc_igenerator_form_field_prompt_button_disabled):hover,
										{{WRAPPER}} .sc_igenerator_form_field_prompt_button:not(.sc_igenerator_form_field_prompt_button_disabled):focus,
										{{WRAPPER}} .sc_igenerator_form_field_generate_button:not(.sc_igenerator_form_field_prompt_button_disabled):hover,
										{{WRAPPER}} .sc_igenerator_form_field_generate_button:not(.sc_igenerator_form_field_prompt_button_disabled):focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field_prompt_button:not(.sc_igenerator_form_field_prompt_button_disabled):hover,
										{{WRAPPER}} .sc_igenerator_form_field_prompt_button:not(.sc_igenerator_form_field_prompt_button_disabled):focus,
										{{WRAPPER}} .sc_igenerator_form_field_generate_button:not(.sc_igenerator_form_field_prompt_button_disabled):hover,
										{{WRAPPER}} .sc_igenerator_form_field_generate_button:not(.sc_igenerator_form_field_prompt_button_disabled):focus',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_igenerator_button_generate_disabled',
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
							'{{WRAPPER}} .sc_igenerator_form_field_prompt_button.sc_igenerator_form_field_prompt_button_disabled,
							 {{WRAPPER}} .sc_igenerator_form_field_generate_button.sc_igenerator_form_field_prompt_button_disabled' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .sc_igenerator_form_field_prompt_button.sc_igenerator_form_field_prompt_button_disabled .sc_igenerator_form_field_prompt_button_icon,
							 {{WRAPPER}} .sc_igenerator_form_field_generate_button.sc_igenerator_form_field_prompt_button_disabled .sc_button_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_field_prompt_button.sc_igenerator_form_field_prompt_button_disabled .sc_igenerator_form_field_prompt_button_svg svg,
							 {{WRAPPER}} .sc_igenerator_form_field_generate_button.sc_igenerator_form_field_prompt_button_disabled .sc_button_icon svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'button_background_disabled',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field_prompt_button.sc_igenerator_form_field_prompt_button_disabled,
										{{WRAPPER}} .sc_igenerator_form_field_generate_button.sc_igenerator_form_field_prompt_button_disabled'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'button_border_disabled',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form_field_prompt_button.sc_igenerator_form_field_prompt_button_disabled,
										{{WRAPPER}} .sc_igenerator_form_field_generate_button.sc_igenerator_form_field_prompt_button_disabled',
					)
				);
		
				$this->add_responsive_control(
					'button_border_radius_disabled',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form_field_prompt_button.sc_igenerator_form_field_prompt_button_disabled,
										{{WRAPPER}} .sc_igenerator_form_field_generate_button.sc_igenerator_form_field_prompt_button_disabled' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_shadow_disabled',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field_prompt_button.sc_igenerator_form_field_prompt_button_disabled,
										{{WRAPPER}} .sc_igenerator_form_field_generate_button.sc_igenerator_form_field_prompt_button_disabled',
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
							'{{WRAPPER}} .sc_igenerator_form_field_prompt_button.sc_igenerator_form_field_prompt_button_disabled,
							 {{WRAPPER}} .sc_igenerator_form_field_generate_button.sc_igenerator_form_field_prompt_button_disabled' => 'opacity: {{SIZE}};',
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
					'section_sc_igenerator_settings_button_style',
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
							'{{WRAPPER}} .sc_igenerator_form_settings_button:before' => 'font-size: {{SIZE}}{{UNIT}};',
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

				$this->start_controls_tabs( 'tabs_sc_igenerator_settings_button_style' );

				$this->start_controls_tab(
					'tab_sc_igenerator_settings_button_normal',
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
							'{{WRAPPER}} .sc_igenerator_form_settings_button' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_settings_button svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'settings_button_background',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_settings_button'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'settings_button_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form_settings_button',
					)
				);
		
				$this->add_responsive_control(
					'settings_button_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form_settings_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'settings_button_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_form_settings_button',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_igenerator_settings_button_hover',
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
							'{{WRAPPER}} .sc_igenerator_form_settings_button:hover' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_settings_button:hover svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'settings_button_background_hover',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_settings_button:hover'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'settings_button_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form_settings_button:hover',
					)
				);
		
				$this->add_responsive_control(
					'settings_button_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form_settings_button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'settings_button_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_form_settings_button:hover',
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
					'section_sc_igenerator_settings_popup_style',
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
							'{{WRAPPER}} .sc_igenerator_form_settings_field + .sc_igenerator_form_settings_field' => 'margin-top: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'settings_popup_background',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_settings'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'settings_popup_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form_settings',
					)
				);
		
				$this->add_responsive_control(
					'settings_popup_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form_settings' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .sc_igenerator_form_settings' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .sc_igenerator_form_settings' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
		
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'settings_popup_box_shadow',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_settings',
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
							'{{WRAPPER}} .sc_igenerator_form_settings' => 'scrollbar-color: {{VALUE}} {{settings_popup_scrollbar_slider.VALUE}};',
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
							'{{WRAPPER}} .sc_igenerator_form_settings' => 'scrollbar-color: {{settings_popup_scrollbar_color.VALUE}} {{VALUE}};',
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
					'section_sc_igenerator_settings_field_style',
					[
						'label' => __( 'Fields in "Settings"', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'settings_field_typography',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_settings_field input,
										{{WRAPPER}} .sc_igenerator_form_settings_field select,
										{{WRAPPER}} .sc_igenerator_form_settings_field .select_container'
					]
				);

				$this->start_controls_tabs( 'tabs_sc_igenerator_form_settings_field_style' );

				$this->start_controls_tab(
					'tab_sc_igenerator_form_settings_field_normal',
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
							'{{WRAPPER}} .sc_igenerator_form_settings_field input,
							 {{WRAPPER}} .sc_igenerator_form_settings_field .select_container:after,
							 {{WRAPPER}} .sc_igenerator_form_settings_field select,
							 {{WRAPPER}} .sc_igenerator_form_settings_field .sc_igenerator_form_settings_field_numeric_wrap_button:before' => 'color: {{VALUE}};',
							// Additional rule to override the select field background with !important
							// '{{WRAPPER}} .sc_igenerator_form_settings_field .select_container select' => 'background-color: {{settings_field_background_color.VALUE}} !important;',
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
							'{{WRAPPER}} .sc_igenerator_form_settings_field input[placeholder]::placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_settings_field input[placeholder]::-moz-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_settings_field input[placeholder]::-webkit-input-placeholder' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'settings_field_background',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_settings_field input,
										{{WRAPPER}} .sc_igenerator_form_settings_field select,
										{{WRAPPER}} .sc_igenerator_form_settings_field .select_container:before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'settings_field_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form_settings_field input,
										{{WRAPPER}} .sc_igenerator_form_settings_field select',
					)
				);
		
				$this->add_responsive_control(
					'settings_field_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form_settings_field input,
										 {{WRAPPER}} .sc_igenerator_form_settings_field select,
										 {{WRAPPER}} .sc_igenerator_form_settings_field .select_container,
										 {{WRAPPER}} .sc_igenerator_form_settings_field .select_container:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .sc_igenerator_form_settings_field input,
							 {{WRAPPER}} .sc_igenerator_form_settings_field select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'settings_field_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_form_settings_field input,
										{{WRAPPER}} .sc_igenerator_form_settings_field :not(.select_container) > select,
										{{WRAPPER}} .sc_igenerator_form_settings_field .select_container',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_igenerator_form_settings_field_focus',
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
							'{{WRAPPER}} .sc_igenerator_form_settings_field input:focus,
							 {{WRAPPER}} .sc_igenerator_form_settings_field select:focus,
							 {{WRAPPER}} .sc_igenerator_form_settings_field input:focus + .sc_igenerator_form_settings_field_numeric_wrap_buttons .sc_igenerator_form_settings_field_numeric_wrap_button:before' => 'color: {{VALUE}};',
							// Additional rule to override the select field background with !important
							// '{{WRAPPER}} .sc_igenerator_form_settings_field .select_container select:focus' => 'background-color: {{settings_field_background_color.VALUE}} !important;',
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
							'{{WRAPPER}} .sc_igenerator_form_settings_field input[placeholder]:focus::placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_settings_field input[placeholder]:focus::-moz-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_form_settings_field input[placeholder]:focus::-webkit-input-placeholder' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'settings_field_background_focus',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_settings_field input:focus,
										{{WRAPPER}} .sc_igenerator_form_settings_field_upload_image_decorator:focus,
										{{WRAPPER}} .sc_igenerator_form_settings_field select:focus',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'settings_field_border_focus',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form_settings_field input:focus,
										{{WRAPPER}} .sc_igenerator_form_settings_field_upload_image_decorator:focus,
										{{WRAPPER}} .sc_igenerator_form_settings_field select:focus',
					)
				);
		
				$this->add_responsive_control(
					'settings_field_border_radius_focus',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form_settings_field input:focus,
										 {{WRAPPER}} .sc_igenerator_form_settings_field_upload_image_decorator:focus,
										 {{WRAPPER}} .sc_igenerator_form_settings_field select:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'settings_field_shadow_focus',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_form_settings_field input:focus,
										{{WRAPPER}} .sc_igenerator_form_settings_field_upload_image_decorator:focus,
										{{WRAPPER}} .sc_igenerator_form_settings_field select:focus',
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
						'selector' => '{{WRAPPER}} .sc_igenerator_form_settings_field label'
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
							'{{WRAPPER}} .sc_igenerator_form_settings_field label' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .sc_igenerator_form_settings_field label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'selector' => '{{WRAPPER}} .sc_igenerator_form_settings_field_description'
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
							'{{WRAPPER}} .sc_igenerator_form_settings_field_description' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .sc_igenerator_form_settings_field_description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Tags'
			 */
			protected function register_controls_style_tags() {

				$this->start_controls_section(
					'section_sc_igenerator_tags_style',
					[
						'label' => __( 'Tags', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->add_responsive_control(
					'tag_label_heading',
					[
						'label' => __( 'Label', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
					]
				);

				$this->add_responsive_control(
					'tag_label_offset',
					[
						'label' => __( 'Vertical Offset', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px', 'em', 'rem', '%', 'vh' ],
						'range' => [
							'px' => [
								'min' => -50,
								'max' => 50
							],
							'%' => [
								'min' => -50,
								'max' => 50
							],
							'em' => [
								'min' => -2,
								'max' => 2,
								'step' => 0.1
							],
							'rem' => [
								'min' => -2,
								'max' => 2,
								'step' => 0.1
							],
						],
						'selectors' => [
							'{{WRAPPER}} .sc_igenerator_form_field_tags_label' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
						],
					]
				);

				$this->add_responsive_control(
					'tag_items_heading',
					[
						'label' => __( 'Items', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'tag_typography',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field_tags_item'
					]
				);

				$this->start_controls_tabs( 'tabs_sc_igenerator_tag_style' );

				$this->start_controls_tab(
					'tab_sc_igenerator_tag_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					"tag_text_color",
					[
						'label' => __( 'Text Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_igenerator_form_field_tags_item' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'tag_background',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field_tags_item'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'tag_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form_field_tags_item',
					)
				);
		
				$this->add_responsive_control(
					'tag_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form_field_tags_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'tag_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field_tags_item',
					]
				);

				$this->add_responsive_control(
					'tag_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_igenerator_form_field_tags_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
						'separator'             => 'before',
					]
				);

				$this->add_responsive_control(
					'tag_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_igenerator_form_field_tags_item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_igenerator_tag_hover',
					[
						'label' => __( 'Hover', 'trx_addons' ),
					]
				);

				$this->add_control(
					"tag_text_color_hover",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_igenerator_form_field_tags_item:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'tag_background_hover',
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field_tags_item:hover'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'tag_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_form_field_tags_item:hover',
					)
				);
		
				$this->add_responsive_control(
					'tag_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_form_field_tags_item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'tag_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_form_field_tags_item:hover',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Limits'
			 */
			protected function register_controls_style_limits() {

				$this->start_controls_section(
					'section_sc_igenerator_limits_style',
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
							'{{WRAPPER}} .sc_igenerator_limits' => 'width: {{SIZE}}%;',
						]
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'limits_typography',
						'selector' => '{{WRAPPER}} .sc_igenerator_limits'
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
							'{{WRAPPER}} .sc_igenerator_limits' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'limits_background',
						'selector' => '{{WRAPPER}} .sc_igenerator_limits'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'limits_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_limits',
					)
				);
		
				$this->add_responsive_control(
					'limits_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_limits' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'limits_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_limits',
					]
				);

				$this->add_responsive_control(
					'limits_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_igenerator_limits' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .sc_igenerator_limits' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'selector' => '{{WRAPPER}} .sc_igenerator_limits_total_value,
										{{WRAPPER}} .sc_igenerator_limits_total_requests,
										{{WRAPPER}} .sc_igenerator_limits_used_value,
										{{WRAPPER}} .sc_igenerator_limits_used_requests'
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
							'{{WRAPPER}} .sc_igenerator_limits_total_value,
							 {{WRAPPER}} .sc_igenerator_limits_total_requests,
							 {{WRAPPER}} .sc_igenerator_limits_used_value,
							 {{WRAPPER}} .sc_igenerator_limits_used_requests' => 'color: {{VALUE}};',
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
					'section_sc_igenerator_message_style',
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
						'selector' => '{{WRAPPER}} .sc_igenerator_message'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'message_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_message',
					)
				);
		
				$this->add_responsive_control(
					'message_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'message_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_message',
					]
				);

				$this->add_responsive_control(
					'message_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_igenerator_message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .sc_igenerator_message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'selector' => '{{WRAPPER}} .sc_igenerator_message h5'
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
							'{{WRAPPER}} .sc_igenerator_message h5' => 'color: {{VALUE}};',
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
						'selector' => '{{WRAPPER}} .sc_igenerator_message p'
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
							'{{WRAPPER}} .sc_igenerator_message p' => 'color: {{VALUE}};',
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

				$this->start_controls_tabs( 'tabs_sc_igenerator_link_style' );

				$this->start_controls_tab(
					'tab_sc_igenerator_link_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'message_link_typography',
						'label' => __( 'Link Typography', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_message_inner a'
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
							'{{WRAPPER}} .sc_igenerator_message_inner a' => 'color: {{VALUE}}; border-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'message_link_background',
						'selector' => '{{WRAPPER}} .sc_igenerator_message_inner a'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'message_link_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_message_inner a',
					)
				);
		
				$this->add_responsive_control(
					'message_link_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_message_inner a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'message_link_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_message_inner a',
					]
				);

				$this->add_responsive_control(
					'message_link_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_igenerator_message_inner a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .sc_igenerator_message_inner a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_igenerator_link_hover',
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
							'{{WRAPPER}} .sc_igenerator_message_inner a:hover,
							 {{WRAPPER}} .sc_igenerator_message_inner a:focus' => 'color: {{VALUE}}; border-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'message_link_background_hover',
						'selector' => '{{WRAPPER}} .sc_igenerator_message_inner a:hover,
									  {{WRAPPER}} .sc_igenerator_message_inner a:focus'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'message_link_border_hover_',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_message_inner a:hover,
									  {{WRAPPER}} .sc_igenerator_message_inner a:focus',
					)
				);
		
				$this->add_responsive_control(
					'message_link_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_message_inner a:hover,
										 {{WRAPPER}} .sc_igenerator_message_inner a:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'message_link_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_message_inner a:hover,
									  {{WRAPPER}} .sc_igenerator_message_inner a:focus',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Images'
			 */
			protected function register_controls_style_images() {

				$this->start_controls_section(
					'section_sc_igenerator_images_style',
					[
						'label' => __( 'Images', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'images_background',
						'selector' => '{{WRAPPER}} .sc_igenerator_images'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'images_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_images',
					)
				);
		
				$this->add_responsive_control(
					'images_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_images' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'images_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_images',
					]
				);

				$this->add_responsive_control(
					'images_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_igenerator_images' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'images_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_igenerator_images' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Single Image'
			 */
			protected function register_controls_style_single_image() {

				$this->start_controls_section(
					'section_sc_igenerator_single_image_style',
					[
						'label' => __( 'Single Image', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'single_image_background',
						'selector' => '{{WRAPPER}} .sc_igenerator_image_inner'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'single_image_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_image_inner',
					)
				);
		
				$this->add_responsive_control(
					'single_image_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_image_inner,
										 {{WRAPPER}} .sc_igenerator_image_inner img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'single_image_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_image_inner',
					]
				);

				$this->add_responsive_control(
					'single_image_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_igenerator_image_inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'section_sc_igenerator_button_download_style',
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
						'selector' => '{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default'
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

				$this->start_controls_tabs( 'tabs_sc_igenerator_button_download_style' );

				$this->start_controls_tab(
					'tab_sc_igenerator_button_download_normal',
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
							'{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default .sc_button_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default .sc_button_icon svg' => 'fill: {{VALUE}};',
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
						'selector' => '{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'button_download_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default',
					)
				);
		
				$this->add_responsive_control(
					'button_download_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_download_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_igenerator_button_download_hover',
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
							'{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default:hover,
							 {{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default:focus' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default:hover .sc_button_icon,
							 {{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default:focus .sc_button_icon' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'button_download_background_hover',
						'selector' => '{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default:hover,
										{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default:focus'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'button_download_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default:hover,
											{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default:focus',
					)
				);
		
				$this->add_responsive_control(
					'button_download_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default:hover,
										 {{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_download_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default:hover,
										{{WRAPPER}} .sc_igenerator_image_link.sc_button.sc_button_default:focus',
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
				if ( ! Utils::is_image_api_available() ) {
					trx_addons_get_template_part( 'templates/tpe.sc_placeholder.php',
						'trx_addons_args_sc_placeholder',
						apply_filters( 'trx_addons_filter_sc_placeholder_args', array(
							'sc' => 'trx_sc_igenerator',
							'title' => __('AI Image Generator is not available - token for access to the API for image generation is not specified', 'trx_addons'),
							'class' => 'sc_placeholder_with_title'
						) )
					);
				} else {
					trx_addons_get_template_part(TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/shortcodes/igenerator/tpe.igenerator.php',
						'trx_addons_args_sc_igenerator',
						array('element' => $this)
					);
				}
			}
		}
		
		// Register widget
		trx_addons_elm_register_widget( 'TRX_Addons_Elementor_Widget_IGenerator' );
	}
}
