<?php
################################################################
#============== Store settings page ================
################################################################
$inside_admin = true;
include '../core/inc/init.php';
if(!isset($_SESSION['admin_id'])){
	header('location: login.php');
	exit;
}
include '../core/inc/admin_header.php';
$errors = array();

// ############ Update Store logo & Favicon #############
if(isset($_POST['upload_logo']))
{
	$images = array();
	 if(!empty($_FILES['logo']['name']) || is_uploaded_file($_FILES['logo']['tmp_name'])){
	 	$images[] = ['logo' => [ 
						'allowed_size' =>  5242880,
						'width' => range(70, 200),
						'height' => range(15, 150),
					  ],
					];
	 }
	 if(!empty($_FILES['favicon']['name']) || is_uploaded_file($_FILES['favicon']['tmp_name'])){
	 	$images[] = ['favicon' => [ 
						'allowed_size' =>  5242880,
						'width' => range(70, 15),
						'height' => range(70, 150),
					  ],
					];
	 }
	 $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
	 
		// echo '<pre>',print_r($images),'</pre>';
	 if(!empty($images))
	 {
	 	foreach ($images as $img)
	 	{
	 		foreach ($img as $img_name => $verif) {
		 		$img_size = $_FILES[$img_name]['size'];
				// $img_info = getimagesize($_FILES[$img_name]["tmp_name"]);
				// $img_width = $img_info[0];
				// $img_height = $img_info[1];
				$getImgExt = explode('.', $_FILES[$img_name]['name']);
				$img_ext = strtolower(end($getImgExt));
				// if(in_array($img_width, $verif['width']) && in_array($img_height, $verif['height'])){
					// check for size
					 if($img_size < $verif['allowed_size'] || $img_size == $verif['allowed_size']){
					// check for allowed extensions
					 	if(!in_array($img_ext, $allowed_ext))
					 		$errors[] = 'only jpg, jpeg, png and gif are allowed';
					 }else{
					 	$errors[] = 'Image size must be less then or equal to 5MB';
					 }
				// }else{
				// 	$errors[] = $img_name.' Image width must be between '.$verif['width'][0].'-'.$verif['width'][count($verif['width']) - 1].' & height must be between '.$verif['height'][0].'-'.$verif['height'][count($verif['height']) - 1];
				// }
	 		}
	 	}

	 }else{
	 	$errors[] = 'please upload a logo Or a favicon';
	 }
	
	if(empty($errors))
	{
		// Update the logo
 	 	foreach ($images as $img)
	 	{
	 		foreach ($img as $img_name => $verif){
				$image = basename($_FILES[$img_name]['name']);
				$tmp = $_FILES[$img_name]["tmp_name"];
			 	$img_to_upload = time().'_'.str_replace(',', '_', $image);
			 	// Delete the previous img from the folder
			 	$img = $getFromA->getAll('shop_front');
			 	if (file_exists($shopfrontPath.$img[0]->$img_name)){
				    unlink($shopfrontPath.$img[0]->$img_name);
			 	}
			 	//Update the image
				$getFromA->update('shop_front', 'id = 1', [
					$img_name => $img_to_upload
				]);
				move_uploaded_file($tmp, $shopfrontPath.$img_to_upload);
	 	    }
	    }
	    $success = true;
	}
}


// #################### Update About Store ######################
if(isset($_POST['about_store']))
{

	$about = $getFromU->clean_input($_POST['about']);
	$fb = $getFromU->clean_input($_POST['fb']);
	$insta = $getFromU->clean_input($_POST['insta']);
	$twitter = $getFromU->clean_input($_POST['twitter']);

	if(empty($about))
		$errors[] = 'Please write something about your store, you cant leave it blank';
	if(empty($errors)){
		$getFromA->update('shop_front', 'id = 1', [
			'about' => $about,
			'facebook' => $fb,
			'insta' => $insta,
			'twitter' => $twitter,
		]);
		$success = true;
	}
}


