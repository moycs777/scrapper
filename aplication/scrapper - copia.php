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
	$data = $page->getData();

	echo "<pre>";
	//print_r ($data);
	echo "</pre>";

	// $enlaces = array_combine(
	//     $data['ciudades'],
	//     $data['enlaces']
	// );

	//creacion de ciudades
	for ($i=0; $i <=count($data['ciudades']) ; $i++) { 
		//echo $i;
		//echo $data['titles'][$i];
		//echo count($link);
		
		echo $data['ciudades'][$i] . '<br>';
		echo $data['enlaces'][$i] . '<br>';
		mysqli_query($conexion,"insert into ciudades (nombre, enlace) values 
		                       ('".$data['ciudades'][$i]."' , '".$data['enlaces'][$i]."' )") or die("Problemas en el select".mysqli_error($conexion));
		
	}
	echo "Ciudades grabadas";

	
	//creacion de enlaces por cada ciudad
	/*foreach ($data['enlaces'] as $enlace) {*/

		/*$l = new Page();
		$l->setUrl("https://www.tripadvisor.com.ve/VacationRentals-g147247-Reviews-Aruba-Vacation_Rentals.html");
		$c = new PageBuilder($l);
		$c->setDataConfig([
			'rentals' => "/html/body[@id='BODY_BLOCK_JQUERY_REFLOW']/div[@id='PAGE']/div[@id='MAINWRAP']/div[@id='MAIN']/div[@id='BODYCON']/div[@class='wrpHeader']/div[@class='ui_container page']/div[@class='ui_columns listingsAndFilters']/div[@id='VR_SRP_CENTER_COLUMN']/div[3]/div[@class='vr_listings']/div[@class='vr_listing']/div[@class='prw_rup prw_vr_listings_vr_srp']/div",
		]);

		$d = new PageBuilderDirector($c);
		$d->buildPage();
		$d = $l->getData();

		echo "<pre>";
		print_r ($d);
		//print_r ($enlace);
		echo "</pre>";*/
		// break;
	

		$uri = "https://www.tripadvisor.com.ve/";
		
		$page2 = new Page();
		$page2
		->setUrl("https://www.tripadvisor.com.ve/VacationRentals-g294316-Reviews-Lima_Lima_Region-Vacation_Rentals.html");
		$builder2 = new PageBuilder($page2);
		$builder2->setDataConfig([
		    
		    'hoteles' => "/html/body[@id='BODY_BLOCK_JQUERY_REFLOW']/div[@id='PAGE']/div[@id='MAINWRAP']/div[@id='MAIN']/div[@id='BODYCON']/div[@class='wrpHeader']/div[@class='ui_container page']/div[@class='ui_columns listingsAndFilters']/div[@id='VR_SRP_CENTER_COLUMN']/div[3]/div[@class='vr_listings']/div[@class='vr_listing']/div/div/div/a",

		]);

		$director2 = new PageBuilderDirector($builder2);
		$director2->buildPage();
		$data2 = $page2->getData();

		echo "<pre>";
		print_r ($data2);
		echo "</pre>";

		//guardamos las ciudades en bd

		//echo $data2['hoteles2'][0] . '<br>';
		mysqli_query($conexion,"insert into hoteles (nombre) values 
		                       ('asd' )") or die("Problemas en el select".mysqli_error($conexion));

	
	echo "Rentals grabados";

	mysqli_close($conexion);	