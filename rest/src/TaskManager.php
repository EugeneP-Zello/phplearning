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
                http_response_code(405);
                echo "Method not allowed\n";
            }
        } else {
            if ($method === "GET") {
                echo "Get task $id\n<br>";
            } elseif ($method === "PATCH") {
                echo "Update task $id\n<br>";
            } elseif ($method === "DELETE") {
                echo "Delete task $id\n<br>";
            } else {
                http_response_code(405);
                echo "Method not allowed\n";
            }
        }
    }
}