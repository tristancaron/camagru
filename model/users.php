<?php
/**
 * Created by PhpStorm.
 * User: tristan
 * Date: 09/12/2015
 * Time: 11:54
 */

namespace UsersModel;

require_once __DIR__ . '/../config/database.php';
global $DB_DSN, $DB_USER, $DB_PASSWORD;

$db = new \PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

function add($data) {
    global $db;

    $task = $db->prepare('SELECT username, email FROM users WHERE email = :email OR username = :username');
    $task->bindParam(':email', $data['email']);
    $task->bindParam(':username', $data['username']);

    $task->execute();
    $result = $task->fetchAll();

    if (!empty($result)) {
        return 'User already registered';
    } else {
        $task = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $task->bindParam(':username', $data['username']);
        $task->bindParam(':email', $data['email']);
        $task->bindParam(':password', $data['password']);

        $task->execute();
    }

    $db = null;
    return null;
}

function get($data) {
    global $db;

    $task = $db->prepare('SELECT id, email, password, username FROM users WHERE email = :email');
    $task->bindParam(':email', $data['email']);

    $task->execute();
    $db = null;
    return $task->fetch();
}

