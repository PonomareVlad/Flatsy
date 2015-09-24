<?php
header('Content-Type: text/html; charset=utf-8', true);
define('AJAX','CORE');
define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/');
require_once('system/core.php');

$provider='AS5468 Yeltsin UrFU, Ural Federal University';

$from =date('Y-m-d H:i:s',mktime(0, 0, 0, date("m")  , date("d")-14, date("Y")));
echo 'From date: '.$from;
echo '<br/>Current date: '.date('Y-m-d H:i:s');
echo('<br>');
$sessions=DB::select('sessions',['*'],'last_act>"'.$from.'"');
$byDay=array();
while($session=mysqli_fetch_assoc(($sessions))) {
    if ($session['provider'] == $provider) {
        $date = date('Y-m-d', strtotime($session['date']));
        if (!isset($byDay[$date])) {
            $byDay[$date] = array();
        }
        $byDay[$date][] = $session;
    }
}
ksort($byDay);
$days=array_keys($byDay);
for($i=0;$i<count($byDay);$i++){
    $day=$days[$i];
    $users=array();
    for($j=0;$j<count($byDay[$day]);$j++){
        $item=$byDay[$day][$j];
        if(!isset($users['id'.$item['iduser']])||mktime($item['date'])<mktime($users['id'.$item['iduser']]['date'])){
            $users['id'.$item['iduser']]=$item;
        }
    }
    $byDay[$day]=$users;
}
for($i=0;$i<count($byDay);$i++){
    $day=$days[$i];
    ksort($byDay[$day]);
    $id=array_keys($byDay[$day]);
    echo('<br/>['.$day.']:');
    //print_r($byDay[$day]);
    for($j=0;$j<count($byDay[$day]);$j++){
        $item=$byDay[$day][$id[$j]];
        //print_r($item);
        $time=date('H:i:s',strtotime($item['date']));
        $user=mysqli_fetch_assoc(DB::select('users',['*'],'id="'.$item['iduser'].'"'));
        $username=$user['lastname'].' '.$user['firstname'];
        //$username=$item['iduser'];
        echo('<br/>'.$username.' ['.$time.']');
    }
}