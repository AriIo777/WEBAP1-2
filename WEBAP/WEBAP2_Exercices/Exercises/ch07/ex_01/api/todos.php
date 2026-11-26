<?php
require_once __DIR__ . "/../../../common/config.php";
require_once "helpers.php";

$action = $_GET["action"] ?? "";
$pdo = getPdo();

// one todo as the client sees it (our own field names, not the column names)
function format(array $row): array
{
    return array(
        "id"      => (int)$row["idTodo"],
        "title"   => $row["dtTitle"],
        "done"    => (bool)$row["dtDone"],
        "created" => $row["dtCreated"]
    );
}

function findTodo(PDO $pdo, int $id): array
{
    $stmt = $pdo->prepare("SELECT * FROM tblTodo WHERE idTodo = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row)
    {
        sendError("Todo $id not found", 404);
    }
    return $row;
}



try
{
    switch ($action)
    {
        case "list":
            requireMethod("GET");
            $rows = $pdo->query("SELECT * FROM tblTodo ORDER BY dtDone, dtCreated DESC")->fetchAll();
            sendJson(array_map("format", $rows));

        case "get":
            requireMethod("GET");
            sendJson(format(findTodo($pdo, (int)($_GET["id"] ?? 0))));

        case "add":
            requireMethod("POST");
            $title = trim($_POST["title"] ?? "");
            if ($title === "")
            {
                sendError("The title is required");
            }
            $stmt = $pdo->prepare("INSERT INTO tblTodo (dtTitle, dtDone, dtCreated) VALUES (?, 0, ?)");
            $stmt->execute([$title, date("Y-m-d H:i:s")]);
            sendJson(format(findTodo($pdo, (int)$pdo->lastInsertId())), 201);

        case "update":
            requireMethod("POST");
            $id = (int)($_POST["id"] ?? 0);
            $title = trim($_POST["title"] ?? "");
            findTodo($pdo, $id);                       // 404 if it does not exist
            if ($title === "")
            {
                sendError("The title is required");
            }
            $pdo->prepare("UPDATE tblTodo SET dtTitle = ? WHERE idTodo = ?")->execute([$title, $id]);
            sendJson(format(findTodo($pdo, $id)));

        case "toggle":
            requireMethod("POST");
            $id = (int)($_POST["id"] ?? 0);
            $todo = findTodo($pdo, $id);
            $pdo->prepare("UPDATE tblTodo SET dtDone = ? WHERE idTodo = ?")->execute([$todo["dtDone"] ? 0 : 1, $id]);
            sendJson(format(findTodo($pdo, $id)));

        case "delete":
            requireMethod("POST");
            $id = (int)($_POST["id"] ?? 0);
            findTodo($pdo, $id);
            $pdo->prepare("DELETE FROM tblTodo WHERE idTodo = ?")->execute([$id]);
            sendJson(array("deleted" => $id));

        default:
            sendError("Unknown action – use list, get, add, update, toggle or delete");
    }
}
catch (PDOException $e)
{
    sendError("Database error: " . $e->getMessage(), 500);
}
?>
