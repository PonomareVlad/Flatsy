<?php
require_once ROOT.'system/class/db.php';
class User extends DB
{
    public function init()
    {
        session_start();
        if (isset($_COOKIE['HASH'])) { // Если обнаружен авторизованный пользователь
            //$DB['USER_DATA'] = mysql_query('SELECT * FROM users WHERE id="' . $_SESSION['ID'] . '"', $DB['CONNECT']); // Запрашиваем данные пользователя
            //$res = DB::select('auth', array('iduser', 'hash'), 'hash=' . $_COOKIE['HASH']);
            $res=mysql_query('SELECT iduser FROM auth WHERE hash="'.$_COOKIE['HASH'].'"');
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

            //$query = $this->select('users', '*', 'mail="' . strtolower($login) . '"');

            $query = mysql_query('SELECT * FROM tm.users WHERE mail="' . strtolower($login) . '"');

            $user = mysql_fetch_array($query);

            if ($user['password'] == md5(strtolower($password))) {

                $hash = genHash();

                if (DB::insert('auth', array('iduser' => $user['id'], 'hash' => $hash))) {

                    //$_SESSION['HASH'] = $hash;

                    $d=setcookie('HASH',$hash,7000000000);

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

            $lastname = Checkdata($array['lastname']);
            $firstname = Checkdata($array['firstname']);
            $patronymic = Checkdata($array['patronymic']);
            $password = Checkdata($array['password']);
            $email = Checkdata($array['email']);

            if ($lastname == '' || $firstname == '' || $password == '' || $patronymic == '' || $email == '') {
                return 'Bad data';
            }

            $password = md5(strtolower($password));
            $reg_date = time();//date("y-m-d G:i:s");//getdate(time()+14400);
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
                return true;
            }else{
                return $query;
            }
        }
        return 'Empty data';

    }

}