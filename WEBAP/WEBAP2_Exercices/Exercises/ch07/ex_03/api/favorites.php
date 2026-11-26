<?php
require_once __DIR__ . "/../../../common/config.php";
require_once __DIR__ . "/../../../common/weathercodes.php";
require_once "helpers.php";

$action = $_GET["action"] ?? "";
$pdo = getPdo();

function format(array $row): array
{
    return array(
        "id"        => (int)$row["idFavorite"],
        "name"      => $row["dtName"],
        "country"   => $row["dtCountry"],
        "latitude"  => (float)$row["dtLatitude"],
        "longitude" => (float)$row["dtLongitude"]
    );
}

try
{
    switch ($action)
    {
        case "list":
            requireMethod("GET");
            $rows = $pdo->query("SELECT * FROM tblFavorite ORDER BY dtName")->fetchAll();
            sendJson(array_map("format", $rows));

        case "add":
            // the client only sends the name: the SERVER finds the coordinates (external service)
            requireMethod("POST");
            $name = trim($_POST["name"] ?? "");
            if ($name === "")
            {
                sendError("The name is required");
            }
            $geo = getJson(API_GEOCODING . "?" . http_build_query(array("name" => $name, "count" => 1, "language" => "en")));
            if (!isset($geo["results"]))
            {
                sendError("City '$name' not found", 404);
            }
            $p = $geo["results"][0];
            $stmt = $pdo->prepare("INSERT INTO tblFavorite (dtName, dtCountry, dtLatitude, dtLongitude) VALUES (?, ?, ?, ?)");
            $stmt->execute([$p["name"], $p["country"] ?? "", $p["latitude"], $p["longitude"]]);
            sendJson(array("id" => (int)$pdo->lastInsertId(), "name" => $p["name"], "country" => $p["country"] ?? "",
                           "latitude" => $p["latitude"], "longitude" => $p["longitude"]), 201);

        case "delete":
            requireMethod("POST");
            $stmt = $pdo->prepare("DELETE FROM tblFavorite WHERE idFavorite = ?");
            $stmt->execute([(int)($_POST["id"] ?? 0)]);
            if ($stmt->rowCount() === 0)
            {
                sendError("Favorite not found", 404);
            }
            sendJson(array("deleted" => (int)$_POST["id"]));

        case "weather":
            // internal data (database) + external data (Open-Meteo) combined in one answer
            requireMethod("GET");
            $rows = $pdo->query("SELECT * FROM tblFavorite ORDER BY dtName")->fetchAll();
            $result = array();
            foreach ($rows as $row)
            {
                $fav = format($row);
                try
                {
                    $w = getJson(API_FORECAST . "?" . http_build_query(array(
                        "latitude" => $fav["latitude"], "longitude" => $fav["longitude"],
                        "current" => "temperature_2m,weather_code"
                    )));
                    $info = weatherInfo($w["current"]["weather_code"]);
                    $fav["temperature"] = $w["current"]["temperature_2m"];
                    $fav["text"] = $info["text"];
                    $fav["icon"] = $info["icon"];
                }
                catch (Exception $e)
                {
                    $fav["temperature"] = null;       // one failed request must not break the whole answer
                    $fav["text"] = "not available";
                    $fav["icon"] = "❔";
                }
                $result[] = $fav;
            }
            sendJson($result);

        default:
            sendError("Unknown action – use list, add, delete or weather");
    }
}
catch (PDOException $e)
{
    sendError("Database error: " . $e->getMessage(), 500);
}
catch (Exception $e)
{
    sendError($e->getMessage(), 502);
}
?>
