<?php
#################################################################
#======= Pay Page, where the Paypal payment will execute ======
#################################################################

include 'core/inc/init.php';
if(isset($_SESSION['user_id'])){
	$user_cart = $getFromC->get_user_cart($_SESSION['user_id']);
	if(is_null($user_cart) || empty($user_cart)){
		header('location: shoping-cart.php');
		exit();
	}
}

require 'vendor/autoload.php';
require 'config/paypal_stripe.php';

// user id
$user_id = $_SESSION['user_id'];

// initialize the api context
$apiContext = new \PayPal\Rest\ApiContext(
	new \PayPal\Auth\OAuthTokenCredential(
		$paypal_config['id'],
		$paypal_config['secret']
	)
);

$payment = \PayPal\Api\Payment::get($_GET['paymentId'], $apiContext);
$transactions = $payment->getTransactions();
$execution = (new \PayPal\Api\PaymentExecution())
	->setPayerId($_GET['PayerID'])
	->setTransactions($transactions);

try{
	$payment->execute($execution, $apiContext);
    if($payment->state == 'approved' && !empty($transactions)){
    	$order_items = array();
		// Set order to paid
		foreach($user_cart AS $cart):
			$getFromA->update('cart', 'id = '.$cart['id'].'', ['paid' => 1]);
			$order_items[] = $cart['id'];
			// $amount = $amount + $cart['price'];
		endforeach;
		// Create the Order in DB
		$order_id = $getFromA->create('orders', [
					'order_by' => $user_id, 'order_items' => json_encode($order_items),
					'amount' => $payment->getTransactions()[0]->amount->total,
					'created_at' => date('Y-m-d H:i:s'),
					'payment_method' => 'paypal'
			]);
		$transactionData = [
			'id' => $payment->id,
			'bywho' => $user_id,
			'amount' => $transactions[0]->amount->total,
			'currency' => $transactions[0]->amount->currency,
			'status' => $payment->state,
			'method' => 'paypal',
			'order_id' => $order_id
		];
		// Create the transactions data in the DB
		$getFromA->create('payments', $transactionData);
		// Create the customer shipping address in the DB
		if(!empty($_SESSION[$user_id.'_shipping'])){
			$getFromA->create('shipping', $_SESSION[$user_id.'_shipping']);
		}
		// Redirect him to a success page
		header('location: success.php?tid='.$payment->id.'&status='.$payment->state.'&peez='.$order_id.'');

	}else{
		// Redirect the customer to an error page
		header('location: error.php?status='.$payment->state);
	}

}catch(\PayPal\Execption\PayPalConnectionExecption $e){
	echo $e->getData();
}