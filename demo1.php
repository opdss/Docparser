<?php
/**
 * demo1.php for Docparser.
 * @author SamWu
 * @date 2018/1/12 15:46
 * @copyright boyaa.com
 */

include './src/Docparser.php';
include './src/ParseFile.php';
include './src/ParseDoc.php';

$file = './src/parseClass.php';
/*$str='nihaoahelaa';
$pattern1='/(?>\w+)a/';
$pattern2='/\w+a/';
$rs1=preg_match($pattern1, $str, $a1);//0
$rs2=preg_match($pattern2, $str, $a2);//1
var_dump($rs1, $rs2);
var_dump($a1, $a2);
exit;*/
$pf = new \Opdss\Docparser\ParseFile($file);
$pf->readDoc();