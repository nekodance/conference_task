<?php
require_once ('handlers/session_start.php');


if (empty($_SESSION['id_user']) and !str_contains($_SERVER['REQUEST_URI'], 'authorization')) {
    header('Location: authorization.php');
    die();
}
// вынос редиректов перед версткой
require("handlers/db.php");

require('handlers/logout.php');
if (isset($_POST['logout'])) {
    logout();
}
require('handlers/enter.php');
if (isset($_POST['submit_enter'])) {
    enter();
}

require('handlers/save_report.php');
if (isset($_POST['submit_report_creation'])) {
    saveReport();
}
require('handlers/save_import.php');
if (isset($_POST['submit_import_excel'])) {
    saveImport();
}
require('handlers/save_user.php');
if (isset($_POST['submit_registration'])) {
    saveUser();
}

require 'handlers/get_db_list.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Availability</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="css/style.css">

</head>



<body>

<header>
    <div class="container">
        <div class="row" style="align-items: center; justify-content: space-around;">
            <div class="col text-center"  ><a href="index.php"  style="font-size: 1.3em;">Главная</a></div>
            <?php
            //    для авторизованных пользователей показаны: приветствие, имя пользователя, кнопка выход
            if (!empty($_SESSION['id_user']) and !empty($_SESSION['is_admin'])) {
                print("<div class='col text-center'><a href='registration.php'  style='font-size: 1.3em'>Регистрация</a></div>".
                    "<div class='col' ><form method='post'><input style='font-size: 1.3em;' type='submit' name='logout' value='Выход'/></form></div>");
            } else if (!empty($_SESSION['id_user'])){
                print("<div class='col text-center' ><form method='post'><input style='font-size: 1.3em;' type='submit' name='logout' value='Выход'/></form></div>");
            }
            ?>
        </div>
    </div>

    <?php
    if (!empty($_SESSION['id_user'])) { ?>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
            Импорт
        </button>

        <!-- Модальное окно -->
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Заголовок модального окна -->
                    <div class="modal-header">
                        <h4 class="modal-title text-dark">Загрузка файла</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Тело модального окна -->
                    <div class="modal-body">
                        <form method="post" enctype="multipart/form-data" autocomplete="off">
                            <input style="padding-left: 0" class="btn " type="file" name="excel_table" id="excel_table" title="Допустимые форматы: .xls" required>

                            <input name="submit_import_excel" type="submit" value="Загрузить"/>
                        </form>
                    </div>

                    <!--                        <!-- Футер модального окна -->-->
                    <!--                        <div class="modal-footer">-->
                    <!--                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>-->
                    <!--                        </div>-->

                </div>
            </div>
        </div>
        <?php
    }
    ?>
</header>