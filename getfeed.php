<?php

	//https://github.com/J7mbo/twitter-api-php
	require_once("TwitterAPIExchange.php");
	require_once("config.php");

	$file_name = "cache.txt";

	$url = "https://api.twitter.com/1.1/search/tweets.json";
	$getField = "?q=%23Paris&result_type=recent";
	$requestMethod = "GET";


	//faili sisu tagasi objektiks
	$file_data = json_decode(file_get_contents($file_name));

	//võrdlen aega
	$delay = 10; //10 sekundit

	//kas on möödunud vähem kui delayga määrasime
	if(strtotime(date("c")) - (strtotime($file_data->date)) < $delay ){

		//on liiga vähe möödas
		echo json_encode($file_data);

		return;

	}



	$twitter = new TwitterAPIExchange($config);

	$dataFromAPI = $twitter->setGetfield($getField)
						   ->buildOauth($url, $requestMethod)
						   ->performRequest();



	//var_dump( json_decode($dataFromAPI)->statuses);

	$object = new StdClass();

	//millal tegime päringu
	$object->date = date("c");

	//saadud tweedid
	$object->statuses = json_decode($dataFromAPI)->statuses;



	//lisan vanad mis jäänud tekstifaili siia juurde
	 foreach($file_data->statuses as $old_status){

		$exists = false;

		foreach($object->statuses as $new_status){

			//kas on olemas
			if($old_status->id == $new_status->id){
				$exists = true;
			}

		}

		//ei olnud olemas
		if($exists == false){
			array_push($object->statuses, $old_status);
		}

	 }


	//echo count($object->statuses);


	file_put_contents($file_name, json_encode($object));

	echo json_encode($object);




?> 
