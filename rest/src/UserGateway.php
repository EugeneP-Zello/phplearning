<?php
class UserGateway
{
    private readonly PDO $pdo;

    public function __construct(MyDatabase $database)
    {
        $this->pdo = $database->Connect();
    }

    public function getByKey(string $key): array | false
    {
        $statement = $this->pdo->prepare("SELECT * FROM users WHERE apikey = :key");
        $statement->bindValue('key', $key, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}