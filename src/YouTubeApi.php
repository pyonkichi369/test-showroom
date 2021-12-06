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

    public function getNumberOfShowroomVideos($requiredNumber = 0)
    {
        $loopCountFrom = 1;
        $loopMaxCount = $requiredNumber / MAX_RESULTS;

        $results = array();
        for ($i = $loopCountFrom; $i <= $loopMaxCount; $i++) {
            $part = ["snippet"];
            $params = [
                "q" => "SHOWROOM",
                "order" => "date",
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
}
