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
}

$task->readOne($_GET['id']);
$data = [];

if (!empty($task->task)) {
    // prepare data
    $data['id'] = $task->id;
    $data['task'] = $task->task;
    $data['status'] = $task->status;
    $data['date_created'] = $task->date_created;
    $data['date_updated'] = $task->date_updated;

    // set the status code
    http_response_code(200);

    // return response in JSON
    echo json_encode([
        'code'=>200,
        'data'=>$data
    ]);
} else {
    // set the status code
    http_response_code(404);

    // return response in JSON
    echo json_encode([
        'code'=>200,
        'message'=>"The Task with the given ID does not exist."
    ]);
}