<?php
// Обеспечение целостности зависимостей
require_once ROOT.'system/class/db.php';

class User extends DB{

    public static function init(){ // Функция проверки авторизации пользователя
        if (isset($_COOKIE['HASH'])||isset($_GET['hash'])||isset($_POST['hash'])) { // Если обнаружен ключ авторизации
            $hash=$_COOKIE['HASH']?$_COOKIE['HASH']:($_GET['hash']?$_GET['hash']:$_POST['hash']);
            $res = DB::select('auth', array('iduser'), 'hash="' . $hash . '"'); // Запрашиваем ID по найденнову HASH
            $USERA = mysqli_fetch_array($res); // Переводим ответ БД в массив
            if (!isset($USERA['iduser'])) { // Если ключ авторизации не найден
                setcookie('HASH', '', time() - 10000); // Удаляем недействительный ключ авторизации
                //header('location: /'); exit;
                // BUILD EXCEPTION
                //dbg('HASH not Found!');
                session_close($hash);
            }else {
                //dbg('Hash ' . $hash . ' aliased by ID: ' . $USERA['iduser']);
                $res = DB::select('users', array('*'), 'id=' . $USERA['iduser']); // Запрашиваем данные пользователя
                $USER = mysqli_fetch_assoc($res); // Переводим ответ БД в массив

                $USER['FULL_NAME'] = $USER['lastname'] . ' ' . $USER['firstname']; // Генерация полного имени для заголовка
                define('USER_NAME', $USER['FULL_NAME']);
                define('USER_ID', $USER['id']);
                define('USER_PIC', 'http://flatsy.ru' . ($USER['photo'] == '' ? '/templates/default/images/avatar.png' : $USER['photo']));
                //dbg('User loaded: ' . USER_ID . ' ' . USER_NAME);
                session_update($hash);

                // BUILD USER SETTINGS LOAD

            }
        }
    }

    public static function auth($login, $password)
    {

        if (!defined('USER_ID')) {
            global $MYSQL_CONNECTION;
            //$query = $this->select('users', array('*'), 'mail="' . strtolower($login) . '"');

            $query = DB::select('users',['*'],'mail="'.strtolower(Checkdata($login, true)).'"');

            $user = mysqli_fetch_assoc($query);

            if ($user['password'] == md5(strtolower(Checkdata($password, true)))) {

                $hash = md5(genHash());

                //dbg('Insert new HASH from auth()');

                if (DB::insert('auth', array('iduser' => $user['id'], 'hash' => $hash))) {

                    //$_SESSION['HASH'] = $hash;

                    define('USER_ID', $user['id']);

                    session_init($hash);

                    setcookie('HASH', $hash, 7000000000);

                    $user['FULL_NAME'] = $user['lastname'] . ' ' . $user['firstname'];

                    $user['PHOTO']='http://flatsy.ru'.($user['photo'] == '' ? '/templates/default/images/avatar.png' : $user['photo']);

                    $return=['id'=>$user['id'],'full_name'=>$user['FULL_NAME'],'photo'=>$user['PHOTO'],'hash'=>$hash];

                    return $return;

                }

            }

        }

        return false;

    }

    public static function logout()
    {

        if (defined('USER_ID')) {

            if (DB::delete('auth', 'hash="' . $_COOKIE['HASH'] . '"')) {

                setcookie('HASH', '', time() - 10000);

                return true;

            }

        }

        return false;

    }

    public static function registration($array)
    {
        if (isset($array['lastname']) && isset($array['firstname']) && isset($array['patronymic']) && isset($array['password']) && isset($array['email']) && isset($array['invite'])) {

            $lastname = Checkdata($array['lastname'], true);
            $firstname = Checkdata($array['firstname'], true);
            $patronymic = Checkdata($array['patronymic'], true);
            $password = Checkdata($array['password'], true);
            $email = Checkdata($array['email'], true);
            $invite = Checkdata($array['invite'], true);

            if ($lastname == '' || $firstname == '' || $password == '' || $patronymic == '' || $email == '' || $invite == '') {
                return 'Bad data';
            }
            $inv = mysqli_fetch_assoc(DB::select('invite', ['*'], 'hash="' . $invite . '"'));
            if (is_array($inv)) {
                if ($inv['type'] == 'reg' && $inv['status'] == 0) {
                    // OK
                } else {
                    return 'Bad key';
                }
            } else {
                return 'Bad key';
            }
            $testlogin = DB::select('users', ['id'], 'mail="' . $email . '"'); // Запрос на поиск указанного логина(почты) среди зарегестрированных пользователей
            $testlogin = mysqli_fetch_assoc($testlogin);
            if (isset($testlogin['id'])) {
                return 'Login exists';
            }

            $password = md5(strtolower($password));
            $reg_date = date("y-m-d G:i:s");//date("y-m-d G:i:s");//getdate("y-m-d G:i:s"); date("y-m-d G:i:s");
            $last_act = $reg_date;

            $query = DB::inserti('users', '(mail,password,firstname,lastname,patronymic,last_act,reg_date,photo) VALUES("' . $email .
                '","' . $password .
                '","' . $firstname .
                '","' . $lastname .
                '","' . $patronymic .
                '","' . $last_act .
                '","' . $reg_date .
                '","")');

            if ($query) {
                $USERN = mysqli_fetch_assoc(DB::select('users',['*'],'mail="'.$email.'"'));
                mkdir(ROOT.'users/'.$USERN['id'].'/files',0777,true);
                /*$CFG_INIT = mysqli_query($MYSQL_CONNECTION,"CREATE TABLE IF NOT EXISTS users.id" . $USERN['id'] . "_config (
  key varchar(50) NOT NULL,
  value varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Пользовательские настройки';");
                if ($CFG_INIT == 1) {*/
                DB::update('invite',['status'=>1,'iduser'=>$USERN['id'],'date'=>$reg_date],'hash="'.$invite.'"');
                // BUILD NOTIFICATION TO CREATOR
                return true;
                /*} else {
                    return $CFG_INIT;
                }*/
            } else {
                return $query;
            }
        }
        return 'Empty data';

    }

