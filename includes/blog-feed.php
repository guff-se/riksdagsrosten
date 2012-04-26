<?php
	$rss = new DOMDocument();
	$rss->load('http://www.riksdagsrosten.se/blogg/feed/');
	$feed = array();
	foreach ($rss->getElementsByTagName('item') as $node) {
		$item = array ( 
			'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
			'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
			'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
			'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
			);
		array_push($feed, $item);
	}
	$limit = 1;
	for($x=0;$x<$limit;$x++) {
		$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
		$link = $feed[$x]['link'];
		$description = $feed[$x]['desc'];
		setlocale(LC_ALL, 'sv_SE');
		//$date = date('d F Y', strtotime($feed[$x]['date']));
		$date = date('Y-m-d', strtotime($feed[$x]['date']));
		echo '<h4 style="padding-left:0px;"><a style="text-decoration:none;" href="'.$link.'" title="'.$title.'">'.$title.'</a></h4>';
		//echo '<small><em>'.$date.'</em></small>';
		echo '<p>'.$description.'</p>';
	}
?>