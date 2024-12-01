<?php
// http://localhost:8044/api/tasks/123

 require dirname(__DIR__) . "/vendor/autoload.php";
echo $_SERVER["REQUEST_URI"]. "\n<br>";
$fullPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
echo $fullPath . "\n<br>";

$parts = explode("/", $fullPath);

$resource = $parts[2];
$id = $parts[3] ?? null;
$method = $_SERVER["REQUEST_METHOD"];
echo "Resource: $resource\n<br>";
echo "ID: $id\n<br>";
echo "Method: $method\n<br>";

if ($resource != "tasks") {
    http_response_code(404);
    echo "Resource not found\n";
}

$manager = new TaskManager();
$manager->process($method, $id);

?>