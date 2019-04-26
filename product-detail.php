<?php
	include 'core/inc/init.php';
	include 'core/inc/header.php';
	include 'core/inc/navbar.php';

	if(isset($_GET['p']) && !empty($_GET['p'])): // p = product name
		$p = str_replace('+', ' ', $_GET['p']);
		$product = $getFromP->get_product_by('name', $p);
		// if there is no product with the name was given in the GET redirect him to the shop
		if($product->id == null){
			header('location: product.php');
			exit();
		// else if there is a product do this...
		}else{
			$product_imgs = explode(',', $product->product_images); // get product images array
			$product_sizes = json_decode($product->size); // get product sizes array
			$product_colors = json_decode($product->color); // get product colors array
			// ### get related products depending on this product information ###
			$related_products = $getFromP->related_products($product->category,$product->id);
		}
	else:
		header('location: product.php');
		exit();
	endif;
?>
	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<div class="mr-2"><i class="fas fa-tags text-muted"></i></div>
			<a href="./shop" class="stext-109 cl8 hov-cl1 trans-04">
				Shop
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<a href="./shop?cate=<?php echo $product->category?>" class="stext-109 cl8 hov-cl1 trans-04">
				<?php echo $product->category?>
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				<?php echo $product->name?>
			</span>
		</div>
	</div>
		

	<!-- Product Detail -->
	<section class="sec-product-detail bg0 p-t-65 p-b-60">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-lg-7 p-b-30">
					<div class="p-l-25 p-r-30 p-lr-0-lg">
						<div class="wrap-slick3 flex-sb flex-w">
							<div class="wrap-slick3-dots"></div>
							<div class="wrap-slick3-arrows flex-sb-m flex-w"></div>

							<div class="slick3 gallery-lb">
							 <?php foreach($product_imgs AS $img): ?>
						 		<div class="item-slick3" data-thumb="<?php echo $productImagesPath.$img?>">
									<div class="wrap-pic-w pos-relative">
										<span
												style="background: url('<?php echo$productImagesPath. $img?>');
														background-position: center !important;
														background-size: cover !important;
														background-repeat: no-repeat !important;
														height: 500px;
														display: block;
														">
										</span>
										<a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="<?php echo $productImagesPath.$img ?>">
											<i class="fa fa-expand"></i>
										</a>
									</div>
								</div>
							 <?php endforeach;  ?>
							</div><!-- end of slider -->
						</div>
					</div>
				</div>
					
				<div class="col-md-6 col-lg-5 p-b-30" id='_<?php echo $product->id?>'>
				<input type="hidden" value="<?php echo $product->id ?>" id='p_id' disabled>
			
					<div class="p-r-50 p-t-5 p-lr-0-lg">
						<h4 class="mtext-105 cl2 js-name-detail p-b-14">
							<?php echo $product->name ?>
						</h4>

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

						<p class="stext-102 cl3 p-t-23">
							<?php echo short_text($product->description, 300) ?>
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

										<input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product" id='qntt' data-max='<?php echo $product->quantity ?>' value="1">

										<div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
											<i class="fs-16 zmdi zmdi-plus"></i>
										</div>
									</div>

								 <div class="d-flex">
							 		<button class="flex-c-m stext-101 cl0 size-101 p-lr-15 trans-04 js-addcart-detail add_to_cart_btn" data-pid='#_<?php echo $product->id?>'>
										Add to cart
									</button>
									<div class="block2-txt-child2 d-block text-center flex-r">
										<?php
										if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
										 	$wish_to = $getFromA->getBy('wishlist', ['wish_to' => $product->id, 'wish_by' => $_SESSION['user_id']]);
										if(isset($wish_to) && !empty($wish_to)): ?>
										  <button type="button" class="btn-addwish-b2 d-flex btn btn-dark h-100 ml-2 text-danger dis-block pos-relative js-addwish-b2" title='remove from wishlist' data-action='remove' data-id='<?php echo $product->id?>'>
												<i class="fas fa-heart"></i>
										  </button>
										<?php else: ?>
										  <button type="button" class="btn-addwish-b2 d-flex btn btn-dark h-100 ml-2 dis-block pos-relative js-addwish-b2" title='add to wishlist' data-action='add' data-id='<?php echo $product->id?>'>
											<i class="far fa-heart"></i>
										  </button>
										<?php endif; ?>
									</div>
								 </div>
								</div>
							</div>	
						</div>

						<!-- social media share -->
						<!-- <div class="flex-w flex-m p-l-100 p-t-40 respon7">
							<div class="flex-m bor9 p-r-10 m-r-11">
								<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100" data-tooltip="Add to Wishlist">
									<i class="zmdi zmdi-favorite"></i>
								</a>
							</div>

							<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Facebook">
								<i class="fa fa-facebook"></i>
							</a>

							<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Twitter">
								<i class="fa fa-twitter"></i>
							</a>

							<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Google Plus">
								<i class="fa fa-google-plus"></i>
							</a>
						</div> -->
					</div>
				</div>
			</div>

			<!-- product information -->
			<div class="bor10 m-t-50 p-t-43 p-b-40">
				<!-- Tab01 -->
				<div class="tab01">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item p-b-10">
							<a class="nav-link active" data-toggle="tab" href="#description" role="tab">Description</a>
						</li>

						<li class="nav-item p-b-10">
							<a class="nav-link" data-toggle="tab" href="#information" role="tab">Additional information</a>
						</li>

						<!-- <li class="nav-item p-b-10">
							<a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Reviews (1)</a>
						</li> -->
					</ul>

					<!-- Tab panes -->
					<div class="tab-content p-t-43">
						<!-- - -->
						<div class="tab-pane fade show active" id="description" role="tabpanel">
							<div class="how-pos2 p-lr-15-md">
								<p class="stext-102 cl6">
									<?php echo $product->description; ?>
								</p>
							</div>
						</div>

						<!-- Product information -->
						<div class="tab-pane fade" id="information" role="tabpanel">
							<div class="row">
								<div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
									<ul class="p-lr-28 p-lr-15-sm">
										<li class="flex-w flex-t p-b-7">
											<span class="stext-102 cl3 size-205">
												Price
											</span>

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
										</li>

										<li class="flex-w flex-t p-b-7">
											<span class="stext-102 cl3 size-205">
												Materials
											</span>

											<span class="stext-102 cl6 size-206">
												<?php echo $product->materials; ?>
											</span>
										</li>

										<li class="flex-w flex-t p-b-7">
											<span class="stext-102 cl3 size-205">
												Color
											</span>

											<span class="stext-102 cl6 size-206">
												<?php foreach($product_colors AS $color): ?>
													 <?php echo $color.', '?>
												<?php endforeach; ?>
											</span>
										</li>

										<li class="flex-w flex-t p-b-7">
											<span class="stext-102 cl3 size-205">
												Size
											</span>

											<span class="stext-102 cl6 size-206">
												<?php foreach($product_sizes AS $size): ?>
													 <?php echo $size.', '?>
												<?php endforeach; ?>
											</span>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


	
	<!-- Related Products -->
	<?php if(isset($related_products) && !empty($related_products)): ?>
		<section class="sec-relate-product bg0 p-t-45 p-b-105">
			<div class="container">
				<div class="p-b-45">
					<h3 class="ltext-106 cl5 txt-center">
						Related products
					</h3>
				</div>

				<!-- Slide2 -->
				<?php if( isset($related_products) && (is_array($related_products) || is_object($related_products)) ): ?>
				<div class="wrap-slick2">
					<div class="slick2">
					<?php 
						foreach($related_products AS $p):
						 // $p = $product (just for shortness)
						 $related_p_imgs = explode(',', $p->product_images); // get product images array
						 $related_p_sizes = json_decode($p->size); // get product sizes array
						 $related_p_colors = json_decode($p->color); // get product colors array
							// echo '<pre>',print_r($p),'</pre>';
						 $p_discount = $getFromA->getBy('discount', ['discount_to' => $product->discount_id, 'expired' => 0]);
					 ?>

						<div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
							<!-- Block2 -->
							<div class="block2">
								<div class="block2-pic hov-img0">
								<?php if(!empty($p_discount) && $p_discount->precent > 0): ?>
									<div class="discount"><?php echo $p_discount->precent.'%'?></div>
								<?php endif; ?>
									<span
								      	style="background: url('<?php echo $productImagesPath.$related_p_imgs[0]?>');
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
											<div class="d-flex ">
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
					<?php foreach($related_products AS $product):
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

												<!-- social media share -->
											<!-- 	<div class="flex-w flex-m p-l-100 p-t-40 respon7">
													<div class="flex-m bor9 p-r-10 m-r-11">
														<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100" data-tooltip="Add to Wishlist">
															<i class="zmdi zmdi-favorite"></i>
														</a>
													</div>

													<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Facebook">
														<i class="fa fa-facebook"></i>
													</a>
													<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Twitter">
														<i class="fa fa-twitter"></i>
													</a>

													<a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Google Plus">
														<i class="fa fa-google-plus"></i>
													</a>
												</div> -->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					 <!-- END of the modal -->
					<?php endforeach; ?>
				<?php endif; ?>


			</div>
		</section>
	<?php endif; ?>

	<!-- Back to top -->
	<div class="btn-back-to-top" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="zmdi zmdi-chevron-up"></i>
		</span>
	</div>
	
 <?php include 'core/inc/footer.php' ?>
