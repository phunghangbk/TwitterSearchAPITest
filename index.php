<?php
ini_set('display_errors', 1);
require_once('RetrieveTweet.php');

$access_token="786115172444282880-FvbQAYn61gZee1H3f2ayzQaJEMwUZ8g";
$access_token_secret="CCASxYENnzoaBo85dt5uLEwP71Bf5xI16A6ydZPxGVXah";
$consumer_key="bFKodhsZXph7pyeigJFHs1d95";
$consumer_secret="2KpQ6nDjg8PurboMu4Ws4oybZsBIJDhEKizW9EJuY1akjvfLbC";

$twitter = new RetrieveTweet();
$request = request();
$result = $twitter->getTweets($access_token, $access_token_secret, $consumer_key, $consumer_secret, $request);
$result = json_decode($result, true);
if (! empty($result['error']['keyword'])) {
	echo $result['error']['keyword'];
} else if (! empty($result['error']['empty_time'])) {
	echo $result['error']['empty_time'];
} else if (! empty($result['error']['time'])) {
	echo $result['error']['time'];
} else {
	echo mb_convert_encoding($result['message'], 'SHIFT-JIS', 'UTF-8');
}

function request()
{
	$result = array();
	parse_str(getenv('QUERY_STRING'), $result);
	return $result;
}
