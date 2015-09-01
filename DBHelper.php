<?php

class db{

	private $servername = "localhost";
	private $username = "JohnZielinski";
	private $password = "Fred891";
	private $dbname = "iTunes_analyzer";

	public $connection;

	//////////////////////////////////////////////////////////////////
	//  Creates a connection to the DB based on the parameters above.
	//  There is no inclusion of a test DB at this time.
	//////////////////////////////////////////////////////////////////
	public function createConnection(){

		$DSNString = 'mysql:host=' . $this->servername . ';dbname=' . $this->dbname . ';charset=utf8';
		$this->connection = new PDO($DSNString, $this->username, $this->password);

		return $this->connection;
	}

	
	public function insertRawiTunesData($columns, $values){
		
		//Strings / array we'll used to dynamically build the prepared statement
		$fields = "";
		$DBOvalues = "";
		$parameters = array();

		//Build the query strings that will make the prepared statement later on.  Has to accept an 
		//arbitrary number of values since we may get an arbitrary number of them from iTunes. 
		for ($i=0; $i<count($columns); $i++){
			$fields = $fields . $columns[$i] . ",";
			$DBOvalues = $DBOvalues . ':' . $columns[$i] . ",";
			$parameters[":" . $columns[$i]] = $values[$i];
		}

		//Get rid of the trailing comma
		$fields = substr($fields, 0, strlen($fields)-1);
		$DBOvalues = substr($DBOvalues, 0, strlen($DBOvalues)-1);

		//Execute the prepared statement using the strings we built above
		$insertStatement = $this->connection->prepare("INSERT INTO songs (" . $fields . ") VALUES ( " . $DBOvalues . " )");
		$insertStatement->execute($parameters);
		
	}

	//////////////////////////////////////////////////////////////////
	//  Retrieves the data required to pull top artists. Not sure it 
	//  makes sense to have a method for each query.  Need to think
	//  through how to reuse one method to handle them (or a few 
	//  methods).
	//////////////////////////////////////////////////////////////////
	public function getTopArtistsBySongPlays(){

		$query = "select Artist as 'label', sum(PlayCount) AS 'value' from songs group by Artist order by 2 desc limit 5;";
		$result = $this->connection->query($query);

		return $result;

	}

}

?>