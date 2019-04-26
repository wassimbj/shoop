<?php
  // Shop front-end
   $shop_front = $getFromA->getAll('shop_front');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Home</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="<?php echo $shopfrontPath.$shop_front[0]->favicon?>"/>
<!--===============================================================================================-->
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,400,500" rel="stylesheet">
<!--===============================================================================================-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
<!--===============================================================================================-->
	<link rel="stylesheet" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" href="fonts/linearicons-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" href="libs/animate/animate.css">	
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="libs/slick/slick.css">
<!--===============================================================================================-->
	<link rel="stylesheet" href="libs/perfect-scrollbar/perfect-scrollbar.css">
<!--===============================================================================================-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="libs/MagnificPopup/magnific-popup.css">
<!--===============================================================================================-->
	<link rel="stylesheet" href="css/util.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/custom.css">
<!--===============================================================================================-->
</head>
<body>


<!-- The snack bar
	==> For notifications and alerts
-->
<div class="notification da_snack">
	<div id='snackbar_text' class="text"> <!-- Snack bar message will go here... --> </div>
	<div class="close ripple" onclick="$('.notification').removeClass('active_snackbar')">
		<div class="text d-flex align-items-center justify-content-center">
			<h4><i class="zmdi zmdi-close"></i></h4>
		</div>
	</div>
</div>
<!-- The snack bar -->