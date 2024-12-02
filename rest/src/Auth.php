<?php

class Auth {
    private readonly UserGateway $userGateway;
    private int $userId;
    public function __construct(MyDatabase $db) {
        $this->userGateway = new UserGateway($db);
        $this->userId = 0;
    }
    public function authenticate(): bool
    {
        if (empty($_SERVER["HTTP_AUTHORIZATION"])) {
            return $this->authenticateApiKey();
        }
        return $this->authenticateAccessToken();
    }

    private function authenticateApiKey(): bool
    {
        if (empty($_SERVER["HTTP_X_API_KEY"])) {
            http_response_code(400);
            echo json_encode(["error" => "API key is required"]);
            return false;
        }
        $account = $this->userGateway->getByKey($_SERVER["HTTP_X_API_KEY"]);
        if ($account === false) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid API key"]);
            return false;
        }
        $this->userId = $account["id"];
        return true;
    }

    private function authenticateAccessToken(): bool
    {
        $tokenBearer = $_SERVER["HTTP_AUTHORIZATION"];
        if (!preg_match('/Bearer (.+)/', $tokenBearer, $matches)) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid access token"]);
            return false;
        }
        $token = $matches[1];
        if (empty($token)) {
            http_response_code(400);
            echo json_encode(["error" => "Access token is required"]);
            return false;
        }
        $payload = json_decode(base64_decode($token), true);
        if ($payload === null) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid access token"]);
            return false;
        }
        $this->userId = $payload["uid"];
        return true;
    }

    public function id(): int
    {
        return $this->userId;
    }
}