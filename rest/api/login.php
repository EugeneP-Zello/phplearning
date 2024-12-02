<?php

declare(strict_types=1);

require dirname(__DIR__) . "/api/bootstrap.php";

$method = $_SERVER["REQUEST_METHOD"];

if ($method !== "POST") {
    http_response_code(405);
    header("Allow: POST");
    exit;
}

$input = (array)json_decode(file_get_contents('php://input'), true);

if (!array_key_exists("login", $input) || !array_key_exists("password", $input)) {
    http_response_code(400);
    echo json_encode(["error" => "Login and password are required"]);
    exit;
}

$db = new MyDatabase($_ENV["DB_HOST"], $_ENV["DB"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
$userGateway = new UserGateway($db);
$account = $userGateway->getByLogin($input["login"]);

if ($account === false) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid login"]);
    exit;
}

if (!password_verify($input["password"], $account["hash"])) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid password"]);
    exit;
}

$payload = [
    "uid" => $account["id"],
    "login" => $account["login"]
];

$token = base64_encode(json_encode($payload));
echo json_encode(["token" => $token]);