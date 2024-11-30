<?php

$ch = curl_init();

$apiKey = "QR_P_WYxQKT2HWixIfO9k-H33LP73Afvwpvsf5pEvXo";
$authHeader = "Authorization: Client-ID ".$apiKey;

$headers = [
    $authHeader,
    "Accept-Version: v1"
];
$url = "https://api.unsplash.com/photos/random";

$response_hdrs = [];

$response_hdrs_cb = function($ch, $value) use (&$response_hdrs) {
    $len = strlen($value);
    $split = explode(":", $value, 2);
    if (count($split) == 2)
        $response_hdrs[trim($split[0])] = trim($split[1]);
    //else
    //    $response_hdrs[] = trim($value);

    return $len;
};

curl_setopt_array($ch, [
    //CURLOPT_URL => "https://api.openweathermap.org/data/3.0/onecall?lat=30.34&lon=-97.74&exclude=hourly,daily&appid=".$apiKey,
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_HEADERFUNCTION => $response_hdrs_cb
]);

$result = curl_exec($ch);

$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

echo "HTTP status: ", $http_status, "\n\n";

print_r($response_hdrs);

// echo $result, "\n";
?>