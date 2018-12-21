<?php
//delete all the learning info
require_once __DIR__.'/../DbConnect.php';

//delete the database from FIND server
if (isset($_POST)){

	$family = $_POST['family'];
	
	try{
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, "http://localhost:8005/api/v1/database/$family");
		curl_setopt($curl, CURLOPT_HEADER, 0); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt ($curl, CURLOPT_CUSTOMREQUEST, "DELETE");

		$data = curl_exec($curl); 
		curl_close($curl); 

		$bdd->query("DELETE FROM location_near");
		$bdd->query("DELETE FROM distance_info");
		$response = array('message' => "done");
	}catch (Exception $e){
			echo 'Exception : '.$e;
		}	
	echo json_encode($response);
}

?>