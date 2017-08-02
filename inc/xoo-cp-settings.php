<?php
//Exit if accessed directly
if(!defined('ABSPATH')){
	return;
}
?>
<?php settings_errors(); ?>
<div class="xoo-cp-main-settings">
<form method="POST" action="options.php" class="xoo-cp-form">
	<?php settings_fields('xoo-cp-group'); ?>
	<?php do_settings_sections('xoo_cp'); ?>
	<?php submit_button(); ?>
</form>
<div class="rate-plugin">If you like the plugin , please show your support by rating <a href="https://wordpress.org/plugins/added-to-cart-popup-woocommerce/reviews" target="_blank">here.</a></div>
	<div class="plugin-support">
		Report Bugs/Issues <a href="http://xootix.com/" target="_blank">here.</a>,so that we can fix it :)
	</div>
</div>

<div class="xoo-cp-sidebar">
	<div class="xoo-chat">
		<span class="xoo-chhead">Need Help?</span>
		<span class="dashicons dashicons-format-chat xoo-chicon"></span>
		<span class="xoo-chtxt">Use <a href="http://xootix.com/" target="_blank">Live Chat</a></span>
	</div>
	<a href="http://xootix.com/plugins" class="xoo-more-plugins" target="_blank">Try other awesome plugins.</a>
</div>

