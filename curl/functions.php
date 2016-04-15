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
			if($val["type"] == "private"){
				unset($DepartmentList["department"][$key]);
				continue;
			}
			if($val["parentdepartmentid"] > 0){
				foreach ($DepartmentList["department"] as $keyfather =>$value)
					if($value["id"] == $val["parentdepartmentid"])
						$DepartmentList["department"][$keyfather]["children"][] =$DepartmentList["department"][$key];
				unset($DepartmentList["department"][$key]);
			}
		}
		return $DepartmentList["department"];
	}
	public function RetriveUserList(){
		$url = "e=/Base/User/Filter&";
		$xml = $this->curl($url,array(),"GET");
		return $this->parseXMLtoArray($xml);
	}
	public function RetriveUserInfoByEmail($email){
		$userList = $this->RetriveUserList();
		foreach ($userList["user"] as $key => $val) {
			if($val["email"] == $email && $val["userrole"] == "user")
				return $userList["user"][$key];
		}
	}
	public function RetrivePriorityList(){
		$url = "e=/Tickets/TicketPriority&";
		$xml = $this->curl($url,array(),"GET");
		$priorityList = $this->parseXMLtoArray($xml);
		$output = array();
		foreach ($priorityList["ticketpriority"] as $key => $val) {
			if($val["type"]=="public")
				$output[] = $priorityList["ticketpriority"][$key];
		}
		return $output;
	}
	public function RetriveStatusList(){
		$url = "e=/Tickets/TicketStatus&";
		$xml = $this->curl($url,array(),"GET");
		$StatusList = $this->parseXMLtoArray($xml);
		$output = array();
		foreach ($StatusList["ticketstatus"] as $key => $val) {
			if($val["type"]=="public")
				$output[$val["id"]] = $StatusList["ticketstatus"][$key];
		}
		// var_dump($output);
		return $output;
	}
	public function RetriveTicketTypeList(){
		$url = "e=/Tickets/TicketType&";
		$xml = $this->curl($url,array(),"GET");
		$tickettypesList = $this->parseXMLtoArray($xml);
		$output = array();
		foreach ($tickettypesList["tickettype"] as $key => $val) {
			if($val["type"]=="public")
				$output[$val["id"]] = $tickettypesList["tickettype"][$key];
		}
		return $output;
	}
	public function NewTicket($data){
		$url = "e=/Tickets/Ticket";
		return $this->curl($url, $data);
	}
	public function RetriveTicketsList($ownerstaffid=-1,$userid=-1,$count=20){
		$departmentid ="";
		for ($id=1; $id < 20; $id++) { 
			$departmentid .= $id.",";
		}
		$ticketstatusid = "";
		for ($id=1; $id < 4; $id++) { 
			$ticketstatusid .= $id.",";
		}
		$departmentid = trim($departmentid, ",");
		$ticketstatusid = trim($ticketstatusid, ",");
		$url = "e=/Tickets/Ticket/ListAll/";
		$url .= $departmentid ."/";
		$url .= $ticketstatusid ."/";
		$url .= $ownerstaffid ."/";
		$url .= $userid ."/";
		$url .= $count ."&";
		$xml = $this->curl($url,array(),"GET");
		$ticketsList = $this->parseXMLtoArray($xml);
		// var_dump($ticketsList);
		return $ticketsList;
	}
	public function RetriveTicketsListForTable($userid){
		$TicketsList = $this->RetriveTicketsList($ownerstaffid=-1,$userid);
		// var_dump($TicketsList);
		if(empty($TicketsList["ticket"])){
			return false;
		}
		$output = array();
		$liststatus = $this->RetriveStatusList();
		$listtype = $this->RetriveTicketTypeList();
		foreach ($TicketsList["ticket"] as $key => $val) {
			if($val["departmentid"] != 0)
				$output[$TicketsList["ticket"][$key]["displayid"]] = array(
						'displayid'=>$val['displayid'],
						'departmentid'=>$val['departmentid'],
						'statusid'=>$val['statusid'],
						'statustitle'=>$liststatus[$val['statusid']]["title"],
						'userid'=>$val['userid'],
						'typeid'=>$val['typeid'],
						'typeid'=>$listtype[$val['typeid']]["title"],
						'fullname'=>$val['fullname'],
						'email'=>$val['email'],
						'lastreplier'=>$val['lastreplier'],
						'subject'=>$val['subject'],
						'creationtime'=>$val['creationtime']
					);
		}
		// if(empty($output)){
		// 	return false;
		// }
		return $output;
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