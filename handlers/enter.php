<?php
function enter(){
    //заносим введенный пользователем email в переменную $email, если он пустой, то уничтожаем переменную
    if (isset($_POST['email']) and $_POST['email'] != '' and isset($_POST['password']) and $_POST['password'] != '') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (preg_match("/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/", $email)){
            //заносим введенный пользователем password в переменную $password, если он пустой, то уничтожаем переменную
            //если email и пароль введены, то обрабатываем их
            $email = stripslashes($email);
            $email = htmlspecialchars($email);
            $password = stripslashes($password);
            $password = htmlspecialchars($password);

            //удаляем лишние пробелы
            $email = trim($email);
            $password = trim($password);

            // подключение к БД
            require("db.php");
            //извлекаем из базы все данные о пользователе с введенным email
            $query_statement = $pdo->prepare('SELECT * FROM users WHERE email=:email');
            $query_statement->execute(['email' => $email]);
            $query_row = $query_statement->fetch();

            if (empty($query_row['id_user'])) {
                //если пользователя с введенным email не существует
                $_SESSION['error'][] = 'Пользователя с таким email не существует!';
            } else {
                //если существует, то сверяем пароли
                if (password_verify($password, $query_row['password'])) {
                    //если пароли совпадают, то заполняем $_SESSION данными пользователя
                    $_SESSION['email'] = $query_row['email'];
                    $_SESSION['id_user'] = $query_row['id_user'];
                    $_SESSION['name'] = $query_row['name'];
                    $_SESSION['isAdmin'] = $query_row['isAdmin'];

                    header('Location: index.php');
                    die();
                } else {
                    //если пароли не сошлись
                    $_SESSION['error'][] = 'Неверный пароль!';
                }
            }
        } else {
            $_SESSION['error'][] = 'Формат email неверный!';
        }
    } else {
        $_SESSION['error'][] = 'Вы заполнили не все поля!';
    }
}

