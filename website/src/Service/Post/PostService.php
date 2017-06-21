<?php
namespace timodermatt\Service\Post;

interface PostService
{
	public function createPost($title, $text, $picture, $userId);
	public function getAllPosts();
	public function getPostById($postId);
}