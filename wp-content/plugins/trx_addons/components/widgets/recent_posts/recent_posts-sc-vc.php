<?php
/**
 * Widget: Recent posts (WPBakery support)
 *
 * @package ThemeREX Addons
 * @since v1.0
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}


// Add [trx_widget_recent_posts] in the VC shortcodes list
if (!function_exists('trx_addons_sc_widget_recent_posts_add_in_vc')) {
	function trx_addons_sc_widget_recent_posts_add_in_vc() {
		
		if (!trx_addons_exists_vc()) return;
		
		vc_lean_map("trx_widget_recent_posts", 'trx_addons_sc_widget_recent_posts_add_in_vc_params');
		class WPBakeryShortCode_Trx_Widget_Recent_Posts extends WPBakeryShortCode {}
	}
	add_action('init', 'trx_addons_sc_widget_recent_posts_add_in_vc', 20);
}

// Return params
if (!function_exists('trx_addons_sc_widget_recent_posts_add_in_vc_params')) {
	function trx_addons_sc_widget_recent_posts_add_in_vc_params() {
		return apply_filters('trx_addons_sc_map', array(
				"base" => "trx_widget_recent_posts",
				"name" => esc_html__("Recent Posts", 'trx_addons'),
				"description" => wp_kses_data( __("Insert recent posts list with thumbs, meta and category", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_widget_recent_posts',
				"class" => "trx_widget_recent_posts",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array_merge(
					array(
						array(
							"param_name" => "title",
							"heading" => esc_html__("Widget title", 'trx_addons'),
							"description" => wp_kses_data( __("Title of the widget", 'trx_addons') ),
							"admin_label" => true,
							"type" => "textfield"
						),
						array(
							"param_name" => "number",
							"heading" => esc_html__("Number of posts to display", 'trx_addons'),
							"description" => wp_kses_data( __("How many posts display in widget?", 'trx_addons') ),
							"admin_label" => true,
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "show_image",
							"heading" => esc_html__("Show image", 'trx_addons'),
							"description" => wp_kses_data( __("Do you want display a featured image?", 'trx_addons') ),
							"group" => esc_html__('Details', 'trx_addons'),
							"std" => "1",
							"value" => array("Show image" => "1" ),
							"type" => "checkbox"
						),
						array(
							"param_name" => "show_author",
							"heading" => esc_html__("Show author", 'trx_addons'),
							"description" => wp_kses_data( __("Do you want display an author?", 'trx_addons') ),
							"group" => esc_html__('Details', 'trx_addons'),
							"std" => "1",
							"value" => array("Show author" => "1" ),
							"type" => "checkbox"
						),
						array(
							"param_name" => "show_date",
							"heading" => esc_html__("Show date", 'trx_addons'),
							"description" => wp_kses_data( __("Do you want display a publish date?", 'trx_addons') ),
							"group" => esc_html__('Details', 'trx_addons'),
							"std" => "1",
							"value" => array("Show date" => "1" ),
							"type" => "checkbox"
						),
						array(
							"param_name" => "show_counters",
							"heading" => esc_html__("Show view count", 'trx_addons'),
							"description" => wp_kses_data( __("Do you want display a view count?", 'trx_addons') ),
							"group" => esc_html__('Details', 'trx_addons'),
							"std" => "1",
							"value" => array("Show counters" => "1" ),
							"type" => "checkbox"
						),
						array(
							"param_name" => "show_categories",
							"heading" => esc_html__("Show categories", 'trx_addons'),
							"description" => wp_kses_data( __("Do you want display categories?", 'trx_addons') ),
							"group" => esc_html__('Details', 'trx_addons'),
							"std" => "1",
							"value" => array("Show categories" => "1" ),
							"type" => "checkbox"
						)
					),
					trx_addons_vc_add_id_param()
				)
			), 'trx_widget_recent_posts' );
	}
}
