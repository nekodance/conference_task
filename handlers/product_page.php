<?php

if (!isset($query_statement)) {
    if (!isset($_SESSION['id']) or $_SESSION['id'] != $_POST['title']) {
        $_SESSION['id'] = $_POST['title'];
    }
    $query_statement = $pdo->prepare('SELECT * FROM goods.availability WHERE id = :id');
    $query_statement->execute(['id' => $_SESSION['id']]);
    $query_row = $query_statement->fetch();
}


