<?php

/**
 * The meta box functionality of the plugin.
 *
 * @link       http://anuragsingh.me
 * @since      1.0.0
 *
 * @package    As_Wp_Braintree_Payments
 * @subpackage As_Wp_Braintree_Payments/admin
 */

/**
 * The meta box functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    As_Wp_Braintree_Payments
 * @subpackage As_Wp_Braintree_Payments/admin
 * @author     Anurag Singh <developer.anuragsingh@outlook.com>
 */
class As_Wp_Braintree_Payments_Meta_Box {

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
	 * The Custom Post Types (CPTs) of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $cpt_names    The ID of this plugin.
	 */
	private $cpt_names;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $cpt_names ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->cpt_names = $cpt_names;


	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box($post_type) {
		// $cpt_names = $this->cpt_names;
		// $post_types = unserialize($cpt_names);

		$post_types = array('subscription');


		//limit meta box to certain post types
		if (in_array($post_type, $post_types)) {
			add_meta_box('subscription-details-meta',
			'Subscription Plan Details',
			array($this, 'meta_box_function'),
			$post_type,
			'normal',
			'high');
		}
	}

	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */

	public function meta_box_function($post) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field('meta_box_nonce_check', 'meta_box_nonce_check_value');

		// Use get_post_meta to retrieve an existing value from the database.
		$plan_id = get_post_meta($post -> ID, '_meta_box_plan_id', true);
		$plan_price = get_post_meta($post -> ID, '_meta_box_plan_price', true);
		$plan_duration = get_post_meta($post -> ID, '_meta_box_plan_duration', true);
		$plan_description = get_post_meta($post -> ID, '_meta_box_plan_description', true);

		// Display the form, using the current value.
		echo '<div style="margin: 10px;>';

		echo '<label for="plan_id">';
		echo '<strong><p>Id</p></strong>';
		echo '</label>';
		echo '<input type="text" id="plan_id" name="plan_id" value="'.  esc_attr($plan_id) .'">';

		echo '<label for="plan_price">';
		echo '<strong><p>Price (GBP)</p></strong>';
		echo '</label>';
		echo '<input type="text" id="plan_price" name="plan_price" value="'.  esc_attr($plan_price) .'">';

		echo '<label for="plan_duration">';
		echo '<strong><p>Duration (Month)</p></strong>';
		echo '</label>';
		echo '<input type="text" id="plan_duration" name="plan_duration" value="'.  esc_attr($plan_duration) .'">';


		echo '<label for="plan_description">';
		echo '<strong><p>Description</p></strong>';
		echo '</label>';
		echo '<textarea rows="3" cols="50" name="plan_description">';
		echo esc_attr($plan_description);
		echo '</textarea>';

