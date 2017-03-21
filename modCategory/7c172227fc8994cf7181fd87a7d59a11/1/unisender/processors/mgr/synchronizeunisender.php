<?php


$api_path = $this->modx->getOption('unisender.api_path');
require_once($api_path . 'Listr.php');
$api_key = $this->modx->getOption('unisender_api');

$l = $modx->newQuery('UnisenderLists');
$lists = $modx->getCollection('UnisenderLists');


$list_obj = new Listr($api_key);
$list_res = $list_obj->getLists();
if ($list_res['error'] == 1) return $modx->error->failure($modx->lexicon('Ошибка синхронизации:' . $list_res['text']));
else {

	foreach ($list_res['result'] as $res) {
		$list_found = false; //Признак, что список есть в базе

		foreach ($lists as $u_list) {
			if ($res['id'] == $u_list->get('unisender_id')) //Такой список есть
			{
				if ($res['title'] != $u_list->get('name'))//Отличается имя, обновляем имя
				{
					$u_list->set('name', $res['title']);
					if ($u_list->save() == false) {
						return $modx->error->failure($modx->lexicon("Ошибка сохранения списка рассылки!"));
					}
				}
				$list_found = true;
				break;
			}

		}

		if ($list_found == false)//Так и не нашли в базе, нужно создать
		{

			$u_list = $modx->newObject('UnisenderLists');
			$u_list->unisender_id = $res['id'];
			$u_list->name = $res['title'];
			if ($u_list->save() == false) {
				return $modx->error->failure($modx->lexicon("Ошибка сохранения списка рассылки!"));
			}
		}


	}

}


return $modx->error->success('', $u_list);