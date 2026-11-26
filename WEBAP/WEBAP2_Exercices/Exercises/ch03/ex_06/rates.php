<?php
require_once __DIR__ . "/../../common/config.php";
header("Content-Type: application/json");

$cacheFile = __DIR__ . "/cache/rates.json";
$maxAge = 3600;

// TODO: use the cache file if it is younger than $maxAge
// TODO: otherwise load the rates and save them in the cache file
// TODO: add fromCache and cacheAge to the answer
?>
