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
$stmt = $task->read();
$count = $stmt->rowCount();
$data = [];

if ($count > 0) {
    // prepare data
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $data[] = [
          'id'=>$id,
          'task'=>$task,
          'status'=>$status,
          'date_created'=>$date_created,
          'date_updated'=>$date_updated,
        ];
    }

    // set the status code
    http_response_code(200);

    // return response in JSON
    echo json_encode([
        'code'=>200,
        'data'=>$data
    ]);
} else {
    // set the status code
    http_response_code(200);

    // return response in JSON
    echo json_encode([
        'code'=>200,
        'message'=>"No record found."
    ]);
}