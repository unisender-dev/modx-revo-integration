<?php
/**
 * @package decors
 * @subpackage processors
 */


if (empty($scriptProperties['name'])) {
	$modx->error->addField('name', $modx->lexicon("Укажите название!"));
} else {
	$alreadyExists = $modx->getObject('UnisenderLists', array('name' => $scriptProperties['name']));
	if ($alreadyExists) {
		$modx->error->addField('name', $modx->lexicon("Список с таким названием уже существует!"));
	}
}

if ($modx->error->hasError()) {
	return $modx->error->failure();
}


$u_list = $modx->newObject('UnisenderLists');
$u_list->fromArray($scriptProperties);

$api_path = $this->modx->getOption('unisender.api_path');
require_once($api_path . 'Listr.php');
$api_key = $this->modx->getOption('unisender_api');

$list_obj = new Listr($api_key);

$list_res = $list_obj->createList($scriptProperties['name']);
if ($list_res['error'] == 1) return $modx->error->failure($modx->lexicon('Ошибка:' . $list_res['text']));
else $u_list->unisender_id = $list_res['id'];

/* save */
if ($u_list->save() == false) {
	return $modx->error->failure($modx->lexicon("Ошибка сохранения списка рассылки!"));
}


return $modx->error->success('', $u_list);