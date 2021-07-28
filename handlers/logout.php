<?php
require_once ('../handlers/exceptions_error_handler.php');
function logout()
{
    try{
        session_start();
    } catch (Exception $e){

    }
//    при разлогине удаляем данные о текущем пользователе из $_SESSION
    unset($_SESSION['email']);
    unset($_SESSION['id_user']);
    unset($_SESSION['name']);
    unset($_SESSION['isAdmin']);
    unset($_SESSION['title']);

    session_destroy();

    header('Location: index.php');
    die();
}


