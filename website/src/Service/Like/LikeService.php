<?php
namespace LucStr\Service\Like;

interface LikeService
{
	public function likeExists($postId, $userId);
	public function removeLikeByPostAndUserId($postId, $userId);
	public function addLike($postId, $userId);
	public function likeCount($postId);
}