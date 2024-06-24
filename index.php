<?php 

    session_start();
    //echo $_SESSION["usuario"];
    if(!empty($_SESSION["usuario"]) && !empty($_SESSION["pto_ven"]) && !empty($_SESSION["cedula"])){
        //echo $_SESSION["usuario"] . " / " . $_SESSION["pto_ven"] . " / " . $_SESSION["cedula"];
        header('Location: ./home.php');
    }
    //echo $_SESSION["Error"];
    if(isset($_SESSION["Error"]) && !empty($_SESSION["Error"])){
        $tipoError = $_SESSION["Error"];
        switch ($tipoError) {
            case 'err01':
                $errorUsu = "Usuario no existe";
                break;
            case 'err02':
                $errorCon = "Contraseña incorrecta";
                break;
        }
    }
    //$errorUsu = (isset($_GET["errorUsu"])) ? $_GET["errorUsu"] : "";
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/sweetalert2.min.css">
    <link rel="stylesheet" href="./css/estilo.css">
</head>
<body>
    <div class="container-fluid">
        <div id="bola1">  
        </div>
        <div id="bola2"></div>
        <div class="row">
            <div id="loginDiv" class="col">
                <h1>INICIO DE SESIÓN</h1>
                <div >
                    <form action="./php/login.php" method="post">
                        <div class="form-group">
                            <!-- <label for="txtusuario">Usuario:</label> -->
                            <span id="error01" class="spanError"><?php echo (isset($errorUsu) && !empty($errorUsu)) ? "Error:".$errorUsu : ""; ?></span>
                            <input type="text" class="inputLog form-control" placeholder="Usuario" id="txtusuario" name="txtusuario">
                        </div>
						
                            <button type="submit" id="btnLogin" class="btn btn-outline-success"> <i class="bi-box-arrow-in-right"> </i> </button>
                        
                        <div class="form-group">
                            <!-- <label for="txtcontra">Contraseña:</label> -->
                            <input type="password" class="inputLog form-control" placeholder="Contraseña" name="txtcontra" id="txtcontra">
                            <span id="error02" span="spanError"><?php echo (isset($errorCon) && !empty($errorCon)) ? "Error: ".$errorCon : ""; ?></span>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
        <div id="bola3">  
        </div>
        <div id="bola4"></div>
    </div>
<script src="./js/popper.min.js"></script>
<script src="./js/jquery-3.6.1.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/sweetalert2.all.min.js"></script>
<script src="./js/script.js"></script>
</body>
</html>