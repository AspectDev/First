<?php
class Config{
	/* Настройки. */
	protected $ApiUrl = "http://dev.dalli-service.com/api/index.php?";
	protected $ApiKey = "aff40586-b89a-1b44-b592-8e271abb4aba";
	protected $ApiSignature;
	protected $ApiSecretKey = "ODkxZjBhZjctNzJiNy0xMTU0LTI5ZDktMGQ4ZGY1NzZkOGYzMmE5Mjc2N2QtN2FhOC04ZWE0LTlkMzEtNTA0ZGYzMWRjMWRj";
	protected $salt;

	function __construct(){
		$this->salt = mt_rand();
		$this->ApiSignature = base64_encode(hash_hmac('sha256',$this->salt,$this->ApiSecretKey,true));
	}
}

?>