<?php
/**
* 
*/
class Functions extends Config
{
	public function curl($url, $data){
		$Config = new Config;
		if(!empty($data)){
			$data['apikey'] = $Config->ApiKey;
			$data['salt'] = $Config->salt;
			$data['signature'] = $Config->ApiSignature;
			$post_data = http_build_query($data, '', '&');
		}else{
			return false;
		}
		$curl = curl_init($Config->ApiUrl.$url); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_URL, $Config->ApiUrl.$url);
		curl_setopt($curl, CURLOPT_HEADER, false); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		$response = curl_exec($curl);  
		curl_close($curl);
		var_dump($response);
	}
}