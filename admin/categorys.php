<?php
##############################################
# ================ ADD CATEGORIES & SUB-CATEGORIES ===============
##############################################
$inside_admin = true;
include '../core/inc/init.php';
if(!isset($_SESSION['admin_id'])){
	header('location: login.php');
	exit;
}
include '../core/inc/admin_header.php';
?>

<?php if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] == 'manage_cate'):
###################################################
#================ MANAGE CATEGORYS ===============
###################################################

$errors = array();
######### Parent categories ########
// edit
if(isset($_POST['edit_cate']))
{
	$new_name = $_POST['cate_name'];
	$new_appear = $_POST['appear'];
	$id = $_POST['cate_id'];
	if(empty($new_name))
		$errors[] = 'Please enter the category name';

	if(empty($errors)){
		$getFromA->update('categories', 'id ='.$id.'', [
			'name' => $new_name,
			'appear' => $new_appear
		]);
		$success = 'Great, the category was <b>successfully updated</b>';
	}
}
// Remove
if(isset($_POST['rem_cate']) && !empty($_POST['cate_id']))
{
	$cate_id = $_POST['cate_id'];
	$getFromA->delete('categories', ['id' => $cate_id]);
	$success = 'Nice, the category was <b>deleted successfully</b>';
}

########## Sub-category #########
if(isset($_POST['edit_subcate']))
{
	$new_name = $_POST['subcate_name'];
	$new_appear = $_POST['sub_appear'];
	$id = $_POST['subcate_id'];
	if(empty($new_name))
		$errors[] = 'Please enter the category name';

	if(empty($errors)){
		$getFromA->update('sub_category', 'id ='.$id.'', [
			'name' => $new_name,
			'appear' => $new_appear
		]);
		$success = 'Great, the sub-category was <b>successfully updated</b>';
	}
}
// Remove
if(isset($_POST['rem_subcate']) && !empty($_POST['subcate_id']))
{
	$subcate_id = $_POST['subcate_id'];
	$getFromA->delete('sub_category', ['id' => $subcate_id]);
	$success = 'Nice, the sub-category was <b>deleted successfully</b>';
}



// get all catgeories
$cates = $getFromA->getAll('categories');
?>

<div class="container">
  <h1 class="text-center mb-5 text-muted"> Manage Categories </h1>
<?php 
	 if(!empty($errors))
	 	error_msg($errors[0]);
 	elseif(isset($success) && !empty($success))
 		success_msg($success);
