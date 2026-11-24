<?php
require_once "helpers.php";

$file = __DIR__ . "/../data/notes.json";

function loadNotes(string $file): array
{
    if (!file_exists($file))
    {
        return array();
    }
    return json_decode(file_get_contents($file), true) ?? array();
}

function saveNotes(string $file, array $notes): void
{
    file_put_contents($file, json_encode(array_values($notes), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$action = $_GET["action"] ?? "";
$notes = loadNotes($file);

switch ($action)
{
    case "list":
        requireMethod("GET");
        sendJson($notes);

    case "add":
        requireMethod("POST");
        $title = trim($_POST["title"] ?? "");
        $text  = trim($_POST["text"] ?? "");
        if ($title === "")
        {
            sendError("The title is required");
        }
        $note = array(
            "id"      => uniqid(),
            "title"   => $title,
            "text"    => $text,
            "created" => date("Y-m-d H:i")
        );
        $notes[] = $note;
        saveNotes($file, $notes);
        sendJson($note, 201);                 // 201 Created

    case "delete":
        requireMethod("POST");
        $id = $_POST["id"] ?? "";
        $remaining = array_filter($notes, function($n) use ($id) { return $n["id"] !== $id; });
        if (count($remaining) === count($notes))
        {
            sendError("Note not found", 404);
        }
        saveNotes($file, $remaining);
        sendJson(array("deleted" => $id));

    case "update":
        requireMethod("POST");
        $id = $_POST["id"] ?? "";
        $title = trim($_POST["title"] ?? "");
        if ($title === "")
        {
            sendError("The title is required");
        }
        foreach ($notes as $i => $n)
        {
            if ($n["id"] === $id)
            {
                $notes[$i]["title"] = $title;
                $notes[$i]["text"] = trim($_POST["text"] ?? "");
                $notes[$i]["changed"] = date("Y-m-d H:i");
                saveNotes($file, $notes);
                sendJson($notes[$i]);
            }
        }
        sendError("Note not found", 404);

    default:
        sendError("Unknown action '" . $action . "' – use list, add, update or delete");
}
?>
