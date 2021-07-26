<?php
function logout()
{
    session_start();
//    при разлогине удаляем данные о текущем пользователе из $_SESSION
    unset($_SESSION['email']);
    unset($_SESSION['id_user']);
    unset($_SESSION['name']);
    unset($_SESSION['isAdmin']);
    unset($_SESSION['title']);

    header('Location: index.php');
    die();
}


