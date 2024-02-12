<?php
function saveReport()
{
//  подключение констант размеров (KB,MB,GB,TB)
    require('defines.php');
    // подключение к БД
    //    require("db.php");
    global $pdo;

    if (isset($_POST['title'])) {
        $title = $_POST['title'];
        if ($title == '') {
            unset($name);
        }
    }
    if (isset($_POST['reporter_brief'])) {
        $reporter_brief = $_POST['reporter_brief'];
        if ($reporter_brief == '') {
            unset($reporter_brief);
        }
    }
    if (isset($_POST['report_subject'])) {
        $report_subject = $_POST['report_subject'];
        if ($report_subject =='') {
            unset($report_subject);
        }
    }
    if (isset($_POST['report_brief'])) {
        $report_brief=$_POST['report_brief'];
        if ($report_brief =='') {
            unset($report_brief);
        }
    }

//    проверка, что все поля были заполнены
    if (empty($title) or empty($reporter_brief) or empty($report_subject) or empty($report_brief)
        or !file_exists($_FILES['text_file']['tmp_name']) or !file_exists($_FILES['presentation_file']['tmp_name'])){
        $_SESSION['error'][] = 'Вы ввели не всю информацию!';
    } else {

        if (pathinfo($_FILES['text_file']['name'], PATHINFO_EXTENSION) != 'doc' and
            pathinfo($_FILES['text_file']['name'], PATHINFO_EXTENSION) != 'docx' and
            pathinfo($_FILES['text_file']['name'], PATHINFO_EXTENSION) != 'pdf'
        ){
            $_SESSION['error'][] = 'Неподходящий формат текстового файла!<br>Допустимые форматы: .doc, .docx, .pdf';
        }

        if ($_FILES['text_file']['size'] > 10*MB){
            $_SESSION['error'][] = 'Размер текстового файла больше 10 МБ!';
        }

        if (pathinfo($_FILES['presentation_file']['name'], PATHINFO_EXTENSION) != 'ppt' and
            pathinfo($_FILES['presentation_file']['name'], PATHINFO_EXTENSION) != 'pptx' and
            pathinfo($_FILES['presentation_file']['name'], PATHINFO_EXTENSION) != 'pdf'
        ){
            $_SESSION['error'][] = 'Неподходящий формат файла презентации!<br>Допустимые форматы: .ppt, .pptx, .pdf';
        }

        if ($_FILES['presentation_file']['size'] > 30*MB){
            $_SESSION['error'][] = 'Размер файла презентации больше 30 МБ!';
        }

        if (!isset($_SESSION['error'])) {

            $query_statement = $pdo->prepare('SELECT id_report FROM reports WHERE title=:title');
            $query_statement->execute(['title' => $title]);
            $query_row = $query_statement->fetch();

            if (!empty($query_row['id_report'])) {
                $_SESSION['error'][] = 'Заявка с таким названием уже существует!';
            }

            if (!isset($_SESSION['error'])) {
                $title = stripslashes($title);
                $title = htmlspecialchars($title);
                $reporter_brief = stripslashes($reporter_brief);
                $reporter_brief = htmlspecialchars($reporter_brief);
                $report_subject = stripslashes($report_subject);
                $report_subject = htmlspecialchars($report_subject);
                $report_brief = stripslashes($report_brief);
                $report_brief = htmlspecialchars($report_brief);
                //удаляем лишние пробелы
                $title = trim($title);
                $reporter_brief = trim($reporter_brief);
                $report_subject = trim($report_subject);
                $report_brief = trim($report_brief);

//              подготовка путей для сохранения файлов
                $path = $_FILES['text_file']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $file = "../files/texts/" . $title . "." . $ext;
                $file_server_dir = "../files/texts/" . $title . "." . $ext;

                $path = $_FILES['presentation_file']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $file2 = "../files/presentations/" . $title . "." . $ext;
                $file2_server_dir = "../files/presentations/" . $title . "." . $ext;

                $query_statement = $pdo->prepare('INSERT INTO reports (id_user, title, brief, subject, summary,text_name,presentation_name) VALUES(:id_user, :title, :reporter_brief, :report_subject, :report_brief, :file, :file2)');
                $result = $query_statement->execute(['id_user' => $_SESSION['id_user'],'title' => $title,
                    'reporter_brief' => $reporter_brief,'report_subject' => $report_subject,
                    'report_brief' => $report_brief,'file' => $file,'file2' => $file2]);

                if ($result == 'true') {
                    if(!is_dir("../files/presentations")) {
                        mkdir("../files/presentations", 0777, true);
                    }
                    if(!is_dir("../files/texts")) {
                        mkdir("../files/texts", 0777, true);
                    }

                    move_uploaded_file($_FILES['text_file']['tmp_name'], $file_server_dir);
                    move_uploaded_file($_FILES['presentation_file']['tmp_name'], $file2_server_dir);

                    header('Location: index.php');
                    die();
                } else {
                    $_SESSION['error'][] = 'Ошибка. Заявка не была создана!';
                }
            }
        }
    }
}

