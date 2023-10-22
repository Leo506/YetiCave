<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');

session_start();

const LIMIT = 1;

$searchString = trim($_GET['search']);

$searchResults = include_template("search_template.php", [
    "lots" => find_lots($con, $searchString, $_GET["page"] ?? 1, LIMIT),
    "pagesCount" => round(lots_count($con, $_GET["search"]) / LIMIT, PHP_ROUND_HALF_UP)
]);
print_layout($searchResults, $con);
