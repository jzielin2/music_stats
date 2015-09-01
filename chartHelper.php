<?php

class chartHelper {
		
	private $graphData = array();
	
	public $colors = array("#369AD9", "#F2F2F2", "#1FBF92", "#F2B705", "#F24141");
	public $highlights = array("#3088BF", "#DEDEDE", "#1CAD85", "#E3AC05", "#E33D3D");

	public function setColors($colorArray){
		$this->colors = $colorArray;
		return 0;
	}

	public function setHighlights($highlightArray){
		$this->highlights = $higlightArray;
		return 0;
	}	

	public function createDataObject($queryResultObject){
		$data = array();
		$colorCounter = 0;

		while ($row = $queryResultObject->fetch(PDO::FETCH_ASSOC)){
		  $data[] = array("value" => $row["value"], "color" => $this->colors[$colorCounter], "highlight" => $this->highlights[$colorCounter], "label" => $row["label"]);
		  $colorCounter++;
		}

		$this->graphData = json_encode($data);

		return $this->graphData;

	}


}



?>