<?php

session_start();

/* supprimer toutes les variables session */

$_SESSION = array();

/* détruire cookie session */

if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

/* détruire session */

session_destroy();

/* redirection */

header("Location: login.php");
exit();

?>