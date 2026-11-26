<?php
// Provided proxy (see chapter 3): current weather for the given coordinates
require_once __DIR__ . "/../../common/config.php";
require_once __DIR__ . "/../../common/weathercodes.php";
header("Content-Type: application/json");

try
{
    $data = getJson(API_FORECAST . "?" . http_build_query(array(
        "latitude"  => $_GET["lat"] ?? 49.61,
        "longitude" => $_GET["lon"] ?? 6.13,
        "current"   => "temperature_2m,weather_code,wind_speed_10m"
    )));
    $info = weatherInfo($data["current"]["weather_code"]);
    echo json_encode(array(
        "temperature" => $data["current"]["temperature_2m"],
        "wind"        => $data["current"]["wind_speed_10m"],
        "text"        => $info["text"],
        "icon"        => $info["icon"]
    ), JSON_UNESCAPED_UNICODE);
}
catch (Exception $e)
{
    http_response_code(502);
    echo json_encode(array("error" => $e->getMessage()));
}
?>
