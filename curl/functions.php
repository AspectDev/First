<?php
/**
* 
*/
class Functions extends Config
{
	public function curl($url, $data){
		if(!empty($data)){
			$Config = new Config;
			$data['apikey'] = $Config->ApiKey;
			$data['salt'] = $Config->$salt;
			$data['signature'] = $Config->$ApiSignature;
			$post_data = http_build_query($data, '', '&');
		}else{
			return false;
		}
	}
}