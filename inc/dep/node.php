<?php
$node;
	function checknode($x){
		$data = '{"jsonrpc": "2.0", "method": "get_accounts", "params": [["sudguru"]], "id": 1}';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $x);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		// echo 'node' . $result;
		if($result == false || $result == null || $result == ''){
			return 0;
		}else{
			return 1;
		}
		curl_close($ch);
	}
	function setknode($x){
		$GLOBALS['node'] = $x;
		return 1;
	}
	if(checknode('https://api.steemit.com')){
		setknode('https://api.steemit.com');
	}elseif(checknode('https://api.justyy.com/')){
		setknode('https://api.justyy.com/');
	}elseif(checknode('https://steem.61bts.com')){
		setknode('https://steem.61bts.com');
	}elseif(checknode('https://s2.61bts.com')){
		setknode('https://s2.61bts.com');
	}else{
		die('Nodes Down.');
	}
?> 
