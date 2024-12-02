<?php
// http://localhost:8044/api/tasks/123

declare(strict_types=1);

require dirname(__DIR__) . "/api/bootstrap.php";

$testClassObject = new TestClass('Hello, World!');
// echo $testClassObject->getName() . "\n<br>";

$db = new MyDatabase($_ENV["DB_HOST"], $_ENV["DB"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);

$auth = new Auth($db);
if (!$auth->authenticate()) {
    exit;
}
$tasks = new TaskGateway($db);

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

$manager = new TaskManager($tasks, $auth->id());
$manager->process($method, $id);

?>