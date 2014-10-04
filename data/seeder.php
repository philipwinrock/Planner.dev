<?php
$dbc = new PDO('mysql:host=127.0.0.1;dbname=ads', 'codeup', 'rocks');


$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



$ads = [
		['title' => 'bike' ,        'content' => "its a bike its awesome" ],
		['title' => 'car' ,         'content' => "its a car its awesome" ],
		['title' => 'house' ,       'content' => "its a house its awesome" ],
		['title' => 'Xbox' ,        'content' => "its a Xbox its awesome" ],
		['title' => 'Playstation' , 'content' => "its a Playstation its awesome"] 
];

$tags = array(
'building' , "transportation" , 'console' , 'used' , 'new'
);

$adIds =array();
$tagIds = array();

$insertAd = $dbc->prepare('INSERT INTO ads (title,content) VALUES(:title , :content)');
$insertTag = $dbc->prepare('INSERT INTO tags (content) VALUES(:content)');
foreach ($ads as $ad) {
	$insertAd->bindValue(':title', $ad['title'],    PDO::PARAM_STR);
	$insertAd->bindValue(':content', $ad['content'],PDO::PARAM_STR);

	$insertAd->execute();

	$adIds[] = $dbc->lastInsertId();

}

foreach ($tags as $tag) {
	$insertTag->bindvalue(':content', $tag , PDO::PARAM_STR); 
	$insertTag->execute();
	$tagIds[]= $dbc->lastInsertId();
}

$insertAdTag = $dbc->prepare('INSERT INTO ad_tag VALUES (:adId, :tagId)');

foreach ($adIds as $adId) {
	$insertAdTag->bindvalue(':adId' , $adId, PDO::PARAM_INT);

	$numTags = rand(2,5);

	foreach (array_rand($tagIds, $numTags)as $key) {
		$insertAdTag->bindValue(':tagId', $tagIds[$key], PDO::PARAM_INT);

		$insertAdTag->execute();
	}

}
















