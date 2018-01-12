<?php
/**
 * parseFile.php for Docparser.
 * @author SamWu
 * @date 2018/1/12 15:55
 * @copyright boyaa.com
 */

namespace Opdss\Docparser;

class ParseFile
{
	const PREG_CLASS = '[\w|_][[\w|_|\d]*';
	const PREG_DOC = '\/\*[\s\S]+?\*\/';

	private $file;

	private $class;

	private $methods;

	private $content;

	public function __construct($file)
	{
		$this->file = $file;
		$this->content = file_get_contents($file);
	}

	function readDoc()
	{

		$preg = "/(".self::PREG_DOC.")[\s\S]+?class[\s]+(".self::PREG_CLASS.")[^\{]*\{([\s\S]*)\}/";
		if (!preg_match($preg, $this->content, $match)) {
			return false;
		}
		$this->class = ParseDoc::factory($match[1])->setName($match[2])->setType('class');
		$str = $match[3];
		var_dump((string)$this->class);
		$preg = '/\/\*+([\s\S]+)\*\/([\s\S]+)function[\s]+([a-zA-Z_0-9-]+\(.*\))/iU';
		preg_match($preg, $str, $match);
		var_dump($match);
	}
}