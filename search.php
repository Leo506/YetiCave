<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');

session_start();

const LIMIT = 1;

$searchResults = include_template("search_template.php", [
    "lots" => find_lots($con, $_GET["search"], $_GET["page"] ?? 1, LIMIT),
    "pagesCount" => round(lots_count($con, $_GET["search"]) / LIMIT, PHP_ROUND_HALF_UP)
]);
print_layout($searchResults, $con);