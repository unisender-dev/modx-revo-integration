<?php

/**
 * @version 0.9
 */
class UniSenderApi
{
	protected $ApiKey;
	protected $Encoding = 'UTF8';


	function __construct($ApiKey, $Encoding = 'UTF8')
	{
		$this->ApiKey = $ApiKey;
		if (!empty($Encoding)) {
			$this->Encoding = $Encoding;
		}
	}


	function __call($Name, $Arguments)
	{
		if (!is_array($Arguments) || empty($Arguments)) {
			$Params = array();
		} else {
			$Params = $Arguments[0];
		}
		return $this->callMethod($Name, $Params);
	}


	function subscribe($Params)
	{
		$Params = (array)$Params;
		if (empty($Params['request_ip'])) {
			$Params['request_ip'] = $this->getClientIp();
		}
		return $this->callMethod('subscribe', $Params);
	}


	protected function decodeJSON($JSON)
	{
		return json_decode($JSON);
	}


	protected function getClientIp()
	{
		if (!empty($_SERVER["REMOTE_ADDR"])) {
			$Result = $_SERVER["REMOTE_ADDR"];
		} else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$Result = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
			$Result = $_SERVER["HTTP_CLIENT_IP"];
		}
		if (preg_match('/([0-9]|[0-9][0-9]|[01][0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[0-9][0-9]|[01][0-9][0-9]|2[0-4][0-9]|25[0-5])){3}/', $Result, $Match)) {
			return $Match[0];
		}
		return $Result;
	}


	protected function iconv(&$Value, $Key)
	{
		$Value = iconv($this->Encoding, 'UTF8//IGNORE', $Value);
	}


	protected function mb_convert_encoding(&$Value, $Key)
	{
		$Value = mb_convert_encoding($Value, 'UTF8', $this->Encoding);
	}


	protected function callMethod($MethodName, $Params = array())
	{
		if ($this->Encoding != 'UTF8') {
			if (function_exists('iconv')) {
				array_walk_recursive($Params, array($this, 'iconv'));
			}
			if (function_exists('mb_convert_encoding')) {
				array_walk_recursive($Params, array($this, 'mb_convert_encoding'));
			}
		}
		$Params = array_merge((array)$Params, array('api_key' => $this->ApiKey, 'platform' => 'MODx Revo'));
		$ContextOptions = array(
			'http' =>
				array(
					'method' => 'POST',
					'header' => 'Content-type: application/x-www-form-urlencoded',
					'content' => http_build_query($Params)
				)
		);

		//     print_r( $Params);

		$Context = stream_context_create($ContextOptions);

		$Result = file_get_contents('https://api.unisender.com/ru/api/' . $MethodName . '?format=json', FALSE, $Context);

		return $Result;
	}
}