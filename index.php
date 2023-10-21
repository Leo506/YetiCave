<?php

require_once("helpers.php");
require_once("init.php");
require_once("functions.php");

session_start();

$main_page = include_template("main.php", [
    "categories" => get_all_categories($con),
    'lots' => get_new_lots($con),

]);

print_layout($main_page, $con, false, "container");
