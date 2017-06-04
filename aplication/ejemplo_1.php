<?php
	 
	/*$cantidad_hoteles =preg_replace("/[^0-9]/", "", $nombre_ciudad);
	echo $cantidad_hoteles;*/
	$ciudad = var; 
	echo  $iterador = floor (  preg_replace("/[^0-9]/", "", $ciudad)/50 );  

	
	$cadena = var;
	$patron = "/Reviews-/";
	$tam_cadena = strlen($cadena);
	$tam_patron = strlen($patron);
	//echo "Tamaño :" . $tam . "<br>";
	$encontrado = preg_match_all($patron, $cadena, $coincidencias, PREG_OFFSET_CAPTURE);

	if ($encontrado) {
	    /*print "<pre>"; print_r($coincidencias); print "</pre>\n";
	    print "<p>Se han encontrado $encontrado coincidencias.</p>\n";*/
	    foreach ($coincidencias[0] as $coincide) {
	        print "<p>Cadena: '$coincide[0]' - Posición: $coincide[1]</p>\n";
	        //echo " Cadena :".$coincidencias[0];
	        echo "<br>";

	        
	        $cadena_1 = substr($cadena,1,$coincide[1]-1+strlen($coincide[0]));
	        $cadena_2 = substr($cadena,($coincide[1]-1+strlen($coincide[0]))+1,strlen($cadena));
	        echo "Cadena 1 :" . $cadena_1  . "<br>";
	        echo "Cadena 2 :" . $cadena_2  . "<br>";
	        //x = valor/50
	        $sw = "0";
	        $w = 0;
	        for ($i=0; $i <= $iterador ; $i++) { 
	        	if ($sw == "0") {
	        		$ww= $cadena_1 . $cadena_2;
	        	}else  {
	        		$ww= $cadena_1 . "oa" . $w . "-" . $cadena_2;
	        	}
	            $w = $w + 50;
	        	
	        	/*$ww= $cadena_1 . "oa" . $w . "-" . $cadena_2;*/
	        	echo $ww . "<br>";
	        	$sw = "1";
	        }
	    }
	} else {
	    print "<p>No se han encontrado coincidencias.</p>\n";
	}

