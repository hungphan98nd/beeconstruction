<?php
/**
 * The template 'Style 2' to displaying related posts
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
			'thumb_ratio'   => '300:223',
			'thumb_size'    => apply_filters( 'edifice_filter_related_thumb_size', edifice_get_thumb_size( (int) edifice_get_theme_option( 'related_posts' ) == 1 ? 'huge' : 'square' ) ),
		)
	);
	?>
	<div class="post_header entry-header">
		<?php
		if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {

			edifice_show_post_meta(
				array(
					'components' => 'categories',
					'class'      => 'post_meta_categories',
				)
			);

		}
		?>
		<h6 class="post_title entry-title"><a href="<?php echo esc_url( $edifice_link ); ?>"><?php
			if ( '' == get_the_title() ) {
				esc_html_e( '- No title -', 'edifice' );
			} else {
				the_title();
			}
		?></a></h6>
	</div>
</div>
