<?php

function mainQueryAdmin()
{
//    require("db.php");
    global $pdo;

    $limit = 50; // количество результатов на странице


    if (isset($_POST['search_request'])){
        if ($_POST['store'] == 'Точки'){
            $_POST['store'] = '';
        }
        if ($_POST['ProductGroups'] == 'Группы товаров'){
            $_POST['ProductGroups'] = '';
        }
        $_POST['search_query'] = trim($_POST['search_query']);

//        if (empty($_POST['search_query']) and !empty($_GET['query'])){
//                $_POST['search_query'] = $_GET['query'];
//
//        }
//        if (empty($_POST['ProductGroups']) and !empty($_GET['group'])){
//            $_POST['ProductGroups'] = $_GET['group'];
//        }
//        if (empty($_POST['store']) and !empty($_GET['store'])){
//            $_POST['store'] = $_GET['store'];
//        }
//        print_r($_POST);

        if ((!empty($_POST['store']) or !empty($_POST['ProductGroups'])) and !empty($_POST['search_query'])){
            $query_statement = $pdo->prepare('SELECT count(*) as total_rows FROM goods.availability WHERE goods_group LIKE :ProductGroups AND point LIKE :store AND (name LIKE :query OR company LIKE :query OR code LIKE :query OR id LIKE :query )');
            $query_statement->execute(['query' => '%'. $_POST['search_query'] . "%", 'store' => '%'. $_POST['store'] . "%", 'ProductGroups' => '%'. $_POST['ProductGroups'] . "%"]);
            $total_product_number = $query_statement->fetch()['total_rows'];
            $page_number = ceil($total_product_number/$limit);
            if (empty($_GET['page']) or $_GET['page'] == 1){
                $offset = 0;
            } else {
                $offset = ($limit * ($_GET['page'] - 1)); // смещение для получения нужной страницы результатов  20 на странице, 4 страницы, 79 отступ
//                print($offset);
            }

            $query_statement = $pdo->prepare('SELECT * FROM goods.availability WHERE goods_group LIKE :ProductGroups AND point LIKE :store AND ( name LIKE :query OR company LIKE :query OR code LIKE :query OR id LIKE :query )');
            $query_statement->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query_statement->bindValue(':offset', $offset, PDO::PARAM_INT);
            $query_statement->execute(['query' => '%'. $_POST['search_query'] . "%", 'store' => '%'. $_POST['store'] . "%", 'ProductGroups' => '%'. $_POST['ProductGroups'] . "%"]);
        } else if ((!empty($_POST['store']) or !empty($_POST['ProductGroups']))) {
            $query_statement = $pdo->prepare('SELECT count(*) as total_rows FROM goods.availability WHERE goods_group LIKE :ProductGroups AND point LIKE :store  ');
            $query_statement->execute(['store' => '%'. $_POST['store'] . "%", 'ProductGroups' => '%'. $_POST['ProductGroups'] . "%"]);
            $total_product_number = $query_statement->fetch()['total_rows'];
            $page_number = ceil($total_product_number/$limit);
            if (empty($_GET['page']) or $_GET['page'] == 1){
                $offset = 0;
            } else {
                $offset = ($limit * ($_GET['page'] - 1)); // смещение для получения нужной страницы результатов  20 на странице, 4 страницы, 79 отступ
//                print($offset);
            }

            $query_statement = $pdo->prepare('SELECT * FROM goods.availability WHERE goods_group LIKE :ProductGroups AND point LIKE :store  ');
            $query_statement->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query_statement->bindValue(':offset', $offset, PDO::PARAM_INT);
            $query_statement->execute(['store' => '%'. $_POST['store'] . "%", 'ProductGroups' => '%'. $_POST['ProductGroups'] . "%"]);
        } else {
            $query_statement = $pdo->prepare('SELECT count(*) as total_rows FROM goods.availability WHERE name LIKE :query OR company LIKE :query OR code LIKE :query OR id LIKE :query');
            $query_statement->execute(['query' => '%'. $_POST['search_query'] . "%"]);
            $total_product_number = $query_statement->fetch()['total_rows'];
            $page_number = ceil($total_product_number/$limit);
            if (empty($_GET['page']) or $_GET['page'] == 1){
                $offset = 0;
            } else {
                $offset = ($limit * ($_GET['page'] - 1)); // смещение для получения нужной страницы результатов  20 на странице, 4 страницы, 79 отступ
//                print($offset);
            }


            $query_statement = $pdo->prepare('SELECT * FROM goods.availability WHERE name LIKE :query OR company LIKE :query OR code LIKE :query OR id LIKE :query');
            $query_statement->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query_statement->bindValue(':offset', $offset, PDO::PARAM_INT);
            $query_statement->execute(['query' => '%'. $_POST['search_query'] . "%"]);
        }

        $num_rows = $query_statement->rowCount();
        // установка счётчика
        $i = 0;
//        print_r('Количество записей:'.$num_rows);
    } else{
        $query_statement = $pdo->prepare('SELECT count(*) as total_rows FROM goods.availability');
        $query_statement->execute();
        $total_product_number = $query_statement->fetch()['total_rows'];
        $page_number = ceil($total_product_number/$limit);
        if (empty($_GET['page']) or $_GET['page'] == 1){
            $offset = 0;
        } else {
            $offset = ($limit * ($_GET['page'] - 1)); // смещение для получения нужной страницы результатов  20 на странице, 4 страницы, 79 отступ
//            print($offset);
        }

        $query_statement = $pdo->prepare('SELECT * FROM goods.availability LIMIT :limit OFFSET :offset');
        $query_statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $query_statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $query_statement->execute();
        $num_rows = $query_statement->rowCount();

//        print_r('Количество записей:'.$num_rows);
//        print_r($_GET);
    }




?>

    <br>
    <form name="search" id="main_search" method="post">
        <div class="container" style="width: 300px">
                        <input class="user-input form-control" type="search" name="search_query" placeholder="Поиск...">
                        <select class="select form-control" name="store" id="store">
                            <option value="Точки">Точки</option>
                            <?php
                            getStores();
                            ?>
                        </select>
                        <select class="select form-control" name="ProductGroups" id="ProductGroups">
                            <option value="Группы товаров">Группы товаров</option>
                            <?php
                            getProductGroups();
                            ?>
                        </select>
                        <!--        <select class="select form-control w-100" name="ProductCompanies" id="ProductCompanies">-->
                        <!--            <option value="Фирмы">Фирмы</option>-->
                        <!--            --><?php
                        //            getProductCompanies();
                        //            ?>
                <div class="row">
                    <div class="col-sm">
                    </div>
                    <div class="col-sm">
                        <button class="btn btn-primary mx-auto d-flex" name="search_request" type="submit">Найти</button>
                    </div>
                    <div class="col-sm">
                    </div>
                </div>
        </div>
    </form>

    <br>
    <div>
        <nav aria-label="Page navigation">
            <ul class="pagination d-flex justify-content-center flex-wrap">
                <?php for ($i = 1; $i <= $page_number; $i++): ?>
<!--                    --><?php //if (!empty($_GET['page']) and $_GET['page'] == $i) {?><!-- <li class="page-item active"><a class="page-link" href="index.php?page=--><?php //echo $i?><!--&query=--><?php //echo $_POST['search_query']?><!--&store=--><?php //echo $_POST['store']?><!--&group=--><?php //echo $_POST['ProductGroups']?><!--"> --><?php //echo $i?><!-- </a></li>--><?php //;} else { ?><!-- <li class="page-item"><a type="submit" class="page-link" href="index.php?page=--><?php //echo $i?><!--"> --><?php //echo $i?><!-- </a></li>--><?php //;}?>
                    <?php if (!empty($_GET['page']) and $_GET['page'] == $i) {?> <li class="page-item active"><a class="page-link" href="index.php?page=<?php echo $i?>"> <?php echo $i?> </a></li><?php ;} else { ?> <li class="page-item"><a type="submit" class="page-link" href="index.php?page=<?php echo $i?>"> <?php echo $i?> </a></li><?php ;}?>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

<?php


    echo '<form method="POST" id="my_form" action="../product.php">';
    echo '<br>';
    echo '<div class="table-responsive">';
    echo '<table class="table table-hover row-clickable " ><tr><th>Наименование</th><th>Фирма</th><th>Код</th><th>Количество</th><th>Цена</th><th>Описание</th><th>Фото</th><th>Группа</th><th>Продано</th><th>В пути</th><th>Закуп</th><th>Актуальность цены</th><th>Аналоги</th><th>Идентификатор</th><th>Дата-время</th><th>Точка</th><th>Инфо точки</th><th>Гео точки</th><th>Телефон</th><th>Последнее изменение</th></tr>';
    while ($row = $query_statement->fetch()){
        // установка счётчика
        $i = 0;
        //счётчик увеличивается на 1 с каждым выводом записи
        $i++;
        //условие для вывода каждой третьей записи
        if($i%3 == 0){
            echo '<tr><td><button type="submit" class="btn btn-primary table_button" data-toggle="modal" data-target="#pop_row_modification" name="title" value="';

            echo "$row[id]";

            echo '">'.$row['name'].'</button></td><td>'.$row['company'].'</td><td>'.$row['code'].'</td><td>'.$row['count'].'</td><td>'.$row['price'].'</td><td>'.$row['description'].'</td><td>'.$row['photo'].'</td><td>'.$row['goods_group'].'</td><td>'.$row['sold'].'</td><td>'.$row['en_route'].'</td><td>'.$row['purchase'].'</td><td>'.$row['price_relevance'].'</td><td>'.$row['analogs'].'</td><td>'.$row['id'].'</td><td>'.$row['date_time'].'</td><td>'.$row['point'].'</td><td>'.$row['point_info'].'</td><td>'.$row['point_geo'].'</td><td>'.$row['phone'].'</td><td>'.$row['changed_by'].'</td></tr>';
            // условие для вывода первой и второй записи
        } elseif($i%3 != 0) {
            echo '<tr><td><button type="submit" class="btn btn-primary table_button" data-toggle="modal" data-target="#pop_row_modification" name="title" value="';
            echo "$row[id]";

            echo '">'.$row['name'].'</button></td><td>'.$row['company'].'</td><td>'.$row['code'].'</td><td>'.$row['count'].'</td><td>'.$row['price'].'</td><td>'.$row['description'].'</td><td>'.$row['photo'].'</td><td>'.$row['goods_group'].'</td><td>'.$row['sold'].'</td><td>'.$row['en_route'].'</td><td>'.$row['purchase'].'</td><td>'.$row['price_relevance'].'</td><td>'.$row['analogs'].'</td><td>'.$row['id'].'</td><td>'.$row['date_time'].'</td><td>'.$row['point'].'</td><td>'.$row['point_info'].'</td><td>'.$row['point_geo'].'</td><td>'.$row['phone'].'</td><td>'.$row['changed_by'].'</td><tr>';
            // условие для самой последней записи
        } elseif ($i == $num_rows) {
            echo '<tr><td><button type="submit" class="btn btn-primary table_button" data-toggle="modal" data-target="#pop_row_modification" name="title" value="';
            echo "$row[id]";
            echo '">'.$row['name'].'</button></td><td>'.$row['company'].'</td><td>'.$row['code'].'</td><td>'.$row['count'].'</td><td>'.$row['price'].'</td><td>'.$row['description'].'</td><td>'.$row['photo'].'</td><td>'.$row['goods_group'].'</td><td>'.$row['sold'].'</td><td>'.$row['en_route'].'</td><td>'.$row['purchase'].'</td><td>'.$row['price_relevance'].'</td><td>'.$row['analogs'].'</td><td>'.$row['id'].'</td><td>'.$row['date_time'].'</td><td>'.$row['point'].'</td><td>'.$row['point_info'].'</td><td>'.$row['point_geo'].'</td><td>'.$row['phone'].'</td><td>'.$row['changed_by'].'</td></tr>';
        }
    }
    echo '</table>';
    echo '</div>';
    echo '</div>';
    echo '</form>';
    ?>
    <br>
    <div>
        <nav aria-label="Page navigation">
            <ul class="pagination d-flex justify-content-center flex-wrap">
                <?php for ($i = 1; $i <= $page_number; $i++): ?>
                    <?php if (!empty($_GET['page']) and $_GET['page'] == $i) {?> <li class="page-item active"><a class="page-link" href="index.php?page=<?php echo $i?>"> <?php echo $i?> </a></li><?php ;} else { ?> <li class="page-item"><a type="submit" class="page-link" href="index.php?page=<?php echo $i?>"> <?php echo $i?> </a></li><?php ;}?>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
    <br>
<?php

}

