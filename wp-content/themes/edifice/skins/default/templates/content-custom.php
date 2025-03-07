<?php
/**
 * The custom template to display the content
 *
 * Used for index/archive/search.
 *
 * @package EDIFICE
 * @since EDIFICE 1.0.50
 */

$edifice_template_args = get_query_var( 'edifice_template_args' );
if ( is_array( $edifice_template_args ) ) {
	$edifice_columns    = empty( $edifice_template_args['columns'] ) ? 2 : max( 1, $edifice_template_args['columns'] );
	$edifice_blog_style = array( $edifice_template_args['type'], $edifice_columns );
} else {
	$edifice_template_args = array();
	$edifice_blog_style = explode( '_', edifice_get_theme_option( 'blog_style' ) );
	$edifice_columns    = empty( $edifice_blog_style[1] ) ? 2 : max( 1, $edifice_blog_style[1] );
}
$edifice_blog_id       = edifice_get_custom_blog_id( join( '_', $edifice_blog_style ) );
$edifice_blog_style[0] = str_replace( 'blog-custom-', '', $edifice_blog_style[0] );
$edifice_expanded      = ! edifice_sidebar_present() && edifice_get_theme_option( 'expand_content' ) == 'expand';
$edifice_components    = ! empty( $edifice_template_args['meta_parts'] )
							? ( is_array( $edifice_template_args['meta_parts'] )
								? join( ',', $edifice_template_args['meta_parts'] )
								: $edifice_template_args['meta_parts']
								)
							: edifice_array_get_keys_by_value( edifice_get_theme_option( 'meta_parts' ) );
$edifice_post_format   = get_post_format();
$edifice_post_format   = empty( $edifice_post_format ) ? 'standard' : str_replace( 'post-format-', '', $edifice_post_format );

$edifice_blog_meta     = edifice_get_custom_layout_meta( $edifice_blog_id );
$edifice_custom_style  = ! empty( $edifice_blog_meta['scripts_required'] ) ? $edifice_blog_meta['scripts_required'] : 'none';

if ( ! empty( $edifice_template_args['slider'] ) || $edifice_columns > 1 || ! edifice_is_off( $edifice_custom_style ) ) {
	?><div class="
		<?php
		if ( ! empty( $edifice_template_args['slider'] ) ) {
			echo 'slider-slide swiper-slide';
		} else {
			echo esc_attr( ( edifice_is_off( $edifice_custom_style ) ? 'column' : sprintf( '%1$s_item %1$s_item', $edifice_custom_style ) ) . "-1_{$edifice_columns}" );
		}
		?>
	">
	<?php
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
			'post_item post_item_container post_format_' . esc_attr( $edifice_post_format )
					. ' post_layout_custom post_layout_custom_' . esc_attr( $edifice_columns )
					. ' post_layout_' . esc_attr( $edifice_blog_style[0] )
					. ' post_layout_' . esc_attr( $edifice_blog_style[0] ) . '_' . esc_attr( $edifice_columns )
					. ( ! edifice_is_off( $edifice_custom_style )
						? ' post_layout_' . esc_attr( $edifice_custom_style )
							. ' post_layout_' . esc_attr( $edifice_custom_style ) . '_' . esc_attr( $edifice_columns )
						: ''
						)
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
	// Custom layout
	do_action( 'edifice_action_show_layout', $edifice_blog_id, get_the_ID() );
	?>
</article><?php
if ( ! empty( $edifice_template_args['slider'] ) || $edifice_columns > 1 || ! edifice_is_off( $edifice_custom_style ) ) {
	?></div><?php
	// Need opening PHP-tag above just after </div>, because <div> is a inline-block element (used as column)!
}
