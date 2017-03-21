<?php
require_once("unisender_api.php");

class Listr
{

	var $api;

	function __construct($ApiKey)
	{
		$this->api = new UniSenderApi($ApiKey, $Encoding);
	}

	function getLists()
	{
		$content = json_decode(
			$this->api->__call(
				"getLists",
				null
			));
		$result = array();
		foreach ($content->result as $key => $value) {
			$result[] = array(
				'id' => $value->id,
				'title' => $value->title
			);
		}
		if (isset($content->error))
			return array(
				"error" => 1,
				"text" => $content->error
			);
		else
			return array(
				"error" => 0,
				"result" => $result
			);
	}

	function createList($title, $before_subscribe_url = null, $after_subscribe_url = null)
	{
		$array = array("title" => $title);
		if ($before_subscribe_url != null) $array['before_subscribe_url'] = $before_subscribe_url;
		if ($after_subscribe_url != null) $array['after_subscribe_url'] = $after_subscribe_url;
		$result = json_decode(
			$this->api->__call(
				"createList",
				array(
					$array
				)));
		if (isset($result->result->id))
			return array(
				"error" => 0,
				"id" => $result->result->id
			);
		else
			return array(
				"error" => 1,
				"text" => $result->error
			);
	}

	function updateList($list_id, $title = null, $before_subscribe_url = null, $after_subscribe_url = null)
	{
		$array = array('list_id' => $list_id);
		if ($title != null) $array['title'] = $title;
		if ($before_subscribe_url != null) $array['before_subscribe_url'] = $before_subscribe_url;
		if ($after_subscribe_url != null) $array['after_subscribe_url'] = $after_subscribe_url;
		$result = json_decode(
			$this->api->__call(
				"updateList",
				array(
					$array
				)));
		if (isset($result->error))
			return array(
				"error" => 1,
				"text" => $result->error
			);
		else
			return array("error" => 0);
	}

	function deleteList($id)
	{
		$result = json_decode(
			$this->api->__call(
				"deleteList",
				array(
					array(
						"list_id" => $id
					)
				)));
		if (isset($result->error))
			return array(
				"error" => 1,
				"text" => $result->error
			);
		else
			return array("error" => 0);
	}

}

?>