<?php
/*
 * Local MOCK of the Frankfurter API v1 (https://api.frankfurter.dev/v1/latest)
 * Parameters: base (default EUR), symbols (comma separated list)
 */
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// reference rates: 1 EUR = ...
$eur = array(
    "AUD" => 1.6512, "BGN" => 1.9558, "BRL" => 6.1834, "CAD" => 1.5123, "CHF" => 0.9378, "CNY" => 7.8441,
    "CZK" => 24.315, "DKK" => 7.4589, "GBP" => 0.8563, "HKD" => 8.4521, "HUF" => 389.12, "INR" => 95.734,
    "JPY" => 167.45, "KRW" => 1512.3, "MXN" => 21.045, "NOK" => 11.732, "NZD" => 1.8412, "PLN" => 4.2718,
    "RON" => 4.9765, "SEK" => 11.214, "SGD" => 1.4567, "THB" => 37.812, "TRY" => 45.876, "USD" => 1.0864,
    "ZAR" => 20.134, "EUR" => 1.0
);

$base = strtoupper($_GET["base"] ?? "EUR");
if (!isset($eur[$base]))
{
    http_response_code(404);
    echo json_encode(array("message" => "not found"));
    exit;
}

$symbols = isset($_GET["symbols"]) && $_GET["symbols"] !== "" ? explode(",", strtoupper($_GET["symbols"])) : array_keys($eur);

$rates = array();
foreach ($eur as $code => $value)
{
    if ($code !== $base && in_array($code, $symbols))
    {
        $rates[$code] = round($value / $eur[$base], 5);
    }
}
ksort($rates);

echo json_encode(array(
    "amount" => 1.0,
    "base" => $base,
    "date" => date("Y-m-d", strtotime("yesterday")),
    "rates" => $rates
));
?>
