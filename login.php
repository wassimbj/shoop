<?php
	include 'core/inc/init.php';
	include 'core/inc/header.php';
	include 'core/inc/navbar.php';

  if(!isset($_SESSION['user_id']) && empty($_SESSION['user_id'])):
        $errors = array();
        $success = false;

        if($_SERVER['REQUEST_METHOD'] === 'POST'):
          // get email and pass
          $email = $getFromU->clean_input($_POST['user_email']);
          $pass = $getFromU->clean_input($_POST['user_pass']);

          // check if there is an account with the given email
           $user = $getFromU->get_user_by('email', $email ,['*']);
          // verify the password
           if(isset($user->password) && !empty($user->password) && password_verify($pass, $user->password)){
            // there is an account
                $success = true;
                 $_SESSION['user_id'] = $user->id;
                if(isset($_SESSION[$user_ip]) && is_array($_SESSION[$user_ip])){
                    foreach($_SESSION[$user_ip] AS $guest_cart){
                        $getFromA->create('cart', [
                          'cart_by' => $user->id,
                          'cart_to' => $guest_cart['cart_to'],
                          'quantity' => $guest_cart['quantity'],
                          'size' => $guest_cart['size'],
                          'color' => $guest_cart['color'],
                          'price' => $guest_cart['price']
                        ]);
                    }
                  unset($_SESSION[$user_ip]);
                  header('location: shoping-cart');
                }else{
                  header('location: shop');
                }

           }else{
            // there is no account
            $errors[] = 'Whoops! <b> there is no account </b> with the provided details';
           }

        endif;

  else:
    header('location: shop');
  endif;
?>

 <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto mt-4">
        <?php
          if(!empty($errors))
            error_msg($errors[0]);
          elseif($success)
            success_msg('Successfully logged in, welcome back, {$user->name}');
        ?>
        <div class="card card-signin my-5">
          <div class="card-body">
            <h3 class="mb-3 text-center"> Login to your account </h3>
            <form class="form-signin" method="POST">
              <div class="form-label-group">
                <input type="email" id="inputEmail" class="form-control" placeholder="Email address" autofocus name='user_email'>
                <label for="inputEmail">Email address</label>
              </div>

              <div class="form-label-group">
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" name='user_pass'>
                <label for="inputPassword">Password</label>
              </div>

              <div class="custom-control custom-checkbox mb-3">
                <input type="checkbox" class="custom-control-input" id="customCheck1">
                <label class="custom-control-label" for="customCheck1">Remember password</label>
              </div>

              <button class="btn web-btn btn-block text-uppercase mt-5" type="submit">Sign in</button>
              <b class="text-center mt-2 mb-2 d-block"> OR </b>
              <a href="join.php" class="green_btn w-100 text-center"> Create an account </a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


 <?php include 'core/inc/footer.php'; ?>
