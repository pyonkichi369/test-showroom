<?php

require_once (dirname(__FILE__) . "/config/const.php");
require_once (dirname(__FILE__) . "/../vendor/autoload.php");

class YouTubeApi
{
    public $youTubeApi;

    public function __construct() {
        date_default_timezone_set('Asia/Tokyo');
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
        $part = ["snippet"];
        $params = [
            "q" => "Apex Legends",
            "order" => "viewCount",
            "type" => "video",
            "publishedAfter" => date('Y-m-d\TH:i:sP', strtotime('-3 day', time())),
            "pageToken" => "",
            "maxResults" => MAX_RESULTS,
        ];

        $i = 1;
        $results = array();
        while ($i <= $requiredNumber) {
            $response = $this->youTubeApi->search->listSearch($part, $params);
            foreach ($response["items"] as $index => $item) {
                $params["pageToken"] = $response->nextPageToken;
                $defaultAudioLanguage = $this->getDefaultAudioLanguage($item["id"]["videoId"]);
                if ($this->isJapaneseVideo($defaultAudioLanguage) && $i <= 10) {
                    array_push($results, $item["id"]["videoId"]);
                    $i++;
                }
                continue;
            }
        }
        return $results;
    }

    private function getDefaultAudioLanguage($videoId) {
        $part = ["snippet", "localizations"];
        $params = ["id" => $videoId];
        $response = $this->youTubeApi->videos->listVideos($part, $params);
        return $response["items"][0]["snippet"]["defaultAudioLanguage"];
    }

    private function isJapaneseVideo($defaultAudioLanguage) {
        return $defaultAudioLanguage === "ja";
    }
}
