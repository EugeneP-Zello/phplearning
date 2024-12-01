<?php
// http://localhost:8044/api/tasks/123

declare(strict_types=1);

require dirname(__DIR__) . "/vendor/autoload.php";

set_error_handler("ErrorProcessor::processError");
set_exception_handler("ErrorProcessor::process");

$apiKey = $_SERVER["HTTP_X_API_KEY"];
if (empty($apiKey)) {
    http_response_code(400);
    echo json_encode(["error" => "API key is required"]);
    exit;
}

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

header("Content-Type: application/json; charset=UTF-8");

$testClassObject = new TestClass('Hello, World!');
// echo $testClassObject->getName() . "\n<br>";

$db = new MyDatabase($_ENV["DB_HOST"], $_ENV["DB"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);

$tasks = new TaskGateway($db);
$accounts = new UserGateway($db);

$account = $accounts->getByKey($apiKey);
if ($account === false) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid API key"]);
    exit;
}

// echo $_SERVER["REQUEST_URI"]. "\n<br>";
$fullPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
// echo $fullPath . "\n<br>";

$parts = explode("/", $fullPath);

$resource = $parts[2];
$id = $parts[3] ?? null;
$method = $_SERVER["REQUEST_METHOD"];
// echo "Resource: $resource\n<br>";
// echo "ID: $id\n<br>";
// echo "Method: $method\n<br>";

if ($resource != "tasks") {
    http_response_code(404);
    echo "Resource not found\n";
}

$manager = new TaskManager($tasks);
$manager->process($method, $id);

?>