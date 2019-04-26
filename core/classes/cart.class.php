<?php
class Cart extends Customer{
	private $db;
	public function __construct($conn){
	  $this->db = $conn;
	}

	// get user cart info
	public function get_user_cart($user_id){
		$sql = "SELECT * FROM cart WHERE cart_by = :user_id AND paid = 0";
		$this->db->query($sql);
		$this->db->bind(':user_id', $user_id);
		$this->db->execute();
		$data = $this->db->all(PDO::FETCH_ASSOC);
		return $data;
	}

	// check if there is already a product in user cart
	public function there_is_product_in_cart($product_id, $user_id){
		$sql = "SELECT id FROM cart WHERE cart_to = :product_id AND cart_by = :user_id AND paid = 0";
		$this->db->query($sql);
		$this->db->bind(':product_id', $product_id);
		$this->db->bind(':user_id', $user_id);
		$this->db->execute();
		$count = $this->db->rowCount();
		if($count > 0)
			return true; // there is product
		else
			return false; // there is no product
	}


}