<?php

$modx->setDebug(E_ALL & ~E_NOTICE); // sets error_reporting to everything except NOTICE remarks
$modx->setLogLevel(modX::LOG_LEVEL_DEBUG);
$modx->log(modX::LOG_LEVEL_DEBUG, 'UnisenderAutosend active');

$api_path = $modx->getOption('unisender.api_path');

$error_text = '';
$api_key = $modx->getOption('unisender_api');
if ($api_key == '') $error_text .= 'unisender_api';

$sender_name = $modx->getOption('unisender_sender_name');
if ($sender_name == '') $error_text .= 'unisender_sender_name';

$sender_email = $modx->getOption('unisender_sender_email');
if ($sender_email == '') $error_text .= 'unisender_sender_email';

$msg_title_field = $modx->getOption('unisender_message_title_field');
if ($msg_title_field == '') $error_text .= 'unisender_message_title_field';

if ($error_text != '')
	$modx->event->output('Нужно задать в настройках системы ' . $error_text);

//GET TVs ids
$tvCollection = $modx->getCollection('modTemplateVar', "`name` IN ('unisender_list_ids', 'unisender_send_date', 'unisender_send_mode', 'unisender_template_chunk')");
$tv_id = array();
foreach ($tvCollection as $tv) {
	switch ($tv->get('name')) {
		case 'unisender_list_ids':
			$tv_id['unisender_list_ids'] = $tv->get('id');
			break;
		case 'unisender_send_date':
			$tv_id['unisender_send_date'] = $tv->get('id');
			break;
		case 'unisender_send_mode':
			$tv_id['unisender_send_mode'] = $tv->get('id');
			break;
		case 'unisender_template_chunk':
			$tv_id['unisender_template_chunk'] = $tv->get('id');
			break;
	}
}
foreach ($tv_id as $n => $t) {
	$modx->log(modX::LOG_LEVEL_DEBUG, $n . '=>' . $t);
}
if (!isset($_POST['tv' . $tv_id['unisender_send_mode']]))
	return; //Ну нету ТВшки такой.. :-)

$tv_val = array();

$tv_val['unisender_list_ids'] = $_POST['tv' . $tv_id['unisender_list_ids']];
$tv_val['unisender_send_date'] = $_POST['tv' . $tv_id['unisender_send_date']];
$tv_val['unisender_send_mode'] = $_POST['tv' . $tv_id['unisender_send_mode']];
$tv_val['unisender_template_chunk'] = $_POST['tv' . $tv_id['unisender_template_chunk']];


if ($tv_val['unisender_send_mode'] > 0)//Создаем сообщение как минимум
{
	$messageBody = '';
	$messageTitle = $_POST[$msg_title_field];

	$resourceArr = $_POST;
	if (intval($tv_val['unisender_template_chunk']) != -1)
		$messageBody = $modx->getChunk($tv_val['unisender_template_chunk'], $resourceArr);
	else $messageBody = $_POST['content'];

	require_once $api_path = $api_path . 'unisender_api.php';
	require_once $api_path . 'Message.php';

	$message_obj = new Message($api_key);

	$message_res = array();

	foreach ($tv_val['unisender_list_ids'] as $list_id) {

		$message_res[] = $message_obj->createEmailMessage($sender_name, $sender_email, $messageTitle, $messageBody, $list_id);

	}

	$i = 0;
	$error_text = '';
	$msgIdArr = array();
	foreach ($message_res as $res) {
		if ($res['error'] == 1) $error_text .= 'Сообщение для списка ' . $tv_val['unisender_list_ids'][$i] . ' не создано, ошибка:" ' . $res['text'] . '"<br>';
		else {
			$msgIdArr[] = $res['message_id'];
			$modx->log(modX::LOG_LEVEL_DEBUG, 'Message created:' . $res['message_id']);
		}
		$i++;
	}


	if ($error_text != '') {
		if ($tv_val['unisender_send_mode'] == 1) $modx->event->output($error_text);
		elseif ($tv_val['unisender_send_mode'] > 1) $modx->event->output($error_text . 'Кампании не созданы - проверьте списки сообщений');
	}


	if ($error_text == '' && $tv_val['unisender_send_mode'] > 1)//Создаем кампанию
	{
		require_once $api_path . 'Campaign.php';
		$campaign_obj = new Campaign($api_key);

		$error_text = '';
		$i = 0;
		foreach ($msgIdArr as $msg_id) {
			if ($tv_val['unisender_send_date'] != '') $tv_val['unisender_send_date'] = date('Y-m-d h:i', strtotime($tv_val['unisender_send_date']));

			$modx->log(modX::LOG_LEVEL_DEBUG, 'Campaign start time: ' . $tv_val['unisender_send_date']);
			$campaign_res = $campaign_obj->createCampaign($msg_id, $tv_val['unisender_send_date']);
			if ($campaign_res['error'] == 1)
				$error_text .= 'Кампания для списка ' . $tv_val['unisender_list_ids'][$i] . ', cooбщение ' . $msg_id . ' не создана, ошибка:" ' . $campaign_res['text'] . '"<br>';
			else $modx->log(modX::LOG_LEVEL_DEBUG, 'Campaign created. Message_id:' . $msg_id . ' campaign id:' . $campaign_res['result']['campaign_id'] . ' status:' . $campaign_res['result']['status']);
			$i++;
		}

		if ($error_text != '') $modx->event->output($error_text);
		else $_POST['tv' . $tv_id['unisender_send_mode']] = 0;
	}

}

?>