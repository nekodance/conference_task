<?php

// данные для подключения к БД берутся из конфигурационного файла
    $db_data = parse_ini_file("db.ini");

    $db_host = $db_data['host'];
    $db_user = $db_data['user'];
    $db_password = $db_data['password'];
    $db_name = $db_data['db_name'];

    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
    global $pdo;
    $pdo= new PDO($dsn,$db_user,$db_password);

