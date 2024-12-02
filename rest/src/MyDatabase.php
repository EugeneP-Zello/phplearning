<?php

class MyDatabase
{
    private ?PDO $pdo = null;
    public function __construct(
        private string $host,
        private string $name,
        private string $user,
        private string $password
    ) {
    }

    public function Connect(): PDO
    {
        if ($this->pdo !== null) {
            return $this->pdo;
        }
        $dataSourceName = "mysql:host={$this->host}:8889;dbname={$this->name};charset=utf8";
        $this->pdo = new PDO($dataSourceName, $this->user, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $this->pdo;
    }
}

