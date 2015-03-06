<?php

define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/'); // Установка корневой директории
define('CORE_MODE','light');
require_once ROOT.'system/core.php'; // Инициация запуска системы
$QUERY=json_decode($_GET['query']); // Запрос
$RESPONSE=array();

if(defined('USER_ID')) { // Статус авторизации
    $RESPONSE['auth']=true;
}else{
    $RESPONSE['auth']=false;
}

echo(json_encode($RESPONSE)); // Ответ