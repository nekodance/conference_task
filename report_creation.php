<?php
require_once ('handlers/exceptions_error_handler.php');

try{
    require ('header.php');

    //Неавторизованный пользователь не имеет доступа
    if (empty($_SESSION['id_user'])) {
        exit ("<a href='index.php' class='wrong_place' >Вам тут не место. Вернуться на главную.</a>");
    }
    //удаление мусора
    if (isset($_SESSION['title'])) {
        unset($_SESSION['title']);
    }

    ?>

    <div class="wrapper" id="wrapper_rep_create">
        <div class="header">
            <h3 class="sign-in">Создание заявки</h3>
            <br>
        </div>
        <div class="clear"></div>
        <form method="post" enctype="multipart/form-data">
            <div>
                <input class="user-input" type="text" name="title" id="title" placeholder="Название доклада..."
                       value="<?php if(isset($_POST['title'])){echo $_POST['title'];}  ?>" required>
            </div>
            <br>
            <div>
                    <textarea class="brief" type="textarea"
                              name="reporter_brief" id="reporter_brief"
                              placeholder="Информация о докладчике..."
                              style="width: 100%" rows="6" required><?php if(isset($_POST['reporter_brief'])){echo $_POST['reporter_brief'];}?></textarea>
            </div>
            <br>
            <div class="select">
                <label style="margin-bottom: 5px; margin-right: 10px"  for="report_subject">Предметная область доклада</label>

                <select name="report_subject" id="report_subject">
                    <?php
                    //                    опции для селекта берутся из конфигурационного файла
                    $options = parse_ini_file("../configs/select_options.ini");

                    foreach ($options as $option) {
                        echo "<option value='$option'>$option</option>";
                    }
                    ?>
                </select>
            </div>
            <br>
            <div>
                    <textarea class="brief" type="textarea" name="report_brief" id="report_brief"
                              placeholder="Краткое описание доклада..." style="width: 100%"
                              rows="6" required><?php if(isset($_POST['report_brief'])){
                            echo $_POST['report_brief'];
                        }?></textarea>
            </div>
            <br>
            <div class="file_upload">
                <label  for="text_file"> Загрузите текст доклада</label>
                <input type="file" name="text_file" id="text_file" title="Допустимые форматы: .doc, .docx, .pdf" required>
            </div>
            <br>
            <div class="file_upload">
                <label  for="text_file"> Загрузите презентацию доклада</label>
                <input type="file" name="presentation_file" id="presentation_file" title="Допустимые форматы: .ppt, .pptx, .pdf" required>
            </div>
            <h1 style="color: red">
                <?php if (isset($_SESSION['error'])) {
                    echo '<br>';
                    foreach ($_SESSION['error'] as $value )
                        echo "$value<br>" ;
                    unset($_SESSION['error']);
                }?>
            </h1>
            <br>
            <div>
                <input name="submit_report_creation" type="submit" value="Создать" />
            </div>
            <div class="clear"></div>
        </form>
    </div>

    <?php
    require ('footer.php');?>

<?php
} catch (Exception $e) {
//    print($e);
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





