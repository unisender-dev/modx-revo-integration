<?php

$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start', $_REQUEST, 0);
$limit = $modx->getOption('limit', $_REQUEST, 20);
$sort = $modx->getOption('sort', $_REQUEST, 'id');
$dir = $modx->getOption('dir', $_REQUEST, 'DESC');
$query = $modx->getOption('query', $_REQUEST, '');

function get_subscription_by_type($type)
{
	switch ($type) {
		case 0:
			return;
		case 1:
			return '(Email)';
		case 2:
			return '(SMS)';
		case 3:
			return '(SMS + Email)';
	}
}

$this->modx =& $modx;
$api_path = $this->modx->getOption('unisender.api_path');
require_once($api_path . 'Listr.php');
require_once($api_path . 'User.php');

$api_key = $this->modx->getOption('unisender_api');

$listName = array();
$l = $modx->newQuery('UnisenderLists');
$lists = $modx->getCollection('UnisenderLists');

foreach ($lists as $ulist)
	$listName[$ulist->get('unisender_id')] = $ulist->get('name');

$c = $modx->newQuery('modUser');

if (!empty($query)) {
	$c->where(array(
		'username:LIKE' => '%' . $query . '%',
	));
}

$users = $modx->getCollection('modUser', $c);

$list = array();
$i = 0;


$model = new Unisender($modx);

foreach ($users as $user) {
	$profile = $user->getOne('Profile');
	$subs = $modx->newQuery('UnisenderSubscribers');
	$subs->where(array(
		'user_id' => intval($user->get('id'))
	));
	$current_subscribtions = $modx->getIterator('UnisenderSubscribers', $subs); //Смотрим все его подписки
	$profile->subscribe = '';
	$subscribe_arr = array();

	foreach ($current_subscribtions as $subscribtion) {
		$subscribe_arr[] = $listName[$subscribtion->get('list_id')] . get_subscription_by_type($subscribtion->get('subscribe_type'));
	}
	$array = array();
	$array['id'] = $user->get('id');
	$array['subscribe'] = implode(', ', $subscribe_arr);
	$array['username'] = $user->get('username');
	$array['fullname'] = $profile->get('fullname');
	$array['email'] = $profile->get('email');
	$array['phone'] = $profile->get('phone');

	$list[] = $array;
}

return $this->outputArray($list, $count);


?>