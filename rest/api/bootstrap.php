<?php

require dirname(__DIR__) . "/vendor/autoload.php";

set_error_handler("ErrorProcessor::processError");
set_exception_handler("ErrorProcessor::process");

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

header("Content-Type: application/json; charset=UTF-8");