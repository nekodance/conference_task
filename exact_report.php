<?php

require_once ('handlers/exceptions_error_handler.php');

try{

    require('header.php');
    require("handlers/exact_report_downloads.php");

    //неавторизованный пользователь не имеет доступа к просмотру заявок
    if (empty($_SESSION['id_user']) or (empty($_SESSION['title']) and empty($_POST['title']))) {
        exit ("<a href='index.php' class='wrong_place' >Вам тут не место. Вернуться на главную.</a>");
    }

    ?>

    <div class="wrapper text-center" id="wrapper_exact_report">
        <div class="header">
            <h3 class="sign-in">Страница заявки</h3>
            <br>

        </div>
        <form method="GET">
            <div>
                <h1><span>Название доклада</span><br><br> <?= $query_row['title']?></h1>
            </div>
            <br>
            <div>
                <h1><span>Краткая информация о докладчике</span><br><br> <?= $query_row['brief']?></h1>
            </div>
            <br>
            <div>
                <h1><span>Предметная область доклада</span><br><br> <?= $query_row['subject']?></h1>
            </div>
            <br>
            <div>
                <h1><span>Краткое описание доклада</span><br><br> <?= $query_row['summary']?></h1>
            </div>
            <br>
            <h1 style="color: red">
                <?php if (isset($_SESSION['error'])) {
                    foreach ($_SESSION['error'] as $value )
                        echo "$value<br>";
                    unset($_SESSION['error']);
                }?>
            </h1>


            <div>
                <br>
                <input type="submit" name="text" value="Скачать текст">
            </div>
            <div>
                <br>
                <input type="submit" name="presentation" value="Скачать презентацию">
            </div>


        </form>

    </div>

    <?php
    require ('footer.php');?>
<?php
} catch (Exception $e) {
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
?>
