<?php

namespace LucStr\Service\User;
use LucStr\Service\User\UserService;

class UserPdoService implements UserService
{
	private $pdo;
	private $salt = "peterpeter";
	public function __construct(\Pdo $pdo)
	{
		$this->pdo = $pdo;
	}

	public function register($username, $email, $password){
		$stmt = $this->pdo->prepare("INSERT INTO user VALUES(NULL, ?, ?, ?, 0, ?)");
		$stmt->bindValue(1, $username);
		$stmt->bindValue(2, $email);
		$stmt->bindValue(3, $this->hashpass($password));
		$stmt->bindValue(4, $this->generateRandomString());
		$stmt->execute();
		return $this->pdo->lastInsertId();
	}	
	
	public function checkUsername($username){
		$stmt = $this->pdo->prepare("SELECT * FROM user WHERE username=?");
		$stmt->bindValue(1, $username);
		$stmt->execute();
		return $stmt->rowCount();
	}
	
	public function getUserById($userId){
		$stmt = $this->pdo->prepare("SELECT userId, username, password, mail, activated, confirmation FROM user WHERE userId=?");
		$stmt->bindValue(1, $userId);
		$stmt->execute();
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}
	
	public function getUserByUsername($username){
		$stmt = $this->pdo->prepare("SELECT userId, username, password, mail, activated, confirmation FROM user WHERE username=?");
		$stmt->bindValue(1, $username);
		$stmt->execute();
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}
	
	public function activate($userId){
		$stmt = $this->pdo->prepare("UPDATE user SET activated = 1 WHERE userId=?");
		$stmt->bindValue(1, $userId);
		$stmt->execute();
		return $stmt->rowCount();
	}
	
	public function updatePassword($userId, $newPassword){
		$stmt = $this->pdo->prepare("UPDATE user SET password=? WHERE userId=?");
		$stmt->bindValue(1, $this->hashpass($newPassword));
		$stmt->bindValue(2, $userId);
		$stmt->execute();
		return $stmt->rowCount();
	}
	
	public function renewConfirmationByUsername($username){
		$stmt = $this->pdo->prepare("UPDATE user SET confirmation=? WHERE username=?");
		$stmt->bindValue(1, $this->generateRandomString());
		$stmt->bindValue(2, $username);
		$stmt->execute();
	}
	
	
	private function hashpass($password){
		return password_hash($password, PASSWORD_DEFAULT);
	}
	
	private function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}