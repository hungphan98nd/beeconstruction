<?php
/**
 * The template to display the socials in the footer
 *
 * @package EDIFICE
 * @since EDIFICE 1.0.10
 */


// Socials
if ( edifice_is_on( edifice_get_theme_option( 'socials_in_footer' ) ) ) {
	$edifice_output = edifice_get_socials_links();
	if ( '' != $edifice_output ) {
		?>
		<div class="footer_socials_wrap socials_wrap">
			<div class="footer_socials_inner">
				<?php edifice_show_layout( $edifice_output ); ?>
			</div>
		</div>
		<?php
	}
}
