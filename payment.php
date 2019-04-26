<?php
##################################################################
#  ========================= Paypal Payment ======================
##################################################################
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

// initialize the api context
$apiContext = new \PayPal\Rest\ApiContext(
	new \PayPal\Auth\OAuthTokenCredential(
		$paypal_config['id'],
		$paypal_config['secret']
	)
);


// --------------- start the payment process method -----------------
$total = 0;
// set customer cart list, the products he will buy
$itemList = new \PayPal\Api\ItemList();

if(isset($user_cart) && is_array($user_cart)){
	foreach($user_cart AS $cart):
		$get_product = $getFromP->get_product_by('products.id', $cart['cart_to']);
		 $item = (new \PayPal\Api\Item())
		  	  ->setName($get_product->name)
		   	  ->setPrice($cart['price'])
		      ->setCurrency('USD') // you can put here EUR => euro | any other currency
		  	  ->setQuantity(1);
		 $itemList->addItem($item);

	  // get totaprice of the cart
	  	$total = $total + $cart['price'];
	endforeach;
}

$details = (new \PayPal\Api\Details())
		 ->setSubtotal($total);


$amount = (new \PayPal\Api\Amount())
		 ->setTotal($total)
		 ->setCurrency('USD')
		 ->setDetails($details);

$transaction = (new \PayPal\Api\Transaction())
				->setItemList($itemList)
				->setDescription('Buy on mysite.com')
				->setAmount($amount)
				->setCustom('coza-demo');

$payment = new \PayPal\Api\Payment();

$payment->setTransactions([$transaction]);


// set the intent there is order|authorize 
//you can read more here https://developer.paypal.com/docs/api/payments/v1/
$payment->setIntent('sale'); 

// set Redirect Urls, redirect the user
$redirectUrl = (new \PayPal\Api\RedirectUrls())
			  ->setReturnUrl('http://localhost/shoop/pay.php')
			  ->setCancelUrl('http://localhost/shoop/shoping-cart.php');

$payment->setRedirectUrls($redirectUrl);

$payment->setPayer((new \PayPal\Api\Payer)->setPaymentMethod('paypal'));


try{
	$payment->create($apiContext);
	header('location: '.$payment->getApprovalLink());
	// echo json_encode([
	// 	'id' => $payment->getId()
	// ]);
}catch(\PayPal\Execption\PayPalConnectionException $e){
	echo $e->getData();
}