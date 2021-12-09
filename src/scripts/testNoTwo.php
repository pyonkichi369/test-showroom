<?php

require_once (dirname(__FILE__) . "/../YouTubeApi.php");

$youTubeApi = new YouTubeApi();
$results = $youTubeApi->getNumberOfApexLegendsVideos(10);

foreach ($results as $index => $result) {
    $number = $index + 1;
    echo $number . " https://www.youtube.com/watch?v=" . $result . PHP_EOL;
}
