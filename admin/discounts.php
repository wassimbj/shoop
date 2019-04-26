<?php
#################################################
# ============== DISCOUNTS PAGE ================
# ==> Manage, Add discounts to any product
#################################################
$inside_admin = true;
include '../core/inc/init.php';
if(!isset($_SESSION['admin_id'])){
	header('location: login.php');
	exit;
}
include '../core/inc/admin_header.php';
?>


<?php if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] == 'manage_discounts'):
######################################
# ====== MANAGE DISCOUNTS PAGE =========
######################################

// Delete discount
if(!empty($_POST['discount_id']) && isset($_POST['rem_discount'])){
	$getFromA->expire_discount($_POST['discount_id']);
	$getFromA->delete('discount', ['id' => $_POST['discount_id']]);
	success_msg('Nice! the discount was <b> successfully deleted </b>');  
}

// Edit/Update the discount
if(!empty($_POST['discount_amount']) && !empty($_POST['expire_after']))
{
	if(strtotime($_POST['expire_after']) > strtotime(date('Y-m-d'))){
		$d_id = $_POST['discount_id'];
		$discount_amount = $_POST['discount_amount'];
		$da_discount = $getFromA->getBy('discount', ['id' => $d_id]);
		$products_that_have_this_discount = $getFromA->getAll('products', 'where discount_id ='.$da_discount->discount_to.' ');
	    $type = $da_discount->amount > 0 ? 'amount': 'precent';
	  if(!empty($products_that_have_this_discount))
	  {
	  	// set the new price after the discount is applied
		foreach ($products_that_have_this_discount as $product) {
		 	if($type == 'precent')
		 		$new_price = $product->old_price - ($product->old_price * ($discount_amount / 100));
		 	else
		 		$new_price = $product->old_price - $discount_amount;
		  	$getFromA->update('products', 'id ='.$product->id.'', [
			 	'price' => $new_price,
			 ]);
		}

	  }
	  $expire_after =  strtotime($_POST['expire_after']);
	  $getFromA->update('discount', 'id ='.$d_id.'', [
	 	$type => $discount_amount,
		'expire_in' => $expire_after,
		'expired' => 0
	 ]);
	  success_msg('Nice! the discount was <b> successfully updated</b>');
	}else{
		error_msg('You choosed a date that is less the the current date('.date('Y-m-d').')');
	}
}

$discounts = $getFromA->getAll('discount');
?>

