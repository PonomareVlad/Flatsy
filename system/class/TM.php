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
            $idproject=$query['project']?$query['project']:'0';
            $parentask='0';

            if($name==''||$description==''||$executor==''||$date_finish==''){
                return 'EMPTY DATA';
            }

            $add=mysqli_query($MYSQL_CONNECTION,'INSERT INTO task (name,description,initiator,executor,date_start,date_finish,fact_finish,finished,idproject,parentask) VALUES("'.$name.'","'.$description.'","'.USER_ID.'","'.$executor.'","'.$now.'","'.$date_finish.'","'.$date_finish.'","0","'.$idproject.'","'.$parentask.'")');

            if($add==1){
                $chck=mysqli_query($MYSQL_CONNECTION,'SELECT * FROM task WHERE initiator="'.USER_ID.'" AND date_start="'.$now.'"');
                $chck=mysqli_fetch_assoc($chck);
                $chck['initiator_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id='.$chck['initiator'])));
                $chck['executor_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id='.$chck['executor'])));
                $chck['projectname'] = @mysqli_fetch_assoc(DB::select('project',['nameproject'],'idproject='.$chck['idproject']))['nameproject'];
                TM::create_notify('new_task',$chck['id']);
                return $chck;
            }

        }else{
            return 'EMPTY DATA';
        }
    }
    public static function get_task($id){
        $arr=DB::select('task',['*'],'id='.$id);
        $task=mysqli_fetch_assoc($arr);
        $task['initiator_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id='.$task['initiator'])));
        $task['executor_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id='.$task['executor'])));
        $task['projectname'] = mysqli_fetch_assoc(DB::select('project',['nameproject'],'idproject='.$task['idproject']))['nameproject'];
        return $task;
    }
    public static function set_task($query){
        global $MYSQL_CONNECTION;
        if($query['param']=='finished'){
            $set=mysqli_query($MYSQL_CONNECTION,'UPDATE task SET finished = '.$query['value'].' WHERE task.id = '.$query['id']);
            if($set==1){
                return ["id"=>$query['id'],"param"=>$query['param'],"value"=>$query['value']];
            }else{
                return false;
            }
        }
    }
    public static function edit_task($query)
    {
        $name = Checkdata($query['name']);
        $description = Checkdata($query['description']);
        $executor = Checkdata($query['executor']);
        $date_finish = $query['date_finish'] . ':00';
        $idproject = $query['project'] ? $query['project'] : '0';
        $parentask = '0';
        $fields=["name"=>$name,"description"=>$description,"executor"=>$executor,"date_finish"=>$date_finish,"idproject"=>$idproject];
        $set = DB::update('task',$fields, 'task.id = ' . $query['id']);
        if ($set == 1) {
            return TM::get_task($query['id']);
        } else {
            return false;
        }

    }
    public static function del_task($id){
        $task=TM::get_task($id);
        if($task['initiator']==USER_ID){
            $set=DB::delete('task','task.id = '.$task['id']);
            if($set==1){
                return $task['id'];
            }else{
                return false;
            }
        }
    }
    public static function del_project($id){
        $project = mysqli_fetch_assoc(DB::select('project', ['*'], 'idproject="' . $id . '"'));
        if($project['initiator']==USER_ID){
            $set=DB::delete('project','idproject = '.$project['idproject']);
            if($set==1){
                $tasks = DB::select('task', ['*'], 'idproject=' . $project['idproject']);
                while ($taska = mysqli_fetch_assoc($tasks)) {
                    $res=DB::update('task',["idproject"=>0],'id = ' . $taska['id']);
                }
                return $project['idproject'];
            }else{
                return false;
            }
        }
    }
    public static function edit_project($query)
    {
        $name = Checkdata($query['name']);
        $description = Checkdata($query['description']);
        //$executor = Checkdata($query['executor']);
        $date_finish = $query['date_finish'] . ':00';
        //$idproject = $query['project'] ? $query['project'] : '0';
        //$parentask = '0';
        $fields=["nameproject"=>$name,"description"=>$description,"date_finish"=>$date_finish];
        $set = DB::update('project',$fields, 'idproject = ' . $query['id']);
        if ($set == 1) {
            return $query['id'];
        } else {
            return false;
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
                $comms[$num]['usercom_name']=@implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id="' . $comment['usercom'] . '"')));
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
            $project[$proj['idproject']]['initiator_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id="' . $proj['initiator'] . '"')));
            $project[$proj['idproject']]['tasks']=[];
            $tasks=mysqli_query($MYSQL_CONNECTION,'SELECT * FROM task WHERE idproject='.$proj['idproject']);
            while($taska=mysqli_fetch_assoc($tasks)){
                $num=count($project[$proj['idproject']]['tasks']);
                $project[$proj['idproject']]['tasks'][$num]=$taska;
                $project[$proj['idproject']]['tasks'][$num]['initiator_name']=@implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id="' . $taska['initiator'] . '"')));
                $project[$proj['idproject']]['tasks'][$num]['executor_name']=@implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id="' . $taska['executor'] . '"')));
            }
        }
        $task=TM::show_task($query,false);
        for($i=0;$i<count($task);$i++){
            if($task[$i]['idproject']!=0){
                $res=mysqli_query($MYSQL_CONNECTION,'SELECT * FROM project WHERE idproject="'.$task[$i]['idproject'].'"');
                $proj=mysqli_fetch_assoc($res);
                $project[$proj['idproject']]=$proj;
                $project[$proj['idproject']]['initiator_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id="' . $proj['initiator'] . '"')));
                $project[$proj['idproject']]['tasks']=[];
                $tasks=mysqli_query($MYSQL_CONNECTION,'SELECT * FROM task WHERE idproject='.$proj['idproject']);
                while($taska=mysqli_fetch_assoc($tasks)){
                    $num=count($project[$proj['idproject']]['tasks']);
                    $project[$proj['idproject']]['tasks'][$num]=$taska;
                    $project[$proj['idproject']]['tasks'][$num]['initiator_name']=@implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id="' . $taska['initiator'] . '"')));
                    $project[$proj['idproject']]['tasks'][$num]['executor_name']=@implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id="' . $taska['executor'] . '"')));
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
                $proj=mysqli_query($MYSQL_CONNECTION,'SELECT * FROM project WHERE initiator="'.USER_ID.'" AND date_start="'.$now.'"');
                $proj=mysqli_fetch_assoc($proj);
                $proj['initiator_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users', ['lastname', 'firstname'], 'id=' . $proj['initiator'])));
                $proj['tasks'] = [];
                DB::inserti('visprojectuser','(iduser,idproject) VALUES ('.USER_ID.','.$proj['idproject'].')');
                $users=DB::select('visprojectuser',['*'],'idproject='.$proj['idproject']);
                $proj['users']=[];
                while($userlink=mysqli_fetch_assoc($users)){
                    $proj['users'][]=User::get_user($userlink['iduser']);
                }
                return $proj;
            }

        }else{
            return 'EMPTY DATA';
        }
    }
    public static function add_group($name){

        $name=Checkdata($name);
        $checkname = mysqli_fetch_assoc(DB::select('groups',['namegroup'],'namegroup="'.$name.'"'));
        if (isset($checkname['namegroup'])) {
            return false;
        }
        $add=DB::inserti('groups','(namegroup,creator,owner) VALUES ("'.$name.'",'.USER_ID.','.USER_ID.')');
        if($add==1){
            $group=mysqli_fetch_assoc(DB::select('groups',['*'],'namegroup="'.$name.'"'));
            $group['subgroup']=[];
            $group['users']=[];
            $group['count_users']=1;
            DB::inserti('useringroup','(iduser,idgroup,userlvl,statususer) VALUES ('.+USER_ID.','.$group['idgroup'].',5,3)');
            return $group;
        }
    }
    public static function get_tasks(){
        $tasks=[];
        $sql = DB::select('task',['*'],'initiator="'.USER_ID.'" OR executor="'.USER_ID.'"');
        while ($task = mysqli_fetch_assoc($sql)) {
            $task['initiator_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id='.$task['initiator'])));
            $task['executor_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id='.$task['executor'])));
            $task['projectname'] = mysqli_fetch_assoc(DB::select('project',['nameproject'],'idproject='.$task['idproject']))['nameproject'];
            $tasks[]=$task;
        }
        return $tasks;
    }
    public static function add_comment($id,$type,$text){
        if(isset($id,$type,$text)){
            $now=date("y-m-d G:i:s");
            $text=str_replace(array("\r\n", "\r", "\n"), '<br>', strip_tags(Checkdata($text)));
            if(trim($text)=='<br>'||trim($text=='')){
                return false;
            }
            $max=mysqli_fetch_assoc(DB::select('comments',['MAX(numbercom) AS numbercom'],'idobject='.$id.' AND type="'.$type.'"'));
            //$add=mysqli_query($MYSQL_CONNECTION,'INSERT INTO comments (idtask, numbercom, usercom, comment, datacom) VALUES ('.$query['id'].','.($max['numbercom']+1).','.USER_ID.',"'.$text.'","'.$now.'")');
            $add=DB::inserti('comments','(idobject, type, numbercom, usercom, comment, datacom) VALUES ('.$id.',"'.$type.'",'.($max['numbercom']+1).','.USER_ID.',"'.$text.'","'.$now.'")');
            if($add==1){
                $comment=mysqli_fetch_assoc(DB::select('comments',['*'],'idobject='.$id.' AND type="'.$type.'" AND datacom="'.$now.'"'));
                $comment['usercom_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users', ['firstname', 'lastname'], 'id=' . $comment['usercom'])));
                $comment['usercom_photo'] = User::get_user($comment['usercom'])['photo'];
                TM::create_notify('new_comment',$comment['id']);
                return $comment;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    public static function get_comments($id,$type){
        if(isset($id)){
            $comms=[];
            $array=DB::select('comments',['*'],'idobject='.$id.' AND type="'.$type.'"');
            while($comment=mysqli_fetch_assoc($array)){
                $num=count($comms);
                $comms[$num]=$comment;
                $comms[$num]['usercom_name']=@implode(' ', mysqli_fetch_assoc(DB::select('users',['firstname','lastname'],'id='.$comment['usercom'])));
                $comms[$num]['usercom_photo']=User::get_user($comment['usercom'])['photo'];
            }
            return $comms;//array_reverse($comms);
        }else{
            return false;
        }
    }
    public static function get_projects()
    {
        $is=[];
        $project = [];
        $res = DB::select('project', ['*'], 'initiator="' . USER_ID . '"');
        while ($proj = mysqli_fetch_assoc($res)) {
            //$num=count($project);
            $proj['initiator_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users', ['lastname', 'firstname'], 'id=' . $proj['initiator'])));
            $proj['tasks'] = [];
            $users=DB::select('visprojectuser',['*'],'idproject='.$proj['idproject']);
            $proj['users']=[];
            while($userlink=mysqli_fetch_assoc($users)){
                $proj['users'][]=User::get_user($userlink['iduser']);
            }
            $tasks = DB::select('task', ['*'], 'idproject=' . $proj['idproject']);
            while ($taska = mysqli_fetch_assoc($tasks)) {
                $num = count($proj['tasks']);
                $proj['tasks'][$num] = $taska;
                $proj['tasks'][$num]['initiator_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users', ['lastname', 'firstname'], 'id="' . $taska['initiator'] . '"')));
                $proj['tasks'][$num]['executor_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users', ['lastname', 'firstname'], 'id="' . $taska['executor'] . '"')));
            }
            $is[$proj['idproject']]=true;
            $project[]=$proj;
        }
        $task = TM::get_tasks();
        for ($i = 0; $i < count($task); $i++) {
            if ($task[$i]['idproject'] != 0) {
                $res = DB::select('project', ['*'], 'idproject="' . $task[$i]['idproject'] . '"');
                $proj = mysqli_fetch_assoc($res);
                if(!isset($is[$proj['idproject']])) {
                    $proj['initiator_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users', ['lastname', 'firstname'], 'id="' . $proj['initiator'] . '"')));
                    $proj['tasks'] = [];
                    $tasks = DB::select('task', ['*'], 'idproject=' . $proj['idproject']);
                    while ($taska = mysqli_fetch_assoc($tasks)) {
                        $num = count($proj['tasks']);
                        $proj['tasks'][$num] = $taska;
                        $proj['tasks'][$num]['initiator_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users', ['lastname', 'firstname'], 'id="' . $taska['initiator'] . '"')));
                        $proj['tasks'][$num]['executor_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users', ['lastname', 'firstname'], 'id="' . $taska['executor'] . '"')));
                    }
                    $is[$proj['idproject']]=true;
                    $project[] = $proj;
                }
            }
        }

        return $project;
    }
    public static function del_user($id,$idgroup){
        //DB::inserti('useringroup','(iduser,idgroup,userlvl,statususer) VALUES ('.+USER_ID.','.$group['idgroup'].',5,3)');
        $del=DB::delete('useringroup','iduser='.$id.' AND idgroup='.$idgroup);
        if($del==1){
            return $id;
        }else{
            return false;
        }
    }
    public static function create_notify($type,$id)
    {
        $notify=false;
        if ($type == 'new_comment') {
            $notify=[];
            $comment = mysqli_fetch_assoc(DB::select('comments', ['*'], 'id='.$id));
            if($comment['type']=='task') {
                $object = mysqli_fetch_assoc(DB::select('task', ['*'], 'id='.$comment['idobject']));
                if(USER_ID!=$object['executor']){$notify[]=$object['executor'];}
                if(USER_ID!=$object['initiator']){$notify[]=$object['initiator'];}
            }
        }
        if($type=='new_task'){
            $notify=[];
            $task= mysqli_fetch_assoc(DB::select('task',['*'],'id='.$id));
            if(USER_ID!=$task['executor']){$notify[]=$task['executor'];}
        }
        if(is_array($notify)){
            for($i=0;$i<count($notify);$i++){
                DB::insert('notifications',['iduser'=>$notify[$i],'type'=>$type,'value'=>$id]);
            }
        }
    }
}