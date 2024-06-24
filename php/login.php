<?php
    include 'conexion.php';
    include 'clave.php';

    if($_SERVER['REQUEST_METHOD'] <> "POST"){
        header('Location: ../');

    }


    $usuario = $_POST["txtusuario"];
    $contrasena = $_POST["txtcontra"];
    //echo password_hash($contrasena, PASSWORD_DEFAULT);
    //$contrasena = ClaveEncrip($contrasena, "E");
    //echo "   //   " .$contrasena;
    // echo $claveConv;
    // echo "   /////   ";
    // echo ClaveEncrip($claveConv, "D");

    $conn = AbrirCon();

    $query = "SELECT *, PASSWORD(\"" . $contrasena . "\") as conIn FROM usuarios WHERE usuario = UPPER('" .$usuario. "') AND aplicativo = 1";

    $result = $conn->query($query);
    session_start();    
    if($result->num_rows == 0){
        
        $_SESSION["Error"] = "err01"; 
        header('Location: ../');
        //header('Location: /');
    }else{

        while($usu = $result->fetch_assoc()){
            //echo trim($usu["conIn"]) . "  /  " . trim($usu["contra"]);
            if(trim($usu["conIn"]) !== trim($usu["contra"])){
                $_SESSION["Error"] = "err02";
                header('Location: ../');
            }else{
                
                $_SESSION["pto_ven"]    = $usu["pto_venta"];
                $_SESSION["usuario"]    = $usu["usuario"];
                $_SESSION["nombre"]     = $usu["nombre"];
                $_SESSION["cedula"]     = $usu["cedula"];
                header('Location: ../home.php');
            }
        }

        
        //echo "login";
    }
?>