<?php
require_once ('../handlers/session_start.php');

// вынос редиректов перед версткой
require("../handlers/db.php");

require ('../handlers/logout.php');
if (isset($_POST['logout'])) {
    logout();
}
require ('../handlers/enter.php');
if (isset($_POST['submit_enter'])) {
        enter();
}
require ('../handlers/save_user.php');
if (isset($_POST['submit_registration'])) {
        saveUser();
}
require ('../handlers/save_report.php');
if (isset($_POST['submit_report_creation'])) {
    saveReport();
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Conference</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<header>

        <div class="container">
            <div class="row" style="align-items: center; justify-content: space-around;">
                <div class="col text-center"  ><a href="index.php"  style="font-size: 1.3em;">Главная</a></div>

    <?php
//    для авторизованных пользователей показаны: приветствие, имя пользователя, кнопка выход
    if (!empty($_SESSION['name']) and !empty($_SESSION['id_user'])) {
        print("<div class='col text-center' style='font-size: 1.3em'>Здравствуйте, $_SESSION[name]</div>".
            "<div class='col' ><form method='post'><input style='font-size: 1.3em;' type='submit' name='logout' value='Выход'/></form></div>");}
//   для неавторизованных пользователей показаны: кнопка авторизации, кнопка регистрации
    if (empty($_SESSION['name']) or empty($_SESSION['id_user'])) {
        // Если пусты, то мы не выводим ссылку
        print("<div class='col text-center'><a href='authorization.php'  style='font-size: 1.3em'>Авторизация</a></div>");
        print("<div class='col text-center'><a href='registration.php'  style='font-size: 1.3em'>Регистрация</a></div>");
    }
    ?>
            </div>
        </div>

</header>