<?php

$modx->setDebug(E_ALL & ~E_NOTICE); // sets error_reporting to everything except NOTICE remarks
$modx->setLogLevel(modX::LOG_LEVEL_DEBUG);
$modx->log(modX::LOG_LEVEL_DEBUG, 'SubscribeUser active');


if (empty($scriptProperties['list_id'])) {
	$modx->error->addField('list_id', $modx->lexicon('Укажите список рассылки'));
}

if ($modx->error->hasError()) {
	return $modx->error->failure();
}

/*
$sp='';
foreach($scriptProperties as $key=>$val){
	$sp.='['.$key.']='.$val.' ';
}*/


function get_subscribe_type($subscr_sms, $subscr_email)
{ //Выдает цифровой код для типа подписки
	if ($subscr_sms == 'on' && $subscr_email == 'on')
		return 3;
	if ($subscr_sms == 'on' && $subscr_email == '')
		return 2;
	if ($subscr_sms == '' && $subscr_email == 'on')
		return 1;
	if ($subscr_sms == '' && $subscr_email == '')
		return 0;
}

function unsubscribe_phone($phone)
{ //Отписывает телефон

}


$api_path = $this->modx->getOption('unisender.api_path');
require_once($api_path . 'User.php');
require_once($api_path . 'ListBook.php');
$api_key = $this->modx->getOption('unisender_api');


$usersIdArr = array();
if ($scriptProperties['users'] != '')//Много юзеров
{
	$usersIdArr = explode(',', $scriptProperties['users']);

} else {
	$usersIdArr[] = $scriptProperties['id']; //Для 1 юзера

}

$list_id = $scriptProperties['list_id'];

$listArr = array();
if ($list_id == -2) //Подписка на все списки
{
	$c = $modx->newQuery('UnisenderLists');
	$all_lists = $modx->getIterator('UnisenderLists', $c);
	foreach ($all_lists as $ulist)
		$listArr[] = $ulist->get('unisender_id');
} else {
	$listArr[] = $list_id;
}


