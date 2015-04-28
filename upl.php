<?php

define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/'); // Установка корневой директории
define('AJAX','CORE'); // Блокируем маршрутизацию

require_once ROOT.'system/core.php'; // Инициация запуска системы

if(!isset($_FILES['f'])){
    exit('Empty field');
}

$uploaddir = ROOT.'users/'.USER_ID.'/files/';
$uploadfile = $uploaddir . genHash();

if($_POST['id']=='new'){

}else {
    if ($_POST['type'] == 'task') {

    }else if($_POST['type']=='project'){

    }else if($_POST['type']=='comment'){

    }else{
        exit('Bad META');
    }
}

if (move_uploaded_file($_FILES['f']['tmp_name'], $uploadfile)) {
    $date = date("y-m-d G:i:s");
    $object=$_POST['id']=='new'?0:$_POST['id'];
    DB::insert('files',['iduser'=>USER_ID,'namefile'=>Checkdata($_FILES['f']['name']),'path'=>$uploadfile,'timeload'=>$date,'type'=>$_POST['type'],'object'=>$object]);
    $file=mysqli_fetch_assoc(DB::select('files',['*'],'namefile="'.$_FILES['f']['name'].'" AND timeload="'.$date.'"'));
    echo('<html><head><title>Файл загружен</title><script>
function save(){window.opener.pick_file('.$file['idfile'].',"'.$file['namefile'].'");window.close();}
</script></head><body onload="save();">Файл '.$file['namefile'].' загружен, для продолжения нажмите кнопку:<br/><button onclick="save();" value="Прикрепить">Прикрепить</button></body></html>');
} else {
    echo "Ошибка загрузки";
}