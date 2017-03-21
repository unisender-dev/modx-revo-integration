<?php
require_once("unisender_api.php");

class SMS
{

	var $api;

	function __construct($ApiKey)
	{
		$this->api = new UniSenderApi($ApiKey, $Encoding);
	}

	function sendSms($phone, $sender, $text)
	{
		$result = json_decode(
			$this->api->__call(
				"sendSms",
				array(
					array(
						"phone" => $phone,
						"sender" => $sender,
						"text" => $text
					)
				)));

		if (isset($result->error))
			return array(
				"error" => 1,
				"text" => $result->error
			);
		else {
			return array(
				"error" => 0,
				"result" => array(
					"currency" => $result->result->currency,
					"price" => $result->result->price,
					"sms_id" => $result->result->sms_id
				)
			);
		}
	}

	function checkSms($sms_id)
	{
		$result = json_decode(
			$this->api->__call(
				"checkSms",
				array(
					array(
						"sms_id" => $sms_id
					)
				)));

		if (isset($result->error))
			return array(
				"error" => 1,
				"text" => $result->error
			);
		else {
			return array(
				"error" => 0,
				"status" => $result->result->status
			);
		}
	}
}

?>