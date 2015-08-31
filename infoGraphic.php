<?php

include("DBHelper.php");

//Connect to the DB
$database = new db();
$connection = $database->createConnection();

$graphData = $database->getTopArtistsBySongPlays();



?>

<html>
	<head>
		<script src="js/Chart.js/Chart.js"></script>
		<script type="text/javascript">
			// var data = [
			//     {
			//         value: 300,
			//         color:"#F7464A",
			//         highlight: "#FF5A5E",
			//         label: "Red"
			//     },
			//     {
			//         value: 50,
			//         color: "#46BFBD",
			//         highlight: "#5AD3D1",
			//         label: "Green"
			//     },
			//     {
			//         value: 100,
			//         color: "#FDB45C",
			//         highlight: "#FFC870",
			//         label: "Yellow"
			//     }
			// ];

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