?>

 <div class="accordion" id="accordionExample">

	  <?php foreach($cates AS $cate):
		// get all sub categorys
		$sub_c = $getFromA->getAll('sub_category', 'where parent ='.$cate->id.'');
	  ?>
	    <div class="card mb-0">
		    <div class="card-header" id="headingTwo">
		      <div class="mb-0 d-flex justify-content-between">
		        <button class="btn collapsed font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse_<?php echo $cate->id?>" aria-expanded="false">
		          <?php echo $cate->name ?> <span class="text-muted"><i class="fas fa-chevron-down"></i></span>
		        </button>
	        	<div class="table-data-feature">
                    <button class="item" data-toggle="modal" data-target='#editcate_<?php echo $cate->id?>'>
                        <i class="zmdi zmdi-edit"></i>
                    </button>
                    <button type='button' class="item" data-toggle="modal" data-target='#deletecate_<?php echo $cate->id?>'>
                        <i class="zmdi zmdi-delete"></i>
                    </button>
                </div>
		      </div>
		    </div>
		    <div id="collapse_<?php echo $cate->id?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
		      <div class="card-body">
		        <div class="d-flex justify-content-around flex-wrap">
				   	  <?php 
				   	    if(!empty($sub_c)):
				   	       foreach($sub_c AS $sub):
				   	  ?>
				   	  	<div class="btn btn-dark d-flex align-items-center mb-3">
					      <span class="mr-3"><?php echo $sub->name?></span>
					      <span>
				      		<div class="table-data-feature">
			                    <button class="item" data-toggle="modal" data-target='#editsub_<?php echo $sub->id?>'>
			                        <i class="zmdi zmdi-edit"></i>
			                    </button>
			                    <button type='button' class="item" data-toggle="modal" data-target='#deletesub_<?php echo $sub->id?>'>
			                        <i class="zmdi zmdi-delete"></i>
			                    </button>
			                </div>
					      </span>
	 				      <!-- DELETE SUB-CATEGORY MODAL -->
							<div class="modal fade" id="deletesub_<?php echo $sub->id?>" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
							  <div class="modal-dialog modal-dialog-centered" role="document">
							    <div class="modal-content">
							      <div class="modal-header">
							        <h5 class="modal-title" id="exampleModalLongTitle">Are you sure ?</h5>
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							          <span aria-hidden="true">&times;</span>
							        </button>
							      </div>
							     <form method="POST" action="./categorys.php?page=manage_cate">
						     		 <div class="modal-body">
						        		Do you really wanna remove this sub-category ? <b>"<?php echo $sub->name?>"</b>
						        		<input type="hidden" name="subcate_id" value="<?php echo $sub->id?>">
								      </div>
								      <div class="modal-footer">
								        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								        <button type="submit" class="btn btn-danger" name='rem_subcate'>Yes remove</button>
								      </div>
							     </form>
							    </div>
							  </div>
							</div>
							<!-- DELETE SUB-CATEGORY MODAL -->

	 				         <!-- EDIT SUB-CATEGORY MODAL -->
							<div class="modal fade" id="editsub_<?php echo $sub->id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
							  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
							    <div class="modal-content">
							      <div class="modal-header">
							        <h5 class="modal-title" id="exampleModalLongTitle">
							        	Edit sub-category: <b><?php echo $sub->name?></b>
							        </h5>
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							          <span aria-hidden="true">&times;</span>
							        </button>
							      </div>
							     <form method="POST" action="./categorys.php?page=manage_cate">
							     	<input type="hidden" name="subcate_id" value="<?php echo $sub->id?>">
						     		 <div class="modal-body">
									      <div class="form-group">
										    <label> Category name </label>
										    <div class="input-group">									        
									           <input type="text" name='subcate_name' class="form-control" value="<?php echo $sub->name?>" placeholder="e.g: 50, 12...">
									        </div>
										  </div>
										  <div class="form-group">
										    <label> appearance </label>
										    <div class="input-group">
									             <select class="custom-select my-1 mr-sm-2" id="appear" name="sub_appear">
												    <option selected value="1">Yea let it appear</option>
												    <option value="0">Naah i dont want it to</option>
												  </select>
									        </div>
										  </div>
								      </div>
								      <div class="modal-footer">
								        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								        <button type="submit" class="btn btn-success" name='edit_subcate'>Save changes</button>
								      </div>
							     </form>
							    </div>
							  </div>
							</div>
							<!-- EDIT SUB-CATEGORY MODAL -->
					    </div>
				   	  <?php
				   			endforeach;
				   		else: 
				   			echo '<p>No sub category found ! <a href="./categorys.php" class="btn btn-link"> Add one </a> </p>';
				   	    endif;
				   	  ?>
				  </div>
		      </div>
		    </div>
		       <!-- DELETE Category MODAL -->
				<div class="modal fade" id="deletecate_<?php echo $cate->id?>" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
				  <div class="modal-dialog modal-dialog-centered" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title" id="exampleModalLongTitle">Are you sure ?</h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				     <form method="POST" action="./categorys.php?page=manage_cate">
			     		 <div class="modal-body">
			        		Do you really wanna remove this category ? <b>"<?php echo $cate->name?>"</b>
			        		<input type="hidden" name="cate_id" value="<?php echo $cate->id?>">
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					        <button type="submit" class="btn btn-danger" name='rem_cate'>Yes remove</button>
					      </div>
				     </form>
				    </div>
				  </div>
				</div>
				<!-- DELETE Category MODAL -->
			    <!-- EDIT Category MODAL -->
				<div class="modal fade" id="editcate_<?php echo $cate->id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
				  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title" id="exampleModalLongTitle">
				        	Edit category: <b><?php echo $cate->name?></b>
				        </h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				     <form method="POST" action="./categorys.php?page=manage_cate">
				     	<input type="hidden" name="cate_id" value="<?php echo $cate->id?>">
			     		 <div class="modal-body">
						      <div class="form-group">
							    <label> Category name </label>
							    <div class="input-group">									        
						           <input type="text" name='cate_name' class="form-control" value="<?php echo $cate->name?>" placeholder="e.g: 50, 12...">
						        </div>
							  </div>
							  <div class="form-group">
							    <label> appearance </label>
							    <div class="input-group">
						             <select class="custom-select my-1 mr-sm-2" id="appear" name="appear">
									    <option selected value="1">Yea let it appear</option>
									    <option value="0">Naah i dont want it to</option>
									  </select>
						        </div>
							  </div>
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					        <button type="submit" class="btn btn-success" name='edit_cate'>Save changes</button>
					      </div>
				     </form>
				    </div>
				  </div>
				</div>
				<!-- EDIT Category MODAL -->
		  </div>

	  <?php endforeach; ?>

