<?php if ($pagesCount > 1) : ?>
    <ul class="pagination-list">

        <li class="pagination-item pagination-item-prev"><a href="<?= $create_paggination_url(max(($_GET['page'] ?? 1) - 1, 1))  ?>">Назад</a></li>

        <?php for ($i = 1; $i <= $pagesCount; $i++) : ?>
            <li class="pagination-item <?= is_active_page($i) ? "pagination-item-active" : "" ?>"><a href="<?= $create_paggination_url($i) ?>"><?= $i ?></a></li>
        <?php endfor; ?>

        <li class="pagination-item pagination-item-next"><a href="<?= $create_paggination_url(min(($_GET['page'] ?? 1) + 1, $pagesCount)) ?>">Вперед</a></li>
    </ul>
<?php endif; ?>

<?php
function is_active_page(int $page): bool
{
    return ($_GET['page'] ?? 1) == $page;
}
?>