<?php
/*=============== DataBase connection ===============*/

class Database {
  private $host = DB_HOST;
  private $user = DB_USER;
  private $pass = DB_PASS;
  private $dbname = DB_NAME;
  
  private $dbh;
  private $error;
  private $stmt;
  
  public function __construct() {
    // Set DSN
    $dsn = 'mysql:host='.$this->host.';dbname=' .$this->dbname;
    $options = array (
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
    );
    // Create a new PDO instanace
    try {
      $this->dbh = new PDO ($dsn, $this->user, $this->pass, $options);
    } // Catch any errors
    catch ( PDOException $e ) {
      $this->error = $e->getMessage();
    }

  }
  
  // Prepare statement with query
  public function query(string $query) {
    $this->stmt = $this->dbh->prepare($query);
  }
  
  // Bind values
  public function bind($param, $value, $type = null) {
    if (is_null ($type)) {
      switch (true) {
        case is_int ($value) :
          $type = PDO::PARAM_INT;
          break;
        case is_bool ($value) :
          $type = PDO::PARAM_BOOL;
          break;
        case is_null ($value) :
          $type = PDO::PARAM_NULL;
          break;
        default :
          $type = PDO::PARAM_STR;
      }
    }
    $this->stmt->bindValue($param, $value, $type);
  }
  
      // Execute the prepared statement
      public function execute(){
          return $this->stmt->execute();
      }

      // get All records as object
      public function all($type){
        return $this->stmt->fetchAll($type);
      }
      
      // Get single record as object
      public function getOne($type){
        return $this->stmt->fetch($type);
      }
      
      // Get record row count
      public function rowCount(){
        return $this->stmt->rowCount();
      }
      
      // Returns the last inserted ID
      public function lastInsertId(){
        return $this->dbh->lastInsertId();
      }
}
