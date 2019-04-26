<?php
	include 'core/inc/init.php';
	include 'core/inc/header.php';
	include 'core/inc/navbar.php';
if(isset($_GET['peez']) && !empty($_GET['peez']) && isset($_GET['status']) && !empty($_GET['status'])
	&& isset($_GET['tid']) && !empty($_GET['tid']))
{
	$payment_he_made = $getFromA->getBy('payments', ['id' => $_GET['tid']]);
	$products_he_ordered = $getFromA->getBy('orders', ['id' => $_GET['peez']]);
	if(empty($payment_he_made) || empty($products_he_ordered))
	{
		header('location: shop');
		exit;
	}
}else{
	header('location: shop');
	exit;
}
?>

<div class="pb-5">

  <div style="background-image: url('./images/curve.png'); background-position: bottom; background-size: cover; z-index: -1">
  	
	    <div class="container mb-5 pt-5">
		    	<div class="mb-4 d-flex flex-column justify-content-center align-items-center mt-4">
		    		<img src="http://tetranoodle.com/wp-content/uploads/2018/07/tick-gif.gif" width="140px">
					<h1 class="text-center display-4 font-weight-bold" style="text-shadow: 4px 3px 2px rgba(0,0,0,0.25)">
						That's All, Thank you !
				    </h1>
					<h2 class="text-center d-block mt-2">Your order was successfully completed</h2>
		    	</div>
		</div>
  </div>

</div>

<?php include 'core/inc/footer.php'; ?>