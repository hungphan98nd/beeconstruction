<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'edifice_mailchimp_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'edifice_mailchimp_theme_setup9', 9 );
	function edifice_mailchimp_theme_setup9() {
		if ( edifice_exists_mailchimp() ) {
			add_action( 'wp_enqueue_scripts', 'edifice_mailchimp_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_mailchimp', 'edifice_mailchimp_frontend_scripts', 10, 1 );
			add_filter( 'edifice_filter_merge_styles', 'edifice_mailchimp_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'edifice_filter_tgmpa_required_plugins', 'edifice_mailchimp_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'edifice_mailchimp_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('edifice_filter_tgmpa_required_plugins',	'edifice_mailchimp_tgmpa_required_plugins');
	function edifice_mailchimp_tgmpa_required_plugins( $list = array() ) {
		if ( edifice_storage_isset( 'required_plugins', 'mailchimp-for-wp' ) && edifice_storage_get_array( 'required_plugins', 'mailchimp-for-wp', 'install' ) !== false ) {
			$list[] = array(
				'name'     => edifice_storage_get_array( 'required_plugins', 'mailchimp-for-wp', 'title' ),
				'slug'     => 'mailchimp-for-wp',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'edifice_exists_mailchimp' ) ) {
	function edifice_exists_mailchimp() {
		return function_exists( '__mc4wp_load_plugin' ) || defined( 'MC4WP_VERSION' );
	}
}



// Custom styles and scripts
//------------------------------------------------------------------------

// Enqueue styles for frontend
if ( ! function_exists( 'edifice_mailchimp_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'edifice_mailchimp_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_mailchimp', 'edifice_mailchimp_frontend_scripts', 10, 1 );
	function edifice_mailchimp_frontend_scripts( $force = false ) {
		edifice_enqueue_optimized( 'mailchimp', $force, array(
			'css' => array(
				'edifice-mailchimp-for-wp' => array( 'src' => 'plugins/mailchimp-for-wp/mailchimp-for-wp.css' ),
			)
		) );
	}
}

// Merge custom styles
if ( ! function_exists( 'edifice_mailchimp_merge_styles' ) ) {
	//Handler of the add_filter( 'edifice_filter_merge_styles', 'edifice_mailchimp_merge_styles');
	function edifice_mailchimp_merge_styles( $list ) {
		$list[ 'plugins/mailchimp-for-wp/mailchimp-for-wp.css' ] = false;
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( edifice_exists_mailchimp() ) {
	$edifice_fdir = edifice_get_file_dir( 'plugins/mailchimp-for-wp/mailchimp-for-wp-style.php' );
	if ( ! empty( $edifice_fdir ) ) {
		require_once $edifice_fdir;
	}
}

