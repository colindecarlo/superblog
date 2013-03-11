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

	public function getCommentCounts()
	{
		$comments = [];

		$sql = 'SELECT post_id, count(*) as number_of_comments FROM comments GROUP BY post_id';
		$results = $this->_conn->fetchAll($sql);

		foreach ($results as $post) {
			$comments[$post['post_id']] = $post['number_of_comments'];
		}

		return $comments;
	}
}
