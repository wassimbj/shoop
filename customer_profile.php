<?php
#####################################################
# ========== Customer profile page ============
#####################################################
include 'core/inc/init.php';
include 'core/inc/header.php';
include 'core/inc/navbar.php';

if(!isset($_SESSION['user_id']) && empty($_SESSION['user_id'])){
	header('location: shop');
	exit;
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['edit_profile']))
{
	$to_update = array();
	$errors = array();
	if(empty($_POST['new_name']))
		$errors[] = 'please enter your name';
	if(empty($_POST['new_email']))
		$errors[] = 'please enter your valid email';
	// if customer is not verified we have to update his email else we dont have to
	if(empty($errors)){
		$to_update = [
			'name' => $getFromU->clean_input($_POST['new_name']),
			'email' => $getFromU->clean_input($_POST['new_email']),
		];
		if(!empty($_POST['new_pass'])){
			 $to_update['password'] = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
		}
	}

	// image validation
	if(!empty($_FILES['customer_img']['name']) || is_uploaded_file($_FILES['customer_img']['tmp_name']))
 	{
 		$allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
		$image_size = $_FILES['customer_img']['size'];
		$arr_of_img_name = explode('.', $_FILES['customer_img']['name']);
		$image_ext = strtolower(end($arr_of_img_name));
		// check for size
		 if($image_size < 5000000){
		// check for allowed extensions
		 	if(in_array($image_ext, $allowed_ext)){
		 		$images = $_FILES['customer_img'];
		 	}else{
		 		$errors[] = 'only jpg, jpeg, png and gif are allowed';
		 	}
		 }else{
		 	$errors[] = 'Image size must be less then 10MB';
		 }
			if(empty($errors)){
			 	$image = basename($_FILES['customer_img']['name']);
		 		$tmp = $_FILES['customer_img']['tmp_name'];
				$exploded_img = explode('.', $_FILES['customer_img']['name']);
		 		$img_to_upload = time().'_'.str_replace(' ', '_', $image);
		 		$to_update['image'] = $img_to_upload;
		 		move_uploaded_file($tmp, $customerImgPath.$img_to_upload);
			}
 	}

  if(empty($errors) && !empty($to_update)){
  	 $getFromA->update('customers', 'id = '.$user_id.'', $to_update);
 	 $success = 'Yayy! your details was successfully updated';
  }
}


// ************ Add/Remove from wishlist ***********
if(!empty($user_id)){
	if(isset($_POST['p_id']) && !empty($_POST['p_id'])
   	   && isset($_POST['action']) && !empty($_POST['action']))
	{
		if($_POST['action'] == 'add'){
			// Add to wishlist
		    $getFromA->create('wishlist', [
				'wish_by' => $user_id,
				'wish_to' => $_POST['p_id']
			]);
		}else{
			// Remove from wishlist
			$getFromA->delete('wishlist', [
				'wish_to' => $_POST['p_id'],
				'wish_by' => $user_id
			]);
		}
	}
}
// Get customer
$customer = $getFromA->getBy('customers', ['id' => $user_id]);

if(empty($customer->image)){
	$type = $customer->gender == 'male'? 'shortHair': 'longHair';
	$customer_img = "https://avatars.dicebear.com/v2/avataaars/".$customer->id.".svg?options[top][]=".$type."";
}else{
	$customer_img = $customerImgPath.$customer->image;
}
// $customer_img = empty($customer->image) ? : $.$customer->image;
// Get customer orders
$orders = $getFromA->getAll('orders', 'where order_by ='.$user_id.'', 'order by created_at desc');

// Get ustomer Wishlist 
$wishlist = $getFromA->getAll('wishlist', 'where wish_by ='.$user_id.'');

?>

