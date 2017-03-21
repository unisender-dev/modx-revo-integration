<?php

require_once dirname(dirname(__FILE__)).'/model/unisender/unisender.class.php';

$model = new Unisender($modx);

return $model->initialize('mgr');

?>