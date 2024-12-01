<?php

class TaskManager
{
    public function process(string $method, ?string $id): void {
        if ($id === null) {
            if ($method === "GET") {
                echo "Getting all tasks\n";
            } elseif ($method === "POST") {
                echo "Creating a new task\n";
            } else {
                $this->notifyNotAllowed(["GET", "POST"]);
            }
        } else {
            if ($method === "GET") {
                echo "Get task $id\n<br>";
            } elseif ($method === "PATCH") {
                echo "Update task $id\n<br>";
            } elseif ($method === "DELETE") {
                echo "Delete task $id\n<br>";
            } else {
                $this->notifyNotAllowed(["GET", "PATCH", "DELETE"]);
            }
        }
    }

    private function notifyNotAllowed(array $allowed): void {
        http_response_code(405);
        header("Allow: " . implode(", ", $allowed));
    }
}