<?php

require_once('helpers.php');
require_once('functions.php');
require_once('init.php');


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
        session_start();
        $_SESSION['userName'] = $user['name'];
        $_SESSION['userId'] = $user['Id'];
        header("Location: /");
        return;
    }
    print_form($connection, ["password" => "Неверный пароль"]);
}

function validate_form(): array
{
    $errors = [];
    if (!$_POST)
        return $errors;

    $requiredFields = ['email', 'password'];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field]))
            $errors[$field] = "Поле должно быть заполнено";
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'Некорректный email адрес';

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
