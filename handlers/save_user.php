<?php

function saveUser()
{
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
        if ($name == '') { unset($name);
        }
    }
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        if ($email == '') { unset($email);
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
    if (isset($_POST['term_acceptance'])) {
        $term_acceptance=$_POST['term_acceptance'];
        if ($term_acceptance =='') { unset($term_acceptance);
        }
    }
    //заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
    if (empty($name) or empty($email) or empty($password) or empty($password_proof) or empty($term_acceptance)) {
        $_SESSION['error'][] = 'Вы ввели не всю информацию!';
    }
    if (!preg_match("/[а-яёА-ЯЁ\-\s]*/", $name) ) {
        $_SESSION['error'][] = 'Формат имени неверный!';
    }
    if (!preg_match("/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/", $email)) {
        $_SESSION['error'][] = 'Формат email неверный!';
    }
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
        $name = stripslashes($name);
        $name = htmlspecialchars($name);
        $email = stripslashes($email);
        $email = htmlspecialchars($email);
        $password = stripslashes($password);
        $password = htmlspecialchars($password);
        //удаляем лишние пробелы
        $name = trim($name);
        $email = trim($email);
        $password = trim($password);
        // подключаемся к базе
        include ("db.php");
        // проверка на существование пользователя с таким же email

        $query_statement = $pdo->prepare('SELECT id_user FROM users WHERE email=:email');
        $query_statement->execute(['email' => $email]);
        $query_row = $query_statement->fetch();

        if (!empty($query_row['id_user'])) {
            $_SESSION['error'][] = 'Пользователь с таким email уже существует!';
        }
        // если такого нет, то сохраняем данные
        if (!isset($_SESSION['error'])) {
            $session_password = $password;
            $password = password_hash($password, PASSWORD_DEFAULT);

            $query_statement = $pdo->prepare('INSERT INTO users (name, email, password) VALUES(:name,:email,:password)');
            $query_result = $query_statement->execute(['name' => $name,'email' => $email,'password' => $password]);

            // Проверяем, есть ли ошибки
            if ($query_result == 'true') {

                $query_statement = $pdo->prepare('SELECT * FROM users WHERE email=:email');
                $query_statement->execute(['email' => $email]);
                $query_row = $query_statement->fetch();

                if (password_verify($session_password, $query_row['password'])) {

                    $_SESSION['email'] = $query_row['email'];
                    $_SESSION['id_user'] = $query_row['id_user'];
                    $_SESSION['name'] = $query_row['name'];
                    $_SESSION['isAdmin'] = $query_row['isAdmin'];

                    header('Location: index.php');
                    die();
                } else {
                    $_SESSION['error'][] = 'Ошибка регистрации!';
                }
            }
        }
    }
}


