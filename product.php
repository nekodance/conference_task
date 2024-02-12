<?php
session_start();
require_once ('handlers/exceptions_error_handler.php');
require("handlers/db.php");

require ('handlers/bd_modification.php');
if (isset($_POST['submit_bd_modification'])) {
    productModification();
}

if (isset($_POST['to_index'])){
    header('Location: index.php');
    die();
}


try{
    require('header.php');
    require("handlers/product_page.php");

    //неавторизованный пользователь не имеет доступа к просмотру данных БД
    if (empty($_SESSION['id_user']) or (empty($_SESSION['id']) and empty($_POST['id']))) {
        exit ("<a href='index.php' class='wrong_place' >Вам тут не место. Вернуться на главную.</a>");
    }
    ?>
    <!-- Модальное окно -->
<div class="wrapper text-center" id="wrapper_exact_report">
    <div class="header">

        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Заголовок модального окна -->
                <div class="modal-header update_bd">
                    <div class="header">
                        <h3 class="sign-in">Редактировать наличие <? echo $_SESSION['id'];?></h3>
                    </div>
                </div>
                <form method="post" enctype="multipart/form-data">
                <!-- Тело модального окна -->
                <div class="modal-body">

                    <div class="clear"></div>

                        <div>
                            <label>Наименование товара</label>
                            <input class="user-input p-1" type="text" name="name" id="name" placeholder="Наименование..."
                                   value="<?php if(isset($query_row['name'])){echo $query_row['name'];}  ?>">
                        </div>
                        <br>
                        <div>
                            <label>Фирма</label>
                            <input class="user-input p-1" type="text" name="company" id="company" placeholder="Фирма..."
                                   value="<?php if(isset($query_row['company'])){echo $query_row['company'];}  ?>">
                        </div>
                        <br>
                        <div>
                            <label>Код</label>
                            <input class="user-input p-1" type="text" name="code" id="code" placeholder="Код..."
                                   value="<?php if(isset($query_row['code'])){echo $query_row['code'];}  ?>">
                        </div>
                        <br>
                        <div>
                            <label>Количество</label>
                            <input class="user-input p-1" type="text" name="count" id="count" placeholder="Количество..."
                                   value="<?php if(isset($query_row['count'])){echo $query_row['count'];}  ?>">
                        </div>
                        <br>
                        <div>
                            <label>Цена</label>
                            <input class="user-input p-1" type="text" name="price" id="price" placeholder="Цена..."
                                   value="<?php if(isset($query_row['price'])){echo $query_row['price'];}  ?>">
                        </div>
                        <br>
                        <div>
                            <label style="margin-bottom:5px">Описание</label>
                            <textarea class="brief p-1" type="textarea"
                                      name="description" id="description"
                                      placeholder="Описание..."
                                      style="width: 100%" rows="6"><?php if(isset($query_row['description'])){echo htmlspecialchars($query_row['description']);}?>
                            </textarea>
                        </div>
                        <br>
                        <div>
                            <label>Фото</label>
                            <input class="user-input p-1" type="text" name="photo" id="photo" placeholder="Фото..."
                                   value="<?php if(isset($query_row['photo'])){echo htmlspecialchars($query_row['photo']);}  ?>">
                        </div>
                        <br>
                        <div>
                            <label>Группа</label>
                            <input class="user-input p-1" type="text" name="group" id="group" placeholder="Группа..."
                                   value="<?php if(isset($query_row['goods_group'])){echo $query_row['goods_group'];}  ?>">
                        </div>
                        <br>
                        <div>
                            <label>Продано</label>
                            <input class="user-input p-1" type="text" name="sold" id="sold" placeholder="Продано..."
                                   value="<?php if(isset($query_row['sold'])){echo $query_row['sold'];}  ?>">
                        </div>
                        <br>
                        <div>
                            <label>В пути</label>
                            <input class="user-input p-1" type="text" name="en_route" id="en_route" placeholder="В пути..."
                                   value="<?php if(isset($query_row['en_route'])){echo $query_row['en_route'];}  ?>">
                        </div>
                        <br>
                        <div>
                            <label>Закуп</label>
                            <input class="user-input p-1" type="text" name="purchase" id="purchase" placeholder="Закуп..."
                                   value="<?php if(isset($query_row['purchase'])){echo $query_row['purchase'];}  ?>">
                        </div>
                        <br>
                        <div>
                            <label>Актуальность цены</label>
                            <input class="user-input p-1" type="datetime-local" name="price_relevance" id="price_relevance" placeholder="Актуальность цены..."
                                   value="<?php if(isset($query_row['price_relevance'])){echo $query_row['price_relevance'];}  ?>">
                        </div>
                        <br>
                        <div>
                            <label>Аналоги</label>
                            <input class="user-input p-1" type="text" name="analogs" id="analogs" placeholder="Аналоги..."
                                   value="<?php if(isset($query_row['analogs'])){echo $query_row['analogs'];}  ?>">
                        </div>
                        <br>
                        <div>
                            <label>Идентификатор</label>
                            <input class="user-input p-1" type="text" name="id" id="id" placeholder="Идентификатор..."
                                   value="<?php if(isset($query_row['id'])){echo $query_row['id'];}  ?>">
                        </div>
                        <br>
                        <div>
                            <label>Дата-время</label>
                            <input class="user-input p-1" type="datetime-local" id="date_time" name="date_time" placeholder="Дата-время..."
                                   value="<?php if(isset($query_row['date_time'])){echo $query_row['date_time'];}  ?>">

                        </div>
                        <br>
                        <div>
                            <label>Точки</label>
                            <select class="select form-control" name="point" id="point">
                                <?php
                                getStores();
                                ?>
                            </select>
                        </div>
                        <br>
                        <div>
                            <label>Информация точки</label>
                            <input class="user-input p-1" type="text" name="point_info" id="point_info" placeholder="Инфо точки..."
                                   value="<?php if(isset($query_row['point_info'])){echo htmlspecialchars($query_row['point_info']);}  ?>">
                        </div>
                        <br>
                        <div>
                            <label>Геолокация точки</label>
                            <input class="user-input p-1" type="text" name="point_geo" id="point_geo" placeholder="Гео точки..."
                                   value="<?php if(isset($query_row['point_geo'])){echo $query_row['point_geo'];}  ?>">
                        </div>
                        <br>
                        <div>
                            <label>Телефон</label>
                            <input class="user-input p-1" type="text" name="phone" id="phone" placeholder="Телефон..."
                                   value="<?php if(isset($query_row['phone'])){echo $query_row['phone'];}  ?>">
                        </div>
                        <br>
                        <?php if (isset($_SESSION['error'])) { ?>
                            <h1 style="color: red">
                            <?php
                            echo '<br>';
                            foreach ($_SESSION['error'] as $value )
                                echo "$value<br>" ;
                            unset($_SESSION['error']);
                        ?>
                            </h1>
                            <?php }?>
                        <br>
                        <div>
                            <input name="submit_bd_modification" type="submit" value="Редактировать" />
                        </div>
                        <div class="clear"></div>

                </div>

                <!-- Футер модального окна -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" data-dismiss="modal" name="to_index">На главную</button>
                </div>
                </form>
            </div>
        </div>

    </div>
</div>


    <?php
    require ('footer.php');?>
<?php
} catch (Exception $e) {
    print($e);
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
