<?php
/**
 * Shortcode: Squeeze
 *
 * @package ThemeREX Addons
 * @since v2.21.2
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}


// Load required styles and scripts for the frontend
if ( ! function_exists( 'trx_addons_sc_squeeze_load_scripts_front' ) ) {
	add_action( 'wp_enqueue_scripts', 'trx_addons_sc_squeeze_load_scripts_front', TRX_ADDONS_ENQUEUE_SCRIPTS_PRIORITY );
	add_action( 'trx_addons_action_pagebuilder_preview_scripts', 'trx_addons_sc_squeeze_load_scripts_front', 10, 1 );
	function trx_addons_sc_squeeze_load_scripts_front( $force = false ) {
		trx_addons_enqueue_optimized( 'sc_squeeze', $force, array(
			'lib' => array(
				'callback' => function() {
//					wp_enqueue_script( 'jquery-ui-touch-punch', trx_addons_get_file_url( 'js/touch-punch/jquery.ui.touch-punch.min.js' ), array( 'jquery', 'jquery-ui-draggable' ), null, true );
					trx_addons_enqueue_tweenmax();
				}
			),
			'css'  => array(
				'trx_addons-sc_squeeze' => array( 'src' => TRX_ADDONS_PLUGIN_SHORTCODES . 'squeeze/squeeze.css' ),
			),
			'js' => array(
				'trx_addons-sc_squeeze' => array( 'src' => TRX_ADDONS_PLUGIN_SHORTCODES . 'squeeze/squeeze.js', 'deps' => 'jquery' ),
			),
			'check' => array(
				array( 'type' => 'sc',  'sc' => 'trx_sc_squeeze' ),
//				array( 'type' => 'gb',  'sc' => 'wp:trx-addons/squeeze' ),
				array( 'type' => 'elm', 'sc' => '"widgetType":"trx_sc_squeeze"' ),
				array( 'type' => 'elm', 'sc' => '"shortcode":"[trx_sc_squeeze' ),
			)
		) );
	}
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'trx_addons_sc_squeeze_load_scripts_front_responsive' ) ) {
	add_action( 'wp_enqueue_scripts', 'trx_addons_sc_squeeze_load_scripts_front_responsive', TRX_ADDONS_ENQUEUE_RESPONSIVE_PRIORITY );
	add_action( 'trx_addons_action_load_scripts_front_sc_squeeze', 'trx_addons_sc_squeeze_load_scripts_front_responsive', 10, 1 );
	function trx_addons_sc_squeeze_load_scripts_front_responsive( $force = false ) {
		trx_addons_enqueue_optimized_responsive( 'sc_squeeze', $force, array(
			'css'  => array(
				'trx_addons-sc_squeeze-responsive' => array(
					'src' => TRX_ADDONS_PLUGIN_SHORTCODES . 'squeeze/squeeze.responsive.css',
					'media' => 'lg'
				),
			),
		) );
	}
}
	
// Merge shortcode's specific styles to the single stylesheet
if ( ! function_exists( 'trx_addons_sc_squeeze_merge_styles' ) ) {
	add_filter( 'trx_addons_filter_merge_styles', 'trx_addons_sc_squeeze_merge_styles' );
	function trx_addons_sc_squeeze_merge_styles( $list ) {
		$list[ TRX_ADDONS_PLUGIN_SHORTCODES . 'squeeze/squeeze.css' ] = false;
		return $list;
	}
}

// Merge shortcode's specific styles to the single stylesheet (responsive)
if ( ! function_exists( 'trx_addons_sc_squeeze_merge_styles_responsive' ) ) {
	add_filter( 'trx_addons_filter_merge_styles_responsive', 'trx_addons_sc_squeeze_merge_styles_responsive' );
	function trx_addons_sc_squeeze_merge_styles_responsive( $list ) {
		$list[ TRX_ADDONS_PLUGIN_SHORTCODES . 'squeeze/squeeze.responsive.css' ] = false;
		return $list;
	}
}

// Merge shortcode's specific scripts into single file
if ( ! function_exists( 'trx_addons_sc_squeeze_merge_scripts' ) ) {
	add_action( 'trx_addons_filter_merge_scripts', 'trx_addons_sc_squeeze_merge_scripts' );
	function trx_addons_sc_squeeze_merge_scripts( $list ) {
		$list[ TRX_ADDONS_PLUGIN_SHORTCODES . 'squeeze/squeeze.js' ] = false;
		return $list;
	}
}

// Load styles and scripts if present in the cache of the menu
if ( !function_exists( 'trx_addons_sc_squeeze_check_in_html_output' ) ) {
//	add_filter( 'trx_addons_filter_get_menu_cache_html', 'trx_addons_sc_squeeze_check_in_html_output', 10, 1 );
	add_action( 'trx_addons_action_show_layout_from_cache', 'trx_addons_sc_squeeze_check_in_html_output', 10, 1 );
	add_action( 'trx_addons_action_check_page_content', 'trx_addons_sc_squeeze_check_in_html_output', 10, 1 );
	function trx_addons_sc_squeeze_check_in_html_output( $content = '' ) {
		$args = array(
			'check' => array(
				'class=[\'"][^\'"]*sc_squeeze'
			)
		);
		if ( trx_addons_check_in_html_output( 'sc_squeeze', $content, $args ) ) {
			trx_addons_sc_squeeze_load_scripts_front( true );
		}
		return $content;
	}
}


// trx_sc_squeeze
//-------------------------------------------------------------
/*
[trx_sc_squeeze id="unique_id" slides="encoded_json_data"]
*/
if ( ! function_exists( 'trx_addons_sc_squeeze' ) ) {
	function trx_addons_sc_squeeze( $atts, $content = '' ) {	
		$atts = trx_addons_sc_prepare_atts( 'trx_sc_squeeze', $atts, trx_addons_sc_common_atts( 'trx_sc_squeeze', 'id,query', array(
			// Individual params
			"type" => "default",
			// Query posts
			'post_type' => 'post',
			'taxonomy' => 'category',
			// Custom slides
			"slides" => "",
			// Paginations
			"bullets" => "",
			"bullets_position" => "left",
			"numbers" => "",
			"numbers_position" => "center",
			"progress" => "",
			"progress_position" => "bottom",
			"disable_on_mobile" => 1,
		) ) );
		// Prepare atts
		if ( function_exists( 'vc_param_group_parse_atts' ) && ! is_array( $atts['slides'] ) ) {
			$atts['slides'] = (array) vc_param_group_parse_atts( $atts['slides'] );
		}
		if ( ! empty( $atts['ids'] ) ) {
			if ( is_array( $atts['ids'] ) ) {
				$atts['ids'] = join( ',', $atts['ids'] );
			}
			$atts['ids'] = str_replace( array( ';', ' '), array(',', ''), $atts['ids'] );
			$ids_count = count( explode( ',', $atts['ids'] ) );
			if ( empty( $atts['count'] ) || $atts['count'] != $ids_count ) {
				$atts['count'] = $ids_count;
			}
		}
		$atts['count'] = $atts['count'] < 0 ? -1 : max( 1, (int) $atts['count'] );
		$atts['offset'] = max( 0, (int) $atts['offset'] );
		if ( empty( $atts['orderby'] ) ) $atts['orderby'] = 'date';
		if ( empty( $atts['order'] ) )   $atts['order'] = 'desc';

		// Load shortcode-specific scripts and styles
		trx_addons_sc_squeeze_load_scripts_front( true );

		// Load template
		$output = '';
		ob_start();
		trx_addons_get_template_part( array(
										TRX_ADDONS_PLUGIN_SHORTCODES . 'squeeze/tpl.' . trx_addons_esc( trx_addons_sanitize_file_name( $atts['type'] ) ) . '.php',
										TRX_ADDONS_PLUGIN_SHORTCODES . 'squeeze/tpl.default.php'
										),
										'trx_addons_args_sc_squeeze',
										$atts
									);
		$output = ob_get_contents();
		ob_end_clean();

		return apply_filters( 'trx_addons_sc_output', $output, 'trx_sc_squeeze', $atts, $content );
	}
}


// Add shortcode [trx_sc_squeeze]
if ( ! function_exists( 'trx_addons_sc_squeeze_add_shortcode' ) ) {
	function trx_addons_sc_squeeze_add_shortcode() {
		add_shortcode( 'trx_sc_squeeze', 'trx_addons_sc_squeeze' );
	}
	add_action( 'init', 'trx_addons_sc_squeeze_add_shortcode', 20 );
}


// Add shortcodes
//----------------------------------------------------------------------------

// Add shortcodes to Elementor
if ( trx_addons_exists_elementor() && function_exists( 'trx_addons_elm_init' ) ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_SHORTCODES . 'squeeze/squeeze-sc-elementor.php';
}
