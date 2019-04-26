<?php
$inside_admin = true;
include '../core/inc/init.php';

?>

<!-- ################# Fetch sub categorys ################# -->
<?php if(isset($_POST['c_id'])):
	// sub category fetch
	$sub_c = $getFromA->getAll('sub_category', 'where parent = '.$_POST['c_id'].'');
?>
	<?php if(!empty($sub_c)): ?>
		<label>Sub-category</label>
		 <select class="custom-select" name='sub_category'>
			<?php foreach($sub_c AS $subc): ?>
		    	<option value="<?php echo $subc->name ?>"><?php echo $subc->name ?></option>
		    <?php endforeach; ?>
		</select>
	  <small> Just select the sub category, this wil help the customer to find what he need faster </small>
  <?php else: ?>
   	 <p> No sub category found for this category </p>
   	 <p> You can add a sub category here <a class='btn btn-primary' href="./categorys.php"> add </a> </p>
   <?php endif; ?>

<?php endif; ?>


<!-- ################### Fetch Users ################## -->
<?php if(isset($_POST['users_pagenum'])):
	// USERS fetch
	$page = $_POST['users_pagenum'];
	$records_per_page = 3;
	$start_from = ($page - 1) * $records_per_page;
	 // Get the users with the condtions: page & records
	$admins = $getFromA->get_with_limit('admins', $start_from, $records_per_page);
	$customers = $getFromA->get_with_limit('customers', $start_from, $records_per_page);
?>

