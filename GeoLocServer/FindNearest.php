<?php
	//require_once ('DbConnect.php');
	require_once __DIR__.'/../DbConnect.php';
	require_once 'FindNearestUtil.php';

	//function FindNearestPeople return staff id

	function FindNearestPeople($bdd,$resident){
		$data = ['location' => ''];
		//send location request to resident
		SendLocationRequest($resident['token'],$data);
		//send location request to all the staff
		LocationStaff($bdd,$data);


		//receive location (SaveLocation.php)
		sleep(10);
		//save the location of residents
		$location1 = ResidentLocation($bdd);

		//staff: idperson => distance
		$staff = array();
		//distance_info is a pre-defined database, it has all the distance between locations
		/*$distance = "SELECT * FROM distance_info WHERE location1 = '".$location1."'";

		foreach ($bdd->query($distance)as $row){
			$location2 = $row['location2'];
			$idperson = Idperson($bdd,$location2);
			$distance = $row['distance'];
		//insert the idperson and distance into the array $staff
			if ($idperson && ($distance <= $requestDis) ){
				$staff[$idperson] = $distance;
			}
		}*/
		// flag = 0 staff
		$sql_staff = "SELECT * FROM locationReceive WHERE flag = '0'";
		foreach ($bdd->query($sql_staff) as $row){
			$id = $row['id'];
			$distance = DistanceBetweenLocation($row['location'], $location1);
			//$staff[] = ('id' => $row['id'], 'distance' => DistanceBetweenLocation($row['location'], $location1));
			$staff[$idperson] = $distance;
		}

		//sort the staff by distance
		asort($staff);

		//less than 9 nearest staff, return all; more than 9 nearest staffs, return 9 staffs
		if (count($staff)< 9){
 			return $staff;
		}else{
			return array_slice($staff,0,9,true);
		}

		//everytime after finding the nearest staff,delete the database "locationReceive"
		$bdd->query("DELETE FROM locationReceive");
	}

	
	
	
	

?>