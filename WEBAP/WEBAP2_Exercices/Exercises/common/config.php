<?php
/*
 * WEBAP2 – shared configuration for all exercises
 * Include it with:   require_once __DIR__ . "/../../common/config.php";
 */

// ---------------------------------------------------------------------------
// 1. External APIs
// Set OFFLINE to true when there is no internet connection (or an API is down):
// all exercises then use the local mock services in /mock, which return the
// same JSON structure as the real APIs.
// ---------------------------------------------------------------------------
const OFFLINE = false;
const MOCK = "http://localhost/mock";      // inside the Docker container, localhost = the web server

const API_FORECAST   = OFFLINE ? MOCK . "/open-meteo/forecast.php"   : "https://api.open-meteo.com/v1/forecast";
const API_GEOCODING  = OFFLINE ? MOCK . "/open-meteo/search.php"     : "https://geocoding-api.open-meteo.com/v1/search";
const API_RATES      = OFFLINE ? MOCK . "/frankfurter/latest.php"    : "https://api.frankfurter.dev/v1/latest";
const API_CURRENCIES = OFFLINE ? MOCK . "/frankfurter/currencies.php": "https://api.frankfurter.dev/v1/currencies";
const API_BOOKS      = OFFLINE ? MOCK . "/openlibrary/search.php"    : "https://openlibrary.org/search.json";

// websites for the scraping chapter (always local)
const SITES = "http://localhost/mock/sites";

// ---------------------------------------------------------------------------
// 2. Database (Docker environment)
// ---------------------------------------------------------------------------
const DB_DSN  = "mysql:host=db;dbname=webap2_db;charset=utf8mb4";
const DB_USER = "webap_user";
const DB_PASS = "webap_password";

function getPdo(): PDO
{
    static $pdo = null;      // only one connection per request
    if ($pdo === null)
    {
        $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    return $pdo;
}

// ---------------------------------------------------------------------------
// 3. Loading JSON from a URL (in ch03/ex_04 you write your own version fetchJson();
//    from chapter 4 on, you can use getJson())
// ---------------------------------------------------------------------------
function getJson(string $url, int $timeout = 10): array
{
    $context = stream_context_create(array(
        "http" => array(
            "timeout" => $timeout,
            "ignore_errors" => true,                         // also read the body of 4xx/5xx answers
            "header" => "User-Agent: WEBAP2-school-project\r\nAccept: application/json\r\n"
        )
    ));

    $text = @file_get_contents($url, false, $context);
    if ($text === false)
    {
        throw new Exception("The service could not be reached: " . $url);
    }

    // $http_response_header is filled automatically by file_get_contents()
    $status = 0;
    if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0] . " ", $m))
    {
        $status = (int)$m[1];
    }
    if ($status >= 400)
    {
        throw new Exception("The service answered with HTTP status " . $status);
    }

    $data = json_decode($text, true);
    if (!is_array($data))
    {
        throw new Exception("The answer is not valid JSON");
    }
    return $data;
}

?>
