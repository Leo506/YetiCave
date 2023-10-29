<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');

session_start();

if (!is_auth()) {
    http_response_code(403);
    return;
}

$errors = validate_form();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)) {
    create_new_lot($con);
    return;
}

print_add_form($con, $errors);

function create_new_lot(mysqli $connection)
{
    $newLotId = add_lot(
        $_POST["lot-name"],
        $_POST["category"],
        $_POST['message'],
        'uploads/' . $_FILES['img']['name'],
        $_POST["lot-rate"],
        $_POST["lot-step"],
        $_POST["lot-date"],
        $connection
    );
    header("Location: /lot.php?id=" . $newLotId);
}

function print_add_form(mysqli $connection, array $errors)
{
    $form = include_template("add_lot.php", [
        "categories" => get_all_categories($connection),
        "errors" => $errors,
        "data" => [
            "lotName" => $_POST["lot-name"] ?? "",
            "message" => $_POST['message'] ?? "",
            "startPrice" => $_POST["lot-rate"] ?? "",
            "lotStep" => $_POST["lot-step"] ?? "",
            "lotEndDate" => $_POST["lot-date"] ?? "",
            "category" => $_POST['category'] ?? "",
            "image" => $_FILES['img']['name'] ?? ""
        ]
    ]);

    print_layout($form, $connection);
}

function validate_form(): array
{
    if (!$_POST)
        return [];

    $errors = validate_lot_image([]);
    
    $requiredfields = ['lot-name', 'lot-rate', 'lot-date', 'lot-step', 'category', 'message'];
    $checks = [
        'lot-rate' => 'validate_lot_rate',
        'lot-date' => 'validate_lot_date',
        'lot-step' => 'validate_lot_step',
        'category' => 'validate_lot_category',
        'message' => 'validate_lot_description'
    ];
    foreach ($requiredfields as $field) {
        if (empty($_POST[$field] ?? "")) {
            $errors[$field] = "Поле должно быть заполнено";
            continue;
        }
        
        if (isset($checks[$field]))
            $errors = $checks[$field]($errors);
    }

    return $errors;
}

function validate_lot_image(array $errors): array
{
    if (!isset($_FILES['img']) || empty($_FILES['img']['tmp_name'])) {
        $errors['img'] = 'Загрузите изображение';
        return $errors;
    }

    $mimeType = mime_content_type($_FILES["img"]["tmp_name"]);
    if (!in_array($mimeType, ["image/jpg", "image/jpeg", "image/png"])) {
        $errors['img'] = 'Некорректный формат файла';
        return $errors;
    }

    move_uploaded_file($_FILES['img']['tmp_name'], get_file_path());
    return $errors;
}

function validate_lot_rate(array $errors): array
{
    if (!filter_var($_POST['lot-rate'], FILTER_VALIDATE_INT)) {
        $errors['lot-rate'] = 'Введите число';
        return $errors;
    }

    if ($_POST["lot-rate"] <= 0)
        $errors["lot-rate"] = "Начальная цена должна быть больше 0";

    return $errors;
}

function validate_lot_date(array $errors): array
{
    if (!is_date_valid($_POST["lot-date"])) {
        $errors["lot-date"] = "Дата окончания должна быть в формате \"ГГГГ-MM-ДД\"";
        return $errors;
    }

    if ((strtotime($_POST["lot-date"]) - time()) / 60 / 60 / 24 < 1)
        $errors["lot-date"] = "Дата окончания должна быть хотя бы на 1 день больше текущей даты";

    return $errors;
}

function validate_lot_step(array $errors): array
{
    if (filter_var($_POST['lot-step'], FILTER_VALIDATE_INT) === false) {
        $errors['lot-step'] = 'Введите число';
        return $errors;
    }
    
    if ($_POST["lot-step"] <= 0)
        $errors["lot-step"] = "Шаг ставки должен быть больше нуля";

    return $errors;
}

function validate_lot_category(array $errors): array
{
    if ($_POST["category"] === "Выберите категорию")
        $errors["category"] = "Выберите категорию";

    return $errors;
}

function validate_lot_description(array $errors): array
{
    if (strlen($_POST['message']) > 500)
        $errors['message'] = "Слишком длинное описание";
    return $errors;
}

function get_file_path(): string
{
    $fileName = $_FILES['img']['name'];
    $filePath = __DIR__ . '/uploads/';

    return $filePath . $fileName;
}
