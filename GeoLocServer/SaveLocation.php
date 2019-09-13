<?php
	//require_once ('DbConnect.php');
	require_once __DIR__.'/../DbConnect.php';
	//tracking
	if(isset($_POST)){
		$location=$_POST['location'];
		//isresident=1 residents else isresidents=0 staff
		$flag=$_POST['isresidents'];
		$idperson= $_POST['idperson'];
		try{
			if($location && $idperson){
				$bdd->query("INSERT INTO locationReceive VALUES ($location,$flag,$idperson)");
			}
		}catch (Exception $e){
			echo 'Exception : '.$e;
		}	
	}

?>