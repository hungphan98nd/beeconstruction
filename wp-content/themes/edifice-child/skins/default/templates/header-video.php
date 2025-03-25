<?php
/**
 * The template to display the background video in the header
 *
 * @package EDIFICE
 * @since EDIFICE 1.0.14
 */
$edifice_header_video = edifice_get_header_video();
$edifice_embed_video  = '';
if ( ! empty( $edifice_header_video ) && ! edifice_is_from_uploads( $edifice_header_video ) ) {
	if ( edifice_is_youtube_url( $edifice_header_video ) && preg_match( '/[=\/]([^=\/]*)$/', $edifice_header_video, $matches ) && ! empty( $matches[1] ) ) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr( $matches[1] ); ?>"></div>
		<?php
	} else {
		?>
		<div id="background_video"><?php edifice_show_layout( edifice_get_embed_video( $edifice_header_video ) ); ?></div>
		<?php
	}
}
