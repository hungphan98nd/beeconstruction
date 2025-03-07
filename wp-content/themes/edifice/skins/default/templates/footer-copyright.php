<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package EDIFICE
 * @since EDIFICE 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap
<?php
$edifice_copyright_scheme = edifice_get_theme_option( 'copyright_scheme' );
if ( ! empty( $edifice_copyright_scheme ) && ! edifice_is_inherit( $edifice_copyright_scheme  ) ) {
	echo ' scheme_' . esc_attr( $edifice_copyright_scheme );
}
?>
				">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text">
			<?php
				$edifice_copyright = edifice_get_theme_option( 'copyright' );
			if ( ! empty( $edifice_copyright ) ) {
				// Replace {{Y}} or {Y} with the current year
				$edifice_copyright = str_replace( array( '{{Y}}', '{Y}' ), date( 'Y' ), $edifice_copyright );
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$edifice_copyright = edifice_prepare_macros( $edifice_copyright );
				// Display copyright
				echo wp_kses( nl2br( $edifice_copyright ), 'edifice_kses_content' );
			}
			?>
			</div>
		</div>
	</div>
</div>
