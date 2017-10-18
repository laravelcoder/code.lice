<?php
/**
 * Plugin Name: WooCommerce NMI Gateway 
 * Plugin URI: https://www.vaultem.com/
 * Description: WooCommerce Plugin powering payments through NMI Payment Gateway.
 * Version: 1.6.4
 * Author: Vaultem
 * Author URI: https://www.vaultem.com
 * Contributors: Vaultem * Requires at least: 3.5
 * Tested up to: 4.1
 *
 * Text Domain: woo-nmi-vaultem
 * Domain Path: /lang/
 *
 * @package NMI Gateway for WooCommerce
 * @author Vaultem
 */

add_action('plugins_loaded', 'init_woocommerce_nmipay', 0);
 
function init_woocommerce_nmipay() {
 
    if ( ! class_exists( 'WC_Payment_Gateway' ) ) { return; }
	
	load_plugin_textdomain('woo-nmi-vaultem', false, dirname( plugin_basename( __FILE__ ) ) . '/lang');
	
	class woocommerce_nmipay extends WC_Payment_Gateway {
		
		public function __construct() {
			global $woocommerce;
			
	        $this->id			= 'nmipay';
	        $this->method_title = __( 'Woocommerce NMI Gateway', 'woo-nmi-vaultem' );
	        $this->icon 		= plugins_url( 'nmipay.png', __FILE__ );
	        $this->has_fields 	= TRUE;
	        
	        $this->nmi 			= 'https://secure.networkmerchants.com/api/transact.php';
	        $this->alphacard 	= 'https://secure.alphacardgateway.com/api/transact.php';
	        $this->llms 		= 'https://secure.llmsgateway.com/api/transact.php';
	        $this->powerpay		= 'https://verifi.powerpay.biz/api/transact.php';
	        $this->sbg			= 'https://secure.skybankgateway.com/api/transact.php';
	        $this->mgg			= 'https://secure.merchantguygateway.com/api/transact.php';
	        $this->durango 		= 'https://secure.durango-direct.com/api/transact.php';
	        $this->tnbci 		= 'https://secure.tnbcigateway.com/api/transact.php';
	        $this->payscape 	= 'https://secure.payscapegateway.com/api/transact.php';
	        $this->paylinedata	= 'https://secure.paylinedatagateway.com/api/transact.php';
	        $this->inspire 		= 'https://secure.inspiregateway.net/api/transact.php';
	        $this->safesave		= 'https://secure.safesavegateway.com/api/transact.php';
	        $this->tranzcrypt	= 'https://secure.tranzcrypt.com/api/transact.php';
	        $this->planetauth	= 'https://secure.planetauthorizegateway.com/api/transact.php';
	        $this->cyogate 		= 'https://secure.cyogate.net/api/transact.php';
	        
			$this->supports           = array(
				'products',
				'refunds'
			);
	        
			// Load the form fields.
			$this->init_form_fields();
			
			// Load the settings.
			$this->init_settings();
			
			// Define user set variables
			$this->title 		= $this->settings['title'];
			$this->description 	= $this->settings['description'];
			$this->username 	= $this->settings['username'];
			$this->password		= $this->settings['password'];
			$this->provider		= $this->settings['provider'];
	        $this->woo_version 	= $this->get_woo_version();
			
			// Actions
			add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(  $this, 'process_admin_options' ) );
			
			if ( !$this->is_valid_for_use() ) $this->enabled = false;
	    }
	    
	     /**
	     * Check if this gateway is enabled and available in the user's country
	     */
	    function is_valid_for_use() {
	        if ( !in_array( get_option('woocommerce_currency'), array('AUD', 'BRL', 'CAD', 'MXN', 'NZD', 'HKD', 'SGD', 'USD', 'EUR', 'JPY', 'TRY', 'NOK', 'CZK', 'DKK', 'HUF', 'ILS', 'MYR', 'PHP', 'PLN', 'SEK', 'CHF', 'TWD', 'THB', 'GBP') ) ) return false;
			
	        return true;
	    }
	    
		/**
		 * Admin Panel Options
		 * - Options for bits like 'title' and availability on a country-by-country basis
		 *
		 * @since 1.0.0
		 */
		public function admin_options() {
			
	    	?>
	    	<h3><?php _e('NMI Payment Gateway', 'woo-nmi-vaultem'); ?></h3>
	    	<p><?php _e('NMI WooCommerce gateway allows your customers to checkout using a credit card without leaving your website.', 'woo-nmi-vaultem'); ?></p>
	    	<table class="form-table">
	    	<?php
	    		if ( $this->is_valid_for_use() ) :
	    			
	    			// Generate the HTML For the settings form.
	    			$this->generate_settings_html();
	    			
	    		else :
	    			
	    			?>
	            		<div class="inline error"><p><strong><?php _e( 'Gateway Disabled', 'woo-nmi-vaultem' ); ?></strong>: <?php _e( 'NMI does not support your store currency.', 'woo-nmi-vaultem' ); ?></p></div>
	        		<?php
	        		
	    		endif;
	    	?>
			</table><!--/.form-table-->
	    	<?php
	    } // End admin_options()
	    
		/**
	     * Initialise Gateway Settings Form Fields
	     */
	    function init_form_fields() {
	    	
	    	$this->form_fields = array(
				'enabled' => array(
								'title' => __( 'Enable/Disable', 'woo-nmi-vaultem' ), 
								'type' => 'checkbox', 
								'label' => __( 'Enable NMI Payment Gateway', 'woo-nmi-vaultem' ), 
								'default' => 'yes'
							), 
				'title' => array(
								'title' => __( 'Title', 'woo-nmi-vaultem' ), 
								'type' => 'text', 
								'description' => __( 'Description customers see during checkout.', 'woo-nmi-vaultem' ), 
								'default' => __( 'Checkout via Credit Card', 'woo-nmi-vaultem' )
							),
				'description' => array(
								'title' => __( 'Description', 'woo-nmi-vaultem' ), 
								'type' => 'textarea', 
								'description' => __( 'Description customers see during checkout.', 'woo-nmi-vaultem' ), 
								'default' => __("Pay by credit card.", 'woo-nmi-vaultem')
							),
				'username' => array(
								'title' => __( 'Username', 'woo-nmi-vaultem' ), 
								'type' => 'text', 
								'description' => __( 'Please enter your Username.', 'woo-nmi-vaultem' ), 
								'default' => ''
							),
				'password' => array(
								'title' => __( 'Password', 'woo-nmi-vaultem' ), 
								'type' => 'text', 
								'description' => __( 'Please enter your Password.', 'woo-nmi-vaultem' ), 
								'default' => ''
							),
				'provider' => array(
								'title' => __('Payment Gateway', 'woo-nmi-vaultem'),
			                    'type' => 'select',
			                    'options' => array( 
												'nmi'			=> 'Network Merchants Inc',
												'alphacard'		=> 'Alpha Card Services',
												'llms'			=> 'LL Merchant Solutions',
												'powerpay'		=> 'PowerPay',
												'sbg'			=> 'SkyBank',
												'mgg'			=> 'MerchantGuy',
												'durango'		=> 'Durango Merchant Services',
												'tnbci'			=> 'TransNational Bankcard Inc',
												'payscape'		=> 'Payscape',
												'paylinedata'	=> 'Payline Data',
												'inspire'		=> 'Inspire Commerce',
												'safesave'		=> 'SafeSave Payments',
												'tranzcrypt'	=> 'Tranzcrypt',
												'planetauth'	=> 'PlanetAuthorize',
												'cyogate'		=> 'CyoGate'
												),
								'description' => __( 'Please choose your Merchant Account Provider.', 'woo-nmi-vaultem' ),
								'default' => 'nmi'
							)
				);
	    		
	    } // End init_form_fields()
	    
	    /**
		 * There are no payment fields for nmi, but we want to show the description if set.
		 **/
	    	function payment_fields() {
	    	if ($this->description) echo wpautop(wptexturize($this->description)); ?>
			
			<p class="form-row" style="width:200px;">
			    <label>Card Number <span class="required">*</span></label>
			    <input id="nmi_creditcard" class="swipe" style="width:180px;" type="text" size="16" autocomplete="on" maxlength="16" name="nmi_creditcard" />
			</p>
			<div class="clear"></div>
			<p class="form-row form-row-first" style="width:200px;">
			    <label>Expiration Month <span class="required">*</span></label>
			    <select id="nmi_expdatemonth" name="nmi_expdatemonth">
			        <option value="01">01</option>
			        <option value="02">02</option>
			        <option value="03">03</option>
			        <option value="04">04</option>
			        <option value="05">05</option>
			        <option value="06">06</option>
			        <option value="07">07</option>
			        <option value="08">08</option>
			        <option value="09">09</option>
			        <option value="10">10</option>
			        <option value="11">11</option>
			        <option value="12">12</option>
			    </select>
			</p>
			<p class="form-row form-row-second" style="width:150px;">
			    <label>Expiration Year  <span class="required">*</span></label>
			    <select id="nmi_expdateyear" name="nmi_expdateyear">
			<?php
			    $today = (int)date('y', time());
				$today1 = (int)date('Y', time());
			    for($i = 0; $i < 15; $i++)
			    {
			?>
			        <option value="<?php echo $today; ?>"><?php echo $today1; ?></option>
			<?php
			        $today++;
					$today1++;
			    }
			?>
			    </select>
			</p>
			
			<div class="clear"></div>
			<?php
	    }
		
	    public function validate_fields(){
	        global $woocommerce;
			
	        if (!$this->isCreditCardNumber($_POST['nmi_creditcard'])){
				if( $this->woo_version >= 2.1 ){
					wc_add_notice( __('(Credit Card Number) is not valid.', 'woo-nmi-vaultem'), $notice_type = 'error' );
				}else if( $this->woo_version < 2.1 ){
	            	$woocommerce->add_error( __('(Credit Card Number) is not valid.', 'woo-nmi-vaultem') );
				}else{
	            	$woocommerce->add_error( __('(Credit Card Number) is not valid.', 'woo-nmi-vaultem') );
				}
			}
			
	        if (!$this->isCorrectExpireDate($_POST['nmi_expdatemonth'], $_POST['nmi_expdateyear'])){
				if( $this->woo_version >= 2.1 ){
					wc_add_notice( __('(Card Expire Date) is not valid.', 'woo-nmi-vaultem'), $notice_type = 'error' );
				}else if( $this->woo_version < 2.1 ){
			  		$woocommerce->add_error( __('(Card Expire Date) is not valid.', 'woo-nmi-vaultem') );
				}else{
			  		$woocommerce->add_error( __('(Card Expire Date) is not valid.', 'woo-nmi-vaultem') );
				}
			}
			
	        if (!$_POST['nmi_cvv']){
				if( $this->woo_version >= 2.1 ){
					wc_add_notice( __('(Card CVV) is not entered.', 'woo-nmi-vaultem'), $notice_type = '' );
				}else if( $this->woo_version < 2.1 ){
			  		$woocommerce->add_error( __('(Card CVV) is not entered.', 'woo-nmi-vaultem') );
				}else{
			  		$woocommerce->add_error( __('(Card CVV) is not entered.', 'woo-nmi-vaultem') );
				}
			}
			
	    }
		
		/**
		 * Process the payment and return the result
		 **/
		function process_payment( $order_id ) {
			global $woocommerce;
			
			$order = new WC_Order( $order_id );
			
			$provider = $this->provider;
			
			$nmi_adr = $this->$provider . '?';
			
			$nmi_args['type'] = 'sale';
			$nmi_args['ipaddress'] = $_SERVER['REMOTE_ADDR'];
			$nmi_args['username'] = $this->username;
			$nmi_args['password'] = $this->password;
			$nmi_args['currency'] = get_option('woocommerce_currency');
			$nmi_args['ccnumber'] = $_POST["nmi_creditcard"];
			$nmi_args['cvv'] = $_POST["nmi_cvv"];
			$nmi_args['ccexp'] = $_POST["nmi_expdatemonth"].'/'.$_POST["nmi_expdateyear"];
			
			$nmi_args['orderid'] = $order_id;
			
			$nmi_args['firstname'] = $order->billing_first_name;
			$nmi_args['lastname'] = $order->billing_last_name;
			$nmi_args['company'] = $order->billing_company;
			$nmi_args['address1'] = $order->billing_address_1;
			$nmi_args['address2'] = $order->billing_address_2;
			$nmi_args['city'] = $order->billing_city;
			$nmi_args['state'] = $order->billing_state;
			$nmi_args['zip'] = $order->billing_postcode;
			$nmi_args['country'] = $order->billing_country;
			$nmi_args['email'] = $order->billing_email;
			
			$nmi_args['shipping_firstname'] = $order->shipping_first_name;
			$nmi_args['shipping_lastname'] = $order->shipping_last_name;
			$nmi_args['shipping_company'] = $order->shipping_company;
			$nmi_args['shipping_address1'] = $order->shipping_address_1;
			$nmi_args['shipping_address2'] = $order->shipping_address_2;
			$nmi_args['shipping_city'] = $order->shipping_city;
			$nmi_args['shipping_state'] = $order->shipping_state;
			$nmi_args['shipping_zip'] = $order->shipping_postcode;
			$nmi_args['shipping_country'] = $order->shipping_country;
			
			$nmi_args['invoice'] = $order->order_key;
			
			$AmountInput = number_format($order->order_total, 2, '.', '');
			
			$nmi_args['amount'] = $AmountInput;
			
			if ( in_array( $order->billing_country, array( 'US','CA' ) ) ) {
				$order->billing_phone = str_replace( array( '( ', '-', ' ', ' )', '.' ), '', $order->billing_phone );
				$nmi_args['phone'] = $order->billing_phone;
			} else {
				$nmi_args['phone'] = $order->billing_phone;
			}
			
			$name_value_pairs = array();
			foreach ($nmi_args as $key => $value) {
				$name_value_pairs[] = $key . '=' . urlencode($value);
			}
			$gateway_values =  implode('&', $name_value_pairs);
			
			$response = wp_remote_post($nmi_adr.$gateway_values);
			
	        if (!is_wp_error($response) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
	        	
		        parse_str($response['body'], $response);
				
			    if($response['response']== '1'){
			    	
			    	// Payment completed
					$order->add_order_note( sprintf( __('The NMI Payment transaction is successful. The Transaction Id is %s.', 'woo-nmi-vaultem'), $response["transactionid"] ) );
					
			        $order->payment_complete( $response["transactionid"] );
					
					return array(
						'result' 	=> 'success',
						'redirect'	=>  $this->get_return_url($order)
						);
				}
				else
				{
					if( $this->woo_version >= 2.1 ){
						wc_add_notice( sprintf( __('Transaction Failed. %s', 'woo-nmi-vaultem'), $response['responsetext'] ), $notice_type = 'error' );
					}else if( $this->woo_version < 2.1 ){
				  		$woocommerce->add_error( sprintf( __('Transaction Failed. %s', 'woo-nmi-vaultem'), $response['responsetext'] ) );
					}else{
				  		$woocommerce->add_error( sprintf( __('Transaction Failed. %s', 'woo-nmi-vaultem'), $response['responsetext'] ) );
					}
				}
	        }
			else{
				
				if( $this->woo_version >= 2.1 ){
					wc_add_notice( sprintf( __('Gateway Error. Please Notify the Store Owner about this error. %s', 'woo-nmi-vaultem'), $statusMessage ), $notice_type = 'error' );
				}else if( $this->woo_version < 2.1 ){
					$woocommerce->add_error( sprintf( __('Gateway Error. Please Notify the Store Owner about this error. %s', 'woo-nmi-vaultem'), $statusMessage ) );
				}else{
					$woocommerce->add_error( sprintf( __('Gateway Error. Please Notify the Store Owner about this error. %s', 'woo-nmi-vaultem'), $statusMessage ) );
				}
				
			}
			
		}

		/**
		 * Process a refund if supported
		 * @param  int $order_id
		 * @param  float $amount
		 * @param  string $reason
		 * @return  bool|wp_error True or false based on success, or a WP_Error object
		 */
		public function process_refund( $order_id, $amount = null, $reason = '' ) {
			$order = wc_get_order( $order_id );

			if ( ! $order || ! $order->get_transaction_id() ) {
				return false;
			}
			
			$provider = $this->provider;
			
			$nmi_adr = $this->$provider . '?';
			
			if ( ! is_null( $amount ) ) {
				$nmi_args['type'] = 'refund';
				$nmi_args['username'] = $this->username;
				$nmi_args['password'] = $this->password;
				$nmi_args['transactionid'] = $order->get_transaction_id();
				$nmi_args['amount'] = number_format( $amount, 2, '.', '' );
			}
						
			$name_value_pairs = array();
			foreach ($nmi_args as $key => $value) {
				$name_value_pairs[] = $key . '=' . urlencode($value);
			}
			$gateway_values =  implode('&', $name_value_pairs);
			
			$response = wp_remote_post($nmi_adr.$gateway_values);
			
			if ( is_wp_error( $response ) ) {
				return $response;
			}

			if ( empty( $response['body'] ) ) {
				return new WP_Error( 'nmi-error', __( 'Empty NMI response.', 'woocommerce' ) );
			}
			
		    parse_str($response['body'], $response);
		    		    
			if($response['response']== '1'){
				$order->add_order_note( sprintf( __( 'Refund %s - Refund ID: %s', 'woocommerce' ), $response['responsetext'], $response['transactionid'] ) );
				return true;
			}else if($response['response']== '2'){
				$order->add_order_note( __( 'Transaction Declined', 'woocommerce' ) );
				return true;
			}else if($response['response']== '3'){
				$order->add_order_note( __( 'Error in transaction data or system error.', 'woocommerce' ) );
				return true;
			}

			return false;
		}
		
		private function isCreditCardNumber($toCheck){
	        if (!is_numeric($toCheck))
	            return false;
			
	        $number = preg_replace('/[^0-9]+/', '', $toCheck);
	        $strlen = strlen($number);
	        $sum    = 0;
			
	        if ($strlen < 13)
	            return false;
			
	        for ($i=0; $i < $strlen; $i++) {
	        	
	            $digit = substr($number, $strlen - $i - 1, 1);
	            if($i % 2 == 1)
	            {
	                $sub_total = $digit * 2;
	                if($sub_total > 9)
	                {
	                    $sub_total = 1 + ($sub_total - 10);
	                }
	            }
	            else
	            {
	                $sub_total = $digit;
	            }
	            $sum += $sub_total;
	        }
			
	        if ($sum > 0 AND $sum % 10 == 0)
	            return true;
			
	        return false;
	    }
		
		private function isCorrectExpireDate($month, $year){
	        $now       = time();
	        $result    = false;
	        $thisYear  = (int)date('y', $now);
	        $thisMonth = (int)date('m', $now);
			
	        if (is_numeric($year) && is_numeric($month)){
	        	
	            if($thisYear == (int)$year){
	            	
		            $result = (int)$month >= $thisMonth;
		        }			else if($thisYear < (int)$year){
					$result = true;
				}
	        }
			
	        return $result;
	    }
		
		function get_woo_version() {
		    
			// If get_plugins() isn't available, require it
			if ( ! function_exists( 'get_plugins' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			
		    // Create the plugins folder and file variables
			$plugin_folder = get_plugins( '/woocommerce' );
			$plugin_file = 'woocommerce.php';
			
			// If the plugin version number is set, return it 
			if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
				return $plugin_folder[$plugin_file]['Version'];
			} else {
				// Otherwise return null
				return NULL;
			}
		}
	}
	
	/**
	 * Add the gateway to WooCommerce
	 **/
	function add_nmipay_gateway( $methods ) {
		$methods[] = 'woocommerce_nmipay'; return $methods;
	}
	
	add_filter('woocommerce_payment_gateways', 'add_nmipay_gateway' );
	
}