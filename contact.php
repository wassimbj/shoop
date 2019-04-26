<?php
	include 'core/inc/init.php';
	include 'core/inc/header.php';
	include 'core/inc/navbar.php';
  // Shop front-end
   $shop_front = $getFromA->getAll('shop_front');



// Send email
   $errors = array();
if(isset($_POST['from']) && isset($_POST['msg']))
{
	if(empty($_POST['from']))
		$errors[] = 'Please enter your email, so we can respond back to you';

	if(empty($_POST['msg']))
		$errors[] = 'Please type in your message';

	if(empty($errors)){
		$subject = 'Some one needs help in your shop';
		if($getFromU->contact(
			$_POST['from'], // from
			$shop_front[0]->email,
			$subject,
			$_POST['msg']
		)){
			$success = true;
		}else{
			$errors[] = 'Oops! something went wrong, please try again later';
		}
	}

}
?>

	<!-- Title page -->
	<section class="bg-img1 txt-center p-lr-15 p-tb-92 for_bg_imgs" style="background-image: url('https://images.pexels.com/photos/789822/pexels-photo-789822.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260');">
		<h2 class="ltext-105 cl0 txt-center">
			Contact
		</h2>
	</section>


	<!-- Content page -->
	<section class="bg0 p-t-104 p-b-116">
		<div class="container">
			<div class="flex-w flex-tr">
				<div class="size-210 bor10 p-lr-70 p-t-55 p-b-70 p-lr-15-lg w-full-md">
					<?php
					 if(!empty($errors))
					 	error_msg($errors[0]);
					 elseif(isset($success))
					 	success_msg('Well done! we will get back to you asap');
					?>
					<form method="POST">
						<h4 class="mtext-105 cl2 txt-center p-b-30">
							Send Us A Message
						</h4>

						<div class="bor8 m-b-20 how-pos4-parent">
							<input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="text" name="from" placeholder="Your Email Address">
							<img class="how-pos4 pointer-none" src="images/icons/icon-email.png" alt="ICON">
						</div>

						<div class="bor8 m-b-30">
							<textarea class="stext-111 cl2 plh3 size-120 p-lr-28 p-tb-25" name="msg" placeholder="How Can We Help?"></textarea>
						</div>

						<button class="flex-c-m stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer" type="submit">
							Send
						</button>
					</form>
				</div>

				<div class="size-210 bor10 flex-w flex-col-m p-lr-93 p-tb-30 p-lr-15-lg w-full-md">
					<div class="flex-w w-full p-b-42">
						<span class="fs-18 cl5 txt-center size-211">
							<span class="lnr lnr-map-marker"></span>
						</span>

						<?php if(!empty($shop_front[0]->address)): ?>
							<div class="size-212 p-t-2">
								<span class="mtext-110 cl2">
									Address
								</span>

								<p class="stext-115 cl6 size-213 p-t-18">
									<?php echo $shop_front[0]->address ?>
								</p>
							</div>
						<?php endif; ?>
					</div>

					<div class="flex-w w-full p-b-42">
						<span class="fs-18 cl5 txt-center size-211">
							<span class="lnr lnr-phone-handset"></span>
						</span>
							<?php if(!empty($shop_front[0]->phone)): ?>
								 <div class="size-212 p-t-2">
									<span class="mtext-110 cl2">
										Lets Talk
									</span>

									<p class="stext-115 cl1 size-213 p-t-18">
										<?php echo $shop_front[0]->phone ?>
									</p>
								</div>
						   <?php endif; ?>
					</div>

					<div class="flex-w w-full">
						<span class="fs-18 cl5 txt-center size-211">
							<span class="lnr lnr-envelope"></span>
						</span>

						<div class="size-212 p-t-2">
							<span class="mtext-110 cl2">
								Sale Support
							</span>

							<p class="stext-115 cl1 size-213 p-t-18">
								<?php echo $shop_front[0]->email ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


<?php if(!empty($shop_front[0]->map)){
  $map = json_decode($shop_front[0]->map);
     if(!empty($map->key) && !empty($map->loc)){
?>

<!-- Map -->
<div class="map mb-0">
	<?php if($map->service == 'paid'): ?>
	   <!-- ** Paid service ** -->
		<div class="size-303" id="google_map" data-map-x="<?php echo $map->lat?>" data-map-y="<?php echo $map->lng?>" data-pin="images/icons/pin.png" data-scrollwhell="0" data-draggable="1" data-zoom="11"></div>
	<?php else: ?>
	   <!-- ## Free Service ## -->
	  <iframe width="100%" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=<?php echo $map->lat.','.$map->lng?>&key=<?php echo $map->key ?>"></iframe> 
	<?php endif; ?>
</div>
<?php if($map->service == 'paid'){ ?>
	<!-- ** related to the paid service ** -->
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $map->key ?>"></script>
<?php } ?>

<?php
     }
   }
?>


<!-- ********************** !! WEB PAGE FOOTER !! ********************* -->
<?php include 'core/inc/footer.php' ?>
<!-- *********************** !! WEB PAGE FOOTER !! ********************* -->


<!-- ** related to the paid service ** -->
<?php  if(isset($map) && $map->service == 'paid' && (!empty($map->key) || !empty($map->loc))):?>
<script src="./js/map-custom.js"></script>
<?php  endif; ?>
