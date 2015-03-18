<?php
require_once ROOT.'system/class/db.php';
class TM extends DB
{
    public function add_task($query){

    }
    public function show_task($query)
    {
        $res = mysql_query('SELECT * FROM task WHERE initiator="' . USER_ID . '" OR executor="' . USER_ID . '"');
        $TODAY = [];
        $FUTURE = [];
        $PAST = [];
        $CURRENT = [];
        $now = strtotime(date('Y-m-d'));//+14400; //Time zone offset (Ekaterinburg,Russia)
        while ($task = mysql_fetch_array($res, MYSQL_ASSOC)) {
            $task['initiator_name'] = implode(' ', mysql_fetch_assoc(mysql_query('SELECT firstname,lastname FROM tm.users WHERE id="' . $task['initiator'] . '"')));
            $task['executor_name'] = implode(' ', mysql_fetch_assoc(mysql_query('SELECT firstname,lastname FROM tm.users WHERE id="' . $task['executor'] . '"')));
            $cur_end = strtotime(explode(' ', $task['date_finish'])[0]);
            $cur_start = strtotime(explode(' ', $task['date_start'])[0]);
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
                    if ($now == $cur_end) {
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

}