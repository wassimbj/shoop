<?php
// <!-- ---------------------------------------
//  ======== LOGIN PAGE =========
// --------------------------------------- -->

$inside_admin = true;
include '../core/inc/init.php';
if(isset($_SESSION['admin_id'])){
    header('location: home');
    exit;
}
$errors = array();
$success = false;
if($_SERVER['REQUEST_METHOD'] === 'POST'):
  // get email and pass
  $email = $getFromU->clean_input($_POST['admin_email']);
  $pass = $getFromU->clean_input($_POST['admin_pass']);

  if(empty($email))
    $errors[] = 'Please enter your email';
  if(empty($pass))
    $errors[] = 'Please enter your password';

  // check if there is an account with the given email
   $admin = $getFromA->get_admin_by('email', $email, ['*']);
  // verify the password
   if(empty($errors)){
        if(!empty($admin) && password_verify($pass, $admin->password)){
        // there is an account
          $success = true;
         $_SESSION['admin_id'] = $admin->id;
          header('location: index.php');
       }else{
        // there is no account
        $errors[] = 'Whoops! <b> there is no account </b> with the provided details';
       }
   }

endif;

?>    
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title> Login </title>

    <!-- Fontfaces CSS-->
    <link rel="stylesheet" href="css/font-face.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="vendor/mdi-font/css/material-design-iconic-font.min.css">

    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script> -->
    <!-- Vendor CSS-->
    <link rel="stylesheet" href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css">
    <link rel="stylesheet" href="vendor/wow/animate.css">

    <!-- Main CSS-->
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="css/theme.css">
</head>

<body>
<div class="page-wrapper">
  <div class="page-content--bge5">
     <div class="container">
        <div class="login-wrap">
         <?php
              if(!empty($errors))
                error_msg($errors[0]);
              elseif($success)
                success_msg('Successfully logged in, welcome back, {$admin->name}');
            ?>
           <div class="login-content">
               <div class="login-logo">
                   <h3> Admin Login </h3>
               </div>
               <div class="login-form">
                 <form method="POST">
                   <div class="form-group">
                       <label>Email Address</label>
                       <input class="au-input au-input--full" type="email" name="admin_email" placeholder="Email">
                   </div>
                   <div class="form-group">
                       <label>Password</label>
                       <input class="au-input au-input--full" type="password" name="admin_pass" placeholder="Password">
                   </div>
                    <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">
                      sign in
                    </button>
                 </form>
               </div>
           </div>
        </div>
     </div>
  </div>

</div>

    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Main JS-->
    <script src="js/main.js"></script>

</body>

</html>
<!-- end document-->