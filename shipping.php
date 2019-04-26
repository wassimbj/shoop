<?php
if(!isset($_SESSION['user_id']) && empty($_SESSION['user_id'])){
	header('location: join');
	exit;
}

$success = false;
$error = '';

	  // get all countries from the countries table
	  $countries = $getFromA->getAll('countries', 'order by country_name asc');
	  $user_id = $_SESSION['user_id'];
  if(isset($_POST['submit_shipping'])):
	  	// get all form data;
	  	$fname = $getFromU->clean_input($_POST['firstname']);
	  	$lname = $getFromU->clean_input($_POST['lastname']);
	  	$address = $getFromU->clean_input($_POST['address']);
	  	$city = $getFromU->clean_input($_POST['city']);
	  	$state = $getFromU->clean_input($_POST['state']);
	  	$country = $getFromU->clean_input($_POST['country']);
	  	$zip = $getFromU->clean_input($_POST['zip']);

	  	// validate it;
	  	if(empty($fname) || empty($lname) || empty($address) || empty($city) || empty($state) || empty($country) || empty($zip))
	  		$error = 'All fields are required';

	  	// store it in a session;
	  	if(empty($error)){
	  		$_SESSION[$user_id.'_shipping'] = [
	  			'ship_by' => $user_id,
	  			'firstname' => $fname, 'lastname' => $lname,
	  			'address' => $address, 'city' => $city, 'state' => $state,
	  			'country' => $country, 'zip' => $zip
	  		];
	  		$success = true;
	  		header('refresh: 3; url = shoping-cart.php?prev_step=shipping&step=payment');
	  	}
  endif;

  $ship_to = $getFromA->getBy('shipping', ['ship_by' => $user_id]);
	$exist_fname = '';
	$exist_lname = '';
	$exist_address = '';
	$exist_country = '';
	$exist_city = '';
	$exist_state = '';
	$exist_zip = '';
if( !empty($ship_to) )
{
  $exist_fname = $ship_to->firstname;
  $exist_lname = $ship_to->lastname;
  $exist_address = $ship_to->address;
  $exist_country = $ship_to->country;
  $exist_city = $ship_to->city;
  $exist_state = $ship_to->state;
  $exist_zip = $ship_to->zip;
}elseif(isset($_SESSION[$user_id.'_shipping']) && !empty($_SESSION[$user_id.'_shipping'])){
	 $exist_fname = $_SESSION[$user_id.'_shipping']['firstname'];
	 $exist_lname = $_SESSION[$user_id.'_shipping']['lastname'];
	 $exist_address = $_SESSION[$user_id.'_shipping']['address'];
	 $exist_country = $_SESSION[$user_id.'_shipping']['country'];
	 $exist_city = $_SESSION[$user_id.'_shipping']['city'];
	 $exist_state = $_SESSION[$user_id.'_shipping']['state'];
	 $exist_zip = $_SESSION[$user_id.'_shipping']['zip'];
}

 $success_msg = 'Great, <b> you will be redirected to the next step </b> :)';

?>

<form method="POST">
	  <div class="mb-4">
	  	<?php
	      if(!empty($error))
	        error_msg($error);
	      elseif($success)
	        success_msg($success_msg);
	    ?>
	  </div>
	  <h5 class="mb-3 font-weight-bold"> Shipping address </h5>
	  <div class="row">
	  	  <div class="col">
	  	  	  <div class="form-group">
			    <input type="text" class="form-control form-control-lg" placeholder="Your first name" name='firstname' value='<?php echo $exist_fname?>'>
			  </div>
	  	  </div>
	  	  <div class="col">
	  	  	  <div class="form-group">
			    <input type="text" class="form-control form-control-lg" placeholder="Last name" name='lastname' value='<?php echo $exist_lname?>'>
			  </div>
	  	  </div>
	  </div>
  	  <div class="form-group">
	    <input type="text" class="form-control form-control-lg" placeholder="Address" name='address' value='<?php echo $exist_address?>'>
	  </div>
  	  <div class="form-group">
	    <input type="text" class="form-control form-control-lg" placeholder="City" name='city' value='<?php echo $exist_city?>'>
	  </div>
	    <select class="custom-select custom-select-lg mb-3" name='country'>
		  <?php if(empty($exist_country)): ?>
		  	<option selected value="">Country</option>
		  <?php else: ?>
		  	<option selected value="<?php echo $exist_country?>"><?php echo $exist_country?></option>
		  	<option disabled> ------ </option>
		  <?php endif?>
		  <?php foreach($countries AS $country): ?>
		 	<option value='<?php echo $country->country_name?>'> <?php echo $country->country_name?> </option>
		  <?php endforeach; ?>
		</select>
	  <div class="row">
	    <div class="col">
      	  <div class="form-group">
		    <input type="text" class="form-control form-control-lg" placeholder="State" name='state' value='<?php echo $exist_state?>'>
		  </div>
	    </div>
	    <div class="col">
	       <div class="form-group">
		    <input type="number" class="form-control form-control-lg" placeholder="Zip code" name='zip' value='<?php echo $exist_zip?>'>
		  </div>
	    </div>
	  </div>

	  <button type="submit" class="btn btn-primary w-100 mt-4 btn-lg" name='submit_shipping'>
  	  <?php if(empty($exist_fname)): ?>
	  	Next step
	  <?php else: ?>
	  	Save Changes
	  <?php endif?>
	  </button>
</form>
