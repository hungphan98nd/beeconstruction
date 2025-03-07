<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package EDIFICE
 * @since EDIFICE 1.0.10
 */

// Footer sidebar
$edifice_footer_name    = edifice_get_theme_option( 'footer_widgets' );
$edifice_footer_present = ! edifice_is_off( $edifice_footer_name ) && is_active_sidebar( $edifice_footer_name );
if ( $edifice_footer_present ) {
	edifice_storage_set( 'current_sidebar', 'footer' );
	$edifice_footer_wide = edifice_get_theme_option( 'footer_wide' );
	ob_start();
	if ( is_active_sidebar( $edifice_footer_name ) ) {
		dynamic_sidebar( $edifice_footer_name );
	}
	$edifice_out = trim( ob_get_contents() );
	ob_end_clean();
	if ( ! empty( $edifice_out ) ) {
		$edifice_out          = preg_replace( "/<\\/aside>[\r\n\s]*<aside/", '</aside><aside', $edifice_out );
		$edifice_need_columns = true;   //or check: strpos($edifice_out, 'columns_wrap')===false;
		if ( $edifice_need_columns ) {
			$edifice_columns = max( 0, (int) edifice_get_theme_option( 'footer_columns' ) );			
			if ( 0 == $edifice_columns ) {
				$edifice_columns = min( 4, max( 1, edifice_tags_count( $edifice_out, 'aside' ) ) );
			}
			if ( $edifice_columns > 1 ) {
				$edifice_out = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $edifice_columns ) . ' widget', $edifice_out );
			} else {
				$edifice_need_columns = false;
			}
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo ! empty( $edifice_footer_wide ) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<?php do_action( 'edifice_action_before_sidebar_wrap', 'footer' ); ?>
			<div class="footer_widgets_inner widget_area_inner">
				<?php
				if ( ! $edifice_footer_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $edifice_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'edifice_action_before_sidebar', 'footer' );
				edifice_show_layout( $edifice_out );
				do_action( 'edifice_action_after_sidebar', 'footer' );
				if ( $edifice_need_columns ) {
					?>
					</div><!-- /.columns_wrap -->
					<?php
				}
				if ( ! $edifice_footer_wide ) {
					?>
					</div><!-- /.content_wrap -->
					<?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
			<?php do_action( 'edifice_action_after_sidebar_wrap', 'footer' ); ?>
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
