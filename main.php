<main style="background-color: aliceblue">

    <?php

    //  очистка мусора
    if (isset($_SESSION['title'])) {
        unset($_SESSION['title']);
    }
    require("handlers/main_db_queries.php");

    // для администратора выводятся все
    if ($_SESSION['is_admin'] == '1') {
        mainQueryAdmin();
    }

    // для обычного пользователя выводится только его точка
    if ($_SESSION['is_admin'] == '0') {
        mainQueryUser();
    }

    ?>

</main>
