<!-- filter -->
<?php
	###################### Filters ######################

	$sort = [ 
			  'Newness' => 'order by created_at desc',
			  'Price: Low to High' => 'order by price asc',
			  'Price: High to Low' => 'order by price desc'
			];

	$price = [
			  'All' => 'price between 0 and 1000000',
			  '$0.00 - $50' => 'price between 10 and 50',
			  '$50 - $100' => 'price between 50 and 100',
			  '$100 - $150' => 'price between 100 and 150',
			  '$150 - $200' => 'price between 150 and 200',
			  '$200+' => 'price > 200'
			];

	$color = [
				'black' => ['#000' => 'color like "%" "black" "%"'],
				'grey' => ['#b3b3b3' => 'color like "%" "grey" "%"'],
				'blue' => ['#0000FF' => 'color like "%" "blue" "%"'],
				'pink' => ['#FF69B4' => 'color like "%" "pink" "%"'],
				'White' => ['#FFFAF0' => 'color like "%" "white" "%"'],
				'yellow' => ['#f9ff21' => 'color like "%" "yellow" "%"'],
				'red' => ['#FF0000' => 'color like "%" "red" "%"'],
    			'purple' => ['#503bff' => 'color like "%" "purple" "%"'],
    			'brown' => ['#A0522D' => 'color like "%" "brown" "%"'],
    			'orange' => ['#FF8C00' => 'color like "%" "orange" "%"'],
				'Green' => ['#00b660 ' => 'color like "%" "green" "%"']
			 ];

 $category = array();
 $cates = $getFromA->getAll('categories', 'where appear = 1');
 	foreach ($cates as $cate) {
 		$category[$cate->name] = 'category = "'.$cate->name.'"';
 	}
?>

<div class="col-lg-3 col-md-12 mr-2 mb-5">
<!-- Filter -->
	<div class="bg6 w-full pt-3 accordion" id="accordionExample">
		<div class="text-center d-block mb-4">
			<h4 class="text-muted mb-2"> Filter products </h4>
			<a href="#" class='btn btn-sm btn-secondary text-white' id='clear_filter'> Clear filter </a>
		</div>
		<!-- ################## Sort BY ###################"" -->
			<div>
				<div class="mtext-102 cl2 p-b-15 filter_collapse_btn p-3 bg-white rounded" data-toggle="collapse" data-target="#collapse1">
					<span><i class="fas fa-sort-amount-down"></i></span> Sort By
				</div>

				<ul id="collapse1" class="collapse filter_collapse show pl-3 mt-2" aria-labelledby="headingOne" data-parent="#accordionExample">
					<?php foreach($sort AS $key => $val):?>
						<li class="p-b-6">
							<div class="custom-control custom-radio custom-control-inline">
							  <input type="radio" id="<?php echo $key?>" name="sort" class="custom-control-input sort" value='<?php echo $val ?>'>
							  <label class="custom-control-label" for="<?php echo $key?>"><?php echo $key ?></label>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<!-- ################ Price #################"" -->
			<div>
				<div class="mtext-102 cl2 p-b-15 filter_collapse_btn p-3 bg-white rounded" data-toggle="collapse" data-target="#collapse2">
					<span><i class="fas fa-dollar-sign"></i></span> Price
				</div>

				<ul id="collapse2" class="collapse filter_collapse pl-3 mt-2" aria-labelledby="headingOne" data-parent="#accordionExample">
					<?php foreach($price AS $key => $val): ?>
						<li class="p-b-6">
							<div class="custom-control custom-radio custom-control-inline">
							  <input type="radio" class="custom-control-input filter_btn" name='price' id="<?php echo $key?>" value='<?php echo $val ?>'>
							  <label class="custom-control-label" for="<?php echo $key?>"><?php echo $key ?></label>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<!-- ################ Color ################## -->
			<div>
				<div class="mtext-102 cl2 p-b-15 filter_collapse_btn p-3 bg-white rounded" data-toggle="collapse" data-target="#collapse3">
					<span><i class="fas fa-fill-drip"></i></span> Color
				</div>

				<ul id="collapse3" class="collapse filter_collapse pl-3 mt-2" aria-labelledby="headingOne" data-parent="#accordionExample">
				 <div class="d-flex flex-wrap">
					<?php foreach($color AS $key => $val): ?>
						<?php foreach($val AS $color => $cond): ?>
							<li class="p-b-6">
							 <div class="custom-control custom-radio custom-control-inline">
							  <input type="radio" class="custom-control-input filter_btn" id="<?php echo $key?>" value='<?php echo $cond ?>' name='filter_bycolor'>
							  <label class="custom-control-label filter_by_color rounded-circle img-thumbnail"
							  		 for="<?php echo $key?>"
							  		 style='background: <?php echo $color?>; width: 30px; height: 30px;
							  		 	   cursor: pointer;'>
							  </label>
							</div>
							</li>
						<?php endforeach; ?>
					<?php endforeach; ?>
				  </div>
			   </ul>
			</div>
		<!-- ################### Category ################### -->
		 	 <div>
				<div class="mtext-102 cl2 p-b-15 filter_collapse_btn p-3 bg-white rounded" data-toggle="collapse" data-target="#collapse4">
					<span><i class="fas fa-tags"></i></span> Category
				</div>

				<ul id="collapse4" class="collapse filter_collapse pl-3 mt-2" aria-labelledby="headingOne" data-parent="#accordionExample">
					<?php foreach($category AS $key => $val): ?>
						<li class="p-b-6">
							<!-- <div class="custom-control custom-checkbox">
							  <input type="checkbox" class="custom-control-input filter_btn" id="<?php echo $key?>" value='<?php echo $val ?>'>
							  <label class="custom-control-label" for="<?php echo $key?>"><?php echo $key ?></label>
							</div> -->
							<div class="custom-control custom-radio custom-control-inline">
							  <input type="radio" class="custom-control-input filter_btn" name='category' id="<?php echo $key?>" value='<?php echo $val ?>'>
							  <label class="custom-control-label" for="<?php echo $key?>"><?php echo $key ?></label>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
	</div>
</div>