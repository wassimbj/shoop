<?php
	$user_ip = getUserIP();
	if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])):
		$user_id = $_SESSION['user_id'];
	    $customer = $getFromU->get_user_by('id', $user_id, ['name', 'email']);
	    $user_role = 'logged';
	else:
		$user_id = $user_ip;
		$user_role = 'guest';
	endif;


// Get all discounts
 $discounts = $getFromA->getAll('discount',
 	'where expire_in = '.strtotime(date('Y-m-d')).' and expired = 0'
 );
  foreach ($discounts as $discount){
  	  $getFromA->expire_discount($discount->id);
	  $getFromA->update('discount', 'id ='.$discount->id.'', ['expired' => 1]);
   }

// get all categorys that admin want them to appear with limit
 $categorys = $getFromA->getAll('categories', 'where appear = 1');

 // Get the 3 first new products
 $new_products = $getFromP->get_products('products.image=i.connector', 'order by created_at desc limit 3');
?>
<!-- Header -->
	<header class="header-v4">
		<!-- Header desktop -->
		<div class="container-menu-desktop">
			<!-- Topbar -->
			<div class="top-bar">
				<div class="content-topbar flex-sb-m h-full container">
					<div class="left-top-bar flex-w h-full">
						<a href="./contact-us" class="flex-c-m trans-04 p-lr-25">
							Contact
						</a>
						<a href="./about-us" class="flex-c-m trans-04 p-lr-25">
							About
						</a>
					</div>

					<div class="right-top-bar flex-w h-full">
						<a href="#" class="flex-c-m trans-04 p-lr-25">
							EN
						</a>

						<a href="#" class="flex-c-m trans-04 p-lr-25">
							USD
						</a>
					</div>
				</div>
			</div>

			<div class="wrap-menu-desktop how-shadow1">
				<nav class="limiter-menu-desktop container justify-content-between">
					
					
					<!-- Menu desktop -->
					<div class="menu-desktop w-100">
						<ul class="main-menu">
							<li>
								<a href="./home">Home</a>
							</li>

							<li>
								<a href="./shop">Shop</a>
							</li>
							<li id='collection'>
								<a>Collection</a>
							</li>
						</ul>
					</div>

				<div class='mega_menu'>
				  <!-- <div class="d-flex flex-wrap justify-content-between"> -->
				    <div class="d-flex flex-wrap panel-group">
				  	<?php foreach($categorys AS $cate):
				  		$sub_c = $getFromA->getAll('sub_category', 'where parent ='.$cate->id.'');
				  	?>
					  	<div class="megamenu_panel">
					      <div class="panel-heading" role="tab" id="heading_<?php echo $cate->id?>">
					        <p class="panel-title">
					         <b role="button" data-toggle="collapse" aria-expanded="true" aria-controls="collapseOne">
					          <?php echo $cate->name ?>
					        </b>
					       </p>
					      </div>
					      <div class="panel-collapse in show" role="tabpanel" aria-labelledby="heading_<?php echo $cate->id?>">
					        <div class="panel-body pb-3">
							  <ul>
								<li><a href="shop?cate=<?php echo $cate->name ?>">All</a></li>
								<?php foreach($sub_c AS $sub): ?>
								  <li><a href="shop?subc=<?php echo $sub->name ?>"><?php echo $sub->name ?></a></li>
								<?php endforeach; ?>
							  </ul>
					        </div>
					      </div>
					    </div>
			  	    <?php endforeach; ?>
				   </div>
				  <!-- </div> -->
				</div>


					<!-- Logo desktop -->		
					<a href="./shop" class="logo w-100 mr-0 justify-content-center text-dark">
						<img id='desk-logo' src="<?php echo $shopfrontPath.$shop_front[0]->logo?>">
					</a>

					<!-- Icon header -->
					<div class="wrap-icon-header flex-r-m w-100">
						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-10 p-r-11 js-show-modal-search">
							<img src="./images/icons/search.png" width="25">
						</div>

						<!-- cart icon -->
						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-10 p-r-11 icon-header-noti js-show-cart cart-notify">
							<img src="./images/icons/cart.png" width='28'>
						</div>
						<!-- user icon -->
						<div class="btn-group">
						  <button type="button" class="dropdown-toggle icon-header-item cl2 hov-cl1 trans-04 p-l-10 p-r-11" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <img src="./images/icons/user.png" width="25">
						  </button>
						  <div class="dropdown-menu">
						    <?php if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])): ?>
						    	<a class="dropdown-item" href="./profile">Profile</a>
							    <a class="dropdown-item" href="./profile#orders">Orders</a>
							    <a class="dropdown-item" href="./profile#wishlist">Wishlist</a>
							    <div class="dropdown-divider"></div>
							    <a class="dropdown-item" href="logout.php">Logout</a>
						    <?php else: ?>
							    <a class="dropdown-item" href="./join">Join</a>
							    <div class="dropdown-divider"></div>
						    	<a class="dropdown-item" href="./login">Login</a>
						    <?php endif; ?>
						  </div>
						</div>

					</div>
				</nav>
			</div>	
		</div>

		<!-- Header Mobile -->
		<div class="wrap-header-mobile">
			<!-- Logo moblie -->		
			<a href="./shop" class="logo-mobile text-dark">
				<img src="<?php echo $shopfrontPath.$shop_front[0]->logo?>">
			</a>

			<!-- Icon header -->
			<div class="wrap-icon-header flex-r-m m-r-15">
				<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
					<img src="./images/icons/search.png" width="23">
				</div>
				<!-- cart notify -->
				<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart cart-notify">
					<img src="./images/icons/cart.png" width='25'>
				</div>
				<!-- User icon -->
				<div class="btn-group">
				  <button type="button" class="dropdown-toggle icon-header-item cl2 hov-cl1 trans-04 p-l-10 p-r-11" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <img src="./images/icons/user.png" width="23">
				  </button>
				     <div class="dropdown-menu">
					    <?php if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])): ?>
					    	<a class="dropdown-item" href="./profile">Profile</a>
						    <a class="dropdown-item" href="./profile#orders">Orders</a>
						    <a class="dropdown-item" href="./profile#wishlist">Wishlist</a>
						    <div class="dropdown-divider"></div>
						    <a class="dropdown-item" href="logout.php">Logout</a>
					    <?php else: ?>
						    <a class="dropdown-item" href="./join">Join</a>
						    <div class="dropdown-divider"></div>
					    	<a class="dropdown-item" href="./login">Login</a>
					    <?php endif; ?>
					 </div>
				</div>
			</div>

			<div class="flex-c-m h-full">
				<div class="icon-header-item hov-cl1 trans-04 p-lr-11 js-show-sidebar">
					<i class="zmdi zmdi-menu"></i>
				</div>
			</div>
		</div>


		<!-- Menu Mobile -->
		<aside class="wrap-sidebar js-sidebar">
		<div class="s-full js-hide-sidebar"></div>

		<div class="sidebar flex-col-l p-t-22 p-b-25">
			<div class="d-flex align-items-center justify-content-between w-full p-b-30 p-r-27">
				 <?php if(isset($_SESSION['user_id'])):
				 	$firstname = substr($customer->name, 0, strpos($customer->name, ' '));
				 ?>
				 	<b class="pl-3"> Heyy ! <?php echo $firstname?> </b>
				 <?php else: ?>
				 	<b class="pl-3"> Hey ! guest </b>
				 <?php endif;?>
				<div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-close trans-04 js-hide-sidebar">
					<i class="zmdi zmdi-close"></i>
				</div>
			</div>

			<div class="sidebar-content flex-w w-full p-lr-65 js-pscroll ps ps--active-y" style="position: relative; overflow: hidden;">
				<ul class="sidebar-link w-full">
					<li class="p-b-13">
						<a href="./shop" class="stext-102 cl2 hov-cl1 trans-04">
							Shop
						</a>
					</li>
					<li class="p-b-13">
						<a href="./home" class="stext-102 cl2 hov-cl1 trans-04">
							Home
						</a>
					</li>
					<!--  -->
					<li>
					  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					  	<?php foreach($categorys AS $cate):
				  		$sub_c = $getFromA->getAll('sub_category', 'where parent ='.$cate->id.'');
				  	?>
					  	<div class="panel panel-default">
					      <div class="panel-heading" role="tab" id="headingOne">
					        <p class="panel-title">
					         <span role="button" data-toggle="collapse" data-parent="#accordion" href="#coll_<?php echo $cate->id?>" aria-expanded="false" aria-controls="collapseOne">
					          <?php echo $cate->name ?>
					        </span>
					       </p>
					      </div>
					      <div id="coll_<?php echo $cate->id?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
					        <div class="panel-body pb-3">
							  <ul>
								<li><a href="shop?cate=<?php echo $cate->name ?>">All</a></li>
								<?php foreach($sub_c AS $sub): ?>
								  <li><a href="shop?subc=<?php echo $sub->name ?>"><?php echo $sub->name ?></a></li>
								<?php endforeach; ?>
							  </ul>
					        </div>
					      </div>
					    </div>
				  	<?php endforeach; ?>
					  </div>
					</li>
					<!--  -->
					<hr>
					<li class="p-b-13">
						<a href="./about-us" class="stext-102 cl2 hov-cl1 trans-04">
							About us
						</a>
					</li>
					<li class="p-b-13">
						<a href="./contact-us" class="stext-102 cl2 hov-cl1 trans-04">
							Contact us
						</a>
					</li>

			   </ul>
			<div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 653px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 533px;"></div></div></div>
		</div>
	</aside>

		<!-- Modal Search -->
		<div class="modal-search-header js-hide-modal-search">
			<div class='flex-c-m search_bar'>
				<div class="container-search-header">
					<button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
						<i class="zmdi zmdi-close"></i>
					</button>

					<form class="wrap-search-header flex-w p-l-15" method="GET" action="./search.php">
						<button class="flex-c-m trans-04">
							<img src="./images/icons/search.png" width="35">
						</button>
						<input class="plh3" type="search" name="search" placeholder="Shopping for...">
					</form>
					<p class="text-white"> Hit enter to search </p>
				</div>
			</div>
		</div>
	</header>

	<!-- Cart -->
	<div class="wrap-header-cart js-panel-cart">
		<div class="s-full js-hide-cart"></div>

		<div class="header-cart flex-col-l p-l-65 p-r-25">
			<div class="header-cart-title flex-w flex-sb-m p-b-8">
				<span class="mtext-103 cl2">
					Your Cart
				</span>

				<div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-close trans-04 js-hide-cart">
					<i class="zmdi zmdi-close"></i>
				</div>
			</div>
			
			<div class="header-cart-content flex-w js-pscroll">
				<ul class="header-cart-wrapitem w-full" id='cart_items'>
					<!-- user cart items -->
				</ul>
				
				<div class="w-full">
					<div class="header-cart-total w-full p-tb-40" id='total'>
						<!-- user cart items total price -->
					</div>

					<div class="header-cart-buttons flex-w w-full">
						<a href="shoping-cart" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
							View Cart
						</a>

						<a href="shoping-cart" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
							Check Out
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- this just to get the user id, it doesnt have any relation to the security of the site dont worry -->
<input type="hidden" id='user' value='<?php echo $user_id?>' disabled>
<input type="hidden" id='user_role' value="<?php echo $user_role?>">