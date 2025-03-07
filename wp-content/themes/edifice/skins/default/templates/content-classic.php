<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package EDIFICE
 * @since EDIFICE 1.0
 */

$edifice_template_args = get_query_var( 'edifice_template_args' );

if ( is_array( $edifice_template_args ) ) {
	$edifice_columns    = empty( $edifice_template_args['columns'] ) ? 2 : max( 1, $edifice_template_args['columns'] );
	$edifice_blog_style = array( $edifice_template_args['type'], $edifice_columns );
    $edifice_columns_class = edifice_get_column_class( 1, $edifice_columns, ! empty( $edifice_template_args['columns_tablet']) ? $edifice_template_args['columns_tablet'] : '', ! empty($edifice_template_args['columns_mobile']) ? $edifice_template_args['columns_mobile'] : '' );
} else {
	$edifice_template_args = array();
	$edifice_blog_style = explode( '_', edifice_get_theme_option( 'blog_style' ) );
	$edifice_columns    = empty( $edifice_blog_style[1] ) ? 2 : max( 1, $edifice_blog_style[1] );
    $edifice_columns_class = edifice_get_column_class( 1, $edifice_columns );
}
$edifice_expanded   = ! edifice_sidebar_present() && edifice_get_theme_option( 'expand_content' ) == 'expand';

$edifice_post_format = get_post_format();
$edifice_post_format = empty( $edifice_post_format ) ? 'standard' : str_replace( 'post-format-', '', $edifice_post_format );

?><div class="<?php
	if ( ! empty( $edifice_template_args['slider'] ) ) {
		echo ' slider-slide swiper-slide';
	} else {
		echo ( edifice_is_blog_style_use_masonry( $edifice_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $edifice_columns ) : esc_attr( $edifice_columns_class ) );
	}
?>"><article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $edifice_post_format )
				. ' post_layout_classic post_layout_classic_' . esc_attr( $edifice_columns )
				. ' post_layout_' . esc_attr( $edifice_blog_style[0] )
				. ' post_layout_' . esc_attr( $edifice_blog_style[0] ) . '_' . esc_attr( $edifice_columns )
	);
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
								: explode( ',', $edifice_template_args['meta_parts'] )
								)
							: edifice_array_get_keys_by_value( edifice_get_theme_option( 'meta_parts' ) );

	edifice_show_post_featured( apply_filters( 'edifice_filter_args_featured',
		array(
			'thumb_size' => ! empty( $edifice_template_args['thumb_size'] )
				? $edifice_template_args['thumb_size']
				: edifice_get_thumb_size(
				'classic' == $edifice_blog_style[0]
						? ( strpos( edifice_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $edifice_columns > 2 ? 'big' : 'huge' )
								: ( $edifice_columns > 2
									? ( $edifice_expanded ? 'square' : 'square' )
									: ($edifice_columns > 1 ? 'square' : ( $edifice_expanded ? 'huge' : 'big' ))
									)
							)
						: ( strpos( edifice_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $edifice_columns > 2 ? 'masonry-big' : 'full' )
								: ($edifice_columns === 1 ? ( $edifice_expanded ? 'huge' : 'big' ) : ( $edifice_columns <= 2 && $edifice_expanded ? 'masonry-big' : 'masonry' ))
							)
			),
			'hover'      => $edifice_hover,
			'meta_parts' => $edifice_components,
			'no_links'   => ! empty( $edifice_template_args['no_links'] ),
        ),
        'content-classic',
        $edifice_template_args
    ) );

	// Title and post meta
	$edifice_show_title = get_the_title() != '';
	$edifice_show_meta  = count( $edifice_components ) > 0 && ! in_array( $edifice_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $edifice_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php

			// Post meta
			if ( apply_filters( 'edifice_filter_show_blog_meta', $edifice_show_meta, $edifice_components, 'classic' ) ) {
				if ( count( $edifice_components ) > 0 ) {
					do_action( 'edifice_action_before_post_meta' );
					edifice_show_post_meta(
						apply_filters(
							'edifice_filter_post_meta_args', array(
							'components' => join( ',', $edifice_components ),
							'seo'        => false,
							'echo'       => true,
						), $edifice_blog_style[0], $edifice_columns
						)
					);
					do_action( 'edifice_action_after_post_meta' );
				}
			}

			// Post title
			if ( apply_filters( 'edifice_filter_show_blog_title', true, 'classic' ) ) {
				do_action( 'edifice_action_before_post_title' );
				if ( empty( $edifice_template_args['no_links'] ) ) {
					the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
				} else {
					the_title( '<h4 class="post_title entry-title">', '</h4>' );
				}
				do_action( 'edifice_action_after_post_title' );
			}

			if( !in_array( $edifice_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
				// More button
				if ( apply_filters( 'edifice_filter_show_blog_readmore', ! $edifice_show_title || ! empty( $edifice_template_args['more_button'] ), 'classic' ) ) {
					if ( empty( $edifice_template_args['no_links'] ) ) {
						do_action( 'edifice_action_before_post_readmore' );
						edifice_show_post_more_link( $edifice_template_args, '<div class="more-wrap">', '</div>' );
						do_action( 'edifice_action_after_post_readmore' );
					}
				}
			}
			?>
		</div><!-- .entry-header -->
		<?php
	}

	// Post content
	if( in_array( $edifice_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
		ob_start();
		if (apply_filters('edifice_filter_show_blog_excerpt', empty($edifice_template_args['hide_excerpt']) && edifice_get_theme_option('excerpt_length') > 0, 'classic')) {
			edifice_show_post_content($edifice_template_args, '<div class="post_content_inner">', '</div>');
		}
		// More button
		if(! empty( $edifice_template_args['more_button'] )) {
			if ( empty( $edifice_template_args['no_links'] ) ) {
				do_action( 'edifice_action_before_post_readmore' );
				edifice_show_post_more_link( $edifice_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'edifice_action_after_post_readmore' );
			}
		}
		$edifice_content = ob_get_contents();
		ob_end_clean();
		edifice_show_layout($edifice_content, '<div class="post_content entry-content">', '</div><!-- .entry-content -->');
	}
	?>

</article></div><?php
// Need opening PHP-tag above, because <div> is a inline-block element (used as column)!
