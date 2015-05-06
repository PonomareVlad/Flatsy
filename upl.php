<?php

define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/'); // Установка корневой директории
define('AJAX','CORE'); // Блокируем маршрутизацию

require_once ROOT.'system/core.php'; // Инициация запуска системы

if(!isset($_FILES['f'])){
    exit('Empty field');
}
$tmp_path='';
// Функция изменения размера
// Изменяет размер изображения в зависимости от type:
//	type = 1 - эскиз
// 	type = 2 - большое изображение
//	rotate - поворот на количество градусов (желательно использовать значение 90, 180, 270)
//	quality - качество изображения (по умолчанию 75%)
function resize($file,$file_type= 'image/jpeg', $type = 2, $rotate = null, $quality = null)
{
    global $tmp_path;

    // Ограничение по ширине в пикселях
    $max_thumb_size = 200;
    $max_size = 500;

    // Качество изображения по умолчанию
    if ($quality == null)
        $quality = 100;

    // Cоздаём исходное изображение на основе исходного файла
    if ($file_type == 'image/jpeg')
        $source = imagecreatefromjpeg($file);
    elseif ($file_type == 'image/png')
        $source = imagecreatefrompng($file);
    elseif ($file_type == 'image/gif')
        $source = imagecreatefromgif($file);
    else
        return false;

    // Поворачиваем изображение
    if ($rotate != null)
        $src = imagerotate($source, $rotate, 0);
    else
        $src = $source;

    // Определяем ширину и высоту изображения
    $w_src = imagesx($src);
    $h_src = imagesy($src);

    // В зависимости от типа (эскиз или большое изображение) устанавливаем ограничение по ширине.
    if ($type == 1)
        $h = $max_thumb_size;
    elseif ($type == 2)
        $h = $max_size;

    // Если ширина больше заданной
    if ($h_src > $h)
    {
        // Вычисление пропорций
        $ratio = $h_src/$h;
        $w_dest = round($w_src/$ratio);
        $h_dest = round($h_src/$ratio);

        // Создаём пустую картинку
        $dest = imagecreatetruecolor($w_dest, $h_dest);

        // Копируем старое изображение в новое с изменением параметров
        imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

        // Вывод картинки и очистка памяти
        imagejpeg($dest, $tmp_path . $file, $quality);
        imagedestroy($dest);
        imagedestroy($src);

        return $file;
    }
    else
    {
        // Вывод картинки и очистка памяти
        imagejpeg($src, $tmp_path . $file, $quality);
        imagedestroy($src);

        return $file;
    }
}

$uploaddir = ROOT.'users/'.USER_ID.'/files/';
$uplname = genHash();
$uploadfile = $uploaddir . $uplname;

if(!is_dir($uploaddir)){
    mkdir($uploaddir,0777,true);
}

if(isset($_POST['id'])&&$_POST['id']=='new'){

}else {
    if ($_POST['type'] == 'task') {

    }else if($_POST['type']=='project'){

    }else if($_POST['type']=='comment'){

    }else if($_POST['type']=='userpic'){
        if (move_uploaded_file($_FILES['f']['tmp_name'], $uploadfile)) {
            $name = resize($uploadfile,$_FILES['f']['type']);
            echo('<html><head><title>Файл загружен</title><script>
function save(){window.opener.crop("/users/'.USER_ID.'/files/'.$uplname.'");window.close();}
</script></head><body onload="save();">Файл загружен, для продолжения нажмите кнопку:<br/><button onclick="save();" value="Прикрепить">Обрезать</button></body></html>');
        } else {
            echo "Ошибка загрузки";
        }
        exit;
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