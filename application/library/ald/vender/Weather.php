<?php

class Ald_Vender_Weather {

    private $city = "";
    private $ak ="oItdMYguj3VFo7ZYP4EnVpgy";

    public function getWeather($city){

        $url = "http://api.map.baidu.com/telematics/v3/weather?location=$city&output=json&ak=$this->ak";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $arr = json_decode($output);
        $weathers = $arr->results[0]->weather_data[0]->weather;
        $temperature = $arr->results[0]->weather_data[0]->temperature;
        $weather = $weathers." ".$temperature;
        return $weather;
    }

} 