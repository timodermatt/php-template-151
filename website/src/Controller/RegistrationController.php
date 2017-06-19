<?php

namespace LucStr\Controller;


use LucStr\MessageHandler;

class RegistrationController extends BaseController
{
  public function Index()
  {
  	$this->CreateCSRF();
  	return $this->view();
  }
  
  /**
   * @HTTP POST
   * @CSRF ON
   */
  public function Register($username, $email, $password)
  {  	
  	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
  		MessageHandler::danger("Email nicht gueltig!");
  		return $this->view("Registration", "Index", [
  				"username" => $username
  		]);
  	}
  	if(!$this->checkPassword($password)){
  		MessageHandler::danger("Passwort zwischen 8 und 20 Zeichen, 1 Zahl und mind. 1 Buchstabe klein und gross");
  		return $this->view("Registration", "Index", [
  				"username" => $username,
  				"email" => $email
  		]);
  	}
  	if($this->CheckUsername($password)){
  		MessageHandler::danger("Benutzername wird bereits benutzt");
  		return $this->view("Registration", "Index", [
  				"email" => $email
  		]);
  	}
  	$userService = $this->factory->getUserService();
  	$userId = $userService->register($username, $email, $password);
  	if($userId == 0){
  		MessageHandler::danger("Unbekannter Fehler aufgetreten, bitte später nochmals versuchen.");
  		return $this->view("Registration", "Index", [
  				"username" => $username,
  				"email" => $email
  		]);
  	}
  	$user = $userService->getUserById($userId);
  	$message =  \Swift_Message::newInstance()  	 
  	->setSubject('Account Bestätigung')
   	->setFrom(array('tim.odermatt@gmail.com' => 'Tim Odermatt'))
  	->setTo(array($user["mail"] => $user["username"]))
  	->setBody('Hallo ' . $user["username"] . ',</br> Bitte bestätige deine Email <a href="https://' 
  			. $_SERVER['SERVER_NAME'] . "/Registration/Activate?userId=" . $userId . "&confirmation=" .
	$user["confirmation"] . '">Hier</a>', 'text/html')
  	->setContentType("text/html");  	 
  	$this->factory->getMailer()->send($message);
  	return $this->view("Login", "Index", [
  			"username" => $username
  	]);
  }
  
  public function CheckUsername($username){
  	$userService = $this->factory->getUserService();
  	echo $userService->checkUsername($username);
  }
  
  public function Activate($userId, $confirmation){
  	$userService = $this->factory->getUserService();
  	$user = $userService->getUserById($userId);
  	if($user["activated"]){
  		MessageHandler::info("Dieser Benutzer wurde bereits bestätigt.");
  	} else if($user["confirmation"] == $confirmation){
  		$userService->activate($userId);
  		MessageHandler::success("Email wurde bestätigt!");
  	} else{
  		MessageHandler::danger("ungültiger Link!");
  	}
  	return $this->redirectToAction("Index", "Index");
  }
  
  public function RequestPasswordReset(){
  	return $this->action();
  }
  
  /**
   * @HTTP POST
   * @CSRF ON
   */
  public function RequestPasswordResetEmail($username){
  	$userService = $this->factory->getUserService();
  	$userService->renewConfirmationByUsername($username);
  	$user = $userService->getUserByUsername($username);
  	if($user["username"] == $username){
  		$message =  \Swift_Message::newInstance()
  		->setSubject('Passwort zurücksetzen')
  		->setFrom(array('tim.odermatt@gmail.ch' => 'Tim Odermatt'))
  		->setTo(array($user["mail"] => $user["username"]))
  		->setBody('Hallo ' . $user["username"] . ',</br> Du Kannst dein Passwor <a href="https://'
  				. $_SERVER['SERVER_NAME'] . "/Registration/ResetPasswordForm?userId=" . $user["userId"] . "&confirmationUUID=" .
  				$user["confirmationUUID"] . '">Hier</a> Zurücksetzen', 'text/html')
  		->setContentType("text/html");
  		$this->factory->getMailer()->send($message);
  	}
  	return $this->redirectToAction("Index", "Index");
  }
  
  public function ResetPasswordForm($userId, $confirmation){
  	$userService = $this->factory->getUserService();
  	$user = $userService->getUserById($userId);
  	$this->CreateCSRF();
  	if($user["userId"] == $userId){
  		return $this->view([
  				"confirmation" => $confirmation,
  				"userId" => $userId,
  		]);
  	} else{
  		MessageHandler::danger("ungültiger Link!");
  		return $this->redirectToAction("Index", "Index");
  	}
  }
  
  /**
   * @HTTP POST
   * @CSRF ON
   */
  public function ResetPassword($userId, $confirmationUUID, $password, $passwordConfirm){
  	if($password != $passwordConfirm){
  		MessageHandler::danger("Passwort und Bestätigung stimmen nicht überein.");
  		return $this->redirectToAction("Registration", "ResetPasswordForm", ["userId" => $userId, "confirmationUUID" => $confirmationUUID]);
  	}
  	if(!$this->checkPassword($password)){
  		MessageHandler::danger("Passwort: mind. 8 Zeichen, 1 Zahl + 1 Buchstabe");
  		return $this->redirectToAction("Registration", "ResetPasswordForm", ["userId" => $userId, "confirmationUUID" => $confirmationUUID]);
  	}
	$userService = $this->factory->getUserService();
	if($userService->updatePassword($userId, $password)){
		MessageHandler::success("Passwort wurde aktualisiert");
		return $this->redirectToAction("Login", "Index");
	} else {
		MessageHandler::danger("Es ist ein Fehler aufgetreten");
		return $this->redirectToAction("Registration", "ResetPasswordForm", ["userId" => $userId, "confirmationUUID" => $confirmationUUID]);
	}
  }
  
  private function checkPassword($password) {
  	return preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#", $password);
  }
}
