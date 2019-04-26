<?php
###########################################
# ========= Manage orders here ==========
###########################################
$inside_admin = true;
include('../core/inc/init.php');
if(!isset($_SESSION['admin_id'])){
  header('location: login.php');
  exit;
}
include('../core/inc/admin_header.php');
  $error = ''; 
  if(isset($_POST['customer_email']) && !empty($_POST['customer_email']))
  {
      $subject = $_POST['email_subj'];
      $body = $_POST['email_body'];
      $attach = $_FILES['attach'];
      $to = $_POST['customer_email'];
      // validate the attach
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
       if( empty($error) )
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

  //  Update the order status
  if(isset($_POST['status']) && isset($_POST['order_id'])){
    $orderid = $_POST['order_id'];
    $new_status = $_POST['status'];
    $getFromA->update(
      'orders',
      'id ='.$orderid.'',
      ['status' => $new_status]
    );
  }

  // Delete order
  if(isset($_POST['del_order']) && isset($_POST['order_to_del']) && !empty($_POST['order_to_del'])){
    $getFromA->delete('orders', ['id' => $_POST['order_to_del']]);
    success_msg('Success! the order was <b> successfully deleted </b>');
  }
?>
<div class="container-fluid pt-5">
       <?php if(isset($_GET['order_id']) && isset($_GET['action']) && $_GET['action'] == 'show-order'):
            $order = $getFromA->getBy('orders', ['id' => intval($_GET['order_id'])]);
       ?>
       <!-- ###################### Show the order ###################### -->
            <?php if(!empty($order)):
                $order_items = array();
                $purchaser = $getFromA->getBy('customers', ['id' => $order->order_by]);
                if(!empty($purchaser)):
                  foreach (json_decode($order->order_items) as $purchased) {
                      $order_items[] = $getFromA->getBy('cart', ['id' => $purchased]);
                  }
                  $ship_adrs = $getFromA->getBy('shipping', ['ship_by' => $order->order_by]);
             ?>
                <a href='./orders.php' class="btn btn-primary rounded mb-4"> Go back </a>
                 <div class='pb-2 shadow-sm mb-5 bg-white rounded'>
                    <h3 class='table-dark text-center pb-2 pt-2'>
                        Order details
                    </h3>
                    <div class='p-2'>
                      
                      <div style='border: 2px solid rgba(0,0,0,0.3); border-radius: 5px'>
                        <div class='d-flex align-items-center justify-content-between p-2'>
                          <div><p> Order No: <mark><b> #<?php echo $order->id?> </b></mark> </p></div>
                          <div>
                            <div>
                              <p>
                                Order date:
                                <mark>
                                  <b>
                                  <?php echo date('d/m/y g:i A', strtotime($order->created_at)) ?>
                                 </b>
                                </mark>
                              </p>
                            </div>
                            <div>
                               <p> Status:
                               <b class='<?php echo $order->status == 'pending' ? 'status--pending' : 'status--process'?>'>
                                 <?php echo $order->status?>
                               </b>
                                </p>
                              </div>
                          </div>
                        </div>
                        
                        <div class='table-responsive'>
                          <table class='table table-striped w-100'>
                            <thead>
                              <tr>
                                <th scope='col'>Product</th>
                                <th scope='col'>Qntty</th>
                                <th scope='col'>Size</th>
                                <th scope='col'>Color</th>
                                <th scope='col'>Price</th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php foreach($order_items AS $item):
                                $product_he_purchased = $getFromA->getBy('products', ['id' => $item->cart_to]);
                            ?>
                             <tr>
                                  <td style='width: 35%'><?php echo $product_he_purchased->name?></td>
                                  <td><?php echo $item->quantity?></td>
                                  <td><?php echo $item->size?></td>
                                  <td><?php echo $item->color?></td>
                                  <td><?php echo '$'.$product_he_purchased->price?> </td>
                                </tr>
                            <?php endforeach; ?>
                             
                    </tbody>
                          </table>
                        </div>
                        <hr>
                        <div class='text-right p-3'>
                          <h3 class='font-weight-bold'>Total: <mark><?php echo '$'.$order->amount?></mark></h3>
                        </div>
                        <div class='p-2 mt-2' style='background-color: #f1f1f1f1'>
                           <div>
                               <h5 class='text-success font-weight-bold'>
                                <i class='fas fa-map-marker-alt'></i> Delivery to
                               </h5>
                            
                          <?php foreach($ship_adrs AS $key => $val ):
                            if($key !== 'id' && $key !== 'ship_by'): ?>
                              <p class="mb-0"> <?php echo $key?>: <mark><?php echo $val?></mark> </p>
                            <?php endif;
                          endforeach; ?>

                        </div>
                        </div>
                      </div>
                      
                    </div>
                  
                  </div> <!-- end of order details table -->
                 <div class='d-flex justify-content-center'>
                    <form target='_blank' action='print.php' method='POST'> 
                      <button type='submit' class='btn btn-info mr-4' name='print'> Print </button>
                      <input type='hidden' name='order_id' value='<?php echo intval($_GET['order_id']);?>'>
                    </form>
                      <button type='button' class='btn btn-outline-primary mr-4'
                              data-toggle='modal' data-target='#send_email'>
                              Send
                      </button>
                      <button type='button' class='btn btn-outline-danger' 
                              data-toggle='modal' data-target='#delete_order_<?php echo $_GET['order_id']?>'>
                              Delete
                      </button>
                  </div>
                  <!-- DELETE CUSTOMER MODAL -->
                  <div class="modal fade" id="delete_order_<?php echo $_GET['order_id']?>">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Are you sure ?</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                       <form method="POST" action="./orders.php">
                         <div class="modal-body">
                            Do you really wanna remove this order ?
                            <input type="hidden" name="order_to_del" value="<?php echo $_GET['order_id']?>">
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name='del_order' class="btn btn-danger">Yes remove</button>
                          </div>
                       </form>
                      </div>
                    </div>
                  </div>
                  <!-- DELETE CUSTOMER MODAL -->
                  <!-- ############ SEND email to customer modal ########### -->
                <div class="modal fade" id="send_email" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLongTitle">Send an email to the customer</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="customer_email" value="<?php echo $purchaser->email?>">
                        <div class="modal-body">
                            <div class="form-group">
                              <label>Subject / Title</label>
                              <input type="text" class="form-control" placeholder="e.g: Your Order details" name="email_subj" value="Your order details">
                            </div>
                            <div class="form-group">
                              <label>Description</label>
                             <textarea id="editor" rows="6" name="email_body">
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
                                 <mark>You can upload the order details that you printed</mark>, or upload any other attachment
                                </label>
                              </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary" name='send'>Send it</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
            <!-- endif of checking if there is a customer -->
            <?php else: 
              error_msg('Whoops! The user was not found ! you maybe deleted his account or something else.');
            endif;?>
            <!-- end if of checking if there is orders -->
           <?php else: ?>
            <h3>No orders yet...</h3>
           <?php endif;?>

       <?php else:
         $orders = $getFromA->getAll('orders', '', 'order by created_at desc');
        ?>
        <!--#################### Show all the orders ######################## -->
         <!-- DATA TABLE -->
            <h2 class="text-center m-b-35"> Customers Orders </h2>
            <div class="table-responsive table-responsive-data2">
                <table class="table table-data2">
                    <thead>
                        <tr>
                            <th width='14%'>#order</th>
                            <th width='14%'>name</th>
                            <th width="14%">email</th>
                            <th width="14%">method</th>
                            <th width="14%">order date</th>
                            <th width="14%">status</th>
                            <th width="14%">amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                       <?php if(!is_null($orders) && is_array($orders) && !empty($orders)): ?>
                         <?php foreach($orders AS $order):
                            $purchaser = $getFromA->getBy('customers', ['id' => $order->order_by]);
                            // $product_he_purchased = json_decode($order->order_items);
                        ?>
                            <tr class="tr-shadow">
                                <td style='width: 10%'><a href="?action=show-order&order_id=<?php echo $order->id?>">#<?php echo $order->id?></a>
                                </td>
                                <td><?php echo !empty($purchaser->name) ? $purchaser->name: 'Not found'; ?></td>
                                <td>
                                    <span class="block-email"><?php echo !empty($purchaser->email) ? $purchaser->email: 'Not found'; ?></span>
                                </td>
                                <td class="desc">
                                  <?php echo $order->payment_method ?>
                                </td>
                                <td><b><?php echo time_ago($order->created_at) ?></b></td>
                                <td>
                                    <select id='order_status' class="custom-select <?php echo $order->status == 'pending' ? 'status--pending' : 'status--process'?>" data-orderid='<?php echo $order->id?>'>
                                      <option value="<?php echo $order->status?>">
                                        <?php echo $order->status?>
                                      </option>
                                      <option value="<?php echo $order->status == 'pending' ? 'shipped' : 'pending'?>">
                                        <?php echo $order->status == 'pending' ? 'Shipped' : 'pending'?>
                                      </option>
                                    </select>
                                </td>
                                <td>$
                                    <?php echo $order->amount;
                                    ?>
                                </td>
                            </tr>
                            <tr class="spacer"></tr>
                        <?php endforeach; ?>
                       <?php else: ?>
                        <tr class="w-100 text-center">
                            <td colspan="10" class="lead"> <h3>No orders yet...</h3> </td>
                        </tr>
                       <?php endif;?>
                        
                    </tbody>
                </table>
            </div>
            <!-- END DATA TABLE --> 
       <?php endif;?>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/11.2.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
      .create( document.querySelector( '#editor' ) )
      .catch( error => {
          console.error( error );
      });
</script>

<?php include('../core/inc/admin_footer.php'); ?>