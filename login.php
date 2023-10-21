<?php

require_once('helpers.php');
require_once('functions.php');
require_once('init.php');


session_start();

$errors = validate_form($con);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)) {
    authenticate_user($con);
    return;
}
print_form($con, $errors);

function authenticate_user(mysqli $connection)
{
    $user = get_user($connection, $_POST['email'], $_POST['password']);
    if ($user) {
        $_SESSION['userName'] = $user['name'];
        $_SESSION['userId'] = $user['Id'];
        header("Location: /");
        return;
    }
    print_form($connection, ["password" => "Incorrect password"]);
}

function validate_form(): array
{
    $errors = [];
    if (!$_POST)
        return $errors;

    $requiredFields = ['email', 'password'];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field]))
            $errors[$field] = "Field must be filled";
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'Enter a valid email';

    return $errors;
}


function print_form(mysqli $connection, array $errors)
{
    $form = include_template("login_template.php", [
        "errors" => $errors,
        "email" => $_POST['email'] ?? "",
    ]);

    print_layout($form, $connection);
}
