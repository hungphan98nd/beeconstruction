<?php if (!defined('WPO_VERSION')) die('No direct access allowed'); ?>

<div id="wp-optimize-general-settings" class="wpo_section wpo_group">
	<form action="#" method="post" enctype="multipart/form-data" name="settings_form" id="settings_form">
		<div id="wpo_settings_warnings"></div>


		<?php WP_Optimize()->include_template('settings/settings-general.php'); ?>
		<?php WP_Optimize()->include_template('settings/settings-trackback-and-comments.php'); ?>
		<?php WP_Optimize()->include_template('settings/settings-logging.php'); ?>
		<?php WP_Optimize()->include_template('settings/settings-export-import.php'); ?>

		<?php do_action('wpo_after_general_settings'); ?>

		<div id="wp-optimize-settings-save-results"></div>

		<input type="hidden" name="action" value="save_redirect">
		
		<?php wp_nonce_field('wpo_optimization'); ?>

		<h3 class="wpo-first-child"><?php esc_html_e('Wipe settings', 'wp-optimize'); ?></h3>

		<div class="wpo-fieldgroup">
			<p>
				<small>
					<?php
						$message = __('This button will delete all of WP-Optimize\'s settings.', 'wp-optimize');
						$message .= ' ';
						$message .= __('You will then need to enter all your settings again.', 'wp-optimize');
						$message .= ' ';
						$message .= __('You can also do this before deactivating/deinstalling WP-Optimize if you wish.', 'wp-optimize');
						echo esc_html($message);
					?>
				</small>
				<br>
				<br>
				<input class="button wpo-wipe-settings" type="button" name="wp-optimize-wipe-settings" value="<?php esc_attr_e('Wipe settings', 'wp-optimize'); ?>" />

				<img class="wpo_spinner" src="<?php echo esc_url(admin_url('images/spinner-2x.gif')); // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage -- N/A ?>" alt="...">

				<span class="dashicons dashicons-yes display-none save-done"></span>

			</p>
		</div>

		<div>
			<input id="wp-optimize-save-main-settings" class="button button-primary wpo-save-settings" type="submit" name="wp-optimize-settings" value="<?php esc_attr_e('Save settings', 'wp-optimize'); ?>" />

			<img class="wpo_spinner wpo-saving-settings" src="<?php echo esc_url(admin_url('images/spinner-2x.gif')); // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage -- N/A ?>" alt="...">

			<span class="dashicons dashicons-yes display-none save-done"></span>
		</div>

	</form>
</div><!-- end #wp-optimize-general-settings -->
