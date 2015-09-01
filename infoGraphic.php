<?php

include("DBHelper.php");
include("chartHelper.php");

//Connect to the DB
$database = new db();
$chartHelper = new chartHelper();

$database->createConnection();

$chartHelper->setColors(array("#369AD9", "#F2F2F2", "#1FBF92", "#F2B705", "#33DB1F"));

$topArtistsBySongPlays = $database->getTopArtistsBySongPlays();
$graphData = $chartHelper->createDataObject($topArtistsBySongPlays);

?>

<html>
	<head>
		<script src="js/Chart.js/Chart.js"></script>
		<script type="text/javascript">

			var data = <?php print_r($graphData); ?>;

			function createChart(chartData, canvasID){
				var ctx = document.getElementById(canvasID).getContext("2d");
				var myDoughnutChart = new Chart(ctx).Doughnut(chartData,{});
			}
		</script>
	</head>

<body onload="createChart(data, 'myCanvas');">
	<h1>Basic Chart Experimentation</h1>
	<canvas id="myCanvas" width="400" height="400"></canvas>


</body>


</html>