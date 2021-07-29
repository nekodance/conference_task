<?php
require_once ('../handlers/exceptions_error_handler.php');
function logout()
{

//  сброс $_SESSION
    $_SESSION = array();

// сброс cookie, к которой привязана сессия (если привязана)
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

// уничтожение сессии
    session_destroy();

    header('Location: index.php');
    die();
}


