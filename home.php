<?php
    session_start();
    if((isset($_SESSION["usuario"]) && empty($_SESSION["usuario"]) || empty($_SESSION["pto_ven"]) || empty($_SESSION["cedula"]))){
        echo $_SERVER['REQUEST_URI'];
        header('Location: ./index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de inventarios</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/sweetalert2.min.css">
    <link rel="stylesheet" href="./css/estilo.css">
</head>
<body>
    <!-- modal ventas / devol -->
    <div class="modal" tabindex="-1" role="dialog" id="myModal">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Histórico de producto</h5>
                    <button type="button" class="close btn btn-outline-secondary" data-bs-dismiss="modal" onclick="cerrarModal();" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 tablaMovim">
                            <h4>Devoluciones</h4>
                            <table class="table table-striped ">
                                <thead>
                                    <th>pto. venta</th>
                                    <th>documento</th>
                                    <th>número</th>
                                    <th>codigo pos</th>
                                    <th>cantidad</th>
                                    <th>usuario</th>
                                    <th class="tdFechaHora">fecha/hora</th>
                                </thead>
                                <tbody id="tablaDevol">
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-12 tablaMovim">
                                <h4>Ventas</h4>
                                <table class="table table-striped">
                                    <thead>
                                        <th>pto. venta</th>
                                        <th>documento</th>
                                        <th>número</th>
                                        <th>codigo pos</th>
                                        <th>cantidad</th>
                                        <th>usuario</th>
                                        <th class="tdFechaHora">fecha/hora</th>
                                    </thead>
                                    <tbody id="tablaVentas">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="cerrarModal();">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- fin modal ventas / devol -->

    <!-- modal de nuevo conteo -->
    <div class="modal fade" id="modalConteo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Nuevo conteo de producto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="nuevaCantidad">Nueva cantidad:</label>
                    <input type="number" class="form-control" name="nuevaCantidad" id="nuevaCantidad">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="nuevoConteo()">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- fin modal conteo -->

    <!-- body de la pagina -->
    <div class="container-fluid">
        <div id="bola1"></div>
        <div id="bola2"></div>
        <div class="divRow row justify-content-around">
            <div class="col">
                <p id="pFechaCon">Fecha de consulta: <h6 href=""id="fechaCons"></h6> </p>   
            </div>
            <div class="col mx-auto justify-content-end" id="divCerrarSe">
                <form action="./php/logout.php">
                    <input class="btn btn-outline-info" type="submit" value="Cerrar Sesión">
                </form>
            </div>
        </div>
        <!-- <div class="row">
            
        </div> -->
        <div class="divRow row divBusqueda">
            <div class="col">
                <form action="">
                    <div class="form-group col">
                        <label for="txtCodigo">Codigo:</label>
                        <input type="text" class="form-control" name="txtCodigo" id="txtCodigo">
                    </div>
                    <div class="form-group">
                        <button class= "btn btn-primary" id="btnConsulta"><i class="bi-search"></i> Consultar</button>
                    </div>
                </form>
                <h6 id= "hConsulta"> <?php echo "Está consultando en: Mercacentro N°".$_SESSION["pto_ven"] ?> </h6>
            </div>
        </div>
        <div class="divRow row">
        <svg class="pl" viewBox="0 0 200 200" width="200" height="200" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="pl-grad1" x1="1" y1="0.5" x2="0" y2="0.5">
                    <stop offset="0%" stop-color="hsl(313,90%,55%)" />
                    <stop offset="100%" stop-color="hsl(223,90%,55%)" />
                </linearGradient>
                <linearGradient id="pl-grad2" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="hsl(313,90%,55%)" />
                    <stop offset="100%" stop-color="hsl(223,90%,55%)" />
                </linearGradient>
            </defs>
            <circle class="pl__ring" cx="100" cy="100" r="82" fill="none" stroke="url(#pl-grad1)" stroke-width="36" stroke-dasharray="0 257 1 257" stroke-dashoffset="0.01" stroke-linecap="round" transform="rotate(-90,100,100)" />
            <line class="pl__ball" stroke="url(#pl-grad2)" x1="100" y1="18" x2="100.01" y2="182" stroke-width="36" stroke-dasharray="1 165" stroke-linecap="round" />
        </svg>
            <div class="divConsulta col" id="divConsulta">
                <h2 id="nombreProd">Nombre de producto</h2>
                <table>
                    <thead>
                        <th>EXISTENCIA TOTAL</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="saldoBodega">Inicial noche: </td>
                            
                        </tr>
                        <tr class="informacion">
                            <td id="ventasDia">Ventas día: </td>
                        </tr>
                        <tr class="informacion">
                            <td id="devolDia">Devolucíon día: </td>
                        </tr>
                        <tr class="informacion">
                            <td id="saldoDia">Saldo actual: </td>
                        </tr>
                        <tr>
                            <td><button type="button" class=" btnDetalle btn btn-info" onclick="mostrarDetalle(1)"><i class="bi-eye"></i> Histórico...</button></td>
                        </tr>
                    </tbody>
                </table>

                <table class="informacion">
                    <thead>
                        <th>PISO DE VENTA </th>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="saldoGondola">Inicio de turno: </td>
                        </tr>
                        <tr>
                            <td id="ventasTurno">Ventas turno: </td>
                        </tr>
                        <tr>
                            <td id="devolTurno">Devolucíon turno: </td>
                        </tr>
                        <tr>
                            <td id="saldoTurno">Saldo turno: </td>
                        </tr>
                        <tr>
                         <td><button type="button" class="btnDetalle btn btn-info" onclick="mostrarDetalle(2)"><i class="bi-eye"></i>  Histórico...</button></td>
                        </tr>
                    </tbody>
                </table>

                <button class="btn btn-danger" id="btnConteo" onclick="mostrarModal('modalConteo')"><i class="bi-pencil-square"></i> Nuevo conteo</button>
            </div>
        </div>
        <!-- <div id="bola3">  
        </div>
        <div id="bola4"></div> -->
    </div>
    <!-- fin del body de la pagina -->

    <script src="./js/popper.min.js"></script>
    <script src="./js/jquery-3.6.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/sweetalert2.all.min.js"></script>
    <script src="./js/script.js"></script>
</body>
</html>