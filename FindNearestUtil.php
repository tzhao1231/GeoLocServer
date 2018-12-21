<?php
	require_once __DIR__.'/../DbConnect.php';
	//find the id of staff
	/*function Idperson($bdd,$location2){
		$findid = "SELECT * FROM location WHERE location =".$location2;
		foreach ($bdd->query($findid)as $row) {
			$idperson = $row['idperson'];
			return $idperson;
		}
	}*/
	//send message to Android without notification
	function SendRequest($token,$data){
		try {
			$server_key = 'AAAASn28Wyw:APA91bEqxKzFkXNLXn6S8wG6OYBhZd4uOmfgWvQe0TxSwd_3u_2d-TO-cvfZtrn0V8RB6yb_7zxP9k_MaSDxOvlByhYOUHa4NFEdXNgmcknhNYy6YdCd_XwQQTdDF9L1ZOkLU0r3hf98fHfoDv7eCL1DdHgJEnrWAg';
			$client = new Client();
			$client->setApiKey($server_key);
			$client->injectGuzzleHttpClient(new \GuzzleHttp\Client());//guzzle is a php client to send http requests
			$message = new Message();
			$message->addRecipient(new Device($token));
			$message->setData($data);
			$response = $client->send($message);
			var_dump($response->getStatusCode()); // http status code 
			var_dump($response->getBody()->getContents()); 
		} catch (Exception $e){
			echo 'Exception : '.$e;
		}	
	}

	function LocationStaff($bdd,$data){
		$reqAllToken = 'SELECT * FROM personnels';
		foreach($bdd->query($reqAllToken) as $row){
			if (strlen($row['token']) > 5){
				$token = $row['token'];
				SendLocationRequest($token,$data);
			}
		}
	}

	function ResidentLocation($bdd){
		$location = "SELECT * FROM location WHERE flag = 1 ";
		foreach($bdd->query($location1)as $row){
				$location1 = $row['location'];
				return $location1;
			}
	}

	function DistanceBetweenLocation($location1, $location2){
		//$sql_location = "SELECT * FROM distance_info WHERE ((location1 = '".$location1.'" OR location1 = '".$location2."') AND (location2 = '".$location1."'" OR location2 = '".$location2."'))";
		 $dis = "SELECT * FROM distance_info WHERE (location1 = '".$location1."' AND location2 = '".$location2."') OR (location1 = '".$location2."' AND (location2 = '".$location1."' )";
		foreach($bdd -> query($dis) as $row){
			$distance = $row['distance'];
			return $distance;
		}
		
	}

?>