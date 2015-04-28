<?php

define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/'); // Установка корневой директории
define('AJAX','CORE'); // Блокируем маршрутизацию

require_once ROOT.'system/core.php'; // Инициация запуска системы

if(!isset($_GET['id'])){
    exit;
}

$file=mysqli_fetch_assoc(DB::select('files',['*'],'idfile='.$_GET['id']));
$filepath=$file['path'];//ROOT.'users/'.$file['iduser'].'/files/'.$file['namefile'];

if (file_exists($filepath)) {
    // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
    // если этого не сделать файл будет читаться в память полностью!
    if (ob_get_level()) {
        ob_end_clean();
    }
    // заставляем браузер показать окно сохранения файла
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $file['namefile']).'"';
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));
    // читаем файл и отправляем его пользователю
    readfile($filepath);
    exit;
}