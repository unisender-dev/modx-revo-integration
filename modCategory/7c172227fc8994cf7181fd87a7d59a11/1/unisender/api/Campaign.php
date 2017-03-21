<?php
require_once("unisender_api.php");

class Campaign
{

	var $api;

	function __construct($ApiKey)
	{
		$this->api = new UniSenderApi($ApiKey);
	}

	function createCampaign(
		$message_id,
		$start_time = null,
		$timezone = null,
		$track_read = null,
		$track_links = null,
		$contacts = null,
		$contacts_url = null,
		$defer = null,
		$track_ga = null,
		$ga_medium = null,
		$ga_source = null,
		$ga_campaign = null,
		$ga_content = null,
		$ga_term = null)
	{

		$array = array();
		$array['message_id'] = $message_id;
		if ($start_time != null) $array['start_time'] = $start_time;
		if ($timezone != null) $array['timezone'] = $timezone;
		if ($track_read != null) $array['track_read'] = $track_read;
		if ($track_links != null) $array['track_links'] = $track_links;
		if ($contacts != null) $array['contacts'] = implode(',', $contacts);
		if ($contacts_url != null) $array['contacts_url'] = $contacts_url;
		if ($defer != null) $array['defer'] = $defer;
		if ($track_ga != null) $array['track_ga'] = $track_ga;
		if ($ga_medium != null) $array['ga_medium'] = $ga_medium;
		if ($ga_source != null) $array['ga_source'] = $ga_source;
		if ($ga_campaign != null) $array['ga_campaign'] = $ga_campaign;
		if ($ga_content != null) $array['ga_content'] = $ga_content;
		if ($ga_term != null) $array['ga_term'] = $ga_term;

		$result = json_decode(
			$this->api->__call(
				"createCampaign",
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
				"result" => array(
					"campaign_id" => $result->result->campaign_id,
					"status" => $result->result->status,
					"count" => $result->result->count
				)
			);
		}
	}

	function getCampaignStatus($campaign_id)
	{
		$result = json_decode(
			$this->api->__call(
				"getCampaignStatus",
				array(
					array(
						"campaign_id" => $campaign_id
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
					"status" => $result->result->status,
					"creation_time" => $result->result->creation_time,
					"start_time" => $result->result->start_time
				)
			);
		}
	}

	function getCampaignDeliveryStats($campaign_id, $changed_since = null)
	{
		$array = array();
		$array['campaign_id'] = $campaign_id;
		if ($changed_since != null) $array['changed_since'] = $changed_since;

		$result = json_decode(
			$this->api->__call(
				"getCampaignDeliveryStats",
				array(
					$array
				)));

		if (isset($result->error))
			return array(
				"error" => 1,
				"text" => $result->error
			);
		else {
			$fields = array();
			foreach ($result->result->fields as $key => $value)
				$fields[] = $value;

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
					"fields" => $fields,
					"data" => $data
				)
			);
		}
	}

	function getCampaignAggregateStats($campaign_id)
	{
		$result = json_decode(
			$this->api->__call(
				"getCampaignAggregateStats",
				array(
					array(
						"campaign_id" => $campaign_id
					)
				)));

		if (isset($result->error))
			return array(
				"error" => 1,
				"text" => $result->error
			);
		else {
			$data = array();
			foreach ($result->result->data as $key => $value) {
				$data[] = $value;
			}

			return array(
				"error" => 0,
				"result" => array(
					"total" => $result->result->total,
					"data" => $data
				)
			);
		}
	}

	function sendEmail(
		$email,
		$sender_name,
		$sender_email,
		$subject,
		$body,
		$list_id,
		$attachments = null,
		$lang = null,
		$track_read = null,
		$track_links = null,
		$attach_multi = null
	)
	{

		$array = array();
		if (is_array($email)) {
			$i = 0;
			foreach ($email as $key => $value) {
				$array['email[' . $i . ']'] = $value;
				$i++;
			}
		} else {
			$array['email'] = $email;
		}

		if (is_array($sender_name)) {
			$i = 0;
			foreach ($sender_name as $key => $value) {
				$array['sender_name[' . $i . ']'] = $value;
				$i++;
			}
		} else {
			$array['sender_name'] = $sender_name;
		}

		if (is_array($sender_email)) {
			$i = 0;
			foreach ($sender_email as $key => $value) {
				$array['sender_email[' . $i . ']'] = $sender_email;
				$i++;
			}
		} else {
			$array['sender_email'] = $sender_email;
		}

		if (is_array($subject)) {
			$i = 0;
			foreach ($subject as $key => $value) {
				$array['subject[' . $i . ']'] = $subject;
				$i++;
			}
		} else {
			$array['subject'] = $subject;
		}

		if (is_array($body)) {
			$i = 0;
			foreach ($body as $key => $value) {
				$array['body[' . $i . ']'] = $body;
				$i++;
			}
		} else {
			$array['body'] = $body;
		}

		$array['list_id'] = $list_id;

		if ($attachments != null)
			if (is_array($attachments[0])) {
				$array['attach_multi'] = 1;
				$i = 0;
				foreach ($attachments as $key => $value) {
					foreach ($value as $key1 => $value1)
						$array['attachments[' . $i . '][' . $key1 . ']'] = $value1;
					$i++;
				}
			} else {
				$array['attach_multi'] = 0;
				foreach ($attachments as $key => $value)
					$array['attachments[' . $key . ']'] = $value;
			}

		if ($lang != null) $array['lang'] = $lang;

		if ($track_read != null)
			if (is_array($track_read)) {
				$i = 0;
				foreach ($track_read as $key => $value) {
					$array['track_read[' . $i . ']'] = $track_read;
					$i++;
				}
			} else {
				$array['track_read'] = $track_read;
			}

		if ($track_links != null)
			if (is_array($track_links)) {
				$i = 0;
				foreach ($track_links as $key => $value) {
					$array['track_links[' . $i . ']'] = $track_links;
					$i++;
				}
			} else {
				$array['track_links'] = $track_links;
			}

		$result = json_decode(
			$this->api->__call(
				"sendEmail",
				array(
					$array
				)));

		if (is_array($email)) {
			$i = 0;
			foreach ($result->result as $key => $value) {
				foreach ($value as $key1 => $value1)
					$res[$i][$key1] = $value1;
				$i++;
			}
			return array(
				"result" => $res
			);
		} else {
			if (isset($result->error))
				return array(
					"error" => 1,
					"text" => $result->error
				);
			else {
				return array(
					"error" => 0,
					"email_id" => $result->result->email_id
				);
			}
		}
	}

	function checkEmail($email_id)
	{
		$result = json_decode(
			$this->api->__call(
				"checkEmail",
				array(
					array(
						"email_id" => $email_id
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

	function updateOptInEmail($sender_name, $sender_email, $subject, $body, $list_id)
	{
		$result = json_decode(
			$this->api->__call(
				"updateOptInEmail",
				array(
					array(
						"sender_name" => $sender_name,
						"sender_email" => $sender_email,
						"subject" => $subject,
						"body" => $body,
						"list_id" => $list_id
					)
				)));

		if (isset($result->error))
			return array(
				"error" => 1,
				"text" => $result->error
			);
		else {
			return array(
				"error" => 0
			);
		}
	}
}

?>