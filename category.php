<?php
require_once('functions.php');
require_once('init.php');

session_start();

const LIMIT = 1;

$lotsIntCategory = include_template("lots_in_categories.php", [
    "lots" => get_lots_by_category_name($con, $_GET['name'], $_GET['page'] ?? 1, LIMIT),
    "pagesCount" => round(lots_by_category_name_count($con, $_GET['name']) / LIMIT, PHP_ROUND_HALF_UP)
]);

print_layout($lotsIntCategory, $con);
