<?php
function AbrirCon(){
   // $dbhost = "localhost";
   // $dbuser = "root";
   // $dbpass = "";
   // $db = "control_inv";
//-----------------------------------
   //$dbhost = "190.8.176.206";
   //$dbuser = "mercac_bitacoras";
   //$dbpass = "hO4!46sr7";
   //$db = "mercac_bitacoras";
//----------------------------------
	$dbhost = "localhost";
   	$dbuser = "mercac_bitacoras";
   	$dbpass = "hO4!46sr7";
   	$db = "mercac_bitacoras";
	
   	$conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
   
   return $conn;
}

function CerrarCon($conn){
   $conn -> close();
}
?>