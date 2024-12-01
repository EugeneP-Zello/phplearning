<?php

class ErrorProcessor
{
    public static function processError(int $code, string $message, string $file, int $line): void
    {
        throw new ErrorException($message, $code, 1, $file, $line);
    }
    public static function process(Throwable $ex): void
    {
        http_response_code(505);
        echo json_encode([
            "code" => $ex->getCode(),
            "error" => $ex->getMessage(),
            "file" => $ex->getFile(),
            "line" => $ex->getLine()
        ]);
    }
}
