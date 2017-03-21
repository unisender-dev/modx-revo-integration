<?php
require_once("Listr.php");

$api_key = "5h8qcuoxppcqgwjy98zzzs4tmbt9z5yygyjsjioy";

$list = new Listr($api_key);
echo "<pre>";
print_r($list->getLists());
echo "</pre>";
?>