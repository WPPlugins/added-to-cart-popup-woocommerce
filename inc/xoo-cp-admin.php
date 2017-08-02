<?php
/**
 ========================
      ADMIN SETTINGS
 ========================
 */

//Exit if accessed directly
if(!defined('ABSPATH')){
	return;
}

// Enqueue Scripts & Stylesheet
function xoo_cp_admin_enqueue($hook){
	global $xoo_cp_version;
	if('toplevel_page_xoo_cp' != $hook){
		return;
	}
	wp_enqueue_style('xoo-cp-admin-css',plugins_url('/assets/css/xoo-cp-admin-css.css',__FILE__),null,$xoo_cp_version);
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('xoo-cp-admin-js',plugins_url('/assets/js/xoo-cp-admin-js.js',__FILE__),array('jquery','wp-color-picker'),$xoo_cp_version,true);
}
add_action('admin_enqueue_scripts','xoo_cp_admin_enqueue');

//Settings page
function xoo_cp_menu_settings(){
	add_menu_page( 'Added to cart popup', 'Added to cart popup', 'manage_options', 'xoo_cp', 'xoo_cp_settings_cb', 'dashicons-cart', 61 );
	add_action('admin_init','xoo_cp_settings');
}
add_action('admin_menu','xoo_cp_menu_settings');

//Settings callback function
function xoo_cp_settings_cb(){
	include plugin_dir_path(__FILE__).'xoo-cp-settings.php';
}

