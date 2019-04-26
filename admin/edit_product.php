<?php
$inside_admin = true;
include('../core/inc/init.php');
if(!isset($_SESSION['admin_id'])){
	header('location: login.php');
	exit;
}
include('../core/inc/admin_header.php');


 $errors = array();
 // $success = false;
 if(isset($_POST['save_changes'])):
     
     // ## Delete the product images
     if(isset($_POST['img_delete']) && !empty($_POST['img_delete'])){
	    $image_to_delete = $_POST['img_delete'];
	    $product_id = $_POST['p_id'];
	    
	    // Get product image number
	    $p = $getFromA->getBy('products', ['id' => $product_id]);
	    $imgs_number = $getFromA->getAll('pimages', 'where pimages.connector = '.$p->image);
	    if(count($image_to_delete) < count($imgs_number)){
	    	foreach ($image_to_delete as $img_id) {
		    	$getFromA->delete('pimages', ['id' => $img_id]);
		    }
		    $success = 'Awsome! the product image was <b> successfully deleted </b>';
	    }else{
	    	$errors[] = 'You cant delete all the image';
	    }
     }

  	// handle the images validation
    $images = $_FILES['image_edit'];
  	$image_ids = array();
  	$da_images = array();
  	$product_id = $_POST['p_id'];
  	// prepare image ids
  	foreach ($images['name'] as $img_id => $value)
  	{
	  	// check if empty
	  	 if($images['error'][$img_id] == 0)
	  	 {
	  		foreach ($images as $key => $value)
	  		{
	  	  		if (!empty($value[$img_id]))
	  	  		{
		  	  		if(!in_array($img_id, $image_ids))
		  	  		  	$image_ids[] = $img_id;

		  	  		if(empty($da_images[$key]))
						$da_images[$key] = array($img_id => $value[$img_id]);
		  	  		else
		  	  			$da_images[$key] += array($img_id => $value[$img_id]);
		  	  	}
	  	  	}
	  	 }
  	}

  	if(!empty($da_images))
  	{
  		// echo '<pre>', print_r($da_images),'</pre>';
  		// echo '<pre>', print_r($image_ids),'</pre>';
		$allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
			for( $i = 0; $i < count($image_ids); $i++ )
			{
				$id = $image_ids[$i];
	 			$image_size = $da_images['size'][$id];
	 			$arr_of_img_name = explode('.', $da_images['name'][$id]);
	 			$image_ext = strtolower(end($arr_of_img_name));
	 			 // check for size
	 			 if($image_size < 10485760)
	 			 {
	 			    // check for allowed extensions
	 			 	if(!in_array($image_ext, $allowed_ext)){
	 			 	  $errors[] = 'only jpg, jpeg, png and gif are allowed';
	 			 	}else{
 			 			// Update image if there is any
				   		if(!empty($image_ids) && !empty($da_images))
				   		{
				   			for( $i = 0; $i < count($image_ids); $i++ )
							{
								$imgid = $image_ids[$i];
								$image = basename($da_images['name'][$imgid]);
								$tmp = $da_images['tmp_name'][$imgid];
								$exploded_img = explode('.', $da_images['name'][$imgid]);
							 	$img_ext = strtolower(end($exploded_img)); // image extension
							 	$img_to_upload = time().'_'.str_replace(',', '_', $image);
							 	// Delete the previous img from the folder
							 	$img = $getFromA->getBy('pimages', ['id' => $imgid]);
							 	if (file_exists($productImagesPath.$img->image)) 
								    unlink($productImagesPath.$img->image);
							 	//Update the image
								$getFromA->update('pimages', 'id = '.$imgid.'', ['image' => $img_to_upload]);
								move_uploaded_file($tmp, $productImagesPath.$img_to_upload);
							}
				   		}
	 			 	}
	 			 }else{
	 			 	$errors[] = 'Image size must be less then 10MB';
	 			 }
	 			
	 	    }

  	}

  	 if(!empty($_POST['name']))
    	 	$name = $getFromU->clean_input($_POST['name']);
    	 else
    	 	$errors[] = 'please enter the product name';
    	 //------------price
    	 if(!empty($_POST['price']))
    	 	$price = $getFromU->clean_input($_POST['price']);
    	 else
    	 	$errors[] = 'please enter the product price';
    	 //-------------qntt
    	 if(!empty($_POST['quantity']))
    	 	$qntt = $getFromU->clean_input($_POST['quantity']);
    	 else
    	 	$errors[] = 'please enter the product quantity';
    	  //-------------description
    	 if(!empty($_POST['desc']))
    	 	$desc = $getFromU->clean_input($_POST['desc']);
    	 else
    	 	$errors[] = 'please enter the product description';
    	  //-------------materials
    	 if(!empty($_POST['materials']))
    	 	$materials = $getFromU->clean_input($_POST['materials']);
    	 else
    	 	$errors[] = 'please enter the product materials';

    	// get size and colors
    	 if(empty($errors))
    	 {
    	 	// ---> get availabale checked sizes
    	  if(!empty($_POST['size']))
    	  	$available_sizes = $_POST['size'];
    	  else
    	  	$errors[] = 'Please select the available sizes for that product "<b>'.$name.'</b>"';
    	  // ---> get availabale checked colors
    	  if(!empty($_POST['color']))
    	  	$available_colors = $_POST['color'];
    	  else
    	  	$errors[] = 'Please select the available colors for that product "<b>'.$name.'</b>"';
    	}


   if(empty($errors))
   {
   		// Update the others
   		$psizes = array();
	 	$pcolors = array();
 		 if($available_sizes == 'without size'){
 		 	$psizes[] = 'without size';
 		 }else{
 		 	foreach($available_sizes AS $size => $val):
	 	 	 $psizes[] = $size;
	 	 	endforeach;
 		 }
	 	foreach($available_colors AS $color => $val):
 	 		$pcolors[] = $color;
 	 	endforeach;
	 	// insert into products table
	 	$getFromA->update('products', 'id = '.$product_id.'' ,[
	 		'name' => $name,
	 		'price' => $price,
	 		'description' => $desc,
	 		'materials' => $materials,
	 		'quantity' => $qntt,
	 		'category' => $_POST['category'],
	 		'size' => json_encode($psizes),
	 		'color' => json_encode($pcolors),
	 	]);
   	
	 $success = 'Awsome! the product "'.$name.'" was <b> successfully updated </b>';
   }

 endif;

 if(isset($_POST['delete_product'])):
 	   // Delete product
   if(isset($_POST['product_id']) && !empty($_POST['product_id'])){
   	$pid = $_POST['product_id'];
   	$getFromA->delete('products', ['id' => $pid]);
   	$success = 'The product was <b> successfully deleted </b>';
   }
 endif;

 // this just for the pagination
 $record_per_page = 5;
 $allProducts = $getFromA->getAll('products');
 $total_records = count($allProducts);  
 $total_pages = ceil($total_records/$record_per_page);  
