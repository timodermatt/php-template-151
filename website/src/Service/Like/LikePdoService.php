<?php

namespace LucStr\Service\Like;
use LucStr\Service\Like\LikeService;

class LikePdoService implements LikeService
{
	private $pdo;
	public function __construct(\Pdo $pdo)
	{
		$this->pdo = $pdo;
	}
	public function likeExists($postId, $userId){
		$stmt = $this->pdo->prepare("SELECT * FROM `like` WHERE postId = ? AND userId = ?");
		$stmt->bindValue(1, $postId);
		$stmt->bindValue(2, $userId);
		$stmt->execute();
		return $stmt->rowCount() > 0;
	}
	public function removeLikeByPostAndUserId($postId, $userId){
		$stmt = $this->pdo->prepare("DELETE FROM `like` WHERE postId = ? AND userId = ?");
		$stmt->bindValue(1, $postId);
		$stmt->bindValue(2, $userId);
		$stmt->execute();
		return $stmt->rowCount();
	}
	public function addLike($postId, $userId){
		$stmt = $this->pdo->prepare("INSERT INTO `like` VALUES(NULL, ?, ?)");
		$stmt->bindValue(1, $userId);
		$stmt->bindValue(2, $postId);
		$stmt->execute();
		return $this->pdo->lastInsertId();
	}
	public function likeCount($postId){		
		$stmt = $this->pdo->prepare("SELECT COUNT(likeId) AS likes FROM `like` WHERE postId=? GROUP BY postId");
		$stmt->bindValue(1, $postId);
		$stmt->execute();
		return $stmt->fetch(\PDO::FETCH_ASSOC)["likes"];
	}
}