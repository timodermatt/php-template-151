<?php

namespace LucStr\Service\Comment;
use LucStr\Service\Comment\CommentService;

class CommentPdoService implements CommentService
{
	private $pdo;
	public function __construct(\Pdo $pdo)
	{
		$this->pdo = $pdo;
	}
	public function createComment($postId, $text, $userId){
		$stmt = $this->pdo->prepare("INSERT INTO comment VALUES(NULL, ?, ?, ?, ?)");
		$stmt->bindValue(1, $userId);
		$stmt->bindValue(2, $postId);
		$stmt->bindValue(3, date("Y-m-d H:i:s", time()));
		$stmt->bindValue(4, $text);
		$stmt->execute();
		return $this->pdo->lastInsertId();
	}
	public function getCommentsByPostId($postId){
		$stmt = $this->pdo->prepare("SELECT user.username, message, `date`  FROM comment LEFT JOIN user ON comment.userId = user.userId WHERE postId=?");
		$stmt->bindValue(1, $postId);
		$stmt->execute();
		$data = array();
		while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
			$data[] = $row;
		}
		return $data;
	}
}