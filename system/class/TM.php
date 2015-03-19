<?php
require_once ROOT.'system/class/db.php';
class TM extends DB
{
    public function add_task($query){
        if(isset($query['name'],$query['description'],$query['executor'],$query['date_finish'])){

            $now=date("y-m-d G:i:s",strtotime(date('y-m-d G:i:s'))+14400);
            $name=Checkdata($query['name']);
            $description=Checkdata($query['description']);
            $executor=Checkdata($query['executor']);
            $date_finish=$query['date_finish'].':00';
            $idproject='0';
            $parentask='0';

            if($name==''||$description==''||$executor==''||$date_finish==''){
                return 'EMPTY DATA';
            }

            $add=mysql_query('INSERT INTO task (name,description,initiator,executor,date_start,date_finish,fact_finish,finished,idproject,parentask) VALUES("'.$name.'","'.$description.'","'.USER_ID.'","'.$executor.'","'.$now.'","'.$date_finish.'","'.$date_finish.'","0","'.$idproject.'","'.$parentask.'")');

            if($add==1){
                $chck=mysql_query('SELECT id FROM task WHERE initiator="'.USER_ID.'" AND date_start="'.$now.'"');
                $chck=mysql_fetch_array($chck);
                return $chck['id'];
            }

        }else{
            return 'EMPTY DATA';
        }
    }
    public function show_task($query)
    {
        $res = mysql_query('SELECT * FROM task WHERE initiator="' . USER_ID . '" OR executor="' . USER_ID . '"');
        $TODAY = [];
        $FUTURE = [];
        $PAST = [];
        $CURRENT = [];
        $now = strtotime(date('y-m-d G:i:s'))+14400; //Time zone offset (Ekaterinburg,Russia)
        $now_day=strtotime(date("y-m-d",$now));
        while ($task = mysql_fetch_array($res, MYSQL_ASSOC)) {
            $task['initiator_name'] = @implode(' ', mysql_fetch_assoc(mysql_query('SELECT firstname,lastname FROM tm.users WHERE id="' . $task['initiator'] . '"')));
            $task['executor_name'] = @implode(' ', mysql_fetch_assoc(mysql_query('SELECT firstname,lastname FROM tm.users WHERE id="' . $task['executor'] . '"')));
            $cur_end = strtotime($task['date_finish']);
            $cur_start = strtotime($task['date_start']);
            //echo '['.$now.']<br/>['.$cur_end.']<br>';
            if ($now < $cur_start) {
                // Если еще не началась
                $FUTURE[$cur_start][] = $task;
            } else {
                // Если уже началась
                if ($now > $cur_end) {
                    //echo '<br>'.$now.'<br>'.$cur_end.'<br>';
                    // Просрочена
                    $PAST[] = $task;
                } else {
                    // Не истекла
                    //echo($now . '<br>' . $cur_end . '<br>');
                    if ($now_day == strtotime(date("y-m-d",$cur_end))) {
                        // Сегодня
                        $TODAY[] = $task;
                    } else {
                        $CURRENT[$cur_end][] = $task;
                    }
                }
            }
        }
        if (isset($cur_start)) {
            return array('FUTURE' => $FUTURE, 'CURRENT'=>$CURRENT, 'TODAY' => $TODAY, 'PAST' => $PAST);
        }else{
            return false;
        }
    }
    public function set_task($query){
        if($query['param']=='finished'){
            $set=mysql_query('UPDATE tm.task SET finished = '.$query['value'].' WHERE task.id = '.$query['id']);
            if($set==1){
                return $query['id'];
            }else{
                return false;
            }
        }
    }
    public function get_comm($query){
        if(isset($query['id'])){
            $comms=[];
            $array=mysql_query('SELECT * FROM comments WHERE idtask='.$query['id']);
            while($comment=mysql_fetch_array($array, MYSQL_ASSOC)){
                $num=count($comms);
                $comms[$num]=$comment;
                $comms[$num]['usercom_name']=@implode(' ', mysql_fetch_assoc(mysql_query('SELECT firstname,lastname FROM tm.users WHERE id="' . $comment['usercom'] . '"')));
            }
            return $comms;//array_reverse($comms);
        }else{
            return false;
        }
    }
    public function add_comm($query){
        if(isset($query['id'],$query['text'])){
            $now=date("y-m-d G:i:s");
            $text=str_replace(array("\r\n", "\r", "\n"), '<br>', strip_tags(Checkdata($query['text'])));
            if(trim($text)=='<br>'||trim($text=='')){
                return false;
            }
            $max=mysql_fetch_array(mysql_query('SELECT MAX(numbercom) AS numbercom FROM comments WHERE idtask='.$query['id']));
            $add=mysql_query('INSERT INTO comments (idtask, numbercom, usercom, comment, datacom) VALUES ('.$query['id'].','.($max['numbercom']+1).','.USER_ID.',"'.$text.'","'.$now.'")');
            if($add==1){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

}