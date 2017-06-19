<?php

namespace LucStr;

class MessageHandler{
	public static function Initialize(){
		if(!isset($_SESSION["MessageHandler"])){
			$_SESSION["MessageHandler"] = array();
		}		 
	}
	
	public static function info($message){
		$message = ["type" => "info", "message" => $message];
		array_push($_SESSION["MessageHandler"], $message);	
	}
	
	public static function warn($message){
		$message = ["type" => "warning", "message" => $message];
		array_push($_SESSION["MessageHandler"], $message);
	}
	
	public static function danger($message){
		$message = ["type" => "danger", "message" => $message];
		array_push($_SESSION["MessageHandler"], $message);
	}
	
	public static function success($message){
		$message = ["type" => "success", "message" => $message];
		array_push($_SESSION["MessageHandler"], $message);
	}
	
	public static function object($obj){
		array_push($_SESSION["MessageHandler"], $obj);
	}
	
	public static function renderMessages(){
		echo "<div id ='messages'>";
		foreach ($_SESSION["MessageHandler"] as $message){
			echo "<div class='alert alert-" . $message["type"] ."' alert-dismissable>
  			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>					
  					" . $message["message"] . "
			</div>";
		}
		echo "</div>";
		$_SESSION["MessageHandler"] = array();
	}
}