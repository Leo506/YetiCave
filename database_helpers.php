<?php

function fetch_array(string $sql, mysqli $connection, ?array $params = null): array {
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_execute($stmt, $params);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function execute_db_command(string $sql, mysqli $connection, ?array $params = null): void {
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_execute($stmt, $params);
}

function execute_scalar(string $sql, mysqli $connection, ?array $params = null): mixed {
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_execute($stmt, $params);
    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_row($result);
    return $row ? $row[0] : null;
}