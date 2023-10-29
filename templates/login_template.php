<form class="form container <?= empty($errors) ? "" : "form--invalid" ?>" action="login.php" method="post"> <!-- form--invalid -->
  <h2>Вход</h2>
  <div class="form__item <?= get_form_item_error_class($errors, 'email') ?>"> <!-- form__item--invalid -->
    <label for="email">E-mail <sup>*</sup></label>
    <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $email ?>">
    <span class="form__error"><?= get_error_text($errors, 'email') ?></span>
  </div>
  <div class="form__item form__item--last <?= get_form_item_error_class($errors, 'password') ?>">
    <label for="password">Пароль <sup>*</sup></label>
    <input id="password" type="password" name="password" placeholder="Введите пароль">
    <span class="form__error"><?= get_error_text($errors, 'password') ?></span>
  </div>
  <button type="submit" class="button">Войти</button>
</form>