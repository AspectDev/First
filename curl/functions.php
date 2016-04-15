<?php
/**
* 
*/
class Functions extends Config
{
	public function curl($url, $data=array(), $method="POST"){
		$Config = new Config;
		$data['apikey'] = $Config->ApiKey;
		$data['salt'] = $Config->salt;
		$data['signature'] = $Config->ApiSignature;
		$post_data = http_build_query($data, '', '&');
		if($method == "GET")
			$url .=$post_data;
		$curl = curl_init($Config->ApiUrl.$url); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		if($method == "POST")
			curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_URL, $Config->ApiUrl.$url);
		curl_setopt($curl, CURLOPT_HEADER, false); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		if($method == "POST")
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		$response = curl_exec($curl);  
		curl_close($curl);
		return $response;
	}
	public function RetriveDepartamentList(){
		$url = "e=/Base/Department&";
		$xml = $this->curl($url,array(),"GET");
		$DepartmentList = $this->parseXMLtoArray($xml);
		foreach ($DepartmentList["department"] as $key => $val) {
			if($val["parentdepartmentid"] > 0)
				foreach ($DepartmentList["department"] as  $keyfather =>$value)
					if($value["id"] == $val["parentdepartmentid"])
						$DepartmentList["department"][$keyfather]["children"][] =$DepartmentList["department"][$key];
		}
		return $DepartmentList;
	}

	private function parseXMLtoArray($xml, $namespaces = null) {
		$iter = 0;
		$arr = array();
		if (is_string($xml)){
			@$xml = new SimpleXMLElement(@$xml,LIBXML_NOERROR);
		}

		if (!($xml instanceof SimpleXMLElement))
			return $arr;

		if ($namespaces === null)
			$namespaces = $xml->getDocNamespaces(true);

		foreach ($xml->attributes() as $attributeName => $attributeValue) {
			$arr["_attributes"][$attributeName] = trim($attributeValue);
		}
		foreach ($namespaces as $namespace_prefix => $namespace_name) {
			foreach ($xml->attributes($namespace_prefix, true) as $attributeName => $attributeValue) {
				$arr["_attributes"][$namespace_prefix.':'.$attributeName] = trim($attributeValue);
			}
		}

		$has_children = false;

		foreach ($xml->children() as $element) {
			$has_children = true;
			$elementName = $element->getName();
			if ($element->children()) {
				$arr[$elementName][] = $this->parseXMLtoArray($element, $namespaces);
			} else {
				$shouldCreateArray = array_key_exists($elementName, $arr) && !is_array($arr[$elementName]);

				if ($shouldCreateArray) {
					$arr[$elementName] = array($arr[$elementName]);
				}

				$shouldAddValueToArray = array_key_exists($elementName, $arr) && is_array($arr[$elementName]);

				if ($shouldAddValueToArray) {
					$arr[$elementName][] = trim($element[0]);
				} else {
					$arr[$elementName] = trim($element[0]);
				}

			}
			$iter++;
		}

		if (!$has_children) {
			$arr['_contents'] = trim($xml[0]);
		}

		return $arr;
	}

}