<?php

session_start();

if (isset($_SESSION['logado'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php');
    exit;
}

?>