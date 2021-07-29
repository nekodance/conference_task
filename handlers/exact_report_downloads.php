<?php
if (!isset($query_statement)) {
    if (!isset($_SESSION['title'])) {
        $_SESSION['title'] = $_POST['title'];
    }
    $query_statement = $pdo->prepare('SELECT * FROM reports WHERE title = :title');
    $query_statement->execute(['title' => $_SESSION['title']]);
    $query_row = $query_statement->fetch();
}
//при нажатии на кнопку произойдет скачивание текста доклада
if (isset($_GET['text']) and isset($query_row)) {
    $file = $query_row['text_name'];
    if (file_exists($file)){
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        header('Content-Description: File Transfer');
        header('Content-type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header("Content-Disposition: attachment; filename=$_SESSION[title]_текст.$ext");
        ob_clean();
        flush();
        readfile($file);
        exit;
    } else {
        $_SESSION['error'][] = 'Файл текста доклада не найден!';
    }
}
//при нажатии на кнопку произойдет скачивание презентации доклада
if (isset($_GET['presentation']) and isset($query_row)) {
    $file = $query_row['presentation_name'];
    if (file_exists($file)){
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        header('Content-Description: File Transfer');
        header('Content-type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header("Content-Disposition: attachment; filename=$_SESSION[title]_презентация.$ext");
        ob_clean();
        flush();
        readfile($file);
        exit;
    } else {
        $_SESSION['error'][] = 'Файл презентации доклада не найден!';
    }


}