##################### Update payment ######################
if(isset($_POST['update_payment']))
{
	$paypal_id = $_POST['paypal_id'];
	$paypal_secret = $_POST['paypal_secret'];
	$stripe_id = $_POST['stripe_id'];
	$stripe_secret = $_POST['stripe_secret'];

	if(empty($paypal_id) || empty($paypal_secret) || empty($stripe_id) || empty($stripe_secret)){
		$errors[] = 'You cant leave the payment api key empty';
	}
	else{
		$to_update = [
			'paypal' => json_encode(
						array('id' => $paypal_id, 'secret' => $paypal_secret)
					),
			'stripe' => json_encode(
						array('id' => $stripe_id, 'secret' => $stripe_secret)
					)
		];
	}

	if(empty($errors)){
		$getFromA->update('shop_front', 'id = 1', $to_update);
		$success = true;
	}
}

####################### Update Contact Info #########################
if(isset($_POST['update_contactinfo']))
{
	$email = $_POST['support_email'];
	$tel = $_POST['support_phone'];
	$address = $_POST['address'];

	if(empty($email))
		$errors[] = 'You cant leave the support email empty';
	if(empty($errors)){
		$getFromA->update('shop_front', 'id = 1', [
			'email' => $email,
			'phone' => $tel,
			'address' => $address
		]);
		$success = true;
	}
}

#################### Update the Map ###################

if(isset($_POST['update_map']))
{
	$lng = $_POST['lng'];
	$lat = $_POST['lat'];
	$key = $_POST['map_key'];
	$location = $_POST['loc'];
	$service = isset($_POST['service']) && !empty($_POST['service'])? $_POST['service'] : 'paid';
	if(empty($lng) && empty($lat))
	  $loc = '';
	else
		$loc = json_encode(array(
			'loc' => $location,
			'lng' => $lng,
			'lat' => $lat,
			'key' => $key,
			'service' => $service
		));

		$getFromA->update('shop_front', 'id = 1', ['map' => $loc]);
		$success = true;


}

// Shop front-end
$shop_front = $getFromA->getAll('shop_front');

$map = json_decode($shop_front[0]->map);
$paypal_keys = json_decode($shop_front[0]->paypal);
$stripe_keys = json_decode($shop_front[0]->stripe);
?>

