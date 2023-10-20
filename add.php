<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');

session_start();

if (!is_auth()) {
    http_response_code(403);
    return;
}

$errors = validateForm();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)) {
    create_new_lot($con);
    return;
}

print_add_form($con, $errors);

function create_new_lot(mysqli $connection) {
    $newLotId = add_lot($_POST["lot-name"],
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

function print_add_form(mysqli $connection, array $errors) {
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

function validateForm() : array {
    $errors = [];
    if (!$_POST)
        return $errors;

    if (!$_POST["lot-name"])
        $errors["lot-name"] = "Lot name must be entered";

    if (isset($_FILES['img']) && !empty($_FILES["img"]["tmp_name"])) {
        $mimeType = mime_content_type($_FILES["img"]["tmp_name"]);
        if (!in_array($mimeType, ["image/jpg", "image/jpeg", "image/png"]))
            $errors["img"] = "Incorrect file format";
        else 
            move_uploaded_file($_FILES['img']['tmp_name'], get_file_path());
    }
    else
        $errors["img"] = "Image is required";


    if (!filter_var($_POST['lot-rate'], FILTER_VALIDATE_INT))
        $errors['lot-rate'] = 'Enter a number';
    else if ($_POST["lot-rate"] <= 0)
        $errors["lot-rate"] = "Start price must be more than zero";

    if(!is_date_valid($_POST["lot-date"]))
        $errors["lot-date"] = "End date must be in \"YYYY-mm-dd\" format";
    if ((strtotime($_POST["lot-date"]) - time())/60/60/24 < 1)
        $errors["lot-date"] = "End date must be more than current date at least by 1 day";

    if (!filter_var($_POST['lot-step'], FILTER_VALIDATE_INT))
        $errors['lot-step'] = 'Enter a number';
    else if ($_POST["lot-step"] <= 0)
        $errors["lot-step"] = "Lot step must be more than zero";

    if ($_POST["category"] === "Выберите категорию")
        $errors["category"] = "Select category";

    if (!$_POST['message'])
        $errors['message'] = "Description must be entered";

    return $errors;
}

function get_file_path(): string {
    $fileName = $_FILES['img']['name'];
    $filePath = __DIR__ . '/uploads/';

    return $filePath . $fileName;
}