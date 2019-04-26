<?php
include 'core/inc/init.php';
include 'core/inc/header.php';
include 'core/inc/navbar.php';

  if(!isset($_SESSION['user_id']) && empty($_SESSION['user_id'])):

      $errors = array();
      $success = false;
      if($_SERVER['REQUEST_METHOD'] === 'POST'):

         $email = $getFromU->clean_input($_POST['email']);
         $pass = $getFromU->clean_input($_POST['pass']);
         $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
         $name = $getFromU->clean_input($_POST['name']);
         $gender = $getFromU->clean_input($_POST['gender']);

          // Validate Email
          if(empty($email))
            $errors[] = 'please <b> enter your valid email </b>';
          elseif(!empty($email) && !preg_match('(\w[-._\w]*\w@\w[-._\w]*\w\.\w{2,})', $email))
            $errors[] = 'the email you entered is <b>not valid</b>';

         // Validate pass
          if(empty($pass))
            $errors[] = 'please enter your password';
          elseif(strlen($pass) < 6)
            $errors[] = 'Password must be <b> more then 6 characters </b>';

         // Validate full name
           if(empty($name))
            $errors[] = 'please <b> enter your password </b>';
           elseif(strlen($name) < 5)
            $errors[] = 'the name is too short, <b>please enter your full name</b>';
          // check if there is already an account
             $there_is_user = $getFromU->get_user_by('email', $email, ['email']);
           if(isset($there_is_user->email) && !empty($there_is_user->email))
             $errors[] = 'There is already an account with this email';

         // everything is good ? create the user account
          if(empty($errors))
          {
            $user_id = $getFromA->create('customers', [
              'email' => $email,
              'password' => $hashed_pass,
              'name' => $name,
              'gender' => $gender,
              'created_at' => date('Y-m-d H:i:s')
            ]);
             $_SESSION['user_id'] = $user_id;
             $success = true;
             if(isset($_SESSION[$user_ip]) && is_array($_SESSION[$user_ip])){
                    foreach($_SESSION[$user_ip] AS $guest_cart){
                        $getFromA->create('cart', [
                          'cart_by' => $user_id,
                          'cart_to' => $guest_cart['cart_to'],
                          'quantity' => $guest_cart['quantity'],
                          'size' => $guest_cart['size'],
                          'color' => $guest_cart['color'],
                          'price' => $guest_cart['price']
                        ]);
                    }
                  unset($_SESSION[$user_ip]);
                  header('refresh: 2; url = shoping-cart');
                }else{
                  header('refresh: 2; url = shop');
                }
          }

      endif; // end of checking server method

  else:
    header('location: shop');
  endif;
?>

 <div class="container">

    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto mt-4">
        <?php
          if(!empty($errors)){
              error_msg($errors[0]);
          }elseif($success)
            success_msg('Welcome, '.$name.' your account was successfully created');
          if(isset($_SESSION[$user_ip]) && !empty($_SESSION[$user_ip]))
          info_msg('Hey look! <b> dont worry about whats in your cart </b>, after you register your cart will stay as it is.');
        ?>
        <div class="card card-signin my-5">
          <div class="card-body">
            <h3 class="mb-3 text-center"> Join us now !</h3>
            <form class="form-signin" method="POST">
              <div class="form-label-group">
                <input type="text" id="inputEmail" class="form-control" placeholder="Email address" autofocus name="email">
                <label for="inputEmail">Email address</label>
              </div>

              <div class="form-label-group">
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" name='pass'>
                <label for="inputPassword">Password</label>
              </div>

              <div class="form-label-group">
                <input type="text" id="fullname" class="form-control" placeholder="Full name" name='name'>
                <label for="fullname">Your full name</label>
              </div>

               <select class="custom-select custom-select-lg" name='gender'>
                <option selected value="male">Male</option>
                <option value="female">Female</option>
              </select>

              <button class="btn btn-block text-uppercase login_btn w-100 green_btn mt-5" type="submit">Create my account</button>
               <b class="text-center mt-2 mb-2 d-block"> OR </b>
               <a href="login.php" class="btn web-btn-outline btn-sm w-100 text-center"> Login </a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


 <?php include 'core/inc/footer.php'; ?>
