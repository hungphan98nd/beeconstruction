<div class="front_page_section front_page_section_contacts<?php
	$edifice_scheme = edifice_get_theme_option( 'front_page_contacts_scheme' );
	if ( ! empty( $edifice_scheme ) && ! edifice_is_inherit( $edifice_scheme ) ) {
		echo ' scheme_' . esc_attr( $edifice_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( edifice_get_theme_option( 'front_page_contacts_paddings' ) );
	if ( edifice_get_theme_option( 'front_page_contacts_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$edifice_css      = '';
		$edifice_bg_image = edifice_get_theme_option( 'front_page_contacts_bg_image' );
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
	$edifice_anchor_icon = edifice_get_theme_option( 'front_page_contacts_anchor_icon' );
	$edifice_anchor_text = edifice_get_theme_option( 'front_page_contacts_anchor_text' );
if ( ( ! empty( $edifice_anchor_icon ) || ! empty( $edifice_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_contacts"'
									. ( ! empty( $edifice_anchor_icon ) ? ' icon="' . esc_attr( $edifice_anchor_icon ) . '"' : '' )
									. ( ! empty( $edifice_anchor_text ) ? ' title="' . esc_attr( $edifice_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_contacts_inner
	<?php
	if ( edifice_get_theme_option( 'front_page_contacts_fullheight' ) ) {
		echo ' edifice-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$edifice_css      = '';
			$edifice_bg_mask  = edifice_get_theme_option( 'front_page_contacts_bg_mask' );
			$edifice_bg_color_type = edifice_get_theme_option( 'front_page_contacts_bg_color_type' );
			if ( 'custom' == $edifice_bg_color_type ) {
				$edifice_bg_color = edifice_get_theme_option( 'front_page_contacts_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_contacts_content_wrap content_wrap">
			<?php

			// Title and description
			$edifice_caption     = edifice_get_theme_option( 'front_page_contacts_caption' );
			$edifice_description = edifice_get_theme_option( 'front_page_contacts_description' );
			if ( ! empty( $edifice_caption ) || ! empty( $edifice_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				// Caption
				if ( ! empty( $edifice_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<h2 class="front_page_section_caption front_page_section_contacts_caption front_page_block_<?php echo ! empty( $edifice_caption ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( $edifice_caption, 'edifice_kses_content' );
					?>
					</h2>
					<?php
				}

				// Description
				if ( ! empty( $edifice_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<div class="front_page_section_description front_page_section_contacts_description front_page_block_<?php echo ! empty( $edifice_description ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( wpautop( $edifice_description ), 'edifice_kses_content' );
					?>
					</div>
					<?php
				}
			}

			// Content (text)
			$edifice_content = edifice_get_theme_option( 'front_page_contacts_content' );
			$edifice_layout  = edifice_get_theme_option( 'front_page_contacts_layout' );
			if ( 'columns' == $edifice_layout && ( ! empty( $edifice_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				<div class="front_page_section_columns front_page_section_contacts_columns columns_wrap">
					<div class="column-1_3">
				<?php
			}

			if ( ( ! empty( $edifice_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				<div class="front_page_section_content front_page_section_contacts_content front_page_block_<?php echo ! empty( $edifice_content ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( $edifice_content, 'edifice_kses_content' );
					?>
				</div>
				<?php
			}

			if ( 'columns' == $edifice_layout && ( ! empty( $edifice_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div><div class="column-2_3">
				<?php
			}

			// Shortcode output
			$edifice_sc = edifice_get_theme_option( 'front_page_contacts_shortcode' );
			if ( ! empty( $edifice_sc ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_output front_page_section_contacts_output front_page_block_<?php echo ! empty( $edifice_sc ) ? 'filled' : 'empty'; ?>">
					<?php
					edifice_show_layout( do_shortcode( $edifice_sc ) );
					?>
				</div>
				<?php
			}

			if ( 'columns' == $edifice_layout && ( ! empty( $edifice_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div></div>
				<?php
			}
			?>

		</div>
	</div>
</div>
