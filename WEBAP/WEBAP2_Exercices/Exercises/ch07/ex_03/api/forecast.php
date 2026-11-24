<?php
// GET forecast.php?lat=..&lon=.. → 7 days {date, weekday, min, max, rain, text, icon}
require_once __DIR__ . "/../../../common/config.php";
require_once __DIR__ . "/../../../common/weathercodes.php";
require_once "helpers.php";

try
{
    $data = getJson(API_FORECAST . "?" . http_build_query(array(
        "latitude"  => $_GET["lat"] ?? 0,
        "longitude" => $_GET["lon"] ?? 0,
        "daily"     => "temperature_2m_max,temperature_2m_min,precipitation_sum,weather_code",
        "timezone"  => "auto"
    )));
}
catch (Exception $e)
{
    sendError($e->getMessage(), 502);
}

$days = array();
foreach ($data["daily"]["time"] as $i => $date)
{
    $info = weatherInfo($data["daily"]["weather_code"][$i]);
    $days[] = array(
        "date"    => date("d/m", strtotime($date)),
        "weekday" => date("l", strtotime($date)),
        "min"     => $data["daily"]["temperature_2m_min"][$i],
        "max"     => $data["daily"]["temperature_2m_max"][$i],
        "rain"    => $data["daily"]["precipitation_sum"][$i],
        "text"    => $info["text"],
        "icon"    => $info["icon"]
    );
}
sendJson($days);
?>
