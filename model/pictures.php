<?php
/**
 * Created by PhpStorm.
 * User: tristan
 * Date: 10/12/2015
 * Time: 11:59
 */

namespace PictureModel;

require_once __DIR__ . '/../config/database.php';
global $DB_DSN, $DB_USER, $DB_PASSWORD;

$db = new \PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

function add($image)
{
    global $db;

    $task = $db->prepare("INSERT INTO pictures (data, owner_id, time) VALUES (:data, :owner_id, datetime('now'))");
    $task->bindParam(':data', $image, \PDO::PARAM_LOB);
    $task->bindParam(':owner_id', $_SESSION['user']['id']);

    $task->execute();

    return;
}

function get_all_from_user()
{
    global $db;

    $task = $db->prepare('SELECT * FROM pictures WHERE owner_id = :id');
    $task->bindParam(':id', $_SESSION['user']['id']);

    $task->execute();

    return $task->fetchAll();
}