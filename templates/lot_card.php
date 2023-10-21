<li class="lots__item lot">
    <div class="lot__image">
        <img src="../<?= htmlspecialchars($lot["image"]) ?>" width="350" height="260" alt="<?= $lot['code'] ?>">
    </div>
    <div class="lot__info">
        <span class="lot__category"><?= htmlspecialchars($lot["category"]) ?></span>
        <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?= htmlspecialchars($lot['id']) ?>"><?= htmlspecialchars($lot['name']) ?></a></h3>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">Стартовая цена</span>
                <span class="lot__cost"><?= htmlspecialchars(formatPrice($lot['start_price'])) ?></span>
            </div>
            <?= get_lot_timer(htmlspecialchars($lot["end_date"])) ?>
        </div>
    </div>
</li>