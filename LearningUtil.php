<?php
require_once __DIR__.'/../DbConnect.php';

function Getlistlocation ($family){
		$list_locations = array();

		$curl = curl_init(); 

		curl_setopt($curl, CURLOPT_URL, "http://localhost:8005/api/v1/locations/$family");

		curl_setopt($curl, CURLOPT_HEADER, 0); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl); 
		curl_close($curl); 

		//echo $data;
		$datalocation = json_decode($data,TRUE);

		$num=count($datalocation["locations"]);
		for ($i=0;$i<$num;$i++) {
		$list_locations[$i] = $datalocation["locations"][$i]["prediction"]["location"];
		//echo $list_locations[$i];
	}
	return $list_locations;

}

function calculateDistance($graph, $list_locations,$bdd){
		$num = count($list_locations);
		for ($i=0;$i<$num;$i++) {
			for($j=$i+1;$j<$num;$j++){
				$route = $graph->search($list_locations[$i], $list_locations[$j]);
				$cost = $graph ->cost($route);
				
				$bdd->query("INSERT INTO distance_info (location1,location2,distance) VALUES ('".$list_locations[$i]."','".$list_locations[$j]."','".$cost."') on DUPLICATE KEY UPDATE distance = '".$cost."' ");
	 
			}	
		}
}

?>