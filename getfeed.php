<?php

  require_once("TwitterAPIExchange.php");
  require_once("config.php");

  $file_name = "cache.txt";

  $url = 'https://api.twitter.com/1.1/search/tweets.json';
  $getField = '?q=%23Paris&result_type=recent';
  $requestMethod = 'GET';

  //Faili sisu tagasi objektiks
  $file_data = json_decode(file_get_contents($file_name));

  //Võrdlen aega
  $delay = 10; // 10sek

  //Kas on möödunud vähemalt 10sek
  if(strtotime(date("c")) - strtotime(($file_data->date)) < $delay) {
    //Liiga vähe
    echo (json_encode($file_data));
    return;
  }

  $twitter = new TwitterAPIExchange($config);

  $dataFromAPI = $twitter->setGetfield($getField)
                         ->buildOauth($url, $requestMethod)
                         ->performRequest();


  //var_dump(json_decode($dataFromAPI)->statuses);

  $object = new StdClass();
  $object->date = date("c");
  $object->statuses = json_decode($dataFromAPI)->statuses;



  //Lisan vanad mis jäänud tekstifaili siia juurde
  foreach($file_data->statuses as $old_status) {

    $exists = false;

    foreach($object->statuses as $new_status) {
      //kas olemas
      if($old_status->id == $new_status->id) {
        $exists = true;
      }

    }

    if($exists == false) {
      array_push($objects->statuses, $old_status);
    }
  }

  //echo count($object->statuses);

  file_put_contents($file_name, json_encode($object));

  echo json_encode($object);
?>
