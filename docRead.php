<?php
/**
 * docRead.php for Docparser.
 * @author SamWu
 * @date 2018/1/12 15:47
 * @copyright boyaa.com
 */
namespace Opdss\Docparser;

class docRead
{
	private $dir;

	function __construct($config = array())
	{
		if (isset($config['dir'])) {
			$this->dir = $config['dir'];
		}
	}

	public function read()
	{

	}
}