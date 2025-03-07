<?php
/**
 * The Portfolio template to display the content
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

$edifice_post_format = get_post_format();
$edifice_post_format = empty( $edifice_post_format ) ? 'standard' : str_replace( 'post-format-', '', $edifice_post_format );

?><div class="
<?php
if ( ! empty( $edifice_template_args['slider'] ) ) {
	echo ' slider-slide swiper-slide';
} else {
	echo ( edifice_is_blog_style_use_masonry( $edifice_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $edifice_columns ) : esc_attr( $edifice_columns_class ));
}
?>
"><article id="post-<?php the_ID(); ?>" 
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $edifice_post_format )
		. ' post_layout_portfolio'
		. ' post_layout_portfolio_' . esc_attr( $edifice_columns )
		. ( 'portfolio' != $edifice_blog_style[0] ? ' ' . esc_attr( $edifice_blog_style[0] )  . '_' . esc_attr( $edifice_columns ) : '' )
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

	$edifice_hover   = ! empty( $edifice_template_args['hover'] ) && ! edifice_is_inherit( $edifice_template_args['hover'] )
								? $edifice_template_args['hover']
								: edifice_get_theme_option( 'image_hover' );

	if ( 'dots' == $edifice_hover ) {
		$edifice_post_link = empty( $edifice_template_args['no_links'] )
								? ( ! empty( $edifice_template_args['link'] )
									? $edifice_template_args['link']
									: get_permalink()
									)
								: '';
		$edifice_target    = ! empty( $edifice_post_link ) && false === strpos( $edifice_post_link, home_url() )
								? ' target="_blank" rel="nofollow"'
								: '';
	}
	
	// Meta parts
	$edifice_components = ! empty( $edifice_template_args['meta_parts'] )
							? ( is_array( $edifice_template_args['meta_parts'] )
								? $edifice_template_args['meta_parts']
								: explode( ',', $edifice_template_args['meta_parts'] )
								)
							: edifice_array_get_keys_by_value( edifice_get_theme_option( 'meta_parts' ) );

	// Featured image
	edifice_show_post_featured( apply_filters( 'edifice_filter_args_featured',
		array(
			'hover'         => $edifice_hover,
			'no_links'      => ! empty( $edifice_template_args['no_links'] ),
			'thumb_size'    => ! empty( $edifice_template_args['thumb_size'] )
								? $edifice_template_args['thumb_size']
								: edifice_get_thumb_size(
									edifice_is_blog_style_use_masonry( $edifice_blog_style[0] )
										? (	strpos( edifice_get_theme_option( 'body_style' ), 'full' ) !== false || $edifice_columns < 3
											? 'masonry-big'
											: 'masonry'
											)
										: (	strpos( edifice_get_theme_option( 'body_style' ), 'full' ) !== false || $edifice_columns < 3
											? 'square'
											: 'square'
											)
								),
			'thumb_bg' => edifice_is_blog_style_use_masonry( $edifice_blog_style[0] ) ? false : true,
			'show_no_image' => true,
			'meta_parts'    => $edifice_components,
			'class'         => 'dots' == $edifice_hover ? 'hover_with_info' : '',
			'post_info'     => 'dots' == $edifice_hover
										? '<div class="post_info"><h5 class="post_title">'
											. ( ! empty( $edifice_post_link )
												? '<a href="' . esc_url( $edifice_post_link ) . '"' . ( ! empty( $target ) ? $target : '' ) . '>'
												: ''
												)
												. esc_html( get_the_title() ) 
											. ( ! empty( $edifice_post_link )
												? '</a>'
												: ''
												)
											. '</h5></div>'
										: '',
            'thumb_ratio'   => 'info' == $edifice_hover ?  '100:102' : '',
        ),
        'content-portfolio',
        $edifice_template_args
    ) );
	?>
</article></div><?php
// Need opening PHP-tag above, because <article> is a inline-block element (used as column)!