<?php

namespace LucStr\Controller;

use LucStr\MessageHandler;

class LoginController extends BaseController
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
  public function Authenticate($username, $password)
  {
  	$userService = $this->factory->getUserService();
  	$user = $userService->getUserByUsername($username);
  	if(!$user["activated"]){  		
  		MessageHandler::danger("Bitte bestätige deine Mail");
  		return $this->view("Login", "Index", [
  				"username" => $username  				
  		]);
  	}
  	if(password_verify($password, $user["password"])){
  		session_regenerate_id();
  		$_SESSION["userId"] = $user["userId"];
  		$_SESSION["username"] = $username;
  		return $this->redirectToAction("Index", "Index");
  	} else{
  		MessageHandler::danger("Login fehlgeschlagen!");
  		return $this->view("Login", "Index", [
  				"username" => $username  				
  		]);
  	}
  }
  
  public function Logout(){
  	session_destroy();
  	$this->redirectToAction("Index", "Index");
  }
}
