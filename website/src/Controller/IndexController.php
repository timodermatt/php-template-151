<?php

namespace LucStr\Controller;

use LucStr\MessageHandler;

class IndexController extends BaseController
{
  public function Index()
  {  	
  	return $this->redirectToAction("Post", "Index");
  }
}
