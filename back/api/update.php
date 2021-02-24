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
if (empty($_POST['id'])) {
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

$task->readOne($_POST['id']);
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

if ($task->task === $_POST['task'] && $task->status === $_POST['status']) {
    // set the status code
    http_response_code(200);

    // return response in JSON
    echo json_encode([
        'code'=>200,
        'message'=>"Nothing is updated."
    ]);
    exit;
}

if (!in_array($_POST['status'], $task::getStatuses())) {
    // set the status code
    http_response_code(400);

    // return response in JSON
    echo json_encode([
        'code'=>400,
        'message'=>"Status is invalid.",
        'data'=>[]
    ]);
    exit;
}

$task->task = $_POST['task'] ? $_POST['task'] : $task->task;
$task->status = $_POST['status'] ? $_POST['status'] : $task->status;
$task->date_updated = date('Y-m-d H:i:s');

$data = [];

if ($task->update()) {
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
        'message'=>'Task is updated successfully.',
        'data'=>$data
    ]);
} else {
    // set the status code
    http_response_code(503);

    // return response in JSON
    echo json_encode([
        'code'=>503,
        'message'=>"Task updated has failed."
    ]);
}