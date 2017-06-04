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


	//creacion de ciudades
	
	//echo "Ciudades grabadas";
	for ($i=0; $i < 3; $i++) { 
		
		mysqli_query($conexion,"insert into ciudades (nombre, enlace) values 
		                       ('".$ciudad_enlace['ciudades'][$i]."' , '".$ciudad_enlace['enlaces'][$i]."' )") or die("Problemas en el select".mysqli_error($conexion));
	
		$page3 = new Page();
		$page3->setUrl("https://www.tripadvisor.com.ve".$ciudad_enlace['enlaces'][$i]);
		$builder3 = new PageBuilder($page3);
		$builder3->setDataConfig([
		    
		    'nombre' => "/html/body[@id='BODY_BLOCK_JQUERY_REFLOW']/div[@id='PAGE']/div[@id='MAINWRAP']/div[@id='MAIN']/div[@id='BODYCON']/div[@class='wrpHeader']/div[@class='ui_container page']/div[@class='ui_columns listingsAndFilters']/div[@id='VR_SRP_CENTER_COLUMN']/div[3]/div[@class='vr_listings']/div[@class='vr_listing']/div/div/div/a",

		]);

		$director2 = new PageBuilderDirector($builder3);
		$director2->buildPage();
		$hoteles = $page3->getData();
		/*echo "<pre>";
		print_r ($hoteles);
		echo "</pre>";*/
		
		echo $ciudad_enlace['ciudades'][$i];		
		$ciudad_id = $i + 1;
		
		// for ($x=0; $x < count($hoteles['nombre']); $x++) { 
		// 	mysqli_query($conexion,"insert into hoteles (nombre, ciudad_id) values 
		//                        ('".$hoteles['nombre'][$x]."' , '".$ciudad_id."' )") or die("Problemas en el select".mysqli_error($conexion));
		// }
		
		/*echo "<pre>";
		print_r ($hoteles);
		echo "</pre>";*/
	}
			

	echo "<pre>";
	print_r ($hoteles);
	echo "</pre>";
		
	
	

	mysqli_close($conexion);	