<?php
/**
 * parseFile.php for Docparser.
 * @author SamWu
 * @date 2018/1/12 15:55
 * @copyright boyaa.com
 */

class parseClass  extends \Opdss\Docparser\Docparser
{
	/**
	 * @var null
	 */
	public $a = null;

	/**
	 * @param $a
	 */
	function test($a)
	{
		echo $a;
	}

	/**
	 * @param $v
	 */
	function tt($v){
		echo $v;
	}
}