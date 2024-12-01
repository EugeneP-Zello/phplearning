<?php

class TaskManager
{
    public function __construct(private readonly TaskGateway $gateway)
    {

    }
    public function process(string $method, ?string $id): void {
        if ($id === null) {
            if ($method === "GET") {
                echo json_encode($this->gateway->getAll());
            } elseif ($method === "POST") {
                //print_r($_POST);
                $newTask = (array)json_decode(file_get_contents('php://input'), true);
                $invalids = $this->validateNewTaskAttributes($newTask, true);
                if (!empty($invalids)) {
                    $this->notifyInvalidAttributes($invalids);
                    return;
                }
                $id = $this->gateway->addNew($newTask);
                $this->respondCreated($id);
            } else {
                $this->notifyNotAllowed(["GET", "POST"]);
            }
        } else {
            $task = $this->gateway->get((int)$id);
            if ($task === false) {
                $this->notifyNotFound($id);
                return;
            }
            if ($method === "GET") {
                echo json_encode($task);
            } elseif ($method === "PATCH") {
                $newTask = (array)json_decode(file_get_contents('php://input'), true);
                $invalids = $this->validateNewTaskAttributes($newTask, false);
                if (!empty($invalids)) {
                    $this->notifyInvalidAttributes($invalids);
                    return;
                }
                $updated = $this->gateway->update((int)$id, $newTask);
                echo json_encode(["result" => $updated === 0 ? "No changes" : "Task updated"]);
            } elseif ($method === "DELETE") {
                $dropped = $this->gateway->drop((int)$id);
                echo json_encode(["result" => $dropped === 0 ? "Task not found" : "Task deleted"]);
            } else {
                $this->notifyNotAllowed(["GET", "PATCH", "DELETE"]);
            }
        }
    }

    private function validateNewTaskAttributes(array $task, bool $newTask): array {
        $errors = [];
        if (empty($task['name']) && $newTask) {
            $errors[] = "Task name is required";
        }
        if (!empty($task['priority']) && !is_int($task['priority'])) {
            $errors[] = "Priority must be an integer";
        }
        if (!empty($task['finished']) && !is_bool($task['finished'])) {
            $errors[] = "Finished must be a boolean";
        }
        return $errors;
    }

    private function respondCreated(string $id): void {
        http_response_code(201);
        echo json_encode(["result" => "Task created", "id" => $id]);
    }

    private function notifyInvalidAttributes(array $errors): void {
        http_response_code(422);
        echo json_encode(["errors" => $errors]);
    }

    private function notifyNotFound(string $id): void {
        http_response_code(404);
        echo json_encode(["error" => "Task $id not found"]);
    }

    private function notifyNotAllowed(array $allowed): void {
        http_response_code(405);
        header("Allow: " . implode(", ", $allowed));
    }
}