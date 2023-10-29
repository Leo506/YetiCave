<form class="form form--add-lot container <?= empty($errors) ? "" : "form--invalid" ?>" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?= get_form_item_error_class($errors, 'lot-name') ?>"> <!-- form__item--invalid -->
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value=<?= $data['lotName'] ?>>
            <span class="form__error"><?= get_error_text($errors, 'lot-name') ?></span>
        </div>
        <div class="form__item <?= get_form_item_error_class($errors, 'category') ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category">
                <option>Выберите категорию</option>
                <?php foreach ($categories as $category) : ?>
                    <option <?= $category["name"] === $data['category'] ? "selected" : "" ?>><?= $category["name"] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?= get_error_text($errors, 'category') ?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?= get_form_item_error_class($errors, 'message') ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите описание лота"><?= $data["message"] ?></textarea>
        <span class="form__error"><?= get_error_text($errors, 'message') ?></span>
    </div>
    <div class="form__item form__item--file <?= get_form_item_error_class($errors, 'img') ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="lot-img" name="img" value="<?= $data['image'] ?>">
            <label for="lot-img">
                Добавить
            </label>
            <span class="form__error"><?= get_error_text($errors, 'img') ?></span>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?= get_form_item_error_class($errors, 'lot-rate') ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value=<?= $data['startPrice'] ?>>
            <span class="form__error"><?= get_error_text($errors, 'lot-rate') ?></span>
        </div>
        <div class="form__item form__item--small <?= get_form_item_error_class($errors, 'lot-step') ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="lot-step" placeholder="0" value=<?= $data['lotStep'] ?>>
            <span class="form__error"><?= get_error_text($errors, 'lot-step') ?></span>
        </div>
        <div class="form__item <?= get_form_item_error_class($errors, 'lot-date') ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value=<?= $data['lotEndDate'] ?>>
            <span class="form__error"><?= get_error_text($errors, 'lot-date') ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>