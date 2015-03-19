<?php
require_once ROOT.'system/class/db.php';
class User extends DB
{
    public function init()
    {
        session_start();
        if (isset($_COOKIE['HASH'])) { // Если обнаружен авторизованный пользователь
            //$DB['USER_DATA'] = mysql_query('SELECT * FROM users WHERE id="' . $_SESSION['ID'] . '"', $DB['CONNECT']); // Запрашиваем данные пользователя
            $res = DB::select('auth', array('iduser', 'hash'), 'hash="' . $_COOKIE['HASH'].'"'); // Ну ты молодец, where без кавычек отправляешь
            //$res=mysql_query('SELECT iduser FROM auth WHERE hash="'.$_COOKIE['HASH'].'"');
            $USERA = mysql_fetch_array($res); // Переводим ответ БД в массив
            if(!isset($USERA['iduser'])){
                session_destroy();
                setcookie('HASH','',time()-10000);
                header('location: /');
                exit;
            }
            /*if ($_COOKIE['HASH'] !== $USERA['hash']) { // Если полученный из БД ID не совпадает с хранимым в сессии (такое происходит если в одном браузере в разных вкладках авторизованы разные пользователи)

                session_destroy(); // Удаление всех данных сессии

                //header('location: /'); // Перенаправление на главную страницу

                exit;

            }*/

            $res = DB::select('users', array('*'), 'id="' . $USERA['iduser'].'"');

            $USER = mysql_fetch_array($res); // Переводим ответ БД в массив

            $USER['FULL_NAME'] = $USER['lastname'] . ' ' . $USER['firstname']; // Генерация полного имени для заголовка

            define('USER_NAME', $USER['FULL_NAME']);

            define('USER_ID', $USER['id']);

            //$DB['USER_CONF'] = mysql_query('SELECT * FROM `users`.`config_id'.$_SESSION['ID'].'`', $DB['CONNECT']); // Запрос таблицы настроек пользователя

            //$res  = $this->select('users.config_id'.$USER['id'],'*');

            //$USER['CONF'] = mysql_fetch_array($res); // Запись всех настроек как массив

        }

    }

    public function auth($login, $password)
    {

        if (!defined('USER_ID')) {
            //$query = $this->select('users', array('*'), 'mail="' . strtolower($login) . '"');

            $query = mysql_query('SELECT * FROM tm.users WHERE mail="' . strtolower(Checkdata($login,true)) . '"'); //СМОТРИ

            $user = mysql_fetch_array($query);

            if ($user['password'] == md5(strtolower(Checkdata($password,true)))) {

                $hash = md5(genHash());

                if (DB::insert('auth', array('iduser' => $user['id'], 'hash' => $hash))) {

                    //$_SESSION['HASH'] = $hash;

                    setcookie('HASH',$hash,7000000000);

                    return $user['id'];

                }

            }

        }

        return false;

    }

    public function logout(){

        if (defined('USER_ID')) {

            if(DB::delete('auth', 'hash="' . $_COOKIE['HASH'].'"')){

                setcookie('HASH','',time()-10000);

                return true;

            }

        }

        return false;

    }

    public function registration($array){

        if (isset($array['lastname']) && isset($array['firstname']) && isset($array['patronymic']) && isset($array['password']) && isset($array['email'])) {

            $lastname = Checkdata($array['lastname'],true);
            $firstname = Checkdata($array['firstname'],true);
            $patronymic = Checkdata($array['patronymic'],true);
            $password = Checkdata($array['password'],true);
            $email = Checkdata($array['email'],true);

            if ($lastname == '' || $firstname == '' || $password == '' || $patronymic == '' || $email == '') {
                return 'Bad data';
            }
            $testlogin=mysql_query('SELECT id FROM users WHERE mail="'.$email.'"'); // Запрос на поиск указанного логина(почты) среди зарегестрированных пользователей
            $testlogin=mysql_fetch_array($testlogin);
            if(isset($testlogin['id'])){
                return 'Login exists';
            }

            $password = md5(strtolower($password));
            $reg_date = date("y-m-d G:i:s");//date("y-m-d G:i:s");//getdate("y-m-d G:i:s"); date("y-m-d G:i:s");
            $last_act = $reg_date;

            $query = mysql_query('INSERT INTO tm.users (mail,password,firstname,lastname,patronymic,last_act,reg_date,photo) VALUES("'.$email.
                '","'.$password.
                '","'.$firstname.
                '","'.$lastname.
                '","'.$patronymic.
                '","'.$last_act.
                '","'.$reg_date.
                '","")');

            if ($query == 1) {
                $USERN=mysql_fetch_array(mysql_query('SELECT * FROM users WHERE mail="'.$email.'"'));
                $CFG_INIT=mysql_query("CREATE TABLE IF NOT EXISTS `users`.`id".$USERN['id']."_config` (
  `key` varchar(50) NOT NULL,
  `value` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Пользовательские настройки';");
                if($CFG_INIT==1) {
                    return true;
                }else{
                    return $CFG_INIT;
                }
            }else{
                return $query;
            }
        }
        return 'Empty data';

    }

    public function get_users($query){
        $users=[];
        $array=mysql_query("SELECT * FROM users WHERE lastname LIKE '%".mysql_real_escape_string($query['query'])."%' OR firstname LIKE '%".mysql_real_escape_string($query['query'])."%' OR patronymic LIKE '%".mysql_real_escape_string($query['query'])."%' LIMIT 0, 10");
        while($user=mysql_fetch_assoc($array)){
            $users[]=["id"=>$user['id'],"lastname"=>$user['lastname'],"firstname"=>$user['firstname'],"patronymic"=>$user['patronymic']];
        }
        return $users;
    }

}