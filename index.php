<?php

require_once("helpers.php");
require_once("init.php");
require_once("functions.php");

session_start();

determine_winners($con);

$main_page = include_template("main.php", [
    "categories" => get_all_categories($con),
    'lots' => get_new_lots($con),

]);

print_layout($main_page, $con, false, "container");


function determine_winners(mysqli $connection): void {
    $expiredLots = get_expired_lots($connection);
    foreach ($expiredLots as $lot) {
        $lastBet = get_last_bet_for_lot($connection, $lot['id']);
        if ($lastBet !== null)
            set_winner_for_lot($connection, $lot['id'], $lastBet['userId']);
    }
}