<div class="container">
	
	<h1 class="text-muted text-center pt-3 pb-4"> Manage Discounts </h1>

	<a href="./discounts.php" class="btn btn-primary btn-sm mb-3">Create one</a>

	<div class="table-responsive pb-5">
		<table class="table bg-white table-hover">
		  <thead>
		    <tr>
		      <th scope="col">ID</th>
		      <th scope="col">Discount</th>
		      <th scope="col">Type</th>
		      <th scope="col"> Applied for </th>
		      <th scope="col">Expire in</th>
		      <th scope="col">Expired</th>
		      <th scope="col">Created at</th>
		      <th></th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php if(empty($discounts)): ?>
  				<!-- <tr class="mb-4"> -->
  					<td width="10%">There is no discount applied yet</td>
  				<!-- </tr> -->
		  	<?php else: ?> 
	  		<?php foreach($discounts AS $discount):
		  		$products_applied_for = $getFromA->getAll('products', 'where discount_id ='.$discount->discount_to.'');
		  		$allProducts = $getFromA->getAll('products');
		  	?>
		   	  <tr>
			      <th scope="row"><?php echo $discount->id?></th>
			      <td>
			      	<button class="btn <?php echo $discount->expired == 0 ? 'btn-success': 'btn-danger'?> btn-sm">
			      		<?php
				      	 	  if($discount->precent > 0):
					      	  	echo $discount->precent.'%';
					      	  else:
					      	  	echo '$'.$discount->amount;
					      	  endif;
					      ?>
			      	</button>
			      </td>
			      <td>
		      		<?php
			      	 	  if($discount->precent > 0):
				      	  	echo 'by precent';
				      	  else:
				      	  	echo 'by amount';
				      	  endif;
				      ?>
			      </td>
			      <td>
			      	<?php
			      	  if(count($products_applied_for) == count($allProducts)){
			      	  	echo 'All products';
			      	  }else{
			      	  	echo count($products_applied_for).' products';
			      	  }
			      	?>
			      </td>
			      <td><?php echo date('Y-m-d', $discount->expire_in)?></td>
			      <td><?php echo $discount->expired  == 0 ? 'Not yet': '<p class="text-danger">Expired</p>'?></td>
			      <td><?php echo date('Y-m-d', strtotime($discount->created_at))?></td>
			      <td>
			      		<div class="table-data-feature">
                    <button class="item" data-toggle="modal" data-target='#edit_<?php echo $discount->id?>'>
                        <i class="zmdi zmdi-edit"></i>
                    </button>
                    <button type='button' class="item" data-toggle="modal" data-target='#delete_<?php echo $discount->id?>'>
                        <i class="zmdi zmdi-delete"></i>
                    </button>
                </div>
			      </td>
			      <td class="p-0">
 				      <!-- DELETE DISCOUNT MODAL -->
							<div class="modal fade" id="delete_<?php echo $discount->id?>" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
							  <div class="modal-dialog modal-dialog-centered" role="document">
							    <div class="modal-content">
							      <div class="modal-header">
							        <h5 class="modal-title" id="exampleModalLongTitle">Are you sure ?</h5>
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							          <span aria-hidden="true">&times;</span>
							        </button>
							      </div>
							     <form method="POST" action="./discounts.php?page=manage_discounts">
						     		 <div class="modal-body">
						        		Do you really wanna remove this discount ?
						        		<input type="hidden" name="discount_id" value="<?php echo $discount->id?>">
								      </div>
								      <div class="modal-footer">
								        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								        <button type="submit" class="btn btn-danger" name='rem_discount'>Yes remove</button>
								      </div>
							     </form>
							    </div>
							  </div>
							</div>
							<!-- DELETE DISCOUNT MODAL -->
		     		</td>
		     		<td class="p-0">
 				         <!-- EDIT DISCOUNT MODAL -->
						<div class="modal fade" id="edit_<?php echo $discount->id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
						  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <h5 class="modal-title" id="exampleModalLongTitle">
						        	Edit Discount No <b>#<?php echo $discount->id?></b>
						        </h5>
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						          <span aria-hidden="true">&times;</span>
						        </button>
						      </div>
						     <form method="POST" action="./discounts.php?page=manage_discounts">
						     	<input type="hidden" name="discount_id" value="<?php echo $discount->id?>">
					     		 <div class="modal-body">
								      <div class="col form-group">
									    <label> The discount </label>
									    <div class="input-group">
									        <div class="input-group-prepend">
									          <span class="input-group-text d_type_logo">
									          	<?php echo $discount->amount > 0 ? '$': '%'?>
									          </span>
									        </div>
								           <input type="number" name='discount_amount' class="form-control" value="<?php echo $discount->amount > 0 ? $discount->amount: $discount->precent?>" placeholder="e.g: 50, 12...">
								        </div>
									  </div>
									  <div class="col form-group">
									    <label> expire after </label>
									    <div class="input-group">
								           <input type="date" name='expire_after' class="form-control" value="<?php echo date('Y-m-d', $discount->expire_in)?>">
								        </div>
									  </div>
							      </div>
							      <div class="modal-footer">
							        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							        <button type="submit" class="btn btn-success">Save changes</button>
							      </div>
						     </form>
						    </div>
						  </div>
						</div>
						<!-- EDIT DISCOUNT MODAL -->
	     			</td>
			    </tr>
		  	<?php endforeach; ?>
		  	<?php endif; ?>
		  </tbody>
		</table>
	</div>

</div>


<?php else:
######################################
# ====== ADD DISCOUNTS PAGE =========
######################################

	$error = array();
if(isset($_POST['discount']) && isset($_POST['expire_in'])):
	$discount_id = time();
	$type = $_POST['type'];
	$discount_amount = $_POST['discount'];
	$expire_in = $_POST['expire_in'];

		if(!isset($_POST['discount_to']) && empty($_POST['discount_to']))
			$error[] = 'You forgot to check the products !';
		else
			$discount_applied_to = $_POST['discount_to'];

		if(!in_array($type, ['precent', 'amount']))
			$error[] = 'Precent and amount are the allowed discount types';
		if(empty($discount_amount))
			$error[] = 'You forgot to enter the discount';
		if(empty($expire_in))
			$error[] = 'Please enter on how many days will this discount expire';
		elseif(strtotime($expire_in) < strtotime(date('Y-m-d')))
			$error[] = 'You choosed a date that is less then the current date';

		// get the product that will be updated
		if(empty($error))
		{
			 foreach ($discount_applied_to as $p_id)
			 {
			 	$p = $getFromA->getBy('products', ['id' => $p_id]);
			 	// get the original price
			 	$original_price = $p->old_price > 0 ? $p->old_price : $p->price;
			 	// set the new price after the discount is applied
			 	if($type == 'precent')
			 		// $new_price = $p->price * $discount_amount / 100;
			 		$new_price = $original_price - ($original_price * ($discount_amount / 100));
			 	else
			 		$new_price = $original_price - $discount_amount;
			 	
				 	if($new_price < 0 || $new_price == 0)
				 	{
				 		$error[0] = 'Oops! you puted discount that <b> made a product price "$0.00" </b>';
				 	}else{
			   			$getFromA->update('products', 'id ='.$p_id.'', [
						 		'old_price' => $original_price,
						 		'price' => $new_price,
						 		'discount_id' => $discount_id
						 	]);
				 	}
			 }// end foreach
			// create the discount
			 $getFromA->create('discount', [
			 	$type => $discount_amount,
			 	'discount_to' => $discount_id,
			 	'expire_in' => strtotime($expire_in),
			 	'expired' => 0,
			 	'created_at' => date('Y-m-d') 
			 ]);

			$success ='<b>Well done !</b> the discount was successfully created';
		}
