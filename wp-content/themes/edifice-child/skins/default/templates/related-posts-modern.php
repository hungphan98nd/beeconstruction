<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package EDIFICE
 * @since EDIFICE 1.0
 */

$edifice_link        = get_permalink();
$edifice_post_format = get_post_format();
$edifice_post_format = empty( $edifice_post_format ) ? 'standard' : str_replace( 'post-format-', '', $edifice_post_format );
?><div id="post-<?php the_ID(); ?>" <?php post_class( 'related_item post_format_' . esc_attr( $edifice_post_format ) ); ?> data-post-id="<?php the_ID(); ?>">
	<?php
	edifice_show_post_featured(
		array(
			'thumb_size'    => apply_filters( 'edifice_filter_related_thumb_size', edifice_get_thumb_size( (int) edifice_get_theme_option( 'related_posts' ) == 1 ? 'huge' : 'big' ) ),
			'post_info'     => '<div class="post_header entry-header">'
									. '<div class="post_categories">' . wp_kses( edifice_get_post_categories( '' ), 'edifice_kses_content' ) . '</div>'
									. '<h6 class="post_title entry-title"><a href="' . esc_url( $edifice_link ) . '">'
										. wp_kses_data( '' == get_the_title() ? esc_html__( '- No title -', 'edifice' ) : get_the_title() )
									. '</a></h6>'
									. ( in_array( get_post_type(), array( 'post', 'attachment' ) )
											? '<div class="post_meta"><a href="' . esc_url( $edifice_link ) . '" class="post_meta_item post_date">' . wp_kses_data( edifice_get_date() ) . '</a></div>'
											: '' )
								. '</div>',
		)
	);
	?>
</div>
