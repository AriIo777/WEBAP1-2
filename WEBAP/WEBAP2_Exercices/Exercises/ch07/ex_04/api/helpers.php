<?php
/*
 * helpers.php – functions used by all endpoints of the API
 */

// send data as JSON and stop the script
function sendJson($data, int $status = 200): void
{
    http_response_code($status);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode(array("success" => true, "data" => $data), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// send an error message as JSON and stop the script
function sendError(string $message, int $status = 400): void
{
    http_response_code($status);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode(array("success" => false, "error" => $message), JSON_UNESCAPED_UNICODE);
    exit;
}

// stop with status 405 if the request does not use the expected HTTP method
function requireMethod(string $method): void
{
    if ($_SERVER["REQUEST_METHOD"] !== $method)
    {
        sendError("Method " . $_SERVER["REQUEST_METHOD"] . " not allowed, use " . $method, 405);
    }
}
?>
