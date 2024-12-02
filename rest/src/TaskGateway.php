<?php

class TaskGateway
{
    private readonly PDO $pdo;
    public function __construct(MyDatabase $database)
    {
        $this->pdo = $database->Connect();
    }

    public function getAll(int $uid): array
    {
        $sql = "SELECT * FROM tasks WHERE user_id = :uid ORDER BY name";
        if ($uid === 0) {
            $sql = "SELECT * FROM tasks ORDER BY name";
        }
        $statement = $this->pdo->prepare($sql);
        if ($uid !== 0) {
            $statement->bindValue('uid', $uid, PDO::PARAM_INT);
        }
        $statement->execute();

        $allTasks = [];
        while ($task = $statement->fetch(PDO::FETCH_ASSOC)) {
            $task['finished'] = (bool)$task['finished'];
            $allTasks[] = $task;
        }
        return $allTasks;
    }

    public function get(int $id, int $uid): array | false
    {
        $sql = "SELECT * FROM tasks WHERE id = :id AND user_id = :uid";
        if ($uid === 0) {
            $sql = "SELECT * FROM tasks WHERE id = :id";
        }
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        if ($uid !== 0) {
            $statement->bindValue('uid', $uid, PDO::PARAM_INT);
        }
        $statement->execute();
        $task = $statement->fetch(PDO::FETCH_ASSOC);
        if ($task !== false) {
            $task['finished'] = (bool)$task['finished'];
        }
        return $task;
    }

    public function addNew(array $task, int $uid): string
    {
        $statement = $this->pdo->prepare("INSERT INTO tasks (name, priority, finished, user_id) VALUES (:name, :priority, :finished, :uid)");
        $statement->bindValue('name', $task['name'], PDO::PARAM_STR);
        $statement->bindValue('finished', $task['finished'] ?? false, PDO::PARAM_BOOL);

        if (empty($task['priority'])) {
            $statement->bindValue('priority', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue('priority', $task['priority'], PDO::PARAM_INT);
        }
        $statement->bindValue('uid', $uid, PDO::PARAM_INT);
        $statement->execute();
        return $this->pdo->lastInsertId();
    }

    public function update(int $id, array $task): int
    {
        $fields = [];
        if (array_key_exists("name", $task)) {
            $fields["name"] = [$task["name"], PDO::PARAM_STR];
        }
        if (array_key_exists("priority", $task)) {
            $fields["priority"] = [$task["priority"], $task["priority"] === null ? PDO::PARAM_NULL : PDO::PARAM_INT];
        }
        if (array_key_exists("finished", $task)) {
            $fields["finished"] = [$task["finished"], PDO::PARAM_BOOL];
        }

        if (empty($fields)) {
            return 0;
        }
        $sets = array_map(function ($value) {
            return "$value = :$value";
        }, array_keys($fields));
        $sql = "UPDATE tasks SET " . implode(", ", $sets) . " WHERE id = :id";
        $statement = $this->pdo->prepare($sql);
        foreach ($fields as $field => $value) {
            $statement->bindValue($field, $value[0], $value[1]);
        }
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->rowCount();
    }

    public function drop(int $id, int $uid): int
    {
        $sql = "DELETE FROM tasks WHERE id = :id AND user_id = :uid";
        if ($uid === 0) {
            $sql = "DELETE FROM tasks WHERE id = :id";
        }
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['id' => $id]);
        $statement->bindValue('uid', $uid, PDO::PARAM_INT);
        return $statement->rowCount();
    }
}
