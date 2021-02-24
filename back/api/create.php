<?php
/**
 *  * Created by hannah on 2/23/2021.
 */
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once $_SERVER['DOCUMENT_ROOT'] . "/todo-app/vendor/autoload.php";

use App\config\Database;
use App\objects\Task;
use App\config\Helper;

$db = new Database();
$pdo = $db->getConnection();
$task = new Task($pdo);
$data = [];

// validation
if (empty($_POST['task'])) {
    echo json_encode([
       'code'=>400,
       'message'=>"Task is required"
    ]);
    exit;
}

$task->task = Helper::sanitize($_POST['task']);

if ($task->create()) {
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
        'message'=>"Task is successfully created.",
        'data'=>$data
    ]);
} else {
    echo json_encode([
        'code'=>400,
        'message'=>"Task creation failed."
    ]);
}