<?php
/**
 * @package unisender
 */
if(!isset($mode))return 'Установите режим (&mode)';

switch($mode){
case 'get_lists':


$usndr= $modx->getService('unisender','Unisender',$modx->getOption('core_path').'components/unisender/model/unisender/',$scriptProperties);
if (!($usndr instanceof Unisender)) return 'Unisender init error!';

$c = $modx->newQuery('UnisenderLists');
$lists = $modx->getCollection('UnisenderLists',$c);

$outArr=array();;
foreach($lists as $u_list)
$outArr[]=$u_list->get('name').'=='.$u_list->get('unisender_id');

return implode('||',$outArr);

break;
case 'get_templates':
$cat_obj=$modx->getObject('modCategory',array('category:LIKE'=>'%Unisender%'));
$unisender_category=$cat_obj->get('id');

$chunks=$modx->getCollection('modChunk',array('category'=>$unisender_category));
$html='';
foreach($chunks as $chunk){
$html.=$chunk->get('name').'=='.$chunk->get('name').'||';
}
$html.='Пустой шаблон==-1';
return $html;
break;

default: return 'Режим не поддерживается.';
}
