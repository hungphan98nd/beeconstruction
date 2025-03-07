<div class="front_page_section front_page_section_features<?php
	$edifice_scheme = edifice_get_theme_option( 'front_page_features_scheme' );
	if ( ! empty( $edifice_scheme ) && ! edifice_is_inherit( $edifice_scheme ) ) {
		echo ' scheme_' . esc_attr( $edifice_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( edifice_get_theme_option( 'front_page_features_paddings' ) );
	if ( edifice_get_theme_option( 'front_page_features_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$edifice_css      = '';
		$edifice_bg_image = edifice_get_theme_option( 'front_page_features_bg_image' );
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
	$edifice_anchor_icon = edifice_get_theme_option( 'front_page_features_anchor_icon' );
	$edifice_anchor_text = edifice_get_theme_option( 'front_page_features_anchor_text' );
if ( ( ! empty( $edifice_anchor_icon ) || ! empty( $edifice_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_features"'
									. ( ! empty( $edifice_anchor_icon ) ? ' icon="' . esc_attr( $edifice_anchor_icon ) . '"' : '' )
									. ( ! empty( $edifice_anchor_text ) ? ' title="' . esc_attr( $edifice_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_features_inner
	<?php
	if ( edifice_get_theme_option( 'front_page_features_fullheight' ) ) {
		echo ' edifice-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$edifice_css      = '';
			$edifice_bg_mask  = edifice_get_theme_option( 'front_page_features_bg_mask' );
			$edifice_bg_color_type = edifice_get_theme_option( 'front_page_features_bg_color_type' );
			if ( 'custom' == $edifice_bg_color_type ) {
				$edifice_bg_color = edifice_get_theme_option( 'front_page_features_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_features_content_wrap content_wrap">
			<?php
			// Caption
			$edifice_caption = edifice_get_theme_option( 'front_page_features_caption' );
			if ( ! empty( $edifice_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<h2 class="front_page_section_caption front_page_section_features_caption front_page_block_<?php echo ! empty( $edifice_caption ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( $edifice_caption, 'edifice_kses_content' ); ?></h2>
				<?php
			}

			// Description (text)
			$edifice_description = edifice_get_theme_option( 'front_page_features_description' );
			if ( ! empty( $edifice_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_description front_page_section_features_description front_page_block_<?php echo ! empty( $edifice_description ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( wpautop( $edifice_description ), 'edifice_kses_content' ); ?></div>
				<?php
			}

			// Content (widgets)
			?>
			<div class="front_page_section_output front_page_section_features_output">
				<?php
				if ( is_active_sidebar( 'front_page_features_widgets' ) ) {
					dynamic_sidebar( 'front_page_features_widgets' );
				} elseif ( current_user_can( 'edit_theme_options' ) ) {
					if ( ! edifice_exists_trx_addons() ) {
						edifice_customizer_need_trx_addons_message();
					} else {
						edifice_customizer_need_widgets_message( 'front_page_features_caption', 'ThemeREX Addons - Services' );
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
