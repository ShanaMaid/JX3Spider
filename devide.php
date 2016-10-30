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

for($i=0;$i<$id_number;$i++){
	echo "$i\r\n";
$card_url = 'http://tieba.baidu.com/p/'.str_ireplace("\r\n","",$id_arr[$i]).'?see_lz=1&pn=';
$page_content = _getUrlContent($card_url);
$tag =  "/([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})-(((0[13578]|1[02])-(0[1-9]|[12][0-9]|3[01]))|((0[469]|11)-(0[1-9]|[12][0-9]|30))|(02-(0[1-9]|[1][0-9]|2[0-8])))/";
$tag_name = "/>(.*?)</";
if(!preg_match($tag,$page_content,$time)){
	echo "fail\r\n";
	continue;
}
if(!preg_match($tag_name,$name_arr[$i+1],$result)){
	echo "fail\r\n";
	continue;
}
	

	 $fp_day = fopen("result\\part\\".$time[0].".md","a+");
 	 fwrite($fp_day,"[".str_ireplace(">","",str_ireplace("<","",$result[0]))."](".$card_url.")   \r\n"); 
 	 fclose($fp_day);
 	}
fclose($fp_card_id);

?>