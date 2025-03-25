<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package EDIFICE
 * @since EDIFICE 1.0
 */

// Page (category, tag, archive, author) title

if ( edifice_need_page_title() ) {
	edifice_sc_layouts_showed( 'title', true );
	edifice_sc_layouts_showed( 'postmeta', true );
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( is_single() ) {
							?>
							<div class="sc_layouts_title_meta">
							<?php
								edifice_show_post_meta(
									apply_filters(
										'edifice_filter_post_meta_args', array(
											'components' => join( ',', edifice_array_get_keys_by_value( edifice_get_theme_option( 'meta_parts' ) ) ),
											'counters'   => join( ',', edifice_array_get_keys_by_value( edifice_get_theme_option( 'counters' ) ) ),
											'seo'        => edifice_is_on( edifice_get_theme_option( 'seo_snippets' ) ),
										), 'header', 1
									)
								);
							?>
							</div>
							<?php
						}

						// Blog/Post title
						?>
						<div class="sc_layouts_title_title">
							<?php
							$edifice_blog_title           = edifice_get_blog_title();
							$edifice_blog_title_text      = '';
							$edifice_blog_title_class     = '';
							$edifice_blog_title_link      = '';
							$edifice_blog_title_link_text = '';
							if ( is_array( $edifice_blog_title ) ) {
								$edifice_blog_title_text      = $edifice_blog_title['text'];
								$edifice_blog_title_class     = ! empty( $edifice_blog_title['class'] ) ? ' ' . $edifice_blog_title['class'] : '';
								$edifice_blog_title_link      = ! empty( $edifice_blog_title['link'] ) ? $edifice_blog_title['link'] : '';
								$edifice_blog_title_link_text = ! empty( $edifice_blog_title['link_text'] ) ? $edifice_blog_title['link_text'] : '';
							} else {
								$edifice_blog_title_text = $edifice_blog_title;
							}
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr( $edifice_blog_title_class ); ?>">
								<?php
								$edifice_top_icon = edifice_get_term_image_small();
								if ( ! empty( $edifice_top_icon ) ) {
									$edifice_attr = edifice_getimagesize( $edifice_top_icon );
									?>
									<img src="<?php echo esc_url( $edifice_top_icon ); ?>" alt="<?php esc_attr_e( 'Site icon', 'edifice' ); ?>"
										<?php
										if ( ! empty( $edifice_attr[3] ) ) {
											edifice_show_layout( $edifice_attr[3] );
										}
										?>
									>
									<?php
								}
								echo wp_kses_data( $edifice_blog_title_text );
								?>
							</h1>
							<?php
							if ( ! empty( $edifice_blog_title_link ) && ! empty( $edifice_blog_title_link_text ) ) {
								?>
								<a href="<?php echo esc_url( $edifice_blog_title_link ); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html( $edifice_blog_title_link_text ); ?></a>
								<?php
							}

							// Category/Tag description
							if ( ! is_paged() && ( is_category() || is_tag() || is_tax() ) ) {
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
							}

							?>
						</div>
						<?php

						// Breadcrumbs
						ob_start();
						do_action( 'edifice_action_breadcrumbs' );
						$edifice_breadcrumbs = ob_get_contents();
						ob_end_clean();
						edifice_show_layout( $edifice_breadcrumbs, '<div class="sc_layouts_title_breadcrumbs">', '</div>' );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
