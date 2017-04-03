<?php
/**
 * @package unisender
 */
//Parameters
//lang - not required, default lang is RU (russian)
//  supported: ru (Русский),ua (Українська),en (English),md(hu) - Hungarian ,ge - Georgian
//list - list id to subscribe
//tags - tags for user

include_once $modx->getOption('core_path').'components/unisender/elements/subscribe.lang.php';


if(!isset($lang))$lang='ru';
if($lang=='hu')$lang='md';

if(!isset($tags))$tags='modx_site';

if(empty($list)) {
	$list = $modx->resource->get('unisender_list_ids');
	$list = str_replace('||', ',', $list[1]);
}
if ( empty($list) ) {
	return $langArr[$lang.'.error'].' укажите список подписки!';
}


$api_key=$modx->getOption('unisender_api');
if(!isset($api_key))return $langArr[$lang.'.error'].' укажите API key в настройках системы!';


$user_lists = $list;


include_once $modx->getOption('core_path').'components/unisender/elements/json/JSON.php';

if( !function_exists('json_encode') ) {
    function json_encode($data) {
        $json = new Services_JSON();
        return( $json->encode($data) );
    }
}

// Future-friendly json_decode
if( !function_exists('json_decode') ) {
    function json_decode($data, $bool=false) {
        if ($bool) {
            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        } else {
            $json = new Services_JSON();
        }
        return( $json->decode($data) );
    }
}
//includeonce $modx->config['base_path'] . 'assets/modules/json/json.define.php';


$show='';


$form.='<form action="" method="POST">';
$form.='<input class="field_new1" type="text" name="email" value="'.$langArr[$lang.'.email'].'" onFocus="this.value=\'\'" onBlur="if (this.value==\'\'){this.value=\''.$langArr[$lang.'.email'].'\'}"/ ><input class="field_new2" type="text" name="name" value="'.$langArr[$lang.'.name'].'"  onFocus="this.value=\'\'" onBlur="if (this.value==\'\'){this.value=\''.$langArr[$lang.'.name'].'\'}" /><input class="btn_new" type="submit" name="subscribe" value="OK"  />
</form>';






if(isset($_POST['subscribe']) && $_POST['subscribe']!='') //Поехали подписываться
{
$_POST['subscribe']='';

// Данные о новом подписчике

if(isset($_POST['email']) && $_POST['email']!='' && isset($_POST['name']) && $_POST['name']!='' ){
   $user_email = $_POST['email'];
   $user_name = $_POST['name'];
}
  else {
echo '<p class="error">'.$langArr[$lang.'.not_all'].'<p>'.$form;
return;
}

if ($_SERVER['HTTP_X_FORWARD_FOR']) {
$user_ip = $_SERVER['HTTP_X_FORWARD_FOR'];
} else {
$user_ip = $_SERVER['REMOTE_ADDR'];
}

$user_tag = urlencode($tags);

// Создаём POST-запрос
$POST = array (
  'api_key' => $api_key,
  'list_ids' => $user_lists,
  'fields[email]' => $user_email,
  'fields[Name]' => $user_name,
  'request_ip' => $user_ip,
  'tags' => $user_tag,
  'platform' => 'MODx Revo'
);
//print_r($POST);

// Устанавливаем соединение
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_URL, 
            'https://api.unisender.com/ru/api/subscribe?format=json');
$result = curl_exec($ch);
//echo $result;
if ($result) {
     // Раскодируем ответ API-сервера
     $jsonObj = json_decode($result);

     if(null===$jsonObj) {
       // Ошибка в полученном ответе
      $html= "<p>JSON error</p>";

     }
     elseif(!empty($jsonObj->error)) {
       // Ошибка добавления пользователя
       
       $html= "<p>" .$langArr[$lang.'.error'] . $jsonObj->error . "(code: " . $jsonObj->code . ")</p>".$form;

     } else {
       // Новый пользователь успешно добавлен
       
       $html= "<p>".$langArr[$lang.'.congratulation']."</p>";
     }
  } else {
  // Ошибка соединения с API-сервером
  $html="<p>".$langArr[$lang.'.connect_error']."</p>".$form;
 
  }
}//endif subscribe
else{ //Просто выводим форму
$html=$form;
}

echo $html;
