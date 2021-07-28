<?php
require_once ('../handlers/exceptions_error_handler.php');

try{
    session_start();
} catch (Exception $e){

}

try{
    require('header.php');
    require('main.php');
    require('footer.php');
} catch (Exception $e){
    exit ("<a href='index.php' class='wrong_place' style='position:absolute;
  width:100%;
  top:50%;
  text-align:center;
  text-decoration: underline;
  color: white;
  font-size: 1.3em;
  background-color: cornflowerblue;
  height: 50px;
  padding: 12px;' >Ошибка: что-то пошло не так! Вернуться на главную.</a>");
}

