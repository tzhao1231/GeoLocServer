<?php
	require_once __DIR__.'/../DbConnect.php';
	require_once 'LearningUtil.php';
	require_once 'vendor/autoload.php';
	
		// Récupération des informations du JSON
		// Appel de GetLearningInfo pour calculer la distance entre cette nouvelle position et les positions précédentes
		// GetLearningInfo va enregistrer ces distances en BDD

	// if the number of parameter is set, use $_POST, else we use json 
	/*if (isset($_POST)){
		echo var_dump($_POST);
		$count = count($_POST);
		$family = $_POST['family'];
		$location = $_POST['location'];
		$loc1 = $_POST['loc1'];
		$dis1 = $_POST['dis1'];
		$loc2 = $_POST['loc2'];
		$dis2 = $_POST['dis2'];
		$loc3 = $_POST['loc3'];
		$dis3 = $_POST['dis3'];
		$result = $bdd->query("INSERT INTO location_near (loc, nearby, dis) VALUES ('".$location."', '".$loc1."', '".$dis1."'),('".$location."', '".$loc2."', '".$dis2."'),('".$location."', '".$loc3."', '".$dis3."')");*/

		$receive = file_get_contents('php://input');

		$jsonReceive = json_decode($receive,TRUE);
	

		$num = count($jsonReceive["nearby"]);
		for($i = 0;$i<$num;$i++){
			$location = $jsonReceive["location"];
			$loc = $jsonReceive["nearby"][$i]["loc"];
			$dis = $jsonReceive["nearby"][$i]["dis"];
			$bdd->query("INSERT INTO location_near (loc, nearby, dis) VALUES ('".$location."', '".$loc."', '".$dis."')");
		}

		global $graph;
		$graph= Taniko\Dijkstra\Graph::create();
		$dij ="SELECT * FROM location_near";
		foreach( $bdd->query($dij)as $row){
			$loc= $row['loc'];
			$nearby=$row['nearby'];
			$dis=$row['dis'];
			$graph->add($loc,$nearby,$dis);
		}

		$list_locations = Getlistlocation ($jsonReceive["family"]);
		calculateDistance($graph, $list_locations,$bdd);
		$response = array('message' => "fini");
		echo json_encode($response);	

	

	
?>

