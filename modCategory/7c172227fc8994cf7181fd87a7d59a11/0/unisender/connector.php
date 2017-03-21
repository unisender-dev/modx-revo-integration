<?php
/**
 * Unisender Connector
 *
 * @package unisender
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$namespace = $modx->getObject('modNamespace', 'unisender');
$corePath = MODX_CORE_PATH.'components/unisender/';
require_once $corePath.'model/unisender/unisender.class.php';
$modx->weather = new Unisender($modx);
$modx->lexicon->load('unisender:default');

/* handle request */
$path = $modx->getOption('processorsPath', $modx->unisender->config, $corePath.'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));
?>