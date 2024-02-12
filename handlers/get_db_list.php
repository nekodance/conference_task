<?php
function getStores(){
    //    require("db.php");
    global $pdo;
    $query_statement = $pdo->prepare('SELECT DISTINCT name FROM goods.store');
    $query_statement->execute();
    while ($row = $query_statement->fetch()) {
        if ($_SESSION['user_store_name'] == $row['name']){
            echo '<option value=' . $row['name'] . ' selected>' . $row['name'] . '</option>';
        } else {
            echo '<option value=' . $row['name'] . '>' . $row['name'] . '</option>';
        }

    }

}

function getProductGroups(){
    //    require("db.php");
    global $pdo;
    $query_statement = $pdo->prepare('SELECT DISTINCT goods_group FROM goods.availability');
    $query_statement->execute();
    while($row = $query_statement->fetch()){
        echo  '<option value='.$row['goods_group'].'>'.$row['goods_group'].'</option>';
    }
}

//function getProductCompanies(){
//    require("db.php");
//    $query_statement = $pdo->prepare('SELECT DISTINCT company FROM goods.availability');
//    $query_statement->execute();
//    while($row = $query_statement->fetch()){
//        echo  '<option value='.$row['company'].'>'.$row['company'].'</option>';
//    }
//}