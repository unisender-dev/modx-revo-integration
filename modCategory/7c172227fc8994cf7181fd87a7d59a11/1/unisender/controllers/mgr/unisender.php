<?php
$modx->regClientStartupScript($unisender->config['jsUrl'].'combos.js');
$modx->regClientStartupScript($unisender->config['jsUrl'].'home.js');
$modx->regClientStartupScript($unisender->config['jsUrl'].'panel.js');
$modx->regClientStartupScript($unisender->config['jsUrl'].'subscribers.grid.js');
$modx->regClientStartupScript($unisender->config['jsUrl'].'lists.grid.js');


$output = '<div id="unisender-main-div"></div>';

return $output;

?>
