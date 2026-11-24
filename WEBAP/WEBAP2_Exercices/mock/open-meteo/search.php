<?php
/*
 * Local MOCK of the Open-Meteo geocoding API (https://geocoding-api.open-meteo.com/v1/search)
 * Parameters: name (required), count (default 10), language (ignored), countryCode
 * Like the real API, the key "results" is MISSING when nothing was found.
 */
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$cities = array(
    array(2960316, "Luxembourg", 49.61167, 6.13, "Luxembourg", "LU", "Luxembourg", 76684, "Europe/Luxembourg"),
    array(2960596, "Esch-sur-Alzette", 49.49583, 5.98056, "Luxembourg", "LU", "Esch-sur-Alzette", 36625, "Europe/Luxembourg"),
    array(2960678, "Differdange", 49.52417, 5.89139, "Luxembourg", "LU", "Esch-sur-Alzette", 27822, "Europe/Luxembourg"),
    array(2960503, "Dudelange", 49.48056, 6.0875, "Luxembourg", "LU", "Esch-sur-Alzette", 21291, "Europe/Luxembourg"),
    array(2960657, "Ettelbruck", 49.8475, 6.10417, "Luxembourg", "LU", "Diekirch", 9100, "Europe/Luxembourg"),
    array(2960656, "Diekirch", 49.86778, 6.15583, "Luxembourg", "LU", "Diekirch", 7100, "Europe/Luxembourg"),
    array(2960534, "Echternach", 49.81212, 6.41746, "Luxembourg", "LU", "Grevenmacher", 5600, "Europe/Luxembourg"),
    array(2960024, "Wiltz", 49.96622, 5.93243, "Luxembourg", "LU", "Diekirch", 7000, "Europe/Luxembourg"),
    array(2960198, "Remich", 49.545, 6.36694, "Luxembourg", "LU", "Grevenmacher", 3700, "Europe/Luxembourg"),
    array(2960096, "Strassen", 49.62056, 6.07333, "Luxembourg", "LU", "Luxembourg", 10000, "Europe/Luxembourg"),
    array(2950159, "Berlin", 52.52437, 13.41053, "Germany", "DE", "Land Berlin", 3426354, "Europe/Berlin"),
    array(2821164, "Trier", 49.75565, 6.63935, "Germany", "DE", "Rheinland-Pfalz", 110636, "Europe/Berlin"),
    array(2867714, "Munich", 48.13743, 11.57549, "Germany", "DE", "Bavaria", 1260391, "Europe/Berlin"),
    array(2988507, "Paris", 48.85341, 2.3488, "France", "FR", "Île-de-France", 2138551, "Europe/Paris"),
    array(2994160, "Metz", 49.11911, 6.17269, "France", "FR", "Grand Est", 123914, "Europe/Paris"),
    array(2973783, "Strasbourg", 48.58392, 7.74553, "France", "FR", "Grand Est", 274845, "Europe/Paris"),
    array(2800866, "Brussels", 50.85045, 4.34878, "Belgium", "BE", "Brussels Capital", 1019022, "Europe/Brussels"),
    array(2792073, "Liège", 50.63373, 5.56749, "Belgium", "BE", "Wallonia", 182597, "Europe/Brussels"),
    array(2643743, "London", 51.50853, -0.12574, "United Kingdom", "GB", "England", 8961989, "Europe/London"),
    array(3117735, "Madrid", 40.4165, -3.70256, "Spain", "ES", "Madrid", 3255944, "Europe/Madrid"),
    array(2267057, "Lisbon", 38.71667, -9.13333, "Portugal", "PT", "Lisbon", 517802, "Europe/Lisbon"),
    array(2735943, "Porto", 41.14961, -8.61099, "Portugal", "PT", "Porto", 249633, "Europe/Lisbon"),
    array(3169070, "Rome", 41.89193, 12.51133, "Italy", "IT", "Lazio", 2318895, "Europe/Rome"),
    array(2759794, "Amsterdam", 52.37403, 4.88969, "Netherlands", "NL", "North Holland", 741636, "Europe/Amsterdam"),
    array(2761369, "Vienna", 48.20849, 16.37208, "Austria", "AT", "Vienna", 1691468, "Europe/Vienna"),
    array(2657896, "Zurich", 47.36667, 8.55, "Switzerland", "CH", "Zurich", 341730, "Europe/Zurich"),
    array(5128581, "New York", 40.71427, -74.00597, "United States", "US", "New York", 8804190, "America/New_York"),
    array(1850147, "Tokyo", 35.6895, 139.69171, "Japan", "JP", "Tokyo", 9733276, "Asia/Tokyo"),
    array(2147714, "Sydney", -33.86785, 151.20732, "Australia", "AU", "New South Wales", 4627345, "Australia/Sydney"),
    array(3448439, "São Paulo", -23.5475, -46.63611, "Brazil", "BR", "São Paulo", 10021295, "America/Sao_Paulo"),
);

$name  = trim($_GET["name"] ?? "");
$count = max(1, min(100, (int)($_GET["count"] ?? 10)));
$cc    = strtoupper($_GET["countryCode"] ?? "");

$response = array("generationtime_ms" => 0.4);

if (mb_strlen($name) >= 2)
{
    $results = array();
    foreach ($cities as $c)
    {
        if (mb_stripos($c[1], $name) === 0 && ($cc === "" || $cc === $c[5]))
        {
            $results[] = array(
                "id" => $c[0], "name" => $c[1], "latitude" => $c[2], "longitude" => $c[3],
                "elevation" => 300.0, "feature_code" => "PPLA", "country_code" => $c[5],
                "timezone" => $c[8], "population" => $c[7], "country" => $c[4], "admin1" => $c[6]
            );
        }
    }
    usort($results, function($a, $b) { return $b["population"] <=> $a["population"]; });
    $results = array_slice($results, 0, $count);
    if (count($results) > 0)
    {
        $response["results"] = $results;
    }
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
