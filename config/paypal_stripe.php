<?php
####################################################
#============= Payments Config ===============
####################################################
// Grab payments from the DB
$shop_settings = $getFromA->getAll('shop_front');
$paypal_keys = json_decode($shop_settings[0]->paypal);
$stripe_keys = json_decode($shop_settings[0]->stripe);

$paypal_config = [
	'id' => $paypal_keys->id,
	'secret' => $paypal_keys->secret
];

$stripe_config = [
	'id' => $stripe_keys->id,
	'secret' => $stripe_keys->secret,
];