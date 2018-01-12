<?php
/**
 * getDoc.php for Docparser.
 * @author SamWu
 * @date 2017/11/28 15:47
 * @copyright boyaa.com
 */

class ReadDoc
{
	private $content;

	private function __construct($content)
	{
		$this->content = $content;
	}

	public static function forPath($path)
	{
		return self::factory(file_get_contents($path));
	}

	public static function factory($content)
	{
		return new self($content);
	}
}


function create($directory,$content,$files){
	//存档一份当前读取的文件内容，下面读取function时候会被删除
	$contents = $content;
	//输出到生成文件数据样式
	$tmp = file_get_contents('./function.html');

	//正则匹配获取数据
	//排除类的注释
	//类名
	$this_class_name = current(explode('.',$files));
	$rege = "/\/\*[\s\S]+?\*\/[\s\S]+?class[\s]+".$this_class_name."/";
	if(preg_match_all($rege,$content,$matches)){
		$content = str_replace(current($matches[0]),'',$content);
	}

	$rege = '/\/\*+([\s\S]+)\*\/([\s\S]+)function[\s]+([a-zA-Z_0-9-]+\(.*\))/iU';
	// $rege = '/\/\*+([\s\S]+)\*\/\n(.*)function[\s]+([a-zA-Z_0-9-]+\(.*\))/iU';
	$function_des = array();
	if(preg_match_all($rege, $content, $ms)){
		foreach($ms[0] as $key => $value){
			//将 */ 后面的数据去除
			$value = explode('*/',$value);
			array_pop($value);
			$value = implode(" ",$value);
			//以* @为分割符取出所有参数拼接到一个数组之中
			$function_des[$key] = explode('* @',$value);
		}
	}

	//拼接获取类页面的方法列表
	$this_class_all_method = '';
	//ms[3]就是当前读取的和这个类文件的function列表
	foreach ($ms[3] as $key => $value) {
		$this_class_all_method.= '<li><a href="#'.current(explode('(',$value)).'">'.$value.'</a></li>';
	}

	$t = '';
	//输出到生成文件的数据第一行是文件路径
	// $t.=$directory.'/'.$files."\n";
	$html_header = file_get_contents('./header.html');
	//将去除绝对路径后当前类文件路径插入模板
	$now_class_url_only = str_replace($GLOBALS['file_now_url'],"",$directory.'/'.$files);
	$html_header = str_replace('{t_location}',$now_class_url_only,$html_header);
	//模板页面左侧类文件列表数据
	$html_header = str_replace('{t_class_tree}',$GLOBALS['class_tree'],$html_header);
	//模板页面右侧的文档标题
	$html_header = str_replace('{t_title}',$GLOBALS['title'],$html_header);
	//模板页面右上角当前类的方法列表
	$html_header = str_replace('{t_this_class_all_method}',$this_class_all_method,$html_header);

	//正则获取类的说明
	if(preg_match_all("/\/\*+[\s\S]+class[\s]+".$this_class_name."/", $contents, $match))
	{
		$current_match = current($match);
		$html_header = str_replace('<h3><a href="#class_details">class detail</a></h3><ul></ul>','<h3><a href="#class_details">class detail</a></h3><ul>'.implode('</br> *',explode(' *',current(explode('class',current($current_match))))).'</ul>',$html_header);
	}


	//页面的class detail的类说明数据
	$t.= str_replace('{t_class_name}',current(explode('.', $files)),$html_header);

	foreach($function_des as $key => $value){

		//去除第一个数组.值是* 不用作为function的描述
		unset($value[0]);
		//现在得到的数字是:method 发送邮件 url email/send?token=xxx 'param  token param  ema_type这样的，处理成同一种参数对一个数组
		$m_function_des = "";
		$new_function_des = array();
		foreach ($value as $kk => $vv) {
			//获取当前键值
			$now_key = current(explode(" ",$vv));
			$vv = str_replace($now_key,"",$vv);
			$new_function_des[$now_key][] = $vv;
			// $m_function_des.="<tr><td><b>".$now_key."</b></td><td>".$vv."</td></tr>";
		}
		foreach ($new_function_des as $bb => $cc) {
			if(count($cc)>1){
				//此时同一个参数有多行，那么不要同一行显示
				$all_ccc ="";
				foreach ($cc as $bbb => $ccc) {
					$all_ccc .= '<div class="onelow">'.str_replace("\r", '<div class="marb5"></div>', trim($ccc)).'</div>';
				}
				$m_function_des.="<tr><th>".$bb."</th><td>".$all_ccc."</td></tr>";

			}else{
				//此时同一个人参数显示只有一行那么显示在同一行就可以了
				foreach ($cc as $bbb => $ccc) {
					$m_function_des.="<tr><th>".$bb."</th><td>".str_replace("\r", '<div class="marb5"></div>', trim($ccc))."</td></tr>";
				}

			}

		}
		$t_function_name = $ms[3][$key];
		$t_access = $ms[2][$key];
		if(strlen($t_access)>9){
			//此时肯定是正则匹配到了function的前面的一些说明,这里t_access数据格式是 /*dasda*/ public
			$t_access = explode("*/",$t_access);
			$t_access = end($t_access);
			$t_access = trim($t_access);
			//如果*/跟function之间隔了非常多字符，那么肯顶截取到了一部分非funciton说明的文字
			if(strlen($t_access)>40){
				$t_access ="";
			}
			if($t_access == 'public'){
				$t_access="";
			}
		}
		//对输出数据的模板进行数据替换
		//组装锚点
		$t_function_name_url = current(explode("(",$t_function_name));
		$t.= str_replace(
			array( '{m_function_des}','{t_function_name}','{t_function_name_url}','<span id="access"></span>'),
			array( $m_function_des ,$t_function_name,$t_function_name_url,"<span id='access'>".$t_access."</span>"),
			$tmp
		);
	}
	$html_footer = file_get_contents('./footer.html');
	$html_footer = str_replace('{all_function_url_json}',$GLOBALS['function_url_tree'],$html_footer);
	$t.=$html_footer;

	file_put_contents('./'.$GLOBALS['save_api_url'].'/'.current(explode('.', $files)).'.html', $t);
}
