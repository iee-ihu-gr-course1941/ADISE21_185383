<?php
class DB {
    private static $user = 'root';
    private static $password = 'root';
    private static $database = 'adise21_185383';
	private static $host = '';
    private static $sock = '/home/student/it/2018/it185383/mysql/run/mysql.sock'; 

    public static function getConnection(){		
		$conn = new mysqli(self::$host, self::$user, self::$password, self::$database, null, self::$sock);

		if($conn->connect_error){
			die("Σφάλμα σύνδεσης με τη MySQL: " . $conn->connect_error);
		} else {
			return $conn;
		}
    }
}
