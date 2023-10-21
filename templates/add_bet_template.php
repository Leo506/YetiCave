<?php
if (!is_auth())
    return;
if ($_SESSION['userId'] === $lotInfo['authorId'])
    return;
if ($_SESSION['userId'] === $lotInfo['lastBettedUserId'])
    return;
?>

<div class="lot-item__state">
    <?= get_lot_timer($lotInfo["end_date"], ['lot-item__timer timer']) ?>
    <div class="lot-item__cost-state">
        <div class="lot-item__rate">
            <span class="lot-item__amount">Текущая цена</span>
            <span class="lot-item__cost"><?= formatPrice($maxBet === 0 ? $lotInfo['start_price'] : $maxBet) ?></span>
        </div>
        <div class="lot-item__min-cost">
            Мин. ставка <span><?= formatPrice($maxBet === 0 ? $lotInfo['start_price'] : $lotInfo["step"] + $maxBet) ?></span>
        </div>
    </div>
    <form class="lot-item__form" action="lot.php?id=<?= $lotInfo["id"] ?>" method="post" autocomplete="off">
        <p class="lot-item__form-item form__item  <?= empty($error) ? "" : "form__item--invalid" ?>">
            <label for="cost">Ваша ставка</label>
            <input id="cost" type="text" name="cost" placeholder="<?= $maxBet === 0 ? $lotInfo['start_price'] : $lotInfo["step"] + $maxBet ?>" value="<?= $_POST['cost'] ?? "" ?>">
            <span class="form__error"><?= $error ?></span>
        </p>
        <button type="submit" class="button">Сделать ставку</button>
    </form>
</div>