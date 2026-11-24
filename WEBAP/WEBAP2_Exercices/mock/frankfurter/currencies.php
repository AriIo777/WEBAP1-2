<?php
/* Local MOCK of https://api.frankfurter.dev/v1/currencies */
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

echo json_encode(array(
    "AUD" => "Australian Dollar", "BGN" => "Bulgarian Lev", "BRL" => "Brazilian Real", "CAD" => "Canadian Dollar",
    "CHF" => "Swiss Franc", "CNY" => "Chinese Renminbi Yuan", "CZK" => "Czech Koruna", "DKK" => "Danish Krone",
    "EUR" => "Euro", "GBP" => "British Pound", "HKD" => "Hong Kong Dollar", "HUF" => "Hungarian Forint",
    "INR" => "Indian Rupee", "JPY" => "Japanese Yen", "KRW" => "South Korean Won", "MXN" => "Mexican Peso",
    "NOK" => "Norwegian Krone", "NZD" => "New Zealand Dollar", "PLN" => "Polish Złoty", "RON" => "Romanian Leu",
    "SEK" => "Swedish Krona", "SGD" => "Singapore Dollar", "THB" => "Thai Baht", "TRY" => "Turkish Lira",
    "USD" => "United States Dollar", "ZAR" => "South African Rand"
), JSON_UNESCAPED_UNICODE);
?>
