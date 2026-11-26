<?php
require_once __DIR__ . "/../../common/config.php";
require_once "fetch.php";
header("Content-Type: application/json");

$urls = array(
    "ok"       => API_FORECAST . "?latitude=49.61&longitude=6.13&current=temperature_2m",
    "badparam" => API_FORECAST . "?current=temperature_2m",
    "notfound" => API_FORECAST . "x",                 // wrong path
    "nohost"   => "http://no-such-server.invalid/data.json",
    "nojson"   => SITES . "/news/"
);

// TODO: call fetchJson($urls[$case]) in a try ... catch block
// TODO: success → {"success": true, "temperature": ...}
// TODO: error   → status 502 and {"success": false, "error": "..."}
?>
