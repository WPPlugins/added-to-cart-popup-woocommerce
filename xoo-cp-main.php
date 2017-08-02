<?php
/**
* Plugin Name: WooCommerce added to cart popup (Ajax) 
* Plugin URI: http://xootix.com
* Author: XootiX
* Version: 1.3
* Text Domain: added-to-cart-popup-woocommerce
* Domain Path: /languages
* Author URI: http://xootix.com
* Description: WooCommerce add to cart popup displays popup when item is added to cart without refreshing page.
**/

//Exit if accessed directly
if(!defined('ABSPATH')){
	return; 	
}

$xoo_cp_version = 1.3;

//Load plugin text domain
function xoo_cp_load_txtdomain() {
	$domain = 'added-to-cart-popup-woocommerce';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	load_textdomain( $domain, WP_LANG_DIR . '/'.$domain.'-' . $locale . '.mo' ); //wp-content languages
	load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' ); // Plugin Languages
}
add_action('plugins_loaded','xoo_cp_load_txtdomain');


//Admin Settings
include(plugin_dir_path(__FILE__).'/inc/xoo-cp-admin.php');

//Activation on mobile devices
if(!$xoo_cp_gl_atcem_value){
	if(wp_is_mobile()){
		return;
	}
}

function xoo_cp_enqueue_scripts(){
	global $xoo_cp_version,$xoo_cp_gl_atces_value;
	wp_enqueue_style('xoo-cp-style',plugins_url('/assets/css/xoo-cp-style.css',__FILE__),null,$xoo_cp_version);
	wp_enqueue_style('font-awesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_enqueue_script('xoo-cp-js',plugins_url('/assets/js/xoo-cp-js.min.js',__FILE__),array('jquery'),$xoo_cp_version,true);
	wp_dequeue_script('wc-add-to-cart');
	//i8n javascript
	$xoo_cp_js_text = array(
		'added' 	=> __('added successfully.','added-to-cart-popup-woocommerce'),
		'updated'	=> __('updated successfully.','added-to-cart-popup-woocommerce'),
		'removed'	=> __('removed from cart.','added-to-cart-popup-woocommerce'),
		'undo'		=> __('Undo?','added-to-cart-popup-woocommerce')
	);

	wp_localize_script('xoo-cp-js','xoo_cp_localize',array(
		'adminurl'     		=> admin_url().'admin-ajax.php',
		'enshop'			=> $xoo_cp_gl_atces_value,
		'xcp_text'			=> json_encode($xoo_cp_js_text)
		));
}

add_action('wp_enqueue_scripts','xoo_cp_enqueue_scripts',500);


//Get rounded total
function xoo_cp_round($number){
	$thous_sep = get_option( 'woocommerce_price_thousand_sep' );
	$dec_sep   = get_option( 'woocommerce_price_decimal_sep' );
	$decimals  = get_option( 'woocommerce_price_num_decimals' );
	return number_format( $number, $decimals, $dec_sep, $thous_sep );
}


//Get price with currency
function xoo_cp_with_currency($price){
	$price 	  = xoo_cp_round($price);
	$format   = get_option( 'woocommerce_currency_pos' );
	$csymbol  = get_woocommerce_currency_symbol();

	switch ($format) {
		case 'left':
			$currency = $csymbol.$price;
			break;

		case 'left_space':
			$currency = $csymbol.' '.$price;
			break;

		case 'right':
			$currency = $price.$csymbol;
			break;

		case 'right_space':
			$currency = $price.' '.$csymbol;
			break;

		default:
			$currency = $csymbol.$price;
			break;
	}
	return $currency;
}


//Popup HTML
function xoo_cp_popup(){
	global $woocommerce;
	$cart_url 		= $woocommerce->cart->get_cart_url();
	$checkout_url 	= $woocommerce->cart->get_checkout_url();
	?>
	<div class="xoo-cp-opac"></div>
	<div class="xoo-cp-modal">
		<div class="xoo-cp-container">
			<div class="xoo-cp-outer">
				<div class="xoo-cp-cont-opac"></div>
				<i class="xcp-icon xcp-icon-spinner2 xcp-outspin"></i>
			</div>
			<i class="xcp-icon-cross xcp-icon xoo-cp-close"></i>

			<div class="xoo-cp-atcn"></div>

			<div class="xoo-cp-content"></div>
				
			<div class="xoo-cp-btns">
				<a class="xoo-cp-btn-vc xcp-btn" href="<?php echo $cart_url; ?>"><?php _e('View Cart','added-to-cart-popup-woocommerce'); ?></a>
				<a class="xoo-cp-btn-ch xcp-btn" href="<?php echo $checkout_url; ?>"><?php _e('Checkout','added-to-cart-popup-woocommerce'); ?></a>
				<a class="xoo-cp-close xcp-btn"><?php _e('Continue Shopping','added-to-cart-popup-woocommerce'); ?></a>
			</div>
		</div>
	</div>
	<?php
}
add_action('wp_footer','xoo_cp_popup');

// Quantity Input
function xoo_cp_qty_input($input_value,$product){

	$max_value = apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product );
	$min_value = apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product );
	$step      = apply_filters( 'woocommerce_quantity_input_step', 1, $product );
	$pattern   = apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' );
		
	return '<input type="number" class="xoo-cp-qty" max="'.esc_attr( 0 < $max_value ? $max_value : '' ).'" min="'.esc_attr($min_value).'" step="'.esc_attr( $step ).'" value="'.$input_value.'" pattern="'.esc_attr( $pattern ).'" >';
}

