<?php
	include 'core/inc/init.php';
	include 'core/inc/header.php';
	include 'core/inc/navbar.php';
?>
	
	<!-- Product -->
	<div class="bg0 p-b-140 products_area container-fluid">
		<div class="row">
			<!-- filter -->
			<?php include 'core/inc/filter.php' ?>
			
			<!-- Products view -->
			<div class="col-lg-9 col-md-12 row pr-2" id='loaded_products'>
				<!-- loaded products goes here -->
			</div>
			<!-- Load more -->
			<!-- <div class="flex-c-m flex-w w-full p-t-45">
				<a href="#" class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04">
					Load More
				</a>
			</div> -->
			
		</div>
	</div>


	<!-- Back to top -->
	<div class="btn-back-to-top" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="zmdi zmdi-chevron-up"></i>
		</span>
	</div>

<?php include 'core/inc/footer.php' ?>
