<?php

class TaskGateway
{
    private readonly PDO $pdo;
    public function __construct(MyDatabase $database)
    {
        $this->pdo = $database->Connect();
    }

    public function getAll(): array
    {
        $statement = $this->pdo->query("SELECT * FROM tasks");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array
    {
        $statement = $this->pdo->prepare("SELECT * FROM tasks WHERE id = :id");
        $statement->execute(['id' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $task): void
    {
        $statement = $this->pdo->prepare("INSERT INTO tasks (title, description) VALUES (:title, :description)");
        $statement->execute([
            'title' => $task['title'],
            'description' => $task['description']
        ]);
    }

    public function update(int $id, array $task): void
    {
        $statement = $this->pdo->prepare("UPDATE tasks SET title = :title, description = :description WHERE id = :id");
        $statement->execute([
            'id' => $id,
            'title' => $task['title'],
            'description' => $task['description']
        ]);
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM tasks WHERE id = :id");
        $statement->execute(['id' => $id]);
    }
}
