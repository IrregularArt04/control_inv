<?php

include 'conexion.php';
session_start();
$codigo = $_GET["codigo"];

$tipo = $_GET["tipo"];

$query = "SELECT s.* FROM saldom" .$_SESSION["pto_ven"]." as s 
		JOIN productosm" .$_SESSION["pto_ven"]." as p ON s.codigo = p.codigo 
		WHERE '".$codigo."' IN (p.codigo, p.cod_bar) ". (($tipo == 2) ? "AND estado = 0" : "");

$devoluciones = array();
$ventas = array();
$tip_movi = "V";
try{
    $conn = AbrirCon();

    //$query .= " AND tip_movi = '". $tip_movi. "'";
    
    //echo $query;
    $result = $conn->query($query . " AND tip_movi = '". $tip_movi. "'");
    while($row = $result->fetch_assoc()){
        //echo json_encode(str_replace(chr(13), " ", $row['fecha']));
        array_push($ventas, $row);
    }

    $tip_movi = "D";

    $result = $conn->query($query . " AND tip_movi = '". $tip_movi. "'");
    while($row = $result->fetch_assoc()){
        array_push($devoluciones, $row);
    }

    $json = array("ventas" => $ventas, "devol" => $devoluciones);
    echo json_encode($json);

}catch(Exception $e){
    http_response_code(400);
    echo json_encode(array("error" => "No se encontraron saldos"));
}

?>