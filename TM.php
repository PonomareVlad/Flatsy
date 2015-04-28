<?php
header("Access-Control-Allow-Origin: *");
echo 'Flatsy v.0.5.3';
if($_GET['gen']=='invite') {
    define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/');
    require_once('system/class/db.php');
    require_once('system/func.php');
    $hash = genHash();
    if (DB::insert('invite', ['creator' => 1, 'iduser' => 0, 'hash' => $hash, 'type' => 'reg', 'value' => 0, 'status' => 0, 'date' => date("y-m-d G:i:s")])) {
        $hash = mysqli_fetch_assoc(DB::select('invite', ['*'], 'hash="' . $hash . '"'));
        $hash = $hash['hash'];
        echo '<br>New Free invite for registrtion:<br>' . $hash;
    }
}