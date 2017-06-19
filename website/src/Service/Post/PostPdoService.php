<?php

namespace LucStr\Service\Post;
use LucStr\Service\Post\PostService;

class PostPdoService implements PostService
{
	private $pdo;
	public function __construct(\Pdo $pdo)
	{
		$this->pdo = $pdo;
	}
	public function createPost($title, $text, $picture, $userId){
		$stmt = $this->pdo->prepare("INSERT INTO post VALUES(NULL, ?, ?, ?, ?, ?, ?)");
		$stmt->bindValue(1, $title);
		$stmt->bindValue(2, file_get_contents($picture['tmp_name']));
		$stmt->bindValue(3, $picture['type']);
		$stmt->bindValue(4, $text);
		$stmt->bindValue(5, date("Y-m-d H:i:s", time()));
		$stmt->bindValue(6, $userId);
		$stmt->execute();
		return $this->pdo->lastInsertId();
	}
	public function getAllPosts(){
		$stmt = $this->pdo->prepare("SELECT p.postId, p.text, p.title, p.userId, date, COUNT(l.likeId) AS likes FROM post p LEFT JOIN `like` l on l.postId=p.postId GROUP BY p.postId");
		$stmt->execute();
		$data = array();
		while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
			$data[] = $row;
		}
		return $data;
	}
	public function getPostById($postId){
		$stmt = $this->pdo->prepare("SELECT * FROM post WHERE postId = ?");
		$stmt->bindValue(1, $postId);
		$stmt->execute();
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}
}