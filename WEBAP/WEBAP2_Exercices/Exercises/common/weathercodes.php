<?php
/*
 * WMO weather interpretation codes used by Open-Meteo (field weather_code)
 * weatherInfo(61) returns array("text" => "Slight rain", "icon" => "🌧️")
 */
const WEATHER_CODES = array(
    0  => array("Clear sky", "☀️"),
    1  => array("Mainly clear", "🌤️"),
    2  => array("Partly cloudy", "⛅"),
    3  => array("Overcast", "☁️"),
    45 => array("Fog", "🌫️"),
    48 => array("Depositing rime fog", "🌫️"),
    51 => array("Light drizzle", "🌦️"),
    53 => array("Moderate drizzle", "🌦️"),
    55 => array("Dense drizzle", "🌦️"),
    56 => array("Light freezing drizzle", "🌧️"),
    57 => array("Dense freezing drizzle", "🌧️"),
    61 => array("Slight rain", "🌧️"),
    63 => array("Moderate rain", "🌧️"),
    65 => array("Heavy rain", "🌧️"),
    66 => array("Light freezing rain", "🌧️"),
    67 => array("Heavy freezing rain", "🌧️"),
    71 => array("Slight snow fall", "🌨️"),
    73 => array("Moderate snow fall", "🌨️"),
    75 => array("Heavy snow fall", "❄️"),
    77 => array("Snow grains", "🌨️"),
    80 => array("Slight rain showers", "🌦️"),
    81 => array("Moderate rain showers", "🌦️"),
    82 => array("Violent rain showers", "⛈️"),
    85 => array("Slight snow showers", "🌨️"),
    86 => array("Heavy snow showers", "🌨️"),
    95 => array("Thunderstorm", "⛈️"),
    96 => array("Thunderstorm with slight hail", "⛈️"),
    99 => array("Thunderstorm with heavy hail", "⛈️"),
);

function weatherInfo(int $code): array
{
    $info = WEATHER_CODES[$code] ?? array("Unknown", "❔");
    return array("text" => $info[0], "icon" => $info[1]);
}
?>
