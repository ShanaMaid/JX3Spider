<?php

	$fp_cardreply = fopen("result\\cardReply.md", "r");
	$cardreply_number = 0;
	$cardreply_arr = array();
	while(! feof($fp_cardreply))
	{
	$cardreply_arr[$cardreply_number++] = fgets($fp_cardreply);

	}
	$cardreply_number--;

	$tag_id = "/[0-9]{10}/";
	$tag_reply = "/[0-9]+?回复/";
	$tag_title = "/\[.*\]/"; 
	$arr = array();
	$count = 0;
	for ($i=0; $i <= $cardreply_number; $i++) { 
		echo $i."\r\n";
		if (!preg_match($tag_title, $cardreply_arr[$i],$title)) {		
			echo "fail";
			continue;
		}
		if (!preg_match($tag_reply, $cardreply_arr[$i],$reply)) {		
			echo "fail";
			continue;
		}
		if (!preg_match($tag_id, $cardreply_arr[$i],$id)) {		
			echo "fail";		
			continue;
		}
		$arr[$count++] = array(
				"cardTitle" => str_ireplace("[", "", str_ireplace("]", "", $title)),
				"cardId"	=> $id,
				"cardReply"	=> str_ireplace("回复", "", $reply)
			);


	}

	$temp = array();
	for ($i=0; $i < sizeof($arr); $i++) { 
		echo $i."\r\n";
		for ($j=$i+1; $j < sizeof($arr) - 1; $j++) { 
			if ($arr[$i]["cardReply"][0] < $arr[$j]["cardReply"][0]) {
				$temp = $arr[$i];
				$arr[$i] = $arr[$j];
				$arr[$j] = $temp;
			}
		}
	}

	$str = json_encode($arr,JSON_UNESCAPED_UNICODE);
	//$str = str_ireplace("[", "[\r\n", $str);
	$str = str_ireplace("{", "{\r\n", $str);
	$str = str_ireplace("}", "\r\n}", $str);
	$str = str_ireplace(",", ",\r\n", $str);

	$fp_rank = fopen("result\\rank.json", "w");
	fwrite($fp_rank,$str);

	fclose($fp_rank);
	fclose($fp_cardreply);








?>