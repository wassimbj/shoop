<?php
#############################################################
#===================== Stripe payment page =================
#############################################################
include 'core/inc/init.php';
if(isset($_SESSION['user_id'])){
	$user_cart = $getFromC->get_user_cart($_SESSION['user_id']);
	
	if(is_null($user_cart) || empty($user_cart)){
		header('location: shoping-cart.php');
		exit();
	}
}
require_once('vendor/autoload.php');
require 'config/paypal_stripe.php';

$user_id = $_SESSION['user_id'];

\Stripe\Stripe::setApiKey($stripe_config['secret']);

// Sanitize POST
$POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
$token = $POST['stripeToken'];

//get user email
$user = $getFromU->get_user_by('id', $user_id, ['email, name']);
$ship_to = $getFromA->getBy('shipping', ['ship_by' => $_SESSION['user_id']]);

// echo '<pre>', print_r($charge) ,'</pre>'; <-- just to see what the charge response give us

try{
	// Create Customer In Stripe
	$customer = \Stripe\Customer::create(array(
		"email" => $user->email,
		"source" => $token
	));

	// Get the customer cart total to pay 
	$total = 0;
	$products_he_purchased_name = array();
	foreach($user_cart AS $cart):
		$get_product = $getFromP->get_product_by('products.id', $cart['cart_to']);
	  	$total = $total + $cart['price'];
	  	$products_he_purchased_name[] = $get_product->name;
	endforeach;
	 $desc = implode(' and ', $products_he_purchased_name);

	// Charge the customer
	$charge = \Stripe\Charge::create(array(
		"amount" => bcmul($total, 100),
		"currency" => 'usd',
		"description" => $user->name.' Purchased '.$desc,
		"customer" => $customer->id
	));

		if(isset($charge->status) && $charge->status == 'succeeded'){
			$order_items = array();
			foreach($user_cart AS $cart):
				$getFromA->update('cart', 'id = '.$cart['id'].'',['paid' => 1]);
				$order_items[] = $cart['id'];
			endforeach;
			// Create the Order in DB
		    $order_id = $getFromA->create('orders', [
					'order_by' => $user_id,
					'order_items' => json_encode($order_items),
					'amount' => number_format(($charge->amount /100), 2),
					'created_at' => date('Y-m-d H:i:s'),
					'payment_method' => 'Credit card'
			]);
			$transactionData = [
				'id' => $charge->id,
				'bywho' => $user_id,
				'amount' => number_format(($charge->amount /100), 2),
				'currency' => $charge->currency,
				'status' => $charge->status,
				'method' => 'credit card',
				'order_id' => $order_id
			];
			// Create the transactions data in the DB
			$getFromA->create('payments', $transactionData);
			// Create the customer shipping address in the DB
			if(!empty($_SESSION[$user_id.'_shipping'])){
				$getFromA->create('shipping', $_SESSION[$user_id.'_shipping']);
			}
			// Redirect him to a success page
			header('location: success.php?tid='.$charge->id.'&status='.$charge->status.'&peez='.$order_id.'');

		}else{
			$fail_msg = $charge->failure_message;
			$fail_code = $charge->failure_code;
			// Redirect the customer to an error page
			header('location: error.php?status='.$charge->status.'&fail_msg='.$fail_msg);
		}
} catch (\Stripe\Error\RateLimit $e) {
  // Too many requests made to the API too quickly
		echo $e->getMessage(); //<-- for debuging and resolving issues
} catch (\Stripe\Error\InvalidRequest $e) {
  // Invalid parameters were supplied to Stripe's API
		echo $e->getMessage(); //<-- for debuging and resolving issues
} catch (\Stripe\Error\Authentication $e) {
  // Authentication with Stripe's API failed
  // (maybe you changed API keys recently)
		echo $e->getMessage(); //<-- for debuging and resolving issues
} catch (\Stripe\Error\ApiConnection $e) {
  // Network communication with Stripe failed
		echo $e->getMessage(); //<-- for debuging and resolving issues
} catch (\Stripe\Error\Base $e) {
  // Display a very generic error to the user, and maybe send
  // yourself an email
		echo $e->getMessage(); //<-- for debuging and resolving issues
} catch (Exception $e) {
  // Something else happened, completely unrelated to Stripe
		echo $e->getMessage(); //<-- for debuging and resolving issues
}