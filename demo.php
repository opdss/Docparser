<?php
/**
 * test.php for Docparser.
 * @author 阿新
 * @date 2017/9/18 14:28
 * @copyright istimer.com
 */

include './Docparser.php';
include './Handler.php';

$class = new \ReflectionClass('Opdss\Docparser\Docparser');

//设置参数处理handler
Opdss\Docparser\Docparser::setGlobalHandler('param', array('Opdss\Docparser\Handler', 'fm_param'));
Opdss\Docparser\Docparser::setGlobalHandler('return', array('Opdss\Docparser\Handler', 'fm_return'));
Opdss\Docparser\Docparser::setGlobalHandler('date', array('Opdss\Docparser\Handler', 'fm_date'));
Opdss\Docparser\Docparser::setGlobalHandler('author', array('Opdss\Docparser\Handler', 'fm_author'));

$doc_class = Opdss\Docparser\Docparser::factory($class->getDocComment());
$doc_method = Opdss\Docparser\Docparser::factory($class->getMethod('setGlobalHandler')->getDocComment());

var_dump($doc_class->getParams());
var_dump($doc_method->getParams());
var_dump($doc_method->getShortDesc());
