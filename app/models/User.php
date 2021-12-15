<?php
class User {   
    
    public $id;
    public $name;
    public $password;

	private $conn;

	private $table = "user";      
	
    public function __construct($db){
        $this->conn = $db;
    }	
	
	function getByName($name){	
		$stmt = $this->conn->prepare("SELECT * FROM ".$this->table." WHERE name = ?");
		$stmt->bind_param("s", $name);					

		$stmt->execute();			
		
		$result = $stmt->get_result();		
		
		return $result;	
	}
}
?>