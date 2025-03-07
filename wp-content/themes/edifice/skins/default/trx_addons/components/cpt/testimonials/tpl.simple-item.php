<?php
/**
 * The style "simple" of the Testimonials
 *
 * @package ThemeREX Addons
 * @since v1.4.3
 */

$args = get_query_var('trx_addons_args_sc_testimonials');

$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
$title = get_the_title();
			
if ($args['slider']) {
	?><div class="slider-slide swiper-slide"><?php
} else if ($args['columns'] > 1) {
	?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, $args['columns'], !empty($args['columns_tablet']) ? $args['columns_tablet'] : '', !empty($args['columns_mobile']) ? $args['columns_mobile'] : '')); ?>"><?php
}
?>
<div data-post-id="<?php the_ID(); ?>" class="sc_testimonials_item hte-testimonial-item sc_item_container post_container">
	<div class="sc_testimonials_item_content">
		<div class="hte-testimonial-author">
			<span class="sc_testimonials_item_author_title"><?php the_title(); ?> -</span>
			<?php if ( ! empty( $meta['subtitle'] ) ) {
				?><span class="sc_testimonials_item_author_subtitle"> <?php echo esc_html($meta['subtitle']); ?></span>
			<?php } ?>
		</div>

		<?php if ( has_excerpt() ) {
			the_excerpt();
		} else {
			the_content();
		} ?>
	</div>
	<div class="hte-testimonial-author-img">
		
		<?php if ( has_post_thumbnail() ) { ?>
			<div class="hte_testimonials_item_author_avatar"><?php the_post_thumbnail( apply_filters('full', trx_addons_get_thumb_size('full'), 'testimonials-default'), array('alt' => get_the_title()) ); ?></div>
		<?php } else if( !empty($args['use_initials']) && trx_addons_is_on($args['use_initials']) && ($title_initials = trx_addons_get_initials($title)) ) { ?>
			<div class="hte_testimonials_item_author_avatar sc_testimonials_avatar_with_initials"><span class="sc_testimonials_item_author_initials"><?php echo esc_html($title_initials)?></span></div>
		<?php } ?>
		<?php
		if ( (int) $args['rating'] == 1 && ! empty( $meta['rating'] ) ) {
			?><div class="sc_testimonials_item_author_rating"><?php trx_addons_testimonials_show_rating($meta['rating']); ?></div><?php
		}
		?>
		
	</div>
</div>
<?php
if ($args['slider'] || $args['columns'] > 1) {
	?></div><?php
}