//Custom settings
function xoo_cp_settings(){

	//General options
	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-gl-atces'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-gl-atcem'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-gl-ibtne'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-gl-qtyen'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-gl-vcbtne'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-gl-chbtne'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-pw'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-imgw'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-btnc'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-btnbg'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-btns'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-btnbr'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-tbs'
 	);

 	register_setting(
		'xoo-cp-group',
	 	'xoo-cp-sy-tbc'
 	);

 	/** Settings Section **/

	add_settings_section(
		'xoo-cp-gl',
		'',
		'xoo_cp_gl_cb',
		'xoo_cp'
	);

	add_settings_section(
		'xoo-cp-sy',
		'',
		'xoo_cp_sy_cb',
		'xoo_cp'
	);

	add_settings_section(
		'xoo-cp-begad',
		'',
		'xoo_cp_begad_cb',
		'xoo_cp'
	);

	add_settings_section(
		'xoo-cp-endad',
		'',
		'xoo_cp_endad_cb',
		'xoo_cp'
	);

	add_settings_field(
		'xoo-cp-gl-atces',
		'Enable on Shop page',
		'xoo_cp_gl_atces_cb',
		'xoo_cp',
		'xoo-cp-gl'
	);

	add_settings_field(
		'xoo-cp-gl-atcem',
		'Enable on Mobile',
		'xoo_cp_gl_atcem_cb',
		'xoo_cp',
		'xoo-cp-gl'
	);

	add_settings_field(
		'xoo-cp-gl-ibtne',
		'+/- Qty Button',
		'xoo_cp_gl_ibtne_cb',
		'xoo_cp',
		'xoo-cp-gl'
	);

	add_settings_field(
		'xoo-cp-gl-qtyen',
		'Update Quantity',
		'xoo_cp_gl_qtyen_cb',
		'xoo_cp',
		'xoo-cp-gl'
	);

	add_settings_field(
		'xoo-cp-gl-vcbtne',
		'View Cart Button',
		'xoo_cp_gl_vcbtne_cb',
		'xoo_cp',
		'xoo-cp-gl'
	);

	add_settings_field(
		'xoo-cp-gl-chbtne',
		'Checkout Button',
		'xoo_cp_gl_chbtne_cb',
		'xoo_cp',
		'xoo-cp-gl'
	);

	add_settings_field(
		'xoo-cp-sy-pw',
		'PopUp Width',
		'xoo_cp_sy_pw_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);

	add_settings_field(
		'xoo-cp-sy-imgw',
		'Image Width',
		'xoo_cp_sy_imgw_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);

	add_settings_field(
		'xoo-cp-sy-btnbg',
		'Button Background Color',
		'xoo_cp_sy_btnbg_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);

	add_settings_field(
		'xoo-cp-sy-btnc',
		'Button Text Color',
		'xoo_cp_sy_btnc_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);

	add_settings_field(
		'xoo-cp-sy-btns',
		'Button Font Size',
		'xoo_cp_sy_btns_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);

	add_settings_field(
		'xoo-cp-sy-btnbr',
		'Button Border Radius',
		'xoo_cp_sy_btnbr_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);

	add_settings_field(
		'xoo-cp-sy-tbs',
		'Item Border Size',
		'xoo_cp_sy_tbs_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);

	add_settings_field(
		'xoo-cp-sy-tbc',
		'Item Border Color',
		'xoo_cp_sy_tbc_cb',
		'xoo_cp',
		'xoo-cp-sy'
	);

}

//Settings Section Callback
function xoo_cp_gl_cb(){
	?>
	<?php 	/** Settings Tab **/ ?>
	<div class="xoo-tabs">
		<ul>
			<li class="tab-1 active-tab">Main</li>
			<li class="tab-2">Advanced</li>
		</ul>
	</div>

<?php 	/** Settings Tab **/ ?>

	<?php
	$tab = '<div class="main-settings settings-tab settings-tab-active" tab-class ="tab-1">';  //Begin Main settings
	echo $tab;
	echo '<h2>General Options</h2>';
}

function xoo_cp_sy_cb(){
	echo '<h2>Style Options</h2>';
}

function xoo_cp_begad_cb(){
	$tab  = '</div>'; // End Main Settings
	$tab .= '<div class="advanced-settings settings-tab" tab-class ="tab-2">';
	echo $tab;
}

function xoo_cp_endad_cb(){
	$html  = '<div class="xoo-hero-btns">';
	$html  .= '<a class="buy-prem button button-primary button-hero" target="_blank" href="http://demo.xootix.com/cart-pop-up-for-woocommerce">LIVE DEMO</a>';
	$html  .= '<a class="live-demo button button-primary button-hero" href="http://xootix.com/plugins/cart-pop-up-for-woocommerce/">BUY PREMIUM - 9$</a>';
	$html .= '</div>';
	$html .= '<div class="prem-disabled">';
	$html .= '<img src="'.plugins_url('/images/1.png',__FILE__).'"/>';
	$html .= '<img src="'.plugins_url('/images/2.png',__FILE__).'"/>';
	$html .= '<img src="'.plugins_url('/images/3.png',__FILE__).'"/>';
	$html .= '<img src="'.plugins_url('/images/4.png',__FILE__).'"/>';
	$html .= '</div>';
	$html .= '</div>'; // End Advanced settings
	echo $html;
}

//General Options Callback
//Enable on shop page
$xoo_cp_gl_atces_value = sanitize_text_field(get_option('xoo-cp-gl-atces','true'));
function xoo_cp_gl_atces_cb(){
	global $xoo_cp_gl_atces_value;
	$html  = '<input type="checkbox" name="xoo-cp-gl-atces" id="xoo-cp-gl-atces" value="true"'.checked('true',$xoo_cp_gl_atces_value,false).'>';
	$html .= '<label for="xoo-cp-gl-atces">Enable add to cart popup on shop page.</label>';
	echo $html;
}

//Enable on Mobile Devices
$xoo_cp_gl_atcem_value = sanitize_text_field(get_option('xoo-cp-gl-atcem','true'));
function xoo_cp_gl_atcem_cb(){
	global $xoo_cp_gl_atcem_value;
	$html  = '<input type="checkbox" name="xoo-cp-gl-atcem" id="xoo-cp-gl-atcem" value="true"'.checked('true',$xoo_cp_gl_atcem_value,false).'>';
	$html .= '<label for="xoo-cp-gl-atcem">Enable on mobile devices.</label>';
	echo $html;
}

//Enable +/- button
$xoo_cp_gl_ibtne_value = sanitize_text_field(get_option('xoo-cp-gl-ibtne','true'));
function xoo_cp_gl_ibtne_cb(){
	global $xoo_cp_gl_ibtne_value;
	$html  = '<input type="checkbox" name="xoo-cp-gl-ibtne" id="xoo-cp-gl-ibtne" value="true"'.checked('true',$xoo_cp_gl_ibtne_value,false).'>';
	$html .= '<label for="xoo-cp-gl-ibtne"> Enable Increase/Decrease Quantity buttons.</label>';
	echo $html;
}

//Allow Quantity Update
$xoo_cp_gl_qtyen_value = sanitize_text_field(get_option('xoo-cp-gl-qtyen','true'));
function xoo_cp_gl_qtyen_cb(){
	global $xoo_cp_gl_qtyen_value;
	$html  = '<input type="checkbox" name="xoo-cp-gl-qtyen" id="xoo-cp-gl-qtyen" value="true"'.checked('true',$xoo_cp_gl_qtyen_value,false).'>';
	$html .= '<label for="xoo-cp-gl-qtyen">Allow users to update quantity from popup.</label>';
	echo $html;
}


//View Cart button
$xoo_cp_gl_vcbtne_value = sanitize_text_field(get_option('xoo-cp-gl-vcbtne','true'));
function xoo_cp_gl_vcbtne_cb(){
	global $xoo_cp_gl_vcbtne_value;
	$html  = '<input type="checkbox" name="xoo-cp-gl-vcbtne" id="xoo-cp-gl-vcbtne" value="true"'.checked('true',$xoo_cp_gl_vcbtne_value,false).'>';
	$html .= '<label for="xoo-cp-gl-vcbtne">Enable View Cart button.</label>';
	echo $html;
}

//Checkout button
$xoo_cp_gl_chbtne_value = sanitize_text_field(get_option('xoo-cp-gl-chbtne','true'));
function xoo_cp_gl_chbtne_cb(){
	global $xoo_cp_gl_chbtne_value;
	$html  = '<input type="checkbox" name="xoo-cp-gl-chbtne" id="xoo-cp-gl-chbtne" value="true"'.checked('true',$xoo_cp_gl_chbtne_value,false).'>';
	$html .= '<label for="xoo-cp-gl-chbtne">Enable Checkout button.</label>';
	echo $html;
}

//Style Options Callback

//Popup Width
$xoo_cp_sy_pw_value = sanitize_text_field(get_option('xoo-cp-sy-pw','650'));
function xoo_cp_sy_pw_cb(){
	global $xoo_cp_sy_pw_value;
	$html  = '<input type="number" name="xoo-cp-sy-pw" id="xoo-cp-sy-pw" value="'.$xoo_cp_sy_pw_value.'">';
	$html .= '<label for="xoo-cp-sy-pw">Value in px (Default: 650).</label>';
	echo $html;
}

//Image Width
$xoo_cp_sy_imgw_value = sanitize_text_field(get_option('xoo-cp-sy-imgw','20'));
function xoo_cp_sy_imgw_cb(){
	global $xoo_cp_sy_imgw_value;
	$html  = '<input type="number" name="xoo-cp-sy-imgw" id="xoo-cp-sy-imgw" value="'.$xoo_cp_sy_imgw_value.'">';
	$html .= '<label for="xoo-cp-sy-imgw">Value in percentage (Default: 20).</label>';
	echo $html;
}

//Button Background Color
$xoo_cp_sy_btnbg_value = sanitize_text_field(get_option('xoo-cp-sy-btnbg','#a46497'));
function xoo_cp_sy_btnbg_cb(){
	global $xoo_cp_sy_btnbg_value;
	$html  = '<input type="text" name="xoo-cp-sy-btnbg" id="xoo-cp-sy-btnbg" class="color-field" value="'.$xoo_cp_sy_btnbg_value.'"';
	echo $html;
}

//Button text Color
$xoo_cp_sy_btnc_value = sanitize_text_field(get_option('xoo-cp-sy-btnc','#ffffff'));
function xoo_cp_sy_btnc_cb(){
	global $xoo_cp_sy_btnc_value;
	$html  = '<input type="text" name="xoo-cp-sy-btnc" id="xoo-cp-sy-btnc" class="color-field" value="'.$xoo_cp_sy_btnc_value.'"';
	echo $html;
}

//Button Font Size
$xoo_cp_sy_btns_value = sanitize_text_field(get_option('xoo-cp-sy-btns','14'));
function xoo_cp_sy_btns_cb(){
	global $xoo_cp_sy_btns_value;
	$html  = '<input type="number" name="xoo-cp-sy-btns" id="xoo-cp-sy-btns" value="'.$xoo_cp_sy_btns_value.'">';
	$html .= '<label for="xoo-cp-sy-btns">Size in px (Default 14).</label>';
	echo $html;
}

//Button Border Radius
$xoo_cp_sy_btnbr_value = sanitize_text_field(get_option('xoo-cp-sy-btnbr','14'));
function xoo_cp_sy_btnbr_cb(){
	global $xoo_cp_sy_btnbr_value;
	$html  = '<input type="number" name="xoo-cp-sy-btnbr" id="xoo-cp-sy-btnbr" value="'.$xoo_cp_sy_btnbr_value.'">';
	$html .= '<label for="xoo-cp-sy-btnbr">Size in px (Default 5).</label>';
	echo $html;
}


//Table Border Size
$xoo_cp_sy_tbs_value = sanitize_text_field(get_option('xoo-cp-sy-tbs','0'));
function xoo_cp_sy_tbs_cb(){
	global $xoo_cp_sy_tbs_value;
	$html  = '<input type="number" name="xoo-cp-sy-tbs" id="xoo-cp-sy-tbs" value="'.$xoo_cp_sy_tbs_value.'">';
	$html .= '<label for="xoo-cp-sy-tbs">Size in px (Default 0).</label>';
	echo $html;
}


//Table Border Color
$xoo_cp_sy_tbc_value = sanitize_text_field(get_option('xoo-cp-sy-tbc','#ebe9eb'));
function xoo_cp_sy_tbc_cb(){
	global $xoo_cp_sy_tbc_value;
	$html  = '<input type="text" class="color-field" name="xoo-cp-sy-tbc" id="xoo-cp-sy-tbc" value="'.$xoo_cp_sy_tbc_value.'">';
	echo $html;
}



?>