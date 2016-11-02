<?php

ini_set("display_errors", 0);
error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL ^ E_WARNING);
error_reporting(E_ALL ^ E_DEPRECATED);
error_reporting(0);
error_reporting(E_ALL ^ E_NOTICE);//屏蔽警告
ini_set('max_execution_time', '0');//由于爬虫时间较长，不添加此语句会导致错误  Fatal error: Maximum execution time of 30 seconds ;设置为0无限制时间



function _getUrlContent($url){

	$handle =fopen($url, "r");
	if ($handle) {
		$content = stream_get_contents($handle,-1,-1);
		return $content;
	}
	else{
		return -1;
	}

}



$fp_card_id = fopen("result\\cardID.txt","a+");
$id_number = 0;
$id_arr = array();
while(! feof($fp_card_id))
{
$id_arr[$id_number++] = fgets($fp_card_id);

}

$id_number--;//消除最后一行的空行


$fp_card_name = fopen("result\\cardName.html","a+");
$name_number = 0;
$name_arr = array();
while(! feof($fp_card_name))
{
$name_arr[$name_number++] = fgets($fp_card_name);

}
$name_number--;//消除最后一行的空行

$fp_reply = fopen("result\\cardReply.md","a+");

for($i=0;$i<$id_number;$i++){
	echo "$i\r\n";
$card_url = 'http://tieba.baidu.com/p/'.str_ireplace("\r\n","",$id_arr[$i]);
$page_content = _getUrlContent($card_url);
$tag =  '/<span class="red" style="margin-right:3px">[0-9]*<\/span>/'; //帖子回复数
$tag_name = "/>(.*?)</"; //获取>XXX<
if(!preg_match($tag,$page_content,$reply_str)){
	echo "fail1\r\n";
	continue;
}

if(!preg_match($tag_name, $reply_str[0],$reply_number)){
	echo "fail2\r\n";
	continue;
}

if(!preg_match($tag_name,$name_arr[$i+1],$result)){
	echo "fail3\r\n";
	continue;
}
	

	
 	 fwrite($fp_reply,"[".str_ireplace(">","",str_ireplace("<","",$result[0]))."](".$card_url.")    >".str_ireplace(">","",str_ireplace("<","",$reply_number[0]))."回复<   \r\n"); 
 	
 	}
 fclose($fp_reply);
fclose($fp_card_id);
fclose($fp_card_name);
?>