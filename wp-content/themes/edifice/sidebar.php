<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package EDIFICE
 * @since EDIFICE 1.0
 */

if ( edifice_sidebar_present() ) {
	
	$edifice_sidebar_type = edifice_get_theme_option( 'sidebar_type' );
	if ( 'custom' == $edifice_sidebar_type && ! edifice_is_layouts_available() ) {
		$edifice_sidebar_type = 'default';
	}
	
	// Catch output to the buffer
	ob_start();
	if ( 'default' == $edifice_sidebar_type ) {
		// Default sidebar with widgets
		$edifice_sidebar_name = edifice_get_theme_option( 'sidebar_widgets' );
		edifice_storage_set( 'current_sidebar', 'sidebar' );
		if ( is_active_sidebar( $edifice_sidebar_name ) ) {
			dynamic_sidebar( $edifice_sidebar_name );
		}
	} else {
		// Custom sidebar from Layouts Builder
		$edifice_sidebar_id = edifice_get_custom_sidebar_id();
		do_action( 'edifice_action_show_layout', $edifice_sidebar_id );
	}
	$edifice_out = trim( ob_get_contents() );
	ob_end_clean();
	
	// If any html is present - display it
	if ( ! empty( $edifice_out ) ) {
		$edifice_sidebar_position    = edifice_get_theme_option( 'sidebar_position' );
		$edifice_sidebar_position_ss = edifice_get_theme_option( 'sidebar_position_ss' );
		?>
		<div class="sidebar widget_area
			<?php
			echo ' ' . esc_attr( $edifice_sidebar_position );
			echo ' sidebar_' . esc_attr( $edifice_sidebar_position_ss );
			echo ' sidebar_' . esc_attr( $edifice_sidebar_type );

			$edifice_sidebar_scheme = apply_filters( 'edifice_filter_sidebar_scheme', edifice_get_theme_option( 'sidebar_scheme' ) );
			if ( ! empty( $edifice_sidebar_scheme ) && ! edifice_is_inherit( $edifice_sidebar_scheme ) && 'custom' != $edifice_sidebar_type ) {
				echo ' scheme_' . esc_attr( $edifice_sidebar_scheme );
			}
			?>
		" role="complementary">
			<?php

			// Skip link anchor to fast access to the sidebar from keyboard
			?>
			<a id="sidebar_skip_link_anchor" class="edifice_skip_link_anchor" href="#"></a>
			<?php

			do_action( 'edifice_action_before_sidebar_wrap', 'sidebar' );

			// Button to show/hide sidebar on mobile
			if ( in_array( $edifice_sidebar_position_ss, array( 'above', 'float' ) ) ) {
				$edifice_title = apply_filters( 'edifice_filter_sidebar_control_title', 'float' == $edifice_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'edifice' ) : '' );
				$edifice_text  = apply_filters( 'edifice_filter_sidebar_control_text', 'above' == $edifice_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'edifice' ) : '' );
				?>
				<a href="#" class="sidebar_control" title="<?php echo esc_attr( $edifice_title ); ?>"><?php echo esc_html( $edifice_text ); ?></a>
				<?php
			}
			?>
			<div class="sidebar_inner">
				<?php
				do_action( 'edifice_action_before_sidebar', 'sidebar' );
				edifice_show_layout( preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $edifice_out ) );
				do_action( 'edifice_action_after_sidebar', 'sidebar' );
				?>
			</div>
			<?php

			do_action( 'edifice_action_after_sidebar_wrap', 'sidebar' );

			?>
		</div>
		<div class="clearfix"></div>
		<?php
	}
}
