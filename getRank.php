<?php 
	$fp_rank = fopen("result\\rank100.md", "w");
	$str = file_get_contents("result\\rank.json");
	$str = str_ireplace("\r\n", "", $str);
	 $str = json_decode($str,true);
	for ($i=0; $i < 100; $i++) { 
		fwrite($fp_rank, "[".$str[$i]["cardTitle"][0]."](http://tieba.baidu.com/p/".$str[$i]["cardId"][0].")     ".$str[$i]["cardReply"][0]."回复           \r\n");
	}

	//  for ($i=0; $i < 100; $i++) { 
	// 	fwrite($fp_rank, "排名：".($i+1)."\r\n\r\n 帖子名字：".$str[$i]["cardTitle"][0]."\r\n \r\n 帖子地址：http://tieba.baidu.com/p/".$str[$i]["cardId"][0]."\r\n\r\n     回复：".$str[$i]["cardReply"][0]."         \r\n");
	// }


	fclose($fp_rank);


?>