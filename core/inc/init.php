<?php
// -----------------------------------------------
// Here where everything is initilized
// just some global things to get htings started 
// and orgonize the work
//------------------------------------------------
 ob_start();
session_start();

if(isset($inside_admin) && $inside_admin == true){
	// if we are inisde the admin folder add the "../" to get out of the admin folder
	// and access the other folders
	// so we can access them from within the admin folder
	include '../config/db.php';
	require_once '../core/database/connection.php';
	require '../admin/vendor/phpmailer/phpmailer/src/Exception.php';
	require '../admin/vendor/phpmailer/phpmailer/src/PHPMailer.php';
	require '../admin/vendor/phpmailer/phpmailer/src/SMTP.php';
	include '../core/classes/admin.class.php';
	include '../core/classes/product.class.php';
	include '../core/classes/customer.class.php';
	include '../core/classes/cart.class.php';
	include '../core/funcs/funcs.php';
	$productImagesPath = '../uploads/product_images/';
	$shopfrontPath = '../uploads/shop_front/';
	$customerImgPath = '../uploads/customer_img/';
}else{
	include './config/db.php';
	require_once 'core/database/connection.php';
	include 'core/classes/admin.class.php';
	include 'core/classes/product.class.php';
	include 'core/classes/customer.class.php';
	include 'core/classes/cart.class.php';
	include 'core/funcs/funcs.php';
	$productImagesPath = './uploads/product_images/';
	$shopfrontPath = './uploads/shop_front/';
	$customerImgPath = './uploads/customer_img/';
}

// user ip, the guest
$user_ip = getUserIP();

// Product images path

ini_set('max_execution_time', 300); //300 seconds = 5 minutes

 // Defines the classes
 $db = new Database();
 $getFromA = new Admin($db); // A = Admin
 $getFromP = new Product($db); // P = Product
 $getFromU = new Customer($db); // U = User
 $getFromC = new Cart($db); // C = Cart

