const deledit = actionData => {
    $.ajax({
        url: 'DelEdit',
        type: 'POST',
        dataType: 'JSON',
        data: ({actionData}),
        success: data => {
            try {
                if (actionData.commentAction === 'Редактирование') {
                    $(`#comment_${data.commentID} .comment-body p`).html(data.commentBody);
                    $(`#comment_${data.commentID} .comment-info .comment-date time`).html(data.editDate);
                    $("#comment-box").val('');
                    $(".buttons-wrapper").remove();
                    $(".btn-block").append("<button type='submit' id='send-btn' class='send-btn'>Отправить</button>");
                } else if (actionData.commentAction === 'Удаление') {
                    $(`#comment_${data}`).remove();
                    let lastComment = $(".comment").length;

                    if (lastComment === 0 && $(".pagination").length > 0) {
                        let activeLeftBrother = $("li.active").index();

                        if ($(`.pagination li:nth-child(${activeLeftBrother})`).hasClass('prev') !== true) {
                            location.href = $(`.pagination li:nth-child(${activeLeftBrother}) a`).attr("href");
                        } else {
                            location.href = $(`.pagination li:nth-child(${activeLeftBrother + 1}) a`).attr("href");
                        }
                    } else if(lastComment > 0) {
                        let i = 1;
                        $(".comment").each(() => {
                            $(this).addClass(`comment-number-${i}`);
                            i++;
                        });
                    } else {
                        let emptyField = `<div class='empty-field'>
                             <p>На данный момент, нет ни одного комментария.<br>
                                    Оставьте его первым
                             </p>
                        </div>`;

                        $(".sort").remove();
                        $(".comments").prepend(emptyField);
                    }
                }
            } catch(err) {
                alert("Возникла ошибка! Информация в консоли.");
                console.log(err);
            }
        },
        error: msg => {
            alert("Возникла ошибка ajax. Подробности в консоли.");
            console.error(msg.responseText);
        }
    });
}

