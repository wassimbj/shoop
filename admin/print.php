<?php
$inside_admin = true;
include('../core/inc/init.php');
$shop_front = $getFromA->getAll('shop_front');

  if(isset($_POST['order_id']))
  {
    $order = $getFromA->getBy('orders', ['id' => intval($_POST['order_id'])]);
    $content = '';  
            $purchaser = $getFromA->getBy('customers', [ 'id' => $order->order_by]);
            foreach (json_decode($order->order_items) as $purchased) {
                $order_items[] = $getFromA->getBy('cart', ['id' => $purchased]);
            }
            $ship_adrs = $getFromA->getBy('shipping', ['ship_by' => $order->order_by]);
            $delivery = '';
            $i = 0;
             foreach($ship_adrs AS $key => $val ):
                if($key !== "id" && $key !== "ship_by"):
                      $delivery .= ' <p>'.$val.'</p> ';
                endif;
             endforeach;
        $content .= '
                <img src="'.$shopfrontPath.$shop_front[0]->logo.'" width="50">
                <h1 align="center"> Order details </h1>
                  <table border="0" cellspacing="5" cellpadding="5">
                    <tr>
                      <th >Order ID: <b>'.$order->id.'</b></th>
                      <th>Total: <b>$'.$order->amount.'</b></th>
                    </tr>
                    <tr>
                      <td>Order Date: <b>'.date("d/m/y g:i A", strtotime($order->created_at)).'</b></td>
                      <td>Payment method: <b>'.$order->payment_method.'</b></td>
                    </tr>
                  </table>
                  <h1></h1>
                   <!-- Delivery info -->
                    <h2 style="color: #43cf22"> Delivery to </h2>
                    <div>
                        '.$delivery.'
                    </div>
                    <!-- Delivery info -->
                    <h1></h1>
                    <table border="1" cellspacing="0" cellpadding="5">
                      <thead>
                      <tr style="background-color: #e6e6e6">
                        <th width="25%"><b> Product </b></th>
                        <th width="20%"><b> Quantity </b></th>
                        <th width="15%"><b> Size </b></th>
                        <th width="20%"><b> Color </b></th>
                        <th width="20%"><b> Price </b></th>
                      </tr>
                    </thead>
                    <tbody>';

         foreach($order_items AS $item):
            $product_he_purchased = $getFromA->getBy('products', ['id' => $item->cart_to]);
             $content .= '<tr>
                          <td width="25%">'.$product_he_purchased->name.'</td>
                          <td width="20%">'.$item->quantity.'</td>
                          <td width="15%">'.$item->size.'</td>
                          <td width="20%">'.$item->color.'</td>
                          <td width="20%"> $'.$product_he_purchased->price.'</td>
                        </tr>';
            endforeach;
                     
        $content .= '</tbody>
                  </table>
                  <div align="right">
                      <h3>Total: <mark>$'.$order->amount.'</mark></h3>
                    </div>';
    require('vendor/autoload.php'); // grab the TCPDF Lib
    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
      $obj_pdf->SetCreator(PDF_CREATOR);  
      $obj_pdf->SetTitle("Order details");  
      $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
      $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
      $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
      $obj_pdf->SetDefaultMonospacedFont('helvetica');  
      $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
      $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
      $obj_pdf->setPrintHeader(false);  
      $obj_pdf->setPrintFooter(false);  
      $obj_pdf->SetAutoPageBreak(TRUE, 10);  
      $obj_pdf->SetFont('helvetica', '', 12);  
      $obj_pdf->AddPage();
      $obj_pdf->writeHTML($content);  
      $obj_pdf->Output('order_details.pdf', 'I');  
  }
?>
