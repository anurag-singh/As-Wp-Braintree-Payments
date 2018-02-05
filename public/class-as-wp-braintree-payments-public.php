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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/as-wp-braintree-payments-public.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'ajax_object', 
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ))
		);
		wp_enqueue_script( $this->plugin_name.'-bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js', array( 'jquery' ), $this->version, true );
		
		if(is_page( 'Payment' )) {
			wp_enqueue_script( $this->plugin_name.'-braintree', '//js.braintreegateway.com/js/braintree-2.32.1.min.js', array( 'jquery' ), $this->version, true );
		}
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
						<div class="collapse multi-collapse show" id="packagesListing" data-parent="#packages-container">
							<div class="card card-body">
								<?php
									// Panel Header
									// $packageDetailsHtml = '<div class="row">';
									// $packageDetailsHtml .= '<div class="col">';
									// $packageDetailsHtml .= '<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#packagesListing" aria-expanded="false" aria-controls="packagesListing">Back</button>';
									// $packageDetailsHtml .= '</div>';
									// $packageDetailsHtml .= '<div class="col">';
									// $packageDetailsHtml .= '</div>';
									// $packageDetailsHtml .= '</div>';
									// // Panel Header

									// echo $packageDetailsHtml;
									echo '<ul class="list-inline" id="subscription-packages">';
										if($packages->have_posts()):
											while($packages->have_posts()) :
												$packages->the_post();
													$plan_id = get_post_meta($post -> ID, '_meta_box_plan_id', true);
													$plan_price = get_post_meta($post -> ID, '_meta_box_plan_price', true);
													$plan_duration = get_post_meta($post -> ID, '_meta_box_plan_duration', true);
													$plan_description = get_post_meta($post -> ID, '_meta_box_plan_description', true);

													
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
																		echo '<button class="btn btn-warning" id="'. get_the_ID() .'" type="button" data-toggle="collapse" data-target="#packageDetails" aria-expanded="false" aria-controls="packageDetails">Buy</button>';
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
				</div>
				<!-- Display list of all packages -->

				<!-- Display details of selected packages -->
				<div class="row">
					<div class="col">
						<div class="collapse multi-collapse" id="packageDetails" data-parent="#packages-container">
							<div class="card card-body" id="packageDetails">
								Package Details
								<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#packagesListing" aria-expanded="false" aria-controls="packagesListing">Back</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Display details of selected packages -->

			</div>


		<?php
	}

	public function render_package_details() {
		if(!isset($_POST['id']))  {	
			$response = array (
							'status' 	=> 	0
							,'msg'		=>	'unable to get package id'
						);
			
		} else {
			$postId = $_POST['id'];

			$as = include('vendor/autoloader.php');
			
			$plan_id = get_post_meta($postId, '_meta_box_plan_id', true);
			$plan_price = get_post_meta($postId, '_meta_box_plan_price', true);
			$plan_duration = get_post_meta($postId, '_meta_box_plan_duration', true);
			$plan_description = get_post_meta($postId, '_meta_box_plan_description', true);

			$packakeObj = array (				
				'id' 		=> $plan_id
				,'name'		=> get_the_title( $postId )
				,'price'	=> $plan_price
				,'duration'	=> $plan_duration
			);

			// Panel Header
			$packageDetailsHtml = '<div class="row">';
			$packageDetailsHtml .= '<div class="col">';
			$packageDetailsHtml .= '<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#packagesListing" aria-expanded="false" aria-controls="packagesListing">Back</button>';
			$packageDetailsHtml .= '</div>';
			$packageDetailsHtml .= '<div class="col">';
			$packageDetailsHtml .= '</div>';
			$packageDetailsHtml .= '</div>';
			// Panel Header

			$packageDetailsHtml .= '<ul class="list-group text-center">';
			$packageDetailsHtml .= '<li class="list-group-item active">';
			$packageDetailsHtml .= 'Package Details';
			$packageDetailsHtml .= '</li>';
			$packageDetailsHtml .= '<li class="list-group-item">';
			$packageDetailsHtml .= get_the_title( $postId );
			$packageDetailsHtml .= '</li>';
			$packageDetailsHtml .= '<li class="list-group-item">';
			$packageDetailsHtml .= $plan_duration;
			$packageDetailsHtml .= '</li>';
			$packageDetailsHtml .= '<li class="list-group-item">';
			$packageDetailsHtml .= $plan_price;
			$packageDetailsHtml .= '</li>';
			$packageDetailsHtml .= '<li class="list-group-item">';
			$packageDetailsHtml .= $plan_description;
			$packageDetailsHtml .= '</li>';
			$packageDetailsHtml .= '</ul>';


			$userObj = wp_get_current_user();
			if($userObj->ID) {	// If Logged in, Fetch user's basic details
				$userFName = $userObj->user_firstname;
				$userLName = $userObj->user_lastname;
				$userEmail = $userObj->user_email;		
				
				$inputMode = 'disabled';
			} else {			// If not logged, set user basic details as blank
				$userFName = $userLName = $userEmail = '';
			}

			$paymentForm = '<div class="row mt-5">';
			$paymentForm .= '<div class="col">';

			$paymentForm .= '<div class="card text-center">';
			$paymentForm .= '<div class="card-header">';
			$paymentForm .= 'User Detatils';
			$paymentForm .= '</div>';
			$paymentForm .= '<div class="card-body">';

			// $paymentForm .= '<form id="payment-form" action="'.get_site_url().'/payment/" method="POST" class="payment">';
			$paymentForm .= '<div>';

			$paymentForm .= '<div class="row">';
			$paymentForm .= '<div class="col">';
			$paymentForm .= '<input type="text" class="form-control" id="payer_fname" name="payer_fname" placeholder="Enter First Name" value="'.$userFName.'" '.$inputMode.'>';
			$paymentForm .= '</div>';

			$paymentForm .= '<div class="col">';
			$paymentForm .= '<input type="text" class="form-control" id="payer_lname" name="payer_lname" placeholder="Enter Last Name" value="'.$userLName.'" '.$inputMode.'>';
			$paymentForm .= '</div>';
			$paymentForm .= '</div>';

			$paymentForm .= '<div class="row">';
			$paymentForm .= '<div class="col-9">';
			$paymentForm .= '<input type="email" class="form-control" id="payer_email" name="payer_email" placeholder="Enter email" value="'.$userEmail.'" '.$inputMode.'>';
			$paymentForm .= '</div>';

			$paymentForm .= '<div class="col-3">';
			$paymentForm .= '<div class="input-group">';
			$paymentForm .= '<div class="input-group-prepend">';
			$paymentForm .= '<span class="input-group-text" id="inputGroupPrepend">$</span>';
			$paymentForm .= '</div>';
			$paymentForm .= '<input type="text" class="form-control" id="payer_amount" name="payer_amount" placeholder="10.00" value="'.$plan_price.'" '.$inputMode.'>';
			$paymentForm .= '</div>';
			$paymentForm .= '</div>';
			$paymentForm .= '</div>';

			$paymentForm .= '<button type="button" id="submit_payment" class="btn btn-primary">Submit</button>';

			$paymentForm .= '</div>';
			$paymentForm .= '</div>';
			$paymentForm .= '</div>';

			$paymentForm .= '</div>';
			$paymentForm .= '</div>';
						

	

			$html = $packageDetailsHtml . $paymentForm;

			$response = array (
				'status' 	=> 	1
				,'msg'		=>	'Its works'
				,'html'	=> $html
			);
		}

		echo json_encode( $response );

		exit();
	}


}
