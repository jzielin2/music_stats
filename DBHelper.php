<?php

class db{

	private $servername = "localhost";
	private $username = "JohnZielinski";
	private $password = "Fred891";
	private $dbname = "iTunes_analyzer";

	public $connection;
	public $graphData = array();
	public $colors = array("#369AD9", "#F2F2F2", "#1FBF92", "#F2B705", "#F24141");
	public $highlights = array("#3088BF", "#DEDEDE", "#1CAD85", "#E3AC05", "#E33D3D");

	//////////////////////////////////////////////////////////////////
	//  Creates a connection to the DB based on the parameters above.
	//  There is no inclusion of a test DB at this time.
	//////////////////////////////////////////////////////////////////
	public function createConnection(){
		$this->connection = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

		// Check connection
		if ($this->connection->connect_error) {
		    die("Connection failed: " . $this->connection->connect_error);
		} 
		else{
			//echo "DB connection all set <br/>";
		}

		return $this->connection;
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

		if (!$result){
			$message  = 'Invalid query: ' . mysql_error() . "\n" . 'Whole query: ' . $query;
		  die($message);
		}
		
		$this->createDataObject($result);

		return $this->graphData;

	}

	//////////////////////////////////////////////////////////////////
	//  Takes as input a mysql query object and returns a formatted
	//  json object that can be used with Chart.js.  Need to figure
	//  out how to pass in color codes, or if this will be defined 
	//  differently.
	//////////////////////////////////////////////////////////////////
	private function createDataObject($queryResult){
		
		$data = array();
		$colorCounter = 0;

		while ($row = $queryResult->fetch_assoc()){
		  $data[] = array("value" => $row["value"], "color" => $this->colors[$colorCounter], "highlight" => $this->highlights[$colorCounter], "label" => $row["label"]);
		  $colorCounter++;
		}

		$this->graphData = json_encode($data);

		return 0;

	}

}

?>