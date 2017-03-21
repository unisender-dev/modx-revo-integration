<?php

/* get obj */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon("Ошибка, не указан id"));
$u_list = $modx->getObject('UnisenderLists', $scriptProperties['id']);
if (empty($u_list)) return $modx->error->failure($modx->lexicon("Ошибка: список не найден!"));


/* set fields */
$u_list->fromArray($scriptProperties);


$api_path = $this->modx->getOption('unisender.api_path');
require_once($api_path . 'Listr.php');
$api_key = $this->modx->getOption('unisender_api');

$list_obj = new Listr($api_key);

$list_res = $list_obj->updateList($u_list->get('unisender_id'), $scriptProperties['name']);
if ($list_res['error'] == 1) return $modx->error->failure($modx->lexicon('Ошибка:' . $list_res['text']));


/* save */
if ($u_list->save() == false) {
	return $modx->error->failure($modx->lexicon('Ошибка сохранения списка рассылки!'));
}


return $modx->error->success('', $u_list);