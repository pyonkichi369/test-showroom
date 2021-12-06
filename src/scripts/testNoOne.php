<?php

require_once (dirname(__FILE__) . "/../YouTubeApi.php");

$youTubeApi = new YouTubeApi();
$results = $youTubeApi->getNumberOfShowroomVideos(100);

foreach ($results as $index => $result) {
    echo "https://www.youtube.com/watch?v=" . $result["id"]["videoId"] . PHP_EOL;
}
