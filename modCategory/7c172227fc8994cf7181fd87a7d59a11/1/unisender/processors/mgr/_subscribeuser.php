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


$api_path = $this->modx->getOption('unisender.api_path');
require_once($api_path . 'User.php');
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
				//return $modx->error->failure($modx->lexicon("Here"));

				$user_obj = new User($api_key);//Unisender User

				//Обновляем телефон и мыло
				$phone = $profile->get('mobilephone');
				$email = $profile->get('email');

				switch (get_subscribe_type($scriptProperties['subscribe_sms'], $scriptProperties['subscribe_email'])) { //Меняем тип подписки на юнисендере
					case 0:
						return $modx->error->failure($modx->lexicon("Нужно указать хотя бы 1 тип подписки - SMS или Email"));
					case 1:
						$list_res = $user_obj->exclude('phone', $phone, array($ulist_id));
						$phone = '';
						break;
					case 2:
						$list_res = $user_obj->exclude('email', $email, array($ulist_id));
						$email = '';
						break;
					case 3:
						break;
				}

				if ($list_res['error'] == 1) {
					return $modx->error->failure($modx->lexicon('Ошибка изменения подписки:' . $list_res['text']));
				}

				$list_res = $user_obj->subscribe(array($ulist_id), array('email' => $email, 'Name' => $profile->get('fullname'), 'phone' => $phone), null, null, null, 3, null, null, 2);

				if ($list_res['error'] == 1) {
					return $modx->error->failure($modx->lexicon('Ошибка подписки:' . $list_res['text']));
				}

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


			$subscription->subscribe_type = get_subscribe_type($scriptProperties['subscribe_sms'], $scriptProperties['subscribe_email']);

			switch ($subscription->subscribe_type) {
				case 0:
					return $modx->error->failure($modx->lexicon("Нужно указать хотя бы 1 тип подписки - SMS или Email"));
				case 1:
					$phone = '';
					break;
				case 2:
					$email = '';
					break;
				case 3:
					break;
			}

			$user_obj = new User($api_key);
			//Подписываем его на новый список в Unisender
			$list_res = $user_obj->subscribe(array($ulist_id), array('email' => $email, 'Name' => $profile->get('fullname'), 'phone' => $phone), null, null, null, 3, null, null, 2);

			if ($list_res['error'] == 1) {
				return $modx->error->failure($modx->lexicon('Ошибка подписки:' . $list_res['text']));
			}

			if ($subscription->save() == false) {
				return $modx->error->failure($modx->lexicon("Ошибка при сохранении подписки!"));
			}


		}

	}
}

return $modx->error->success('', $subscription);