		echo '</div>';
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save_meta_box($post_id) {

		/*
		 * We need to verify this came from the our screen and with
		 * proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if (!isset($_POST['meta_box_nonce_check_value']))
			return $post_id;

		$nonce = $_POST['meta_box_nonce_check_value'];

		// Verify that the nonce is valid.
		if (!wp_verify_nonce($nonce, 'meta_box_nonce_check'))
			return $post_id;

		// If this is an autosave, our form has not been submitted,
		//     so we don't want to do anything.
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;

		// Check the user's permissions.
		if ('page' == $_POST['post_type']) {

			if (!current_user_can('edit_page', $post_id))
				return $post_id;

		} else {

			if (!current_user_can('edit_post', $post_id))
				return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		// Sanitize the user input.
		$planID = sanitize_text_field($_POST['plan_id']);
		$planPrice = sanitize_text_field($_POST['plan_price']);
		$planDuration = sanitize_text_field($_POST['plan_duration']);
		$planDescription = sanitize_text_field($_POST['plan_description']);

		// Update the meta field.
		update_post_meta($post_id, '_meta_box_plan_id', $planID);
		update_post_meta($post_id, '_meta_box_plan_price', $planPrice);
		update_post_meta($post_id, '_meta_box_plan_duration', $planDuration);
		update_post_meta($post_id, '_meta_box_plan_description', $planDescription);
	}

	public function display_meta_box_content($content) {
		global $post;
		//retrieve the metadata values if they exist
		$planID = get_post_meta($post -> ID, '_meta_box_plan_id', true);
		$planPrice = get_post_meta($post -> ID, '_meta_box_plan_price', true);
		$planDuration = get_post_meta($post -> ID, '_meta_box_plan_duration', true);
		$planDescription = get_post_meta($post -> ID, '_meta_box_plan_description', true);


		// Add meta box content in wp_content
		if (!empty($planDescription)) {
			$plan_description = "<div style='background-color: #FFEBE8;border-color: #C00;padding: 2px;margin:2px;'>";
			$plan_description .= 'Description: ';
			$plan_description .= $planDescription;
			$plan_description .= "</div>";
			$content = $plan_description . $content;
		}

		if (!empty($planDuration)) {
			$duration = "<div style='background-color: #FFEBE8;border-color: #C00;padding: 2px;margin:2px;'>";
			$duration .= 'Duration: ';
			$duration .= $planDuration;
			$duration .= "</div>";
			$content = $duration . $content;
		}

		if (!empty($planPrice)) {
			$price = "<div style='background-color: #FFEBE8;border-color: #C00;padding: 2px;margin:2px;'>";
			$price .= 'Price: ';
			$price .= $planPrice;
			$price .= "</div>";
			$content = $price . $content;
		}


		if (!empty($planID)) {
			$price = "<div style='background-color: #FFEBE8;border-color: #C00;padding: 2px;margin:2px;'>";
			$price .= 'ID: ';
			$price .= $planID;
			$price .= "</div>";
			$content = $price . $content;
		}

		return $content;
	}

	/**
	 * Adds the meta box container to display transaction details.
	 */
	public function add_transaction_details_meta_box_for_cpt_payment($post_type) {
		$post_types = array('payment');


		//limit meta box to certain post types
		if (in_array($post_type, $post_types)) {
			add_meta_box('transaction-details-meta',
			'TRANSACTION DETAILS',
			array($this, 'transaction_meta_box_function'),
			$post_type,
			'normal',
			'high');
		}
	}

	/**
	 * Render Meta Box content for transaction details.
	 *
	 * @param WP_Post $post The post object.
	 */

	public function transaction_meta_box_function($post) {
		global $post;

		$transaction_id = get_post_meta( $post->ID, $meta_key='_transaction_id', true);
		$transaction_type = get_post_meta( $post->ID, $meta_key='_transaction_type', true);
		$transaction_amount = get_post_meta( $post->ID, $meta_key='_transaction_amount', true);
		$transaction_currency_iso_code = get_post_meta( $post->ID, $meta_key='_transaction_currency_iso_code', true);
		$transaction_status = get_post_meta( $post->ID, $meta_key='_transaction_status', true);

		$transaction_created_at = get_post_meta( $post->ID, $meta_key='_transaction_created_at', true);
		$transaction_updated_at = get_post_meta( $post->ID, $meta_key='_transaction_updated_at', true);
		?>

		<table class="form-table"><tbody>
			<tr>
				<th scope="row"><p>ID</p></th>
				<td><p><?php echo $transaction_id; ?></p></td>
			</tr>

			<tr>
				<th scope="row"><p>Amount</p></th>
				<td><p><?php echo $transaction_amount . ' ' . $transaction_currency_iso_code; ?></p></td>
			</tr>

			<tr>
				<th scope="row"><p>Type</p></th>
				<td><p><?php echo $transaction_type; ?></p></td>
			</tr>

			<tr>
				<th scope="row"><p>Status</p></th>
				<td><p><?php echo $transaction_status; ?></p></td>
			</tr>

			<tr>
				<th scope="row"><p>Created At / Timezone</p></th>
				<td><p><?php echo $transaction_created_at->date; ?> / <?php  echo $transaction_created_at->timezone; ?></p></td>
			</tr>

			<tr>
				<th scope="row"><p>Updated At / Timezone</p></th>
				<td><p><?php echo $transaction_updated_at->date; ?> / <?php  echo $transaction_updated_at->timezone; ?></p></td>
			</tr>

			<tr>
				<th scope="row"><p>View on Braintree</p></th>
				<td><a target="_blank" href="https://sandbox.braintreegateway.com/merchants/bt8m5c9jry7ffzpw/transactions/<?php echo $transaction_id; ?> ">View</a></td>
			</tr>


		</tbody></table>
		<?php
	}

	/**
	 * Adds the meta box container to display payment details.
	 */
	public function add_payment_details_meta_box_for_cpt_payment($post_type) {
		$post_types = array('payment');


		//limit meta box to certain post types
		if (in_array($post_type, $post_types)) {
			add_meta_box('payment-details-meta',
			'PAYMENT DETAILS',
			array($this, 'payment_meta_box_function'),
			$post_type,
			'normal',
			'high');
		}
	}

	/**
	 * Render Meta Box content for payment details.
	 *
	 * @param WP_Post $post The post object.
	 */

	public function payment_meta_box_function($post) {
		global $post;

		$cardDetails_token = get_post_meta( $post->ID, $meta_key='_cardDetails_token', true);
		$cardDetails_bin = get_post_meta( $post->ID, $meta_key='_cardDetails_bin', true);
		$cardDetails_last4 = get_post_meta( $post->ID, $meta_key='_cardDetails_last4', true);
		$cardDetails_cardType = get_post_meta( $post->ID, $meta_key='_cardDetails_cardType', true);
		// $cardDetails_expirationDate = get_post_meta( $post->ID, $meta_key='_cardDetails_expirationDate', true);
		$cardDetails_cardholderName = get_post_meta( $post->ID, $meta_key='_cardDetails_cardholderName', true);
		$cardDetails_customerLocation = get_post_meta( $post->ID, $meta_key='_cardDetails_customerLocation', true);

		the_meta();
		?>


		<table class="form-table"><tbody>
			<tr>
				<th scope="row"><p>Token</p></th>
				<td><p><?php echo $cardDetails_token; ?></p></td>
			</tr>

			<tr>
				<th scope="row"><p>Bin</p></th>
				<td><p><?php echo $cardDetails_bin; ?></p></td>
			</tr>

			<tr>
				<th scope="row"><p>Last 4 Digit</p></th>
				<td><p><?php echo $cardDetails_last4; ?></p></td>
			</tr>

			<tr>
				<th scope="row"><p>Card Type</p></th>
				<td><p><?php echo $cardDetails_cardType; ?></p></td>
			</tr>

			<tr>
				<th scope="row"><p>Cardholder Name</p></th>
				<td><p><?php echo $cardDetails_cardholderName; ?></p></td>
			</tr>

			<tr>
				<th scope="row"><p>Customer Location</p></th>
				<td><p><?php echo $cardDetails_customerLocation; ?></p></td>
			</tr>




		</tbody></table>
		<?php
	}

}
?>