function io(array,callback){
    query=JSON.stringify(array);
    Ajax('GET','/ajax.php?query='+query+'&rand='+new Date().getTime(),callback||'handler');
}

function check(mode){
    send={"check":mode||"simple"};
    io(send);
}

function logout(){
    send={"action":"logout"}
    io(send);
}

function handler(response) {
    response=JSON.parse(response);
    if (response['auth']==true) {
        auth=true;
        if (response['tasks']) {
            TASK=response['tasks'];
            tasks_upd();
        }
    }else{
        if(auth==true){
            auth=false;
            alert(response['auth']);
            window.location='/auth';
        }
    }
}

function tasks_upd() {
    date = new Date();
    now = new Date(date.getFullYear(), date.getMonth(), date.getDate()).getTime();
    source = '';
    source += '<div class="task_day" id="past"><div class="task_name">Просрочено</div>';
    for (i in TASK['PAST']) {
        if(tasks_mode=='my'){
            if(TASK['PAST'][i]['executor']!=USER_ID){
                continue;
            }
        }else if(tasks_mode=='unfinished'){
            if(TASK['PAST'][i]['finished']==1){
                continue;
            }
        }
        source += '<a href="#" onclick="task_show('+i+',\'PAST\');"><div class="task_info">' + TASK['PAST'][i]['name'] + '</div></a>';
    }
    source += '</div><div class="task_day active_day" id="today"><div class="task_name">Сегодня</div>';
    for (i in TASK['TODAY']) {
        if(tasks_mode=='my'){
            if(TASK['TODAY'][i]['executor']!=USER_ID){
                continue;
            }
        }else if(tasks_mode=='unfinished'){
            if(TASK['TODAY'][i]['finished']==1){
                continue;
            }
        }
        source += '<a href="#" onclick="task_show('+i+',\'TODAY\');"><div class="task_info">' + TASK['TODAY'][i]['name'] + '</div></a>';
    }
    source += '</div>';
    dates = new Array();
    dates_num = new Array()
    for (d in TASK['CURRENT']) {
        day = TASK['CURRENT'][d][0]['date_finish'];
        day = day.split(' ')[0];
        day = day.split('-');
        cur = new Date(day[0], day[1] - 1, day[2] - 1).getTime();
        day = day[2] + '.' + day[1] + '.' + day[0];
        if (now == cur) {
            cur = '0';
        }
        for (i in TASK['CURRENT'][d]) {
            if(tasks_mode=='my'){
                if(TASK['CURRENT'][d][i]['executor']!=USER_ID){
                    continue;
                }
            }else if(tasks_mode=='unfinished'){
                if(TASK['CURRENT'][d][i]['finished']==1){
                    continue;
                }
            }
            if (!dates[cur]) {
                dates[cur] = ''
            }
            ;
            dates_num[cur] = day;
            dates[cur] += '<a href="#" onclick="task_show('+i+',\'CURRENT\','+d+');"><div class="task_info">' + TASK['CURRENT'][d][i]['name'] + '</div></a>';
        }
    }
    for (d in TASK['FUTURE']) {
        day = TASK['FUTURE'][d][0]['date_start'];
        day = day.split(' ')[0];
        day = day.split('-');
        cur = new Date(day[0], day[1] - 1, day[2] - 1).getTime();
        day = day[2] + '.' + day[1] + '.' + day[0];
        if (now == cur) {
            cur = '0';
        }
        for (i in TASK['FUTURE'][d]) {
            if(tasks_mode=='my'){
                if(TASK['FUTURE'][d][i]['executor']!=USER_ID){
                    continue;
                }
            }else if(tasks_mode=='unfinished'){
                if(TASK['FUTURE'][d][i]['finished']==1){
                    continue;
                }
            }
            if (!dates[cur]) {
                dates[cur] = ''
            }
            ;
            dates_num[cur] = day;
            dates[cur] += '<a href="#" onclick="task_show('+i+',\'FUTURE\','+d+');"><div class="task_info">' + TASK['FUTURE'][d][i]['name'] + '</div></a>';
        }
    }
    for (f in dates) {
        if (f == 0 && dates['0']) {
            source += '<div class="task_day" id="' + dates_num[f] + '"><div class="task_name">Завтра</div>' + dates[f] + '</div>';
        } else {
            source += '<div class="task_day" id="' + dates_num[f] + '"><div class="task_name">' + dates_num[f] + '</div>' + dates[f] + '</div>';
        }
    }
    document.getElementById('tasks').innerHTML = source;
}

function task_show(id,type,dat){
    if(type=='FUTURE'||type=='CURRENT'){
        if(!dat){
            source='BAD LINK TO TASK DB! PLEASE REFRESH PAGE!';
            return false;
        }else{
            taski=TASK[type][dat][id];
        }
    }else{
        taski=TASK[type][id];
    }
    source='<h3>'+taski['name']+'</h3><div class="text">'+taski['description']+'</div><div class="info"><table>';
    source+='<tr><td>Статус</td><td>'+(taski['finished']==1?'Выполнена':'Не выполнена')+'</td></tr>';
    source+='<tr><td>Истекает</td><td>'+taski['date_finish']+'</td></tr>';
    source+='<tr><td>Создана</td><td>'+taski['date_start']+'</td></tr>';
    if(taski['idproject']!=0){
        source+='<tr><td>Проект</td><td>'+taski['idproject']+'</td></tr>';
    }
    source+='<tr><td>Инициатор</td><td>'+taski['initiator_name']+'</td></tr>';
    source+='<tr><td>Исполнитель</td><td>'+taski['executor_name']+'</td></tr></table></div>';
    document.getElementById('view').innerHTML = source;
    return false;
}