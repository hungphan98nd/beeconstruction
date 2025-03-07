<?php
/**
 * The template to display the widgets area in the header
 *
 * @package EDIFICE
 * @since EDIFICE 1.0
 */

// Header sidebar
$edifice_header_name    = edifice_get_theme_option( 'header_widgets' );
$edifice_header_present = ! edifice_is_off( $edifice_header_name ) && is_active_sidebar( $edifice_header_name );
if ( $edifice_header_present ) {
	edifice_storage_set( 'current_sidebar', 'header' );
	$edifice_header_wide = edifice_get_theme_option( 'header_wide' );
	ob_start();
	if ( is_active_sidebar( $edifice_header_name ) ) {
		dynamic_sidebar( $edifice_header_name );
	}
	$edifice_widgets_output = ob_get_contents();
	ob_end_clean();
	if ( ! empty( $edifice_widgets_output ) ) {
		$edifice_widgets_output = preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $edifice_widgets_output );
		$edifice_need_columns   = strpos( $edifice_widgets_output, 'columns_wrap' ) === false;
		if ( $edifice_need_columns ) {
			$edifice_columns = max( 0, (int) edifice_get_theme_option( 'header_columns' ) );
			if ( 0 == $edifice_columns ) {
				$edifice_columns = min( 6, max( 1, edifice_tags_count( $edifice_widgets_output, 'aside' ) ) );
			}
			if ( $edifice_columns > 1 ) {
				$edifice_widgets_output = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $edifice_columns ) . ' widget', $edifice_widgets_output );
			} else {
				$edifice_need_columns = false;
			}
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo ! empty( $edifice_header_wide ) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<?php do_action( 'edifice_action_before_sidebar_wrap', 'header' ); ?>
			<div class="header_widgets_inner widget_area_inner">
				<?php
				if ( ! $edifice_header_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $edifice_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'edifice_action_before_sidebar', 'header' );
				edifice_show_layout( $edifice_widgets_output );
				do_action( 'edifice_action_after_sidebar', 'header' );
				if ( $edifice_need_columns ) {
					?>
					</div>	<!-- /.columns_wrap -->
					<?php
				}
				if ( ! $edifice_header_wide ) {
					?>
					</div>	<!-- /.content_wrap -->
					<?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
			<?php do_action( 'edifice_action_after_sidebar_wrap', 'header' ); ?>
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