function mainQueryUser()
{
    global $pdo;
    $limit = 50; // количество результатов на странице

    if (isset($_POST['search_request'])){
        if ($_POST['ProductGroups'] == 'Группы товаров'){
            $_POST['ProductGroups'] = '';
        }
        $_POST['search_query'] = trim($_POST['search_query']);
        if (!empty($_POST['ProductGroups']) and !empty($_POST['search_query'])){
            $query_statement = $pdo->prepare('SELECT count(*) as total_rows FROM goods.availability WHERE goods_group LIKE :ProductGroups AND point LIKE :store AND ( name LIKE :query OR company LIKE :query OR code LIKE :query OR id LIKE :query )');
            $query_statement->execute(['query' => '%'. $_POST['search_query'] . "%", 'store' => '%'. trim($_SESSION['user_store_name']) . "%", 'ProductGroups' => '%'. $_POST['ProductGroups'] . "%"]);
            $total_product_number = $query_statement->fetch()['total_rows'];
            $page_number = ceil($total_product_number/$limit);
            if (empty($_GET['page']) or $_GET['page'] == 1){
                $offset = 0;
            } else {
                $offset = ($limit * ($_GET['page'] - 1)); // смещение для получения нужной страницы результатов  20 на странице, 4 страницы, 79 отступ
//                print($offset);
            }

            $query_statement = $pdo->prepare('SELECT * FROM goods.availability WHERE goods_group LIKE :ProductGroups AND point LIKE :store AND ( name LIKE :query OR company LIKE :query OR code LIKE :query OR id LIKE :query ) LIMIT :limit OFFSET :offset');
            $query_statement->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query_statement->bindValue(':offset', $offset, PDO::PARAM_INT);
            $query_statement->bindValue(':store', '%' . trim($_SESSION['user_store_name']) . '%');
            $query_statement->bindValue(':query', '%'. $_POST['search_query'] . "%");
            $query_statement->bindValue(':ProductGroups', '%'. $_POST['ProductGroups'] . "%");
            $query_statement->execute();

        } else if (!empty($_POST['ProductGroups'])) {
            $query_statement = $pdo->prepare('SELECT count(*) as total_rows FROM goods.availability WHERE goods_group LIKE :ProductGroups AND point LIKE :store');
            $query_statement->execute(['store' => '%'. trim($_SESSION['user_store_name']) . "%", 'ProductGroups' => '%'. $_POST['ProductGroups'] . "%"]);
            $total_product_number = $query_statement->fetch()['total_rows'];
            $page_number = ceil($total_product_number/$limit);
            if (empty($_GET['page']) or $_GET['page'] == 1){
                $offset = 0;
            } else {
                $offset = ($limit * ($_GET['page'] - 1)); // смещение для получения нужной страницы результатов  20 на странице, 4 страницы, 79 отступ
//                print($offset);
            }



            $query_statement = $pdo->prepare('SELECT * FROM goods.availability WHERE goods_group LIKE :ProductGroups AND point LIKE :store LIMIT :limit OFFSET :offset');
            $query_statement->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query_statement->bindValue(':offset', $offset, PDO::PARAM_INT);
            $query_statement->bindValue(':store', '%' . trim($_SESSION['user_store_name']) . '%');
            $query_statement->bindValue(':ProductGroups', '%'. $_POST['ProductGroups'] . "%");
            $query_statement->execute();
        } else {
            $query_statement = $pdo->prepare('SELECT count(*) as total_rows FROM goods.availability WHERE point LIKE :store AND ( name LIKE :query OR company LIKE :query OR code LIKE :query OR id LIKE :query)');
            $query_statement->execute(['query' => '%'. $_POST['search_query'] . "%", 'store'=> '%' . trim($_SESSION['user_store_name']) . '%']);
            $total_product_number = $query_statement->fetch()['total_rows'];
            $page_number = ceil($total_product_number/$limit);
            if (empty($_GET['page']) or $_GET['page'] == 1){
                $offset = 0;
            } else {
                $offset = ($limit * ($_GET['page'] - 1)); // смещение для получения нужной страницы результатов  20 на странице, 4 страницы, 79 отступ
//                print($offset);
            }


            $query_statement = $pdo->prepare('SELECT * FROM goods.availability WHERE point LIKE :store AND ( name LIKE :query OR company LIKE :query OR code LIKE :query OR id LIKE :query) LIMIT :limit OFFSET :offset');
            $query_statement->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query_statement->bindValue(':offset', $offset, PDO::PARAM_INT);
            $query_statement->bindValue(':store', '%' . trim($_SESSION['user_store_name']) . '%');
            $query_statement->bindValue(':query', '%'. $_POST['search_query'] . "%");
            $query_statement->execute();
        }
        $num_rows = $query_statement->rowCount();
        // установка счётчика
        $i = 0;
//        print_r('Количество записей:'.$num_rows);
    } else {
        $query_statement = $pdo->prepare('SELECT count(*) as total_rows FROM goods.availability WHERE point LIKE :store');
        $query_statement->bindValue(':store', '%' . trim($_SESSION['user_store_name']) . '%');
        $query_statement->execute();
        $total_product_number = $query_statement->fetch()['total_rows'];
        $page_number = ceil($total_product_number/$limit);
        if (empty($_GET['page']) or $_GET['page'] == 1){
            $offset = 0;
        } else {
            $offset = ($limit * ($_GET['page'] - 1)); // смещение для получения нужной страницы результатов  20 на странице, 4 страницы, 79 отступ
//                print($offset);
        }


        $query_statement = $pdo->prepare('SELECT * FROM goods.availability WHERE point LIKE :store LIMIT :limit OFFSET :offset');
        $query_statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $query_statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $query_statement->bindValue(':store', '%' . trim($_SESSION['user_store_name']) . '%');
        $query_statement->execute();
        $num_rows = $query_statement->rowCount();
        // установка счётчика
        $i = 0;
//      print_r('Количество записей:'.$num_rows);
    }




    ?>




    <br>
    <form name="search" id="main_search" method="post">
        <div class="container" style="width: 300px;">
        <input class="user-input form-control" type="search" name="search_query" placeholder="Поиск...">
        <select class="select form-control" name="ProductGroups" id="ProductGroups">
            <option value="Группы товаров">Группы товаров</option>
            <?php
            getProductGroups();
            ?>
        </select>
        <div class="row">
            <div class="col-sm">
            </div>
            <div class="col-sm">
                <button class="btn btn-primary mx-auto d-flex" name="search_request" type="submit">Найти</button>
            </div>
            <div class="col-sm">
            </div>
        </div>
        </div>
    </form>
    <br>
        <div>
            <nav aria-label="Page navigation">
                <ul class="pagination d-flex justify-content-center flex-wrap">
                    <?php for ($i = 1; $i <= $page_number; $i++): ?>
                        <?php if (!empty($_GET['page']) and $_GET['page'] == $i) {?> <li class="page-item active"><a class="page-link" href="index.php?page=<?php echo $i?>"> <?php echo $i?> </a></li><?php ;} else { ?> <li class="page-item"><a type="submit" class="page-link" href="index.php?page=<?php echo $i?>"> <?php echo $i?> </a></li><?php ;}?>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    <?php


    echo '<form method="POST" id="my_form" action="../product.php">';
    echo '<br>';
    echo '<div class="table-responsive">';
    echo '<table class="table table-hover row-clickable" ><tr><th>Наименование</th><th>Фирма</th><th>Код</th><th>Количество</th><th>Цена</th><th>Описание</th><th>Фото</th><th>Группа</th><th>Продано</th><th>В пути</th><th>Закуп</th><th>Актуальность цены</th><th>Аналоги</th><th>Идентификатор</th><th>Дата-время</th><th>Точка</th><th>Инфо точки</th><th>Гео точки</th><th>Телефон</th></tr>';
    while ($row = $query_statement->fetch()){
        // установка счётчика
        $i = 0;
        //счётчик увеличивается на 1 с каждым выводом записи
        $i++;
        //условие для вывода каждой третьей записи
        if($i%3 == 0){
            echo '<tr><td><button class="table_button" type="submit" name="title" value="';

            echo "$row[id]";

            echo '">'.$row['name'].'</button></td><td>'.$row['company'].'</td><td>'.$row['code'].'</td><td>'.$row['count'].'</td><td>'.$row['price'].'</td><td>'.$row['description'].'</td><td>'.$row['photo'].'</td><td>'.$row['goods_group'].'</td><td>'.$row['sold'].'</td><td>'.$row['en_route'].'</td><td>'.$row['purchase'].'</td><td>'.$row['price_relevance'].'</td><td>'.$row['analogs'].'</td><td>'.$row['id'].'</td><td>'.$row['date_time'].'</td><td>'.$row['point'].'</td><td>'.$row['point_info'].'</td><td>'.$row['point_geo'].'</td><td>'.$row['phone'].'</td></tr>';
            // условие для вывода первой и второй записи
        } elseif($i%3 != 0) {
            echo '<tr><td><button class="table_button" type="submit" name="title" value="';
            echo "$row[id]";

            echo '">'.$row['name'].'</button></td><td>'.$row['company'].'</td><td>'.$row['code'].'</td><td>'.$row['count'].'</td><td>'.$row['price'].'</td><td>'.$row['description'].'</td><td>'.$row['photo'].'</td><td>'.$row['goods_group'].'</td><td>'.$row['sold'].'</td><td>'.$row['en_route'].'</td><td>'.$row['purchase'].'</td><td>'.$row['price_relevance'].'</td><td>'.$row['analogs'].'</td><td>'.$row['id'].'</td><td>'.$row['date_time'].'</td><td>'.$row['point'].'</td><td>'.$row['point_info'].'</td><td>'.$row['point_geo'].'</td><td>'.$row['phone'].'</td></tr>';
            // условие для самой последней записи
        } elseif ($i == $num_rows) {
            echo '<tr><td><button class="table_button" type="submit" name="title" value="';
            echo "$row[id]";
            echo '">'.$row['name'].'</button></td><td>'.$row['company'].'</td><td>'.$row['code'].'</td><td>'.$row['count'].'</td><td>'.$row['price'].'</td><td>'.$row['description'].'</td><td>'.$row['photo'].'</td><td>'.$row['goods_group'].'</td><td>'.$row['sold'].'</td><td>'.$row['en_route'].'</td><td>'.$row['purchase'].'</td><td>'.$row['price_relevance'].'</td><td>'.$row['analogs'].'</td><td>'.$row['id'].'</td><td>'.$row['date_time'].'</td><td>'.$row['point'].'</td><td>'.$row['point_info'].'</td><td>'.$row['point_geo'].'</td><td>'.$row['phone'].'</td></tr>';
        }
    }
    echo '</table>';
    echo '</div>';
    echo '</form>';

    ?>
    <div>
            <nav aria-label="Page navigation">
                <ul class="pagination d-flex justify-content-center flex-wrap">
                    <?php for ($i = 1; $i <= $page_number; $i++): ?>
                        <?php if (!empty($_GET['page']) and $_GET['page'] == $i) {?> <li class="page-item active"><a class="page-link" href="index.php?page=<?php echo $i?>"> <?php echo $i?> </a></li><?php ;} else { ?> <li class="page-item"><a type="submit" class="page-link" href="index.php?page=<?php echo $i?>"> <?php echo $i?> </a></li><?php ;}?>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
<?php
}
?>





