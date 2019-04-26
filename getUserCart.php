<?php
########################################
#======== Grab User Cart Data =======
########################################
include 'core/inc/init.php';
// unset($_SESSION['user_id']);

$everything_is_ok = false; // initialize everything

#####  If user is logged in set user id else set the user ip means he is a guest here  #####
if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])):
	$realUser = $_SESSION['user_id'];
else:
	$realUser = $user_ip;
endif;

###### Check if the user_id that comes from ajax call is the real user id or the guest ip ######
if(isset($_POST['user_id']) && !empty($_POST['user_id'])):
	$user_id = $_POST['user_id'];
	if($user_id == $realUser):
		$everything_is_ok = true;
	else:
		$everything_is_ok = false;
	endif;

else:
	$everything_is_ok = false;
endif;

 #######  IF everything is okay get all items from the real user cart  ######
if($everything_is_ok):
   # check if the user id is an ip (mean he's a guest)
   # then grab the user cart from the session else grab it from DB
	if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])){
		$user_cart = $getFromC->get_user_cart($_SESSION['user_id']);
	}else if($user_id = $user_ip){
		if(isset($_SESSION[$user_ip]))
			$user_cart = $_SESSION[$user_ip];
		else
			$user_cart = '';
	}else{
		$user_cart = '';
	}
 else:
	echo $everything_is_ok;
	exit;
endif;
?>

<?php if(isset($user_cart) && is_array($user_cart) && !empty($user_cart)):
 $total_price = 0;
 $items_in_cart = 0;
?>
	<?php foreach($user_cart AS $cart):
		// get the product that customer wanna buy
		$product_wanna_buy = $getFromP->get_product_by('products.id', $cart['cart_to']);
		$product_img = explode(',', $product_wanna_buy->product_images);
		$total_price = $total_price + $cart['price'];
		$items_in_cart = $items_in_cart + $cart['quantity'];
	?>
			<li class="header-cart-item flex-w flex-t m-b-12">
				<div class="header-cart-item-img">
					<img src="<?php echo $productImagesPath.$product_img[0]?>" alt="IMG">
				</div>

				<div class="header-cart-item-txt p-t-8">
					<a href="product-detail.php?p=<?php echo str_replace(' ', '+', $product_wanna_buy->name)?>" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
						<?php echo $product_wanna_buy->name?>
					</a>

					<div class="header-cart-item-info">
						<?php echo $cart['quantity'].' x '.$product_wanna_buy->price?>
					</div>
				</div>
			</li>
	<?php endforeach; ?>

	<input type="hidden" value='<?php echo $items_in_cart ?>' id='cart_many'>
	<input type="hidden" value='<?php echo $total_price ?>' id='total_price'>
<?php else: ?>
	<div class="header-cart-item d-flex flex-column justify-content-center align-items-center">
		<img src="./images/empty-cart.png" width="120">
		<h4 class="text-muted text-center mt-1"> Your cart is empty </h4>
		<p class="text-center mt-3"> Come on ! fill it with some great deals </p>
	</div>
<?php endif; ?>
