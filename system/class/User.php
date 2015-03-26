<?php
// Обеспечение целостности зависимостей
require_once ROOT.'system/class/db.php';

class User extends DB{

    public static function init(){ // Функция проверки авторизации пользователя
        if (isset($_COOKIE['HASH'])) { // Если обнаружен ключ авторизации
            $res = DB::select('auth', array('iduser'), 'hash="' . $_COOKIE['HASH'] . '"'); // Запрашиваем ID по найденнову HASH
            $USERA = mysqli_fetch_array($res); // Переводим ответ БД в массив
            if (!isset($USERA['iduser'])) { // Если ключ авторизации не найден
                setcookie('HASH', '', time() - 10000); // Удаляем недействительный ключ авторизации
                //header('location: /'); exit;
                // BUILD EXCEPTION
            }

            $res = DB::select('users', array('*'), 'id='.$USERA['iduser']); // Запрашиваем данные пользователя
            $USER = mysqli_fetch_assoc($res); // Переводим ответ БД в массив

            $USER['FULL_NAME'] = $USER['lastname'] . ' ' . $USER['firstname']; // Генерация полного имени для заголовка
            define('USER_NAME', $USER['FULL_NAME']);
            define('USER_ID', $USER['id']);
            define('USER_PIC', $USER['photo'] == '' ? '/templates/default/images/avatar.png' : $USER['photo']);

            // BUILD USER SETTINGS LOAD

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

                if (DB::insert('auth', array('iduser' => $user['id'], 'hash' => $hash))) {

                    //$_SESSION['HASH'] = $hash;

                    setcookie('HASH', $hash, 7000000000);

                    $user['FULL_NAME'] = $user['lastname'] . ' ' . $user['firstname'];

                    $user['PHOTO']=$user['photo'] == '' ? '/templates/default/images/avatar.png' : $user['photo'];

                    $return=['id'=>$user['id'],'full_name'=>$user['FULL_NAME'],'photo'=>$user['PHOTO']];

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
        if (isset($array['lastname']) && isset($array['firstname']) && isset($array['patronymic']) && isset($array['password']) && isset($array['email'])) {

            $lastname = Checkdata($array['lastname'], true);
            $firstname = Checkdata($array['firstname'], true);
            $patronymic = Checkdata($array['patronymic'], true);
            $password = Checkdata($array['password'], true);
            $email = Checkdata($array['email'], true);

            if ($lastname == '' || $firstname == '' || $password == '' || $patronymic == '' || $email == '') {
                return 'Bad data';
            }
            $testlogin = DB::select('users',['id'],'mail="'.$email.'"'); // Запрос на поиск указанного логина(почты) среди зарегестрированных пользователей
            $testlogin = mysqli_fetch_assoc($testlogin);
            if (isset($testlogin['id'])) {
                return 'Login exists';
            }

            $password = md5(strtolower($password));
            $reg_date = date("y-m-d G:i:s");//date("y-m-d G:i:s");//getdate("y-m-d G:i:s"); date("y-m-d G:i:s");
            $last_act = $reg_date;

            $query = DB::inserti('users','(mail,password,firstname,lastname,patronymic,last_act,reg_date,photo) VALUES("' . $email .
                '","' . $password .
                '","' . $firstname .
                '","' . $lastname .
                '","' . $patronymic .
                '","' . $last_act .
                '","' . $reg_date .
                '","")');

            if ($query) {
                /*$USERN = mysqli_fetch_array(mysqli_query($MYSQL_CONNECTION,'SELECT * FROM users WHERE mail="' . $email . '"'));
                $CFG_INIT = mysqli_query($MYSQL_CONNECTION,"CREATE TABLE IF NOT EXISTS users.id" . $USERN['id'] . "_config (
  key varchar(50) NOT NULL,
  value varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Пользовательские настройки';");
                if ($CFG_INIT == 1) {*/
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
        $array = mysqli_query($MYSQL_CONNECTION,"SELECT * FROM users WHERE lastname LIKE '%" . mysqli_real_escape_string($MYSQL_CONNECTION,$query['query']) . "%' OR firstname LIKE '%" . mysqli_real_escape_string($MYSQL_CONNECTION,$query['query']) . "%' OR patronymic LIKE '%" . mysqli_real_escape_string($MYSQL_CONNECTION,$query['query']) . "%' LIMIT 0, 10");
        while ($user = mysqli_fetch_assoc($array)) {
            $users[] = ["id" => $user['id'], "lastname" => $user['lastname'], "firstname" => $user['firstname'], "patronymic" => $user['patronymic']];
        }
        return $users;
    }

    public static function get_user($id, $private = false)
    {
        if ($private == true && !is_numeric($id)) {
            $mail = strtolower(Checkdata($id));
            $user = DB::select('users', ['photo'], 'mail="' . $mail . '"');
        } else {
            $user = DB::select('users', ['id', 'lastname', 'firstname', 'patronymic', 'last_act', 'photo'], 'id=' . $id);
        }
        $user = mysqli_fetch_assoc($user);
        if (isset($user['photo'])) {
            $user['photo'] = $user['photo'] == '' ? '/templates/default/images/avatar.png' : $user['photo'];
            if ($private == true) {
                $return = ['photo' => $user['photo']];
            }else{
                $return = $user;
            }
            return $return;
        } else {
            return false;
        }
    }

}