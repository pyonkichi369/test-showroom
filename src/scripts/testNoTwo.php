<?php

require_once (dirname(__FILE__) . "/../YouTubeApi.php");

$youTubeApi = new YouTubeApi();
$results = $youTubeApi->getNumberOfApexLegendsVideos(10);

foreach ($results as $index => $result) {
    echo "https://www.youtube.com/watch?v=" . $result . PHP_EOL;
}
