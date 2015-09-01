<?php

include("DBHelper.php");

$libraryXML = fopen("XMLs/Library.xml", "r") or die("Unable to open file!");

$size = fstat($libraryXML)[7];

if($size < 1024)
	echo "File size: " . $size . " B."  . "<br/>";
else if($size/1024 < 1024)
	echo "File size: " . round($size/1024,2) . " KB." . "<br/>";
else if($sizeMB = $size/1024/1024 < 1024)
	echo "File size: " . round($size/1024/1024,2) . " MB." . "<br/>";
else if($sizeGB = $size/1024/1024/1024 < 1024)
	echo "File size: " . round($size/1024/1024/1024,2) . " GB." . "<br/>";
else
	echo "File too big";


$dictionaryLevel = 0;
$complete = false;
$songCounter = 1;
$lineCounter = 1;
$queryColumns = array();
$queryValues = array();

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

//Connect to the DB
$dbObject = new db();
$connection = $dbObject->createConnection();

$timeStart = microtime(true);

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

				$totalTime = microtime(true) - $timeStart;

				echo "Total processing time: " . round($totalTime, 2) . " seconds.";
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

					$dbObject->insertRawiTunesData($queryColumns, $queryValues);

					$queryValues = array();
					$queryColumns = array();
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

					$queryColumns[] = str_replace(" ", "", $key);
					$queryValues[] = $value;

					//echo $line . " - this is line number " . $lineCounter++ . " - it is in loop number " . $dictionaryLevel . " - the key value is " . $start . "|" . $end . "|" . $key . "|". $valueStart . "|" . $valueEnd . "|" . $value . "<br/>";
				}
			}
		}		
	}
}

fclose($libraryXML);

?>