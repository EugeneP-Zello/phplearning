<?php

$ch = curl_init();

// curl_setopt($ch, CURLOPT_URL, "https://randomuser.me/api");
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$apiKey = "QR_P_WYxQKT2HWixIfO9k-H33LP73Afvwpvsf5pEvXo";
$authHeader = "Authorization: Client-ID ".$apiKey;

$headers = [
    $authHeader,
    "Accept-Version: v1"
];
$url = "https://api.unsplash.com/photos/random";
echo $url;
curl_setopt_array($ch, [
    //CURLOPT_URL => "https://api.openweathermap.org/data/3.0/onecall?lat=30.34&lon=-97.74&exclude=hourly,daily&appid=".$apiKey,
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_HEADER => true
]);

$result = curl_exec($ch);

$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$ct = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$cl = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

echo "HTTP status: ", $http_status, "\n\n";

curl_close($ch);

echo $result, "\n";
?>