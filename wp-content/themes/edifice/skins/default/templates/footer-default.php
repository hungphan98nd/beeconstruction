<?php
/**
 * The template to display default site footer
 *
 * @package EDIFICE
 * @since EDIFICE 1.0.10
 */

?>
<footer class="footer_wrap footer_default
<?php
$edifice_footer_scheme = edifice_get_theme_option( 'footer_scheme' );
if ( ! empty( $edifice_footer_scheme ) && ! edifice_is_inherit( $edifice_footer_scheme  ) ) {
	echo ' scheme_' . esc_attr( $edifice_footer_scheme );
}
?>
				">
	<?php

	// Footer widgets area
	get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/footer-widgets' ) );

	// Logo
	get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/footer-logo' ) );

	// Socials
	get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/footer-socials' ) );

	// Copyright area
	get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/footer-copyright' ) );

	?>
</footer><!-- /.footer_wrap -->
