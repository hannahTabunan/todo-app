<?php
/**
 *  * Created by hannah on 2/23/2021.
 */

namespace App\objects;

use PDO;

class Task
{
    private $pdo;

    public $id;
    public $task;
    public $status;
    public $date_created;
    public $date_updated;

    private static $statuses = [
        'P' => 'Pending',
        'IP' => 'In Progress',
        'C' => 'Completed',
        'D' => 'Deleted'
    ];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function read()
    {
        $stmt = $this->pdo->prepare("select * from tasks");
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id)
    {
        $stmt = $this->pdo->prepare("select * from tasks where id = :id limit 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // store values in the properties
        $this->id = $result['id'];
        $this->task = $result['task'];
        $this->status = $result['status'];
        $this->date_created = $result['date_created'];
        $this->date_updated = $result['date_updated'];
    }

    public function create()
    {
        try {
            $stmt = $this->pdo->prepare("
                insert into tasks (task, status, date_created) 
                values(:task, :status, :date_created)
            ");
            $stmt->bindParam(':task', $this->task, PDO::PARAM_STR);
            $stmt->bindValue(':status', 'P', PDO::PARAM_STR);
            $stmt->bindValue(':date_created', date('Y-m-d H:i:s'), PDO::PARAM_STR);

            if($stmt->execute()) {
                $id = $this->pdo->lastInsertId();
                $this->readOne($id);
                return true;
            } else {
                throw new \Exception("ERROR: Encountered while saving Task.");
            }
        } catch(\PDOException $e) {
            echo $e->getMessage();
            return false;
        } catch(\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function update()
    {
        try {
            $stmt = $this->pdo->prepare("
                update tasks 
                set task = :task, status = :status, date_updated = :date_updated
                where id = :id
            ");
            $stmt->bindParam(':task', $this->task, PDO::PARAM_STR);
            $stmt->bindParam(':status', $this->status, PDO::PARAM_STR);
            $stmt->bindParam(':date_updated', $this->date_updated, PDO::PARAM_STR);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

            if($stmt->execute()) {
                return true;
            } else {
                throw new \Exception("ERROR: Encountered while saving Task.");
            }
        } catch(\PDOException $e) {
            echo $e->getMessage();
            return false;
        } catch(\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function delete()
    {
        try {
            $stmt = $this->pdo->prepare("
                delete from tasks where id = :id
            ");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

            if($stmt->execute()) {
                return true;
            } else {
                throw new \Exception("ERROR: Encountered while deleting Task.");
            }
        } catch(\PDOException $e) {
            echo $e->getMessage();
            return false;
        } catch(\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public static function getStatuses()
    {
        return array_keys(self::$statuses);
    }
}