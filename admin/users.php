<?php
####################################################################
# ================ ADD,MANAGE ADMINS & CUSTOMERS ===============
# !!! You will find the html of this in AjaxData.php file !!!
####################################################################
$inside_admin = true;
include '../core/inc/init.php';
if(!isset($_SESSION['admin_id'])){
	header('location: login.php');
	exit;
}
include '../core/inc/admin_header.php';
?>

<?php if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] == 'add'):
// <!-- #####################** ADD CUSTOMERS, ADMINS **######################## -->
  $errors = array();
      $success = false;
      if(isset($_POST['add_users'])):

         $email = $getFromU->clean_input($_POST['email']);
         $pass = $getFromU->clean_input($_POST['pass']);
         $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
         $name = $getFromU->clean_input($_POST['name']);
         $gender = $getFromU->clean_input($_POST['gender']);
         $role = $getFromU->clean_input($_POST['role']);
          // Validate Email
          if(!isset($email) && empty($email))
            $errors[] = 'please <b>enter your email</b>';
          elseif(isset($email) && !preg_match('(\w[-._\w]*\w@\w[-._\w]*\w\.\w{2,})', $email))
            $errors[] = 'the email you entered is <b>not valid</b>';

         // Validate pass 
          if(!isset($pass) && empty($pass))
            $errors[] = 'please enter your password';
          elseif(isset($pass) && strlen($pass) < 6)
            $errors[] = 'Password must be <b> more then 6 characters </b>';

         // Validate full name
           if(!isset($name) && empty($name))
            $errors[] = 'please <b> enter your password </b>';
           elseif(isset($name) && strlen($name) < 5)
            $errors[] = 'the name is too short, <b>please enter the full name</b>';

        // see if there is a user with that email
        if($getFromA->already_there($role, 'email', $email))
        	$errors[] = 'There is already an account with that email "<b>'.$email.'</b>"';

         // everything is good ? create user account
          if(empty($errors)){
            $getFromA->create($role, [
              'email' => $email,
              'password' => $hashed_pass,
              'name' => $name,
              'gender' => $gender,
              'created_at' => date('Y-m-d H:i:s')
            ]);
            $success = true;
          }


      endif; 
?>
<div class="container" style="height: 100vh">

	<h1 class="text-center mb-5 text-muted"> ADD USERS </h1>
	   <?php if(!empty($errors)):
	   	  error_msg($errors[0]);
	     elseif($success):
	     	success_msg('Success! the '.substr($role, 0, strripos($role,'s')).' was successfully added');
	     endif; ?>
	<form method="POST">
	  <div class="form-label-group mb-3">
	    <label>Full name</label>
	    <input type="text" class="form-control" placeholder="e.g: Jhon doe" name='name'>
	  </div>

	  <div class="form-label-group mb-3">
	    <label>Email address</label>
	    <input type="text" class="form-control" placeholder="Jhondoe@bla.com" name="email">
	  </div>

	  <div class="form-label-group mb-3">
	    <label>Password</label>
	    <input type="password" class="form-control" placeholder="password" name='pass'>
	  </div>

	   <div class="form-label-group">
	   	 <label> Gender </label>
	   	  <select class="custom-select" name='gender'>
		    <option selected value="male">Male</option>
		    <option value="female">Female</option>
		  </select>
	   </div>
	   <hr>
	   <div class="form-label-group">
	   	 <label> Role </label>
	   	  <select class="custom-select" name='role'>
		    <option selected value="customers">Customer</option>
		    <option value="admins">Admin</option>
		  </select>
	   </div>

	  <button class="btn btn-lg btn-primary mt-5 w-100" type="submit" name="add_users">ADD USER</button>
	 
	</form>

</div>


<?php else:
// <!-- #####################!! MANAGE CUSTOMERS, ADMINS !!######################## -->

 // get all admins
	$admins = $getFromA->getAll('admins');
