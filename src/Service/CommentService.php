<?php

namespace App\Service;

use App\Entity\Comment;
use Exception;

/**
 * Class CommentService
 */
class CommentService extends BaseService
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return Comment::class;
    }

    /**
     * @param Comment $comment
     *
     * @throws Exception
     */
    public function delete(Comment $comment)
    {
        parent::remove($comment);
    }
}
