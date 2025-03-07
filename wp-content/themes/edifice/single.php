<?php
/**
 * The template to display single post
 *
 * @package EDIFICE
 * @since EDIFICE 1.0
 */

// Full post loading
$full_post_loading          = edifice_get_value_gp( 'action' ) == 'full_post_loading';

// Prev post loading
$prev_post_loading          = edifice_get_value_gp( 'action' ) == 'prev_post_loading';
$prev_post_loading_type     = edifice_get_theme_option( 'posts_navigation_scroll_which_block' );

// Position of the related posts
$edifice_related_position   = edifice_get_theme_option( 'related_position' );

// Type of the prev/next post navigation
$edifice_posts_navigation   = edifice_get_theme_option( 'posts_navigation' );
$edifice_prev_post          = false;
$edifice_prev_post_same_cat = edifice_get_theme_option( 'posts_navigation_scroll_same_cat' );

// Rewrite style of the single post if current post loading via AJAX and featured image and title is not in the content
if ( ( $full_post_loading 
		|| 
		( $prev_post_loading && 'article' == $prev_post_loading_type )
	) 
	&& 
	! in_array( edifice_get_theme_option( 'single_style' ), array( 'style-6' ) )
) {
	edifice_storage_set_array( 'options_meta', 'single_style', 'style-6' );
}

do_action( 'edifice_action_prev_post_loading', $prev_post_loading, $prev_post_loading_type );

get_header();

while ( have_posts() ) {

	the_post();

	// Type of the prev/next post navigation
	if ( 'scroll' == $edifice_posts_navigation ) {
		$edifice_prev_post = get_previous_post( $edifice_prev_post_same_cat );  // Get post from same category
		if ( ! $edifice_prev_post && $edifice_prev_post_same_cat ) {
			$edifice_prev_post = get_previous_post( false );                    // Get post from any category
		}
		if ( ! $edifice_prev_post ) {
			$edifice_posts_navigation = 'links';
		}
	}

	// Override some theme options to display featured image, title and post meta in the dynamic loaded posts
	if ( $full_post_loading || ( $prev_post_loading && $edifice_prev_post ) ) {
		edifice_sc_layouts_showed( 'featured', false );
		edifice_sc_layouts_showed( 'title', false );
		edifice_sc_layouts_showed( 'postmeta', false );
	}

	// If related posts should be inside the content
	if ( strpos( $edifice_related_position, 'inside' ) === 0 ) {
		ob_start();
	}

	// Display post's content
	get_template_part( apply_filters( 'edifice_filter_get_template_part', 'templates/content', 'single-' . edifice_get_theme_option( 'single_style' ) ), 'single-' . edifice_get_theme_option( 'single_style' ) );

	// If related posts should be inside the content
	if ( strpos( $edifice_related_position, 'inside' ) === 0 ) {
		$edifice_content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action( 'edifice_action_related_posts' );
		$edifice_related_content = ob_get_contents();
		ob_end_clean();

		if ( ! empty( $edifice_related_content ) ) {
			$edifice_related_position_inside = max( 0, min( 9, edifice_get_theme_option( 'related_position_inside' ) ) );
			if ( 0 == $edifice_related_position_inside ) {
				$edifice_related_position_inside = mt_rand( 1, 9 );
			}

			$edifice_p_number         = 0;
			$edifice_related_inserted = false;
			$edifice_in_block         = false;
			$edifice_content_start    = strpos( $edifice_content, '<div class="post_content' );
			$edifice_content_end      = strrpos( $edifice_content, '</div>' );

			for ( $i = max( 0, $edifice_content_start ); $i < min( strlen( $edifice_content ) - 3, $edifice_content_end ); $i++ ) {
				if ( $edifice_content[ $i ] != '<' ) {
					continue;
				}
				if ( $edifice_in_block ) {
					if ( strtolower( substr( $edifice_content, $i + 1, 12 ) ) == '/blockquote>' ) {
						$edifice_in_block = false;
						$i += 12;
					}
					continue;
				} else if ( strtolower( substr( $edifice_content, $i + 1, 10 ) ) == 'blockquote' && in_array( $edifice_content[ $i + 11 ], array( '>', ' ' ) ) ) {
					$edifice_in_block = true;
					$i += 11;
					continue;
				} else if ( 'p' == $edifice_content[ $i + 1 ] && in_array( $edifice_content[ $i + 2 ], array( '>', ' ' ) ) ) {
					$edifice_p_number++;
					if ( $edifice_related_position_inside == $edifice_p_number ) {
						$edifice_related_inserted = true;
						$edifice_content = ( $i > 0 ? substr( $edifice_content, 0, $i ) : '' )
											. $edifice_related_content
											. substr( $edifice_content, $i );
					}
				}
			}
			if ( ! $edifice_related_inserted ) {
				if ( $edifice_content_end > 0 ) {
					$edifice_content = substr( $edifice_content, 0, $edifice_content_end ) . $edifice_related_content . substr( $edifice_content, $edifice_content_end );
				} else {
					$edifice_content .= $edifice_related_content;
				}
			}
		}

		edifice_show_layout( $edifice_content );
	}

	// Comments
	do_action( 'edifice_action_before_comments' );
	comments_template();
	do_action( 'edifice_action_after_comments' );

	// Related posts
	if ( 'below_content' == $edifice_related_position
		&& ( 'scroll' != $edifice_posts_navigation || edifice_get_theme_option( 'posts_navigation_scroll_hide_related' ) == 0 )
		&& ( ! $full_post_loading || edifice_get_theme_option( 'open_full_post_hide_related' ) == 0 )
	) {
		do_action( 'edifice_action_related_posts' );
	}

	// Post navigation: type 'scroll'
	if ( 'scroll' == $edifice_posts_navigation && ! $full_post_loading ) {
		?>
		<div class="nav-links-single-scroll"
			data-post-id="<?php echo esc_attr( get_the_ID( $edifice_prev_post ) ); ?>"
			data-post-link="<?php echo esc_attr( get_permalink( $edifice_prev_post ) ); ?>"
			data-post-title="<?php the_title_attribute( array( 'post' => $edifice_prev_post ) ); ?>"
			<?php do_action( 'edifice_action_nav_links_single_scroll_data', $edifice_prev_post ); ?>
		></div>
		<?php
	}
}

get_footer();
