<?php
require_once __DIR__ . "/../../../common/config.php";
require_once "helpers.php";

$action = $_GET["action"] ?? "";
$pdo = getPdo();
$statuses = array("want", "reading", "read");

function format(array $row): array
{
    return array(
        "id"     => (int)$row["idBook"],
        "key"    => $row["dtKey"],
        "title"  => $row["dtTitle"],
        "author" => $row["dtAuthor"],
        "year"   => $row["dtYear"] === null ? null : (int)$row["dtYear"],
        "cover"  => $row["dtCoverId"] ? "https://covers.openlibrary.org/b/id/" . $row["dtCoverId"] . "-M.jpg" : null,
        "status" => $row["dtStatus"],
        "rating" => (int)$row["dtRating"]
    );
}

try
{
    switch ($action)
    {
        case "search":
            // external: Open Library → our own format
            requireMethod("GET");
            $q = trim($_GET["q"] ?? "");
            if (mb_strlen($q) < 2)
            {
                sendError("Enter at least 2 characters");
            }
            $data = getJson(API_BOOKS . "?" . http_build_query(array(
                "q" => $q, "fields" => "key,title,author_name,first_publish_year,cover_i", "limit" => 12
            )));
            $books = array();
            foreach ($data["docs"] as $d)
            {
                $books[] = array(
                    "key"     => $d["key"],
                    "title"   => $d["title"],
                    "author"  => isset($d["author_name"]) ? implode(", ", $d["author_name"]) : "unknown",
                    "year"    => $d["first_publish_year"] ?? null,
                    "coverId" => $d["cover_i"] ?? null,
                    "cover"   => isset($d["cover_i"]) ? "https://covers.openlibrary.org/b/id/" . $d["cover_i"] . "-M.jpg" : null
                );
            }
            sendJson($books);

        case "list":
            // internal: the shelf in the database
            requireMethod("GET");
            $rows = $pdo->query("SELECT * FROM tblBook ORDER BY dtTitle")->fetchAll();
            sendJson(array_map("format", $rows));

        case "add":
            requireMethod("POST");
            $key = $_POST["key"] ?? "";
            if ($key === "" || trim($_POST["title"] ?? "") === "")
            {
                sendError("key and title are required");
            }
            $check = $pdo->prepare("SELECT COUNT(*) FROM tblBook WHERE dtKey = ?");
            $check->execute([$key]);
            if ($check->fetchColumn() > 0)
            {
                sendError("This book is already on your shelf", 409);      // 409 Conflict
            }
            $stmt = $pdo->prepare("INSERT INTO tblBook (dtKey, dtTitle, dtAuthor, dtYear, dtCoverId, dtStatus, dtRating)
                                   VALUES (?, ?, ?, ?, ?, 'want', 0)");
            $stmt->execute([$key, $_POST["title"], $_POST["author"] ?? "", ($_POST["year"] ?? "") === "" ? null : (int)$_POST["year"],
                            ($_POST["coverId"] ?? "") === "" ? null : (int)$_POST["coverId"]]);
            $row = $pdo->query("SELECT * FROM tblBook WHERE idBook = " . (int)$pdo->lastInsertId())->fetch();
            sendJson(format($row), 201);

        // TODO: case "update" (POST): id, status (want / reading / read), rating (0-5)
        //       → check the values, UPDATE the row, return the formatted book (404 if it does not exist)

        case "delete":
            requireMethod("POST");
            $stmt = $pdo->prepare("DELETE FROM tblBook WHERE idBook = ?");
            $stmt->execute([(int)($_POST["id"] ?? 0)]);
            if ($stmt->rowCount() === 0)
            {
                sendError("Book not found", 404);
            }
            sendJson(array("deleted" => (int)$_POST["id"]));

        default:
            sendError("Unknown action – use search, list, add, update or delete");
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
