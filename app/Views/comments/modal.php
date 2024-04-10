<?php
/** @var $action */
?>
<div class="modal-area">
    <div class="modal">
        <i id="xmark" class="fa-solid fa-xmark"></i>
        <div class="modal-content">
            <div>
                <a href="#" data-modal-action="Авторизация" class="tab active-tab">Войти</a>
                <a href="#" data-modal-action="Регистрация" class="tab">Зарегистрироваться</a>
            </div>
            <div class="form">
                <?= form_open('#', ['class' => 'login-form', 'id' => 'login-form']) ?>
                    <div class='pass'>
                        <input class='entry-field email-field validation' id='email-field' name='email_field' placeholder='Электронная почта*' type='email' aria-label="email" required>
                    </div>
                    <div class='pass'>
                        <input class='entry-field password-field validation' id='password-field' name='password_field' placeholder='Пароль*' type='password' aria-label="password" required>
                        <i id='eye' class='fa-solid fa-sharp fa-eye-slash'></i>
                    </div>
                    <button class='entry-btn' id='entry-btn' type='submit'>Войти</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
