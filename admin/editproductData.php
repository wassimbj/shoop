<?php
###########################################
# ======== The product filter page ======
###########################################
$inside_admin = true;
include('../core/inc/init.php');
$sizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL', '4XL','5Xl'];
$colors = [ 
			'grey' => '#999999', 
			'black' => '#000000',
			'white' => '#ffffff', 
			'blue' => '#033fff', 
			'red' => '#fd0054',
			'yellow' => '#f9ff21', 
			'purple' => '#503bff',
			'green' => '#080',
			'orange' => '#ff9234',
			'brown' => '#aa7243',
			];
$cates = $getFromA->getAll('categories', '', 'order by created_at');

if(isset($_POST['search'])){
	$search = $_POST['search'];
	$products = $getFromA->search_for_product($search);
}else{
	$page = isset($_POST['page']) ? $_POST['page']: 1;
	$records_per_page = isset($_POST['records_per_page']) ? $_POST['records_per_page']: 5;
	$start_from = ($page - 1) * $records_per_page;
	 // Get the products with the condtions: page & records
	$products = $getFromA->get_with_limit('products', $start_from, $records_per_page);
}


// Loop through the products
if(is_array($products) && !empty($products)):
  $i = 0;
  foreach($products AS $product):
	$i = $i + 1;
	$product_imgs = $getFromA->getAll('pimages', 'where pimages.connector = '.$product->image);
	$product_sizes = json_decode($product->size);
	$product_colors = json_decode($product->color);
?>
    