// Ajax Add to cart 
function xoo_cp_cart_ajax(){

	global $woocommerce,$xoo_cp_gl_qtyen_value,$xoo_cp_gl_ibtne_value;
	$product_data   = json_decode(stripslashes($_POST['product_data']),true);
	$product_id 	= intval($product_data['product_id']);
	$variation_id 	= intval($product_data['variation_id']);
	$quantity 		= empty( $product_data['quantity'] ) ? 1 : wc_stock_amount( $product_data['quantity'] );
	$product 		= wc_get_product($product_id);
	$variations 	= array();

	if($variation_id){
		$attributes = $product->get_attributes();
		$variation_data = wc_get_product_variation_attributes($variation_id);
		$chosen_attributes = json_decode(stripslashes($product_data['attributes']),true);
		
		foreach($attributes as $attribute){

			if ( ! $attribute['is_variation'] ) {
					continue;
			}

			$taxonomy = 'attribute_' . sanitize_title( $attribute['name'] );
			

			if ( isset( $chosen_attributes[ $taxonomy ] ) ) {
				
				// Get value from post data
				if ( $attribute['is_taxonomy'] ) {
					// Don't use wc_clean as it destroys sanitized characters
					$value = sanitize_title( stripslashes( $chosen_attributes[ $taxonomy ] ) );

				} else {
					$value = wc_clean( stripslashes( $chosen_attributes[ $taxonomy ] ) );

				}

				// Get valid value from variation
				$valid_value = isset( $variation_data[ $taxonomy ] ) ? $variation_data[ $taxonomy ] : '';

				// Allow if valid or show error.
				if ( '' === $valid_value || $valid_value === $value ) {
					$variations[ $taxonomy ] = $value;
				} 
			}

		}
		$cart_success =  WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations );
	}

	elseif($variation_id === 0){
		$cart_success = $woocommerce->cart->add_to_cart($product_id,$quantity);
	}

	//Successfully added to cart.
	if($cart_success){
		$cart_item_key  = $cart_success;
		$cart_data		= $woocommerce->cart->get_cart();
		$cart_item 		= $cart_data[$cart_item_key];

		$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

		$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

		$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );


		
		$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

		

		if ( ! $product_permalink ) {
			$product_name =  apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
		} else {
			$product_name =  apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_title() ), $cart_item, $cart_item_key );
		}
								

		$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

		$product_subtotal = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );



		// Meta data
		$attributes  = wc_get_formatted_variation($_product);
		$attributes .=  WC()->cart->get_item_data( $cart_item );


		$html  = '<table class="xoo-cp-pdetails clearfix" data-cp="'.htmlentities(json_encode(array('key' => $cart_item_key, 'pname' => $product_name))).'">';
		$html .= '<tr>';
		$html .= '<td class="xoo-cp-remove"><i class="xcp-icon-cancel-circle xcp-icon"></i></td>';
		$html .= '<td class="xoo-cp-pimg">'.$thumbnail.'</td>';
		$html .= '<td class="xoo-cp-ptitle"><a href="'.get_permalink($product_id).'">'.$product_name.'</a>';

		if($attributes){
			$html .= '<div class="xoo-cp-variations">'.$attributes.'</div>';
		}

		$html .= '<td class="xoo-cp-pprice">'.$product_price.'</td>';


		$html .= '<td class="xoo-cp-pqty">';

		if ( $_product->is_sold_individually()) {
			$html .= sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
		}
		elseif(!$xoo_cp_gl_qtyen_value){
			$html .= '<span>'.$cart_item['quantity'].'</span>';
		}
		else{
			$html .= '<div class="xoo-cp-qtybox">';
			$html .= '<span class="xcp-minus xcp-chng">-</span>';
			$html .= xoo_cp_qty_input($cart_item['quantity'],$_product);
			$html .= '<span class="xcp-plus xcp-chng">+</span></div>';
		}

		$html .= '</td>';

		$html .= '</tr>';
		$html .= '</table>';
		$html .= '<div class="xoo-cp-ptotal"><span class="xcp-totxt">'.__('Total','added-to-cart-popup-woocommerce').': </span><span class="xcp-ptotal">'.$product_subtotal.'</span></div>';

		$ajax_fragm = xoo_cp_ajax_fragments();

		wp_send_json(array('pname' => $product_name , 'cp_html' => "$html" , 'ajax_fragm' => $ajax_fragm));
	}
	else{
		if(wc_notice_count('error') > 0){
    		echo wc_print_notices();
		}
  	}
	die();
}



