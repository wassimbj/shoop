<?php
	include 'core/inc/init.php';
	include 'core/inc/header.php';
	include 'core/inc/navbar.php';


// Get hottest discounts
$hot_products = $getFromP->get_products('discount_id > 0', 'order by created_at desc');

// Categorys
$cates = $getFromA->getAll('categories', '', 'order by created_at');

// Get newest products
$newest_p = $getFromP->get_products(
																	 'DATE(created_at) > (NOW() - INTERVAL 10 DAY)'
																	);

?>

	<!-- Banner  -->
	<section>
	  <div class="banner">
			<div class="d-flex align-items-center justify-content-center flex-column h-100">
				<h1 class="text-white display-4 mb-0 font-weight-bold text-center"> With shoop </h1>
				<br>
				<h1 class="text-white display-4 font-weight-bold text-center"> Dont think that much... </h1>
				<p class="text-white mb-4 mt-3"> Get the best deals, shop and delievery  </p>
				<a href="./shop" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
					Shop Now
				</a>
			</div>
	  </div>
	  <div class="banner_info shadow-sm p-3 mb-5 bg-white rounded p-3 w-100 h-25">
	  	<div class="d-flex justify-content-around flex-wrap">
	  		<div class="d-flex align-items-center">
	  			<span class="border-right pr-2"><img src="./images/icons/shipping.png" width="50px"></span>
	  			<div class='pl-2'>
	  				<b> Fast shipping </b> <br>
	  				<span class="text-muted"> Get the best shipping experience </span>
	  			</div>
	  		</div>
	  		<!-- *** -->
	  		<div class="d-flex align-items-center">
	  			<span class="border-right pr-2"><img src="./images/icons/secure.png" width="50px"></span>
	  			<div class='pl-2'>
	  				<b> Secure payments </b> <br>
	  				<span class="text-muted"> All of your payments are secured </span>
	  			</div>
	  		</div>
	  		<!-- *** -->
	  		<div class="d-flex align-items-center">
	  			<span class="border-right pr-2"><img src="./images/icons/support.png" width="50px"></span>
	  			<div class='pl-2'>
	  				<b> Got a question ? we will help </b> <br>
	  				<span class="text-muted"> You can send us messages </span>
	  			</div>
	  		</div>
	  	</div>
	  </div>
	</section>

	<!-- Categories -->
	<div class="sec-banner bg0 p-t-95 p-b-200">
		<div class="container">
			<div class="p-b-10">
				<h3 class="ltext-103 cl5 belle_underline">
				  Collections
				</h3>
			</div>
			<div class="row">
				<?php
				  foreach($cates as $cate):
					// get some images from products
					$product = $getFromP->get_product_by('category', $cate->name);
					if(!is_null($product->id)):
					  $img = explode(',', $product->product_images); // get product images array
				?>
				 <a href='shop?cate=<?php echo $cate->name ?>' class="col col-lg-3 col-sm-6 mb-3">
					<!-- Block1 -->
					<div class="block1 wrap-pic-w cate_card for_bg_imgs" style='background: url("<?php echo $productImagesPath.$img[0]?>");'></div>
					<h4 class="text-center d-block text-dark cate_card_name"><?php echo $cate->name ?></h4>
				</a>
				<?php
				    endif; // end of checking if product is empty
				  endforeach;
				?>

			</div>
		</div>
	</div>



