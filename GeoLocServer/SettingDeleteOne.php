<?php
	require_once __DIR__.'/../DbConnect.php';
	require_once 'LearningUtil.php';
	require_once 'vendor/autoload.php';

//Everytime we want to delete the fingerprint, we have to do 2 things, delete it from server FIND, and delete it from server PHP

	if (isset($_POST)){
	//delete it from server FIND
		$family = $_POST['family'];
		$location = $_POST['location'];

		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, "http://localhost:8005/api/v1/location/$family/$location");
		curl_setopt($curl, CURLOPT_HEADER, 0); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
		$data = curl_exec($curl); 
		curl_close($curl); 
		
	
	/*	$dij ="SELECT * FROM  location_near WHERE location1 = '".$location."' OR location2 = '".$location."'";
		foreach( $bdd->query($dij)as $row){
			$graph->remove($row['location1'], $row['location2']);
		}*/
		// delete it from server PHP
		$bdd->query("DELETE FROM location_near WHERE loc = '".$location."' OR nearby = '".$location."'");
		$bdd->query("DELETE FROM distance_info WHERE location1 = '".$location."' OR location2 = '".$location."'");
	//update the distance_info database
		$graph= Taniko\Dijkstra\Graph::create();
		$dij ="SELECT * FROM location_near";
		foreach( $bdd->query($dij)as $row){
			$loc= $row['loc'];
			$nearby=$row['nearby'];
			$dis=$row['dis'];
			$graph->add($loc,$nearby,$dis);
		}
	
		$list_locations = Getlistlocation ($family);

		$j = array_search($location, $list_locations);
		unset($list_locations[$j]);
		$allloc = array_values($list_locations);

		calculateDistance($graph, $allloc,$bdd);
		$response = array('message' => "done");
	
		echo json_encode($response);	
}


?>