<?php
/**
 * Created by PhpStorm.
 * User: tristan
 * Date: 09/12/2015
 * Time: 11:56
 */

namespace AuthenticationController;

require_once __DIR__ . '/../model/users.php';

// todo: Email sending, and password recovery

function register()
{
    $filter = array(
        'username' => FILTER_SANITIZE_STRING,
        'email' => FILTER_VALIDATE_EMAIL,
        'password' => array(
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => array('regexp' => '/^(?=\S*\d)(?=\S*[a-zA-Z])\S{8,}$/')
        )
    );
    $filter['password_bis'] = &$filter['password'];

    $user_data = filter_input_array(INPUT_POST, $filter, FILTER_NULL_ON_FAILURE);

    if (in_array(false, $user_data) || in_array(null, $user_data)) {
        return 'Fields are not filled as expected';
    } else if ($user_data['password'] != $user_data['password_bis']) {
        return 'Passwords do not match';
    } else {
        $user_data['password'] = password_hash($user_data['password'], PASSWORD_DEFAULT);
        return \UsersModel\add($user_data);
    }
}

function login() {
    $filter = array(
        'email' => FILTER_VALIDATE_EMAIL,
        'password' => array(
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => array('regexp' => '/^(?=\S*\d)(?=\S*[a-zA-Z])\S{8,}$/')
        )
    );

    $user_data = filter_input_array(INPUT_POST, $filter, FILTER_NULL_ON_FAILURE);

    if ($user_data === null || in_array(false, $user_data) || in_array(null, $user_data)) {
        return 'Fields are not filled as expected';
    } else {
        $user = \UsersModel\get($user_data);

        if (empty($user)) {
            return "Email not found";
        } else if (!password_verify($user_data['password'], $user['password'])) {
            return "Password incorrect";
        } else {
            $_SESSION['user'] = array(
                'id' => $user['id']
            );
            return null;
        }
    }
}