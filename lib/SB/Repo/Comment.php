<?php

namespace SB\Repo;

use SB\Repo as BaseRepo;

class Comment extends BaseRepo
{
	protected $_table = 'comments';

	public function getCommentsForPost($postId)
	{
		$sql = 'SELECT * FROM comments WHERE post_id = ? ORDER BY comment_id';
		return $this->_conn->fetchAll($sql, [$postId]);
	}
}
