<?php

	libxml_use_internal_errors(true);
	include __DIR__ . '/../vendor/autoload.php';
	use PageScraper\Page\Page;
	use PageScraper\Builder\PageBuilder;
	use PageScraper\Director\PageBuilderDirector;
	
	$conexion = new mysqli("127.0.0.1", "root", "", "base1");
	if ($conexion->connect_errno) {
	    echo "Falló la conexión con MySQL: (" . $conexion->connect_errno . ") " . $conexion->connect_error;
	}	
	$sql = "TRUNCATE ciudades";
	if ($conexion->query($sql) === TRUE) {
	    echo "Record deleted successfully";
	} else {
	    echo "Error deleting record: " . $conexion->error;
	}
	$sql = "TRUNCATE hoteles";
	if ($conexion->query($sql) === TRUE) {
	    echo "Record deleted successfully";
	} else {
	    echo "Error deleting record: " . $conexion->error;
	}

	$uri = "https://www.tripadvisor.com.ve/";

	//creacion de ciudades
	$page = new Page();
	$page->setUrl("https://www.tripadvisor.com.ve/Rentals");
	$builder = new PageBuilder($page);
	$builder->setDataConfig([
	    'ciudades' => "/html/body[@class='rebrand_2017 HomeRebranded  js_logging']/div[@class='page']/div[@class='ui_container']/div[@id='taplc_popular_vacation_rentals_0']/ul[@class='flexCols']/li/a",
	    'enlaces' => "/html/body[@class='rebrand_2017 HomeRebranded  js_logging']/div[@class='page']/div[@class='ui_container']/div[@id='taplc_popular_vacation_rentals_0']/ul[@class='flexCols']/li/a/@href",
	]);

	$director = new PageBuilderDirector($builder);
	$director->buildPage();
	$ciudad_enlace = $page->getData();

	echo "<pre>";
	print_r ($ciudad_enlace);
	echo "</pre>";
	$j = 0;	
	
	for ($i=0; $i < 2; $i++) { 

		$ciudad = $ciudad_enlace['ciudades'][$i]; 
		$iterador = ceil ( preg_replace("/[^0-9]/", "", $ciudad)/50 )-1;  
		/*echo "Iterador :". $iterador . "<br>";*/
		mysqli_query($conexion,"insert into ciudades (nombre, enlace) values 
		                       ('".$ciudad_enlace['ciudades'][$i]."' , '".$ciudad_enlace['enlaces'][$i]."' )") or die("Problemas en el select".mysqli_error($conexion));
		
		$cadena = $ciudad_enlace['enlaces'][$i];
		$patron = "/Reviews-/";
		
		$encontrado = preg_match_all($patron, $cadena, $coincidencias, PREG_OFFSET_CAPTURE);

		if ($encontrado) {
		    
		    foreach ($coincidencias[0] as $coincide) {
		        /*print "<p>Cadena: '$coincide[0]' - Posición: $coincide[1]</p>\n";
		        echo "<br>";*/

		        $cadena_1 = substr($cadena,1,$coincide[1]-1+strlen($coincide[0]));
		        $cadena_2 = substr($cadena,($coincide[1]-1+strlen($coincide[0]))+1,strlen($cadena));

                $sw = "0";
		        $w = 0;
		        for ($y=0; $y <= $iterador ; $y++) { 
		        	$ww = "";
		        	if ($sw == "0") {
		        		$ww= $cadena_1 . $cadena_2;
		        	}else  {
		        		$ww= $cadena_1 . "oa" . $w . "-" . $cadena_2;
		        	}
		            $w = $w + 50;
		        	$sw = "1";
		        	echo $ww . "<br>";
		        	echo "https://www.tripadvisor.com.ve/".$ww;

	                    $url2 = "https://www.tripadvisor.com.ve/".$ww;
		                $page3 = new Page();
				      	$page3->setUrl("https://www.tripadvisor.com.ve/".$ww);
						$builder3 = new PageBuilder($page3);
						$builder3->setDataConfig([
						    
						    'nombre' => "/html/body[@id='BODY_BLOCK_JQUERY_REFLOW']/div[@id='PAGE']/div[@id='MAINWRAP']/div[@id='MAIN']/div[@id='BODYCON']/div[@class='wrpHeader']/div[@class='ui_container page']/div[@class='ui_columns listingsAndFilters']/div[@id='VR_SRP_CENTER_COLUMN']/div[3]/div[@class='vr_listings']/div[@class='vr_listing']/div/div/div/a",

						]);

						$director2 = new PageBuilderDirector($builder3);
						$director2->buildPage();
						$hoteles = $page3->getData();
						$ciudad_id = $i + 1;

						for ($x=0; $x < count($hoteles['nombre']); $x++) { 
							$nombre_hotel = preg_replace('/\'/', ' ', $hoteles['nombre'][$x]);
							mysqli_query($conexion,"insert into hoteles (nombre, ciudad_id) values 
						                       ('".$nombre_hotel."' , '".$ciudad_id."' )") or die("Problemas en el select".mysqli_error($conexion));
						}
					
			        }
			    }
			} else {
			    print "<p>No se han encontrado coincidencias.</p>\n";
			}

	}
			

	echo "fin";
		

	mysqli_close($conexion);	