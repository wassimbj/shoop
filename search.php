<?php
################################################################
#==================== Search products page ===================
################################################################
include 'core/inc/init.php';
include 'core/inc/header.php';
include 'core/inc/navbar.php';

	if(isset($_GET['search']) && !empty($_GET['search']))
	{
		$q = $_GET['search'];
		$cond = '
			name like "%" "'.$q.'" "%" or description like "%" "'.$q.'" "%"
			or category like "%" "'.$q.'" "%" or size like "%" "'.$q.'" "%"
			or sub_category like "%" "'.$q.'" "%"
		';
		$products = $getFromP->get_products($cond);
	}else{
		header('location: shop');
		exit;
	}
	// print_r($products);
	// echo $cond;
?>
<?php if(isset($products) && $products !== false && count($products) > 0): ?>
 <div class="bg0 p-b-140 products_area container">
 	<div class="mb-3"> <b><?php echo count($products)?> Results</b> was found for
 		<span class="badge bade-pill bg-dark text-white"> <?php echo short_text($q, 40)?> </span>
 	</div>
    <div class="row">
  <?php if(isset($products) && (is_array($products) || is_object($products))):  ?>
	<?php foreach($products AS $product):
			$product_img = explode(',', $product->product_images);
			$product_sizes = json_decode($product->size);
			$product_colors = json_decode($product->color);
			$p_discount = $getFromA->getBy('discount', ['discount_to' => $product->discount_id, 'expired' => 0]);
	?>

	  <!-- product card -->
		<div class="col-sm-6 col-md-4 col-lg-3 p-b-35">
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
						if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
						 	$wish_to = $getFromA->getBy('wishlist', ['wish_to' => $product->id, 'wish_by' => $_SESSION['user_id']]);
						if(isset($wish_to) && !empty($wish_to)): ?>
						  <button type="button" class="btn-addwish-b2 text-danger dis-block pos-relative js-addwish-b2" title='remove from wishlist' data-action='remove' data-id='<?php echo $product->id?>'>
								<i class="fas fa-heart"></i>
						  </button>
						<?php else: ?>
						  <button type="button" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2" title='add to wishlist' data-action='add' data-id='<?php echo $product->id?>'>
							<i class="far fa-heart"></i>
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
																<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="<?php echo $productImagesPath.$p_img ?>">
																	<i class="fa fa-expand"></i>
																</a>
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
														<label class="btn">
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
														<label class="btn">
														    <input type="radio" name="color" id="color" value="<?php echo $color?>"> <?php echo $color?>
														 </label>
													<?php endforeach; ?>
													</div>
												</div>
											</div>

											<div class="flex-w flex-r-m p-b-10">
												<div class="size-204 respon6-next">
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

													<button class="flex-c-m stext-101 cl0 size-101 p-lr-15 trans-04 js-addcart-detail add_to_cart_btn"
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
    <?php endif; ?>
   </div>
</div>
<?php else: ?>

	<div class="d-flex justify-content-center align-items-center flex-column container mb-5 p-4">
		<img src="https://www.gotriptravel.com.au/img/empty_cart.png" class="img-fluid">
		<h3 class="mt-4 mb-3 text-center text-muted font-weight-light">
			Whoops! no product was found for
			<span class="badge bade-pill bg-dark"> <?php echo short_text($q, 40)?> </span>
 		</h3>
	</div>

<?php endif; ?>












<!-- Back to top -->
<div class="btn-back-to-top" id="myBtn">
	<span class="symbol-btn-back-to-top">
		<i class="zmdi zmdi-chevron-up"></i>
	</span>
</div>

<?php include 'core/inc/footer.php' ?>