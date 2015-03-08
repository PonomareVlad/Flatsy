<?php

class User extends DB
{
    public function init()
    {
        session_start();
        if (isset($_COOKIE['HASH'])) { // Если обнаружен авторизованный пользователь
            //$DB['USER_DATA'] = mysql_query('SELECT * FROM users WHERE id="' . $_SESSION['ID'] . '"', $DB['CONNECT']); // Запрашиваем данные пользователя
            $res = $this->select('auth', array('iduser', 'hash'), 'hash=' . $_COOKIE['HASH']);
            echo $res;
            $USERA = mysql_fetch_array($res); // Переводим ответ БД в массив

            if ($_COOKIE['HASH'] !== $USERA['hash']) { // Если полученный из БД ID не совпадает с хранимым в сессии (такое происходит если в одном браузере в разных вкладках авторизованы разные пользователи)

                session_destroy(); // Удаление всех данных сессии

                header('location: /'); // Перенаправление на главную страницу

                exit;

            }

            $res = $this->select('users', '*', 'id=' . $USERA['iduser']);

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

            $res = $this->select('users', '*', 'mail="' . strtolower($login) . '"');

            $user = mysql_fetch_array($res);

            if ($user['password'] == md5(strtolower($password))) {

                $hash = genHash();

                if ($this->insert('auth', array('iduser' => $user['id'], 'hash' => $hash))) {

                    //$_SESSION['HASH'] = $hash;

                    setcookie('HASH',$hash,time()+2592000);

                    return $user['id'];

                }

            }

        }

        return false;

    }

    public function logout(){

        if (defined('USER_ID')) {

            if($this->delete('auth', 'iduser=' . USER_ID)){

                setcookie('HASH','',time()-10000);

                return true;

            }

        }

        return false;

    }

}