?>

<div class="container-fluid pt-2">
	<?php
      if(!empty($errors)){
      	foreach ($errors as $err) {
        	error_msg($err);
        }
      }elseif(isset($success) && !empty($success)){
        success_msg($success);
      }
    ?>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
						<h2 class="text-white"> Manage products </h2>
					</div>
					<div>						
						<a href="add_product.php" class="btn btn-primary"><i class="fas fa-plus"></i> <span>Add product</span></a>
					</div>
                </div>
            </div>
			<div class="table-filter">
				<div class="row">
                    <div class="col-sm-4">
						<div class="show-entries">
							<span>Show</span>
							<select class="custom-select" style="width: 70px" id='filter_records_num'>
								<option>5</option>
								<option>10</option>
								<option>15</option>
								<option>20</option>
							</select>
							<span>entries</span>
						</div>
					</div>
                    <div class="col-sm-8">
						<div class="filter-group">
						  <label>Search</label>
						  <input type="search" class="form-control" placeholder="search for product" id='filter_search'>
						</div>
                    </div>
                </div>
			</div>
			<div class="table-responsive">
	            <table class="table table-striped table-hover">
	                <thead>
	                    <tr>
	                        <th>#</th>
	                        <th>Product</th>
							<th>Category</th>
							<th>Date added</th>						
							<th>Price</th>
							<th>Action</th>
	                    </tr>
	                </thead>
	                <tbody id='products_table'>
	                	
	                </tbody>
	            </table>
             </div>
			<div class="clearfix">
				<input type="hidden" id='total_pages' value="<?php echo $total_pages?>">
                <div class="hint-text">Showing <b class="records_info">5</b> out of <b><?php echo count($allProducts)?></b> entries</div>
                <ul class="pagination">
                    <li class="page-item" data-action='prev'><a href="#" class="page-link">Previous</a></li>
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    	<li class="page-item" data-page='<?php echo $i?>'>
                    		<a href="#" class="page-link"> <?php echo $i?> </a>
                    	</li>
                    <?php endfor; ?>
                    <li class="page-item" data-action='next'><a href="#" class="page-link">Next</a></li>
                </ul>
            </div>
        </div>
    </div>

<?php include('../core/inc/admin_footer.php') ?>