<div class="container-fluid pt-5 pb-5">
   <?php
      if(!empty($errors))
        error_msg($errors[0]);
      elseif(isset($success))
        success_msg($success);
    ?>
	<div class="d-flex flex-wrap align-items-start profile_wrapper">

		<div class="img-thumbnail mr-3 position-relative">
			<div style="position: absolute; top: 3%; right: 3%">
				<button data-toggle='modal' data-target='#editprofile_<?php echo $customer->id?>' class="btn btn-sm btn-secondary rounded-circle">
					<i class="fas fa-pencil-alt"></i>
				</button>
			</div>
			<div class="d-flex align-items-center justify-content-center flex-column">
				<img src="<?php echo $customer_img?>" class="img-thumbnail rounded-circle"
				style="width: 145px; height: 145px">
				<h4 class="text-center d-block"><?php echo $customer->name?></h4>
				<p class="text-center d-block text-muted">
					<?php if($customer->verified == 0): ?>
						<span class="text-danger"><i class="far fa-times-circle"></i></span>
						Unverified
					<?php else: ?>
						<span class="text-success"><i class="far fa-check-circle"></i></span>
						Verified
					<?php endif?>
				</p>
				<hr />
				<div>
					<p> <span class="text-muted">Email</span>: <?php echo $customer->email?> </p>
					<p> <span class="text-muted">password</span>: •••••••••• </p>
				</div>
			</div>
		</div>


		<div class="bd-example bd-example-tabs img-thumbnail">
		  <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
		    <li class="nav-item">
		      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#orders" role="tab" aria-controls="home" aria-selected="false">My Orders</a>
		    </li>
		    <li class="nav-item">
		      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#wishlist" role="tab" aria-controls="profile" aria-selected="false">My Wishlist</a>
		    </li>
		  </ul>
		  <!-- ########################## Orders Tab ######################### -->
		  <div class="tab-content" id="myTabContent">
		    <div class="tab-pane fade active show" id="orders" role="tabpanel" aria-labelledby="home-tab">
		    	<?php if(is_array($orders) && !empty($orders)): ?>
		    	 <?php foreach($orders AS $order):
		    		// get the order item
		    		$order_items_ids = json_decode($order->order_items);
		    		$order_items = array();
		    		foreach($order_items_ids AS $item_id){
		    			$order_items[] = $getFromA->getBy('cart', ['id' => $item_id]);
		    		}
		    	?>
		    		<!--  Order Box  -->
					   <div class='img-thumbnail p-0 mt-3'>
					     <div class='d-flex align-items-center justify-content-between p-2'>
					     <!--    order imgs    -->
					       <div class='d-flex align-items-center'>
					       <?php foreach($order_items AS $item):
					       	 $product_he_purchased = $getFromA->getBy('products', ['id' => $item->cart_to]);
					       	 $product_img = $getFromA->getBy('pimages', [
					       	 	'connector' => $product_he_purchased->image
					       	 ]);
					       	?>
					       	 <span class='order_p_img'>
					       	 	<img src='<?php echo $productImagesPath.$product_img->image?>' class='rounded-circle img-thumbnail' width='70px' style='height: 70px'/>
					         </span>
					       <?php endforeach; ?>
					         <!--   order product names    -->
					       <div class='ml-3'>
					       	<?php foreach($order_items AS $item):
					       	 $product_he_purchased = $getFromA->getBy('products', ['id' => $item->cart_to]);?>
					           <p class='text-muted mb-1'>
					           	<?php echo $product_he_purchased->name?>
					           	<b><?php echo $item->quantity > 1 ? '(x'.$item->quantity.')': '';?></b>
					           </p>
					          <?php endforeach; ?>
					       </div>
					    </div>
					     
					     <!--  Order price and time  -->
					       <div>
					        <p class='mb-0'>
					        	<h5 class='font-weight-bold'> 
					        		<?php echo '$'.$order->amount?>
					            </h5> 
					        </p>
					        <p class='text-muted text-right mb-0'>
					        	<small><?php echo time_ago($order->created_at) ?></small>
					    	</p>
					       </div>
					     </div>
					  <hr class='mb-0' />
					   <div class='d-flex justify-content-between text-center order_steps'>
					     <?php if(strtolower($order->status) == 'shipped'): ?>
					     	<span class='w-100 bg-success text-white'>
					     		<h5> <i class="far fa-check-circle"></i> Delivered</h5>
					     	</span>
					     <?php else: ?>
					        <span class='active'> Recieved </span>
					        <span class='active'> Preparing </span>
					     	<span> Delivered </span>
					     <?php endif; ?>
					   </div>
					 </div>
				  <!-- end of order box -->
		    	<?php endforeach; ?>
		    	<?php else: ?>
		    		<h3 class="text-muted text-center pt-5 pb-4">
	    			 <p><i class="fas fa-truck-loading"></i></p>
	    			 <p>Yooo! you have no orders yet...</p>
		    		</h3>
		    	<?php endif; ?>
			 </div>
			 <!-- ########################## Wishlist Tab ######################### -->
		    <div class="tab-pane fade" id="wishlist" role="tabpanel" aria-labelledby="profile-tab">
		     <?php if(is_array($wishlist) && !empty($wishlist)): ?>
		     	<div class="row p-2">
			     <?php foreach($wishlist AS $fav):
			     	$product = $getFromP->get_product_by('products.id', $fav->wish_to);
		     		$product_img = explode(',', $product->product_images);
					$product_sizes = json_decode($product->size);
					$product_colors = json_decode($product->color);
					$p_discount = $getFromA->getBy('discount', ['discount_to' => $product->discount_id, 'expired' => 0]);
			     ?>
			         <!-- product card -->
				   <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 pr-0">
						<?php if(!empty($p_discount) && $p_discount->precent > 0): ?>
							<div class="discount"><?php echo $p_discount->precent.'%'?></div>
						<?php endif; ?>
					  <div class="block2">
						<div class="block2-pic hov-img0">
							<span
						      	style="background: url('<?php echo $productImagesPath.$product_img[1]?>');
						      		   background-position: center !important;
						      		   background-size: cover !important;
						      		   background-repeat: no-repeat !important;
						      		   height: 100%;
						      		   display: block;
						      		   ">
						      </span>
							<a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1" data-target='#_<?php echo $product->id?>'>
								Quick View
							</a>
						</div>

						<div class="block2-txt flex-w flex-t p-t-14">
							<div class="block2-txt-child1 flex-col-l ">
								<a href="<?php echo str_replace(' ', '+', $product->name) ?>" class="mtext-101 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
									<?php echo short_text($product->name, 30) ?>
								</a>

								<span class="stext-105 cl5">
									<?php if($product->old_price > 0):?>
										<div class="d-flex product_price">
											<p class="mb-0 text-danger"><del><?php echo '$'.$product->old_price?></del></p>
											<h5 class="font-weight-bold ml-1"> <?php echo '$'.$product->price?> </h5> 
									     </div>
									<?php else: ?>
										<h5 class="font-weight-bold"> <?php echo '$'.$product->price?> </h5>
									<?php endif?>
								</span>
							</div>

							<div class="block2-txt-child2 d-block text-center flex-r p-t-3">
								<?php
								$wish_to = $getFromA->getBy('wishlist', ['wish_to' => $product->id]);
								if(empty($wish_to)): ?>
								  <button type="button" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2" title='add to wishlist' data-action='add' data-id='<?php echo $product->id?>'>
									<i class="far fa-heart"></i>
								  </button>
								<?php else: ?>
								  <button type="button" class="btn-addwish-b2 text-danger dis-block pos-relative js-addwish-b2" title='remove from wishlist' data-action='remove' data-id='<?php echo $product->id?>'>
										<i class="fas fa-heart"></i>
								  </button>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<!-- START of the Modal -->
						<div class="wrap-modal1 js-modal1 p-t-60 p-b-20" id='_<?php echo $product->id?>'>
							<input type="hidden" name="product_name" value="<?php echo $product->id ?>" id='p_id' disabled>
							<div class="overlay-modal1 js-hide-modal1" data-target='#_<?php echo $product->id?>'></div>
							<div class="container">
								<div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
									<button class="how-pos3 trans-04 js-hide-modal1" data-target='#_<?php echo $product->id?>'>
										<img src="images/icons/icon-close.png" alt="CLOSE">
									</button>

									<div class="row">
										<div class="col-md-6 col-lg-7 p-b-30">
											<div class="p-l-25 p-r-30 p-lr-0-lg">
												<div class="wrap-slick3 flex-sb flex-w flex-column-reverse">
													<div class="wrap-slick3-dots"></div>
													
														<div class="wrap-slick3-arrows flex-sb-m flex-w"></div>
													<div class="slick3 gallery-lb">
														<?php foreach($product_img AS $p_img): ?>
															<div class="item-slick3" data-thumb="<?php echo $productImagesPath.$p_img ?>">
																<div class="wrap-pic-w pos-relative">
																	<span
															      	style="background: url('<?php echo $productImagesPath.$p_img?>');
															      		   background-position: center !important;
															      		   background-size: cover !important;
															      		   background-repeat: no-repeat !important;
															      		   height: 500px;
															      		   display: block;
															      		   ">
															      </span>
																</div>
															</div>
														<?php endforeach;?>
													</div><!-- end of modal slick/slider -->
												</div>
											</div>
										</div>
										
										<div class="col-md-6 col-lg-5 p-b-30">
											<div class="p-r-50 p-t-5 p-lr-0-lg">
												<div class="pb-4">
													<h3 class="mtext-105 cl2 js-name-detail">
														<?php echo $product->name ?>
													</h3>

													<span class="stext-105 cl5 d-block mt-3">
														<?php if($product->old_price > 0):?>
															<div class="d-flex ">
																<p class="mb-0 text-danger"><del><?php echo '$'.$product->old_price?></del></p>
																<h5 class="font-weight-bold ml-1"> <?php echo '$'.$product->price?> </h5> 
														     </div>
														<?php else: ?>
															<h5 class="font-weight-bold"> <?php echo '$'.$product->price?> </h5>
														<?php endif?>
													</span>
												</div>

												<p class="stext-102 cl3 p-t-23">
													<?php echo short_text($product->description, 250) ?>
												</p>
												
												<!--  -->
												<div class="p-t-33">
												<?php if(is_array($product_sizes) && $product_sizes[0] != 'without size'): ?>
													<div class="flex-w flex-r-m p-b-10">
														<div class="size-203 flex-c-m respon6">
															Size
														</div>

														<div class="size-204 respon6-next">
															<div class="btn-group btn-group-toggle" data-toggle="buttons">
															<?php foreach($product_sizes AS $size): ?>
																<label class="btn btn-secondary">
																    <input type="radio" name="size" id="size" value="<?php echo $size?>"> <?php echo $size?>
																 </label>
															<?php endforeach; ?>
															</div>
														</div>
												   </div>
												<?php else: ?>
													<input type="radio" id="size" value="without size" style='opacity: 0' checked>
												<?php endif; ?>

													<div class="flex-w flex-r-m p-b-10">
														<div class="size-203 flex-c-m respon6">
															Color
														</div>

														<div class="size-204 respon6-next">
															<div class="btn-group btn-group-toggle" data-toggle="buttons">
															<?php foreach($product_colors AS $color): ?>
																<label class="btn btn-secondary">
																    <input type="radio" name="color" id="color" value="<?php echo $color?>"> <?php echo $color?>
																 </label>
															<?php endforeach; ?>
															</div>
														</div>
													</div>

													<div class="flex-w flex-r-m p-b-10">
														<div class="size-204 flex-w flex-m respon6-next">
															<div class="wrap-num-product flex-w m-r-20 m-tb-10">
																<div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
																	<i class="fs-16 zmdi zmdi-minus"></i>
																</div>

																<input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product"
																data-max='<?php echo $product->quantity?>' value="1" id='qntt'>

																<div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
																	<i class="fs-16 zmdi zmdi-plus"></i>
																</div>
															</div>

															<button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail add_to_cart_btn"
															data-pid='#_<?php echo $product->id?>' type='button'>
																Add to cart
															</button>
														</div>
													</div>	
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
				  <!-- END of the modal -->
				     </div><!-- end of product card -->
			     <?php endforeach; ?>
			     </div><!-- end of row of the wishlist products -->
		     <?php else: ?>
		     	<h3 class="text-muted text-center pt-5 pb-4">
		     	 <p><i class="far fa-heart"></i></p>
		    	  <p>Your wishlist is empty for now !</p>
		    	</h3>
		     <?php endif; ?>
		    </div>
		  </div>
		</div>
	</div>
