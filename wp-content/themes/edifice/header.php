<?php
/**
 * The Header: Logo and main menu
 *
 * @package EDIFICE
 * @since EDIFICE 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js<?php
	// Class scheme_xxx need in the <html> as context for the <body>!
	echo ' scheme_' . esc_attr( edifice_get_theme_option( 'color_scheme' ) );
?>">

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
	do_action( 'edifice_action_before_body' );
	?>

	<div class="<?php echo esc_attr( apply_filters( 'edifice_filter_body_wrap_class', 'body_wrap' ) ); ?>" <?php do_action('edifice_action_body_wrap_attributes'); ?>>

		<?php do_action( 'edifice_action_before_page_wrap' ); ?>

		<div class="<?php echo esc_attr( apply_filters( 'edifice_filter_page_wrap_class', 'page_wrap' ) ); ?>" <?php do_action('edifice_action_page_wrap_attributes'); ?>>

			<?php do_action( 'edifice_action_page_wrap_start' ); ?>

			<?php
			$edifice_full_post_loading = ( edifice_is_singular( 'post' ) || edifice_is_singular( 'attachment' ) ) && edifice_get_value_gp( 'action' ) == 'full_post_loading';
			$edifice_prev_post_loading = ( edifice_is_singular( 'post' ) || edifice_is_singular( 'attachment' ) ) && edifice_get_value_gp( 'action' ) == 'prev_post_loading';

			// Don't display the header elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ! $edifice_full_post_loading && ! $edifice_prev_post_loading ) {

				// Short links to fast access to the content, sidebar and footer from the keyboard
				?>
				<a class="edifice_skip_link skip_to_content_link" href="#content_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'edifice_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to content", 'edifice' ); ?></a>
				<?php if ( edifice_sidebar_present() ) { ?>
				<a class="edifice_skip_link skip_to_sidebar_link" href="#sidebar_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'edifice_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to sidebar", 'edifice' ); ?></a>
				<?php } ?>
				<a class="edifice_skip_link skip_to_footer_link" href="#footer_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'edifice_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to footer", 'edifice' ); ?></a>

				<?php
				do_action( 'edifice_action_before_header' );

				// Header
				$edifice_header_type = edifice_get_theme_option( 'header_type' );
				if ( 'custom' == $edifice_header_type && ! edifice_is_layouts_available() ) {
					$edifice_header_type = 'default';
				}
				get_template_part( apply_filters( 'edifice_filter_get_template_part', "templates/header-" . sanitize_file_name( $edifice_header_type ) ) );

				// Side menu
				if ( in_array( edifice_get_theme_option( 'menu_side' ), array( 'left', 'right' ) ) ) {
					get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/header-navi-side' ) );
				}

				// Mobile menu
				get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/header-navi-mobile' ) );

				do_action( 'edifice_action_after_header' );

			}
			?>

			<?php do_action( 'edifice_action_before_page_content_wrap' ); ?>

			<div class="page_content_wrap<?php
				if ( edifice_is_off( edifice_get_theme_option( 'remove_margins' ) ) ) {
					if ( empty( $edifice_header_type ) ) {
						$edifice_header_type = edifice_get_theme_option( 'header_type' );
					}
					if ( 'custom' == $edifice_header_type && edifice_is_layouts_available() ) {
						$edifice_header_id = edifice_get_custom_header_id();
						if ( $edifice_header_id > 0 ) {
							$edifice_header_meta = edifice_get_custom_layout_meta( $edifice_header_id );
							if ( ! empty( $edifice_header_meta['margin'] ) ) {
								?> page_content_wrap_custom_header_margin<?php
							}
						}
					}
					$edifice_footer_type = edifice_get_theme_option( 'footer_type' );
					if ( 'custom' == $edifice_footer_type && edifice_is_layouts_available() ) {
						$edifice_footer_id = edifice_get_custom_footer_id();
						if ( $edifice_footer_id ) {
							$edifice_footer_meta = edifice_get_custom_layout_meta( $edifice_footer_id );
							if ( ! empty( $edifice_footer_meta['margin'] ) ) {
								?> page_content_wrap_custom_footer_margin<?php
							}
						}
					}
				}
				do_action( 'edifice_action_page_content_wrap_class', $edifice_prev_post_loading );
				?>"<?php
				if ( apply_filters( 'edifice_filter_is_prev_post_loading', $edifice_prev_post_loading ) ) {
					?> data-single-style="<?php echo esc_attr( edifice_get_theme_option( 'single_style' ) ); ?>"<?php
				}
				do_action( 'edifice_action_page_content_wrap_data', $edifice_prev_post_loading );
			?>>
				<?php
				do_action( 'edifice_action_page_content_wrap', $edifice_full_post_loading || $edifice_prev_post_loading );

				// Single posts banner
				if ( apply_filters( 'edifice_filter_single_post_header', edifice_is_singular( 'post' ) || edifice_is_singular( 'attachment' ) ) ) {
					if ( $edifice_prev_post_loading ) {
						if ( edifice_get_theme_option( 'posts_navigation_scroll_which_block' ) != 'article' ) {
							do_action( 'edifice_action_between_posts' );
						}
					}
					// Single post thumbnail and title
					$edifice_path = apply_filters( 'edifice_filter_get_template_part', 'templates/single-styles/' . edifice_get_theme_option( 'single_style' ) );
					if ( edifice_get_file_dir( $edifice_path . '.php' ) != '' ) {
						get_template_part( $edifice_path );
					}
				}

				// Widgets area above page
				$edifice_body_style   = edifice_get_theme_option( 'body_style' );
				$edifice_widgets_name = edifice_get_theme_option( 'widgets_above_page' );
				$edifice_show_widgets = ! edifice_is_off( $edifice_widgets_name ) && is_active_sidebar( $edifice_widgets_name );
				if ( $edifice_show_widgets ) {
					if ( 'fullscreen' != $edifice_body_style ) {
						?>
						<div class="content_wrap">
							<?php
					}
					edifice_create_widgets_area( 'widgets_above_page' );
					if ( 'fullscreen' != $edifice_body_style ) {
						?>
						</div>
						<?php
					}
				}

				// Content area
				do_action( 'edifice_action_before_content_wrap' );
				?>
				<div class="content_wrap<?php echo 'fullscreen' == $edifice_body_style ? '_fullscreen' : ''; ?>">

					<?php do_action( 'edifice_action_content_wrap_start' ); ?>

					<div class="content">
						<?php
						do_action( 'edifice_action_page_content_start' );

						// Skip link anchor to fast access to the content from keyboard
						?>
						<a id="content_skip_link_anchor" class="edifice_skip_link_anchor" href="#"></a>
						<?php
						// Single posts banner between prev/next posts
						if ( ( edifice_is_singular( 'post' ) || edifice_is_singular( 'attachment' ) )
							&& $edifice_prev_post_loading 
							&& edifice_get_theme_option( 'posts_navigation_scroll_which_block' ) == 'article'
						) {
							do_action( 'edifice_action_between_posts' );
						}

						// Widgets area above content
						edifice_create_widgets_area( 'widgets_above_content' );

						do_action( 'edifice_action_page_content_start_text' );
