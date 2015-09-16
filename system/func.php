<?php

function head(){ // Фунция генерации основных элементов тега HEAD
    $title='Flatsy';
    $scripts='';

    // Подключение скриптов
    $js_dir = scandir(ROOT.'/js');
    for ($i = 2; $i < count($js_dir); $i++){
        $tjs=explode('.', $js_dir[$i]);
        if(isset($tjs[count($js_dir[$i])])) {
            if ($tjs[count($js_dir[$i])] == 'js') {
                $scripts .= '<script src="/js/' . $js_dir[$i] . '"></script>';
            }
        }
    }
    return '<title>' . $title . '</title><meta charset="utf-8" />'.$scripts;
}

function Checkdata($string,$cls_probels=false){ // Функция обработки принятых данных
    // $cls_probels устанавливает разрешение на очистку строки от пробелов
    $string=stripslashes($string);
    $string=htmlspecialchars($string);
    $string=$cls_probels==true?trim($string):$string;
    return $string;
}

function genHash($isNum=false){ // Фунуция генерации случайного хэша
    $chars = $isNum?'1234567890':'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
    $numChars = strlen($chars);
    $string = '';
    $length=$isNum?8:rand(8,16);
    for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }
    return $isNum?$string:md5($string);
}

function objectToArray($d){
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}

function dbg($text){
    //if (!defined('USER_ID')||USER_ID == 1) {
        $text = $text . '
';
        $old = file(ROOT . 'errlog.txt');
        if ($old && $old[count($old) - 1] == $text) {
            return true;
        }
        $file = fopen(ROOT . 'errlog.txt', 'a');
        fwrite($file, $text);
        fclose($file);
    //}
}

function sendNotify($to,$title,$body){
    require_once(ROOT . '/system/class/PHPMailer.php');
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.flatsy.ru';
    $mail->SMTPAuth = true;
    $mail->Username = 'info@flatsy.ru';
    $mail->Password = 'j9br7tYwNMHU';
    $mail->Port = '25';
    $mail->CharSet = 'UTF-8';
    $mail->From = 'info@flatsy.ru';
    $mail->FromName = 'Уведомления Flatsy';
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->Body = $body;
    $mail->send() or dbg($mail->ErrorInfo);
}
function sendPush($title,$body){
    require_once(ROOT.'/system/class/Pushalot.php');
    $pushalot = new Pushalot('3709b5318a1144018d40674ad5e2bb52');
    $success = $pushalot->sendMessage(array(
        'Title'=>$title,
        'Body'=>$body,
        'LinkTitle'=>'Flatsy.ru',
        'Link'=>'http://flatsy.ru',
        'IsImportant'=>true,
        'IsSilent'=>false,
        'Image'=>'http://flatsy.ru/templates/default/images/icon.png',
        'Source'=>'sendPush()'
    ));
    return $success?'The message was submitted.':$pushalot->getError();
}

$MONTHS=["января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"];

function getIspByIp($ip){
    $json = file_get_contents("http://ipinfo.io/{$ip}/json");
    $details = objectToArray(json_decode($json));
    //$isp=explode(' ',$details['org']);
    //return $isp[count($isp)-1];
    return $details['org'];
}

function session_init($hash){
    if(isset($hash)){
        $session=mysqli_fetch_assoc(DB::select('sessions',['*'],'hash="'.$hash.'"'));
        if(isset($session['closed'])){
            session_close($hash);
            $hash = md5(genHash());
            if (DB::insert('auth', array('iduser' => USER_ID, 'hash' => $hash))) {
                //session_init($hash);
                setcookie('HASH', $hash, 7000000000);
            }
        }
        DB::insert('sessions',['hash'=>$hash,'iduser'=>USER_ID,'date'=>date('Y-m-d H:i:s'),'last_act'=>date('Y-m-d H:i:s'),'ip'=>$_SERVER['REMOTE_ADDR'],'provider'=>getIspByIp($_SERVER['REMOTE_ADDR']),'closed'=>0]);
    }
}

function session_update($hash){
    if (isset($hash)) {
        $session = mysqli_fetch_assoc(DB::select('sessions', ['*'], 'hash="'.$hash.'"'));
        if (isset($session['last_act'])) {
            if ($session['closed']==1||$session['ip']!==$_SERVER['REMOTE_ADDR']){//||date('U',$session['last_act']) < time() - 1000 * 60 * 15) {
                session_close($hash);
                $hash = md5(genHash());
                if (DB::insert('auth', array('iduser' => USER_ID, 'hash' => $hash))) {
                    session_init($hash);
                    setcookie('HASH', $hash, 7000000000);
                }
            }
            DB::update('sessions',['last_act'=>date('Y-m-d H:i:s')],'hash="'.$hash.'"');
        }else{
            session_close($hash);
            $hash = md5(genHash());
            if (DB::insert('auth', array('iduser' => USER_ID, 'hash' => $hash))) {
                session_init($hash);
                setcookie('HASH', $hash, 7000000000);
            }
        }
    }
}

function session_close($hash){
    if(isset($hash)){
        $session=mysqli_fetch_assoc(DB::select('sessions',['*'],'hash="'.$hash.'"'));
        if(isset($session['closed'])&&$session['closed']==0){
            DB::update('sessions',['closed'=>1],'hash="'.$hash.'"');
        }
        return User::logout();
    }
    return false;
}
