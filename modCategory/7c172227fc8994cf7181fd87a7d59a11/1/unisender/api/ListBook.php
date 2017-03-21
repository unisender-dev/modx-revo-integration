<?php
require_once("unisender_api.php");

class ListBook
{

	var $api;

	function __construct($ApiKey)
	{
		$this->api = new UniSenderApi($ApiKey, $Encoding);
	}

	function importContacts($field_names, $data, $double_option = null, $overwrite_tags = null, $overwrite_lists = null)
	{
		$array = array();
		for ($i = 0; $i < count($field_names); $i++)
			$array["field_names[" . $i . "]"] = $field_names[$i];

		for ($i = 0; $i < count($data); $i++)
			for ($j = 0; $j < count($data[$i]); $j++)
				$array["data[" . $i . "][" . $j . "]"] = $data[$i][$j];
		if ($double_option != null) $array['double_option'] = $double_option;
		if ($overwrite_tags != null) $array['overwrite_tags'] = $overwrite_tags;
		if ($overwrite_lists != null) $array['overwrite_lists'] = $overwrite_lists;
		file_put_contents('111.txt', var_export($array, true));
		$result = json_decode(
			$this->api->__call(
				"importContacts",
				array(
					$array
				)));
		if (isset($result->error))
			return array(
				"error" => 1,
				"text" => $result->error
			);
		else {
			$log = array();
			foreach ($result->result->log as $key => $value)
				$log[] = array(
					'index' => $value->index,
					'code' => $value->code
				);
			return array(
				"error" => 0,
				"result" => array(
					'total' => $result->result->total,
					'inserted' => $result->result->inserted,
					'updated' => $result->result->updated,
					'deleted' => $result->result->deleted,
					'new_emails' => $result->result->new_emails,
					'log' => $log
				)
			);
		}
	}

	function exportContacts($list_id = null, $field_names = null, $offset = null, $limit = null)
	{
		$array = array();
		if ($list_id != null) $array['list_id'] = $list_id;
		if ($field_names != null)
			foreach ($field_names as $key => $value)
				$array[$key] = $value;
		if ($offset != null) $array['offset'] = $offset;
		if ($limit != null) $array['limit'] = $limit;

		$result = json_decode(
			$this->api->__call(
				"importContacts",
				array(
					$array
				)));

		$result = json_decode(
			$this->api->__call(
				"exportContacts",
				array(array(
					"list_id" => ($list_id == null) ? "" : implode(',', $list_id),
					"field_names" => ($field_names == null) ? "" : implode(',', $field_names),
					"offset" => ($offset == null) ? "" : $offset,
					"limit" => $limit
				))));

		if (isset($result->error))
			return array(
				"error" => 1,
				"text" => $result->error
			);
		else {
			$field_names = array();
			foreach ($result->result->field_names as $key => $value)
				$field_names[] = $value;
			$data = array();
			$i = 0;
			foreach ($result->result->data as $key => $value) {
				foreach ($value as $key1 => $value1)
					$data[$i][] = $value1;
				$i++;
			}
			return array(
				"error" => 0,
				"result" => array(
					'field_names' => $field_names,
					'data' => $data
				)
			);
		}

	}

	function activateContacts($contact_type = null, $contacts = null, $list_ids = null, $invite = null)
	{
		$array = array();
		if ($contact_type != null) $array['contact_type'] = $contact_type;
		if ($contacts != null) $array['contacts'] = implode(',', $contacts);
		if ($list_ids != null) $array['list_ids'] = implode(',', $list_ids);
		if ($invite != null) $array['invite'] = $invite;

		$result = json_decode(
			$this->api->__call(
				"activateContacts",
				array(
					$array
				)));

		if (isset($result->error))
			return array(
				"error" => 1,
				"text" => $result->error
			);
		else {
			switch ($invite) {
				case 0:
					return array(
						'activated' => $result->result->activated,
						'activation_request_id' => $result->result->activation_request_id
					);
					break;
				case 1:
					return array(
						'campaign_id' => $result->result->campaign_id,
						'campaign_status' => $result->result->campaign_status
					);
					break;
			}
		}
	}

}

?>