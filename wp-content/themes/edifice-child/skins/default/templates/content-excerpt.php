<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package EDIFICE
 * @since EDIFICE 1.0
 */

$edifice_template_args = get_query_var( 'edifice_template_args' );
$edifice_columns = 1;
if ( is_array( $edifice_template_args ) ) {
	$edifice_columns    = empty( $edifice_template_args['columns'] ) ? 1 : max( 1, $edifice_template_args['columns'] );
	$edifice_blog_style = array( $edifice_template_args['type'], $edifice_columns );
	if ( ! empty( $edifice_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $edifice_columns > 1 ) {
	    $edifice_columns_class = edifice_get_column_class( 1, $edifice_columns, ! empty( $edifice_template_args['columns_tablet']) ? $edifice_template_args['columns_tablet'] : '', ! empty($edifice_template_args['columns_mobile']) ? $edifice_template_args['columns_mobile'] : '' );
		?>
		<div class="<?php echo esc_attr( $edifice_columns_class ); ?>">
		<?php
	}
} else {
	$edifice_template_args = array();
}
$edifice_expanded    = ! edifice_sidebar_present() && edifice_get_theme_option( 'expand_content' ) == 'expand';
$edifice_post_format = get_post_format();
$edifice_post_format = empty( $edifice_post_format ) ? 'standard' : str_replace( 'post-format-', '', $edifice_post_format );
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_excerpt post_format_' . esc_attr( $edifice_post_format ) );
	edifice_add_blog_animation( $edifice_template_args );
	?>
>
	<?php

	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?>
		<span class="post_label label_sticky"></span>
		<?php
	}

	// Featured image
	$edifice_hover      = ! empty( $edifice_template_args['hover'] ) && ! edifice_is_inherit( $edifice_template_args['hover'] )
							? $edifice_template_args['hover']
							: edifice_get_theme_option( 'image_hover' );
	$edifice_components = ! empty( $edifice_template_args['meta_parts'] )
							? ( is_array( $edifice_template_args['meta_parts'] )
								? $edifice_template_args['meta_parts']
								: array_map( 'trim', explode( ',', $edifice_template_args['meta_parts'] ) )
								)
							: edifice_array_get_keys_by_value( edifice_get_theme_option( 'meta_parts' ) );
	edifice_show_post_featured( apply_filters( 'edifice_filter_args_featured',
		array(
			'no_links'   => ! empty( $edifice_template_args['no_links'] ),
			'hover'      => $edifice_hover,
			'meta_parts' => $edifice_components,
			'thumb_size' => ! empty( $edifice_template_args['thumb_size'] )
							? $edifice_template_args['thumb_size']
							: edifice_get_thumb_size( strpos( edifice_get_theme_option( 'body_style' ), 'full' ) !== false
								? 'full'
								: ( $edifice_expanded 
									? 'huge' 
									: 'big' 
									)
								),
		),
		'content-excerpt',
		$edifice_template_args
	) );

	// Title and post meta
	$edifice_show_title = get_the_title() != '';
	$edifice_show_meta  = count( $edifice_components ) > 0 && ! in_array( $edifice_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $edifice_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			if ( apply_filters( 'edifice_filter_show_blog_title', true, 'excerpt' ) ) {
				do_action( 'edifice_action_before_post_title' );
				if ( empty( $edifice_template_args['no_links'] ) ) {
					the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
				} else {
					the_title( '<h3 class="post_title entry-title">', '</h3>' );
				}
				do_action( 'edifice_action_after_post_title' );
			}
			?>
		</div><!-- .post_header -->
		<?php
	}

	// Post content
	if ( apply_filters( 'edifice_filter_show_blog_excerpt', empty( $edifice_template_args['hide_excerpt'] ) && edifice_get_theme_option( 'excerpt_length' ) > 0, 'excerpt' ) ) {
		?>
		<div class="post_content entry-content">
			<?php

			// Post meta
			if ( apply_filters( 'edifice_filter_show_blog_meta', $edifice_show_meta, $edifice_components, 'excerpt' ) ) {
				if ( count( $edifice_components ) > 0 ) {
					do_action( 'edifice_action_before_post_meta' );
					edifice_show_post_meta(
						apply_filters(
							'edifice_filter_post_meta_args', array(
								'components' => join( ',', $edifice_components ),
								'seo'        => false,
								'echo'       => true,
							), 'excerpt', 1
						)
					);
					do_action( 'edifice_action_after_post_meta' );
				}
			}

			if ( edifice_get_theme_option( 'blog_content' ) == 'fullpost' ) {
				// Post content area
				?>
				<div class="post_content_inner">
					<?php
					do_action( 'edifice_action_before_full_post_content' );
					the_content( '' );
					do_action( 'edifice_action_after_full_post_content' );
					?>
				</div>
				<?php
				// Inner pages
				wp_link_pages(
					array(
						'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'edifice' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'edifice' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					)
				);
			} else {
				// Post content area
				edifice_show_post_content( $edifice_template_args, '<div class="post_content_inner">', '</div>' );
			}

			// More button
			if ( apply_filters( 'edifice_filter_show_blog_readmore',  ! isset( $edifice_template_args['more_button'] ) || ! empty( $edifice_template_args['more_button'] ), 'excerpt' ) ) {
				if ( empty( $edifice_template_args['no_links'] ) ) {
					do_action( 'edifice_action_before_post_readmore' );
					if ( edifice_get_theme_option( 'blog_content' ) != 'fullpost' ) {
						edifice_show_post_more_link( $edifice_template_args, '<p>', '</p>' );
					} else {
						edifice_show_post_comments_link( $edifice_template_args, '<p>', '</p>' );
					}
					do_action( 'edifice_action_after_post_readmore' );
				}
			}

			?>
		</div><!-- .entry-content -->
		<?php
	}
	?>
</article>
<?php

if ( is_array( $edifice_template_args ) ) {
	if ( ! empty( $edifice_template_args['slider'] ) || $edifice_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
