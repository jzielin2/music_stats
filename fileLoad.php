<?php

$libraryXML = fopen("Library.xml", "r") or die("Unable to open file!");


$dictionaryLevel = 0;
$complete = false;
$songCounter = 1;
$lineCounter = 1;
$queryColumns = "";
$queryValues = "";

$construct = array(
	"Album"=>array("&lt;string&gt" , "&lt;/string&gt"),
	"Artist"=>array("&lt;string&gt" , "&lt;/string&gt"),
	"Bit Rate"=>array("&lt;integer&gt" , "&lt;/integer&gt"),
	"Composer"=>array("&lt;string&gt" , "&lt;/string&gt"),
	"Genre"=>array("&lt;string&gt" , "&lt;/string&gt"),
	"Kind"=>array("&lt;string&gt" , "&lt;/string&gt"),
	"Name"=>array("&lt;string&gt" , "&lt;/string&gt"),
	"Play Count"=>array("&lt;integer&gt" , "&lt;/integer&gt"),
	"Play Date UTC"=>array("&lt;date&gt" , "&lt;/date&gt"),
	"Rating"=>array("&lt;integer&gt" , "&lt;/integer&gt"),
	"Sample Rate"=>array("&lt;integer&gt" , "&lt;/integer&gt"),
	"Size"=>array("&lt;integer&gt" , "&lt;/integer&gt"),
	"Skip Count"=>array("&lt;integer&gt" , "&lt;/integer&gt"),
	"Total Time"=>array("&lt;integer&gt" , "&lt;/integer&gt"),
	"Track ID"=>array("&lt;integer&gt" , "&lt;/integer&gt"),
	"Year"=>array("&lt;integer&gt" , "&lt;/integer&gt")
);

$servername = "localhost";
$username = "JohnZielinski";
$password = "Fred891";
$dbname = "iTunes_analyzer";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
else{
	echo "DB connection all set <br/>";
}

//Loop for the base
while ($dictionaryLevel == 0 && ! feof($libraryXML) && ! $complete){
	if (strpos($line = htmlentities(fgets($libraryXML)), "/dict&gt;") == true){
		$dictionaryLevel--;
	}
	elseif(strpos($line, "dict&gt")){
		$dictionaryLevel++;
	}

	//echo $line . " - this is line number " . $lineCounter++ . " - it is in loop number " . $dictionaryLevel . "<br/>";

	while($dictionaryLevel == 1 && ! $complete){
		if (strpos($line = htmlentities(fgets($libraryXML)), "/dict&gt;") == true){
			$dictionaryLevel--;
		}
		elseif(strpos($line, "dict&gt")){
			$dictionaryLevel++;
		}


		//echo $line . " - this is line number " . $lineCounter++ . " - it is in loop number " . $dictionaryLevel . "THIS ONE<br/>";


		//now we're looking at each song
		while($dictionaryLevel == 2 && ! $complete){
			if (strpos($line = htmlentities(fgets($libraryXML)), "/dict&gt;") == true){
				$dictionaryLevel--;
				$complete = true;
				break;
			}
			elseif(strpos($line, "dict&gt")){
				$dictionaryLevel++;
			
				//echo "<br/>";
				//echo "This is a song<br/>";
				//echo "=====================================================================<br/>";
			}

			//echo $line . " - this is line number " . $lineCounter++ . " - it is in loop number " . $dictionaryLevel . "<br/>";

			while($dictionaryLevel == 3){
				if (strpos($line = htmlentities(fgets($libraryXML)), "/dict&gt;") == true){
					$dictionaryLevel--;
					//echo "=====================================================================<br/>";
					$query = "INSERT INTO songs (" . substr($queryColumns, 0, strlen($queryColumns) - 2) . ") VALUES (" . substr($queryValues, 0, strlen($queryValues) - 2) . ")";
					//echo "<br/>=====================================================================<br/>";
					
					if ($conn->query($query) === TRUE) {
					    //echo "New record created successfully";
					} else {
					    echo "Error: " . $sql . "<br>" . $conn->error;
					}

					$queryValues = "";
					$queryColumns = "";
				}
				elseif(strpos($line, "dict&gt")){
					$dictionaryLevel++;
				}

				$start = strpos($line, "&lt;key&gt;") + 11;
				$end = strpos($line, "&lt;/key&gt;");

				$key = substr($line, $start, $end-$start);


				if ($valueEnd = strpos($line, $construct[$key][1])){
					$valueStart = strpos($line, $construct[$key][0]) + strlen($construct[$key][0]) +1;

					$value = substr($line, $valueStart, $valueEnd-$valueStart);

					//echo $key . ": " . $value . "<br/>";

					$queryColumns = $queryColumns . str_replace(" ", "", $key) . ", ";
					$queryValues = $queryValues . "\"" . $value . "\", ";

					//echo $line . " - this is line number " . $lineCounter++ . " - it is in loop number " . $dictionaryLevel . " - the key value is " . $start . "|" . $end . "|" . $key . "|". $valueStart . "|" . $valueEnd . "|" . $value . "<br/>";
				}
			}
		}		
	}
}

fclose($libraryXML);


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// while ($dictPos < 100){

// $line = htmlentities(fgets($libraryXML));
// echo $line . "<br/>";
// if (strpos($line, "/dict&gt;")){
// 	echo "found it";
// }
// $dictPos++;
// }
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~




?>