<?php

define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/'); // Установка корневой директории
define('CORE_MODE','light');
require_once ROOT.'system/core.php'; // Инициация запуска системы
$QUERY=json_decode($_GET['query']); // Запрос
$RESPONSE=array();

if(defined('USER_ID')) { // Статус авторизации
    $RESPONSE['auth']=true;



}else{
    //$RESPONSE['auth']=false;
    $RESPONSE['auth']=true;
    $RESPONSE['tasks']=array();
    $RESPONSE['tasks'][0]=array();
    $RESPONSE['tasks'][0][0]='Название задачи';
    $RESPONSE['tasks'][0][1]='Описание задачи';
    $RESPONSE['tasks'][0][2]='Пономарев Владислав';
    $RESPONSE['tasks'][0][3]='10.03.2015';

    $RESPONSE['tasks'][1]=array();
    $RESPONSE['tasks'][1][0]='Название задачи 2';
    $RESPONSE['tasks'][1][1]='Описание задачи 2';
    $RESPONSE['tasks'][1][2]='Пономарев Владислав';
    $RESPONSE['tasks'][1][3]='11.03.2015';
}

echo(json_encode($RESPONSE)); // Ответ