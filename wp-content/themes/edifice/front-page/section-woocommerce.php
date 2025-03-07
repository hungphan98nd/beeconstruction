<?php
$edifice_woocommerce_sc = edifice_get_theme_option( 'front_page_woocommerce_products' );
if ( ! empty( $edifice_woocommerce_sc ) ) {
	?><div class="front_page_section front_page_section_woocommerce<?php
		$edifice_scheme = edifice_get_theme_option( 'front_page_woocommerce_scheme' );
		if ( ! empty( $edifice_scheme ) && ! edifice_is_inherit( $edifice_scheme ) ) {
			echo ' scheme_' . esc_attr( $edifice_scheme );
		}
		echo ' front_page_section_paddings_' . esc_attr( edifice_get_theme_option( 'front_page_woocommerce_paddings' ) );
		if ( edifice_get_theme_option( 'front_page_woocommerce_stack' ) ) {
			echo ' sc_stack_section_on';
		}
	?>"
			<?php
			$edifice_css      = '';
			$edifice_bg_image = edifice_get_theme_option( 'front_page_woocommerce_bg_image' );
			if ( ! empty( $edifice_bg_image ) ) {
				$edifice_css .= 'background-image: url(' . esc_url( edifice_get_attachment_url( $edifice_bg_image ) ) . ');';
			}
			if ( ! empty( $edifice_css ) ) {
				echo ' style="' . esc_attr( $edifice_css ) . '"';
			}
			?>
	>
	<?php
		// Add anchor
		$edifice_anchor_icon = edifice_get_theme_option( 'front_page_woocommerce_anchor_icon' );
		$edifice_anchor_text = edifice_get_theme_option( 'front_page_woocommerce_anchor_text' );
		if ( ( ! empty( $edifice_anchor_icon ) || ! empty( $edifice_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
			echo do_shortcode(
				'[trx_sc_anchor id="front_page_section_woocommerce"'
											. ( ! empty( $edifice_anchor_icon ) ? ' icon="' . esc_attr( $edifice_anchor_icon ) . '"' : '' )
											. ( ! empty( $edifice_anchor_text ) ? ' title="' . esc_attr( $edifice_anchor_text ) . '"' : '' )
											. ']'
			);
		}
	?>
		<div class="front_page_section_inner front_page_section_woocommerce_inner
			<?php
			if ( edifice_get_theme_option( 'front_page_woocommerce_fullheight' ) ) {
				echo ' edifice-full-height sc_layouts_flex sc_layouts_columns_middle';
			}
			?>
				"
				<?php
				$edifice_css      = '';
				$edifice_bg_mask  = edifice_get_theme_option( 'front_page_woocommerce_bg_mask' );
				$edifice_bg_color_type = edifice_get_theme_option( 'front_page_woocommerce_bg_color_type' );
				if ( 'custom' == $edifice_bg_color_type ) {
					$edifice_bg_color = edifice_get_theme_option( 'front_page_woocommerce_bg_color' );
				} elseif ( 'scheme_bg_color' == $edifice_bg_color_type ) {
					$edifice_bg_color = edifice_get_scheme_color( 'bg_color', $edifice_scheme );
				} else {
					$edifice_bg_color = '';
				}
				if ( ! empty( $edifice_bg_color ) && $edifice_bg_mask > 0 ) {
					$edifice_css .= 'background-color: ' . esc_attr(
						1 == $edifice_bg_mask ? $edifice_bg_color : edifice_hex2rgba( $edifice_bg_color, $edifice_bg_mask )
					) . ';';
				}
				if ( ! empty( $edifice_css ) ) {
					echo ' style="' . esc_attr( $edifice_css ) . '"';
				}
				?>
		>
			<div class="front_page_section_content_wrap front_page_section_woocommerce_content_wrap content_wrap woocommerce">
				<?php
				// Content wrap with title and description
				$edifice_caption     = edifice_get_theme_option( 'front_page_woocommerce_caption' );
				$edifice_description = edifice_get_theme_option( 'front_page_woocommerce_description' );
				if ( ! empty( $edifice_caption ) || ! empty( $edifice_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					// Caption
					if ( ! empty( $edifice_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<h2 class="front_page_section_caption front_page_section_woocommerce_caption front_page_block_<?php echo ! empty( $edifice_caption ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( $edifice_caption, 'edifice_kses_content' );
						?>
						</h2>
						<?php
					}

					// Description (text)
					if ( ! empty( $edifice_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<div class="front_page_section_description front_page_section_woocommerce_description front_page_block_<?php echo ! empty( $edifice_description ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( wpautop( $edifice_description ), 'edifice_kses_content' );
						?>
						</div>
						<?php
					}
				}

				// Content (widgets)
				?>
				<div class="front_page_section_output front_page_section_woocommerce_output list_products shop_mode_thumbs">
					<?php
					if ( 'products' == $edifice_woocommerce_sc ) {
						$edifice_woocommerce_sc_ids      = edifice_get_theme_option( 'front_page_woocommerce_products_per_page' );
						$edifice_woocommerce_sc_per_page = count( explode( ',', $edifice_woocommerce_sc_ids ) );
					} else {
						$edifice_woocommerce_sc_per_page = max( 1, (int) edifice_get_theme_option( 'front_page_woocommerce_products_per_page' ) );
					}
					$edifice_woocommerce_sc_columns = max( 1, min( $edifice_woocommerce_sc_per_page, (int) edifice_get_theme_option( 'front_page_woocommerce_products_columns' ) ) );
					echo do_shortcode(
						"[{$edifice_woocommerce_sc}"
										. ( 'products' == $edifice_woocommerce_sc
												? ' ids="' . esc_attr( $edifice_woocommerce_sc_ids ) . '"'
												: '' )
										. ( 'product_category' == $edifice_woocommerce_sc
												? ' category="' . esc_attr( edifice_get_theme_option( 'front_page_woocommerce_products_categories' ) ) . '"'
												: '' )
										. ( 'best_selling_products' != $edifice_woocommerce_sc
												? ' orderby="' . esc_attr( edifice_get_theme_option( 'front_page_woocommerce_products_orderby' ) ) . '"'
													. ' order="' . esc_attr( edifice_get_theme_option( 'front_page_woocommerce_products_order' ) ) . '"'
												: '' )
										. ' per_page="' . esc_attr( $edifice_woocommerce_sc_per_page ) . '"'
										. ' columns="' . esc_attr( $edifice_woocommerce_sc_columns ) . '"'
						. ']'
					);
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
