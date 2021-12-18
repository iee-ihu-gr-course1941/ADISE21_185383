<?php
class User {   
	public static function getByName($conn, $name){	
		$stmt = $conn->prepare("SELECT * FROM user WHERE name = ?");
		$stmt->bind_param("s", $name);					

		$stmt->execute();			
		
		$result = $stmt->get_result();		
		
		if ($result->num_rows > 0)
		 {
			return $result->fetch_assoc();
		}
		else {
			return null;
		}
	}
}
