<?php

require_once (dirname(__FILE__) . "/config/const.php");
require_once (dirname(__FILE__) . "/../vendor/autoload.php");

class YouTubeApi
{
    public $youTubeApi;

    public function __construct() {
        $this->youTubeApi = new Google_Service_YouTube($this->getClient());
    }

    public function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName("youtubeTestApp");
        $client->setDeveloperKey(API_KEY);
        return $client;
    }

    public function getNumberOfShowroomVideos($requiredNumber = 50)
    {
        $loopCountFrom = 1;
        $loopMaxCount = $requiredNumber / MAX_RESULTS;

        $results = array();
        for ($i = $loopCountFrom; $i <= $loopMaxCount; $i++) {
            $part = ["snippet"];
            $params = [
                "q" => "SHOWROOM",
                "order" => "date",
                "type" => "video",
                "pageToken" => "",
                "maxResults" => MAX_RESULTS,
            ];
            $firstResponse = $this->youTubeApi->search->listSearch($part, $params);
            if ($i != $loopCountFrom) {
                $results = array_merge($results, $firstResponse["items"]);
            } elseif (isset($firstResponse->nextPageToken)) {
                $params["pageToken"] = $firstResponse->nextPageToken;
                $secondResponse = $this->youTubeApi->search->listSearch($part, $params);
                $results = array_merge($results, $secondResponse["items"]);
            }
        }
        return $results;
    }

    public function getNumberOfApexLegendsVideos($requiredNumber = 10)
    {
        $loopCountFrom = 1;
        $loopMaxCount = $requiredNumber / MAX_RESULTS;
//         $publishedAfter = date('Y-m-d H:i:s', strtotime('-3 day', time()));
//         var_dump($publishedAfter);

        $i = 0;
        $results = array();
        while ($i <= 10) {
            $part = ["snippet"];
            $params = [
                "q" => "Apex Legends",
                "order" => "viewCount",
                "type" => "video",
//                 "publishedAfter" => $publishedAfter,
                "pageToken" => "",
                "maxResults" => MAX_RESULTS,
            ];
            $response = $this->youTubeApi->search->listSearch($part, $params);
            foreach ($response as $index => $value) {
                var_dump($value);
            }
            $i++;
        }
        return $results;
    }
}
