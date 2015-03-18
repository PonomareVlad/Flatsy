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
        if (response['add_task']){
            if(response['add_task']=='EMPTY DATA'){
                alert('Заполнены не все данные или не корректное заполнение');
            }else{
                Show_it=response['add_task'];
                check('all');
            }
        }
        if(response['set_task']){
            if(response['set_task']!=false){
                Show_it=response['set_task'];
                check('all');
            }
        }
    }else{
        if(auth==true){
            auth=false;
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
        if(TASK['PAST'][i]['id']==Show_it){
            Show_it=false;
            postload_show=[i,'PAST'];
        }
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
        if(TASK['TODAY'][i]['id']==Show_it){
            Show_it=false;
            postload_show=[i,'TODAY'];
        }
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
            if(TASK['CURRENT'][d][i]['id']==Show_it){
                Show_it=false;
                postload_show=[i,'CURRENT',d];
            }
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
            if(TASK['FUTURE'][d][i]['id']==Show_it){
                Show_it=false;
                postload_show=[i,'FUTURE',d];
            }
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
    if(postload_show){
        task_show(postload_show[0],postload_show[1],postload_show[2]?postload_show[2]:false);
    }
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
    if(taski['finished']!=1&&(taski['executor']==USER_ID||taski['initiator']==USER_ID)){
        source+='<input type="button" value="Завершить задачу" onclick="task_end('+taski['id']+');"/>';
    }
    document.getElementById('view').innerHTML = source;
    return false;
}

function show_add_task() {
    source = '<div class="title"><h4>Создание новой задачи</h4></div><p>' +
    '<label for="name">Постановка задачи</label><input type="text" name="task_title" id="name"></p>' +
    '<p><label for="description">Описание</label>' +
    '<textarea type="text" name="task_description" id="description"></textarea></p>' +
    '<p><label for="date_finish">Завершить</label><input size="45" type="text" placeholder="* Только в формате YYYY-MM-DD HH:MM:SS" name="date_final" id="date_finish"></p>' +
    //'<p><label for="project_id">Проект</label>' +
    //'<input type="text" name="project_id" id="idproject" placeholder="Если Ваша задача должна быть включена в проект, укажите его"></p>' +
    '<p><label for="executor">Отвественный</label><input size="33" type="text" placeholder="* ID, только в числовом формате" name="main_user" id="executor"></p>' +
    //'<p><label for="not_main_user">Соисполнители</label><input type="text" name="not_main_user" id="viser"></p>' +
    //'<p>Иван иванов, Иван иванов,Иван иванов</p><p>Прикрепить</p>' +
    '<input type="button" value="Создать" id="new_send" onclick="send_task();">' +
    ' * Отображаются, на данный момент,только те поля, которые,<br>функционально, имеют возможность обрабатываться системой!';
    document.getElementById('view').innerHTML = source;
    return false;
}

function send_task(){

    name=document.getElementById('name').value;
    description=document.getElementById('description').value;
    executor=document.getElementById('executor').value;
    date_finish=document.getElementById('date_finish').value;

    document.getElementById('description').innerHTML='Создаем...';
    io({"action":"add_task",
        "name":name,
        "description":description,
        "executor":executor,
        "date_finish":date_finish});
}

function task_end(id){
    io({"action":"set_task","param":"finished","value":"1","id":id});
}