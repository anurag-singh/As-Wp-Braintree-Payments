<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://anuragsingh.me
 * @since      1.0.0
 *
 * @package    As_Wp_Braintree_Payments
 * @subpackage As_Wp_Braintree_Payments/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    As_Wp_Braintree_Payments
 * @subpackage As_Wp_Braintree_Payments/public
 * @author     Anurag Singh <developer.anuragsingh@outlook.com>
 */
class As_Wp_Braintree_Payments_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in As_Wp_Braintree_Payments_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The As_Wp_Braintree_Payments_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/as-wp-braintree-payments-public.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name.'-bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in As_Wp_Braintree_Payments_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The As_Wp_Braintree_Payments_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/as-wp-braintree-payments-public.js', array( 'jquery' ), $this->version, false );


		wp_enqueue_script( $this->plugin_name.'-bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js', array( 'jquery' ), $this->version, true );

		wp_enqueue_script( $this->plugin_name.'-dropin', '//js.braintreegateway.com/web/dropin/1.9.4/js/dropin.min.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Display shortcode to display all packages.
	 *
	 * @since    1.0.0
	 */
	public function render_packages() {
		global $post;
		$packageArgs = array(
				'post_type' => 'subscription'
		);

		$packages = new WP_Query($packageArgs);
		?>

		<div id="packages-container">
			<!-- Display list of all packages -->
			<div class="row">
				<div class="col">

					<div class="card card-body">
						<?php
							echo '<ul class="list-inline" id="subscription-packages">';
								if($packages->have_posts()):
									while($packages->have_posts()) :
										$packages->the_post();
											$plan_id = get_post_meta($post -> ID, '_meta_box_plan_id', true);
											$plan_price = get_post_meta($post -> ID, '_meta_box_plan_price', true);
											$plan_duration = get_post_meta($post -> ID, '_meta_box_plan_duration', true);
											$plan_description = get_post_meta($post -> ID, '_meta_box_plan_description', true);
											// Add var in url
											$paymentPageUrl = add_query_arg('packagePostId', $post->ID, get_permalink( get_page_by_path( 'Payment' ) ));

											echo '<li data-id="'. $plan_id. '" data-price="'. $plan_price .'" data-duration="'. $plan_duration .'" class="list-inline-item text-center">';
													echo '<ul class="list-group" id="package-details">';
															echo '<li class="list-group-item active">';
																echo the_title();
															echo '</li>';
															echo '<li class="list-group-item">';
																echo $plan_price;
															echo '</li>';
															echo '<li class="list-group-item">';
																echo $plan_duration;
															echo '</li>';
															echo '<li class="list-group-item">';
																echo $plan_description;
															echo '</li>';
															echo '<li class="list-group-item">';
																echo '<a class="btn btn-warning" href="' .$paymentPageUrl. '">Buy</a>';
															echo '</li>';
													echo '</ul>';
											echo '</li>';
									endwhile;
								else :
								endif;
								wp_reset_postdata();
							echo '</ul>';
						?>
					</div>

				</div>
			</div>
				<!-- Display list of all packages -->
		</div>
		<?php
	}

	public function config_braintree() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'braintree/vendor/autoload.php';
		Braintree_Configuration::environment('sandbox');
		Braintree_Configuration::merchantId('bt8m5c9jry7ffzpw');
		Braintree_Configuration::publicKey('2qwtjnn5vfpnsvjc');
		Braintree_Configuration::privateKey('49cdea202b61604c4a341dc9d07d717b');
	}



	public function render_payment_form() {
		if(!isset($_GET['packagePostId'])) {
			echo "Unable to fetch package details.";
			return;
		}
		global $post;
		$post_id = $_GET['packagePostId'];
		$plan_id = get_post_meta($post_id, '_meta_box_plan_id', true);
		$plan_price = get_post_meta($post_id, '_meta_box_plan_price', true);

		if(empty($plan_price)) {
			echo "Price not set.";
			return;
		}

		$this->config_braintree();
		$clientToken = Braintree_ClientToken::generate();
		?>
			<div class="wrapper">
		        <div class="checkout container">

		            <header>
		                <h1>Hi, <br>Let's test a transaction</h1>
		                <p>
		                    Make a test payment with Braintree using PayPal or a card
		                </p>
		            </header>

		            <form method="post" id="payment-form" action="/success">
		                <section>
		                    <label for="amount">
		                        <span class="input-label">Amount</span>
		                        <div class="input-wrapper amount-wrapper">
		                            <input id="amount" name="amount" type="text" min="1" placeholder="Amount" value="<?php echo $plan_price; ?>">
		                        </div>
		                    </label>

		                    <div class="bt-drop-in-wrapper">
		                        <div id="bt-dropin"></div>
		                    </div>
		                </section>

		                <input id="nonce" name="payment_method_nonce" type="hidden" />
		                <button class="button" type="submit"><span>Test Transaction</span></button>
		            </form>
		        </div>
		    </div>
		<script>
	        var form = document.querySelector('#payment-form');
	        var client_token = "<?php echo $clientToken; ?>";
	        // console.log(client_token);
	        braintree.dropin.create({
	          authorization: client_token,
	          selector: '#bt-dropin',
	          paypal: {
	            flow: 'vault'
	          }
	        }, function (createErr, instance) {
	          if (createErr) {
	            console.log('Create Error', createErr);
	            return;
	          }
	          form.addEventListener('submit', function (event) {
	            event.preventDefault();
	            instance.requestPaymentMethod(function (err, payload) {
	              if (err) {
	                console.log('Request Payment Method Error', err);
	                return;
	              }
	              // Add the nonce to the form and submit
	              document.querySelector('#nonce').value = payload.nonce;
	              form.submit();
	            });
	          });
	        });
	    </script>
		<?php
	}

	public function payment_success() {
		$this->config_braintree();

		$amount = $_POST["amount"];
		$nonce = $_POST["payment_method_nonce"];
		$result = Braintree\Transaction::sale([
		    'amount' => $amount,
		    'paymentMethodNonce' => $nonce,
		    'options' => [
		        'submitForSettlement' => true
		    ]
		]);

		if ($result->success || !is_null($result->transaction)) {
		    $transaction = $result->transaction;
		    //header("Location: transaction.php?id=" . $transaction->id);

		    if (isset($transaction->id)) {
		        $transaction = Braintree\Transaction::find($transaction->id);
		        $transactionSuccessStatuses = array(
		            Braintree\Transaction::AUTHORIZED,
		            Braintree\Transaction::AUTHORIZING,
		            Braintree\Transaction::SETTLED,
		            Braintree\Transaction::SETTLING,
		            Braintree\Transaction::SETTLEMENT_CONFIRMED,
		            Braintree\Transaction::SETTLEMENT_PENDING,
		            Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT
		        );
		        if (in_array($transaction->status, $transactionSuccessStatuses)) {
		            $header = "Sweet Success!";
		            $icon = "success";
		            $message = "Your test transaction has been successfully processed. See the Braintree API response and try again.";

		            $this->insert_transaction_in_db($transaction);
		        } else {
		            $header = "Transaction Failed";
		            $icon = "fail";
		            $message = "Your test transaction has a status of " . $transaction->status . ". See the Braintree API response and try again.";
		        }
		        ?>
		        <!-- Display HTML on Success -->
		        	<div class="wrapper">
					    <div class="response container">
					        <div class="content">
					            <div class="icon">
					            <img src="/images/<?php echo($icon)?>.svg" alt="">
					            </div>

					            <h1><?php echo($header)?></h1>
					            <section>
					                <p><?php echo($message)?></p>
					            </section>
					            <section>
					                <a class="button primary back" href="/index.php">
					                    <span>Test Another Transaction</span>
					                </a>
					            </section>
					        </div>
					    </div>
					</div>

					<aside class="drawer dark">
					    <header>
					        <div class="content compact">
					            <a href="https://developers.braintreepayments.com" class="braintree" target="_blank">Braintree</a>
					            <h3>API Response</h3>
					        </div>
					    </header>

					    <article class="content compact">
					        <section>
					            <h5>Transaction</h5>
					            <table cellpadding="0" cellspacing="0">
					                <tbody>
					                    <tr>
					                        <td>id</td>
					                        <td><?php echo($transaction->id)?></td>
					                    </tr>
					                    <tr>
					                        <td>type</td>
					                        <td><?php echo($transaction->type)?></td>
					                    </tr>
					                    <tr>
					                        <td>amount</td>
					                        <td><?php echo($transaction->amount)?></td>
					                    </tr>
					                    <tr>
					                        <td>status</td>
					                        <td><?php echo($transaction->status)?></td>
					                    </tr>
					                    <tr>
					                        <td>created_at</td>
					                        <td><?php echo($transaction->createdAt->format('Y-m-d H:i:s'))?></td>
					                    </tr>
					                    <tr>
					                        <td>updated_at</td>
					                        <td><?php echo($transaction->updatedAt->format('Y-m-d H:i:s'))?></td>
					                    </tr>
					                </tbody>
					            </table>
					        </section>

					        <section>
					            <h5>Payment</h5>

					            <table cellpadding="0" cellspacing="0">
					                <tbody>
					                    <tr>
					                        <td>token</td>
					                        <td><?php echo($transaction->creditCardDetails->token)?></td>
					                    </tr>
					                    <tr>
					                        <td>bin</td>
					                        <td><?php echo($transaction->creditCardDetails->bin)?></td>
					                    </tr>
					                    <tr>
					                        <td>last_4</td>
					                        <td><?php echo($transaction->creditCardDetails->last4)?></td>
					                    </tr>
					                    <tr>
					                        <td>card_type</td>
					                        <td><?php echo($transaction->creditCardDetails->cardType)?></td>
					                    </tr>
					                    <tr>
					                        <td>expiration_date</td>
					                        <td><?php echo($transaction->creditCardDetails->expirationDate)?></td>
					                    </tr>
					                    <tr>
					                        <td>cardholder_name</td>
					                        <td><?php echo($transaction->creditCardDetails->cardholderName)?></td>
					                    </tr>
					                    <tr>
					                        <td>customer_location</td>
					                        <td><?php echo($transaction->creditCardDetails->customerLocation)?></td>
					                    </tr>
					                </tbody>
					            </table>
					        </section>

					        <?php if (!is_null($transaction->customerDetails->id)) : ?>
					        <section>
					            <h5>Customer Details</h5>
					            <table cellpadding="0" cellspacing="0">
					                <tbody>
					                    <tr>
					                        <td>id</td>
					                        <td><?php echo($transaction->customerDetails->id)?></td>
					                    </tr>
					                    <tr>
					                        <td>first_name</td>
					                        <td><?php echo($transaction->customerDetails->firstName)?></td>
					                    </tr>
					                    <tr>
					                        <td>last_name</td>
					                        <td><?php echo($transaction->customerDetails->lastName)?></td>
					                    </tr>
					                    <tr>
					                        <td>email</td>
					                        <td><?php echo($transaction->customerDetails->email)?></td>
					                    </tr>
					                    <tr>
					                        <td>company</td>
					                        <td><?php echo($transaction->customerDetails->company)?></td>
					                    </tr>
					                    <tr>
					                        <td>website</td>
					                        <td><?php echo($transaction->customerDetails->website)?></td>
					                    </tr>
					                    <tr>
					                        <td>phone</td>
					                        <td><?php echo($transaction->customerDetails->phone)?></td>
					                    </tr>
					                    <tr>
					                        <td>fax</td>
					                        <td><?php echo($transaction->customerDetails->fax)?></td>
					                    </tr>
					                </tbody>
					            </table>
					        </section>i
					        <?php endif; ?>

					        <section>
					            <p class="center small">Integrate with the Braintree SDK for a secure and seamless checkout</p>
					        </section>

					        <section>
					            <a class="button secondary full" href="https://developers.braintreepayments.com/guides/drop-in" target="_blank">
					                <span>See the Docs</span>
					            </a>
					        </section>
					    </article>
					</aside>
		        	<!-- Display HTML on Success -->
			<?php

		    }
		    else {
		    	echo "Sorry, ID not found";
		    }
		    echo '<pre>Success';
		    print_r($transaction);
		    echo '</pre>';
		} else {
		    $errorString = "";
		    foreach($result->errors->deepAll() as $error) {
		        $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
		    }
		    $_SESSION["errors"] = $errorString;
		    // header("Location: index.php");
		}


	}

	public function insert_transaction_in_db($transaction) {
		$args = array(
			'post_type' => 'payment',
			'comment_status'    =>   	'closed',
			'ping_status'       =>   	'closed',
			'post_author'       =>   	1,
			'post_title'        =>   	$transaction->type . ' - ' . $transaction->id,
			//'post_content'      =>  	$content,
			'post_status'       =>   	'publish',
		  /*other default parameters you want to set*/
		);

		$post_id = wp_insert_post($args);


		if(!is_wp_error($post_id)){
		  	//the post is valid

		  	// Add transaction details
			$transaction_id = add_post_meta( $post_id, $meta_key='_transaction_id', $meta_value = $transaction->id, true);
			$transaction_type = add_post_meta( $post_id, $meta_key='_transaction_type', $meta_value = $transaction->type, true);
			$transaction_amount = add_post_meta( $post_id, $meta_key='_transaction_amount', $meta_value = $transaction->amount, true);
			$transaction_currency_iso_code = add_post_meta( $post_id, $meta_key='_transaction_currency_iso_code', $meta_value = $transaction->currencyIsoCode, true);
			$transaction_status = add_post_meta( $post_id, $meta_key='_transaction_status', $meta_value = $transaction->status, true);
			$transaction_created_at = add_post_meta( $post_id, $meta_key='_transaction_created_at', $meta_value = $transaction->createdAt, true);
			$transaction_updated_at = add_post_meta( $post_id, $meta_key='_transaction_updated_at', $meta_value = $transaction->updatedAt, true);

			// Add card details
			$cardDetails_token = add_post_meta( $post_id, $meta_key='_cardDetails_token', $meta_value = $transaction->creditCardDetails->token, true);
			$cardDetails_bin = add_post_meta( $post_id, $meta_key='_cardDetails_bin', $meta_value = $transaction->creditCardDetails->bin, true);
			$cardDetails_last4 = add_post_meta( $post_id, $meta_key='_cardDetails_last4', $meta_value = $transaction->creditCardDetails->last4, true);
			$cardDetails_cardType = add_post_meta( $post_id, $meta_key='_cardDetails_cardType', $meta_value = $transaction->creditCardDetails->cardType, true);
			// $cardDetails_expirationDate = add_post_meta( $post_id, $meta_key='_cardDetails_expirationDate', $meta_value = $transaction->creditCardDetails->expirationDate, true);
			$cardDetails_cardholderName = add_post_meta( $post_id, $meta_key='_cardDetails_cardholderName', $meta_value = $transaction->creditCardDetails->cardholderName, true);
			$cardDetails_customerLocation = add_post_meta( $post_id, $meta_key='_cardDetails_customerLocation', $meta_value = $transaction->creditCardDetails->customerLocation, true);

		}else{
		  //there was an error in the post insertion,
		  echo $post_id->get_error_message();
		}
	}


}