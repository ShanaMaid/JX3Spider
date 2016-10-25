<?php
	date_default_timezone_set("Asia/Shanghai");
	 $start_time = 1477385506;
	while (true) {
		$fp_raw = fopen("raw.txt","r");
		$raw_old = fgets($fp_raw);
		fclose($fp_raw);
		$raw_now = count(file('result/cardID.txt'));
		
		
		$fp_raw = fopen("raw.txt","w");
		fwrite($fp_raw, $raw_now);
		fclose($fp_raw);
		$days = (int)((time()-$start_time+86400)/86400);
		$fp_mid = fopen("mid.md","w");
		fwrite($fp_mid, "目前收录帖子总数:$raw_now  \r\n最后一次更新:".date('Y年m月d日 H:i:s',time())."  \r\n存活时间:".$days."天  \r\n  \r\n  \r\n Log  \r\n===  \r\n");
		fclose($fp_mid);
		$update = $raw_now-$raw_old;

		//更新log
		$logs = date('Y年m月d日 H:i:s',time())."         更新".$update."帖子  \r\n".file_get_contents("logs.md");
		$fp_log = fopen("logs.md", "w");
		fwrite($fp_log, $logs);
		fclose($fp_log);

		//合并信息生成README.md
		$data =  file_get_contents("header.md").file_get_contents("mid.md").file_get_contents("logs.md");
		$fp_read = fopen("README.md", "w");
		fwrite($fp_read, $data);
		fclose($fp_read);


		//git  -add 

		exec("git add -A",$out);
		exec("git commit -m \"".date('Y-m-d H:i:s',time())."\"",$out);
		exec("git push origin master",$out);
		
		echo "OK ".date('Y-m-d H:i:s',time());

		// echo time()-$time;
		sleep(86400/3);//一天更新3次
	}

?>