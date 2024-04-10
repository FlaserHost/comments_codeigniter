<?php

namespace App\Controllers;
require_once 'functions.php';

use App\Models\comments\CommentsModel;
use App\Models\comments\UsersModel;

class Comments extends BaseController
{
    public function index()
    {
        try {
            $commentsModel = new CommentsModel();
            $count = $commentsModel->findAll();
            $comments = $commentsModel->getPagination(3);

            $dataArray = [];

            if (count($count) > 0) {
                $dataArray = [
                    'comments' => $comments['comments'],
                    'pages' => $comments['pager'],
                    'no_records' => false
                ];
            } else {
                $dataArray = [
                    'no_records' => true
                ];
            }

            return view('comments/index', $dataArray);
        } catch (\Exception $err) {
            die($err->getMessage());
        }
    }

    public function modal()
    {
        return view('comments/modal');
    }

    public function modalParts()
    {
        $request = $this->request;

        try {
            if ($request->isAJAX()) {
                $current_fields = "<div class='pass'>
                        <input class='entry-field email-field validation' id='email-field' name='email_field' placeholder='Электронная почта*' type='email' required>
                    </div>
                    <div class='pass'>
                        <input class='entry-field password-field validation' id='password-field' name='password_field' placeholder='Пароль*' type='password' required>
                        <i id='eye' class='fa-solid fa-sharp fa-eye-slash'></i>
                    </div>";

                if ($request->getPost('modalAction')) {
                    $action = $request->getPost("modalAction");

                    if ($action === 'Регистрация') {
                        $current_fields .= "<div class='reg-fields'>
                            <input class='reg-field validation' id='first-name-field' name='first_name' placeholder='Имя*' type='text' required>
                            <input class='reg-field validation' id='second-name-field' name='second_name' placeholder='Фамилия*' type='text' required>
                        </div>
                        <button class='reg-btn' id='reg-btn' type='submit'>Зарегистрироваться</button>";
                    } else {
                        $current_fields .= "<button class='entry-btn' id='entry-btn' type='submit'>Войти</button>";
                    }
                }

                echo $current_fields;
            }
        } catch(\Exception $err) {
            die($err->getMessage());
        }

        die;
    }

    public function auth()
    {
        try {
            if ($this->request->isAJAX() && $this->request->getPost('loginData')) {
                $postData = $this->request->getPost('loginData');

                $transform = [
                    0 => $postData[0]['value'],
                    1 => $postData[1]['value']
                ];

                if (emptyCheck($transform)) {
                    $email = $postData[0]['value'];
                    $password = $postData[1]['value'];

                    $db = db_connect();
                    $sql = "SELECT id_user, first_name, password FROM users WHERE email = :email:";
                    $query = $db->query($sql, [
                        'email' => $email,
                    ]);

                    if ($query->getResult()) {
                        $user = $query->getRow();

                        if (password_verify($password, $user->password)) {
                            $userIdent = [
                                'status' => true,
                                'user_id' => $user->id_user,
                                'userName' => $user->first_name
                            ];

                            session()->set("currentUser", $userIdent);
                            echo json_encode($userIdent);
                        }
                    }
                }
            }
        } catch (\Exception $err) {
            die($err->getMessage());
        }
    }

    public function logout()
    {
        session()->remove("currentUser");
    }

    public function sort()
    {
        $orderBy = '';
        $sortType = '';

        try {
            if ($this->request->isAJAX() && $this->request->getPost('sortData')) {
                $postData = $this->request->getPost('sortData');

                $parametr = $postData['parametr'];
                $orderBy = "comments.$parametr";
                $sortType = $postData['sortType'];

                $savedParams = [
                    'orderBy' => $orderBy,
                    'sortType' => $sortType
                ];

                session()->set('savedSort', $savedParams);
            } else {
                $savedparams = session()->get('savedSort');
                $parametr = $savedparams['orderBy'];
                $orderBy = "comments.$parametr";
                $sortType = $savedparams['sortType'];
            }

            $commentsModel = new CommentsModel();
            $comments = $commentsModel->orderBy($orderBy, $sortType)->getPagination(3);

            $dataArray = [
                'comments' => $comments['comments'],
                'pages' => $comments['pager'],
            ];

            return view('comments/sorted_view', $dataArray);
        }
        catch(\Exception $err) {
            die($err->getMessage());
        }
    }

    public function add()
    {
        try {
            if ($this->request->isAJAX() && $this->request->getPost("postData")) {
                $user_id = session()->get("currentUser")["user_id"];
                $commentBody = $this->request->getPost("postData")[0]["value"];
                $commentDate = date("Y-m-d H:i:s");

                $comments = new CommentsModel();
                $comments->insert([
                    'id_user' => $user_id,
                    'comment' => $commentBody,
                    'created_at' => $commentDate
                ]);

                $newMaxID = $comments->getInsertID();
                $count = $comments->findAll();

                $users = new UsersModel();
                $userName = $users->select('first_name')->where('id_user', $user_id)->first();

                $newComment = [
                    'user_id' => $user_id,
                    'userName' => $userName['first_name'],
                    'commentID' => $newMaxID,
                    'commentBody' => $commentBody,
                    'commentDate' => $commentDate,
                    'commentsCount' => count($count)
                ];

                echo json_encode($newComment);
                die;
            }
        } catch(\Exception $err) {
            die($err->getMessage());
        }
    }

    public function DelEdit()
    {
        try {
            if ($this->request->isAJAX() && $this->request->getPost("actionData")) {
                $commentBody = '';
                $postData = $this->request->getPost("actionData");

                $action = $postData["commentAction"];
                $commentID = $postData["commentNamespace"];

                if (isset($postData["commentBody"])) {
                    $commentBody = $postData["commentBody"];
                }

                $comments = new CommentsModel();

                if ($action === 'Редактирование') {
                    $editDate = date("Y-m-d H:i:s");

                    $arrayData = [
                        'comment' => $commentBody,
                        'updated_at' => $editDate,
                    ];

                    $comments->set($arrayData);
                    $comments->where('id_comment', $commentID);
                    $comments->update();

                    $actionResult = [
                        'commentID' => $commentID,
                        'commentBody' => $commentBody,
                        'editDate' => $editDate
                    ];

                    echo json_encode($actionResult);
                    die;
                } else if ($action === 'Удаление') {
                    $comments->where('id_comment', $commentID);
                    $comments->delete();

                    echo json_encode($commentID);
                    die;
                }

                die;
            }
        } catch(\Exception $err) {
            die($err->getMessage());
        }
    }

    public function registration()
    {
        try {
            if ($this->request->isAJAX() && $this->request->getPost("registrationData")) {

                $postData = $this->request->getPost("registrationData");
                $transform = [
                    0 => $postData[0]["value"],
                    1 => $postData[1]["value"],
                    2 => $postData[2]["value"],
                    3 => $postData[3]["value"]
                ];

                if (emptyCheck($transform)) {
                    $email = $postData[0]["value"];

                    $password = $postData[1]["value"];
                    $password_hash = password_hash($password, PASSWORD_BCRYPT);
                    $firstName = $postData[2]["value"];
                    $secondName = $postData[3]["value"];

                    $users = new UsersModel();
                    $users->insert([
                        'first_name' => $firstName,
                        'second_name' => $secondName,
                        'email' => $email,
                        'password' => $password_hash,
                    ]);

                    if ($users->errors()) {
                        echo json_encode($users->errors());
                    }
                } else {
                    echo json_encode(['empty_form' => 'Заполните форму корректно']);
                }

                die;
            }
        } catch(\Exception $err) {
            die($err->getMessage());
        }
    }
}
