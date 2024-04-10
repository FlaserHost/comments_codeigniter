<?php
function emptyCheck($data) {
    $notEmptyFlag = false;

    foreach($data as $key => $validate) {
        if(!empty($validate[$key])) {
            $notEmptyFlag = true;
        } else {
            $notEmptyFlag = false;
            break;
        }
    }

    return $notEmptyFlag;
}

function userExist($userEmail) {
    $checkResult = false;

    $db = db_connect();
    $sql = "SELECT id_user FROM users WHERE email = :email:";
    $query = $db->query($sql, [
        'email' => $userEmail,
    ]);

    $results = $query->getResult();

    if (!empty($results)) {
        $checkResult = true;
    }

    return $checkResult;
}