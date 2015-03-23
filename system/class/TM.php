<?php
require_once ROOT.'system/class/db.php';
class TM extends DB
{
    public static function add_task($query){
        global $MYSQL_CONNECTION;
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

            $add=mysqli_query($MYSQL_CONNECTION,'INSERT INTO task (name,description,initiator,executor,date_start,date_finish,fact_finish,finished,idproject,parentask) VALUES("'.$name.'","'.$description.'","'.USER_ID.'","'.$executor.'","'.$now.'","'.$date_finish.'","'.$date_finish.'","0","'.$idproject.'","'.$parentask.'")');

            if($add==1){
                $chck=mysqli_query($MYSQL_CONNECTION,'SELECT id FROM task WHERE initiator="'.USER_ID.'" AND date_start="'.$now.'"');
                $chck=mysqli_fetch_array($chck);
                return $chck['id'];
            }

        }else{
            return 'EMPTY DATA';
        }
    }
    public static function show_task($query,$separated=true){
        global $MYSQL_CONNECTION;
        $res = mysqli_query($MYSQL_CONNECTION,'SELECT * FROM task WHERE initiator="' . USER_ID . '" OR executor="' . USER_ID . '"');
        $TODAY = [];
        $FUTURE = [];
        $PAST = [];
        $CURRENT = [];
        $now = strtotime(date('y-m-d G:i:s'))+14400; //Time zone offset (Ekaterinburg,Russia)
        $now_day=strtotime(date("y-m-d",$now));
        while ($task = mysqli_fetch_assoc($res)) {
            $task['initiator_name'] = @implode(' ', mysqli_fetch_assoc(mysqli_query($MYSQL_CONNECTION,'SELECT firstname,lastname FROM users WHERE id="' . $task['initiator'] . '"')));
            $task['executor_name'] = @implode(' ', mysqli_fetch_assoc(mysqli_query($MYSQL_CONNECTION,'SELECT firstname,lastname FROM users WHERE id="' . $task['executor'] . '"')));
            if($separated==true) {
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
                        if ($now_day == strtotime(date("y-m-d", $cur_end))) {
                            // Сегодня
                            $TODAY[] = $task;
                        } else {
                            $CURRENT[$cur_end][] = $task;
                        }
                    }
                }
            }else{
                $TODAY[]=$task;
            }
        }
        if($separated!=true){
            return $TODAY;
        }
        if (isset($cur_start)) {
            return array('FUTURE' => $FUTURE, 'CURRENT'=>$CURRENT, 'TODAY' => $TODAY, 'PAST' => $PAST);
        }else{
            return false;
        }
    }
    public static function get_task($id){
        $arr=DB::select('task',['*'],'id='.$id);
        $task=mysqli_fetch_assoc($arr);
        $task['initiator_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id='.$task['initiator'])));
        $task['executor_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id='.$task['executor'])));
        return $task;
    }
    public static function set_task($query){
        global $MYSQL_CONNECTION;
        if($query['param']=='finished'){
            $set=mysqli_query($MYSQL_CONNECTION,'UPDATE task SET finished = '.$query['value'].' WHERE task.id = '.$query['id']);
            if($set==1){
                return $query['id'];
            }else{
                return false;
            }
        }
    }
    public static function get_comm($query){
        global $MYSQL_CONNECTION;
        if(isset($query['id'])){
            $comms=[];
            $array=mysqli_query($MYSQL_CONNECTION,'SELECT * FROM comments WHERE idtask='.$query['id']);
            while($comment=mysqli_fetch_assoc($array)){
                $num=count($comms);
                $comms[$num]=$comment;
                $comms[$num]['usercom_name']=@implode(' ', mysqli_fetch_assoc(mysqli_query($MYSQL_CONNECTION,'SELECT firstname,lastname FROM users WHERE id="' . $comment['usercom'] . '"')));
                $comms[$num]['usercom_photo']=User::get_user(['id'=>$comment['usercom']])['photo'];
            }
            return $comms;//array_reverse($comms);
        }else{
            return false;
        }
    }
    public static function add_comm($query){
        global $MYSQL_CONNECTION;
        if(isset($query['id'],$query['text'])){
            $now=date("y-m-d G:i:s");
            $text=str_replace(array("\r\n", "\r", "\n"), '<br>', strip_tags(Checkdata($query['text'])));
            if(trim($text)=='<br>'||trim($text=='')){
                return false;
            }
            $max=mysqli_fetch_assoc(mysqli_query($MYSQL_CONNECTION,'SELECT MAX(numbercom) AS numbercom FROM comments WHERE idtask='.$query['id']));
            $add=mysqli_query($MYSQL_CONNECTION,'INSERT INTO comments (idtask, numbercom, usercom, comment, datacom) VALUES ('.$query['id'].','.($max['numbercom']+1).','.USER_ID.',"'.$text.'","'.$now.'")');
            if($add==1){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    public static function show_projects($query){
        global $MYSQL_CONNECTION;
        $project=[];
        $res = mysqli_query($MYSQL_CONNECTION,'SELECT * FROM project WHERE initiator="' . USER_ID . '"');
        while($proj=mysqli_fetch_assoc($res)){
            //$num=count($project);
            $project[$proj['idproject']]=$proj;
            $project[$proj['idproject']]['initiator_name'] = @implode(' ', mysqli_fetch_assoc(mysqli_query($MYSQL_CONNECTION,'SELECT firstname,lastname FROM users WHERE id="' . $proj['initiator'] . '"')));
            $project[$proj['idproject']]['tasks']=[];
            $tasks=mysqli_query($MYSQL_CONNECTION,'SELECT * FROM task WHERE idproject='.$proj['idproject']);
            while($taska=mysqli_fetch_assoc($tasks)){
                $num=count($project[$proj['idproject']]['tasks']);
                $project[$proj['idproject']]['tasks'][$num]=$taska;
                $project[$proj['idproject']]['tasks'][$num]['initiator_name']=@implode(' ', mysqli_fetch_assoc(mysqli_query($MYSQL_CONNECTION,'SELECT firstname,lastname FROM users WHERE id="' . $taska['initiator'] . '"')));
                $project[$proj['idproject']]['tasks'][$num]['executor_name']=@implode(' ', mysqli_fetch_assoc(mysqli_query($MYSQL_CONNECTION,'SELECT firstname,lastname FROM users WHERE id="' . $taska['executor'] . '"')));
            }
        }
        $task=TM::show_task($query,false);
        for($i=0;$i<count($task);$i++){
            if($task[$i]['idproject']!=0){
                $res=mysqli_query($MYSQL_CONNECTION,'SELECT * FROM project WHERE idproject="'.$task[$i]['idproject'].'"');
                $proj=mysqli_fetch_assoc($res);
                $project[$proj['idproject']]=$proj;
                $project[$proj['idproject']]['initiator_name'] = @implode(' ', mysqli_fetch_assoc(mysqli_query($MYSQL_CONNECTION,'SELECT firstname,lastname FROM users WHERE id="' . $proj['initiator'] . '"')));
                $project[$proj['idproject']]['tasks']=[];
                $tasks=mysqli_query($MYSQL_CONNECTION,'SELECT * FROM task WHERE idproject='.$proj['idproject']);
                while($taska=mysqli_fetch_assoc($tasks)){
                    $num=count($project[$proj['idproject']]['tasks']);
                    $project[$proj['idproject']]['tasks'][$num]=$taska;
                    $project[$proj['idproject']]['tasks'][$num]['initiator_name']=@implode(' ', mysqli_fetch_assoc(mysqli_query($MYSQL_CONNECTION,'SELECT firstname,lastname FROM users WHERE id="' . $taska['initiator'] . '"')));
                    $project[$proj['idproject']]['tasks'][$num]['executor_name']=@implode(' ', mysqli_fetch_assoc(mysqli_query($MYSQL_CONNECTION,'SELECT firstname,lastname FROM users WHERE id="' . $taska['executor'] . '"')));
                }
            }
        }

        return $project;
    }
    public static function add_project($query){
        global $MYSQL_CONNECTION;
        if(isset($query['name'],$query['description'],$query['date_finish'])){

            $now=date("y-m-d G:i:s",strtotime(date('y-m-d G:i:s'))+14400);
            $name=Checkdata($query['name']);
            $description=Checkdata($query['description']);
            $date_finish=$query['date_finish'].':00';
            $parentproject='0';

            if($name==''||$description==''||$date_finish==''){
                return 'EMPTY DATA';
            }

            $add=mysqli_query($MYSQL_CONNECTION,'INSERT INTO project (nameproject,description,initiator,date_start,date_finish,fact_finish,parentproject) VALUES("'.$name.'","'.$description.'","'.USER_ID.'","'.$now.'","'.$date_finish.'","'.$date_finish.'","'.$parentproject.'")');

            if($add==1){
                $chck=mysqli_query($MYSQL_CONNECTION,'SELECT idproject FROM project WHERE initiator="'.USER_ID.'" AND date_start="'.$now.'"');
                $chck=mysqli_fetch_assoc($chck);
                return $chck['idproject'];
            }

        }else{
            return 'EMPTY DATA';
        }
    }
    public static function get_tasks(){
        $tasks=[];
        $sql = DB::select('task',['*'],'initiator="'.USER_ID.'" OR executor="'.USER_ID.'"');
        while ($task = mysqli_fetch_assoc($sql)) {
            $task['initiator_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id='.$task['initiator'])));
            $task['executor_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id='.$task['executor'])));
            $tasks[]=$task;
        }
        return $tasks;
    }

}