<?php
/*
 * Local MOCK of the Open Library search API (https://openlibrary.org/search.json)
 * Parameters: q, title, author, limit (default 100), page, fields (ignored)
 * Covers are not available offline: cover_i is set, but the images need internet.
 */
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$books = array(
    array("/works/OL27448W", "The Lord of the Rings", array("J.R.R. Tolkien"), 1954, 14625765, 165),
    array("/works/OL262758W", "The Hobbit", array("J.R.R. Tolkien"), 1937, 14627509, 189),
    array("/works/OL1168083W", "Nineteen Eighty-Four", array("George Orwell"), 1949, 9267242, 116),
    array("/works/OL1168007W", "Animal Farm", array("George Orwell"), 1945, 11261770, 172),
    array("/works/OL82563W", "Harry Potter and the Philosopher's Stone", array("J. K. Rowling"), 1997, 10521270, 302),
    array("/works/OL45804W", "Fantastic Mr Fox", array("Roald Dahl"), 1970, 6498519, 90),
    array("/works/OL45883W", "Charlie and the Chocolate Factory", array("Roald Dahl"), 1964, 8236348, 91),
    array("/works/OL3140822W", "The Hitchhiker's Guide to the Galaxy", array("Douglas Adams"), 1979, 11464000, 214),
    array("/works/OL17930368W", "Dune", array("Frank Herbert"), 1965, 11481354, 188),
    array("/works/OL15413843W", "The Name of the Wind", array("Patrick Rothfuss"), 2007, 8231992, 115),
    array("/works/OL59863W", "Pride and Prejudice", array("Jane Austen"), 1813, 14348537, 211),
    array("/works/OL468431W", "The Great Gatsby", array("F. Scott Fitzgerald"), 1925, 10590366, 217),
    array("/works/OL3335245W", "The Martian", array("Andy Weir"), 2011, 11446816, 102),
    array("/works/OL1892617W", "Clean Code", array("Robert C. Martin"), 2008, 8510941, 99),
    array("/works/OL2733417W", "JavaScript: The Good Parts", array("Douglas Crockford"), 2008, 6261212, 92),
    array("/works/OL17357863W", "Learning PHP, MySQL & JavaScript", array("Robin Nixon"), 2009, 8237893, 105),
    array("/works/OL45794W", "Matilda", array("Roald Dahl"), 1988, 12722633, 102),
    array("/works/OL893415W", "Frankenstein", array("Mary Shelley"), 1818, 12356249, 208),
    array("/works/OL81613W", "It", array("Stephen King"), 1986, 14659034, 162),
    array("/works/OL5735363W", "The Little Prince", array("Antoine de Saint-Exupéry"), 1943, 10708272, 193),
);

$q      = mb_strtolower(trim($_GET["q"] ?? ""));
$title  = mb_strtolower(trim($_GET["title"] ?? ""));
$author = mb_strtolower(trim($_GET["author"] ?? ""));
$limit  = max(1, min(100, (int)($_GET["limit"] ?? 100)));
$page   = max(1, (int)($_GET["page"] ?? 1));

$docs = array();
foreach ($books as $b)
{
    $authors = mb_strtolower(implode(" ", $b[2]));
    $t = mb_strtolower($b[1]);
    $ok = ($q === "" || str_contains($t, $q) || str_contains($authors, $q))
       && ($title === "" || str_contains($t, $title))
       && ($author === "" || str_contains($authors, $author));
    if ($ok && ($q !== "" || $title !== "" || $author !== ""))
    {
        $docs[] = array("key" => $b[0], "title" => $b[1], "author_name" => $b[2],
                        "first_publish_year" => $b[3], "cover_i" => $b[4], "edition_count" => $b[5]);
    }
}

$found = count($docs);
$docs = array_slice($docs, ($page - 1) * $limit, $limit);

echo json_encode(array(
    "numFound" => $found,
    "start" => ($page - 1) * $limit,
    "numFoundExact" => true,
    "num_found" => $found,
    "q" => $q,
    "offset" => null,
    "docs" => $docs
), JSON_UNESCAPED_UNICODE);
?>
