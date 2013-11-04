<?php

include_once("entities.php");

function get_window($id)  {
	$query = "select * from nexu_ranking where viewer=$id order by timestamp desc";
	$response = get_data($query);
	return $response;
}

function windowIsChanged($id) {
	$query = "select * from nexu_ranking where viewer=$id and rank is null";
	$response = get_data($query);
	return (count($response) != 0);
}

function rankingUpdate($idUtente) {
	$IP_SERVICE = "10.24.4.225:8080";
	$URL_RANKUPDATE_SERVICE = "http://$IP_SERVICE/restService/rest/ranking/notify";
	$elemsRanking = get_window($idUtente);
	$input = array("window" => $elemsRanking, "typeAlgorithm" => "AffinityTagProfile");
	$jsonParam = json_encode($input);
	error_log("JSON:".$jsonParam);
	
	/////////////////////////// SERVICE INVOCATION BY CURL ////////////////////////////////
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonParam);
    curl_setopt($curl, CURLOPT_URL, $URL_RANKUPDATE_SERVICE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    $JSONresponse = curl_exec($curl);
	
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
	curl_close($curl);
	////////////////////////////////////////////////////////////////////////////////////////
	
	/* ------ ESEMPIO AGGIORNAMENTO MULTIPLO SQL  --------
		INSERT INTO nexu_ranking (id,rank) VALUES (1,0.2),(3,0.35)
			ON DUPLICATE KEY UPDATE rank=VALUES(rank);
	*/
	
	/* ------ ESEMPIO CANCELLAZIONE MULTIPLA SQL  --------
		DELETE FROM nexu_ranking WHERE (id) IN (12,13,14,15)
	*/
	
	//error_log("risposta dall'url($URL_RANKUPDATE_SERVICE):".var_export($JSONresponse,true));
	
	$updateList = json_decode($JSONresponse);
	$deleteQuery = "DELETE FROM nexu_ranking WHERE (id) IN (";
	$updateQuery = "INSERT INTO nexu_ranking (id,rank) VALUES ";
	
	$updateNeeded = false;
	$deleteNeeded = false;
	
	if($httpCode==0)
		error_log("Connessione al servizio di ranking fallita!");
	else
		error_log("Risposta dal servizio di ranking:".var_export($httpCode,true)."===");
	
	foreach($updateList->window as $update) {
		if($update->action=="DELETE") {
			$deleteNeeded = true;
			$deleteQuery .= $update->id.",";
		} else {
			if(is_numeric($update->rank)) {
				$updateNeeded = true;
				$updateQuery .= "(".$update->id.",".$update->rank."),";
			} else {
				error_log("E' arrivato un valore di rank non valido per l'elemento ".$update->id);
			}
		}
	}
	$deleteQuery = substr_replace($deleteQuery ,")",-1);
	$updateQuery = substr_replace($updateQuery ,"",-1);
	$updateQuery.=" ON DUPLICATE KEY UPDATE rank=VALUES(rank)";
	
	//error_log("delete--->".$deleteQuery."<---");
	//error_log("update--->".$updateQuery."<---");
	
	if($deleteNeeded)
		get_data($deleteQuery);
	
	if($updateNeeded)
		get_data($updateQuery);
		
}

function getRanking($idUtente, $algo) {
	$IP_SERVICE = "10.24.4.221:8080";
	$URL_RANKUPDATE_SERVICE = "http://$IP_SERVICE/restService/rest/ranking/notify";
	$elemsRanking = get_window($idUtente);
	$window = array("window" => $elemsRanking, "rankingType" => $algo);
	$jsonParam = json_encode($window);
	
	/////////////////////////// SERVICE INVOCATION BY CURL ////////////////////////////////
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonParam);
    curl_setopt($curl, CURLOPT_URL, $URL_RANKUPDATE_SERVICE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    $JSONresponse = curl_exec($curl);
	
	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
	curl_close($curl);
	////////////////////////////////////////////////////////////////////////////////////////
	
	/* ------ ESEMPIO AGGIORNAMENTO MULTIPLO SQL  --------
		INSERT INTO nexu_ranking (id,rank) VALUES (1,0.2),(3,0.35)
			ON DUPLICATE KEY UPDATE rank=VALUES(rank);
	*/
	
	/* ------ ESEMPIO CANCELLAZIONE MULTIPLA SQL  --------
		DELETE FROM nexu_ranking WHERE (id) IN (12,13,14,15)
	*/
	
	//error_log("risposta dall'url($URL_RANKUPDATE_SERVICE):".var_export($JSONresponse,true));
	
	$updateList = json_decode($JSONresponse);
	$deleteQuery = "DELETE FROM nexu_ranking WHERE (id) IN (";
	$updateQuery = "INSERT INTO nexu_ranking (id,rank) VALUES ";
	
	$updateNeeded = false;
	$deleteNeeded = false;
	
	error_log("httpCode from url [$URL_RANKUPDATE_SERVICE]--->".var_export($httpCode,true)."&&&");
	//error_log("response--->".var_export($JSONresponse,true)."&&&");
	
	foreach($updateList->window as $update) {
		if($update->action=="DELETE") {
			$deleteNeeded = true;
			$deleteQuery .= $update->id.",";
		} else {
			if(is_numeric($update->rank)) {
				$updateNeeded = true;
				$updateQuery .= "(".$update->id.",".$update->rank."),";
			} else {
				error_log("E' arrivato un valore di rank non valido per l'elemento ".$update->id);
			}
		}
	}
	$deleteQuery = substr_replace($deleteQuery ,")",-1);
	$updateQuery = substr_replace($updateQuery ,"",-1);
	$updateQuery.=" ON DUPLICATE KEY UPDATE rank=VALUES(rank)";
	
	//error_log("delete--->".$deleteQuery."<---");
	//error_log("update--->".$updateQuery."<---");
	
	if($deleteNeeded)
		get_data($deleteQuery);
	
	if($updateNeeded)
		get_data($updateQuery);
		
}