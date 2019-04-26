<?php
// ########################################
// ============= ADD TO CART =============
// ########################################
include 'core/inc/init.php';

#####  If user is logged in set user id else set the user ip means he is a guest here  #####
if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])):
	$realUser = $_SESSION['user_id'];
else:
	$realUser = $user_ip;
endif;

if(isset($_POST['size']) && isset($_POST['color']) && isset($_POST['qntt'])
  && isset($_POST['product_id']) && isset($_POST['user_id'])):
	if($_POST['user_id'] == $realUser){
		$size = $_POST['size'];
		$color = $_POST['color'];
		$qntt = $_POST['qntt'];
		$product_id = $_POST['product_id'];
		$get_product = $getFromP->get_product_by('products.id', $product_id);
		// check if there is a product with the given name
		if(!is_null($get_product->id)){
		  // check if user already have this product in his cart
			$there_is_product = $getFromC->there_is_product_in_cart($get_product->id, @$_SESSION['user_id']);
			$cond = isset($_SESSION['user_id']) ? !$there_is_product : empty($_SESSION[$user_ip][$product_id]);
			if($cond){
				// check if the given size, color and qntt exist in the current product
				$product_available_sizes = json_decode($get_product->size);
				$product_available_colors = json_decode($get_product->color);
					if(!in_array($size, $product_available_sizes)){
						echo 'The size you selected is no longer available or not on the list';
						exit;
					}elseif(!in_array($color, $product_available_colors)){
						echo 'The color you selected is no longer available or not on the list';
						exit;
					}elseif($qntt > $get_product->quantity){
						echo 'You choosed a quantity that is not in the stock';
						exit;
					}else{
						if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])):
							  // if user is logged in store the product in the DB
								$user_id = $_SESSION['user_id'];
							  	$getFromA->create('cart', [
									'cart_by' => $user_id,
									'cart_to' => $get_product->id,
									'size' => $size, 'color' => $color, 'quantity' => $qntt,
									'price' => $get_product->price * $qntt
								]);
								echo 1;
						else:
						 // else if he is not logged in or he is a guest store it a session
							$_SESSION[$user_ip][$product_id] = [
								'cart_by' => $user_ip,
								'cart_to' => $get_product->id,
								'size' => $size,
								'color' => $color,
								'quantity' => $qntt,
								'price' => $get_product->price * $qntt
							  ];
							echo 1;
						endif;
					}
				}else{
					echo 'this product is already in you cart'; //end of checking if there is already a product in cart
					exit;
				}

		}else{
			 echo 'The product that you want to add is maybe no longer available or doesnt exist';// end of checking if product exists
			 exit;
		}
	}else{
		echo 'Something went wrong, please try again later'; // end of checking real user
		exit;
	}

else:
	 echo 'Sorry, no values was sent'; // end of checking is set values
	 exit;
endif;


?>
