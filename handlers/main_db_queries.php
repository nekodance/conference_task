<?php

function mainQueryAdmin()
{
    require("db.php");
    $query_statement = $pdo->prepare('SELECT users.email, users.name, reports.* FROM reports, users WHERE users.id_user = reports.id_user');
    $query_statement->execute();
    $num_rows = $query_statement->rowCount();
    // установка счётчика
    $i = 0;
    echo '<form method="POST" id="my_form" action="exact_report.php">';
    echo '<br>';
    echo '<table class="table table-hover row-clickable" ><tr><th>Название доклада</th><th>Имя и e-mail</th><th>Тематика</th><th>Краткое описание</th></tr>';
    while ($row = $query_statement->fetch()){
        //счётчик увеличивается на 1 с каждым выводом записи
        $i++;
        //условие для вывода каждой третьей записи
        if($i%3 == 0){
            echo '<td><button class="table_button" type="submit" name="title" value="';

            echo "$row[title]";

            echo '">'.$row['title'].'</button></td><td>'.$row['name'].', '.$row['email'].'</td><td>'.$row['subject'].'</td><td>'.$row['summary'].'</tr><tr>';
            // условие для вывода первой и второй записи
        } elseif($i%3 != 0) {
            echo '<td><button class="table_button" type="submit" name="title" value="';
            echo "$row[title]";

            echo '">'.$row['title'].'</button></td><td>'.$row['name'].', '.$row['email'].'</td><td>'.$row['subject'].'</td><td>'.$row['summary'].'</td><tr>';
            // условие для самой последней записи
        } elseif ($i == $num_rows) {
            echo '<td><button class="table_button" type="submit" name="title" value="';
            echo "$row[title]";
            echo '">'.$row['title'].'</button></td><td>'.$row['name'].', '.$row['email'].'</td><td>'.$row['subject'].'</td><td>'.$row['summary'].'</td></tr>';
        }
    }
    echo '</table>';
}

function mainQueryUser()
{
    require("db.php");
    echo "<div class='text-center'  ><a href='report_creation.php' class='btn' id='to_report_creation'>Сформировать заявку</a></div>";
    $query_statement = $pdo->prepare('SELECT title,subject,summary FROM reports WHERE id_user = :id_user');
    $query_statement->execute(['id_user'=>$_SESSION['id_user']]);
    $num_rows = $query_statement->rowCount();

    // установка счетчика
    $i = 0;
    echo '<form method="POST" id="my_form" action="exact_report.php">';
    echo '<table class="table table-hover row-clickable" ><tr><th>Название доклада</th><th>Имя и e-mail</th><th>Тематика</th><th>Краткое описание</th></tr>';
    while ($row = $query_statement->fetch()){
        // счётчик увеличивается на 1 с каждым выводом записи
        $i++;
        //условие для вывода каждой третьей записи
        if($i%3 == 0){
            echo '<td><button class="table_button" type="submit" name="title" value="';
            echo "$row[title]";
            echo '">'.$row['title'].'</button></td><td>'.$_SESSION['name'].', '.$_SESSION['email'].'</td><td>'.$row['subject'].'</td><td>'.$row['summary'].'</tr><tr>';
            // условие для вывода первой и второй записи
        } elseif($i%3 != 0) {
            echo '<td><button class="table_button" type="submit" name="title" value="';
            echo "$row[title]";
            echo '">'.$row['title'].'</button></td><td>'.$_SESSION['name'].', '.$_SESSION['email'].'</td><td>'.$row['subject'].'</td><td>'.$row['summary'].'</td><tr>';
            // условие для самой последней записи
        } elseif ($i == $num_rows) {
            echo '<td><button class="table_button" type="submit" name="title" value="';
            echo "$row[title]";
            echo '">'.$row['title'].'</button></td><td>'.$_SESSION['name'].', '.$_SESSION['email'].'</td><td>'.$row['subject'].'</td><td>'.$row['summary'].'</td></tr>';
        }
    }
    echo '</table>';
}



