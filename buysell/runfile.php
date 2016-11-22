<?php
		$host = "http://purchase.tickets.com/buy/MapTicketSales?user_context=S_tomcat_irprwebpvo3_1457839073146_258516|S|tomcat_irprwebpvo5|purchase.tickets.com|en|US|null|MLB&supplier_code=NYM_&bots_event_code=6054&event_sub_code=";
		$payloadname = array("event=6055&seatmap=1143&inclseatmap=Y&holdcode=0");
		$process = curl_init($host);
		curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $additionalHeaders));
		curl_setopt($process, CURLOPT_HEADER, 1);
		//curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_POST, 1);
		curl_setopt($process, CURLOPT_POSTFIELDS, $payloadName);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, true);
		$return = curl_exec($process);
		curl_close($process);
		
		echo $return;