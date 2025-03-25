<?php
/**
 * The template to display default site header
 *
 * @package EDIFICE
 * @since EDIFICE 1.0
 */

$edifice_header_css   = '';
$edifice_header_image = get_header_image();
$edifice_header_video = edifice_get_header_video();
if ( ! empty( $edifice_header_image ) && edifice_trx_addons_featured_image_override( is_singular() || edifice_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$edifice_header_image = edifice_get_current_mode_image( $edifice_header_image );
}

?><header class="top_panel top_panel_default
	<?php
	echo ! empty( $edifice_header_image ) || ! empty( $edifice_header_video ) ? ' with_bg_image' : ' without_bg_image';
	if ( '' != $edifice_header_video ) {
		echo ' with_bg_video';
	}
	if ( '' != $edifice_header_image ) {
		echo ' ' . esc_attr( edifice_add_inline_css_class( 'background-image: url(' . esc_url( $edifice_header_image ) . ');' ) );
	}
	if ( is_single() && has_post_thumbnail() ) {
		echo ' with_featured_image';
	}
	if ( edifice_is_on( edifice_get_theme_option( 'header_fullheight' ) ) ) {
		echo ' header_fullheight edifice-full-height';
	}
	$edifice_header_scheme = edifice_get_theme_option( 'header_scheme' );
	if ( ! empty( $edifice_header_scheme ) && ! edifice_is_inherit( $edifice_header_scheme  ) ) {
		echo ' scheme_' . esc_attr( $edifice_header_scheme );
	}
	?>
">
	<?php

	// Background video
	if ( ! empty( $edifice_header_video ) ) {
		get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/header-video' ) );
	}

	// Main menu
	get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/header-navi' ) );

	// Mobile header
	if ( edifice_is_on( edifice_get_theme_option( 'header_mobile_enabled' ) ) ) {
		get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/header-mobile' ) );
	}

	// Page title and breadcrumbs area
	if ( ! is_single() ) {
		get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/header-title' ) );
	}

	// Header widgets area
	get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/header-widgets' ) );
	?>
</header>