<!-- Hot discounts -->
<?php if(!empty($hot_products)): ?>
	<section class="p-b-200">
		<div class="container">
			<div class="p-b-10">
				<h3 class="ltext-103 cl5 belle_underline">
					Hot discounts
				</h3>
			</div>
			<div class="wrap-slick2">
				<div class="slick2">
				<?php
					foreach($hot_products AS $p):
					// $p = $product (just for shortness)
					$imgs = explode(',', $p->product_images); // get product images array
					$related_p_sizes = json_decode($p->size); // get product sizes array
					$related_p_colors = json_decode($p->color); // get product colors array
					$p_discount = $getFromA->getBy('discount', ['discount_to' => $p->discount_id, 'expired' => 0]);
				?>
				<!-- Product -->
					<div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
						<!-- Block2 -->
						<div class="block2">
							<div class="block2-pic hov-img0">
								<?php if(!empty($p_discount) && $p_discount->precent > 0): ?>
									<div class="discount"><?php echo $p_discount->precent.'%'?></div>
								<?php endif; ?>
								<span
											style="background: url('<?php echo $productImagesPath.$imgs[0]?>');
													background-position: 75% 50% !important;
													background-size: cover !important;
													background-repeat: no-repeat !important;
													height: 100%;
													display: block;
													">
										</span>
								<a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1" data-target='#_<?php echo $p->id?>'>
									Quick View
								</a>
							</div>

							<div class="block2-txt flex-w flex-t p-t-14">
								<div class="block2-txt-child1 flex-col-l ">
									<a href="<?php echo str_replace(' ', '+', $p->name) ?>" class="mtext-101 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
										<?php echo short_text($p->name, 30)?>
									</a>

									<span class="stext-105 cl5">
										<?php if($p->old_price > 0):?>
											<div class="d-flex product_price">
												<p class="mb-0 text-danger"><del><?php echo '$'.$p->old_price?></del></p>
												<h5 class="font-weight-bold ml-1"> <?php echo '$'.$p->price?> </h5>
												</div>
										<?php else: ?>
											<h5 class="font-weight-bold"> <?php echo '$'.$p->price?> </h5>
										<?php endif?>
									</span>
								</div>

								<div class="block2-txt-child2 d-block text-center flex-r p-t-3">
									<?php
									if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
										$wish_to = $getFromA->getBy('wishlist', ['wish_to' => $p->id, 'wish_by' => $_SESSION['user_id']]);
									if(isset($wish_to) && !empty($wish_to)): ?>
										<button type="button" class="btn-addwish-b2 text-danger dis-block pos-relative js-addwish-b2" title='remove from wishlist' data-action='remove' data-id='<?php echo $p->id?>'>
											<i class="fas fa-heart"></i>
										</button>
									<?php else: ?>
										<button type="button" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2" title='add to wishlist' data-action='add' data-id='<?php echo $p->id?>'>
										<i class="far fa-heart"></i>
										</button>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			</div>
			<!-- product quick view models -->
			<?php foreach($hot_products AS $product):
				$related_p_imgs = explode(',', $product->product_images); // get product images array
				$related_p_sizes = json_decode($product->size); // get product sizes array
				$related_p_colors = json_decode($product->color); // get product colors array
			?>
				<!-- START of the Modal -->
				<div class="wrap-modal1 js-modal1 p-t-60 p-b-20" id='_<?php echo $product->id?>'>
					<div class="overlay-modal1 js-hide-modal1" data-target='#_<?php echo $product->id?>'></div>
					<input type="hidden" value="<?php echo $product->id ?>" id='p_id' disabled>
					<div class="container">
						<div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
							<button class="how-pos3 trans-04 js-hide-modal1" data-target='#_<?php echo $product->id?>'>
								<img src="images/icons/icon-close.png" alt="CLOSE">
							</button>

							<div class="row">
								<div class="col-md-6 col-lg-7 p-b-30">
									<div class="p-l-25 p-r-30 p-lr-0-lg">
										<div class="wrap-slick3 flex-sb flex-w">
											<div class="wrap-slick3-dots"></div>
											<div class="wrap-slick3-arrows flex-sb-m flex-w"></div>

											<div class="slick3 gallery-lb">
												<?php foreach($related_p_imgs AS $p_img): ?>
													<div class="item-slick3" data-thumb="<?php echo $productImagesPath.$p_img ?>">
														<div class="wrap-pic-w pos-relative">
															<span
																	style="background: url('<?php echo $productImagesPath.$p_img?>');
																			background-position: 75% 50% !important;
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
											<div class="d-flex align-items-center justify-content-between pb-4">
											<h3 class="mtext-105 cl2 js-name-detail">
												<?php echo $product->name ?>
											</h3>

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

										<p class="stext-102 cl3 p-t-23">
											<?php echo $product->description ?>
										</p>

										<!--  -->
										<div class="p-t-33">
											<?php if(is_array($related_p_sizes) && $related_p_sizes[0] != 'without size'): ?>
												<div class="flex-w flex-r-m p-b-10">
													<div class="size-203 flex-c-m respon6">
														Size
													</div>

													<div class="size-204 respon6-next">
														<div class="btn-group btn-group-toggle" data-toggle="buttons">
														<?php foreach($related_p_sizes AS $size): ?>
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
													<?php foreach($related_p_colors AS $color): ?>
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

														<input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product" data-max='<?php echo $product->quantity?>' value="1" id='qntt'>

														<div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
															<i class="fs-16 zmdi zmdi-plus"></i>
														</div>
													</div>

													<button class="flex-c-m stext-101 cl0 size-101 p-lr-15 trans-04 js-addcart-detail add_to_cart_btn" data-pid='#_<?php echo $product->id?>'>
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
			<?php endforeach; ?>
		</div>
	</section>
<?php endif; ?>

