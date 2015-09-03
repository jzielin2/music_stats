<?php

include("DBHelper.php");
include("chartHelper.php");

//Connect to the DB
$database = new db();
$chartHelper = new chartHelper();

$database->createConnection();

$topArtistsBySongPlays = $database->getTopArtistsBySongPlays();
$TopSongsByPlayCount = $database->getTopSongsByPlayCount();

//$graphData = $chartHelper->createDataObject($topArtistsBySongPlays);



?>

<html>
	<head>
		<script src="js/Chart.js/Chart.js"></script>
		<script type="text/javascript">

			function createChart(){
				var data = <?php print_r($chartHelper->createDataObject($TopSongsByPlayCount)); ?>;
				var dataTwo = <?php print_r($chartHelper->createDataObject($topArtistsBySongPlays)); ?>;

				var ctx = document.getElementById('myCanvas').getContext("2d");
				var myDoughnutChart = new Chart(ctx).Doughnut(data,{});

				var ctx = document.getElementById('topSongsByPlayCount').getContext("2d");
				var myDoughnutChart = new Chart(ctx).Doughnut(dataTwo,{});
			}
		</script>
	</head>

<body onload="createChart();">
	<h1>Basic Chart Experimentation</h1>
	<canvas id="myCanvas" width="400" height="400"></canvas>
	<canvas id="topSongsByPlayCount" width="200" height="200"></canvas>

</body>


</html>