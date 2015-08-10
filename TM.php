<?php
header("Access-Control-Allow-Origin: *");
define('CORE','VERSION');
require_once('system/core.php');
$ver=str_split(VERSION);
$version='Flatsy v.0';
for($i=0;$i<count($ver);$i++){
    $version=$version.'.'.$ver[$i];
}
echo $version;
if(isset($_GET['gen'])&&$_GET['gen']=='invite') {
    define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/');
    require_once('system/class/db.php');
    require_once('system/func.php');
    $hash = genHash();
    if (DB::insert('invite', ['creator' => 1, 'iduser' => 0, 'hash' => $hash, 'type' => 'reg', 'value' => 0, 'status' => 0, 'date' => date("y-m-d G:i:s")])) {
        $hash = mysqli_fetch_assoc(DB::select('invite', ['*'], 'hash="' . $hash . '"'));
        $hash = $hash['hash'];
        echo '<br>New Free invite for registrtion:<br>' . $hash;
        sendPush('[Flatsy] New Invite Generated','New invite code: '.$hash);
    }
}