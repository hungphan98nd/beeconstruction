<?php
/**
 * Plugin support: WP GDPR Compliance
 *
 * @package ThemeREX Addons
 * @since v1.6.49
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}

if ( ! function_exists( 'trx_addons_exists_wp_gdpr_compliance' ) ) {
	/**
	 * Check if WP GDPR Compliance plugin is installed and activated
	 *
	 * @return bool  True if plugin is installed and activated
	 */
	function trx_addons_exists_wp_gdpr_compliance() {
//		Old way (before v.2.0)
//		return class_exists( 'WPGDPRC\WPGDPRC' );

//		New way (to avoid error in wp_gdpr_compliance autoloader)
//		Check constants:	before v.2.0						after v.2.0
		return defined( 'WP_GDPR_C_ROOT_FILE' ) || defined( 'WPGDPRC_ROOT_FILE' );
	}
}

if ( ! function_exists( 'trx_addons_wp_gdpr_compliance_create_empty_post_on_404' ) ) {
	add_action( 'wp', 'trx_addons_wp_gdpr_compliance_create_empty_post_on_404', 1 );
	/**
	 * Create empty post for a global variable $post to prevent error message on the page 404
	 * 
	 * @hooked wp, 1
	 */
	function trx_addons_wp_gdpr_compliance_create_empty_post_on_404() {
		if ( trx_addons_exists_wp_gdpr_compliance() && ! isset( $GLOBALS['post'] ) ) {	//&& ( is_404() || is_search() )
			$GLOBALS['post'] = new stdClass();
			$GLOBALS['post']->ID = 0;
			$GLOBALS['post']->post_type = 'unknown';
			$GLOBALS['post']->post_content = '';
		}
	}
}

if ( ! function_exists( 'trx_addons_wp_gdpr_compliance_add_new_user_double_opt_in' ) ) {
	add_filter( 'trx_addons_filter_add_new_user', 'trx_addons_wp_gdpr_compliance_add_new_user_double_opt_in' );
	/**
	 * Add hack for a new user registration via double opt-in method to prevent error message
	 * in wp-gdpr-compliance\Integrations\WPRegistration.php
	 * 
	 * @hooked trx_addons_filter_add_new_user
	 *
	 * @param array $user_data  User data
	 * 
	 * @return array  		User data
	 */
	function trx_addons_wp_gdpr_compliance_add_new_user_double_opt_in( $user_data ) {
		if ( trx_addons_exists_wp_gdpr_compliance() ) {
			if ( ! isset( $_POST['user_email'] ) && ! empty( $user_data['user_email'] ) ) {
				$_POST['user_email'] = $user_data['user_email'];
			}
		}
		return $user_data;
	}
}

if ( ! function_exists( 'trx_addons_wp_gdpr_compliance_remove_from_learnpress' ) ) {
	add_action( 'init', 'trx_addons_wp_gdpr_compliance_remove_from_learnpress', 10 );
	/**
	 * Disable injection the checkbox to pages of LearnPress
	 * 
	 * @hooked init, 10
	 * 
	 * @trigger trx_addons_filter_disable_wp_gdpr_on_learnpress_profile_page
	 */
	function trx_addons_wp_gdpr_compliance_remove_from_learnpress() {
		if (   apply_filters( 'trx_addons_filter_disable_wp_gdpr_on_learnpress_pages', true )
			&& trx_addons_exists_wp_gdpr_compliance()
			&& function_exists( 'trx_addons_exists_learnpress' )
			&& trx_addons_exists_learnpress()
			&& ( trx_addons_is_learnpress_page() || in_array( trx_addons_get_value_gp( 'lp-ajax' ), array( 'checkout' ) ) )
		) {
			trx_addons_remove_filter( 'register_form', 'addField', 'WPGDPRC\\Integrations\\WPRegistration' );
			trx_addons_remove_filter( 'registration_errors', 'validateField', 'WPGDPRC\\Integrations\\WPRegistration' );
		}
	}
}

if ( ! function_exists( 'trx_addons_wp_gdpr_compliance_modify_tgmpa_required_plugins' ) ) {
	add_filter( str_replace( '-', '_', get_template() ) . '_filter_tgmpa_required_plugins', 'trx_addons_wp_gdpr_compliance_modify_tgmpa_required_plugins', 100 );
	/**
	 * Modify WP GDPR Compliance plugin source path in the list
	 *
	 * @hooked THEME_SLUG_filter_tgmpa_required_plugins
	 * 
	 * @param array $list  List of required plugins
	 * 
	 * @return array  Modified list of required plugins
	 */
	function trx_addons_wp_gdpr_compliance_modify_tgmpa_required_plugins( $list = array() ) {
		if ( is_array( $list ) && count( $list ) > 0 ) {
			foreach ( $list as $k => $v ) {
				if ( is_array( $v ) && isset( $v['slug'] ) && $v['slug'] == 'wp-gdpr-compliance' && empty( $v['source'] ) ) {
					$fn = str_replace( '-', '_', get_template() ) . '_get_plugin_source_path';
					$path = function_exists( $fn ) ? call_user_func( $fn, 'plugins/wp-gdpr-compliance/wp-gdpr-compliance.zip' ) : '';
					$list[ $k ]['source'] = ! empty( $path ) ? $path : 'upload://wp-gdpr-compliance.zip';
					$list[ $k ]['version'] = '2.0.23';
					break;
				}
			}
		}
		return $list;
	}
}


// Demo data install
//----------------------------------------------------------------------------

// One-click import support
if ( is_admin() ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'wp-gdpr-compliance/wp-gdpr-compliance-demo-importer.php';
}

// OCDI support
if ( is_admin() && trx_addons_exists_wp_gdpr_compliance() && function_exists( 'trx_addons_exists_ocdi' ) && trx_addons_exists_ocdi() ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'wp-gdpr-compliance/wp-gdpr-compliance-demo-ocdi.php';
}
