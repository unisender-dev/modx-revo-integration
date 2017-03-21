<?php
/**
 * Get a list of Lists
 *
 * /* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start', $scriptProperties, 0);
$limit = $modx->getOption('limit', $scriptProperties, 20);
$sort = $modx->getOption('sort', $scriptProperties, 'id');
$dir = $modx->getOption('dir', $scriptProperties, 'ASC');
$query = $modx->getOption('query', $scriptProperties, '');
$combobox = $modx->getOption('combo', $_REQUEST, false);

$c = $modx->newQuery('UnisenderLists');

if (!empty($query)) {
	$c->where(array(
		'name:LIKE' => '%' . $query . '%',

	));
}

$count = $modx->getCount('UnisenderLists', $c);
$c->sortby($sort, $dir);
if ($isLimit) $c->limit($limit, $start);
$unisender_lists = $modx->getIterator('UnisenderLists', $c);

$list = array();


if (!empty($combobox) && $combobox == 'true' && $count == 0) {
	$list[] = array(
		'unisender_id' => 0,
		'name' => "Нет списков"
	);
}
foreach ($unisender_lists as $u_list) {
	$listArray = $u_list->toArray();
	$list[] = $listArray;
}
return $this->outputArray($list, $count);