<?php
	/*$conexion = new mysqli("localhost", "root", "", "prueba");
	if ($conexion->connect_errno) {
	    echo "Fall� la conexi�n con MySQL: (" . $conexion->connect_errno . ") " . $conexion->connect_error;
	}*/
	try {
    $conexion = new PDO('mysql:host=localhost;dbname=prueba', 'root', '');
    /*$consulta = $conexion->query('SELECT * from ciudades');
    foreach($consulta as $fila) {
        echo  $fila['descripcion'].'<br>';
    }
    $conexion = null;*/
} catch (PDOException $e) {
    print "�Error!: " . $e->getMessage() . "<br/>";
    die();
}




libxml_use_internal_errors(true);
include __DIR__ . '/../vendor/autoload.php';
use PageScraper\Page\Page;
use PageScraper\Builder\PageBuilder;
use PageScraper\Director\PageBuilderDirector;

$uri = "https://www.tripadvisor.com.ve/";

$page = new Page();
$page->setUrl("https://www.tripadvisor.com.ve/Rentals");
$builder = new PageBuilder($page);
$builder->setDataConfig([
    'titles' => "/html/body[@class='rebrand_2017 HomeRebranded  js_logging']/div[@class='page']/div[@class='ui_container']/div[@id='taplc_popular_vacation_rentals_0']/ul[@class='flexCols']/li/a",
    'links' => "/html/body[@class='rebrand_2017 HomeRebranded  js_logging']/div[@class='page']/div[@class='ui_container']/div[@id='taplc_popular_vacation_rentals_0']/ul[@class='flexCols']/li/a/@href",
]);

$director = new PageBuilderDirector($builder);
$director->buildPage();
$data = $page->getData();


echo "<pre>";
print_r ($data);
echo "</pre>";


// $links = array_combine(
//     $data['titles'],
//     $data['links']
// );


foreach ($data['links'] as $link) {

	$l = new Page();
	$l->setUrl("https://www.tripadvisor.com.ve".$link);
	$c = new PageBuilder($l);
	$c->setDataConfig([
		'rentals' => "/html/body[@id='BODY_BLOCK_JQUERY_REFLOW']/div[@id='PAGE']/div[@id='MAINWRAP']/div[@id='MAIN']/div[@id='BODYCON']/div[@class='wrpHeader']/div[@class='ui_container page']/div[@class='ui_columns listingsAndFilters']/div[@id='VR_SRP_CENTER_COLUMN']/div[3]/div[@class='vr_listings']/div[@class='vr_listing']/div[@class='prw_rup prw_vr_listings_vr_srp']/div",
	]);

	$d = new PageBuilderDirector($c);
	$d->buildPage();

	echo "<pre>";
	//print_r ($l->getData());
	print_r ($link);
	echo "</pre>";
	// break;
	

}

//for ($i=0; $i <= count($link); $i++) {
for ($i=0; $i <=count($data['links']) ; $i++) { 
	//echo $i;
	//echo $data['titles'][$i];
	//echo count($link);
	echo $data['titles'][$i] . '<br>';
	if($conexion->query("select upper(descripcion) from ciudades where trim(upper(descripcion)) = 'trim(upper(" . $data['titles'][$i] ."))'") == false){
		$conexion->query("insert into ciudades(descripcion) values 
	                       ('".$data['titles'][$i]."')");
	} 
}
?>