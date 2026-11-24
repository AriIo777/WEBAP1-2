<?php
require_once __DIR__ . "/../../../common/config.php";
require_once "helpers.php";

$action = $_GET["action"] ?? "";
$pdo = getPdo();

// TODO: function format(array $row): array
// TODO: function findTodo(PDO $pdo, int $id): array
// TODO: switch ($action): list, get, add, update, toggle, delete
?>
