<?php
require_once('helpers.php');
require_once('database_helpers.php');

function formatPrice(int $price): string
{
    $formattedString = number_format($price, thousands_separator: ' ');
    return "$formattedString ₽";
}

function get_lot_timer(string $endDate, array $defaultClasses = ['lot__timer', 'timer']): string
{
    $remainTime = get_dt_range($endDate);
    $class = $remainTime['hours'] < 24 ? 'timer--finishing' : '';
    $defaultClassesString = join(' ', $defaultClasses);
    return "<div class = \"$defaultClassesString $class\">{$remainTime['hours']}:{$remainTime['minutes']}</div>";
}

function get_dt_range(string $date): array
{
    date_default_timezone_set("Asia/Yekaterinburg");
    $lotTime = strtotime($date . "24:00:00");
    $currentTime = time();
    $remainSecs = $lotTime - $currentTime;
    $hours = floor($remainSecs / 3600);
    $minutes = floor(($remainSecs - $hours * 3600) / 60) + 1;
    return [
        "hours" => str_pad($hours, 2, "0", STR_PAD_LEFT),
        "minutes" => str_pad($minutes, 2, "0", STR_PAD_LEFT)
    ];
}

function get_new_lots(mysqli $connection): array
{
    $sql = "SELECT l.id, l.name,  l.start_price, l.image, c.name \"category\", l.end_date, c.code FROM Lot l
    INNER JOIN Category c
       ON l.categoryId = c.id
    WHERE end_date >= CURRENT_DATE
    ORDER BY creating_date DESC;";
    return fetch_array($sql, $connection);
}

function get_all_categories(mysqli $connection): array
{
    $sql = "SELECT * FROM Category;";
    return fetch_array($sql, $connection);
}

function get_lot_info(mysqli $connection, int $id): array|bool
{
    $sql = "SELECT l.id, l.name, l.creating_date, l.description, l.step, l.authorId, l.winnerId,  l.start_price, l.image, c.name \"category\", l.end_date, 
            (SELECT userId FROM Bet WHERE lotId = ? ORDER BY date DESC LIMIT 1) lastBettedUserId
            FROM Lot l
            INNER JOIN Category c
                ON l.categoryId = c.id
                WHERE l.id = ?;";

    $lots = fetch_array($sql, $connection, [$id, $id]);
    return $lots ? $lots[0] : false;
}

function add_lot(string $lotName, string $categoryName, string $description, string $imagePath, int $startPrice, int $step, string $endDate, mysqli $connection): int
{
    $categoryId = get_category_id($categoryName, $connection);
    $authorId = $_SESSION["userId"];

    $sql = "INSERT INTO Lot(name, description, image, start_price, end_date, step, authorId, categoryId)
            VALUES(?, ?, ?, ?, ?, ?, ?, ?);";

    execute_db_command($sql, $connection, [$lotName, $description, $imagePath, $startPrice, $endDate, $step, $authorId, $categoryId]);

    return get_last_lot_id($connection);
}

function get_category_id(string $categoryName, mysqli $connection): int
{
    $sql = "SELECT id FROM Category WHERE name = ?";
    return execute_scalar($sql, $connection, [$categoryName]);
}

function get_last_lot_id(mysqli $connection): int
{
    $sql = "SELECT id FROM Lot ORDER BY id DESC;";
    return execute_scalar($sql, $connection);
}


function get_user_by_email(mysqli $connection, string $email): array|bool
{
    $sql = "SELECT * FROM User WHERE email = ?";
    $user = fetch_array($sql, $connection, [$email]);
    return empty($user) ? false : $user;
}


function create_user(mysqli $connection, string $email, string $name, string $password, string $contact)
{
    $sql = "INSERT INTO User(email, name, password, contacts) VALUES (?, ?, MD5(?), ?);";
    execute_db_command($sql, $connection, [$email, $name, $password, $contact]);
}

function get_user(mysqli $connection, string $email, string $password): array|bool
{
    $sql = "SELECT * FROM User WHERE email = ? AND password = MD5(?);";
    $users = fetch_array($sql, $connection, [$email, $password]);
    return empty($users[0]) ? false : $users[0];
}

function get_user_name_from_session(): string
{
    return $_SESSION['userName'] ?? "";
}

function is_auth(): bool
{
    return isset($_SESSION['userName']);
}