</div>



</div>

<?php else:
###################################################
#================= ADD CATEGORYS =================
###################################################
// Get all categorysb 
$categorys = $getFromA->getAll('categories');

 $errors = array();
 if(isset($_POST['add_cate']) && !empty($_POST['name'])):
 	$c_name = $_POST['name'];
 	$c_parent = $_POST['parent'];
 	$appear = $_POST['appear'];

 	// make some validation
 	if(empty($c_name))
 		$errors[] = 'Please enter the category name';
 	else
 		$category = explode(',', $c_name);

 	if(!in_array($appear, ['0', '1']))
 		$errors[] = 'Hey!! did you play with the appear html value ?';

 	if(empty($errors))
 	{
	 	if(is_array($category) && !empty($category))
	 	{
	 				// many categorys
				if($c_parent == 0)
				{ // this a main category
		 			foreach ($category as $c)
		 			{
			 				$getFromA->create(
			 					'categories',
			 					['name' => $c,
			 					'appear' => $appear,
			 					'created_at' => date('Y-m-d H:i:s')
			 					]
			 				);
				 	}
		 		}else{
		 		   foreach ($category as $c)
		 		   {
			 			$getFromA->create(
		 					'sub_category',
		 					 ['name' => $c,
		 					 'parent' => $c_parent,
		 					 'appear' => $appear,
		 					 'created_at' => date('Y-m-d H:i:s')
		 					 ]
		 				  );
		 		   }
		 		}
	    }
	   
	    $success = 'Great! the category successfully added';
 	}

 endif;

?>
<div class="container" style="height: 100vh">
  <h1 class="text-center mb-5 text-muted"> Add Categories </h1>
	   <?php 
	  	 if(!empty($errors))
	  	 	error_msg($errors[0]);
	     elseif(isset($success) && !empty($success))
	     	success_msg($success);
	    ?>
	<?php
	  info_msg('you can add many categorys, just seperate them with comma <b>[,]</b>');
	?>
	<form method="POST">
		 <div class="form-group">
		    <label>Category name</label>
		      <input type="text" class="form-control" name='name' placeholder="e.g: men,women,electronics...">
		  </div>
	   <div class="form-group">
	   	 <label class="my-1 mr-2" for="parent">Parent</label>
		  <select class="custom-select my-1 mr-sm-2" id="parent" name="parent">
		    <option selected value="0">This is a main/parent category</option>
		    <?php foreach($categorys AS $cate): ?>
		    	<option value="<?php echo $cate->id ?>"><?php echo $cate->name ?></option>
		    <?php endforeach; ?>
		  </select>
	   </div>
	   <div class="form-group">
	   	 <label class="my-1 mr-2" for="appear" id='cate_appear_msg'>
	   	 	Do you want this main category to appear in the Home, <abbr data-toggle="tooltip" title="The menu is the navbar">Menu</abbr> and in the Filter ?
	   	 </label>
		  <select class="custom-select my-1 mr-sm-2" id="appear" name="appear">
		    <option selected value="1">Yea let it appear</option>
		    <option value="0">Naah i dont want it to</option>
		  </select>
	   </div>
		<button class="btn btn-primary btn-large w-100 mt-4 p-2" name='add_cate'> ADD CATEGORY </button>
	</form>

</div>
<?php endif; ?>



<?php include '../core/inc/admin_footer.php' ?>