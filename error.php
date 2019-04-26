<?php
	include 'core/inc/init.php';
	include 'core/inc/header.php';
	include 'core/inc/navbar.php';
if(!isset($_GET['status']) && empty($_GET['status']))
{
	header('location: shop');
	exit;
}
?>

<div class="pb-5">

  <div style="background-image: url('./images/curve.png'); background-position: bottom; background-size: cover; z-index: -1">
  	
	    <div class="container mb-5 pt-5">
		    	<div class="mb-4 d-flex flex-column justify-content-center align-items-center mt-4">
		    		<img src="https://image.flaticon.com/icons/svg/753/753345.svg" width="140px">
					<h1 class="text-center display-4 font-weight-bold" style="text-shadow: 4px 3px 2px rgba(0,0,0,0.25)">
						Whoops! looks like something wrong happened
				    </h1>
					<h2 class="text-center d-block mt-2">We are really sorry, please try again later...</h2>
		    	</div>
		</div>
  </div>

</div>

<?php include 'core/inc/footer.php'; ?>