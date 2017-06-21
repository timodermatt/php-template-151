<?php
namespace timodermatt\Service\User;

interface UserService
{
	public function register($username, $email, $password);
	public function checkUsername($username);
	public function activate($userId);
	public function getUserById($userId);
	public function getUserByUsername($username);
	public function updatePassword($userId, $newPassword);
	public function renewConfirmationByUsername($username);
}