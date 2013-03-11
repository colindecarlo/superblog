<?php

namespace SB\Repo;

use SB\Repo as BaseRepo;

class Post extends BaseRepo
{
	protected $_table = 'posts';

	public function getPosts()
	{
		$sql = 'SELECT * FROM posts ORDER BY created DESC';
		return $this->_conn->fetchAll($sql);
	}

	public function getPost($postId)
	{
		$sql = 'SELECT * FROM posts WHERE post_id = ?';
		return $this->_conn->fetchAssoc($sql, [$postId]);
	}
}