endif;

// get all products
$products = $getFromP->get_products();

?>
<div class="container">
	
	<h1 class="text-muted text-center pt-3"> Add Discounts </h1>
	<?php
		if(!empty($error))
			foreach ($error as $err) {
	 		   error_msg($err);
			}
		elseif(isset($success) && !empty($success) && empty($error))
			success_msg($success);
	?>
	<div class="pt-5">
		<?php info_msg('All the discounts you will make, <b> will be affected to the original price that you set from the first time ofc ! </b>') ?>
		<form method="POST" action="./discounts.php">
			<div class="row mb-4">
				<div class="col">
				  <label> Choose the discount type </label>
				  <select class="custom-select" id='discount_type' name="type">
					 <option selected value="precent"> By precent </option>
					 <option value="amount"> By amount </option>
				  </select>
			   </div>
			  <div class="col">
			    <label> The discount </label>
			    <div class="input-group">
			        <div class="input-group-prepend">
			          <span class="input-group-text d_type_logo">%</span>
			        </div>
		           <input type="number" name='discount' class="form-control" placeholder="e.g: 50, 12...">
		        </div>
			  </div>
			</div>

		  <div class="form-group">
		    <label>When do you want this discount to expire ?</label>
			   <div class="input-group">
			      <!-- <div class="input-group-prepend">
			         <span class="input-group-text">expire after: </span>
			      </div> -->
		          <input type="date" class="form-control" name='expire_in'>
		          <!-- <div class="input-group-prepend">
			         <span class="input-group-text">Days</span>
			      </div> -->
		      </div>
		      <!-- <small>1 year = 365 day, 1 month = 30 day</small> -->
		  </div>

		  	<div class="form-group">
	  		 <mark> Please check the products that you want this discount to be applied to ! </mark>
		 	 <div class='mt-2 table-responsive' style="height: 500px; overflow-y: scroll; border: 1px solid rgba(0,0,0,0.25)">
		  		<table class="table table-hover">
				  <thead>
				    <tr>
				      <th width="5%">
				      	 <div class="custom-control custom-checkbox">
						  <input type="checkbox" name='check_all' class="custom-control-input" id="check_all">
						  <label class="custom-control-label" for="check_all">check all</label>
						</div>
				      </th>
				      <th width="50%">name</th>
				      <th width="35%">photo</th>
				      <th width="5%">original price</th>
				      <th width="5%">after discount</th>
				      <th width="5%">applied discount</th>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php foreach($products AS $product):
							   $product_img = explode(',', $product->product_images);
							   $p_discount = $getFromA->getBy('discount', ['discount_to' => $product->discount_id]);
					?>
				    <tr>
				      <th>
				        <div class="custom-control custom-checkbox">
							  <input type="checkbox"  value='<?php echo $product->id?>' name='discount_to[]' class="apply_discount_to custom-control-input" id="check_<?php echo $product->id?>">
							  <label class="custom-control-label" for="check_<?php echo $product->id?>">check</label>
							</div>
					     </th>
				      <td><?php echo $product->name ?></td>
				      <td>
				       <img src="<?php echo $productImagesPath.$product_img[1]?>" alt="product image" height='100px' width='150px'>
				      </td>
				      <td>
				      	<b class="stext-105 cl5">
							<?php
							 if($product->old_price > 0 && $product->discount_id > 0):
							 	 echo '$'.$product->old_price;
							 else:
								 echo '$'.$product->price;
							 endif
							?>
						</b>
				      </td>
				      <td>
				      	<?php
								 if($product->old_price > 0 && $product->discount_id > 0):
								    echo '<span class="text-danger"> $'.$product->price.'</span>';
								 else:
								 	echo 'Nothing, still no discount yet';
								 endif
								?>
				      </td>
				      <td >
				      	<?php
				      	 if(!empty($p_discount))
				      	 {
			      	 	  if($p_discount->precent > 0):
				      	  	echo '-'.$p_discount->precent.'%';
				      	  else:
				      	  	echo '- $'.$p_discount->amount;
				      	  endif;
				      	 }else{
				      	 	echo 'No discount was applied yet..';
				      	 }
				      	?>
				      </td>
				    </tr>
				    <?php endforeach;?>
				  </tbody>
				</table>
			  </div>
		  	</div>
		  <button type="submit" class="btn btn-primary w-100 mt-5 mb-5">add discount</button>
		</form>
	</div>
</div>
<?php endif; ?>






<?php include '../core/inc/admin_footer.php';?>