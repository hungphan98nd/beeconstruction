<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package EDIFICE
 * @since EDIFICE 1.0
 */

							do_action( 'edifice_action_page_content_end_text' );
							
							// Widgets area below the content
							edifice_create_widgets_area( 'widgets_below_content' );
						
							do_action( 'edifice_action_page_content_end' );
							?>
						</div>
						<?php
						
						do_action( 'edifice_action_after_page_content' );

						// Show main sidebar
						get_sidebar();

						do_action( 'edifice_action_content_wrap_end' );
						?>
					</div>
					<?php

					do_action( 'edifice_action_after_content_wrap' );

					// Widgets area below the page and related posts below the page
					$edifice_body_style = edifice_get_theme_option( 'body_style' );
					$edifice_widgets_name = edifice_get_theme_option( 'widgets_below_page' );
					$edifice_show_widgets = ! edifice_is_off( $edifice_widgets_name ) && is_active_sidebar( $edifice_widgets_name );
					$edifice_show_related = edifice_is_single() && edifice_get_theme_option( 'related_position' ) == 'below_page';
					if ( $edifice_show_widgets || $edifice_show_related ) {
						if ( 'fullscreen' != $edifice_body_style ) {
							?>
							<div class="content_wrap">
							<?php
						}
						// Show related posts before footer
						if ( $edifice_show_related ) {
							do_action( 'edifice_action_related_posts' );
						}

						// Widgets area below page content
						if ( $edifice_show_widgets ) {
							edifice_create_widgets_area( 'widgets_below_page' );
						}
						if ( 'fullscreen' != $edifice_body_style ) {
							?>
							</div>
							<?php
						}
					}
					do_action( 'edifice_action_page_content_wrap_end' );
					?>
			</div>
			<?php
			do_action( 'edifice_action_after_page_content_wrap' );

			// Don't display the footer elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ( ! edifice_is_singular( 'post' ) && ! edifice_is_singular( 'attachment' ) ) || ! in_array ( edifice_get_value_gp( 'action' ), array( 'full_post_loading', 'prev_post_loading' ) ) ) {
				
				// Skip link anchor to fast access to the footer from keyboard
				?>
				<a id="footer_skip_link_anchor" class="edifice_skip_link_anchor" href="#"></a>
				<?php

				do_action( 'edifice_action_before_footer' );

				// Footer
				$edifice_footer_type = edifice_get_theme_option( 'footer_type' );
				if ( 'custom' == $edifice_footer_type && ! edifice_is_layouts_available() ) {
					$edifice_footer_type = 'default';
				}
				get_template_part( apply_filters( 'edifice_filter_get_template_part', "templates/footer-" . sanitize_file_name( $edifice_footer_type ) ) );

				do_action( 'edifice_action_after_footer' );

			}
			?>

			<?php do_action( 'edifice_action_page_wrap_end' ); ?>

		</div>

		<?php do_action( 'edifice_action_after_page_wrap' ); ?>

	</div>

	<?php do_action( 'edifice_action_after_body' ); ?>

	<?php wp_footer(); ?>


	<script>
		document.addEventListener("DOMContentLoaded", function () {
		    // Bắt sự kiện click vào nút mở popup
		    document.querySelectorAll(".trx_addons_panel_link.panel_inited").forEach(function (btn) {
		        btn.addEventListener("click", function (event) {
		            event.preventDefault(); 

		            // Hiển thị popup bằng cách thêm class
		            document.querySelector(".sc_layouts_panel_hide_content")?.classList.add("inited", "sc_layouts_panel_opened");
		            document.getElementById("panel-bar-inverse")?.classList.add("inited", "animate_to_inited", "sc_layouts_panel_opened");
		            document.getElementById("panel-bar-inverse2")?.classList.add("inited", "animate_to_inited", "sc_layouts_panel_opened");
		        });
		    });

		    // Bắt sự kiện click vào nút đóng popup
		    document.querySelectorAll(".sc_layouts_panel_close.trx_addons_button_close").forEach(function (closeBtn) {
		        closeBtn.addEventListener("click", function () {
		            // Ẩn popup bằng cách xóa class
		            document.querySelector(".sc_layouts_panel_hide_content")?.classList.remove("inited", "sc_layouts_panel_opened");
		            document.getElementById("panel-bar-inverse")?.classList.remove("inited", "animate_to_inited", "sc_layouts_panel_opened");
		            document.getElementById("panel-bar-inverse2")?.classList.remove("inited", "animate_to_inited", "sc_layouts_panel_opened");
		        });
		    });
		});
	</script>



</body>
</html>