<?php $this->session = \Config\Services::session() ?>

<header class="header">
    <?php if($this->session->has("currentUser")): ?>
        <div class="user-name-block">
            <span class="current-user-Auth">Вы вошли как: &nbsp;<?= $this->session->get("currentUser")["userName"] ?></span>
        </div>
    <?php endif ?>
    <div class="lk adaptive">
        <div class="middle">
            <?php if(!$this->session->has("currentUser")): ?>
                <i class="lk-sign fa-solid fa-user"></i>
                <span class="enter current-action-span">личный кабинет</span>
            <?php else: ?>
                <i class="lk-sign fa-solid fa-door-open"></i>
                <span class="exit current-action-span">выйти</span>
                <input type="hidden" id="exit">
            <?php endif ?>
        </div>
    </div>
</header>
