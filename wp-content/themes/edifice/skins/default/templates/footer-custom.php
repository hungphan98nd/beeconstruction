<?php
/**
 * The template to display default site footer
 *
 * @package EDIFICE
 * @since EDIFICE 1.0.10
 */

$edifice_footer_id = edifice_get_custom_footer_id();
$edifice_footer_meta = get_post_meta( $edifice_footer_id, 'trx_addons_options', true );
if ( ! empty( $edifice_footer_meta['margin'] ) ) {
	edifice_add_inline_css( sprintf( '.page_content_wrap{padding-bottom:%s}', esc_attr( edifice_prepare_css_value( $edifice_footer_meta['margin'] ) ) ) );
}
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr( $edifice_footer_id ); ?> footer_custom_<?php echo esc_attr( sanitize_title( get_the_title( $edifice_footer_id ) ) ); ?>
						<?php
						$edifice_footer_scheme = edifice_get_theme_option( 'footer_scheme' );
						if ( ! empty( $edifice_footer_scheme ) && ! edifice_is_inherit( $edifice_footer_scheme  ) ) {
							echo ' scheme_' . esc_attr( $edifice_footer_scheme );
						}
						?>
						">
	<?php
	// Custom footer's layout
	do_action( 'edifice_action_show_layout', $edifice_footer_id );
	?>
</footer><!-- /.footer_wrap -->
