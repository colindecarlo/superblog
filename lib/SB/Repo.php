<?php

namespace SB;

use Doctrine\DBAL\Connection;

abstract class Repo
{

	protected $_conn;
	protected $_table = null;

	public function __construct(Connection $conn)
	{
		$this->_conn = $conn;
	}

	public function insert(array $data)
	{
		return $this->_conn->insert($this->_table, $data);
	}
}
