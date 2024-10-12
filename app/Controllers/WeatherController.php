<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class WeatherController extends Controller
{
    public function index()
    {
        $apiKey = 'c619473341a5a496ab839db794ad8fb7';
        $city = 'Bhopal';
        $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->get($url);
            $data = json_decode($response->getBody());
            if (isset($data->main)) {
                $weather = [
                    'city' => $data->name,
                    'temperature' => $data->main->temp,
                    'description' => $data->weather[0]->description,
                    'humidity' => $data->main->humidity,
                    'wind_speed' => $data->wind->speed,
                ];

                return view('weather_view', ['weather' => $weather]);
            } else {
                return view('weather_view', [
                    'error' => 'Weather data not found for the specified city.'
                ]);
            }
        } catch (\Exception $e) {
            return view('weather_view', [
                'error' => 'Unable to fetch weather data. Please try again later.'
            ]);
        }
    }
}
