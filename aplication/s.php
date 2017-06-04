<?php
	include __DIR__ . '/../vendor/autoload.php';
	use curl\curl\Curl;

	
	$curl = new Curl();
	$url = "http://www.google.com/finance/company_news?q=PINK:GDHI&start=0&num=30";
	$fields = "usr=user1&pass=PassWord";
	$html_text = $curl->postForm($url, $fields);
	$html = new DOMDocument();
	$xpath = new DOMXPath( $html );
	$links = $xpath->query( ".//div[@id='news-main']/div[@class='g-section news sfe-break-bottom-16']" ); 
	$return = array();

	foreach ( $links as $item ) {
		$newDom = new DOMDocument; $newDom->appendChild($newDom->importNode($item,true)); $xpath = new DOMXPath( $newDom ); 
		$title = trim($xpath->query("//span[@class='name']/a")->item(0)->nodeValue);
		$date = trim($xpath->query("//div[@class='byline']/span[@class='date']")->item(0)->nodeValue);
		$source = trim($xpath->query("//span[@class='name']/a")->item(0)->getAttribute('href'));

		$return[] = array( 'title' => $title, 'date' => $date, 'sources' => $source, ); 
	}

	echo '<pre>'; 
	print_r($return);
?>