<tr>
    <td><?php echo $i?></td>
    <td>
    	<img src='<?php echo $productImagesPath.$product_imgs[0]->image?>' class="p_avatar" alt="product_IMG"> 
    	<?php echo $product->name ?>
    </td>
	<td><?php echo $product->category ?></td>
    <td><?php echo time_ago($product->created_at) ?></td>                        
	<td><?php echo '$'.$product->price ?></td>
	<td>
		<div class="d-flex justify-content-between">
			<button class="edit" data-toggle='modal' data-target="#edit_<?php echo $product->id?>" title="Edit"><i class="fas fa-pen"></i></button>
			<button class="delete" data-toggle='modal' data-target="#delete_<?php echo $product->id?>" title="Delete"><i class="fas fa-trash"></i></button>
		</div>
	</td>
    <!-- ######### Delete product Modal ######## -->
   <td class="p-0"> 
     <div class="modal fade" id="delete_<?php echo $product->id?>">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLongTitle">Are you sure ?</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	     	<form method="POST">
     		   <div class="modal-body">
		         Do you really want to remove this product ?,
		         <input type="hidden" name="product_id" value='<?php echo $product->id?>'>
		      </div>
	           <div class="modal-footer">
	             <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
	             <button type="submit" class="btn btn-primary" name='delete_product'>Yes remove</button>
	          </div>
	     	</form>
	      </div>
	    </div>
	  </div>
  </td>
  <!-- ######### Edit Product Modal ######### -->
  <td class="p-0">
  	  <div class="modal fade" id="edit_<?php echo $product->id?>">
	     <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
	       <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Edit product "<?php echo $product->name?>"</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
	      <div class="modal-body">
	      	<code class="text-center text-muted d-block mb-2">
	      		If you want to <mark> delete some images </mark> just check what you want to delete and hit save changes.
	      	</code>
	        <form method="POST" enctype="multipart/form-data">
	            <input type="hidden" name='p_id' value="<?php echo $product->id?>">
		              <div class="d-flex justify-content-center flex-wrap file-field input-field w-100">
		                  <?php foreach($product_imgs AS $image): ?>
		                    <div class="d-flex flex-column">
		                      <div class="position-relative item_image_to_edit">
		                        <img src='<?php echo $productImagesPath.$image->image?>' style='height: 135px !important; width: 120px !important'>
		                        <input type="file" title='Change image' name='image_edit[<?php echo $image->id?>]' class='edit_imgs_input'>
		                        <span class='edit_image_overlay'>
		                          <i class="fas fa-camera text-white"></i>
		                          <p>Change</p>
		                        </span>
		                    </div>
		                    <div class="custom-control custom-checkbox mb-3 text-center">
							  <input type="checkbox" name='img_delete[]' class="custom-control-input" id="<?php echo $image->id ?>" value='<?php echo $image->id?>'>
							  <label class="custom-control-label d-flex align-items-center" for="<?php echo $image->id ?>">
							  	Delete
							  </label>
							</div>
		                    </div>
		                  <?php endforeach; ?>
	                </div>

					  <div class="form-group">
					    <label>Product name</label>
					    <input type="text" name='name' class="form-control" value='<?php echo $product->name?>' required>
					  </div>

					 <div class="form-group" id='cate_<?php echo $product->id?>'>
					 	<label>Category</label>
						 <select class="custom-select" name='category' >
							 <?php foreach($cates AS $cate): ?>
								 <option value="<?php echo $cate->name?>" id='<?php echo $cate->name?>'><?php echo $cate->name?></option>
							 <?php endforeach; ?>
						</select>
					</div>

					  <div class="form-group">
					    <label>Price</label>
					    <input type="number" name='price' class="form-control" value='<?php echo $product->price?>' required>
					  </div>

					   <div class="form-group">
					    <label>Quantity</label>
					    <input type="number" name='quantity' class="form-control" value='<?php echo $product->quantity?>' required>
					  </div>
					  <?php if($product_sizes[0] !== 'without size'): ?>
					  	<div class="form-group">
						    <label>Check available sizes</label>
						    <div class="d-flex justify-content-around flex-wrap">
						    	<?php if(is_array($sizes) || is_object($sizes)):
						    			foreach($sizes AS $size):
						    	 ?>
							    	<div class="custom-control custom-checkbox mb-3">
									  <input type="checkbox" name='size[<?php echo $size?>]' class="custom-control-input" id="<?php echo $product->id.'_'.$size ?>">
									  <label class="custom-control-label d-flex align-items-center" for="<?php echo $product->id.'_'.$size ?>">
									  	<?php echo $size ?>
									  </label>
									</div>
							    <?php endforeach; endif; ?>
						    </div>
						  </div>
						 <script type="text/javascript">
						 	// Add selected to the product sizes
								<?php  foreach ($product_sizes as $size) {
									echo "$('#edit_".$product->id."').find('#".$product->id.'_'.$size."').attr('checked', true);";
								} ?>
						 </script>
					  <?php else: ?>
					  	<div class="custom-control custom-checkbox mb-3">
						  <input type="checkbox" name='size' value='without size' class="custom-control-input" id="nosize" checked>
						  <label class="custom-control-label d-flex align-items-center" for="nosize">
						  	without size
						  </label>
						</div>
					  <?php endif; ?>

					  <div class="form-group">
					    <label>Check available colors</label>
					      <div class="d-flex justify-content-around flex-wrap">
						    <?php
						       if(is_array($colors) || is_object($colors)):
						    	foreach($colors AS $color => $val):
						     ?>
						    	<div class="custom-control custom-checkbox mb-3">
								  <input type="checkbox" name='color[<?php echo $color?>]' class="custom-control-input" id="<?php echo $product->id.'_'.$color ?>">
								  <label class="custom-control-label d-flex align-items-center" for="<?php echo $product->id.'_'.$color ?>">
								  	<span class="mr-1" style="background: <?php echo $val?>;border-radius: 50%;height: 16px;width: 16px; border: 1px solid #999"></span>
								  	<?php echo $color ?>
								  </label>
								</div>
						    <?php endforeach; endif; ?>
						  </div>
					  </div>

					  <div class="form-group">
					    <label>Materials</label>
					    <input type="text" name='materials' class="form-control" value='<?php echo $product->materials?>' required>
					  </div>

					  <div class="form-group">
					    <label>Description</label>
					    <textarea class="form-control" name='desc' rows='7' required> <?php echo $product->description?> </textarea>
					  </div>
				      <div class="modal-footer">
				        <a href='#' class="btn btn-secondary" data-dismiss="modal" role='button'>Close</a>
				        <button type="submit" class="btn btn-primary" name='save_changes'>Save changes</button>
				      </div>
			        </form>
		      </div>
	      </div>
	     </div>
	  </div>
  </td>
</tr>

 <script>
	// add selected attr to the product category
	$('#edit_<?php echo $product->id?>').find("#cate_<?php echo $product->id?>").find('option[value="<?php echo $product->category?>"]').attr('selected', true);
		
	
	// Add selected to the product colors
	<?php  foreach ($product_colors as $color) {
		echo "$('#edit_".$product->id."').find('#".$product->id.'_'.$color."').attr('checked', true);";
	} ?>
 </script>
<?php endforeach; ?>
<!-- If there was no products found tell him -->
<?php else: ?>
 
	<tr class="w-100 text-center">
		<td colspan="10" class="lead"> No data was found </td>
	</tr>

<?php endif; ?>
