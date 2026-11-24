<?php
/*
 * Local MOCK of the Open-Meteo forecast API (https://api.open-meteo.com/v1/forecast)
 * Same parameters and same JSON structure as the real API, but with generated values.
 * Used when there is no internet connection (see Exercises/common/config.php).
 *
 * Supported: latitude, longitude, current=..., daily=..., hourly=temperature_2m,
 *            timezone, forecast_days (1-16)
 */
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

if (!isset($_GET["latitude"]) || !isset($_GET["longitude"]))
{
    http_response_code(400);
    echo json_encode(array("error" => true, "reason" => "Parameter 'latitude' and 'longitude' must be set"));
    exit;
}

$lat  = round((float)$_GET["latitude"], 3);
$lon  = round((float)$_GET["longitude"], 3);
$days = max(1, min(16, (int)($_GET["forecast_days"] ?? 7)));
$tz   = $_GET["timezone"] ?? "GMT";
if ($tz === "auto")
{
    $tz = "Europe/Luxembourg";
}

// deterministic "random" numbers: same place + same day = same weather
mt_srand(crc32($lat . "/" . $lon . "/" . date("Y-m-d")));
$base = 14 - abs($lat - 45) * 0.4 + 8 * sin((date("z") - 110) / 365 * 2 * M_PI);
$codes = array(0, 1, 2, 3, 3, 45, 51, 61, 61, 63, 71, 80, 95);

$response = array(
    "latitude" => $lat,
    "longitude" => $lon,
    "generationtime_ms" => 0.05,
    "utc_offset_seconds" => 3600,
    "timezone" => $tz,
    "timezone_abbreviation" => "GMT+1",
    "elevation" => 300.0
);

// ---- current
if (!empty($_GET["current"]))
{
    $vars = explode(",", $_GET["current"]);
    $current = array("time" => date("Y-m-d\\TH:00"), "interval" => 900);
    $units = array("time" => "iso8601", "interval" => "seconds");
    foreach ($vars as $v)
    {
        switch ($v)
        {
            case "temperature_2m":
            case "apparent_temperature":
                $current[$v] = round($base + mt_rand(-40, 40) / 10, 1); $units[$v] = "°C"; break;
            case "relative_humidity_2m":
                $current[$v] = mt_rand(40, 95); $units[$v] = "%"; break;
            case "wind_speed_10m":
                $current[$v] = round(mt_rand(0, 350) / 10, 1); $units[$v] = "km/h"; break;
            case "wind_direction_10m":
                $current[$v] = mt_rand(0, 359); $units[$v] = "°"; break;
            case "precipitation":
            case "rain":
                $current[$v] = round(mt_rand(0, 30) / 10, 1); $units[$v] = "mm"; break;
            case "weather_code":
                $current[$v] = $codes[mt_rand(0, count($codes) - 1)]; $units[$v] = "wmo code"; break;
            case "is_day":
                $current[$v] = (date("G") >= 7 && date("G") < 19) ? 1 : 0; $units[$v] = ""; break;
            case "cloud_cover":
                $current[$v] = mt_rand(0, 100); $units[$v] = "%"; break;
        }
    }
    $response["current_units"] = $units;
    $response["current"] = $current;
}

// ---- hourly (only temperature_2m)
if (!empty($_GET["hourly"]))
{
    $time = array();
    $temp = array();
    for ($h = 0; $h < 24 * $days; $h++)
    {
        $time[] = date("Y-m-d\\TH:00", strtotime("today") + $h * 3600);
        $temp[] = round($base + 4 * sin(($h % 24 - 9) / 24 * 2 * M_PI) + mt_rand(-10, 10) / 10, 1);
    }
    $response["hourly_units"] = array("time" => "iso8601", "temperature_2m" => "°C");
    $response["hourly"] = array("time" => $time, "temperature_2m" => $temp);
}

// ---- daily
if (!empty($_GET["daily"]))
{
    $vars = explode(",", $_GET["daily"]);
    $daily = array("time" => array());
    $units = array("time" => "iso8601");
    for ($d = 0; $d < $days; $d++)
    {
        $daily["time"][] = date("Y-m-d", strtotime("today +$d day"));
        $max = round($base + 3 + mt_rand(-50, 50) / 10, 1);
        $min = round($max - 5 - mt_rand(0, 60) / 10, 1);
        $code = $codes[mt_rand(0, count($codes) - 1)];
        $rain = $code >= 51 ? round(mt_rand(5, 150) / 10, 1) : 0.0;
        $values = array(
            "temperature_2m_max" => array($max, "°C"),
            "temperature_2m_min" => array($min, "°C"),
            "weather_code" => array($code, "wmo code"),
            "precipitation_sum" => array($rain, "mm"),
            "rain_sum" => array($rain, "mm"),
            "precipitation_probability_max" => array($code >= 51 ? mt_rand(40, 100) : mt_rand(0, 30), "%"),
            "wind_speed_10m_max" => array(round(mt_rand(50, 500) / 10, 1), "km/h"),
            "sunrise" => array(date("Y-m-d", strtotime("today +$d day")) . "T07:" . sprintf("%02d", mt_rand(30, 59)), "iso8601"),
            "sunset" => array(date("Y-m-d", strtotime("today +$d day")) . "T17:" . sprintf("%02d", mt_rand(0, 30)), "iso8601"),
            "uv_index_max" => array(round(mt_rand(0, 70) / 10, 1), ""),
        );
        foreach ($vars as $v)
        {
            if (isset($values[$v]))
            {
                $daily[$v][] = $values[$v][0];
                $units[$v] = $values[$v][1];
            }
        }
    }
    $response["daily_units"] = $units;
    $response["daily"] = $daily;
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
