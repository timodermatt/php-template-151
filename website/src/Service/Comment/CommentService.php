<?php
namespace timodermatt\Service\Comment;

interface CommentService
{
	public function createComment($postId, $text, $userId);
	public function getCommentsByPostId($postId);
}