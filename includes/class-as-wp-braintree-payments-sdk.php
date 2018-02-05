<?php 
	class As_Wp_Braintree_Payments_Sdk {

		function __construct() {
			require_once plugin_dir_path( dirname( __FILE__ ) ). 'public/vendor/autoload.php';

			Braintree_Configuration::environment('sandbox');
			Braintree_Configuration::merchantId('bt8m5c9jry7ffzpw');
			Braintree_Configuration::publicKey('2qwtjnn5vfpnsvjc');
			Braintree_Configuration::privateKey('49cdea202b61604c4a341dc9d07d717b');


		}


		public function get_token(){
			echo json_encode(Braintree_ClientToken::generate());
		}
	}
?>