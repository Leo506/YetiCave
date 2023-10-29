<?php

require_once('helpers.php');
require_once('functions.php');
require_once('init.php');

session_start();

$lotId = $_GET['id'] ?? $_POST['id'] ?? -1;

$lotInfo = get_lot_info($con, $lotId);
if (!$lotInfo) {
    not_found_response($con);
    return;
}

$formError = validate_add_bet_form($con, $lotInfo);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($formError)) {
    add_bet($con, $_POST['cost'], $_SESSION['userId'], $lotId);
    header("Location: lot.php?id=" . $lotId);
    return;
}

show_lot($con, $lotInfo, $formError);

function show_lot(mysqli $con, array $lotInfo, string $formErrors): void
{
    $addBetForm = include_template("add_bet_template.php", [
        "lotInfo" => $lotInfo,
        "error" => $formErrors,
        "maxBet" => get_max_bet($con, $lotInfo['id'])
    ]);

    $betsHistory = include_template('bet_history.php', [
        'bets' => get_bets_for_lot($con, $lotInfo['id']),
    ]);

    $lot = include_template("lot_template.php", [
        "lotInfo" => $lotInfo,
        "betForm" => $addBetForm,
        'betsHistory' => $betsHistory
    ]);

    print_layout($lot, $con);
}

function not_found_response(mysqli $con): void
{
    http_response_code(404);
    $notFound = include_template("404.php");
    print_layout($notFound, $con);
}


function validate_add_bet_form(mysqli $connection, array $lotInfo): string
{
    $INVALID_FORMAT = "Введите число";
    $BET_TOO_SMALL = "Ваша ставка слишком мала";
    $NO_ERRORS = "";
    $INPUT_REQUIRED = "Заполните поле";

    if (empty($_POST))
        return $NO_ERRORS;

    if (empty($_POST['cost']))
        return $INPUT_REQUIRED;

    if (filter_var($_POST['cost'], FILTER_VALIDATE_INT) === false)
        return $INVALID_FORMAT;

    $maxBet = get_max_bet($connection, $lotInfo['id']);
    $betValue = $_POST['cost'];
    if ($maxBet === 0 && $betValue < $lotInfo['start_price'])
        return $BET_TOO_SMALL;

    if ($betValue < $maxBet + $lotInfo['step'] && $maxBet !== 0)
        return $BET_TOO_SMALL;

    return $NO_ERRORS;
}