</div>

<!-- edit profile popup -->
<div class="modal fade" id="editprofile_<?php echo $customer->id?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header flex-column align-items-center">
        <h4 class="modal-title d-block">Edit your details</h4>
        <small class="d-block"> Just change what you wanna change and save the changes </small>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
        style="position: absolute; top: 3%; right: 2%; padding: 0 !important">
         <h3><i class="zmdi zmdi-close"></i></h3>
        </button>
      </div>
      <form method="POST" enctype="multipart/form-data">
      	<div class="modal-body">
      	<div class="position-relative image_edit text-center">
      		<img src="<?php echo $customer_img?>" class="rounded-circle"
						style="width: 145px; height: 145px">
					<input type="file" name="customer_img" style="position: absolute; top: 0; left: 0; opacity: 0" class="w-100 h-100 pointer">
      	</div>
  		 <?php if($customer->verified == 0): ?>
  		 	  <div class="form-group">
			    <label>Email address</label>
			    <input type="email" name='new_email' class="form-control" value="<?php echo $customer->email?>">
			  </div>
  		 <?php else: ?>
  		 	<div class="form-group">
			    <label>Email address</label>
			    <input type="email" name='new_email' class="form-control" value="<?php echo $customer->email?>" disabled>
			    <small> This email is <span class="text-success">verified</span>, you can't edit it </small>
			 </div>
  		 <?php endif?>
  		 <input type="hidden" name='verified' class="form-control" value="<?php echo $customer->verified?>">
		  <div class="form-group">
		    <label>Name</label>
		    <input type="text" name="new_name" class="form-control" value="<?php echo $customer->name?>">
		  </div>
		 <!--  <div class="form-group">
		    <label>Current password</label>
		    <input type="password" class="form-control" placeholder="Current password">
		  </div> -->
		  <div class="form-group">
		    <label>New password</label>
		    <input type="password" name='new_pass' class="form-control" placeholder="New password">
		  </div>
	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      <button type="submit" class="btn btn-primary" name='edit_profile'>Save changes</button>
	    </div>
      </form>
    </div>
  </div>
</div>
<?php include 'core/inc/footer.php'; ?>