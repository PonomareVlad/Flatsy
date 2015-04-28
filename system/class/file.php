<?php
require_once ROOT.'system/class/db.php';
class FILE extends DB
{
    public static function del_file($id)
    {
        $file=mysqli_fetch_assoc(DB::select('files',['*'],'idfile='.$id));
        $file=ROOT.'users/'.$file['iduser'].'/files/'.$file['namefile'];
        if (DB::delete('files', 'idfile=' . $id)&&@unlink($file)) {
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