<div class="img-thumbnail m-2 pt-5">
  
	<div class="m-3">
		<?php
		 if(!empty($errors)){
		 	foreach ($errors as $err) {
		 		error_msg($err);
		 	}
		 }elseif(isset($success)){
		 	success_msg('Yayy! your store details was <b>successfully updated</b>');
		 }
		 	info_msg('If you dont have a store location, or social links, just leave them empty, the icons/map wont show on the website')
		?>
	</div>

	<!-- ******************************** -->
		<div class='d-flex flex-wrap'>
			<ul class="nav flex-column nav-pills w-25 p-1" id="pills-tab" role="tablist" style="overflow-x: auto">
				<li class="nav-item">
					<a class="nav-link active" id="store_logo_tab" data-toggle="pill" href="#store_logo">Store logo</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="about_store_tab" data-toggle="pill" href="#about_store">About your store</a>
				</li>
					<li class="nav-item">
					<a class="nav-link" id="about_store_tab" data-toggle="pill" href="#payment_settings">Payment settings</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="about_store_tab" data-toggle="pill" href="#contact_info">Contact information</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" id="about_store_tab" data-toggle="pill" href="#map_sett">Google Map Settings</a>
				</li>
			</ul>

			<div class="tab-content w-75" id="pills-tabContent">
					<!-- ##################### Store Logo Tab ################### -->
					<div class="tab-pane fade show active" id="store_logo">
						<div class="p-3">
						
						<form method="POST" enctype="multipart/form-data">
							<div class="mb-3">
								<label> Store logo </label>
								<div class="position-relative image_edit text-center p-4">
									<img src="<?php echo $shopfrontPath.$shop_front[0]->logo?>"
										style="width: 100%;">
										<input type="file" class="custom-file-input w-100 h-100" style="position: absolute; top: 0; left: 0; opacity: 0; cursor: pointer" name='logo'>
								</div>
								<!-- <div class="custom-file">
									<input type="file" class="custom-file-input" name='logo' id="customFile">
									<label class="custom-file-label" for="customFile">Upload Logo</label>
								</div> -->
							</div>
							<div class="mb-3">
								<label> Store favicon </label>
								<div class="position-relative image_edit text-center p-4">
									<img src="<?php echo $shopfrontPath.$shop_front[0]->favicon?>"
										style="width: 100%;">
										<input type="file" class="custom-file-input w-100 h-100" style="position: absolute; top: 0; left: 0; opacity: 0; cursor: pointer" name='favicon'>
								</div>
								<!-- <div class="custom-file">
									<input type="file" class="custom-file-input" name='favicon' id="customFile">
									<label class="custom-file-label" for="customFile">Upload favicon</label>
								</div> -->
								<!-- <p> <small> This favicon will show up in the browser-tab </small> </p> -->
							</div>
							<button type="submit" name='upload_logo' class="btn btn-primary w-100 d-block"> Upload </button>
						</form>

						</div>
					</div>

					<!-- ##################### About store Tab ################### -->
					<div class="tab-pane fade show" id="about_store">
						<div class="p-3">
						<form method="POST">
							<div class="mb-3">
								<textarea class="form-control" name='about' rows="15" placeholder="Write about your store"><?php echo $shop_front[0]->about?></textarea>
							</div>
							<div class="mb-3">
								<div class="col-auto">
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<div class="input-group-text"><i class="fab fa-facebook"></i></div>
											</div>
											<input type="text" class="form-control" name='fb' value='<?php echo $shop_front[0]->facebook?>' placeholder="link to facebook page">
										</div>
									</div>
							</div>
							<div class="mb-3">
								<div class="col-auto">
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<div class="input-group-text"><i class="fab fa-instagram"></i></div>
											</div>
											<input type="text" class="form-control" name='insta' value='<?php echo $shop_front[0]->insta?>'placeholder="link to instagram page">
										</div>
									</div>
							</div>
							<div class="mb-3">
								<div class="col-auto">
										<div class="input-group mb-2">
											<div class="input-group-prepend">
												<div class="input-group-text"><i class="fab fa-twitter"></i></div>
											</div>
											<input type="text" class="form-control" name='twitter' value='<?php echo $shop_front[0]->twitter?>' placeholder="link to twitter page">
										</div>
									</div>
							</div>

							<button type="submit" name='about_store' class="btn btn-primary w-100 d-block"> Save changes </button>
						</form>

						</div>
					</div>

					<!-- ##################### Payment settings Tab ################### -->
					<div class="tab-pane fade show" id="payment_settings">
						<div class="p-3">
							<form method="POST">
								<div class="mb-3">
									<mark class="d-block text-center mb-3 font-weight-bold"> Paypal settings </mark>
									<div class="form-row">
										<div class="col">
										<label> Paypal Client ID </label>
											<input type="text" class="form-control" name='paypal_id' value='<?php echo empty($paypal_keys) ? '': $paypal_keys->id?>' placeholder="Paypal Client ID">
										</div>
										<div class="col">
											<label> Paypal Secret key </label>
											<input type="text" class="form-control" name='paypal_secret' value='<?php echo empty($paypal_keys) ? '': $paypal_keys->secret?>'placeholder="Paypal Secret key">
										</div>
									</div>
								</div>
								<hr>
								<div class="mb-3">
									<mark class="d-block text-center mb-3 font-weight-bold"> Stripe settings </mark>
								<div class="form-row">
										<div class="col">
											<label> Stripe Client ID </label>
											<input type="text" class="form-control" name='stripe_id' value='<?php echo empty($stripe_keys) ? '': $stripe_keys->id?>' placeholder="Stripe Client ID">
										</div>
										<div class="col">
											<label> Stripe Secret key </label>
											<input type="text" class="form-control" name='stripe_secret' value='<?php echo empty($stripe_keys) ? '': $stripe_keys->id?>' placeholder="Stripe Secret key">
										</div>
									</div>
								</div>
								<button type="submit" name='update_payment' class="btn btn-primary w-100 mt-3">Save changes</button>
							</form>
						</div>
					</div>

					<!-- ##################### Contact information Tab ################### -->
						<div class="tab-pane fade show" id="contact_info">
							<div class="p-3">
								<form method="POST">
								<div class="form-row">
									<div class="form-group col-md-6">
										<label>Support email</label>
										<input type="email" class="form-control" value='<?php echo $shop_front[0]->email?>' name='support_email' placeholder="Email">
										<small>
											Any user who will send a message from the contact page <a href="../contact-us" target="_blank">here</a>, it will get to this email
										</small>
									</div>
									<div class="form-group col-md-6">
										<label>Support Phone No</label>
										<input type="tel" class="form-control" name='support_phone' value='<?php echo $shop_front[0]->phone?>' placeholder="e.g: (+216)20123456">
									</div>
								</div>
								<div class="form-group">
									<label>Store Address</label>
									<input type="text" class="form-control" name='address' value='<?php echo $shop_front[0]->address?>' placeholder="e.g: Store-name Center 8th floor, 379 Hudson St, New York, NY 10018 US">
								</div>
								<button type="submit" name='update_contactinfo' class="btn btn-primary w-100 mt-3">Save changes</button>
							</form>
							</div>
						</div>

					<!-- ##################### Google Map Settings ################### -->
					<div class="tab-pane fade show" id="map_sett">
						<div class="p-3">
								<label>If you dont want to show a map in the contact page leave this fields here empty</label>
							<form method="POST">
								<div class="form-group">
									<label>Google Map ApiKey</label>
									<input type="text" class="form-control" value='<?php echo !empty($map->key) ? $map->key : ''; ?>' name='map_key' placeholder="Google Map Api key">
								</div>
								<div class="mb-3">
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div>
										</div>
										<input type="hidden" value="<?php echo empty($map) ? '': $map->lng?>" name='lng' id='lng'>
											<input type="hidden" value="<?php echo empty($map) ? '': $map->lat?>" name='lat' id='lat'>
											<input class="form-control" id="autocomplete" type="text" value='<?php echo empty($map) ? '': $map->loc?>' onfocus="geolocate()" name="loc" placeholder="Store location, Headquartes">
									</div>
								</div>
								<div class="form-group">
									<div class="custom-control custom-checkbox mr-sm-2">
										<input type="checkbox" name='service' id='service_type' value='free' class="custom-control-input" id="customControlAutosizing">
										<label class="custom-control-label" for="service_type">use the free map service</label>
									</div>
								</div>
							<button type="submit" name='update_map' class="btn btn-primary w-100 mt-3">Save changes</button>
						</form>
					</div>
		</div>
		<?php 
				if($map->service == 'free'){
					echo '<script>
									$("#service_type").attr("checked", true);
								</script>';
				}
		?>
	<!-- ******************************** -->
