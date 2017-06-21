<?php

namespace timodermatt;

class Factory 
{
	private $config;
	
	public static function crateFromInitFile($source){
		return new Factory(parse_ini_file($source));
	}
	
	public function __construct(array $config){
		$this->config = $config;
	}
	
	function getIndexController()
	{
		return new Controller\IndexController($this->getTemplateEngine());
	}
	
	function getPdo()
	{
		return new \PDO(
			"mysql:host=mariadb;dbname=blog;charset=utf8",
			$this->config['user'],
			"my-secret-pw",
			[\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
		);
	}
	
	function getLoginService()
	{
		return new Service\Login\LoginPdoService($this->getPdo());
	}	
	
	function getUserService()
	{
		return new Service\User\UserPdoService($this->getPdo());
	}
	
	function getPostService()
	{
		return new Service\Post\PostPdoService($this->getPdo());
	}
	
	function getCommentService()
	{
		return new Service\Comment\CommentPdoService($this->getPdo());
	}
	
	function getLikeService()
	{
		return new Service\Like\LikePdoService($this->getPdo());
	}
	
	public function getMailer()
	{
		return \Swift_Mailer::newInstance(
				\Swift_SmtpTransport::newInstance("smtp.gmail.com", 465, "ssl")
				->setUsername("gibz.module.151@gmail.com")
				->setPassword("Pe$6A+aprunu")
				);
	}
}