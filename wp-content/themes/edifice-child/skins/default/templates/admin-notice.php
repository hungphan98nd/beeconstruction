<?php
/**
 * The template to display Admin notices
 *
 * @package EDIFICE
 * @since EDIFICE 1.0.1
 */

$edifice_theme_slug = get_option( 'template' );
$edifice_theme_obj  = wp_get_theme( $edifice_theme_slug );
?>
<div class="edifice_admin_notice edifice_welcome_notice notice notice-info is-dismissible" data-notice="admin">
	<?php
	// Theme image
	$edifice_theme_img = edifice_get_file_url( 'screenshot.jpg' );
	if ( '' != $edifice_theme_img ) {
		?>
		<div class="edifice_notice_image"><img src="<?php echo esc_url( $edifice_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'edifice' ); ?>"></div>
		<?php
	}

	// Title
	?>
	<h3 class="edifice_notice_title">
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name and version to the 'Welcome' message
				__( 'Welcome to %1$s v.%2$s', 'edifice' ),
				$edifice_theme_obj->get( 'Name' ) . ( EDIFICE_THEME_FREE ? ' ' . __( 'Free', 'edifice' ) : '' ),
				$edifice_theme_obj->get( 'Version' )
			)
		);
		?>
	</h3>
	<?php

	// Description
	?>
	<div class="edifice_notice_text">
		<p class="edifice_notice_text_description">
			<?php
			echo str_replace( '. ', '.<br>', wp_kses_data( $edifice_theme_obj->description ) );
			?>
		</p>
		<p class="edifice_notice_text_info">
			<?php
			echo wp_kses_data( __( 'Attention! Plugin "ThemeREX Addons" is required! Please, install and activate it!', 'edifice' ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="edifice_notice_buttons">
		<?php
		// Link to the page 'About Theme'
		?>
		<a href="<?php echo esc_url( admin_url() . 'themes.php?page=edifice_about' ); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> 
			<?php
			echo esc_html__( 'Install plugin "ThemeREX Addons"', 'edifice' );
			?>
		</a>
	</div>
</div>
