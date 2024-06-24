<?php
//  header("Access-Control-Allow-Origin: *");

    session_start();
    $_SESSION["pto_ven"]    = "";
    $_SESSION["usuario"]    = "";
    $_SESSION["nombre"]     = "";
    $_SESSION["cedula"]     = "";
    session_destroy();

    header('Location: ../index.php')
?>