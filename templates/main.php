<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $category) : ?>
            <li class="promo-item promo__item--<?= $category['code'] ?>">
                <a href="/category.php?name=<?= $category['name'] ?>" class="promo__link"><?= htmlspecialchars($category['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($lots as $lot) {
            $lotCard = include_template("lot_card.php", [
                "lot" => $lot
            ]);
            echo $lotCard;
        } ?>

    </ul>
</section>