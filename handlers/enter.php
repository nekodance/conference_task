<?php
function enter(){
    //заносим введенный пользователем email в переменную $email, если он пустой, то уничтожаем переменную
    if (isset($_POST['login']) and $_POST['login'] != '' and isset($_POST['password']) and $_POST['password'] != '') {
        $login = $_POST['login'];
        $password = $_POST['password'];


        //заносим введенный пользователем password в переменную $password, если он пустой, то уничтожаем переменную
        //если email и пароль введены, то обрабатываем их
        $login = stripslashes($login);
        $login = htmlspecialchars($login);
        $password = stripslashes($password);
        $password = htmlspecialchars($password);

        //удаляем лишние пробелы
        $login = trim($login);
        $password = trim($password);

        // подключение к БД
        //    require("db.php");
        global $pdo;
        //извлекаем из базы все данные о пользователе с введенным логином
        $query_statement = $pdo->prepare('SELECT * FROM user WHERE login=:login');
        $query_statement->execute(['login' => $login]);
        $query_row = $query_statement->fetch();

        if (empty($query_row['id'])) {
            //если пользователя с введенным email не существует
            $_SESSION['error'][] = 'Пользователя с таким логином не существует!';
        } else {
            //если существует, то сверяем пароли
            if (password_verify($password, $query_row['password'])) {
                //если пароли совпадают, то заполняем $_SESSION данными пользователя
                $_SESSION['login'] = $query_row['login'];
                $_SESSION['id_user'] = $query_row['id'];
                $_SESSION['is_admin'] = $query_row['is_admin'];

                if (!$_SESSION['is_admin']){
                    $_SESSION['user_store_id'] = $query_row['store_id'];
                    $query_statement = $pdo->prepare('SELECT name FROM store WHERE id=:user_store_id');
                    $query_statement->execute(['user_store_id' => $query_row['store_id']]);
                    $query_row = $query_statement->fetch();
                    $_SESSION['user_store_name'] = $query_row['name'];
                } else {
                    $_SESSION['user_store_id'] = 'admin';
                    $_SESSION['user_store_name'] = 'admin';
                }


                header('Location: index.php');
                die();
            } else {
                //если пароли не сошлись
                $_SESSION['error'][] = 'Неверный пароль!';
            }
        }
    } else {
        $_SESSION['error'][] = 'Вы заполнили не все поля!';
    }
}

