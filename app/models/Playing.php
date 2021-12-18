<?php
class PLaying {   
    
    public $id;
    public $active;
    public $phase;
    public $player_cnt;

	private $conn;

	private $table = "playing";      
	
    public function __construct($db){
        $this->conn = $db;
    }	
	
	function getActive(){	
		$stmt = $this->conn->prepare("SELECT * FROM ".$this->table." WHERE active = '1'");

		$stmt->execute();			
		
		$result = $stmt->get_result();		
		
		return $result;	
	}
    
    function add(){
        $stmt = $this->conn->prepare("
            INSERT INTO ".$this->table."(`active`, `phase`, `player_cnt`)
            VALUES(?,?,?)");
        
        $stmt->bind_param("iii", $this->active, $this->phase, $this->player_cnt);
        
        if($stmt->execute()){
            return $this->conn->insert_id;
        }
     
        return 0;		 
    }
}
