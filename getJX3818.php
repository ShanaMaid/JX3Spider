<?php
/**
* 爬虫程序 -- JX3818
* QQ：416193699
* 作者：ShanaMaid
*
*/
/**
*
*/

date_default_timezone_set("Asia/Shanghai");
header("Content-Type:text/html;charset=gb2312");
/*
加载首页
*/
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

/*
开始爬虫
*/
function _spiderGo($url){
	 	//$end=10;
		$fp_puts = fopen("result\\cardName.html","a+");//记录url列表
	 	$fp_card_id = fopen("result\\cardID.txt","a+");
	 	$id_number = 0;
	 	$id_arr = array();
	 	while(! feof($fp_card_id))
		  {
		  $id_arr[$id_number++] = fgets($fp_card_id);
		  
		  }
		  
		  $id_number--;//消除最后一行的空行
		
	 	for($k=0;;$k++){
	 	$list = _getTagCard($url,$k*10);	
	 	for ($i=0; $i<sizeof($list); $i++) { 
	 		if(isHave($id_arr,$list[$i][1]."\r\n")){	
	 			continue;
	 		}
	 			
	 		 $card_url = 'http://tieba.baidu.com/p/'.$list[$i][1].'?see_lz=1&pn=';
	 		 $id_arr[$id_number++] = $list[$i][1]."\r\n";
 			 fwrite($fp_puts,"<a href=\"".$card_url."\">".$list[$i][0]."</a><br/>\r\n"); 
 			 fwrite($fp_card_id,$list[$i][1]."\r\n"); 

 			 $fp_day = fopen("result\\part\\".date('Y',time())."\\".date('m',time())."\\".date('Y-m-d',time()).".md","a+");
 			 fwrite($fp_day,"[".$list[$i][0]."](".$card_url.")   \r\n"); 
 			 fclose($fp_day);
		 
 		}

 		if ($k==2) {
		 	echo "finish one  ".date('Y-m-d H:i:s',time())."\r\n";
		 	$k=0;
		 }
	 }
	 		
	  		 fclose($fp_puts);
			 fclose($fp_card_id);
			 
}


/*
帖子内容筛选
*/
function _getMain($web_content,$fp_puts){
	$tag = '/<cc>(.*)<\/cc>/';//抓取回复内容
	$result = preg_match_all($tag,$web_content,$match_result);
	$length = sizeof($match_result[0]);
	
	for($i=0;$i<$length;$i++){
		//echo '  '.$match_result[0][$i];
		fwrite($fp_puts,$match_result[0][$i]);
	}
	
}

/*
获取帖子总页数
*/
function _getSumPage($web_content){
	$tag= '/<span class="red">.*<\/span>/';
	$getPage = '/[0-9]+/';
	$result = preg_match_all($tag,$web_content,$match_result);
	
	preg_match_all($getPage,$match_result[0][0],$sumpage);
	return $sumpage[0][0];	
}



/*
帖子-翻页
*/
function _nextPageUrl($currentpage,$sumpage,$url){
	if ($currentpage<$sumpage) {
		$currentpage++;
		// url:http://tieba.baidu.com/p/4637678498?see_lz=1&pn=
		$url=$url.$currentpage;
		return $url;//返回下一页地址
	}
	return false;//达到最大页数
}


/*
贴吧筛选帖子-返回符合要求的帖子
*/
function _getTagCard($url,$start){
	$tag = '/a href=".*" title=".*"/';//筛选帖子
	$tag_card_url = '/[0-9]+/';//帖子地址
	$tag_card_title = '/e=".*" t/';//帖子名字
	$tag_get_rel_title = '/[^"]{10,1000}/';//帖子的真正名字
	$tag_choose='/.*(818|树洞).*/';
	//echo $content;
	$counter = 0;
	for($cur=-1;$cur<10;$cur++){
		//echo "<br>".$cur."</br>";
	$content = _getUrlContent(_nextPostBarPageUrl($url,$start+$cur));//获取url中的txt html 
	preg_match_all($tag, $content,$result_card);
		for($i=0;$i<sizeof($result_card[0]);$i++){
		 preg_match($tag_card_title, $result_card[0][$i],$result_card_title);
		 preg_match($tag_choose, $result_card_title[0],$result_card_title_fi);
		 preg_match($tag_get_rel_title, $result_card_title[0],$result_card_title_re);
		 //echo $result_card_title_fi[0]."          ";
		 if($result_card_title_fi){
		 	preg_match($tag_card_url, $result_card[0][$i],$result_card_url);
		//	echo $result_card_url[0]."<br/>";
			$result_list[$counter][0]=$result_card_title_re[0];
			$result_list[$counter][1]=$result_card_url[0];
		//	echo "<br>".$result_card_title[0]."       ".$result_card_url[0]."</br>";
			$counter++;
		 }
		}
	}
	return $result_list;
}

/*
贴吧翻页
*/
function _nextPostBarPageUrl($url,$current){
		$url=$url.($current*50);
		//echo "<br>".$url."</br>";
		return $url;//返回下一页地址
}

function isHave($arr,$id){
	
	for ($i=0; $i < sizeof($arr); $i++) { 
		
		if ($arr[$i] == $id) {
			return true;
		}
	}
	return false;
}



_spiderGo('http://tieba.baidu.com/f?kw=%E5%89%91%E7%BD%913&ie=utf-8&pn=')


?>