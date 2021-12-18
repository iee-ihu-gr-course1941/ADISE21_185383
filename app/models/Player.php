<?php
class Player {   
    
    public $id;
    public $playing_id;
    public $playing_seqno;
    public $playing_iscurrent;
    public $state;

	private $conn;

	private $table = "player";      
	
    public function __construct($db){
        $this->conn = $db;
    }	
	
	function getPlayingCurrent($playing_id){	
		$stmt = $this->conn->prepare("SELECT * FROM ".$this->table." WHERE playing_iscurrent = 1 and playing_id = ?");
		$stmt->bind_param("i", $playing_id);					

		$stmt->execute();			
		
		$result = $stmt->get_result();		
		
		return $result;	
	}

	function add(){
        $stmt = $this->conn->prepare("
            INSERT INTO ".$this->table."(`id`, `playing_id`, `playing_seqno`, `playing_iscurrent`, `state`)
            VALUES(?,?,?,?,?)");
        
        $stmt->bind_param("iiiii", $this->id, $this->playing_id, $this->playing_seqno, $this->playing_iscurrent, $this->state);
        
        if($stmt->execute()){
            return $this->conn->insert_id;
        }
     
        return 0;		 
    }

}
?>