<?php
/**
 * test.php for Docparser.
 * @author 阿新
 * @date 2017/9/18 14:28
 * @copyright istimer.com
 */

include './Docparser.php';
include './Handler.php';

$class = new ReflectionClass('Docparser');

//设置参数处理handler
Docparser::setGlobalHandler('param', array('Handler', 'fm_param'));
Docparser::setGlobalHandler('return', array('Handler', 'fm_return'));
Docparser::setGlobalHandler('date', array('Handler', 'fm_date'));
Docparser::setGlobalHandler('author', array('Handler', 'fm_author'));

$doc_class = Docparser::factory($class->getDocComment());
$doc_method = Docparser::factory($class->getMethod('setGlobalHandler')->getDocComment());

var_dump($doc_class->getParams());
var_dump($doc_method->getParams());
var_dump($doc_method->getShortDesc());
