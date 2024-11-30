<?php

$ch = curl_init();

// curl_setopt($ch, CURLOPT_URL, "https://randomuser.me/api");
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$apiKey = "0ee1bd719639a623f82da17037e2621f";
$url = "https://api.openweathermap.org/data/2.5/weather?q=Austin&appid=".$apiKey;
echo $url;
curl_setopt_array($ch, [
    //CURLOPT_URL => "https://api.openweathermap.org/data/3.0/onecall?lat=30.34&lon=-97.74&exclude=hourly,daily&appid=".$apiKey,
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true
]);

$result = curl_exec($ch);

$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "HTTP status: ", $http_status, "\n\n";

curl_close($ch);

echo $result, "\n";
?>