<?php

function ClaveEncrip($clave, $accion){
    $cadena = "";
    $clave = TRIM($clave);
    //echo $clave;    
    switch ($accion) {
        case 'E':
            $m_plain = strtoupper($clave);
            $m_password = "LETRA";

            $m_coded = "";

            $j = 1;

            for ($i=1; $i < strlen($m_plain); $i++) { 
                $m_newcode = ord(substr($m_plain,$i,1)) + ord(substr($m_password,$j,1)) - ord("A");
                $j = $j + 1;

                if ($j > strlen($m_password)) {
                    $j = 1;
                }

                if($m_newcode > ord("Z")){
                    $m_newcode = ord("A") + ($m_newcode - ord("Z") - 1);
                }

                $m_coded = $m_coded . chr($m_newcode);
               // echo $m_newcode;
            }
            //echo $m_coded;
            $cadena = $m_coded;

            break;
        
        case 'D':
            $m_coded = strtoupper($clave);
            $m_password= "LETRA";
            
            $m_plain = "";
            
            $j= 1;
            
            for($i = 1; $i < strlen($m_coded); $i++){
                $m_plaincod = ord(substr($m_coded, $i, 1)) - ord(substr($m_password, $j,1)) + ord("A");
                $j = $j + 1;
                
                if($j > strlen($m_password)){
                    $j = 1;
                }
            
                if( $m_plaincod < ord("A")){
                    $m_plaincod = $m_plaincod + 26;
                }
                
                $m_plain = $m_plain . chr($m_plaincod);
            }
            
            $cadena = $m_plain;
            break;
        
        default:
            # code...
            break;
    }

    return $cadena;
}

function encripta($contra, $accion){

}

?>