<?php
require_once("unisender_api.php");

class User
{

	var $api;

	function __construct($ApiKey, $Encoding = 'UTF8')
	{
		$this->api = new UniSenderApi($ApiKey, $Encoding);
	}

	function subscribe($list_ids, $fields, $tags = null, $request_ip = null, $request_time = null, $double_option = null, $confirm_ip = null, $confirm_time = null, $overwrite = null)
	{
		$array = array();
		$array['list_ids'] = implode(',', $list_ids);
		foreach ($fields as $key => $value)
			$array['fields[' . $key . ']'] = $value;
		if ($tags != null) $array['tags'] = implode(',', $tags);
		if ($request_ip != null) $array['request_ip'] = $request_ip;
		if ($request_time != null) $array['request_time'] = $request_time;
		if ($double_option != null) $array['double_option'] = $double_option;
		if ($confirm_ip != null) $array['confirm_ip'] = $confirm_ip;
		if ($confirm_time != null) $array['confirm_time'] = $confirm_time;
		if ($overwrite != null) $array['overwrite'] = $overwrite;

		$result = json_decode(
			$this->api->__call(
				"subscribe",
				array(
					$array
				)));

		if (isset($result->error))
			return array(
				"error" => 1,
				"text" => $result->error
			);
		else
			return array(
				"error" => 0,
				"person_id" => $result->result->person_id
			);
	}

	function exclude($contact_type, $contact, $list_ids = null)
	{
		$array = array();
		$array['contact_type'] = $contact_type;
		$array['contact'] = $contact;
		if ($list_ids != null) $array['list_ids'] = implode(',', $list_ids);

		$result = json_decode(
			$this->api->__call(
				'exclude',
				array(
					$array
				)
			));

		if (isset($result->error))
			return array(
				"error" => 1,
				"text" => $result->error
			);
		else
			return array(
				"error" => 0
			);
	}

	function unsubscribe($contact_type, $contact, $list_ids = null)
	{
		$array = array();
		$array['contact_type'] = $contact_type;
		$array['contact'] = $contact;
		if ($list_ids != null) $array['list_ids'] = implode(',', $list_ids);

		$result = json_decode(
			$this->api->__call(
				'unsubscribe',
				array(
					$array
				)
			));

		if (isset($result->error))
			return array(
				"error" => 1,
				"text" => $result->error
			);
		else
			return array(
				"error" => 0
			);
	}
}

?>