add_action('wp_ajax_xoo_cp_cart_ajax','xoo_cp_cart_ajax');
add_action('wp_ajax_nopriv_xoo_cp_cart_ajax','xoo_cp_cart_ajax');

//Ajax change in cart
function xoo_cp_change_ajax(){
	global $woocommerce;
	$cart_item_key = sanitize_text_field($_POST['cart_key']);
	$new_qty = (int) $_POST['new_qty'];

	if($new_qty === 0){
		$removed = $woocommerce->cart->remove_cart_item($cart_item_key);
	}
	else{
		$updated = WC()->cart->set_quantity($cart_item_key,$new_qty,true);
		$cart_data = WC()->cart->get_cart();
		$cart_item = $cart_data[$cart_item_key];
		$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$ptotal = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );	
	}


	if($removed || $updated){
		$ajax_fragm     = xoo_cp_ajax_fragments();
		$data 			= array('ptotal' => $ptotal ,'ajax_fragm' => $ajax_fragm);
		wp_send_json($data);
	}
	else{
		if(wc_notice_count('error') > 0){
    		echo wc_print_notices();
		}
	}
	die();
}
add_action('wp_ajax_xoo_cp_change_ajax','xoo_cp_change_ajax');
add_action('wp_ajax_nopriv_xoo_cp_change_ajax','xoo_cp_change_ajax');

//Get Ajax refreshed fragments
function xoo_cp_ajax_fragments(){

  	// Get mini cart
    ob_start();

    woocommerce_mini_cart();

    $mini_cart = ob_get_clean();

    // Fragments and mini cart are returned
    $data = array(
        'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
                'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
            )
        ),
        'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
    );
    return $data;
}

//Options Styles
function xoo_cp_styles(){
	global $xoo_cp_sy_pw_value,$xoo_cp_sy_imgw_value,$xoo_cp_sy_btnbg_value,$xoo_cp_sy_btnc_value,$xoo_cp_sy_btns_value,$xoo_cp_sy_btnbr_value,$xoo_cp_sy_tbc_value,$xoo_cp_sy_tbs_value,$xoo_cp_gl_ibtne_value,$xoo_cp_gl_vcbtne_value,$xoo_cp_gl_chbtne_value,$xoo_cp_gl_qtyen_value;

	$style = '';

	if(!$xoo_cp_gl_vcbtne_value){
		$style .= 'a.xoo-cp-btn-vc{
			display: none;
		}';
	}

	if(!$xoo_cp_gl_ibtne_value){
		$style .= 'span.xcp-chng{
			display: none;
		}';
	}

	if(!$xoo_cp_gl_chbtne_value){
		$style .= 'a.xoo-cp-btn-ch{
			display: none;
		}';
	}

	echo "<style>
		.xoo-cp-container{
			max-width: {$xoo_cp_sy_pw_value}px;
		}
		.xcp-btn{
			background-color: {$xoo_cp_sy_btnbg_value};
			color: {$xoo_cp_sy_btnc_value};
			font-size: {$xoo_cp_sy_btns_value}px;
			border-radius: {$xoo_cp_sy_btnbr_value}px;
			border: 1px solid {$xoo_cp_sy_btnbg_value};
		}
		.xcp-btn:hover{
			color: {$xoo_cp_sy_btnc_value};
		}
		td.xoo-cp-pimg{
			width: {$xoo_cp_sy_imgw_value}%;
		}
		table.xoo-cp-pdetails , table.xoo-cp-pdetails tr{
			border: 0!important;
		}
		table.xoo-cp-pdetails td{
			border-style: solid;
			border-width: {$xoo_cp_sy_tbs_value}px;
			border-color: {$xoo_cp_sy_tbc_value};
		}
		{$style}
	</style>";
}	
add_action('wp_head','xoo_cp_styles');