// get all customers
	$customers = $getFromA->getAll('customers');
	  // this just for the pagination
	 $record_per_page = 3;
	 $total_records = count($customers) + count($admins);  
	 $total_pages = ceil($total_records/$record_per_page);

	 // Delete customer OR admin, whatever its is
	 if(isset($_POST['admin_to_delete'])){
	 	$admin_id = $_POST['admin_to_delete'];
	 	$getFromA->delete('admins', ['id' => $admin_id]);
	 }
	  if(isset($_POST['customer_to_delete'])){
	 	$cust_id = $_POST['customer_to_delete'];
	 	$getFromA->delete('customers', ['id' => $cust_id]);
	 }

	 // Edit Customer
	 if(isset($_POST['customer_to_edit']))
	 {
	 	if(!empty($_POST['new_cust_email']) && !empty($_POST['new_cust_name'])){
		 	$getFromA->update('customers', 'id ='.$_POST['customer_to_edit'].'', [
		 		'email' => $_POST['new_cust_email'],
		 		'name' => $_POST['new_cust_name']
		 	]);
		 	success_msg('The customer credentials was successfully changed');
	 	}else{
	 		error_msg('You cant leave email or name empty');
	 	}
	 }
	 // Edit  Admin
	 if(isset($_POST['admin_to_edit']))
	 {
	 	$admin_to_edit = $_POST['admin_to_edit'];
	 	if(!empty($_POST['new_admin_email']) && !empty($_POST['new_admin_name'])){
	 		$new_admin_email = $_POST['new_admin_email'];
	 		$new_admin_name = $_POST['new_admin_name'];
		 	if(!empty($_POST['new_admin_pass'])){
		 		$getFromA->update('admins', 'id ='.$admin_to_edit.'', [
			 		'email' => $_POST['new_admin_email'],
			 		'name' => $_POST['new_admin_name'],
			 		'password' => password_hash($_POST['new_admin_pass'], PASSWORD_DEFAULT)
			 	]);
		 	}else{
			 	$getFromA->update('admins', 'id ='.$admin_to_edit.'', [
			 		'email' => $_POST['new_admin_email'],
			 		'name' => $_POST['new_admin_name']
			 	]);
		 	}
		 	success_msg('The admin credentials was successfully changed');
	 	}else{
	 		error_msg('You cant leave email or name empty');
	 	}
	 }

	 // send email to all customers
	  $error = ''; 
	  $to = array();
	  if(isset($_POST['send_to_all_cust']))
	  {
		  	// get all verified customers email to send
		  	foreach ($customers as $cust) {
		  	  $to[] = $cust->email;	
		  	}
		      $subject = $_POST['email_subj'];
		      $body = $_POST['email_body'];
		      $attach = $_FILES['attach'];
	    
		      // validate the attach
		       if(is_array($attach) && !empty($attach['tmp_name']) && !empty($attach['name'])){
		       	    $after_expld = explode('.', $attach['name']);
			        $attach_ext = strtolower(end($after_expld));
			       if(in_array($attach_ext, ['pdf', 'png', 'jpeg', 'jpg', 'gif', 'svg']))
			       {
			         if($attach['size'] > 7000000){
			           $error = 'The attachment size must be less then 7MB';
			         }
			       }else{
			        $error = 'Only pdf, png, jpeg, jpg, gif, svg. are allowed';
			       }
		       }else{
		       	$attach = array();
		       }
		       // no errors ? send the email then
		       if(empty($error))
		       {
		            if(empty($attach))
		            {
			            $getFromA->send_email(
			              $to, // to
			              $subject, // subject
			              $body // body
			            );
		            }else{
			             $getFromA->send_email(
			              $to, // to
			              $subject, // subject
			              $body, // body
			              [$attach['tmp_name'], $attach['name']] // attachment
			            );
		            }
		       }


	     }
?>
<div class="user-data m-b-30">
    <h3 class="title-3 m-b-30 text-center text-muted">
        <i class="zmdi zmdi-account-calendar"></i> Customers & Admins Management
    </h3>
    <div class="filters m-b-45">
       	 <button class="btn role member mt-3" data-toggle='modal' data-target='#send_email_to_all_cust'> <i class="fas fa-mail-bulk"></i> Send email to all customers </button>
    </div>

    <div class="table-responsive table-data h-100" id='users_data'>
        
    </div>
    <div align='center' class='d-flex justify-content-center align-items-center pt-3 pb-3'>
    	<input type="hidden" id='total_pages' value="<?php echo $total_pages?>">
        <ul class="pagination">
            <li class="users_manage_page" data-action='prev'>
            	<a href="#" class="page-link">Previous</a>
            </li>
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
            	<li class="users_manage_page" data-page='<?php echo $i?>'>
            		<a href="#" class="page-link"> <?php echo $i?> </a>
            	</li>
            <?php endfor; ?>
            <li class="users_manage_page" data-action='next'>
            	<a href="#" class="page-link">Next</a>
            </li>
        </ul>
    </div>
     <!-- ############ SEND email to customer modal ########### -->
        <div class="modal fade" id="send_email_to_all_cust" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLongTitle">Send an email to all Customers</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                      <label>Subject / Title</label>
                      <input type="text" class="form-control" placeholder="Your subject, what's the email is talking about ?" name="email_subj">
                    </div>
                    <div class="form-group">
                      <label>Description</label>
                     <textarea class="editor" rows="6" name="email_body">
                       <!-- the body of the email -->
                     </textarea>
                    </div>
                    <!-- <div class='or_line d-flex align-items-center justify-content-center'>
                      <span></span> OR <span></span>
                    </div> -->
                    <div class="form-group">
                     <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile" name="attach">
                        <label class="custom-file-label" for="customFile">
                         Upload an file
                        </label>
                      </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" name='send_to_all_cust'>Send it</button>
                </div>
              </form>
            </div>
          </div>
        </div>
	</div>
   <!-- ############ SEND email to customer modal ########### -->

<?php endif; ?>

<script src="https://cdn.ckeditor.com/ckeditor5/11.2.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
      .create( document.querySelector( '.editor' ) )
      .catch( error => {
          console.error( error );
      });
</script>
<?php include '../core/inc/admin_footer.php' ?>