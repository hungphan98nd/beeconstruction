<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package EDIFICE
 * @since EDIFICE 1.0.06
 */

$edifice_header_css   = '';
$edifice_header_image = get_header_image();
$edifice_header_video = edifice_get_header_video();
if ( ! empty( $edifice_header_image ) && edifice_trx_addons_featured_image_override( is_singular() || edifice_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$edifice_header_image = edifice_get_current_mode_image( $edifice_header_image );
}

$edifice_header_id = edifice_get_custom_header_id();
$edifice_header_meta = get_post_meta( $edifice_header_id, 'trx_addons_options', true );
if ( ! empty( $edifice_header_meta['margin'] ) ) {
	edifice_add_inline_css( sprintf( '.page_content_wrap{padding-top:%s}', esc_attr( edifice_prepare_css_value( $edifice_header_meta['margin'] ) ) ) );
}

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr( $edifice_header_id ); ?> top_panel_custom_<?php echo esc_attr( sanitize_title( get_the_title( $edifice_header_id ) ) ); ?>
				<?php
				echo ! empty( $edifice_header_image ) || ! empty( $edifice_header_video )
					? ' with_bg_image'
					: ' without_bg_image';
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

	// Custom header's layout
	do_action( 'edifice_action_show_layout', $edifice_header_id );

	// Header widgets area
	get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/header-widgets' ) );

	?>
</header>
