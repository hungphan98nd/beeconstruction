<?php
/**
 * Required plugins
 *
 * @package EDIFICE
 * @since EDIFICE 1.76.0
 */

// THEME-SUPPORTED PLUGINS
// If plugin not need - remove its settings from next array
//----------------------------------------------------------
$edifice_theme_required_plugins_groups = array(
	'core'          => esc_html__( 'Core', 'edifice' ),
	'page_builders' => esc_html__( 'Page Builders', 'edifice' ),
	'ecommerce'     => esc_html__( 'E-Commerce & Donations', 'edifice' ),
	'socials'       => esc_html__( 'Socials and Communities', 'edifice' ),
	'events'        => esc_html__( 'Events and Appointments', 'edifice' ),
	'content'       => esc_html__( 'Content', 'edifice' ),
	'other'         => esc_html__( 'Other', 'edifice' ),
);
$edifice_theme_required_plugins        = array(
	'trx_addons'                 => array(
		'title'       => esc_html__( 'ThemeREX Addons', 'edifice' ),
		'description' => esc_html__( "Will allow you to install recommended plugins, demo content, and improve the theme's functionality overall with multiple theme options", 'edifice' ),
		'required'    => true,
		'logo'        => 'trx_addons.png',
		'group'       => $edifice_theme_required_plugins_groups['core'],
	),
	'elementor'                  => array(
		'title'       => esc_html__( 'Elementor', 'edifice' ),
		'description' => esc_html__( "Is a beautiful PageBuilder, even the free version of which allows you to create great pages using a variety of modules.", 'edifice' ),
		'required'    => false,
		'logo'        => 'elementor.png',
		'group'       => $edifice_theme_required_plugins_groups['page_builders'],
	),
	'gutenberg'                  => array(
		'title'       => esc_html__( 'Gutenberg', 'edifice' ),
		'description' => esc_html__( "It's a posts editor coming in place of the classic TinyMCE. Can be installed and used in parallel with Elementor", 'edifice' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'gutenberg.png',
		'group'       => $edifice_theme_required_plugins_groups['page_builders'],
	),
	'js_composer'                => array(
		'title'       => esc_html__( 'WPBakery PageBuilder', 'edifice' ),
		'description' => esc_html__( "Popular PageBuilder which allows you to create excellent pages", 'edifice' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'js_composer.jpg',
		'group'       => $edifice_theme_required_plugins_groups['page_builders'],
	),
	'woocommerce'                => array(
		'title'       => esc_html__( 'WooCommerce', 'edifice' ),
		'description' => esc_html__( "Connect the store to your website and start selling now", 'edifice' ),
		'required'    => false,
		'install'     => false,
		'logo'        => 'woocommerce.png',
		'group'       => $edifice_theme_required_plugins_groups['ecommerce'],
	),
	'elegro-payment'             => array(
		'title'       => esc_html__( 'Elegro Crypto Payment', 'edifice' ),
		'description' => esc_html__( "Extends WooCommerce Payment Gateways with an elegro Crypto Payment", 'edifice' ),
		'required'    => false,
		'install'     => false,
		'logo'        => 'elegro-payment.png',
		'group'       => $edifice_theme_required_plugins_groups['ecommerce'],
	),
	'instagram-feed'             => array(
		'title'       => esc_html__( 'Instagram Feed', 'edifice' ),
		'description' => esc_html__( "Displays the latest photos from your profile on Instagram", 'edifice' ),
		'required'    => false,
		'logo'        => 'instagram-feed.png',
		'group'       => $edifice_theme_required_plugins_groups['socials'],
	),
	'mailchimp-for-wp'           => array(
		'title'       => esc_html__( 'MailChimp for WP', 'edifice' ),
		'description' => esc_html__( "Allows visitors to subscribe to newsletters", 'edifice' ),
		'required'    => false,
		'logo'        => 'mailchimp-for-wp.png',
		'group'       => $edifice_theme_required_plugins_groups['socials'],
	),
	'booked'                     => array(
		'title'       => esc_html__( 'Booked Appointments', 'edifice' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'booked.png',
		'group'       => $edifice_theme_required_plugins_groups['events'],
	),
	'quickcal'                     => array(
		'title'       => esc_html__( 'QuickCal', 'edifice' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'quickcal.png',
		'group'       => $edifice_theme_required_plugins_groups['events'],
	),
	'the-events-calendar'        => array(
		'title'       => esc_html__( 'The Events Calendar', 'edifice' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'the-events-calendar.png',
		'group'       => $edifice_theme_required_plugins_groups['events'],
	),
	'contact-form-7'             => array(
		'title'       => esc_html__( 'Contact Form 7', 'edifice' ),
		'description' => esc_html__( "CF7 allows you to create an unlimited number of contact forms", 'edifice' ),
		'required'    => false,
		'logo'        => 'contact-form-7.png',
		'group'       => $edifice_theme_required_plugins_groups['content'],
	),

	'latepoint'                  => array(
		'title'       => esc_html__( 'LatePoint', 'edifice' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => edifice_get_file_url( 'plugins/latepoint/latepoint.png' ),
		'group'       => $edifice_theme_required_plugins_groups['events'],
	),
	'advanced-popups'                  => array(
		'title'       => esc_html__( 'Advanced Popups', 'edifice' ),
		'description' => '',
		'required'    => false,
		'logo'        => edifice_get_file_url( 'plugins/advanced-popups/advanced-popups.jpg' ),
		'group'       => $edifice_theme_required_plugins_groups['content'],
	),
	'devvn-image-hotspot'                  => array(
		'title'       => esc_html__( 'Image Hotspot by DevVN', 'edifice' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => edifice_get_file_url( 'plugins/devvn-image-hotspot/devvn-image-hotspot.png' ),
		'group'       => $edifice_theme_required_plugins_groups['content'],
	),
	'ti-woocommerce-wishlist'                  => array(
		'title'       => esc_html__( 'TI WooCommerce Wishlist', 'edifice' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => edifice_get_file_url( 'plugins/ti-woocommerce-wishlist/ti-woocommerce-wishlist.png' ),
		'group'       => $edifice_theme_required_plugins_groups['ecommerce'],
	),
	'woo-smart-quick-view'                  => array(
		'title'       => esc_html__( 'WPC Smart Quick View for WooCommerce', 'edifice' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => edifice_get_file_url( 'plugins/woo-smart-quick-view/woo-smart-quick-view.png' ),
		'group'       => $edifice_theme_required_plugins_groups['ecommerce'],
	),
	'twenty20'                  => array(
		'title'       => esc_html__( 'Twenty20 Image Before-After', 'edifice' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => edifice_get_file_url( 'plugins/twenty20/twenty20.png' ),
		'group'       => $edifice_theme_required_plugins_groups['content'],
	),
	'essential-grid'             => array(
		'title'       => esc_html__( 'Essential Grid', 'edifice' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'essential-grid.png',
		'group'       => $edifice_theme_required_plugins_groups['content'],
	),
	'revslider'                  => array(
		'title'       => esc_html__( 'Revolution Slider', 'edifice' ),
		'description' => '',
		'required'    => false,
		'logo'        => 'revslider.png',
		'group'       => $edifice_theme_required_plugins_groups['content'],
	),
	'sitepress-multilingual-cms' => array(
		'title'       => esc_html__( 'WPML - Sitepress Multilingual CMS', 'edifice' ),
		'description' => esc_html__( "Allows you to make your website multilingual", 'edifice' ),
		'required'    => false,
		'install'     => false,      // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'sitepress-multilingual-cms.png',
		'group'       => $edifice_theme_required_plugins_groups['content'],
	),
	'wp-gdpr-compliance'         => array(
		'title'       => esc_html__( 'Cookie Information', 'edifice' ),
		'description' => esc_html__( "Allow visitors to decide for themselves what personal data they want to store on your site", 'edifice' ),
		'required'    => false,
		'install'     => false,
		'logo'        => 'wp-gdpr-compliance.png',
		'group'       => $edifice_theme_required_plugins_groups['other'],
	),
	'gdpr-framework'         => array(
		'title'       => esc_html__( 'The GDPR Framework', 'edifice' ),
		'description' => esc_html__( "Tools to help make your website GDPR-compliant. Fully documented, extendable and developer-friendly.", 'edifice' ),
		'required'    => false,
		'install'     => false,
		'logo'        => 'gdpr-framework.png',
		'group'       => $edifice_theme_required_plugins_groups['other'],
	),
	'trx_updater'                => array(
		'title'       => esc_html__( 'ThemeREX Updater', 'edifice' ),
		'description' => esc_html__( "Update theme and theme-specific plugins from developer's upgrade server.", 'edifice' ),
		'required'    => false,
		'logo'        => 'trx_updater.png',
		'group'       => $edifice_theme_required_plugins_groups['other'],
	),
);

if ( EDIFICE_THEME_FREE ) {
	unset( $edifice_theme_required_plugins['js_composer'] );
	unset( $edifice_theme_required_plugins['booked'] );
	unset( $edifice_theme_required_plugins['quickcal'] );
	unset( $edifice_theme_required_plugins['the-events-calendar'] );
	unset( $edifice_theme_required_plugins['calculated-fields-form'] );
	unset( $edifice_theme_required_plugins['essential-grid'] );
	unset( $edifice_theme_required_plugins['revslider'] );
	unset( $edifice_theme_required_plugins['sitepress-multilingual-cms'] );
	unset( $edifice_theme_required_plugins['trx_updater'] );
	unset( $edifice_theme_required_plugins['trx_popup'] );
}

// Add plugins list to the global storage
edifice_storage_set( 'required_plugins', $edifice_theme_required_plugins );
