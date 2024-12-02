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
        $apiKey = $_SERVER["HTTP_X_API_KEY"];
        if (empty($apiKey)) {
            http_response_code(400);
            echo json_encode(["error" => "API key is required"]);
            return false;
        }
        $account = $this->userGateway->getByKey($apiKey);
        if ($account === false) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid API key"]);
            return false;
        }
        $this->userId = $account["id"];
        return true;
    }

    public function id(): int
    {
        return $this->userId;
    }
}