<table class="table">
            <thead>
                <tr>
                    <td>
                       ID
                    </td>
                    <td>name</td>
                    <td>role</td>
                    <td>joined/added on</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
             <?php
             if(!empty($admins)):
               foreach($admins AS $admin):
             ?>
                <tr>
                    <td>
                        <?php echo $admin->id ?>
                    </td>
                    <td>
                        <div class="table-data__info">
                            <h6> <?php echo $admin->name;
                             if(isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $admin->id)
                             	echo '(YOU)';
                           	?>
                           	</h6>
                            <span>
                                <a href="#"> <?php echo $admin->email ?></a>
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="role user">admin</span>
                    </td>
                    <td>
                        <?php  echo time_ago($admin->created_at) ?>
                    </td>
                    <td>
     					<div class="table-data-feature">
                            <!-- <button class="item" data-toggle="modal">
                                <i class="fas fa-envelope-open"></i>
                            </button> -->
                            <button class="item" data-toggle="modal" data-target='#edit_<?php echo $admin->id?>'>
                                <i class="zmdi zmdi-edit"></i>
                            </button>
                            <button type='button' class="item" data-toggle="modal" data-target='#delete_<?php echo $admin->id?>'>
                                <i class="zmdi zmdi-delete"></i>
                            </button>
                        </div>
                    </td>
             	<td class="p-0">
         		   <!-- DELETE ADMIN MODAL -->
					<div class="modal fade" id="delete_<?php echo $admin->id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLongTitle">Are you sure ?</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					     <form method="POST" action="./users.php">
				     		 <div class="modal-body">
				        		Do you really wanna remove this admin ?
				        		<input type="hidden" name="admin_to_delete" value="<?php echo $admin->id?>">
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						        <button type="submit" class="btn btn-primary">Yes remove</button>
						      </div>
					     </form>
					    </div>
					  </div>
					</div>
					<!-- DELETE ADMIN MODAL -->
             	</td>
             	<td class="p-0">
		         <!-- EDIT ADMIN MODAL -->
					<div class="modal fade" id="edit_<?php echo $admin->id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLongTitle">
					        	edit admin <b>#<?php echo $admin->id?></b>
					        </h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					     <form method="POST" action="./users.php">
				     		 <div class="modal-body">
				        		<input type="hidden" name="admin_to_edit" value="<?php echo $admin->id?>">
				        		<div class="form-group">
				        		<label> enter the new email </label>
							     <input type="email" name='new_admin_email' class="form-control" value="<?php echo $admin->email?>">
							    </div>
							     <div class="form-group">
							      <label> enter the new name </label>
							      <input type="text" name='new_admin_name' class="form-control" value="<?php echo $admin->name?>">
							    </div>
							    <div class="form-group">
							      <label> enter the new password </label>
							      <input type="text" name='new_admin_pass' class="form-control" placeholder="*********">
							    </div>

						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						        <button type="submit" class="btn btn-success">Save changes</button>
						      </div>
					     </form>
					    </div>
					  </div>
					</div>
					<!-- EDIT ADMIN MODAL -->
	     		   </td>
                </tr>
             
               <?php endforeach;
           		endif;
               ?>

               <?php 
               	   if(!empty($customers)):
               		foreach($customers AS $customer):
               	?>
                <tr>
                    <td>
                        <?php echo $customer->id ?>
                    </td>
                    <td>
                        <div class="table-data__info">
                            <h6> <?php echo $customer->name ?></h6>
                            <span>
                                <a href="#"> <?php echo $customer->email ?></a>
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="role member">customer</span>
                    </td>
                    <td>
                        <?php  echo time_ago($customer->created_at) ?>
                    </td>
                    <td>
     					<div class="table-data-feature">
                            <button class="item" data-toggle="modal" data-target='#editcust_<?php echo $customer->id?>'>
                                <i class="zmdi zmdi-edit"></i>
                            </button>
                            <button class="item" data-toggle="modal" data-target='#deletecust_<?php echo $customer->id?>'>
                                <i class="zmdi zmdi-delete"></i>
                            </button>
                        </div>
                    </td>
	     			<td class="p-0">
 				         <!-- DELETE CUSTOMER MODAL -->
						<div class="modal fade" id="deletecust_<?php echo $customer->id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
						  <div class="modal-dialog modal-dialog-centered" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <h5 class="modal-title" id="exampleModalLongTitle">Are you sure ?</h5>
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						          <span aria-hidden="true">&times;</span>
						        </button>
						      </div>
						     <form method="POST" action="./users.php">
					     		 <div class="modal-body">
					        		Do you really wanna remove this customer ?
					        		<input type="hidden" name="customer_to_delete" value="<?php echo $customer->id?>">
							      </div>
							      <div class="modal-footer">
							        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							        <button type="submit" class="btn btn-danger">Yes remove</button>
							      </div>
						     </form>
						    </div>
						  </div>
						</div>
						<!-- DELETE CUSTOMER MODAL -->
	     			</td>
	     			<td class="p-0">
 				         <!-- EDIT CUSTOMER MODAL -->
						<div class="modal fade" id="editcust_<?php echo $customer->id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
						  <div class="modal-dialog modal-dialog-centered" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <h5 class="modal-title" id="exampleModalLongTitle">
						        	Edit user <b>#<?php echo $customer->id?></b>
						        </h5>
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						          <span aria-hidden="true">&times;</span>
						        </button>
						      </div>
						     <form method="POST" action="./users.php">
						     	<input type="hidden" name="customer_to_edit" value="<?php echo $customer->id?>">
					     		 <div class="modal-body">
					        		<div class="form-group">
					        		<label> enter the new email </label>
								     <input type="email" name='new_cust_email' class="form-control" value="<?php echo $customer->email?>">
								    </div>
								     <div class="form-group">
								     <label> enter the new name </label>
								     <input type="text" name='new_cust_name' class="form-control" value="<?php echo $customer->name?>">
								    </div>
							      </div>
							      <div class="modal-footer">
							        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							        <button type="submit" class="btn btn-success">Save changes</button>
							      </div>
						     </form>
						    </div>
						  </div>
						</div>
						<!-- EDIT CUSTOMER MODAL -->
	     			</td>
                </tr>
               <?php 
           		  endforeach;
           	   endif;
               ?>
            </tbody>
        </table>

<?php endif; ?>
