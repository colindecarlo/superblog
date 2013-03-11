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
}
