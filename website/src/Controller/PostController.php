<?php

namespace timodermatt\Controller;
use LucStr\Controller\BaseController;

use LucStr\MessageHandler;

class PostController extends BaseController
{
  public function Index()
  {
  	$postService = $this->factory->getPostService();
  	$posts = $postService->getAllPosts();
  	return $this->view([
  			"posts" => $posts
  	]);
  }
  
  public function CreateIndex(){
  	if(!isset($_SESSION["userId"])){
  		MessageHandler::danger("Please Log In!");
  		return $this->redirectToAction("Index", "Index");
  	}
  	$this->CreateCSRF();
  	return $this->view();
  }
  
  /**
   * @HTTP POST
   * @CSRF ON
   */
  public function Create($title, $text){
  	if(!isset($_SESSION["userId"])){
  		MessageHandler::danger("Please Log In!");
  		return $this->redirectToAction("Index", "Index");
  	}
  	$picture = $_FILES["picture"];
  	$path = $picture['name'];
  	$extension = pathinfo($path, PATHINFO_EXTENSION);
  	$check = getimagesize($picture["tmp_name"]);
  	if($check === false) {
  		MessageHandler::danger("File is not an image.");
  		return $this->view([
  				"title" => $title,
  				"text" => $text
  		]);
  	}
  	if ($picture["size"] > 500000) {
  		MessageHandler::danger("Sorry, your file is too large.");
  		return $this->view([
  				"title" => $title,
  				"text" => $text
  		]);
  	}
  	if($extension != "jpg" && $extension != "png" && $extension != "jpeg"
  			&& $extension != "gif" ) {
  				MessageHandler::danger("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
  				return $this->view([
  						"title" => $title,
  						"text" => $text
  				]);  				 
  	}
  	$postService = $this->factory->getPostService();
	$postId = $postService->createPost($title, $text, $picture, $_SESSION["userId"]);
	$this->redirectToAction("Post", "Index");
  }
  
  public function Detail($postId){
  	$postService = $this->factory->getPostService();
  	$commentService = $this->factory->getCommentService();
  	$post = $postService->getPostById($postId);
  	$comments = $commentService->getCommentsByPostId($postId);
  	$this->CreateCSRF();
  	return $this->view([
  			"post" => $post,
  			"comments" => $comments
  	]);
  }
  
  /**
   * @HTTP POST
   * @CSRF ON
   */
  public function Comment($postId, $text){
  	if(!isset($_SESSION["userId"])){
  		MessageHandler::danger("Please Log In!");
  		return $this->redirectToAction("Index", "Index");
  	}
  	$commentService = $this->factory->getCommentService();
  	$commentService->createComment($postId, $text, $_SESSION["userId"]);
  	return $this->redirectToAction("Post", "Detail", [
  			"postId" => $postId
  	]);
  }
  
  public function GetPostImage($postId){
  	$postService = $this->factory->getPostService();
  	$post = $postService->getPostById($postId);
  	
  	header('Content-Type: ' . $post["type"]);
  	echo $post["picture"];
  }
  
  public function Like($postId){
  	if(!isset($_SESSION["userId"])){
  		MessageHandler::danger("Please Log In!");
  		return $this->redirectToAction("Index", "Index");
  	}
  	$likeService = $this->factory->getLikeService();
  	if($likeService->likeExists($postId, $_SESSION["userId"])){
  		$likeService->removeLikeByPostAndUserId($postId, $_SESSION["userId"]);
  	}else{
  		$likeService->addLike($postId, $_SESSION["userId"]);
  	}
  	$likeCount = $likeService->likeCount($postId);
  	if($likeCount == null){
  		echo 0;
  	}
  	else{
  		echo $likeCount;
  	}
  }
}
