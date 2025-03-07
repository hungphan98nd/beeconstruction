<?php
/**
 * The style "qw-case" of the Portfolio
 *
 * @package ThemeREX Addons
 * @since v1.5
 */

$args = get_query_var('trx_addons_args_sc_portfolio');

$query_args = array(
// Attention! Parameter 'suppress_filters' is damage WPML-queries!
	'post_type' => TRX_ADDONS_CPT_PORTFOLIO_PT,
	'post_status' => 'publish',
	'ignore_sticky_posts' => true,
);
if ( empty( $args['ids'] ) || count( explode( ',', $args['ids'] ) ) > $args['count'] ) {
	$query_args['posts_per_page'] = $args['count'];
	if ( !trx_addons_is_off($args['pagination']) && $args['page'] > 1 ) {
		if ( empty( $args['offset'] ) ) {
			$query_args['paged'] = $args['page'];
		} else {
			$query_args['offset'] = $args['offset'] + $args['count'] * ( $args['page'] - 1 );
		}
	} else {
		$query_args['offset'] = $args['offset'];
	}
}

$query_args = trx_addons_query_add_sort_order($query_args, $args['orderby'], $args['order']);

$query_args = trx_addons_query_add_posts_and_cats($query_args, $args['ids'], TRX_ADDONS_CPT_PORTFOLIO_PT, $args['cat'], TRX_ADDONS_CPT_PORTFOLIO_TAXONOMY);

if ( !empty( $args['project_author'] ) ) {
	$query_args = trx_addons_query_add_meta( $query_args, 'trx_addons_project_author', $args['project_author'] );
}

// Exclude posts
if ( ! empty( $args['posts_exclude'] ) ) {
	$query_args['post__not_in'] = is_array( $args['posts_exclude'] )
									? $args['posts_exclude']
									: explode( ',', str_replace( array( ';', ' ' ), array( ',', '' ), $args['posts_exclude'] ) );
}

$query_args = apply_filters( 'trx_addons_filter_query_args', $query_args, 'sc_portfolio' );

$query = new WP_Query( $query_args );

if ($query->post_count > 0) {

	$args = apply_filters( 'trx_addons_filter_sc_prepare_atts_before_output', $args, $query_args, $query, 'portfolio.default' );

	//if ($args['count'] > $query->post_count) $args['count'] = $query->post_count;
	$posts_count = ($args['count'] > $query->post_count) ? $query->post_count : $args['count'];
	?><div <?php if (!empty($args['id'])) echo ' id="'.esc_attr($args['id']).'"'; ?>
		class="sc_portfolio sc_portfolio_<?php 
			echo esc_attr($args['type']);
			if (!empty($args['class'])) echo ' '.esc_attr($args['class']); 
			?>"<?php
		if (!empty($args['css'])) echo ' style="'.esc_attr($args['css']).'"';
		?>><?php

		trx_addons_sc_show_titles('sc_portfolio', $args);

		?><div class="sc_portfolio_content sc_item_content sc_item_posts_container"><?php

		while ( $query->have_posts() ) { $query->the_post();
			trx_addons_get_template_part(array(
											TRX_ADDONS_PLUGIN_CPT . 'portfolio/tpl.' . trx_addons_esc( trx_addons_sanitize_file_name( $args['type'] ) ) . '-item.php',
											TRX_ADDONS_PLUGIN_CPT . 'portfolio/tpl.default-item.php'
											),
											'trx_addons_args_sc_portfolio',
											$args
										);
		}

		wp_reset_postdata();
	
		?></div><?php

		trx_addons_sc_show_pagination('sc_portfolio', $args, $query);
		
		trx_addons_sc_show_links('sc_portfolio', $args);

	?></div><?php
}