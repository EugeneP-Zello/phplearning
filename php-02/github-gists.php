<?php
if(!empty($_GET["apiKey"])) {
    $ch = curl_init();

    $authHeader = "Authorization: token {$_GET['apiKey']}";

    $headers = [
        $authHeader,
        "User-Agent: EugeneP-Zello"
    ];


    $url = "https://api.github.com/gists";
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_CUSTOMREQUEST => "GET"
    ]);

    $result = curl_exec($ch);

    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $gists = json_decode($result, true);

    foreach ($gists as $gist) {
        echo $gist['name'], " --- ", $gist['description'], "\n";
    }

    curl_close($ch);
}
?>
<!DOCTYPE html>
<html lang="en">
<body>
<?php if (isset($http_status)): ?>
    Github returned <?= $http_status ?>
    Reply: <?= $result ?>
<?php endif; ?>
<?php if ((isset($http_status) ? $http_status : null) == 204): ?>
    result: <?= $http_status ?> Starred!
<?php endif; ?>
<?php if ((isset($http_status) ? $http_status : null) == 404): ?>
    result: <?= $http_status ?> Not starred!
<?php endif; ?>

<form>
    <label for="apiKey">Github API Key</label>
    <input name="apiKey" id="apiKey" />
    <button>Apply</button>
</form>
</body>
</html>