<?php
namespace LucStr\Service\Like;

interface LikeService
{
	public function likeExists($postId, $userId);
	public function removeLikeById($likeId);
	public function addLike($postId, $userId);
	public function likeCount($postId);
}