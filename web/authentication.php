<?php
/**
 * Created by PhpStorm.
 * User: tristan
 * Date: 09/12/2015
 * Time: 11:42
 */

session_start();

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$info_login = null;
$info_register = null;

if (count($_GET) === 1 && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_GET['login'])) {
        require_once __DIR__ . '/../controller/authentication.php';
        if (($info_login = \AuthenticationController\login()) === null) {
            header("Location: index.php");
            exit();
        }
    } else if (isset($_GET['register'])) {
        require_once __DIR__ . '/../controller/authentication.php';
        if (($info_register = \AuthenticationController\register()) === null) {
            $info_register = 'You are now registered. You can login.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Camagru</title>
    <link rel="stylesheet" type="text/css" href="css/authentication.css">
    <script type="application/javascript" src="javascript/authentication.js" defer></script>
</head>
<body>
<main>
    <div class="form-wrap">
        <div class="tabs">
            <h3 class="login-tab"><a class="active" href="#login-tab-content">Login</a></h3>
            <h3 class="signup-tab"><a href="#signup-tab-content">Sign Up</a></h3>
        </div><!--.tabs-->

        <div class="tabs-content">
            <div id="login-tab-content" class="active">
                <form class="login-form" action="/authentication.php?login" method="post">
                    <input type="email" class="input" id="user_login" autocomplete="off" placeholder="Email" name="email">
                    <input type="password" class="input" id="user_pass" autocomplete="off" placeholder="Password" name="password">
                    <input type="submit" class="button" value="Login">
                </form><!--.login-form-->
                <div class="help-text">
                    <p><a href="#" id="forget-password">Forget your password?</a></p>
                </div><!--.help-text-->
            </div><!--.login-tab-content-->

            <div id="signup-tab-content">
                <form class="signup-form" action="/authentication.php?register" method="post">
                    <input type="email" class="input" id="user_email" autocomplete="off" placeholder="Email" name="email">
                    <input type="text" class="input" id="user_name" autocomplete="off" placeholder="Username" name="username">
                    <input type="password" class="input" id="user_pass" autocomplete="off" placeholder="Password" name="password">
                    <input type="password" class="input" id="user_pass" autocomplete="off" placeholder="Password Bis" name="password_bis">
                    <input type="submit" class="button" value="Sign Up">
                </form><!--.login-form-->
            </div><!--.signup-tab-content-->

            <div id="forget-tab-content">
                <form class="signup-form" action="/authentication.php?forget" method="post">
                    <input type="email" class="input" id="user_email" autocomplete="off" placeholder="Email" name="email">
                    <input type="submit" class="button" value="Retrieve">
                </form><!--.login-form-->
            </div>
            <div class="help-text">
                <p><?php echo $info_login ?: $info_register ?></p>
            </div>
        </div><!--.tabs-content-->
    </div><!--.form-wrap-->
</main>
</body>
</html>
