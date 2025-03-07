<?php
/**
 * The template 'Style 5' to displaying related posts
 *
 * @package EDIFICE
 * @since EDIFICE 1.0.54
 */

$edifice_link        = get_permalink();
$edifice_post_format = get_post_format();
$edifice_post_format = empty( $edifice_post_format ) ? 'standard' : str_replace( 'post-format-', '', $edifice_post_format );
?><div id="post-<?php the_ID(); ?>" <?php post_class( 'related_item post_format_' . esc_attr( $edifice_post_format ) ); ?> data-post-id="<?php the_ID(); ?>">
	<?php
	edifice_show_post_featured(
		array(
			'thumb_size'    => apply_filters( 'edifice_filter_related_thumb_size', edifice_get_thumb_size( (int) edifice_get_theme_option( 'related_posts' ) == 1 ? 'big' : 'med' ) ),
		)
	);
	?>
	<div class="post_header entry-header">
		<h6 class="post_title entry-title"><a href="<?php echo esc_url( $edifice_link ); ?>"><?php
			if ( '' == get_the_title() ) {
				esc_html_e( '- No title -', 'edifice' );
			} else {
				the_title();
			}
		?></a></h6>
		<?php
		if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
			?>
			<div class="post_meta">
				<a href="<?php echo esc_url( $edifice_link ); ?>" class="post_meta_item post_date"><?php echo wp_kses_data( edifice_get_date() ); ?></a>
			</div>
			<?php
		}
		?>
	</div>
</div>
