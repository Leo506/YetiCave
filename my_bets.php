<?php

require_once('helpers.php');
require_once('functions.php');
require_once('init.php');

session_start();
if (!is_auth()) {
    header("Location: /");
    return;
}

$myBets = get_user_bets($con, $_SESSION['userId']);

$betsList = include_template('my_bets_template.php', [
    'bets' => $myBets
]);

print_layout($betsList, $con);
