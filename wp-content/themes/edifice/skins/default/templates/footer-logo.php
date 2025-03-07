<?php
/**
 * The template to display the site logo in the footer
 *
 * @package EDIFICE
 * @since EDIFICE 1.0.10
 */

// Logo
if ( edifice_is_on( edifice_get_theme_option( 'logo_in_footer' ) ) ) {
	$edifice_logo_image = edifice_get_logo_image( 'footer' );
	$edifice_logo_text  = get_bloginfo( 'name' );
	if ( ! empty( $edifice_logo_image['logo'] ) || ! empty( $edifice_logo_text ) ) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if ( ! empty( $edifice_logo_image['logo'] ) ) {
					$edifice_attr = edifice_getimagesize( $edifice_logo_image['logo'] );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
							. '<img src="' . esc_url( $edifice_logo_image['logo'] ) . '"'
								. ( ! empty( $edifice_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $edifice_logo_image['logo_retina'] ) . ' 2x"' : '' )
								. ' class="logo_footer_image"'
								. ' alt="' . esc_attr__( 'Site logo', 'edifice' ) . '"'
								. ( ! empty( $edifice_attr[3] ) ? ' ' . wp_kses_data( $edifice_attr[3] ) : '' )
							. '>'
						. '</a>';
				} elseif ( ! empty( $edifice_logo_text ) ) {
					echo '<h1 class="logo_footer_text">'
							. '<a href="' . esc_url( home_url( '/' ) ) . '">'
								. esc_html( $edifice_logo_text )
							. '</a>'
						. '</h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
