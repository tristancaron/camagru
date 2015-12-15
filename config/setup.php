<?php

require_once __DIR__ . '/database.php';

function setup_database() {
    global $DB_DSN, $DB_USER, $DB_PASSWORD;

    try {
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->exec('
CREATE TABLE IF NOT EXISTS users (
                    id INTEGER PRIMARY KEY,
                    username VARCHAR(20),
                    email VARCHAR(255),
                    password VARCHAR(255))
'
        );

        $db->exec('
CREATE TABLE IF NOT EXISTS comments (
                    id INTEGER PRIMARY KEY,
                    data TEXT,
                    picture_id INTEGER,
                    owner_id INTEGER)
'
        );

        $db->exec('
CREATE TABLE IF NOT EXISTS pictures (
                    id INTEGER PRIMARY KEY,
                    data BLOB,
                    owner_id INTEGER,
                    time DATE)
'
        );

        $db->exec('
CREATE TABLE IF NOT EXISTS likes (
                    id INTEGER PRIMARY KEY,
                    picture_id INTEGER,
                    owner_id INTEGER)
'
        );

        $db = null;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

setup_database();