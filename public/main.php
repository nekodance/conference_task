<main style="background-color: aliceblue">

    <?php
    session_start();

    //  очистка мусора
    if (isset($_SESSION['title'])) {
        unset($_SESSION['title']);
    }

    include ("../handlers/db.php");
    include("../handlers/main_db_queries.php");

    //  для авторизованного пользователя выводится список заявок
    if (!empty($_SESSION['name']) and !empty($_SESSION['id_user'])) {

        // для администратора выводятся все заявки
        if ($_SESSION['isAdmin'] == '1') {
            mainQueryAdmin();
        }

        // для обычного пользователя выводятся только его собственные заявки
        if ($_SESSION['isAdmin'] == '0') {
            mainQueryUser();
        }
    }
    ?>

</main>
