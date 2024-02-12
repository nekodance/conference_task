<?php
function saveUser()
{
    if (isset($_POST['login'])) {
        $login = $_POST['login'];
        if ($login == '') { unset($login);
        }
    }

    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        if ($password =='') { unset($password);
        }
    }
    if (isset($_POST['password_proof'])) {
        $password_proof = $_POST['password_proof'];
        if ($password_proof =='') { unset($password_proof);
        }
    }
    if (isset($_POST['store'])) {
        $store=$_POST['store'];
        if ($store =='') { unset($store);
        }
    }
//    заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
    if (empty($login)  or empty($password) or empty($password_proof) or empty($store)) {
        $_SESSION['error'][] = 'Вы ввели не всю информацию!';
    }
//    if (!preg_match("/[а-яёА-ЯЁ\-\s]*/", $login) ) {
//        $_SESSION['error'][] = 'Формат имени неверный!';
//    }
    if ($_POST['password'] != $_POST['password_proof']) {
        $_SESSION['error'][] = 'Пароли не совпадают!';
    }
    if (strlen($_POST['password']) < 6) {
        $_SESSION['error'][] = 'Пароль короче 6 символов!';
    }
    if (is_numeric($_POST['password'])) {
        $_SESSION['error'][] = 'Пароль не может состоять из одних цифр!';
    }
    if (!isset($_SESSION['error'])){
        $login = stripslashes($login);
        $login = htmlspecialchars($login);
        $password = stripslashes($password);
        $password = htmlspecialchars($password);
        //удаляем лишние пробелы
        $login = trim($login);
        $password = trim($password);
        // подключаемся к базе
        //    require("db.php");
        global $pdo;
        // проверка на существование пользователя с таким же логином

        $query_statement = $pdo->prepare('SELECT id FROM user WHERE login=:login');
        $query_statement->execute(['login' => $login]);
        $query_row = $query_statement->fetch();

        if (!empty($query_row['id'])) {
            $_SESSION['error'][] = 'Пользователь с таким логином уже существует!';
        }
        // если такого нет, то сохраняем данные
        if (!isset($_SESSION['error'])) {
            $session_password = $password;
            $password = password_hash($password, PASSWORD_DEFAULT);

            //получаем id магазина по названию
            $query_statement = $pdo->prepare('SELECT * FROM store');
            $query_statement->execute();
            while($row = $query_statement->fetch()){
                if (str_contains($row['name'], $store)){
                    $store_id = $row['id'];
                    break;
                }
            }
            $query_statement = $pdo->prepare('INSERT INTO user (login, password, store_id) VALUES(:login,:password,:store_id)');
            $query_statement->execute(['login' => $login, 'password' => $password, 'store_id' => $store_id]);

            header('Location: index.php');
            die();
        } else{
            print_r($_SESSION);
        }
    }
}


