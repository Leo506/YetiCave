<div class="history">
    <h3>История ставок (<span><?= count($bets) ?></span>)</h3>
    <table class="history__list">
        <?php foreach ($bets as $bet) : ?>
            <tr class="history__item">
                <td class="history__name"><?= $bet['name'] ?></td>
                <td class="history__price"><?= format_price($bet['summ']) ?></td>
                <td class="history__time"><?= get_past_time_string($bet['date']) ?> назад</td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>