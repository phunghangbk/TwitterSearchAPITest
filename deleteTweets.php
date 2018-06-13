<?php
ini_set('display_errors', 1);
require_once('Tweet.php');
include('DatabasePDO.php');



$bdd = new DatabasePDO();
$twitter = new Tweet($bdd);
$query = request();
$date = ! empty($query['date']) ? $query['date'] : '';
$result = $twitter->deleteTweets($date);
echo $result;
function request()
{
	$result = array();
	parse_str($_SERVER['QUERY_STRING'], $result);
	return $result;
}
