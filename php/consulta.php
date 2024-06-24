<?php

include 'conexion.php';
session_start();
$codigo = $_GET["codigo"];
//$consulta = 'SELECT (SELECT sum(saldo) FROM saldoinv WHERE tip_movi <> "D" and codigo = "'.$codigo.'" and fecha = CURRENT_DATE()-1) - (SELECT sum(saldo) FROM saldoinv WHERE tip_movi = "D" and codigo = "'.$codigo.'" and fecha = CURRENT_DATE()-1) as "saldo" FROM `saldoinv` WHERE codigo = "'.$codigo.'" and fecha = CURRENT_DATE()-1 group by codigo;';
$consulta = "SELECT TRIM(productosm" .$_SESSION["pto_ven"].".nombre) as nombre, 
            productosm" .$_SESSION["pto_ven"].".referencia as refer, 
            SUM(IF(tip_movi = 'I', cantidad, 0)) AS saldoBodega,
            SUM(IF(tip_movi = 'V', cantidad, 0)) as ventasDia,
            SUM(IF(tip_movi = 'D', cantidad, 0)) as devolDia,
            SUM(IF(tip_movi IN ('I', 'D'), cantidad, IF(tip_movi = 'V', cantidad*-1, 0))) as restantesDia,
            SUM(IF(tip_movi = 'T' and estado = 0, cantidad, 0)) as saldoGondola,
            SUM(IF(tip_movi = 'V' and estado = 0, cantidad, 0)) as ventasConteo,
            SUM(IF(tip_movi = 'D' and estado = 0, cantidad, 0)) as devolConteo,
            SUM(IF(tip_movi IN ('T', 'D') and estado = 0, cantidad, IF(tip_movi = 'V' and estado = 0, cantidad*-1, 0))) as restantesConteo,
            (SELECT usuario FROM saldom" .$_SESSION["pto_ven"]." WHERE codigo = '" . $codigo . "' and tip_movi = 'I') as usuarioI,
            (SELECT usuario FROM saldom" .$_SESSION["pto_ven"]." WHERE codigo = '" . $codigo . "' and tip_movi = 'T' and estado = 0) as usuarioT,
            productosm" .$_SESSION["pto_ven"].".esControl as esControl 
            FROM `saldom" .$_SESSION["pto_ven"]."`
            LEFT JOIN `productosm" .$_SESSION["pto_ven"]."` 
            ON saldom" .$_SESSION["pto_ven"].".codigo = productosm" .$_SESSION["pto_ven"].".codigo 
            WHERE '" . $codigo . "' IN(productosm" .$_SESSION["pto_ven"].".codigo, productosm" .$_SESSION["pto_ven"].".cod_bar )   
            GROUP BY saldom" .$_SESSION["pto_ven"].".codigo";
//echo $consulta;
//$consulta = "CALL `consultar_saldo`('" . $codigo . "', '10', @p2)";
$conexion = AbrirCon();
 //echo $consulta;
$result = $conexion->query($consulta);

if($result->num_rows == 0){ //En caso de que no se encuentre el usuario o se encuentre repetido
    http_response_code(400);
    echo json_encode( array(
        "error" => "no se encontraron saldos"
    ));

}else{
    while ($row = $result->fetch_assoc()) {
        /*$json = array('mermaInterna' => $row["mermaInterna"],
                        'ventasDia' => $row["ventasDia"],
                        );*/
        echo json_encode($row);
    }
}

?>