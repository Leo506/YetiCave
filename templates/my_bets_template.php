<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($bets as $bet) : ?>
            <tr class="rates__item">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="../<?= $bet['image'] ?>" alt="" width="54" height="40">
                    </div>
                    <h3 class="rates__title"><a href="/lot.php?id=<?=$bet['lotId']?>"><?= $bet['lotName'] ?></a></h3>
                </td>
                <td class="rates__category">
                    <?= $bet['category'] ?>
                </td>
                <td class="rates__timer">
                    <?=get_lot_timer($bet['end_date'])?>
                </td>
                <td class="rates__price">
                    <?= formatPrice($bet['summ']) ?>
                </td>
                <td class="rates__time">
                    <?= get_past_time_string($bet['date']) ?> назад
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>