<?php
require_once ROOT.'system/class/db.php';
class TM extends DB
{
    public function add_task($query){

    }
    public function show_task($query){
        echo('KU');
        $res=mysql_query('SELECT * FROM task WHERE initiator="'.USER_ID.'" OR executor="'.USER_ID.'"');

        $res=mysql_fetch_array($res);

        $TODAY=[];
        $FUTURE=[];
        $PAST=[];

        foreach($res as $item){

            if(getdate($item['date_finish'])){
                echo getdate($item['date_finish']);
            }

        }
    }

}