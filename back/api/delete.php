<?php
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . "/todo-app/vendor/autoload.php";

use App\config\Database;
use App\objects\Task;

$db = new Database();
$pdo = $db->getConnection();
$task = new Task($pdo);

// validation
if (empty($_GET['id'])) {
    // set the status code
    http_response_code(400);

    // return response in JSON
    echo json_encode([
        'code'=>400,
        'message'=>"Task ID is required.",
        'data'=>[]
    ]);
    exit;
}

$task->readOne($_GET['id']);
// does task exist
if (empty($task->id)) {
    // set the status code
    http_response_code(400);

    // return response in JSON
    echo json_encode([
        'code'=>400,
        'message'=>"Task with given ID does not exist.",
        'data'=>[]
    ]);
    exit;
}

if ($task->delete()) {
    // set the status code
    http_response_code(200);

    // return response in JSON
    echo json_encode([
        'code'=>200,
        'message'=>'Task is deleted successfully.'
    ]);
} else {
    // set the status code
    http_response_code(503);

    // return response in JSON
    echo json_encode([
        'code'=>503,
        'message'=>"Task deletion has failed."
    ]);
}