<!-- Our Newest -->
<?php if(!empty($newest_p)){ ?>
	<section class="p-b-200">
		<div class="container">
			<div class="p-b-10">
				<h3 class="ltext-103 cl5 belle_underline">
					Our newest
				</h3>
			</div>
			<div class="wrap-slick2">
				<div class="slick2">
				<?php
					foreach($newest_p AS $p):
					// $p = $product (just for shortness)
					$imgs = explode(',', $p->product_images); // get product images array
					$related_p_sizes = json_decode($p->size); // get product sizes array
					$related_p_colors = json_decode($p->color); // get product colors array
					$p_discount = $getFromA->getBy('discount', ['discount_to' => $p->discount_id, 'expired' => 0]);
				?>
				<!-- Products -->
					<div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
						<!-- Block2 -->
						<div class="block2">
							<div class="block2-pic hov-img0">
								<?php if(!empty($p_discount) && $p_discount->precent > 0): ?>
									<div class="discount"><?php echo $p_discount->precent.'%'?></div>
								<?php endif; ?>
								<span
											style="background: url('<?php echo $productImagesPath.$imgs[0]?>');
													background-position: 75% 50% !important;
													background-size: cover !important;
													background-repeat: no-repeat !important;
													height: 100%;
													display: block;
													">
										</span>
								<a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1" data-target='#_<?php echo $p->id?>'>
									Quick View
								</a>
							</div>

							<div class="block2-txt flex-w flex-t p-t-14">
								<div class="block2-txt-child1 flex-col-l ">
									<a href="<?php echo str_replace(' ', '+', $p->name) ?>" class="mtext-101 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
										<?php echo short_text($p->name, 30)?>
									</a>

									<span class="stext-105 cl5">
										<?php if($p->old_price > 0):?>
											<div class="d-flex product_price">
												<p class="mb-0 text-danger"><del><?php echo '$'.$p->old_price?></del></p>
												<h5 class="font-weight-bold ml-1"> <?php echo '$'.$p->price?> </h5>
												</div>
										<?php else: ?>
											<h5 class="font-weight-bold"> <?php echo '$'.$p->price?> </h5>
										<?php endif?>
									</span>
								</div>

								<div class="block2-txt-child2 d-block text-center flex-r p-t-3">
									<?php
									if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
										$wish_to = $getFromA->getBy('wishlist', ['wish_to' => $p->id, 'wish_by' => $_SESSION['user_id']]);
									if(isset($wish_to) && !empty($wish_to)): ?>
										<button type="button" class="btn-addwish-b2 text-danger dis-block pos-relative js-addwish-b2" title='remove from wishlist' data-action='remove' data-id='<?php echo $p->id?>'>
											<i class="fas fa-heart"></i>
										</button>
									<?php else: ?>
										<button type="button" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2" title='add to wishlist' data-action='add' data-id='<?php echo $p->id?>'>
										<i class="far fa-heart"></i>
										</button>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
						<?php endforeach; ?>
				</div>
			</div>
			<!-- product quick view models -->
			<?php foreach($newest_p AS $product):
				$related_p_imgs = explode(',', $product->product_images); // get product images array
				$related_p_sizes = json_decode($product->size); // get product sizes array
				$related_p_colors = json_decode($product->color); // get product colors array
			?>
				<!-- START of the Modal -->
				<div class="wrap-modal1 js-modal1 p-t-60 p-b-20" id='_<?php echo $product->id?>'>
					<div class="overlay-modal1 js-hide-modal1" data-target='#_<?php echo $product->id?>'></div>
					<input type="hidden" value="<?php echo $product->id ?>" id='p_id' disabled>
					<div class="container">
						<div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
							<button class="how-pos3 trans-04 js-hide-modal1" data-target='#_<?php echo $product->id?>'>
								<img src="images/icons/icon-close.png" alt="CLOSE">
							</button>

							<div class="row">
								<div class="col-md-6 col-lg-7 p-b-30">
									<div class="p-l-25 p-r-30 p-lr-0-lg">
										<div class="wrap-slick3 flex-sb flex-w">
											<div class="wrap-slick3-dots"></div>
											<div class="wrap-slick3-arrows flex-sb-m flex-w"></div>

											<div class="slick3 gallery-lb">
												<?php foreach($related_p_imgs AS $p_img): ?>
													<div class="item-slick3" data-thumb="<?php echo $productImagesPath.$p_img ?>">
														<div class="wrap-pic-w pos-relative">
															<span
																	style="background: url('<?php echo $productImagesPath.$p_img?>');
																			background-position: 75% 50% !important;
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
													<div class="d-flex product_price">
														<p class="mb-0 text-danger"><del><?php echo '$'.$product->old_price?></del></p>
														<h5 class="font-weight-bold ml-1"> <?php echo '$'.$product->price?> </h5>
														</div>
												<?php else: ?>
													<h5 class="font-weight-bold"> <?php echo '$'.$product->price?> </h5>
												<?php endif?>
											</span>
										</div>

										<p class="stext-102 cl3 p-t-23">
											<?php echo $product->description ?>
										</p>

										<!--  -->
										<div class="p-t-33">
											<?php if(is_array($related_p_sizes) && $related_p_sizes[0] != 'without size'): ?>
												<div class="flex-w flex-r-m p-b-10">
													<div class="size-203 flex-c-m respon6">
														Size
													</div>

													<div class="size-204 respon6-next">
														<div class="btn-group btn-group-toggle" data-toggle="buttons">
														<?php foreach($related_p_sizes AS $size): ?>
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
													<?php foreach($related_p_colors AS $color): ?>
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

														<input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product" data-max='<?php echo $product->quantity?>' value="1" id='qntt'>

														<div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
															<i class="fs-16 zmdi zmdi-plus"></i>
														</div>
													</div>

													<button class="flex-c-m stext-101 cl0 size-101 p-lr-15 trans-04 js-addcart-detail add_to_cart_btn" data-pid='#_<?php echo $product->id?>'>
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
			<?php endforeach; ?>
		</div>
	</section>
<?php } ?>

<?php include 'core/inc/footer.php' ?>
