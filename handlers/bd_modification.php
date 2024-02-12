<?php

function productModification()
{
//    if (isset($_POST['name'])) {
//        $name = $_POST['name'];
//        if ($name == '') { unset($name);
//        }
//    }
//    if (isset($_POST['email'])) {
//        $email = $_POST['email'];
//        if ($email == '') { unset($email);
//        }
//    }
//    if (isset($_POST['password'])) {
//        $password = $_POST['password'];
//        if ($password =='') { unset($password);
//        }
//    }
//    if (isset($_POST['password_proof'])) {
//        $password_proof = $_POST['password_proof'];
//        if ($password_proof =='') { unset($password_proof);
//        }
//    }
//    if (isset($_POST['term_acceptance'])) {
//        $term_acceptance=$_POST['term_acceptance'];
//        if ($term_acceptance =='') { unset($term_acceptance);
//        }
//    }
    //заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
//    if (empty($name) or empty($email) or empty($password) or empty($password_proof) or empty($term_acceptance)) {
//        $_SESSION['error'][] = 'Вы ввели не всю информацию!';
//    }
//    if (!preg_match("/[а-яёА-ЯЁ\-\s]*/", $name) ) {
//        $_SESSION['error'][] = 'Формат имени неверный!';
//    }
//    if (!preg_match("/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/", $email)) {
//        $_SESSION['error'][] = 'Формат email неверный!';
//    }
//    if ($_POST['password'] != $_POST['password_proof']) {
//        $_SESSION['error'][] = 'Пароли не совпадают!';
//    }
//    if (strlen($_POST['password']) < 6) {
//        $_SESSION['error'][] = 'Пароль короче 6 символов!';
//    }
//    if (is_numeric($_POST['password'])) {
//        $_SESSION['error'][] = 'Пароль не может состоять из одних цифр!';
//    }

    $name = $_POST['name'];
    $company = $_POST['company'];
    $code = $_POST['code'];
    $count = $_POST['count'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $photo = $_POST['photo'];
    $group = $_POST['group'];
    $sold = $_POST['sold'];
    $en_route = $_POST['en_route'];
    $purchase = $_POST['purchase'];
    $price_relevance = $_POST['price_relevance'];
    if (empty($price_relevance)){
        $price_relevance = null;
    }
    $analogs = $_POST['analogs'];
    $id = $_POST['id'];
    $date_time = $_POST['date_time'];
    if (empty($date_time)){
        $date_time = null;
    }
    $point = $_POST['point'];
    $point_info = $_POST['point_info'];
    $point_geo = $_POST['point_geo'];
    $phone = $_POST['phone'];

    if (!isset($_SESSION['error'])){
//        $name = stripslashes($name);
//        $name = htmlspecialchars($name);
//        $email = stripslashes($email);
//        $email = htmlspecialchars($email);
//        $password = stripslashes($password);
//        $password = htmlspecialchars($password);
//        //удаляем лишние пробелы
//        $name = trim($name);
//        $email = trim($email);
//        $password = trim($password);
        // подключаемся к базе
        //    require("db.php");
        global $pdo;
        // проверка на существование пользователя с таким же email

        // если такого нет, то сохраняем данные
        if (!isset($_SESSION['error'])) {

            $query_statement = $pdo->prepare('UPDATE goods.availability 
            SET
            name = :name, company = :company, code = :code, count = :count, price = :price, description = :description, photo = :photo, 
            goods_group = :goods_group, sold = :sold, en_route = :en_route, purchase = :purchase, 
            price_relevance = :price_relevance, analogs = :analogs, id = :id, date_time = :date_time, 
            point = :point, point_info = :point_info, point_geo = :point_geo, phone = :phone, changed_by = :changed_by WHERE id = :id');
            $query_result = $query_statement->execute(['name' => $name, 'company' => $company,
                'code' => $code, 'count' => $count,
                'price' => $price, 'description' => $description,
                'photo' => $photo, 'goods_group' => $group,
                'sold' => $sold, 'en_route' => $en_route,
                'purchase' => $purchase, 'price_relevance' => $price_relevance,
                'analogs' => $analogs, 'id' => $id,
                'date_time' => $date_time, 'point' => $point,
                'point_info' => $point_info,'point_geo' => $point_geo, 'phone' => $phone,
                'changed_by' => 'id: '. $_SESSION['id_user'] . ', login: '. $_SESSION['login'] . ', ' . date("Y-m-d H:i:s")]);

            // Проверяем, есть ли ошибки
            if ($query_result == 'true') {

                header('Location: index.php');
                die();
            } else {
                $_SESSION['error'][] = 'Ошибка редактирования!';
            }
        }
    }
}