function find_lots(mysqli $connection, string $searchString, int $page, int $limit): array
{
    $sql = "SELECT l.id, l.name, l.creating_date, l.description, l.image, l.start_price, l.end_date, l.step, c.name \"category\", c.code FROM Lot l
            INNER JOIN Category c ON l.categoryId = c.id
            WHERE MATCH(l.name, l.description) AGAINST(?) AND l.end_date >= CURRENT_DATE
            LIMIT ? OFFSET ?;";
    $offset = ($page - 1) * $limit;
    return fetch_array($sql, $connection, [$searchString, $limit, $offset]);
}

function lots_count(mysqli $connection, string $searchString): int
{
    $sql = "SELECT COUNT(*) FROM Lot
            WHERE MATCH(name, description) AGAINST(?) AND end_date >= CURRENT_DATE;";

    return execute_scalar($sql, $connection, [$searchString]) ?? 0;
}

function get_max_bet(mysqli $connection, int $lotId): int
{
    $sql = "SELECT summ FROM `Bet`
            WHERE lotId = ?
            ORDER BY summ DESC LIMIT 1;";
    return execute_scalar($sql, $connection, [$lotId]) ?? 0;
}

function add_bet(mysqli $connection, int $summ, int $userId, int $lotId)
{
    $sql = "INSERT INTO Bet(summ, userId, lotId) VALUES (?, ?, ?);";
    execute_db_command($sql, $connection, [$summ, $userId, $lotId]);
}

function get_bets_for_lot(mysqli $connection, int $lotId): array
{
    $sql = "SELECT b.id, b.date, b.summ, u.name FROM Bet b 
            INNER JOIN User u 
                ON b.userId = u.Id 
            WHERE b.lotId = ? 
            ORDER BY b.date DESC";
    return fetch_array($sql, $connection, [$lotId]);
}

function get_user_bets(mysqli $connection, int $userId): array
{
    $sql = "SELECT L.id 'lotId', L.image, L.name 'lotName', C.name category, L.end_date, B.date, B.summ
            FROM Bet B
                    INNER JOIN Lot L on B.lotId = L.id
                    INNER JOIN Category C on L.categoryId = C.id
            WHERE B.userId = ?;";
    return fetch_array($sql, $connection, [$userId]);
}

function get_lots_by_category_name(mysqli $connection, string $categoryName, int $page, int $limit): array
{
    $sql = "SELECT l.id, l.name, l.creating_date, l.description, l.image, l.start_price, l.end_date, l.step, c.name category, c.code FROM Lot l
            INNER JOIN Category c on l.categoryId = c.id
            WHERE c.name = ? AND l.end_date >= CURRENT_DATE
            LIMIT ? OFFSET ?;";
    $offset = ($page - 1) * $limit;
    return fetch_array($sql, $connection, [$categoryName, $limit, $offset]);
}

function lots_by_category_name_count(mysqli $connection, $categoryName): int
{
    $sql = "SELECT COUNT(*) FROM Lot l
            INNER JOIN Category c on l.categoryId = c.id
            WHERE c.name = ? AND l.end_date >= CURRENT_DATE;";
    return execute_scalar($sql, $connection, [$categoryName]);
}

function get_past_time_string(string $date): string
{
    date_default_timezone_set('Asia/Yekaterinburg');
    $diff = time() - strtotime($date);

    $days = round($diff / 60 / 60 / 24);
    if ($days > 0)
        return $days . " " . get_noun_plural_form($days, "день", "дня", "дней");

    $hours = round($diff / 60 / 60);
    if ($hours  > 0)
        return $hours . " " . get_noun_plural_form($hours, "час", "часа", "часов");

    $minutes = round($diff / 60);
    if ($minutes > 0)
        return $minutes . " " . get_noun_plural_form($minutes, "минуту", "минуты", "минут");

    $secs = round($diff);
    return $secs . " " . get_noun_plural_form($secs, "секунду", "секунды", "секунд");
}

function print_layout(string $pageContent, mysqli $connection, bool $includeTopCategoryMenu = true, string $mainSectionClass = ""): void
{
    $menu = include_template("categories_menu.php", [
        "categories" => get_all_categories($connection),
    ]);
    $layout_content = include_template("layout.php", [
        "page_title" => "Глвавная",
        "page_content" => $pageContent,
        "menu" => $menu,
        "includeTopCategoryMenu" => $includeTopCategoryMenu,
        "mainSectionClass" => $mainSectionClass
    ]);

    print($layout_content);
}
