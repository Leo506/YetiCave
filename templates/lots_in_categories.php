<div class="container">
    <section class="lots">
        <h2>Все лоты в категории <span>«<?= $_GET['name'] ?>»</span></h2>
        <?php if (empty($lots)): ?>
            <h3>Ничего не найдено по вашему запросу</h3>
        <?php else : ?>
        <ul class="lots__list">
            <?php foreach ($lots as $lot) {
                $lotCard = include_template("lot_card.php", [
                    "lot" => $lot
                ]);
                echo $lotCard;
            } ?>
        </ul>
        <?php endif; ?>
    </section>
    <?php
    $pagginationUrlFunction = function (int $page): string {
        return "/category.php?name=" . $_GET['name'] . "&page=" . $page;
    };
    $pagginationList = include_template("pagination_list.php", [
        "pagesCount" => $pagesCount,
        "create_paggination_url" => $pagginationUrlFunction
    ]);
    echo $pagginationList;
    ?>
</div>