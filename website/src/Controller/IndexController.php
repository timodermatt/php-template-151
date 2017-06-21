<?php

namespace timodermatt\Controller;
use LucStr\Controller\BaseController;

use LucStr\MessageHandler;

class IndexController extends BaseController
{
  public function Index()
  {  	
  	return $this->redirectToAction("Post", "Index");
  }
}
