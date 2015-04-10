<?php
require_once ROOT.'system/class/db.php';
class FILE extends DB
{
    public static function del_file($id)
    {
        if (DB::delete('files', 'idfile=' . $id)) {
            return true;
        }else{
            return false;
        }
    }

    public static function upload($filename){
        if(is_uploaded_file($_FILES[$filename][$filename]))
        {
            move_uploaded_file($_FILES[$filename][$filename], ROOT."users/".$_FILES[$filename][$filename]);
        } else {
            return("Ошибка загрузки файла");
        }
    }
}