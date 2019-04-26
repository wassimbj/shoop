<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Customer extends Product{
	private $db;
	public function __construct($conn){
	  $this->db = $conn;
	}

		
	// return a clean input data
	public function clean_input($data){
		$result = htmlspecialchars($data, ENT_COMPAT, 'ISO-8859-1', true);
		$result = trim($data);
		$result = stripslashes($data);
		return $result;
	}

	public function get_user_by($col, $val, $getColumns = array()){
		$colToGet = implode(',', $getColumns);
		$sql = "SELECT {$colToGet} FROM customers WHERE {$col} = :colval";
		$this->db->query($sql);
		$this->db->bind(':colval', $val);
		$this->db->execute();
		$data = $this->db->getOne(PDO::FETCH_OBJ);
		 return $data;
	}
	// // Function to send emails
	public function contact($from, $to, string $subject, string $body){
		 //Load Composer's autoloader
		// These must be at the top of your script, not inside a function
        require './admin/vendor/autoload.php';

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
              $mail->setFrom($from); // From
              $mail->addAddress($to); // To one only
              
              // $mail->addAddress('ellen@example.com');
              // $mail->addReplyTo('info@example.com', 'Information'); // Name is optional "Information"
              // $mail->addCC('cc@example.com');
              // $mail->addBCC('bcc@example.com');

              //Content
              $mail->isHTML(true); // Set email format to HTML
              $mail->Subject = $subject;
              $mail->Body    = $body;
              // This is the body in plain text for non-HTML mail clients
              // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

              // send the email
              if($mail->send()){
               return true;
              }
          } catch (Exception $e) {
              return false;
          }
	}
}