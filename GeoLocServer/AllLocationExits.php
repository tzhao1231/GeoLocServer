<?php
require_once __DIR__.'/../DbConnect.php';
require_once 'vendor/autoload.php';
//learning
//Send request to "find"server to get all the locations saved.
if (isset($_POST)){

		$family = $_POST['family'];
		//$location = $_POST['location'];

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
		//echo 'number'.$num;
		for ($i=0;$i<$num;$i++) {
			$list_locations[$i] = $datalocation["locations"][$i]["prediction"]["location"];
		}

		//$j = array_search($location, $list_locations);
		//unset($list_locations[$j]);
		//$allloc = array_values($list_locations);
		echo json_encode($list_locations);
		var_dump($list_locations);
}

?>