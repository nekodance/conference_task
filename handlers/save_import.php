<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function saveImport()
{
    require 'vendor/autoload.php';
//  подключение констант размеров (KB,MB,GB,TB)
    require('defines.php');
    // подключение к БД
    //    require("db.php");
    global $pdo;



    if (pathinfo($_FILES['excel_table']['name'], PATHINFO_EXTENSION) != 'xls') {
        $_SESSION['error'][] = 'Неподходящий формат файла!<br>Допустимые форматы: .xls';
    }

    if ($_FILES['excel_table']['size'] > 10 * MB) {
        $_SESSION['error'][] = 'Размер файла больше 10 МБ!';
    }

    if (!isset($_SESSION['error'])) {
//              подготовка путей для сохранения файлов
        $path = $_FILES['excel_table']['name'];
        $file_server_dir = "../files/excel/" . $_FILES['excel_table']['name'];

        $spreadsheet = IOFactory::load($_FILES['excel_table']['tmp_name']);
        $data = $spreadsheet->getActiveSheet()->toArray();

    }

    foreach ($data as $row) {
        if ($row[0] == 'Наименование'){
            continue;
        }

        $excel_name = $row[0];
        $excel_company = $row[1];
        $excel_code = $row[2];
        $excel_count = $row[3];
        $excel_price = $row[4];
        $excel_description = $row[5];
        $excel_photo = $row[6];
        $excel_goods_group = $row[7];
        $excel_sold = $row[8];
        $excel_en_route = $row[9];
        $excel_purchase = $row[10];
//        $excel_price_relevance = date("Y-m-d H:i:s", strtotime($row[11]));
        $excel_price_relevance = null;
        $excel_analogs = $row[12];
        $excel_id = $row[13];
//        $excel_date_time = date("Y-m-d H:i:s", strtotime($row[14]));
        $excel_date_time = null;
        $excel_point = $row[15];
        $excel_point_info = $row[16];
        $excel_point_geo = $row[17];
        $excel_phone = $row[18];

        $query_statement = $pdo->prepare('INSERT INTO goods.availability (name, company, code, count, price, description, photo, goods_group, sold, en_route, purchase, price_relevance, analogs, id, date_time, point, point_info, point_geo, phone, changed_by) 
            VALUES 
            (:excel_name, :excel_company, :excel_code, :excel_count, :excel_price, :excel_description, :excel_photo, 
             :excel_goods_group, :excel_sold, :excel_en_route, :excel_purchase, 
             :excel_price_relevance, :excel_analogs, :excel_id, :excel_date_time, 
             :excel_point, :excel_point_info, :excel_point_geo, :excel_phone, :changed_by)');
        $result = $query_statement->execute(['excel_name' => $excel_name, 'excel_company' => $excel_company,
            'excel_code' => $excel_code, 'excel_count' => $excel_count,
            'excel_price' => $excel_price, 'excel_description' => $excel_description,
            'excel_photo' => $excel_photo, 'excel_goods_group' => $excel_goods_group,
            'excel_sold' => $excel_sold, 'excel_en_route' => $excel_en_route,
            'excel_purchase' => $excel_purchase, 'excel_price_relevance' => $excel_price_relevance,
            'excel_analogs' => $excel_analogs, 'excel_id' => $excel_id,
            'excel_date_time' => $excel_date_time, 'excel_point' => $excel_point,
            'excel_point_info' => $excel_point_info,'excel_point_geo' => $excel_point_geo, 'excel_phone' => $excel_phone,
            'changed_by' => 'id: '. $_SESSION['id_user'] . ', login: '. $_SESSION['login'] . ', ' . date("Y-m-d H:i:s")]);

        if ($result != 'true') {
            $_SESSION['error'][] = 'Ошибка импорта!';
        }
    }
    if (!is_dir("../files/excel")) {
        mkdir("../files/excel", 0777, true);
    }

    move_uploaded_file($_FILES['excel_table']['tmp_name'], $file_server_dir);

    header('Location: index.php');
    die();
}





