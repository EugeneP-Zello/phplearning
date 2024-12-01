<?php

class Database
{
    public function __construct(
        private string $host,
        private string $name,
        private string $user,
        private string $password
    ) {
    }

    public function Connect(): PDO
    {
        $dataSourceName = "mysql:host={$this->host};dbname={$this->name};charset=utf8";
       return new PDO($dataSourceName, $this->user, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}

