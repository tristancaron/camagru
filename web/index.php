<?php
/**
 * Created by PhpStorm.
 * User: tristan
 * Date: 09/12/2015
 * Time: 11:02
 */

require_once __DIR__ . '/../controller/app.php';

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: authentication.php");
    exit();
}

$info_picture = null;

if (count($_GET) === 1 && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_GET['add'])) {
        if (($info_picture = \AppController\perform()) === null) {
            $info_picture = 'Successfully added';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Camagru</title>
    <script type="application/ecmascript" src="javascript/app.js" defer></script>
</head>
<body>
<header>Camagru</header>
<main>
    <form action="index.php?add" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend>Preview</legend>
            <canvas id="picture-view"></canvas>
        </fieldset>
        <fieldset>
            <legend>Picture</legend>
            <label for="input-webcam">From WebCam </label>
            <button type="button" id="input-webcam">Take a picture</button>
            <label for="input-local">Select a picture</label>
            <input type="file" name="picture" id="input-local">
        </fieldset>
        <fieldset>
            <legend>Filter</legend>
            <label for="input-filter">Selected filter </label>
            <select name="filter" id="input-filter">
                <option value="frames/soldier_frame.png">Soldier</option>
                <option value="frames/wanted_frame.png">Wanted</option>
                <option value="frames/wood_frame.png">Wood</option>
            </select>
        </fieldset>
        <button type="submit">Submit</button>
        <p><?php echo $info_picture ?></p>
    </form>
    <?php \AppController\display_user_pictures() ?>
</main>
</body>
</html>