// Лимит в 500 записей
$usersIdArr_500 = array_chunk($usersIdArr, 500);
foreach ($usersIdArr_500 as $usersIdArr) {
	$data = array();
	foreach ($usersIdArr as $user_id) {

		$c = $modx->newQuery('UnisenderSubscribers');
		$c->where(array(
			'user_id' => $user_id
		));
		$current_subscribtions = $modx->getIterator('UnisenderSubscribers', $c); //Смотрим сначала все его подписки
		$subscribtionArr = array();


		//Получаем всё по юзеру
		$user = $modx->getObject('modUser', $user_id);
		$profile = $user->getOne('Profile');

		foreach ($listArr as $ulist_id) { //Идем по всем спискам, на которые надо подписаться


			$subscribed = false; //Сначала предполагаем что он ни на 1 список не подписан
			foreach ($current_subscribtions as $subscribtion_obj) {
				if ($subscribtion_obj->list_id == $ulist_id) {//Если нужный нам список у него уже есть

					$user_obj = new User($api_key);//Unisender User

					//Обновляем телефон и мыло
					$phone = $profile->get('mobilephone');
					$email = $profile->get('email');

					if ($phone == '') $scriptProperties['subscribe_sms'] = ''; //Если нет телефона, нельзя подписать на SMS


					$email_subscribed_list_id = $ulist_id;
					$phone_subscribed_list_id = $ulist_id;

					$type = get_subscribe_type(
						!empty($scriptProperties['subscribe_sms']) ? $scriptProperties['subscribe_sms'] : '',
						!empty($scriptProperties['subscribe_email']) ? $scriptProperties['subscribe_email'] : ''
					);

					switch ($type) { //Меняем тип подписки на юнисендере
						case 0:
							$phone_subscribed_list_id = 0;
							if ($phone != '') {
								$list_res = $user_obj->exclude('phone', $phone, array($ulist_id));
								if ($list_res['error'] == 1)
									return $modx->error->failure($modx->lexicon('Ошибка изменения подписки:' . $list_res['text']));

								$phone = '';
							}

							$email_subscribed_list_id = 0;
							if ($email != '') {
								$list_res = $user_obj->exclude('email', $email, array($ulist_id));
								if ($list_res['error'] == 1)
									return $modx->error->failure($modx->lexicon('Ошибка изменения подписки:' . $list_res['text']));

								$email = '';
							}

							$subscribtion_obj->remove(); //Удаляем из базы
							break;
						case 1:
							$phone_subscribed_list_id = 0;
							if ($phone != '')
								$list_res = $user_obj->exclude('phone', $phone, array($ulist_id)); //Убираем телефон из подписки
							$phone = '';
							break;
						case 2:
							$email_subscribed_list_id = 0;
							$list_res = $user_obj->exclude('email', $email, array($ulist_id)); //Убираем мыло из подписки
							$email = '';
							break;
						case 3:
							break;
					}

					if (empty($lists_res) || $list_res['error'] == 1) {
						return $modx->error->failure($modx->lexicon('Ошибка изменения подписки:' . $list_res['text']));
					}

					$data[] = array($email, $email_subscribed_list_id, $phone, $phone_subscribed_list_id, $profile->get('fullname'));

					//Меняем тип подписки в базе
					$subscribtion_obj->set('subscribe_type', get_subscribe_type($scriptProperties['subscribe_sms'], $scriptProperties['subscribe_email']));


					if ($subscribtion_obj->save() == false) {
						return $modx->error->failure($modx->lexicon("Ошибка при сохранении подписки!"));
					}

					$subscribtion = $subscribtion_obj;

					$subscribed = true;
					break;

				}
			}


			if ($subscribed == false)//Не нашелся, создаем новую запись
			{
				$subscription = $modx->newObject('UnisenderSubscribers');
				$subscription->list_id = $ulist_id;
				$subscription->user_id = $user_id;

				//Обновляем телефон и мыло
				$phone = $profile->get('mobilephone');
				$email = $profile->get('email');

				if ($phone == '') $scriptProperties['subscribe_sms'] = ''; //Если нет телефона, нельзя подписать на SMS
				$subscription->subscribe_type = get_subscribe_type($scriptProperties['subscribe_sms'], $scriptProperties['subscribe_email']);


				$email_subscribed_list_id = $ulist_id;
				$phone_subscribed_list_id = $ulist_id;

				switch ($subscription->subscribe_type) {
					case 0:
						$phone_subscribed_list_id = 0;
						if ($phone != '') {
							$list_res = $user_obj->exclude('phone', $phone, array($ulist_id));
							if ($list_res['error'] == 1) {
								return $modx->error->failure($modx->lexicon('Ошибка изменения подписки:' . $list_res['text']));
							}
							$phone = '';
						}
						$email_subscribed_list_id = 0;
						$list_res = $user_obj->exclude('email', $email, array($ulist_id));
						$email = '';
						break;
					case 1:
						$phone = '';
						$phone_subscribed_list_id = 0;
						break;
					case 2:
						$email = '';
						$email_subscribed_list_id = 0;
						break;
					case 3:
						break;
				}


				$data[] = array($email, $email_subscribed_list_id, $phone, $phone_subscribed_list_id, $profile->get('fullname'));

				if ($subscription->save() == false) {
					return $modx->error->failure($modx->lexicon("Ошибка при сохранении подписки!"));
				}


			}

		}
	}

	$imports = new ListBook($api_key);
	$field_names = array('email', 'email_list_ids', 'phone', 'phone_list_ids', 'Name');
	$list_res = $imports->importContacts($field_names, $data, 3, 1, 1);

	if ($list_res['error'] == 1) {
		return $modx->error->failure($modx->lexicon('Ошибка подписки:' . $list_res['text']));
	}

}


return $modx->error->success('', $subscription);
