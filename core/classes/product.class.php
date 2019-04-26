<?php
class Product extends Admin {
	private $db;
	public function __construct($conn){
	  $this->db = $conn;
	}

	// get all products from the DB with conditions or filters
	public function get_products($cond = 'products.image=i.connector', $order = 'order by created_at desc'){
		$sql = "SELECT products.*, GROUP_CONCAT(i.image) as product_images
					FROM products
					INNER JOIN pimages i ON i.connector = products.image
					WHERE {$cond}
                    GROUP BY products.id
					{$order}";
		// return $sql;
		$this->db->query($sql);
		if($this->db->execute()){
			$count = $this->db->rowCount();
			$data = $this->db->all(PDO::FETCH_OBJ);
			if($count > 0)
				return $data;
			else
				return false ;
		}else{
			return false;
		}

	}

	// get products deltails by name it can be changed by id or anything we want
	public function get_product_by(string $cond, $cond_val){
		$sql = "SELECT products.*, GROUP_CONCAT(i.image) as product_images
				FROM products
				RIGHT JOIN pimages i ON i.connector = products.image
				WHERE {$cond} = :cond_val";
		$this->db->query($sql);
		$this->db->bind(':cond_val', $cond_val);
		$this->db->execute();
		$count = $this->db->rowCount();
		if($count > 0)
			return $this->db->getOne(PDO::FETCH_OBJ);
		else
			return false;

	}

	// get related products by category or price or name
	public function related_products($category,$id){
		$sql = "SELECT products.*, GROUP_CONCAT(i.image) as product_images
				FROM products
				RIGHT JOIN pimages i ON i.connector = products.image
				WHERE products.id <> :id AND products.category = :category
					  AND i.connector = products.image
			    GROUP BY products.id";
		$this->db->query($sql);
		$this->db->bind(':category', $category);
		$this->db->bind(':id', $id);
		if($this->db->execute()){
			return $this->db->all(PDO::FETCH_OBJ);
		}
	}



}

