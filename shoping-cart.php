 <?php
	include 'core/inc/init.php';
	include 'core/inc/header.php';
	include 'core/inc/navbar.php';
	require 'config/paypal_stripe.php';

 // ***** Initilizing the vars *****
  $total = 0; // initialize the total variable
  $shipping_is_completed = false;
  if(isset($_SESSION['user_id']))
  	$ship_to = $getFromA->getBy('shipping', ['ship_by' => $_SESSION['user_id']]);
    $step = !isset($_SESSION[$user_id.'_shipping']) && isset($ship_to) && empty($ship_to)
  			? 'shipping': 'payment';

 // Delete from guest/loggedIn user cart
  if(isset($_POST['product_to_rem']) && !empty($_POST['product_to_rem']))
  {
  	      $product_to_rem_from_cart = $_POST['product_to_rem'];
  		  if(isset($_SESSION[$user_ip]) && !isset($_SESSION['user_id']))
	  		unset($_SESSION[$user_ip][$product_to_rem_from_cart]);
	  	  elseif(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
		  	$getFromA->delete('cart', ['id' => $product_to_rem_from_cart]);
  }


  // Get user cart guest or logged in
	$user_cart = array();
  if(isset($_SESSION[$user_ip]) && !isset($_SESSION['user_id'])){
  	  $user_cart = $_SESSION[$user_ip];
  	  $user_is_guest = true;
  }
  elseif(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])){
  	  $user_cart = $getFromC->get_user_cart($_SESSION['user_id']);
  	  $user_is_guest = false;
  	  $customer = $getFromU->get_user_by('id', $_SESSION['user_id'], ['*']);
  }
  else{
  	$user_cart = array();
  }



  // Update the products quantity in the user cart
  if(isset($_POST['updated_qntt']) && isset($_POST['product_id']) && isset($_POST['cartid']))
  {
  	$produ_id = $_POST['product_id'];
  	$cart_id = $_POST['cartid'];
  	$qntt_to_update = $_POST['updated_qntt'];
  	$get_p = $getFromP->get_product_by('products.id', $produ_id);
  	 if(!is_null($get_p->id))
  	 {
  	 	  if(isset($_SESSION[$user_ip]) && !isset($_SESSION['user_id']))
  	 	  {
	  	     $_SESSION[$user_ip][$produ_id]['quantity'] = $_POST['updated_qntt'];
	  	     $_SESSION[$user_ip][$produ_id]['price'] = $get_p->price * $_POST['updated_qntt'];

	  	  }elseif(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
	  	  {
		  	$there_is_in_cart_with_cartid = $getFromA->getBy('cart', ['id' => $cart_id]);
			  	if(!empty($there_is_in_cart_with_cartid))
			  	{
			  		$getFromA->update('cart', 'id ='.$cart_id.'', [
				  		'quantity' => $qntt_to_update,
				  		'price' => $get_p->price * $qntt_to_update
				  	]);
			  	}
		  }
	  }
  }


?>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>

 <!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="./shop" class="stext-109 cl8 hov-cl1 trans-04">
				Shop
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>


		 <?php if(!isset($_GET['step'])): ?>
		 	<span class="stext-109 cl4">
				You cart
			</span>
		 <?php else: ?>
		 	<a href="./shoping-cart" class="stext-109 cl8 hov-cl1 trans-04">
				You cart
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>
				<?php if($step == 'shipping'): ?>
					<span class="stext-109 cl4">
						Shipping
					</span>
				<?php else: ?>
					<a href="./shoping-cart?step=shipping" class="stext-109 cl8 hov-cl1 trans-04">
						Shipping
						<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
					</a>
					<a href="./shoping-cart?step=payment" class="stext-109 cl8 hov-cl1 trans-04">
						Payment
					</a>
				<?php endif; ?>
		 <?php endif; ?>
		</div>
	</div>


<!-- Shoping Cart -->
<div class="bg0 p-t-50 p-b-85">
	<div class="container">
		 <?php if(isset($user_cart) && is_array($user_cart) && !empty($user_cart)): ?>
		 	<?php if(isset($_GET['step']) && !empty($_GET['step'])): ?>

		 	  <?php if(!$user_is_guest): ?>
			 	  <?php if(isset($_GET['step']) && $_GET['step'] === 'shipping'): ?>
			 	  	<!-- Shipping to -->
					<?php include './shipping.php'; ?>  <!-- Shipping form -->

			 	  <?php elseif($_GET['step'] === 'payment' && $step == 'payment'): ?>
					   <?php if(isset($_SESSION['credit_pay_error']))
					    	 echo $_SESSION['credit_pay_error'];
					     	elseif(isset($_SESSION['credit_pay_success']))
					     	  echo $_SESSION['credit_pay_success'];
					   ?>
			 	  	<!-- Order and pay -->
			 	  	<div class='box'>
					  <div class='d-flex align-items-center justify-content-between'>
					    <div>
					    	<b class="mr-5"> Contact: </b>
					    	<span> <?php echo $customer->email; ?>  </span>
					    </div>

					    <a href="./profile"> change </a>
					  </div>
					  <div class='d-flex align-items-center justify-content-between'>
					   <div>
					   	    <b class="mr-5"> Ship to: </b>
						    <span>
						    	<?php
						    	if(!empty($ship_to))
						    	  $user_address = $ship_to;
						    	else
						    	  $user_address = $_SESSION[$user_id.'_shipping'];
						    	// print_r($user_address);
						    	 foreach ($user_address as $key => $value) {
						    	 	$val = '';
						    	   	 if($key !== 'id' && $key !== 'ship_by'){
					    				$val = $value.', ';
						    	   	 }
						    	   	 echo $val;
						    	  }
						    	?>
						    </span>
					   </div>
					    <a href="shoping-cart.php?step=shipping"> change </a>
					  </div>
					   <div class='d-flex align-items-center'>
					    <div>
					    	<b class="mr-5"> Method: </b>
					    	<span> Free Worldwide Shipping <b>·</b> Free </span>
					    </div>
					  </div>
					 </div>

					 <h3 class="mt-4 mb-4 belle_underline"> Choose your payment method </h3>
					<div class="accordion" id="accordionExample">
					  <div class="card">
					    <div class="card-header" id="headingTwo">
					          <button class="btn text-center collapsed" type="button" data-toggle="collapse" data-target="#cc" aria-expanded="false" aria-controls="cc">
							      <b>Credit Card</b>
					        </button>
					    </div>
					    <div id="cc" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
					      <div class="card-body">
				            <form action="./charge.php" method="post" id="payment-form">
							  <div class="form-row">
							    <label for="card-element">
							      <!-- Credit or debit card -->
							    </label>
							    <div class='form-control' id="card-element">
							      <!-- A Stripe Element will be inserted here. -->
							    </div>

							    <!-- Used to display Element errors. -->
							    <div id="card-errors" role="alert"></div>
							  </div>

							  <button class="btn btn-primary mt-3 w-100">Submit Payment</button>
							</form>
					      </div>
					    </div>
					  </div>
					  <div class="card">
					    <div class="card-header" id="headingThree">
					        <button class="btn text-center collapsed" type="button" data-toggle="collapse" data-target="#paypal_accordian" aria-expanded="false" aria-controls="paypal_accordian">
							     <b>Paypal</b>
					        </button>
					    </div>
					    <div id="paypal_accordian" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
					      <div class="d-flex justify-content-center p-3">
					      	<a href="payment.php" class="btn btn-primary border-rounded"> Pay with Paypal </a>
					      </div>
					    </div>
					  </div>
					</div>
					<script src="https://js.stripe.com/v3/"></script>
					<script type="text/javascript">// Create a Stripe client.
						var stripe = Stripe('<?php echo $stripe_config['id']?>');
					</script>
					<script src="js/charge.js"></script>
			 	  <?php else: header('location: shoping-cart');
  					 endif; ?>
		 	  <?php else:
		 	  	 header('location: join');
		 	   endif; ?>

		 	<?php else: ?>
		 	 <div class="row">
		       <div class="col-lg-7 col-md-12 m-b-50 cart_box h-100">
				<?php foreach($user_cart AS $cart):
					$get_p = $getFromP->get_product_by('products.id', $cart['cart_to']);
					$p_img = explode(',', $get_p->product_images);
					$data_target = isset($_SESSION['user_id']) ? $cart['id'] : $cart['cart_to'];
 				  ################# Payment Details ################
					$total = $total + $cart['price'];
				?>
					<!-- product cart card -->
					<div class="d-flex justify-content-around flex-wrap cart_p_card">
						<!-- product image -->
						<div>
							  <span class="cart_p_img"
						      	style="background: url('<?php echo $productImagesPath.$p_img[0]?>');
						      		   background-position: center !important;
						      		   background-size: cover !important;
						      		   background-repeat: no-repeat !important;
						      		   height: 140px;width: 140px;
						      		   display: block;
						      		   border-radius: 5px;
						      		   ">
						      </span>
						</div>
						<!-- product info -->
						<div class="d-flex flex-column w-75">
							<div class="d-flex justify-content-between">
								<h5> <?php echo $get_p->name ?> </h5>
								 <form method="POST" action='<?php echo $_SERVER['PHP_SELF'] ?>'>
							      	 <?php if(isset($_SESSION['user_id']) && !empty($cart['id'])): ?>
							      	 	 <input type="hidden" name='product_to_rem' value='<?php echo $cart["id"]?>'>
							      	 <?php else: ?>
							      	 	 <input type="hidden" name='product_to_rem' value='<?php echo $cart["cart_to"]?>'>
							      	 <?php endif;?>
									<button type='submit'
											name="rem_from_cart"
											class="btn-light text-danger rounded-circle text-center btn-sm" style='background: #f3f5f7'>
										<i class="far fa-trash-alt"></i>
									</button>
							   </form>
							</div>
							<small class="text-muted"> Size: <?php echo $cart['size'] ?> </small>
							<small class="text-muted"> Color: <?php echo $cart['color'] ?> </small>
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<!-- data-price is just number there to calculate the total of product from the client side its just some numbers to show the uset the total, it dont have any any any ! relation to the DB, if user changed it from the code it wont effect anything!! -->
									<small class="text-muted"> Quantity: </small>
									<select class="custom-select custom-select-sm update_cart_qntt" data-price='<?php echo $get_p->price?>' data-pid='<?php echo $cart['cart_to']?>' data-cartid='<?php echo isset($_SESSION['user_id']) ? $cart['id']: 0?>'>
									    <option selected value='<?php echo $cart['quantity']?>'>
									  	  <?php echo $cart['quantity']?>
									  	</option>
									  <?php for($i = 1; $i < intval($get_p->quantity); $i++) {
									  	echo '<option value="'.$i.'">'.$i.'</option>';
									  } ?>
									</select>
								</div>
									<span class="text-muted">
										<span class="quantity">
											<?php echo $cart['quantity']?>
										</span>
										x $<?php echo $get_p->price?>
									</span>
								<h4 class="font-weight-bold">
									$<span class="cart_p_price"><?php echo $cart['price']?></span>
								</h4>
							</div>
						   </div>
						</div><!-- end of product cart card -->
						<?php endforeach; ?>
				   </div>


					<div class="col-lg-5 col-md-12 m-b-50">
				       <!-- Order Summary Box -->
						<div class="d-flex flex-column justify-content-center cart_box p-3">
							<h3 class="p-2 text-center"> Order Summary </h3>
							<div>
								<table class="table mb20">
		                            <tbody>
		                                <tr>
		                                    <th>
		                                        <span>Price (<?php echo count($user_cart)?> items)</span>
		                                    </th>
		                                    <td>$<span id='total_for_items'><?php echo $total?></span></td>
		                                </tr>
		                                <tr>
		                                    <th>
		                                        <span>Delivery Charges</span></th>
		                                    <td><strong class="text-green">Free</strong></td>
		                                </tr>
		                            </tbody>
		                            <tbody>
		                                <tr>
		                                    <th>
		                                        <span class="mb0" style="font-weight: 700;">Amount Payable</span></th>
		                                    <td style="font-weight: 700; color: #1c1e1e; ">
		                                     $<span id='order_summ_total'><?php echo $total?></span></td>
		                                </tr>
		                            </tbody>
		                        </table>
		                        <a href="shoping-cart?step=<?php echo $step?>" class="green_btn w-100 text-center"> Proceed To Checkout </a>
							</div>
						</div>
						  <!-- Coupons Box -->
						<!-- <div class="d-flex flex-column justify-content-center cart_box mt-5 p-3">
							<h3 class="p-3" style="border-bottom: 1px dashed rgba(0,0,0,0.2);"> Coupons & Offers </h3>
							<div>
								<form>
									<div class="form-group mt-3">
										<input class="form-control form-control-lg" type="text" placeholder="Discount code">
									</div>
									<button class="pink_btn w-100"> Apply </button>
								</form>
							</div>
						</div> -->
					</div>
				 </div>

		 	<?php endif; ?>


		 <?php else: ?>
		 	<div class="header-cart-item d-flex flex-column justify-content-center align-items-center">
				<img src="./images/empty-cart.png" width="120">
				<h3 class="text-center mt-2"> Your cart is empty </h3>
				<small class="text-muted mt-2"> Dont be shy ! fill it with some great deals </small>
			</div>
		<?php endif; ?>
	</div>
</div>


<?php include 'core/inc/footer.php' ?>
