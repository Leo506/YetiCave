<?php

function create_paggination_url(int $page): string {
    return "/search.php?search=" . $_GET['search'] . '&' . 'offset=' . $page;
}

function is_active_page(int $page): bool {
    return ($_GET['offset'] ?? 1) == $page;
}

?>


<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= $_GET['search'] ?></span>»</h2>
        <?php if (empty($lots)) : ?>
            <h3>Ничего не найдено по вашему запросу</h3>
        <?php else : ?>
            <ul class="lots__list">
                <?php foreach ($lots as $lot) : ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="../<?= $lot["image"] ?>" width="350" height="260" alt="<?= $lot['code'] ?>">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= $lot["category"] ?></span>
                            <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?= $lot['id'] ?>"><?= $lot['name'] ?></a></h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?= formatPrice($lot['start_price']) ?></span>
                                </div>
                                <?=get_lot_timer($lot["end_date"])?>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
    <?php if ($pagesCount > 1) : ?>
        <ul class="pagination-list">

            <li class="pagination-item pagination-item-prev"><a href="/search.php?search=<?= create_paggination_url(max(($_GET['offset'] ?? 1) - 1, 1))  ?>">Назад</a></li>

            <?php for ($i = 1; $i <= $pagesCount; $i++) : ?>
                <li class="pagination-item <?= is_active_page($i) ? "pagination-item-active" : "" ?>"><a href=<?= create_paggination_url($i) ?>><?= $i ?></a></li>
            <?php endfor; ?>

            <li class="pagination-item pagination-item-next"><a href="/search.php?search=<?= create_paggination_url(min(($_GET['offset'] ?? 1) + 1, $pagesCount)) ?>">Вперед</a></li>
        </ul>
    <?php endif; ?>
</div>