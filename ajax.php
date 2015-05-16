<?php

define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/'); // Установка корневой директории
define('AJAX','CORE'); // Блокируем маршрутизацию

require_once ROOT.'system/core.php'; // Инициация запуска системы

if(!isset($_GET['query'])OR!isset($_GET['ver'])){
    exit;
}

$QUERY=json_decode($_GET['query']); // Запрос
$QUERY=objectToArray($QUERY); // Трансформируем из Object в Array
$RESPONSE=array();
if($_GET['ver']<VERSION){
    $RESPONSE['old_version']=true;
    exit(json_encode($RESPONSE));
}

if(defined('USER_ID')) { // Статус авторизации
    $RESPONSE['auth'] = true;
    if (isset($QUERY['action'])) {
        if ($QUERY['action'] == 'check') {
            $NEW = [];
            $get = DB::select('notifications', ['*'], 'iduser=' . USER_ID);
            if ($get) {
                while ($notify = mysqli_fetch_assoc($get)) {
                    if ($notify['type'] == 'new_task') {
                        $task = TM::get_task($notify['value']);
                        if($task){
                        $task['new']=true;
                        if (!isset($NEW['TASK'])) {
                            $NEW['TASK'] = [];
                        }
                        $NEW['TASK'][] = $task;
                        }else{
                            DB::delete('notifications','idnotification='.$notify['idnotification']);
                        }
                    }
                    if ($notify['type'] == 'new_comment') {
                        $comment = DB::select('comments', ['*'], 'id=' . $notify['value']);
                        $comment = mysqli_fetch_assoc($comment);
                        $comment['usercom_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users', ['firstname', 'lastname'], 'id=' . $comment['usercom'])));
                        $comment['usercom_photo'] = User::get_user($comment['usercom'])['photo'];
                        if (!isset($NEW['COMMENT'])) {
                            $NEW['COMMENT'] = [];
                        }
                        $NEW['COMMENT'][] = $comment;
                        DB::delete('notifications','idnotification='.$notify['idnotification']);
                    }
                }
                $RESPONSE['NEW'] = $NEW;
                $RESPONSE['check'] = true;
            } else {
                $RESPONSE['check'] = false;
            }
            $RESPONSE['time']=time()+(3600*5);
        }
        if ($QUERY['action'] == 'load_db'){
            $DB=[];
            $DB['GROUP']=User::get_groups();
            $DB['PROJECT']=TM::get_projects($DB['GROUP']);
            $DB['TASK']=TM::get_tasks($DB['PROJECT']);
            $RESPONSE['DB']=$DB;
        }
        if ($QUERY['action'] == 'logout') { // Выход из системы
            $out = User::logout();
            if ($out == true) {
                $RESPONSE['auth'] = false;
            }
        }
        if ($QUERY['action'] == 'add_task') { // Создание задачи
            $addt = TM::add_task($QUERY);
            $RESPONSE['add_task'] = $addt;
        }
        if ($QUERY['action'] == 'set_task') { // Изменение свойств задачи
            $setask = TM::set_task($QUERY);
            $RESPONSE['set_task'] = $setask;
        }
        if ($QUERY['action'] == 'del_notify') { // Изменение свойств задачи
            $del_notify = TM::del_notification($QUERY['type'],$QUERY['id']);
            $RESPONSE['del_notify'] = $del_notify;
        }
        if ($QUERY['action'] == 'del_task') { // Удаление задачи
            $RESPONSE['del_task'] = TM::del_task($QUERY['id']);
        }
        if ($QUERY['action'] == 'edit_task') { // Изменение задачи
            $RESPONSE['edit_task'] = TM::edit_task($QUERY);
        }
        if ($QUERY['action'] == 'del_project') { // Удаление проекта
            $RESPONSE['del_project'] = TM::del_project($QUERY['id']);
        }
        if ($QUERY['action'] == 'del_user') { // Удаление проекта
            $RESPONSE['del_user'] = TM::del_user($QUERY['id'],$QUERY['group']);
        }
        if ($QUERY['action'] == 'edit_project') { // Изменение проекта
            $RESPONSE['edit_project'] = TM::edit_project($QUERY);
        }
        if ($QUERY['action'] == 'get_comments') {
            $comm = TM::get_comments($QUERY['id'],$QUERY['type']);
            $RESPONSE['comments'] = $comm;
        }
        if ($QUERY['action'] == 'add_comment') {
            $RESPONSE['new_comment'] = TM::add_comment($QUERY['id'],$QUERY['type'],$QUERY['text']);
            //$RESPONSE['comments'] = TM::get_comm($QUERY);
        }
        if ($QUERY['action'] == 'get_users') {
            $RESPONSE['users'] = User::get_users($QUERY);
        }
        if ($QUERY['action'] == 'show_projects') {
            $RESPONSE['projects'] = TM::show_projects($QUERY);
        }
        if ($QUERY['action'] == 'add_project') {
            $RESPONSE['add_project'] = TM::add_project($QUERY);
        }
        if ($QUERY['action'] == 'add_group') {
            $RESPONSE['add_group'] = TM::add_group($QUERY['name']);
        }
        if ($QUERY['action'] == 'get_user') {
            $RESPONSE['get_user'] = User::get_user($QUERY);
        }
        if ($QUERY['action'] == 'gen_invite_group') {
            $RESPONSE['gen_invite_group'] = User::gen_invite_group($QUERY['id']);
        }
        if ($QUERY['action'] == 'parse_hash') {
            $RESPONSE['parse_hash'] = TM::parse_hash($QUERY['hash']);
        }
        if ($QUERY['action'] == 'pick_file') {
            $RESPONSE['pick_file'] = TM::pick_file($QUERY);
        }
        if ($QUERY['action'] == 'del_file') {
            $RESPONSE['del_file'] = FILE::del_file($QUERY['id']);
        }
        if ($QUERY['action'] == 'crop') {
            $RESPONSE['crop'] = FILE::crop($QUERY['crop']);
        }
        if ($QUERY['action'] == 'init') {
            $RESPONSE['ID'] = USER_ID;
            $RESPONSE['NAME'] = USER_NAME;
            $RESPONSE['PIC'] = USER_PIC;
            /*$DB=[];
            $DB['GROUP']=User::get_groups();
            $DB['PROJECT']=TM::get_projects($DB['GROUP']);
            $DB['TASK']=TM::get_tasks($DB['PROJECT']);
            $RESPONSE['DB']=$DB;*/
        }
    }
}else {
    $RESPONSE['auth'] = false;
    if (isset($QUERY['action'])) {
        if($QUERY['action']=='check'){
            $RESPONSE['check'] = false;
        }
        if ($QUERY['action'] == 'reg') { // Регистрация
            $reg = User::registration($QUERY);
            $RESPONSE['reg'] = $reg;
        }
        if ($QUERY['action'] == 'auth') { // Авторизация
            $auth = User::auth($QUERY['email'], $QUERY['pass']);
            $RESPONSE['auth'] = $auth;
        }
        if ($QUERY['action'] == 'get_user') {
            $RESPONSE['get_user'] = User::get_user($QUERY['email'], true);
        }
    }
}

echo(json_encode($RESPONSE)); // Ответ