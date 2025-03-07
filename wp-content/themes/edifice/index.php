<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: //codex.wordpress.org/Template_Hierarchy
 *
 * @package EDIFICE
 * @since EDIFICE 1.0
 */

$edifice_template = apply_filters( 'edifice_filter_get_template_part', edifice_blog_archive_get_template() );

if ( ! empty( $edifice_template ) && 'index' != $edifice_template ) {

	get_template_part( $edifice_template );

} else {

	edifice_storage_set( 'blog_archive', true );

	get_header();

	if ( have_posts() ) {

		// Query params
		$edifice_stickies   = is_home()
								|| ( in_array( edifice_get_theme_option( 'post_type' ), array( '', 'post' ) )
									&& (int) edifice_get_theme_option( 'parent_cat' ) == 0
									)
										? get_option( 'sticky_posts' )
										: false;
		$edifice_post_type  = edifice_get_theme_option( 'post_type' );
		$edifice_args       = array(
								'blog_style'     => edifice_get_theme_option( 'blog_style' ),
								'post_type'      => $edifice_post_type,
								'taxonomy'       => edifice_get_post_type_taxonomy( $edifice_post_type ),
								'parent_cat'     => edifice_get_theme_option( 'parent_cat' ),
								'posts_per_page' => edifice_get_theme_option( 'posts_per_page' ),
								'sticky'         => edifice_get_theme_option( 'sticky_style' ) == 'columns'
															&& is_array( $edifice_stickies )
															&& count( $edifice_stickies ) > 0
															&& get_query_var( 'paged' ) < 1
								);

		edifice_blog_archive_start();

		do_action( 'edifice_action_blog_archive_start' );

		if ( is_author() ) {
			do_action( 'edifice_action_before_page_author' );
			get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/author-page' ) );
			do_action( 'edifice_action_after_page_author' );
		}

		if ( edifice_get_theme_option( 'show_filters' ) ) {
			do_action( 'edifice_action_before_page_filters' );
			edifice_show_filters( $edifice_args );
			do_action( 'edifice_action_after_page_filters' );
		} else {
			do_action( 'edifice_action_before_page_posts' );
			edifice_show_posts( array_merge( $edifice_args, array( 'cat' => $edifice_args['parent_cat'] ) ) );
			do_action( 'edifice_action_after_page_posts' );
		}

		do_action( 'edifice_action_blog_archive_end' );

		edifice_blog_archive_end();

	} else {

		if ( is_search() ) {
			get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/content', 'none-search' ), 'none-search' );
		} else {
			get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/content', 'none-archive' ), 'none-archive' );
		}
	}

	get_footer();
}
