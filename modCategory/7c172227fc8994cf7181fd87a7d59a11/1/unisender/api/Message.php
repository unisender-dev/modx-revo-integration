<?php
require_once("unisender_api.php");

class Message
{

	var $api;

	function __construct($ApiKey)
	{
		$this->api = new UniSenderApi($ApiKey);
	}

	function createEmailMessage(
		$sender_name,
		$sender_email,
		$subject,
		$body,
		$list_id,
		$tag = null,
		$attachments = null,
		$lang = null,
		$series_day = null,
		$series_time = null)
	{

		$array = array();
		$array['sender_name'] = $sender_name;
		$array['sender_email'] = $sender_email;
		$array['subject'] = $subject;
		$array['body'] = $body;
		$array['list_id'] = $list_id;
		if ($tag != null) $array['tag'] = $tag;
		if ($attachments != null) {
			foreach ($attachments as $key => $value)
				$array['attachments[' . $key . ']'] = $value;
		}
		if ($lang != null) $array['lang'] = $lang;
		if ($series_day != null) $array['series_day'] = $series_day;
		if ($series_time != null) $array['series_time'] = $series_time;

		$result = json_decode(
			$this->api->__call(
				"createEmailMessage",
				array(
					$array
				)));

		if (isset($result->error))
			return array(
				"error" => 1,
				"text" => $result->error
			);
		else {
			return array(
				"error" => 0,
				"message_id" => $result->result->message_id
			);
		}
	}

	function createSmsMessage($sender, $body, $list_id, $tag = null, $series_day = null, $series_time = null)
	{
		$array = array();
		$array['sender'] = $sender;
		$array['body'] = $body;
		$array['list_id'] = $list_id;
		if ($tag != null) $array['tag'] = $tag;
		if ($series_day != null) $array['series_day'] = $series_day;
		if ($series_time != null) $array['series_time'] = $series_time;

		$result = json_decode(
			$this->api->__call(
				"createSmsMessage",
				array(
					$array
				)));

		if (isset($result->error))
			return array(
				"error" => 1,
				"text" => $result->error
			);
		else {
			return array(
				"error" => 0,
				"message_id" => $result->result->message_id
			);
		}
	}

}

?>