</div>

 <?php if(!empty($map->key)): ?>
			<script>
					var placeSearch, autocomplete;
					var componentForm = {
						lat: 'lat',
						lng: 'lng'
					};

					function initAutocomplete() {
						// Create the autocomplete object, restricting the search to geographical
						// location types.
						autocomplete = new google.maps.places.Autocomplete(
								(document.getElementById('autocomplete')),
								{types: ['geocode']});

						// When the user selects an address from the dropdown, populate the address
						// fields in the form.
						autocomplete.addListener('place_changed', fillInAddress);
					}

					function fillInAddress() {
						// Get the place details from the autocomplete object.
						var place = autocomplete.getPlace();
						var getlng = place.geometry.location.lng();
						var getlat = place.geometry.location.lat();
						document.getElementById('lat').value = getlat;
						document.getElementById('lng').value = getlng;        
					}

					// Bias the autocomplete object to the user's geographical location,
					// as supplied by the browser's 'navigator.geolocation' object.
					function geolocate() {
						if (navigator.geolocation) {
							navigator.geolocation.getCurrentPosition(function(position) {
								var geolocation = {
									lat: position.coords.latitude,
									lng: position.coords.longitude
								};
							});
						}
					}
				</script>
			<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $map->key?>&libraries=places&callback=initialize" async defer></script>
			<script>
				function initialize() {
					initAutocomplete();
				}
			</script>
 <?php endif;?>
<?php include '../core/inc/admin_footer.php';?>