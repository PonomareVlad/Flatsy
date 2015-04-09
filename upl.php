<?php

define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/'); // Установка корневой директории
define('AJAX','CORE'); // Блокируем маршрутизацию

require_once ROOT.'system/core.php'; // Инициация запуска системы

if(!isset($_FILES['f'])){
    exit('Empty field');
}

$uploaddir = ROOT.'users/'.USER_ID.'/files/';
$uploadfile = $uploaddir . basename($_FILES['f']['name']);

if($_POST['id']=='new'){

}else {
    if ($_POST['type'] == 'task') {

    }else if($_POST['type']=='project'){

    }else{
        exit('Bad META');
    }
}

if (move_uploaded_file($_FILES['f']['tmp_name'], $uploadfile)) {
    $date = date("y-m-d G:i:s");
    $object=$_POST['id']=='new'?0:$_POST['id'];
    DB::insert('files',['iduser'=>USER_ID,'namefile'=>basename($_FILES['f']['name']),'timeload'=>$date,'type'=>$_POST['type'],'object'=>$object]);
    $file=mysqli_fetch_assoc(DB::select('files',['*'],'namefile="'.basename($_FILES['f']['name']).'" AND timeload="'.$date.'"'));
    echo('<html><head><title>Файл загружен</title><script>
function save(){window.opener.pick_file('.$file['idfile'].',"'.$file['namefile'].'");}
</script></head><body>Файл '.$file['namefile'].' загружен, для продолжения нажмите кнопку:<br/><button onclick="save();" value="Прикрепить">Прикрепить</button></body></html>');
} else {
    echo "Ошибка загрузки";
}