<?php

include 'conexion.php';
session_start();
try{

    $codigo = $_POST["codigo"];
    $cantidad = $_POST["cantidad"];

    $conn = AbrirCon();
	
	$query = "SELECT codigo FROM productosm" .$_SESSION["pto_ven"]. " WHERE '" .$codigo. "' IN(cod_bar, codigo)";

	$result = $conn->query($query);
	
	while($producto = $result->fetch_assoc()){
		$codigo = $producto["codigo"];	
	}
	
    $query = "UPDATE saldom" .$_SESSION["pto_ven"]." SET estado = 1 WHERE codigo = '" . $codigo . "'";
    //echo $query;
    $conn->query($query);

    $query = "INSERT INTO saldom" .$_SESSION["pto_ven"]."(pto_venta, codigo, tip_movi, cantidad, cod_pos, doc_fact, num_fact, usuario, movimiento) 
                VALUES('" .$_SESSION["pto_ven"]."','". $codigo ."', 'T', '". $cantidad ."', 'PAG', '', 0, '" .$_SESSION["usuario"]."', 'E')";

    $conn->query($query);
	//echo $query;
    //$query =     
}catch(Exception $e){
    echo json_encode(Array("error" => "Error al conectar con la bd."));
}

?>