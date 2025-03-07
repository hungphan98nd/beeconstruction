<?php
/**
 * The template to display Admin notices
 *
 * @package EDIFICE
 * @since EDIFICE 1.0.64
 */

$edifice_skins_url  = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$edifice_skins_args = get_query_var( 'edifice_skins_notice_args' );
?>
<div class="edifice_admin_notice edifice_skins_notice notice notice-info is-dismissible" data-notice="skins">
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
		<?php esc_html_e( 'New skins are available', 'edifice' ); ?>
	</h3>
	<?php

	// Description
	$edifice_total      = $edifice_skins_args['update'];	// Store value to the separate variable to avoid warnings from ThemeCheck plugin!
	$edifice_skins_msg  = $edifice_total > 0
							// Translators: Add new skins number
							? '<strong>' . sprintf( _n( '%d new version', '%d new versions', $edifice_total, 'edifice' ), $edifice_total ) . '</strong>'
							: '';
	$edifice_total      = $edifice_skins_args['free'];
	$edifice_skins_msg .= $edifice_total > 0
							? ( ! empty( $edifice_skins_msg ) ? ' ' . esc_html__( 'and', 'edifice' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d free skin', '%d free skins', $edifice_total, 'edifice' ), $edifice_total ) . '</strong>'
							: '';
	$edifice_total      = $edifice_skins_args['pay'];
	$edifice_skins_msg .= $edifice_skins_args['pay'] > 0
							? ( ! empty( $edifice_skins_msg ) ? ' ' . esc_html__( 'and', 'edifice' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d paid skin', '%d paid skins', $edifice_total, 'edifice' ), $edifice_total ) . '</strong>'
							: '';
	?>
	<div class="edifice_notice_text">
		<p>
			<?php
			// Translators: Add new skins info
			echo wp_kses_data( sprintf( __( "We are pleased to announce that %s are available for your theme", 'edifice' ), $edifice_skins_msg ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="edifice_notice_buttons">
		<?php
		// Link to the theme dashboard page
		?>
		<a href="<?php echo esc_url( $edifice_skins_url ); ?>" class="button button-primary"><i class="dashicons dashicons-update"></i> 
			<?php
			// Translators: Add theme name
			esc_html_e( 'Go to Skins manager', 'edifice' );
			?>
		</a>
	</div>
</div>
