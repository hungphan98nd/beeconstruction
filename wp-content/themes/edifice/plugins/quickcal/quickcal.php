<?php
/* QuickCal support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'edifice_quickcal_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'edifice_quickcal_theme_setup9', 9 );
	function edifice_quickcal_theme_setup9() {
		if ( edifice_exists_quickcal() ) {
			add_action( 'wp_enqueue_scripts', 'edifice_quickcal_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_quickcal', 'edifice_quickcal_frontend_scripts', 10, 1 );
			add_action( 'wp_enqueue_scripts', 'edifice_quickcal_frontend_scripts_responsive', 2000 );
			add_action( 'trx_addons_action_load_scripts_front_quickcal', 'edifice_quickcal_frontend_scripts_responsive', 10, 1 );
			add_filter( 'edifice_filter_merge_styles', 'edifice_quickcal_merge_styles' );
			add_filter( 'edifice_filter_merge_styles_responsive', 'edifice_quickcal_merge_styles_responsive' );
		}
		if ( is_admin() ) {
			add_filter( 'edifice_filter_tgmpa_required_plugins', 'edifice_quickcal_tgmpa_required_plugins' );
			add_filter( 'edifice_filter_theme_plugins', 'edifice_quickcal_theme_plugins' );
		}
	}
}


// Filter to add in the required plugins list
if ( ! function_exists( 'edifice_quickcal_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('edifice_filter_tgmpa_required_plugins',	'edifice_quickcal_tgmpa_required_plugins');
	function edifice_quickcal_tgmpa_required_plugins( $list = array() ) {
		if ( edifice_storage_isset( 'required_plugins', 'quickcal' ) && edifice_storage_get_array( 'required_plugins', 'quickcal', 'install' ) !== false && edifice_is_theme_activated() ) {
			$path = edifice_get_plugin_source_path( 'plugins/quickcal/quickcal.zip' );
			if ( ! empty( $path ) || edifice_get_theme_setting( 'tgmpa_upload' ) ) {
				$list[] = array(
					'name'     => edifice_storage_get_array( 'required_plugins', 'quickcal', 'title' ),
					'slug'     => 'quickcal',
					'source'   => ! empty( $path ) ? $path : 'upload://quickcal.zip',
					'version'  => '1.0.6',
					'required' => false,
				);
			}
		}
		return $list;
	}
}


// Filter theme-supported plugins list
if ( ! function_exists( 'edifice_quickcal_theme_plugins' ) ) {
	//Handler of the add_filter( 'edifice_filter_theme_plugins', 'edifice_quickcal_theme_plugins' );
	function edifice_quickcal_theme_plugins( $list = array() ) {
		return edifice_add_group_and_logo_to_slave( $list, 'quickcal', 'quickcal-' );
	}
}


// Check if plugin installed and activated
if ( ! function_exists( 'edifice_exists_quickcal' ) ) {
	function edifice_exists_quickcal() {
		return class_exists( 'quickcal_plugin' );
	}
}

// Enqueue styles for frontend
if ( ! function_exists( 'edifice_quickcal_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'edifice_quickcal_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_quickcal', 'edifice_quickcal_frontend_scripts', 10, 1 );
	function edifice_quickcal_frontend_scripts( $force = false ) {
		edifice_enqueue_optimized( 'quickcal', $force, array(
			'css' => array(
				'edifice-quickcal' => array( 'src' => 'plugins/quickcal/quickcal.css' ),
			)
		) );
	}
}


// Enqueue responsive styles for frontend
if ( ! function_exists( 'edifice_quickcal_frontend_scripts_responsive' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'edifice_quickcal_frontend_scripts_responsive', 2000 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_quickcal', 'edifice_quickcal_frontend_scripts_responsive', 10, 1 );
	function edifice_quickcal_frontend_scripts_responsive( $force = false ) {
		edifice_enqueue_optimized_responsive( 'quickcal', $force, array(
			'css' => array(
				'edifice-quickcal-responsive' => array( 'src' => 'plugins/quickcal/quickcal-responsive.css', 'media' => 'all' ),
			)
		) );
	}
}


// Merge custom styles
if ( ! function_exists( 'edifice_quickcal_merge_styles' ) ) {
	//Handler of the add_filter('edifice_filter_merge_styles', 'edifice_quickcal_merge_styles');
	function edifice_quickcal_merge_styles( $list ) {
		$list[ 'plugins/quickcal/quickcal.css' ] = false;
		return $list;
	}
}


// Merge responsive styles
if ( ! function_exists( 'edifice_quickcal_merge_styles_responsive' ) ) {
	//Handler of the add_filter('edifice_filter_merge_styles_responsive', 'edifice_quickcal_merge_styles_responsive');
	function edifice_quickcal_merge_styles_responsive( $list ) {
		$list[ 'plugins/quickcal/quickcal-responsive.css' ] = false;
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( edifice_exists_quickcal() ) {
	$edifice_fdir = edifice_get_file_dir( 'plugins/quickcal/quickcal-style.php' );
	if ( ! empty( $edifice_fdir ) ) {
		require_once $edifice_fdir;
	}
}
