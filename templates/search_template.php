<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= $_GET['search'] ?></span>»</h2>
        <?php if (empty($lots)) : ?>
            <h3>Ничего не найдено по вашему запросу</h3>
        <?php else : ?>
            <ul class="lots__list">
                <?php foreach ($lots as $lot) {
                    echo include_template("lot_card.php", [
                        "lot" => $lot
                    ]);
                } ?>
            </ul>
        <?php endif; ?>
    </section>
    <?php
    $pagginationUrlFunction = function (int $page): string {
        return "/search.php?search=" . $_GET['search'] . '&' . 'page=' . $page;
    };
    echo include_template("pagination_list.php", [
        "pagesCount" => $pagesCount,
        "create_paggination_url" => $pagginationUrlFunction
    ]);
    ?>

</div>