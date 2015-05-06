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

    public static function crop($query){
        $filename = $query['filename'];
        $filename=explode('/',$filename);
        $filename=ROOT.'users/'.USER_ID.'/files/'.$filename[count($filename)-1];
        $new_name = uniqid();//.'.jpg';
        $new_filename = ROOT.'users/avatars/'.$new_name;
        list($current_width, $current_height) = getimagesize($filename);
        $x1    = $query['x1'];
        $y1    = $query['y1'];
        $x2    = $query['x2'];
        $y2    = $query['y2'];
        $w    = $query['w'];
        $h    = $query['h'];
        $crop_width = 37;
        $crop_height = 37;
        $new = imagecreatetruecolor($crop_width, $crop_height);
        $current_image = imagecreatefromjpeg($filename);
        imagecopyresampled($new, $current_image, 0, 0, $x1, $y1, $crop_width, $crop_height, $w, $h);
        imagejpeg($new, $new_filename, 95);
        DB::update('users',['photo'=>'/users/avatars/'.$new_name],'id='.USER_ID);
        return '/users/avatars/'.$new_name;
    }
}