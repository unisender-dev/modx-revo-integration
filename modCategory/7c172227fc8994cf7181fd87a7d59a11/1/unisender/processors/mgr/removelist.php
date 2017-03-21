<?php


$modx->setDebug(E_ALL & ~E_NOTICE); // sets error_reporting to everything except NOTICE remarks
$modx->setLogLevel(modX::LOG_LEVEL_DEBUG);
$modx->log(modX::LOG_LEVEL_DEBUG, 'RemoveList active');


/* get obj */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon("Ошибка, не указан id"));
$u_list = $modx->getObject('UnisenderLists', $scriptProperties['id']);
if (empty($u_list)) return $modx->error->failure($modx->lexicon("Ошибка: список не найден!"));


$l = $modx->newQuery('UnisenderSubscribers');
$l->where(array(
	'list_id' => $u_list->get('unisender_id')
));
$subscr = $modx->getCollection('UnisenderSubscribers', $l);

foreach ($subscr as $sub) {
	$modx->log(modX::LOG_LEVEL_DEBUG, $sub->get('id'));
	if ($sub->remove() == false) {
		return $modx->error->failure($modx->lexicon('Ошибка удаления статуса подписки'));
	}
}


$api_path = $this->modx->getOption('unisender.api_path');
require_once($api_path . 'Listr.php');
$api_key = $this->modx->getOption('unisender_api');

$list_obj = new Listr($api_key);

$list_res = $list_obj->deleteList($u_list->get('unisender_id'));
if ($list_res['error'] == 1) return $modx->error->failure($modx->lexicon('Ошибка:' . $list_res['text']));


/* remove */
if ($u_list->remove() == false) {
	return $modx->error->failure($modx->lexicon('Ошибка удаления списка рассылки'));
}
return $modx->error->success('', $u_list);