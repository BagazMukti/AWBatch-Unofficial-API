<?php
/* AWBatch Unofficial API */
/* Coded by bl33dz */
error_reporting(0);
header("Content-Type: application/json;charset=UTF-8");

function curl($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 5.1; bl33dz Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.92 Mobile Safari/537.36');
	$res = curl_exec($ch);
	$res = str_replace(urldecode('%0A'), '', $res);
	return $res;
}

if(isset($_GET['page'])) {
	$content = (($_GET['page'] == 1) ? curl("http://awbatch.in/") : curl("http://awbatch.in/page/".$_GET['page']));
	preg_match_all('/<!-- post thumbnail -->(.*?)<div style="borde/', $content, $batchlist);
	foreach($batchlist[1] as $batchcon) {
		preg_match('/title="(.*?)"/', $batchcon, $title);
		preg_match('/href="(.*?)"/', $batchcon, $link);
		preg_match('/http:\/\/awbatch.in\/(.*?).html/', $link[1], $title2);
		preg_match('/datetime="(.*?)"/', $batchcon, $date);
		preg_match('/rel="author" data-wpel-link="internal">(.*?)<\/a>/', $batchcon, $author);
		preg_match('/Status : (.*?)<\/p>/', $batchcon, $status);
		preg_match('/View :<\/b> (.*?) Views<\/p>/', $batchcon, $view);
		preg_match_all('/rel="tag" data-wpel-link="internal">(.*?)<\/a>/', $batchcon, $genres);
		preg_match('/Aired on (.*?)<\/p>/', $batchcon, $aired);
		$data['title'] = $title[1];
		$data['title2'] = $title2[1];
		$data['link'] = $link[1];
		$data['date'] = $date[1];
		$data['author'] = $author[1];
		$data['status'] = $status[1];
		$data['view'] = $view[1];
		$data['genres'] = $genres[1];
		$data['aired'] = $aired[1];
		$res[] = $data;
	}
	print json_encode($res, JSON_PRETTY_PRINT);
} elseif(isset($_GET['anime'])) {
	$content = curl("http://awbatch.in/".$_GET['anime'].".html");
	preg_match('/<main role="main">(.*?)<\/main>/', $content, $infocont);
	$infocon = $infocont[1];
	preg_match('/id="rar">(.*?)<\/h1>/', $infocon, $title);
	preg_match('/datetime="(.*?)"/', $infocon, $date);
	preg_match('/<\/i> (.*?) Views<\/span>/', $infocon, $view);
	preg_match('/Type<\/b>: (.*?)<\/div>/', $infocon, $type);
	preg_match('/Total Episodes<\/b>: (.*?)<\/div>/', $infocon, $episode);
	preg_match('/Tayang<\/b>: (.*?)<\/div>/', $infocon, $aired);
	preg_match('/Genres<\/b>: (.*?)<\/div>/', $infocon, $genre);
	preg_match_all('/rel="tag" data-wpel-link="internal">(.*?)<\/a>/', $genre[1], $genres);
	preg_match('/Score<\/b>: (.*?)<\/div>/', $infocon, $score);
	preg_match('/Credit SUB<\/b>: (.*?)<\/div>/', $infocon, $creditsub);
	preg_match('/Uploaded by<\/b>: (.*?) \(/', $infocon, $uploader);
	preg_match_all('/<p>(.*?)<\/p>/', $infocon, $sinopsis);
	preg_match('/<iframe width="100%" height="400" src="(.*?)" frameborder="0" allowfullscreen>/', $infocon, $trailer);
	preg_match('/Link Download Batch versi 360p(.*?)<hr \/>/', $infocon, $link360);
	preg_match('/Link Download Batch versi 480p(.*?)<hr \/>/', $infocon, $link480);
	preg_match('/Link Download Batch versi 720p(.*?)<div id="downloadlink">/', $infocon, $link720);
	preg_match_all('/<a href="(.*?)" data-wpel-link="external" target="_blank" rel="nofollow external noopener noreferrer">/', $link360[1], $l360);
	preg_match_all('/<a href="(.*?)" data-wpel-link="external" target="_blank" rel="nofollow external noopener noreferrer">/', $link480[1], $l480);
	preg_match_all('/<a href="(.*?)" data-wpel-link="external" target="_blank" rel="nofollow external noopener noreferrer">/', $link720[1], $l720);

	$data['title'] = $title[1];
	$data['date'] = $date[1];
	$data['view'] = $view[1];
	$data['type'] = $type[1];
	$data['episode'] = $episode[1];
	$data['aired'] = $aired[1];
	$data['genres'] = $genres[1];
	$data['score'] = $score[1];
	$data['creditsub'] = $creditsub[1];
	$data['uploader'] = $uploader[1];
	$data['trailer'] = $trailer[1];
	$data['sinopsis'] = $sinopsis[1][2];
	$data['360p'] = $l360[1];
	$data['480p'] = $l480[1];
	$data['720p'] = $l720[1];
	print json_encode($data, JSON_PRETTY_PRINT);
} else {
	print "  @@@@@@  @@@  @@@  @@@
 @@!  @@@ @@!  @@!  @@!
 @!@!@!@! @!!  !!@  @!@
 !!:  !!!  !:  !!:  !! 
  :   : :   ::.:  :::
[ AWBatch Unofficial API ]

Usage: ?page=[number]
       ?anime=[title2]

Example: ?page=2
         ?anime=love-live-sunshine-subtitle-indonesia-batch-episode-1-13

- by bl33dz -";
}