<?php
    /** @var $comments */
    /** @var $pages */

    $count = 0;
?>
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
        <?php if(session()->has("currentUser") && session()->get("currentUser")["status"] === true): ?>
            <?php if($comment['id_user'] === session()->get("currentUser")["user_id"]): ?>
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
