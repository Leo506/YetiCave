<section class="lot-item container">
    <h2><?= $lotInfo['name'] ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="../<?= $lotInfo['image'] ?>" width="730" height="548" alt="<?= $lotInfo['category'] ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= $lotInfo['category'] ?></span></p>
            <p class="lot-item__description"><?= $lotInfo['description'] ?></p>
        </div>
        <div class="lot-item__right">
            <?= $betForm ?>
            <?= $betsHistory ?>
        </div>
    </div>
</section>