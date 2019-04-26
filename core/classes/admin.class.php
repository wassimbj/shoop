<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Admin {
	private $db;
	public function __construct($conn){
	  $this->db = $conn;
	}

	//Get admin by
	public function get_admin_by($col, $val, $getColumns = array()){
		$colToGet = implode(',', $getColumns);
		$sql = "SELECT {$colToGet} FROM admins WHERE {$col} = :colval";
		$this->db->query($sql);
		$this->db->bind(':colval', $val);
		$this->db->execute();
		$data = $this->db->getOne(PDO::FETCH_OBJ);
		 return $data;
	}

	// Insert into the DB
	public function create($table, $fields = array()){
		$columns = implode(',', array_keys($fields));
		$values  = ':'.implode(', :', array_keys($fields));
		$sql = "INSERT INTO {$table}({$columns}) VALUES ({$values})";
		 $this->db->query($sql);
		 	foreach ($fields as $key => $value) {
		 		$this->db->bind(':'.$key, $value);
		 	}
		 $this->db->execute();
		 return $this->db->lastInsertId();
	}

	// Update DB
	public function update($table, $cond, $fields = array()){
		$columns = '';
		$i = 1;
		 foreach ($fields as $name => $value) {
		 	$columns .= "{$name} = :{$name}";
		 	 if($i < count($fields)){
		 	 	$columns .= ', ';
		 	 }
		 	$i = $i+1;
		 }
		 $sql = "UPDATE {$table} SET {$columns} WHERE {$cond}";
		 $this->db->query($sql);
	 	    foreach ($fields as $key => $val) {
	 	    	$this->db->bind(':'.$key, $val);
	 		}
		 $this->db->execute();
	}

	// Delete from DB
	public function delete($table, $cond = array()){
		$condition = '';
		$i = 1;
		 foreach ($cond as $col => $value) {
		 	$condition .= "{$col} = {$value}";
		 	 if($i < count($cond)){
		 	 	$condition .= ' and ';
		 	 }
		 	$i = $i+1;
		 }
		$sql = "DELETE FROM {$table} WHERE {$condition}";
		$this->db->query($sql);
		$this->db->execute();
	}

	// Get By condition
	public function getBy($table, $cond = array()){
		$condition = '';
		$i = 1;
		 foreach ($cond as $col => $value) {
		 	$condition .= "{$col} = '{$value}'";
		 	 if($i < count($cond)){
		 	 	$condition .= ' and ';
		 	 }
		 	$i = $i+1;
		 }
		$sql = "SELECT * FROM {$table} WHERE {$condition}";
		$this->db->query($sql);
		$this->db->execute();
		$data = $this->db->getOne(PDO::FETCH_OBJ);
		 return $data;
	}

	// get everything from a table
	public function getAll($table, $cond = '', $order = ''){
		$sql = "SELECT * FROM {$table} {$cond} {$order}";
		$this->db->query($sql);
		$this->db->execute();
		$data = $this->db->all(PDO::FETCH_OBJ);
		 return $data;
	}

	// Search for products with a key word
	public function search_for_product($word){
		$sql = "SELECT * FROM products WHERE
								name LIKE '%{$word}%'
		                        OR description LIKE '%{$word}%'
		                        OR price LIKE '%{$word}%'
		                        OR category LIKE '%{$word}%'";
		$this->db->query($sql);
		$this->db->execute();
		$data = $this->db->all(PDO::FETCH_OBJ);
		 return $data;
	}

	// get products to edit with limit and everything
	public function get_with_limit($table, $start_from, $records_per_page){
		$sql = "SELECT * FROM {$table} LIMIT {$start_from}, {$records_per_page}";
		$this->db->query($sql);
		$this->db->execute();
		$data = $this->db->all(PDO::FETCH_OBJ);
		 return $data;
	}

	// There is already a record
	public function already_there(string $table, string $colname, $colval){
		$sql = "SELECT * from {$table} WHERE {$colname} = :val";
		$this->db->query($sql);
		$this->db->bind(':val', $colval);
		$this->db->execute();
		$count = $this->db->rowCount();
		if($count > 0)
			return true;
		else
			return false;
	}

	// expired discount
	public function expire_discount($discount_id){
		$da_discount = $this->getBy('discount', ['id' => $discount_id]);

		$products_that_have_this_discount = $this->getAll('products', 'where discount_id ='.$da_discount->discount_to.' ');
	  if(!empty($products_that_have_this_discount))
	  {
			foreach ($products_that_have_this_discount as $product) {
			 $this->update('products', 'id ='.$product->id.'', [
			 	'discount_id' => 0,
			 	'price' => $product->old_price,
			 	'old_price' => 0
			 ]);
			}	
	  }
	}
	// Function to send emails
	public function send_email($to, string $subject, string $body, array $attach = array()){
		 //Load Composer's autoloader
		// These must be at the top of your script, not inside a function
        require '../admin/vendor/autoload.php';

          $mail = new PHPMailer(true); // Passing `true` enables exceptions
          try {
              // 0 = off (for production use, No debug messages)
              // 1 = client messages
              // 2 = client and server messages
              $mail->SMTPDebug = 0; 
              $mail->isSMTP();   // Set mailer to use SMTP
              $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
              $mail->SMTPAuth = true;       // Enable SMTP authentication
              $mail->Username = 'wassimbenjdida@gmail.com';  // SMTP username
              $mail->Password = 'wassim005';  // SMTP password
              $mail->SMTPSecure = 'tls';   // Enable TLS encryption, `ssl` also accepted
              $mail->Port = 587;  

              //Recipients
              $mail->setFrom('wassimbenjdida@gmail.com', 'Shop'); // From
              if(is_array($to)){
              	foreach ($to as $to_who) {
              		$mail->addAddress($to_who); // To many users
              	}
              }else{
              		$mail->addAddress($to); // To one only
              }
              // $mail->addAddress('ellen@example.com');
              // $mail->addReplyTo('info@example.com', 'Information'); // Name is optional "Information"
              // $mail->addCC('cc@example.com');
              // $mail->addBCC('bcc@example.com');

              //Attachments
              if(!empty($attach)){
                $mail->AddAttachment($attach[0], $attach[1]);
              }
              //Content
              $mail->isHTML(true); // Set email format to HTML
              $mail->Subject = $subject;
              $mail->Body    = $body;
              // This is the body in plain text for non-HTML mail clients
              // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

              // send the email
              if($mail->send()){
               success_msg('The email was successfully sent');
              }
          } catch (Exception $e) {
              error_msg('Oops, something went wrong !');
          }
	}


} // end of the class