$(document).ready(() => {
    const body = $('body');
    $(".lk").click(() =>{
        let actionCheck = $("#exit").length;

        if (actionCheck === 0) {
            $.ajax({
                url: 'modal',
                success: res => {
                    body.prepend(res);
                    body.css("overflow", "hidden");
                    $("header").hide();
                },
                error: msg => {
                    alert("Возникла ошибка ajax. Подробности в консоли.");
                    console.log(msg.responseText);
                }
            });
        } else {
            $.ajax({
                url: 'logout',
                success: () => {
                    try {
                        const currentAction = $(".current-action-span");
                        const commentBox = $("#comment-box");

                        $(".lk-sign").toggleClass("fa-user fa-door-open");
                        currentAction.toggleClass("exit enter");
                        currentAction.html("личный кабинет");
                        $(".user-name-block").remove();
                        $(".control-panel").remove();
                        $("#exit").remove();
                        commentBox.attr("placeholder", "Авторизируйтесь, чтобы оставить комментарий");
                        commentBox.prop("disabled", true);
                        $("#send-btn").prop("disabled", true);
                        $(".lk").addClass("adaptive");
                    } catch(err) {
                        alert("Возникла ошибка! Информация в консоли.");
                        console.log(err);
                    }
                },
                error: msg => {
                    alert("Возникла ошибка ajax. Подробности в консоли.");
                    console.log(msg.responseText);
                }
            });
        }
    });

    body.on("click", ".tab", function(){
        $(".tab").removeClass("active-tab");
        $(this).addClass("active-tab");
        let modalAction = $(this).data("modal-action");

        $.ajax({
            url: 'modalParts',
            type: 'POST',
            dataType: 'html',
            data: ({modalAction}),
            success: res => {
                $(".form > form > *").remove();
                $(".form > form").append(res);
            },
            error: msg => {
                alert("Возникла ошибка ajax. Подробности в консоли.");
                console.log(msg.responseText);
            }
        });
    });

    body.on("click", "#eye", function(){
        $(this).toggleClass("fa-eye fa-sharp fa-eye-slash");
        const passField = $("#password-field");

        if (passField.attr("type") === 'password') {
            passField.attr("type", "text");
        } else {
            passField.attr("type", "password");
        }
    });

    body.on("click", "#xmark", () => {
        $(".modal-area").remove();
        $("body").css("overflow", "auto");
        $("header").css("display", "flex");
    });

    body.on("click", "#entry-btn", function(e){
        e.preventDefault();
        let formData = $("#login-form").serializeArray();

        $.ajax({
            url: 'auth',
            type: 'POST',
            dataType: 'JSON',
            data: ({loginData: formData}),
            success: data => {
                try {
                    const currentAction = $(".current-action-span");

                    $(".modal-area").remove();
                    $(".lk-sign").toggleClass("fa-user fa-door-open");
                    currentAction.toggleClass("enter exit");
                    currentAction.html("выйти");
                    $(".lk").removeClass("adaptive");
                    $("body").css("overflow", "auto");
                    $(".middle").append('<input type="hidden" id="exit" name="exit" value="1">');

                    const commentLength = $(".comment").length;
                    let commentNamespaces;

                    for (let i = 1; i <= commentLength; i++) {
                        commentNamespaces = $(`.comment-number-${i}`).data();

                        if (commentNamespaces.commentUnamespace === data.user_id) {
                            let insert = `<div class="control-panel">
                                <div class="edit-comment">
                                     <i data-comment-namespace="${commentNamespaces.commentNamespace}" data-comment-action="Редактирование" class="fa-solid fa-pencil"></i>
                                </div>
                                <div class="delete-comment">
                                     <i data-comment-namespace="${commentNamespaces.commentNamespace}" data-comment-action="Удаление" class="fa-solid fa-trash"></i>
                                </div>
                            </div>`;

                            $(`.comment-number-${i}`).append(insert);
                        }
                    }

                    const commentBox = $("#comment-box");
                    commentBox.attr("placeholder", "");
                    commentBox.prop("disabled", false);
                    $("header").css('display', 'flex');

                    let logged = `<div class="user-name-block">
                        <span class="current-user-auth">Вы вошли как: &nbsp; ${data.userName}</span>
                    </div>`;

                    $(".lk").before(logged);
                    $("#send-btn").prop("disabled", false);
                } catch(err) {
                    alert("Возникла ошибка! Информация в консоли.");
                    console.log(err);
                }
            },
            error: msg => {
                let numberField = 0;
                let checkField = 0;

                $(".validation").each(function(){
                    if ($(this).val() === '') {
                        $(this).css("borderColor", "red");
                        checkField++;
                    }

                    numberField++;
                });

                if (checkField > 0) {
                    setTimeout(() => {
                        alert("Введите логин/пароль для авторизации.");
                    }, 100);
                } else if(msg.responseText === '') {
                    alert("Либо данного пользователя не существует,\nлибо Вы ввели неправильный email/пароль.");
                } else {
                    alert("Возникла ошибка! Информация в консоли.");
                    console.log(msg.responseText);
                }
            }
        });
    });

    body.on("click", ".sort-link", function(){
        let sortData = {
            'parametr': $(this).attr("data-parametr"),
            'sortType': $(this).attr("data-sort-type")
        };

        $.ajax({
            url: 'sort',
            type: 'POST',
            dataType: 'HTML',
            data: ({sortData}),
            success: res => {
                try {
                    $(".comment").remove();
                    $(".sort").after(res);

                    if(sortData.sortType === "DESC") {
                        $(this).attr("data-sort-type", "ASC");
                    } else {
                        $(this).attr("data-sort-type", "DESC");
                    }

                    $(this).children("i").toggleClass("fa-arrow-up-long fa-arrow-down-long");
                } catch(err) {
                    alert("Возникла ошибка! Информация в консоли.");
                    console.error(err);
                }
            },
            error: msg => {
                alert("Возникла ошибка ajax. Подробности в консоли.");
                console.error(msg.responseText);
            }
        });
    });

    body.on("click", "#send-btn", function(e){
        e.preventDefault();
        let formData = $("#comment-form").serializeArray();

        $.ajax({
            url: 'add',
            type: 'POST',
            dataType: 'JSON',
            data: ({postData: formData}),
            success: data => {
                try {
                    const commentsCountOnPage = $(".comment").length;
                    if ((commentsCountOnPage < 3 && (data.commentsCount - 1) % 3 !== 0) || commentsCountOnPage === 0) {
                        let sort = `<div class="sort">
                                        <div>
                                            <span>Сортировка:</span>
                                            <a href="#" class="sort-link" data-parametr="created_at" data-sort-type="DESC">по дате <i class="fa-solid fa-arrow-up-long"></i></a>&nbsp;
                                            <a href="#" class="sort-link" data-parametr="user_id" data-sort-type="DESC">по пользователям <i class="fa-solid fa-arrow-up-long"></i></a>
                                        </div>
                                </div>`;

                        let insertComment = `<div id="comment_${data.commentID}" class="comment comment-number-${commentsCountOnPage + 1}" data-comment-namespace="${data.commentID}" data-comment-unamespace="${data.user_id}">
                            <div class="comment-info">
                                <div class="avatar">
                                    <figure>
                                        <i class="fa-solid fa-user"></i>
                                    </figure>
                                </div>
                                <div class="full-name">
                                    <span title="Имя пользователя">${data.userName}</span>
                                </div>
                                <div class="comment-date">
                                    <time title="Дата и время добавления комментария">${data.commentDate}</time>
                                </div>
                            </div>
                            <div class="comment-body">
                                <p>${data.commentBody}</p>
                            </div>
                            <div class="control-panel">
                                <div class="edit-comment">
                                    <i data-comment-namespace="${data.commentID}" data-comment-action="Редактирование" class="fa-solid fa-pencil"></i>
                                </div>
                                <div class="delete-comment">
                                    <i data-comment-namespace="${data.commentID}" data-comment-action="Удаление" class="fa-solid fa-trash"></i>
                                </div>
                            </div>`;

                        if (commentsCountOnPage > 0) {
                            $(`.comment:nth-child(${commentsCountOnPage + 1})`).after(insertComment);
                        } else {
                            $('.empty-field').remove();
                            $('.comments').prepend(sort);
                            $('.sort').after(insertComment);
                        }
                    } else if (commentsCountOnPage === 3 && (data.commentsCount - 1) % 3 !== 0) {
                        $("#comment-box").val('');
                        alert("Комментарий успешно добавлен");
                        return 0;
                    } else {
                        const pagination = $(".pagination").length;
                        if (pagination !== 0) {
                            const pagesCount = $(".pagination > li").length - 1;
                            let pagePath = `/comments/index?page=${pagesCount}&amp;per-page=3`;

                            let insert = `<li>
                                <a href="${pagePath}" data-page="${pagesCount - 1}">${pagesCount}</a>
                            </li>`;

                            $(`.pagination > li:nth-child(${pagesCount})`).after(insert);

                            const next = $("li.next");

                            if (next.hasClass("disabled")) {
                                next.css("cursor", "pointer");
                                next.removeClass("disabled");
                                $("li.next a").css({
                                    "color": "#3498db",
                                    "cursor": "pointer"
                                });
                                $("li.next span").remove();
                                next.append(`<a href="${pagePath}" data-page="${pagesCount - 1}">»</a>`);
                            }

                            alert("Комментарий успешно добавлен");
                        } else {
                            const pages = `<ul class="pagination">
                                <li class="prev disabled"><span>«</span></li>
                                <li class="active">
                                    <a href="/comments/index?page=1&amp;per-page=3" data-page="0">1</a>
                                </li>
                                <li>
                                    <a href="/comments/index?page=2&amp;per-page=3" data-page="1">2</a>
                                </li>
                                <li class="next">
                                    <a href="/comments/index?page=2&amp;per-page=3" data-page="1">»</a>
                                </li>
                            </ul>`;

                            $(".comment:nth-child(4)").after(pages);
                        }
                    }

                    $("#comment-box").val('');
                } catch(err) {
                    alert("Возникла ошибка! Информация в консоли.");
                    console.error(err);
                }
            },
            error: msg => {
                alert("Возникла ошибка ajax. Подробности в консоли.");
                console.error(msg.responseText);
            }
        });
    });

    body.on("click", ".control-panel > div .fa-solid", function(){
        const actionInfo = $(this).data();

        try {
            if(actionInfo.commentAction === 'Редактирование') {
                $(this).addClass('orange');
                let text = $(`#comment_${actionInfo.commentNamespace} .comment-body p`).html();

                if ($("#edit-send-btn").length === 0) {
                    let btns = `<div class="buttons-wrapper">
                              <button id="cancel-edit" class="cancel-edit">Отменить</button>
                            <button type='submit' id='edit-send-btn' class='send-btn' data-comment-action="${actionInfo.commentAction}" data-comment-namespace="${actionInfo.commentNamespace}">Сохранить</button>
                        </div>`;

                    $(".btn-block").append(btns);
                    $("#send-btn").remove();
                } else {
                    $("#edit-send-btn").attr("data-comment-namespace", actionInfo.commentNamespace);
                }

                $("#comment-box").val(text);
            } else if (actionInfo.commentAction === 'Удаление') {
                let accept = confirm("Точно желаете удалить Ваш комментарий?");
                if (accept) {
                    deledit(actionInfo);
                } else {
                    return 0;
                }
            }
        } catch(err) {
            alert("Возникла ошибка! Информация в консоли.");
            console.error(err);
        }
    });

    body.on("click", "#edit-send-btn", function(e){
        e.preventDefault();
        try {
            let editInfo = $(this).data();
            editInfo.commentBody = $("#comment-box").val();
            deledit(editInfo);
            $(".fa-pencil").removeClass("orange");
        } catch(err) {
            alert("Возникла ошибка! Информация в консоли.");
            console.error(err);
        }
    });

    body.on("click", "#cancel-edit", function(){
        $(".buttons-wrapper").remove();
        $(this).remove();
        $(".fa-pencil").removeClass("orange");
        $("#comment-box").val('');
        $(".btn-block").append("<button type='submit' id='send-btn' class='send-btn'>Отправить</button>");
    });

    body.on("click", "#reg-btn", function(e){
        e.preventDefault();
        let regData = $("#login-form").serializeArray();

        setTimeout(() => {
            $.ajax({
                url: 'registration',
                type: 'POST',
                data: ({registrationData: regData}),
                success: data => {
                    let counter = 0;

                    try {
                        $(".validation").each(function(){
                            if ($(this).val() === '') {
                                $(this).css("borderColor", "red");
                            }

                            counter++;
                        });

                        if (data !== '') {
                            const object = JSON.parse(data);
                            const message = Object.values(object).join(', ');
                            setTimeout(() => {
                                alert(message);
                            }, 100);
                        } else {
                            $(".modal-area").remove();
                            $(".header").css("display", "flex");
                            setTimeout(() => {
                                alert('Вы успешно зарегистрировались. Осуществите вход');
                            }, 100);
                        }
                    } catch(err) {
                        alert("Возникла ошибка! Информация в консоли.");
                        console.error(err);
                    }
                },
                error: msg => {
                    alert("Возникла ошибка ajax. Подробности в консоли.");
                    console.error(msg.responseText);
                }
            });
        }, 150);
    });

    body.on("blur", ".validation", function(){
        if ($(this).val() === '') {
            $(this).css("borderColor", "red");
        } else {
            setTimeout(() => {
                if ($(this).parent().siblings(".invalid-feedback").html()) {
                    $(this).css("borderColor", "red");
                } else {
                    $(this).css("borderColor", "green");
                }
            }, 200);
        }
    });
});