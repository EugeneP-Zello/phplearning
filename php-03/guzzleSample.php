<?php
if(!empty($_GET["apiKey"])) {
    $hdr = "Bearer {$_GET['apiKey']}";
    echo $hdr;
    $authHeader = "Authorization: token {$_GET['apiKey']}";
    $headers = [
        $authHeader,
        "User-Agent: EugeneP-Zello"
    ];
require __DIR__ . '/../vendor/autoload.php';

$client = new GuzzleHttp\Client;

$response = $client->request('GET', 'https://api.github.com/user/repos', [
    'headers' => [
        'User-Agent' => 'EugeneP-Zello',
        'Authorization' => $hdr
    ]]);

$http_status = $response->getStatusCode();
$body = $response->getBody()->getContents();
}

?>
<!DOCTYPE html>
<html lang="en">
<body>
<?php if (isset($http_status)): ?>
    Github returned <?= $http_status ?>
    Reply: <?= $body ?>
<?php endif; ?>
<form>
    <label for="apiKey">Github API Key</label>
    <input name="apiKey" id="apiKey" />
    <button>Apply</button>
</form>
</body>
</html>