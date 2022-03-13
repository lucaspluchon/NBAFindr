<?php

namespace App\Service;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PlayerApi
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    //Some values can be null, so we transform it to 0 to avoid some null error
    private function check_null($value)
    {
        return $value == null ? 0 : $value;
    }


    //Generate from API all the usefuls players info
    private function generatePlayer_info($api_result)
    {
        if (empty($api_result))
            return null;
        $playerInfos = $api_result;


        $playerStats = $this->getPlayerStat($playerInfos["id"])["data"];
        if (empty($playerStats))
        {
            $stats = null;
        }
        else
        {
            $stats = $playerStats[0];
        }

        $res = [
            "name" => $playerInfos["first_name"] . " " . $playerInfos["last_name"],
            "team" => $playerInfos["team"]["full_name"],
            "height" => round($this->check_null($playerInfos["height_feet"]) * 30.48, 2) . " cm", // feet to centimeters
            "weight" => round($this->check_null($playerInfos["weight_pounds"]) * 0.453592, 2) . " kg", // pounds to kg
            "stats" => $stats
        ];

        return $res;
    }

    public function info_fromName($name)
    {
        return $this->generatePlayer_info($this->getPlayerByName($name)["data"][0]);
    }

    public function info_fromId($id)
    {
        return $this->generatePlayer_info($this->getApi("players/" . $id));
    }

    public function getAllPlayers($page)
    {
        return $this->getApi('players?page='. $page);
    }

    public function getPlayerStat($id)
    {
        return $this->getApi('season_averages?player_ids[]='. $id);
    }

    public function getPlayerByName($name)
    {
        return $this->getApi('players?search=' . $name);
    }

    private function getApi($request)
    {
        $response = $this->client->request(
            'GET',
            'https://www.balldontlie.io/api/v1/' . $request
        );

        return $response->toArray();
    }
}