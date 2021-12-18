<?php
class DB {
	private static $host = 'localhost';
    private static $user = 'root';
    private static $password = "root";
    private static $database = "adise21_185383"; 
    
    public static function getConnection(){		
		$conn = new mysqli(self::$host, self::$user, self::$password, self::$database);
		if($conn->connect_error){
			die("Error failed to connect to MySQL: " . $conn->connect_error);
		} else {
			return $conn;
		}
    }
}
?>