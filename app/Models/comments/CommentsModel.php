<?php

namespace App\Models\comments;

use CodeIgniter\Model;

class CommentsModel extends Model
{
    protected $table = 'comments';

    protected $useTimestamps = true;

    protected $allowedFields = ['id_user', 'comment', 'created_at'];

    public function getPagination($pages)
    {
        $this->builder()
            ->select('comments.*, users.*')
            ->join('users', 'comments.id_user = users.id_user');

        return [
            'comments'  => $this->paginate($pages),
            'pager' => $this->pager,
        ];
    }
}