    public static function get_users($query)
    {
        global $MYSQL_CONNECTION;
        $users = [];
        $array = DB::select('users',['*'],'lastname LIKE "%' . mysqli_real_escape_string($MYSQL_CONNECTION,$query['query']) . '%" OR firstname LIKE "%' . mysqli_real_escape_string($MYSQL_CONNECTION,$query['query']) . '%" OR patronymic LIKE "%' . mysqli_real_escape_string($MYSQL_CONNECTION,$query['query']) . '%" LIMIT 0, 10');
        while ($user = mysqli_fetch_assoc($array)) {
            $users[] = ["id" => $user['id'], "name" => $user['lastname'].' '.$user['firstname']];
        }
        return $users;
    }

    public static function get_user($id, $private = false)
    {
        if ($private == true && !is_numeric($id)) {
            $mail = strtolower(Checkdata($id));
            $user = DB::select('users', ['id','photo'], 'mail="' . $mail . '"');
        } else {
            $user = DB::select('users', ['id', 'lastname', 'firstname', 'patronymic', 'last_act', 'photo'], 'id=' . $id);
        }
        $user = mysqli_fetch_assoc($user);
        if (isset($user['photo'])) {
            $user['photo'] = 'http://flatsy.ru'.($user['photo'] == '' ? '/templates/default/images/avatar.png' : $user['photo']);
            //if ($private == true) {
            //    $return = ['photo' => $user['photo']];
            //}else{
            //    $return = $user;
            //}
            return $user;
        } else {
            return false;
        }
    }

    public static function get_groups(){
        $groups = [];
        $arr = DB::select('useringroup', ['*'], 'iduser=' . USER_ID);
        while ($link = mysqli_fetch_assoc($arr)) {
            if ($link['statususer'] > 2) {
                $group = User::get_group($link['idgroup'],true);
                $group['lvl'] = $link['userlvl'];
                $groups[] = $group;
            }
        }
        return $groups;
    }

    public static function get_group($idg,$firstwave=false){
        $group = DB::select('groups', ['*'], 'idgroup=' . $idg);
        $group = mysqli_fetch_assoc($group);
        $group['users'] = [];
        $users = DB::select('useringroup', ['*'], 'idgroup=' . $group['idgroup']);
        $group['count_users']=0;
        while ($user = mysqli_fetch_assoc($users)) {
            if ($user['statususer'] > 2) {
                if ($user['iduser'] == USER_ID) {
                    if ($firstwave == true) {
                        $group['lvl'] = $user['userlvl'];
                    } else {
                        return false;
                    }
                }
                $us = User::get_user($user['iduser']);
                if(!isset($us['firstname'])){
                    continue;
                }
                $us['lvl'] = $user['userlvl'];
                $group['count_users'] += 1;
                $group['users'][] = $us;
            }
        }
        $group['subgroup'] = [];
        $subgr = DB::select('visgroups', ['*'], 'id=' . $group['idgroup']);
        while ($subgrouplink = mysqli_fetch_assoc($subgr)) {
            $subgroup = User::get_group($subgrouplink['visidgroup']);
            if ($subgroup != false) {
                $group['subgroup'][] = $subgroup;
            }
        }
        return $group;
    }

    public static function gen_invite_group($idg)
    {
        $hash = genHash();
        $date = date("y-m-d G:i:s");
        if (DB::insert('invite', ["creator" => USER_ID, "iduser" => 0, "hash" => $hash, "type" => "group", "value" => $idg, "status" => 0, "date" => $date])) {
            return $hash;
        } else {
            $hash = genHash();
            $date = date("y-m-d G:i:s");
            if (DB::insert('invite', ["creator" => USER_ID, "iduser" => 0, "hash" => $hash, "type" => "group", "value" => $idg, "status" => 0, "date" => $date])) {
                return $hash;
            } else {
                return false;
            }
        }
    }

    public static function reset_pass($email){
        $user = User::get_user($email,true);
        if($user) {
            $hash = genHash(true);
            if (DB::insert('notifications', ["iduser" => $user['id'], "type" => "reset_pass", "value" => $hash])) {
                sendNotify(strtolower(Checkdata($email)), '[Flatsy] Password change request', 'You have change password by this link <a href="http://flatsy.ru/?reset_pass=' . $hash . '">http://flatsy.ru/?reset_pass=' . $hash . '</a>');
                return true;
            }
        }
        return false;
    }

    public static function change_pass($hash,$pass){
        $get = DB::select('notifications', ['*'], 'type="reset_pass" AND value="' . $hash.'"');
        if ($get) {
            $notify = mysqli_fetch_assoc($get);
            $password = Checkdata($pass, true);
            $password = md5(strtolower($password));
            if(DB::update('users',['password'=>$password],'id='.$notify['iduser'])){
                DB::delete('notifications','type="reset_pass" AND value="' . $hash.'"');
                return true;
            }
        }
        return false;
    }

}