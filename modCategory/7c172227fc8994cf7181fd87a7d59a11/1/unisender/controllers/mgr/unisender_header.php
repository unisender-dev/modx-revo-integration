<?php
$modx->regClientStartupScript($unisender->config['jsUrl'].'unisender.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
	Ext.onReady(function() {
		Unisender.config = '.$modx->toJSON($unisender->config).';
		Unisender.config.connector_url = "'.$unisender->config['connectorUrl'].'";
		Unisender.request = '.$modx->toJSON($_GET).';
		Unisender.dateFormat = "d.m.Y";
	});
</script>');

return '';

?>