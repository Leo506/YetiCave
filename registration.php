<?php

require_once('helpers.php');
require_once('functions.php');
require_once('init.php');

session_start();
if (is_auth()) {
    header("Location: /");
    return;
}

$errors = validate_form($con);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)) {
    create_user($con, $_POST['email'], $_POST['name'], $_POST['password'], $_POST['message']);
    header("Location: /login.php");
    return;
}

print_form($con, $errors, [
    "email" => $_POST['email'] ?? "",
    "name" => $_POST['name'] ?? "",
    "message" => $_POST['message'] ?? "",
]);


function validate_form(mysqli $connection): array
{
    $errors = [];
    if (!$_POST)
        return $errors;

    $requiredFields = ['email', 'password', 'name', 'message'];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field]))
            $errors[$field] = "Field must be filled";
    }
    
    return validate_email_address($connection, $_POST['email'], $errors);
}

function validate_email_address(mysqli $connection, string $email, array $errors): array
{
    if (isset($errors['email']))
        return $errors;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email';
        return $errors;
    }

    if (is_user_already_exists($connection, $email))
        $errors['email'] = 'There is user with same email';
    
    return $errors;
}


function print_form(mysqli $connection, array $errors, array $data)
{
    $form = include_template("registration_template.php", [
        "errors" => $errors,
        "data" => $data
    ]);
    print_layout($form, $connection);
}
