<?php

use PhpTest\Services\WeatherService;
use Symfony\Component\HttpFoundation\Request;

if (!is_file(__DIR__ . '/../vendor/autoload.php')) {
    exit('/!\ Please run composer install /!\\');
}

require __DIR__ . '/../vendor/autoload.php';

// Get weather
$weatherService = new WeatherService();

// Get parameters
$request = new Request($_GET);
$params  = $request->query->all();

$weatherData = $weatherService->fetchCityWeather($params);

// Show weather
echo $weatherData;





