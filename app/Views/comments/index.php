<?php
    /** @var $comments */
    /** @var $pages */
    /** @var $no_records */
    /** @var $count */

    $count = 0;
?>

<?php $this->extend('layouts/default') ?>

<?php $this->section('title') ?>Комментарии<?php $this->endSection() ?>

<?php $this->section('content') ?>
    <div class="comments">
        <?php if(!$no_records): ?>
            <div class="sort">
                <div>
                    <span>Сортировка:</span>
                    <a href="#" class="sort-link" data-parametr="created_at" data-sort-type="DESC">по дате <i class="fa-solid fa-arrow-up-long"></i></a>&nbsp;
                    <a href="#" class="sort-link" data-parametr="id_user" data-sort-type="DESC">по пользователям <i class="fa-solid fa-arrow-up-long"></i></a>
                </div>
            </div>
            <?php foreach($comments as $comment): ?>
                <?php $count++ ?>
                <div id="comment_<?= $comment['id_comment'] ?>" class="comment comment-number-<?= $count ?>" data-comment-namespace="<?= $comment['id_comment'] ?>" data-comment-unamespace="<?= $comment['id_user'] ?>">
                    <div class="comment-info">
                        <div class="avatar">
                            <figure>
                                <i class="fa-solid fa-user"></i>
                            </figure>
                        </div>
                        <div class="full-name">
                            <span title="Имя пользователя"><?= esc($comment['first_name']) ?></span>
                        </div>
                        <div class="comment-date">
                            <time title="Дата и время добавления комментария"><?= esc($comment['created_at']) ?></time>
                        </div>
                    </div>
                    <div class="comment-body">
                        <p><?= esc($comment['comment']) ?></p>
                    </div>
                    <?php if (session()->has("currentUser") && session()->get("currentUser")["status"] === true): ?>
                        <?php if ($comment['id_user'] === session()->get("currentUser")["user_id"]): ?>
                            <div class="control-panel">
                                <div class="edit-comment">
                                    <i data-comment-namespace="<?= $comment['id_comment'] ?>" data-comment-action="Редактирование" class="fa-solid fa-pencil"></i>
                                </div>
                                <div class="delete-comment">
                                    <i data-comment-namespace="<?= $comment['id_comment'] ?>" data-comment-action="Удаление" class="fa-solid fa-trash"></i>
                                </div>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            <?php endforeach ?>
            <?= $pages->links() ?>
        <?php else: ?>
            <div class='empty-field'>
                <p>На данный момент, нет ни одного комментария.<br>
                    Оставьте его первым
                </p>
            </div>
        <?php endif ?>
        <div class="comment-input">
            <?= form_open('Comments/sendComment', ['id' => 'comment-form']) ?>
                <?php
                    if (session()->has("currentUser") && session()->get("currentUser")["status"] === true) {
                        $placeholder = '';
                        $disabled = 'value';
                    } else {
                        $placeholder = 'Авторизируйтесь, чтобы оставить комментарий';
                        $disabled = 'disabled';
                    }
                ?>
                <textarea class="comment-box" id="comment-box" name="comment" placeholder="<?= $placeholder ?>" <?= $disabled ?> aria-label="comment"></textarea>
                <div class="btn-block">
                    <button class="send-btn" id="send-btn" type="submit" <?= $disabled ?>>Отправить</button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
<?php $this->endSection() ?>
