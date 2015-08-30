<?php

class db{

	private $servername = "localhost";
	private $username = "JohnZielinski";
	private $password = "Fred891";
	private $dbname = "iTunes_analyzer";

	public function createConnection(){
		$conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 
		else{
			//echo "DB connection all set <br/>";
		}

		return $conn;
	}

}

?>