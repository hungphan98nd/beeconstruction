<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package EDIFICE
 * @since EDIFICE 1.0
 */

$edifice_args = get_query_var( 'edifice_logo_args' );

// Site logo
$edifice_logo_type   = isset( $edifice_args['type'] ) ? $edifice_args['type'] : '';
$edifice_logo_image  = edifice_get_logo_image( $edifice_logo_type );
$edifice_logo_text   = edifice_is_on( edifice_get_theme_option( 'logo_text' ) ) ? get_bloginfo( 'name' ) : '';
$edifice_logo_slogan = get_bloginfo( 'description', 'display' );
if ( ! empty( $edifice_logo_image['logo'] ) || ! empty( $edifice_logo_text ) ) {
	?><a class="sc_layouts_logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
		if ( ! empty( $edifice_logo_image['logo'] ) ) {
			if ( empty( $edifice_logo_type ) && function_exists( 'the_custom_logo' ) && is_numeric($edifice_logo_image['logo']) && (int) $edifice_logo_image['logo'] > 0 ) {
				the_custom_logo();
			} else {
				$edifice_attr = edifice_getimagesize( $edifice_logo_image['logo'] );
				echo '<img src="' . esc_url( $edifice_logo_image['logo'] ) . '"'
						. ( ! empty( $edifice_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $edifice_logo_image['logo_retina'] ) . ' 2x"' : '' )
						. ' alt="' . esc_attr( $edifice_logo_text ) . '"'
						. ( ! empty( $edifice_attr[3] ) ? ' ' . wp_kses_data( $edifice_attr[3] ) : '' )
						. '>';
			}
		} else {
			edifice_show_layout( edifice_prepare_macros( $edifice_logo_text ), '<span class="logo_text">', '</span>' );
			edifice_show_layout( edifice_prepare_macros( $edifice_logo_slogan ), '<span class="logo_slogan">', '</span>' );
		}
		?>
	</a>
	<?php
}
