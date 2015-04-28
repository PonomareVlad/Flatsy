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
}