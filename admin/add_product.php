<?php
// <!-- ---------------------------------------
// 	======== ADD PRODUCT PAGE =========
// --------------------------------------- -->

$inside_admin = true;
include '../core/inc/init.php';
if(!isset($_SESSION['admin_id'])){
	header('location: login.php');
	exit;
}
    include '../core/inc/admin_header.php';
    // Get all categorysb 
	$categorys = $getFromA->getAll('categories');
	$sub_c = $getFromA->getAll('sub_category', 'where parent = 1');
    $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', '4XL','5XL'];
    $colors = [ 
    			'grey' => '#999999', 
    			'black' => '#000000',
    			'white' => '#ffffff', 
    			'blue' => '#033fff', 
    			'red' => '#fd0054',
    			'yellow' => '#f9ff21',
    			'pink' => '#FF69B4',
    			'purple' => '#503bff',
    			'green' => '#080',
    			'orange' => '#ff9234',
    			'brown' => '#aa7243',
    		  ];
    $errors = array();
    $success = false;
	$allowed_num_to_upload = 10;
    if($_SERVER['REQUEST_METHOD'] == 'POST'):
    	$connector = time();
    	// handle the images validation
		 if(empty($errors)){
		 	if(!empty($_FILES['image']['name'][0]) || is_uploaded_file($_FILES['image']['tmp_name'][0]))
		 	{
		 		$allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
		 		if(count($_FILES['image']['name']) > $allowed_num_to_upload):
		 			$errors[] = 'You cant upload <b>more then {$allowed_num_to_upload} images</b>';
		 		else:
		 			for( $i = 0; $i < count($_FILES['image']['name']); $i++ ){
				 			$image_size = $_FILES['image']['size'][$i];
				 			$arr_of_img_name = explode('.', $_FILES['image']['name'][$i]);
				 			$image_ext = strtolower(end($arr_of_img_name));
				 			// check for size
				 			 if($image_size < 10485760){
				 			// check for allowed extensions
				 			 	if(in_array($image_ext, $allowed_ext)){
				 			 		$images = $_FILES['image'];
				 			 	}else{
				 			 		$errors[] = 'only jpg, jpeg, png and gif are allowed';
				 			 	}
				 			 }else{
				 			 	$errors[] = 'Image size must be less then 10MB';
				 			 }
				 			
				 	}
		 	    endif;
		 	}else{
		 		$errors[] = 'please upload product image';
		 	}
    	 }
    	// get input data
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

    	// get checkboxes data
    	 if(empty($errors)){
    	 	// ---> get availabale checked sizes
    	  if(!empty($_POST['size']))
    	  	$available_sizes = $_POST['size'];

    	  // ---> get availabale checked colors
    	  if(!empty($_POST['color']))
    	  	$available_colors = $_POST['color'];
    	  else
    	  	$errors[] = 'please select available colors';
    	}

    	// if everything is gut add the product to the DB 
    	 if(empty($errors)){
    	 	$pcolors = array();
    	 	$psizes = array();
	 		if(isset($available_sizes) && is_array($available_sizes)){
	 			foreach($available_sizes AS $size => $val):
		 	 	 $psizes[] = $size;
		 	 	endforeach;
	 		}else{
	 			$psizes[] = "without size";
	 		}

 	 		foreach($available_colors AS $color => $val):
	 	 		$pcolors[] = $color;
	 	 	endforeach;
    	 	// insert into products table
    	 	$getFromA->create('products', [
    	 		'name' => $name,
    	 		'price' => $price,
    	 		'description' => $desc,
    	 		'materials' => $materials,
    	 		'quantity' => $qntt,
    	 		'category' => $_POST['category'],
    	 		'sub_category' => empty($_POST['sub_category']) ? $_POST['category'] : $_POST['sub_category'],
    	 		'image' => $connector,
    	 		'size' => json_encode($psizes),
    	 		'color' => json_encode($pcolors),
    	 		'created_at' => date('Y-m-d H:i:s'),
    	 	]);
    	 	// insert into product images table
    	 	 	for( $i = 0; $i < count($_FILES['image']['name']); $i++ ){
    	 	 		$image = basename($_FILES['image']['name'][$i]);
    	 	 		$tmp = $_FILES['image']['tmp_name'][$i];
					$exploded_img = explode('.', $_FILES['image']['name'][$i]);
				 	$img_ext = strtolower(end($exploded_img)); // image extension
				 	$img_to_upload = time().'_'.str_replace(',', '_', $image);
    	 	 		$getFromA->create('pimages', [ 'image' => $img_to_upload, 'connector' => $connector]);
    	 	 		move_uploaded_file($tmp, $productImagesPath.$img_to_upload);
			    }
			   $success = true;
    	 }

    endif;
 ?>
 <div class="container pt-2">

	<?php if(!empty($errors)): ?>
		 <div class="alert alert-danger" role="alert">
		 	<ul class="p-0">
			<?php foreach ($errors as $error): ?>
	  		  <li><?php echo $error ?></li>
	        <?php endforeach; ?>
	     </ul>
	</div>
	<?php elseif($success): ?>
	  <div class="alert alert-success" role="alert">
	    <b>Success!</b> you product was successfully added
	  </div>
	<?php endif; ?>

	<h1 class="text-center"> Lets add a new product </h1>
	 <form action='<?php echo $_SERVER['PHP_SELF']?>' method='POST' enctype="multipart/form-data">

		  <div class="form-group upload_img mb-4 mt-3 position-relative d-flex justify-content-center align-items-center flex-column">
		 	<img src="http://cdn.onlinewebfonts.com/svg/img_234957.png" width="90">
		 	  <div class="w-50 text-center">
		 	  	  <code class="text-muted text-center">
			 	   Drag and Drop images here or click and choose <b>to upload your product images</b>, you can only upload <?php echo $allowed_num_to_upload?> images at once, but remember the less images, the more speed of your site will be !
			 	  </code>
		 	  </div>
		 	<input type="file" title='upload the product images' name="image[]" multiple class="upload_input" required>
		  </div>

		  <div class="form-group">
		    <label>Product name</label>
		    <input type="text" name='name' class="form-control" placeholder="what is the product name ?" required>
		  </div>

		 <div class="form-group">
		 	<label>Category</label>
			 <select class="custom-select" name='category' id='cate'>
	 			<?php foreach($categorys AS $cate): ?>
			    	<option data-cid='<?php echo $cate->id ?>' value="<?php echo $cate->name ?>">
			    		<?php echo $cate->name ?>
			    	</option>
			    <?php endforeach; ?>
			</select>
		</div>

		<div class="form-group" id='sub_c'>
		 	<label>Sub-category</label>
			 <select class="custom-select" name='sub_category'>
	 			<?php foreach($sub_c AS $subc): ?>
			    	<option value="<?php echo $subc->name ?>"><?php echo $subc->name ?></option>
			    <?php endforeach; ?>
			</select>
		<small> Just select the sub category, this wil help the customer to find what he need faster </small>
		</div>

		  <div class="form-group">
		    <label>Price</label>
		    <input type="number" name='price' class="form-control" placeholder="give your price in dollar" required>
		  </div>

		   <div class="form-group">
		    <label>Quantity</label>
		    <input type="number" name='quantity' class="form-control" placeholder="how many product is available ?" required>
		  </div>

		  <div class="form-group">
		    <label>Check available sizes</label>
		    <div class="custom-control custom-checkbox mb-3" style="cursor: pointer">
			  <input type="checkbox" name='nosize' class="custom-control-input" id="nosize">
			  <label class="custom-control-label d-flex align-items-center" for="nosize">
			  	<!-- <span class="mr-1"></span> -->
			  	<b> <mark>This product has no sizes</mark> </b>
 			  </label>
			</div>
			<!-- <div align="center" class="size-204 respon6-next"> -->
				<div class="btn-group btn-group-toggle d-flex flex-wrap align-items-center" data-toggle="buttons">
		    	<?php if(is_array($sizes) || is_object($sizes)):
		    			foreach($sizes AS $size): 
		    	 ?>
					<label class="btn btn-primary mb-3">
					    <input type="checkbox" class='add_sizes' name="size[<?php echo $size?>]" id="<?php echo $size ?>">
					    <?php echo $size?>
					 </label>
			    <?php endforeach; endif; ?>
				</div>
			<!-- </div> -->
		  </div>

		  <div class="form-group">
		    <label>Check available colors</label>
		      <!-- <div class="d-flex justify-content-around flex-wrap"> -->
		      	<div class="btn-group btn-group-toggle d-flex flex-wrap align-items-center" data-toggle="buttons">
				    <?php
				       if(is_array($colors) || is_object($colors)):
				    	foreach($colors AS $color => $val):
				     ?>
				     	 <label class="btn btn-primary mb-3">
						    <input type="checkbox" name="color[<?php echo $color?>]" id="<?php echo $color ?>">
						    <?php echo $color?>
						 </label>
				    	<!-- <div class="custom-control custom-checkbox mb-3">
						  <input type="checkbox" name='color[<?php echo $color?>]' class="custom-control-input" id="<?php echo $color ?>">
						  <label class="custom-control-label d-flex align-items-center" for="<?php echo $color ?>">
						  	<span class="mr-1" style="background: <?php echo $val?>;border-radius: 50%;height: 16px;width: 16px; border: 1px solid #999"></span>
						  	<?php echo $color ?>
						  </label>
						</div> -->
				    <?php endforeach; endif; ?>
			   </div>
			  <!-- </div> -->
		  </div>

		  <div class="form-group">
		    <label>Materials</label>
		    <input type="text" name='materials' class="form-control" placeholder="eg: 60% coutton" required>
		  </div>

		  <div class="form-group">
		    <label>Description</label>
		    <textarea class="form-control" name='desc' rows='5' placeholder="Describe your product" required></textarea>
		  </div>

	  <button type='submit' class="btn btn-primary w-100 mb-5 mt-3"> add product </button>

	</form>

</div>

 <?php include '../core/inc/admin_footer.php' ?>