function handler(response) {
    response=JSON.parse(response);
    if (response['auth']==true) {
        if(response['check']){
            if(response['NEW']){
                if(response['NEW']['TASK']){
                    for(i in response['NEW']['TASK']) {
                        // BUILD UPD DB
                        task = response['NEW']['TASK'][i];
                        DB['TASK'][DB['TASK'].length] = task;
                        if (task['idproject'] != 0) {
                            for (p in DB['PROJECT']) {
                                if (DB['PROJECT'][p]['idproject'] == task['idproject']) {
                                    DB['PROJECT'][p]['tasks'][DB['PROJECT'][p]['tasks'].length] = task;
                                }
                            }
                        }
                        if (TM['current_page'] == 'tasks') {
                            gen_list();
                        }
                        //view(task['id'], 'task');
                    }
                }
                if(response['NEW']['COMMENT']){
                    offset=3600000*5;
                    MONTH=["января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"];
                    date=new Date(new Date().getTime()+offset);
                    now = new Date(date.getFullYear(), date.getMonth(), date.getDate()).getTime();
                    for(i in response['NEW']['COMMENT']){
                        comment=response['NEW']['COMMENT'][i];
                        // BUILD UPD DB
                        if(comment['idobject']==TM['CID']&&comment['type']==TM['comments_loaded']) {
                            source='';
                            datacom=comment['datacom'].split(' ');
                            datestring=datacom[0]+'T'+datacom[1];
                            date= new Date(new Date(datestring).getTime()+offset);
                            timecom=[(date.getHours()<10?'0':'')+date.getHours(),(date.getMinutes()<10?'0':'')+date.getMinutes()];
                            datacom=[(date.getDate()<10?'0':'')+date.getDate(),date.getMonth()];
                            datestring=new Date(date.getFullYear(), date.getMonth(), date.getDate()).getTime();
                            source+='<div class="comment">' +
                            '<img src="'+comment['usercom_photo']+'"><div class="info_text">' +
                            '<a href="javascript:void(0)" onclick=\'view('+comment['usercom']+',"user")\'><div class="name">'+comment['usercom_name']+'</div></a>' +
                            '<div class="date">'+(now==datestring?('сегодня в '+timecom[0]+':'+timecom[1]):datacom[0]+' '+MONTH[parseInt(datacom[1])])+'</div>' +
                            '<p class="text">'+comment['comment']+'</p></div></div>';
                            if(TM['empty_comments']){
                                document.getElementById('comments').innerHTML='';
                                TM['empty_comments']=false;
                            }
                            //alert(source);
                            document.getElementById('comments').innerHTML += source;
                            document.getElementById('comments').scrollTop=9999;
                        }else{
                            //alert('New comment for '+comment['type']+' '+comment['idobject']);
                        }
                    }
                }
            }
        }
        if(response['DB']){
            DB=response['DB'];
            TM['update_db']=false;
            if(TM['wait_load']){
                TM['wait_load']=false;
                //alert('load page..');
                TM['current_page']=(TM['current_page']=='auth'||TM['current_page']=='reg')?'tasks':TM['current_page'];
                //TM['current_page']=PAGE[TM['current_page']]?TM['current_page']:'tasks';
                //document.getElementById('main').className='blur';
                page(TM['current_page']==false?'tasks':TM['current_page'],true);
                //alert('loaded');
            }
            if(TM['tmp_rebuild_lists']) {
                TM['highlight_day']=false;
                TM['highlight_element']=false;
                gen_list();
                TM['tmp_rebuild_lists'] = false;
            }
            if(TM['tmp_view_upd']){
                TM['highlight_day']=false;
                TM['highlight_element']=false;
                gen_list();
                view(TM['view_id'],TM['view_type']);
                TM['tmp_view_upd']=false;
            }
            document.getElementById('main').className='noblur';
            // BUILD REFRESH VIEW
            //gen_list();
        }
        if(response['new_comment']){
            comment=response['new_comment'];
            if(comment['idobject']==TM['CID']&&comment['type']==TM['comments_loaded']) {
                source='';
                offset=3600000*5;
                MONTH=["января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"];
                date=new Date(new Date().getTime()+offset);
                now = new Date(date.getFullYear(), date.getMonth(), date.getDate()).getTime();
                datacom=comment['datacom'].split(' ');
                datestring=datacom[0]+'T'+datacom[1];
                date= new Date(new Date(datestring).getTime()+offset);
                timecom=[(date.getHours()<10?'0':'')+date.getHours(),(date.getMinutes()<10?'0':'')+date.getMinutes()];
                datacom=[(date.getDate()<10?'0':'')+date.getDate(),date.getMonth()];
                datestring=new Date(date.getFullYear(), date.getMonth(), date.getDate()).getTime();
                source+='<div class="comment">' +
                '<img src="'+comment['usercom_photo']+'"><div class="info_text">' +
                '<a href="javascript:void(0)" onclick=\'view('+comment['usercom']+',"user")\'><div class="name">'+comment['usercom_name']+'</div></a>' +
                '<div class="date">'+(now==datestring?('сегодня в '+timecom[0]+':'+timecom[1]):datacom[0]+' '+MONTH[parseInt(datacom[1])])+'</div>' +
                '<p class="text">'+comment['comment']+'</p></div></div>';
                if(TM['empty_comments']){
                    TM['empty_comments']=false;
                    document.getElementById('comments').innerHTML='';
                }
                document.getElementById('comments').innerHTML += source;
                document.getElementById('comments').scrollTop = 9999;
            }
        }
        if(response['add_task']) {
            if (response['add_task'] == 'EMPTY DATA') {
                alert('Некорректное заполнение полей');
            } else {
                task = response['add_task'];
                if (TM['current_page'] == 'tasks') {
                    DB['TASK'][DB['TASK'].length] = task;
                    if(task['idproject']!=0){
                        for(p in DB['PROJECT']){
                            if(DB['PROJECT'][p]['idproject']==task['idproject']){
                                DB['PROJECT'][p]['tasks'][DB['PROJECT'][p]['tasks'].length]=task;
                            }
                        }
                    }
                    gen_list();
                    view(task['id'], 'task');
                }
            }
        }
        if(response['add_project']) {
            if (response['add_project'] == 'EMPTY DATA') {
                alert('Некорректное заполнение полей');
            } else {
                project = response['add_project'];
                if (TM['current_page'] == 'projects') {
                    DB['PROJECT'][DB['PROJECT'].length] = project;
                    gen_list();
                    view(project['idproject'], 'project');
                }
            }
        }
        if(response['set_task']){
            if(response['set_task']!=false){
                if(response['set_task']['param']=='finished'){
                    if(response['set_task']['value']==1) {
                        get('fhd' + response['set_task']['id']).innerHTML='<img src="templates/default/images/done.png">';
                    }else{
                        get('fhd' + response['set_task']['id']).innerHTML='<img src="templates/default/images/n_done.png">';
                    }

                }
                for(t in DB['TASK']){
                    if(DB['TASK'][t]['id']==response['set_task']['id']){
                        DB['TASK'][t][response['set_task']['param']]=response['set_task']['value'];
                        if(DB['TASK'][t]['idproject']!=0){
                            for(p in DB['PROJECT']){
                                if(DB['PROJECT'][p]['idproject']==DB['TASK'][t]['idproject']){
                                    for(i in DB['PROJECT'][p]['tasks']){
                                        if(DB['PROJECT'][p]['tasks'][i]['id']==DB['TASK'][t]['id']){
                                            DB['PROJECT'][p]['tasks'][i][response['set_task']['param']]=response['set_task']['value'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if(response['edit_task']){
            if(response['edit_task']!=false){
                for(t in DB['TASK']){
                    if(DB['TASK'][t]['id']==response['edit_task']['id']){
                        if(DB['TASK'][t]['idproject']!=response['edit_task']['idproject']) {
                            //alert(DB['TASK'][t]['idproject']+'>>'+response['edit_task']['idproject'])
                            if (response['edit_task']['idproject'] != 0) {
                                for (p in DB['PROJECT']) {
                                    if (DB['PROJECT'][p]['idproject'] == DB['TASK'][t]['idproject']) {
                                        for (i in DB['PROJECT'][p]['tasks']) {
                                            if (DB['PROJECT'][p]['tasks'][i]['id'] == DB['TASK'][t]['id']) {
                                                DB['PROJECT'][p]['tasks'].splice(i, 1);
                                                //alert('Deleted from '+DB['PROJECT'][p]['nameproject']);
                                            }
                                        }
                                    }
                                    if(DB['PROJECT'][p]['idproject'] == response['edit_task']['idproject']){
                                        DB['PROJECT'][p]['tasks'][DB['PROJECT'][p]['tasks'].length]=response['edit_task'];
                                        //alert('Inserted into '+DB['PROJECT'][p]['nameproject']);
                                    }
                                }
                            }else{
                                for (p in DB['PROJECT']) {
                                    if (DB['PROJECT'][p]['idproject'] == DB['TASK'][t]['idproject']) {
                                        for (i in DB['PROJECT'][p]['tasks']) {
                                            if (DB['PROJECT'][p]['tasks'][i]['id'] == DB['TASK'][t]['id']) {
                                                DB['PROJECT'][p]['tasks'].splice(i, 1);
                                                //alert('Deleted from '+DB['PROJECT'][p]['nameproject']);
                                            }
                                        }
                                    }
                                }
                                page('tasks');
                            }
                        }else {
                            if (DB['TASK'][t]['idproject'] != 0) {
                                for (p in DB['PROJECT']) {
                                    if (DB['PROJECT'][p]['idproject'] == response['edit_task']['idproject']) {
                                        for (i in DB['PROJECT'][p]['tasks']) {
                                            if (DB['PROJECT'][p]['tasks'][i]['id'] == response['edit_task']['id']) {
                                                DB['PROJECT'][p]['tasks'][i] = response['edit_task'];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        DB['TASK'][t]=response['edit_task'];
                        if(TM['CID']==response['edit_task']['id']){
                            gen_list();
                            TM['highlight_day']=false;
                            TM['highlight_element']=false;
                            view(response['edit_task']['id'],'task');
                        }
                    }
                }
            }
        }
        if(response['del_task']){
            if(response['del_task']!=false){
                for(t in DB['TASK']){
                    if(DB['TASK'][t]['id']==response['del_task']){
                        if(DB['TASK'][t]['idproject']!=0){
                            for(p in DB['PROJECT']){
                                if(DB['PROJECT'][p]['idproject']==DB['TASK'][t]['idproject']){
                                    for(i in DB['PROJECT'][p]['tasks']){
                                        if(DB['PROJECT'][p]['tasks'][i]['id']==DB['TASK'][t]['id']){
                                            DB['PROJECT'][p]['tasks'].splice(i,1);
                                        }
                                    }
                                }
                            }
                        }
                        DB['TASK'].splice(t,1);
                        if(TM['CID']==response['del_task']){
                            reset_comments();
                            get('view').innerHTML='';
                            TM['highlight_day']=false;
                            TM['highlight_element']=false;
                        }
                    }
                }
                gen_list();
            }
        }
        if(response['del_project']) {
            if (response['del_project'] != false) {
                document.getElementById('main').className='blur';
                get('view').innerHTML='';
                TM['tmp_rebuild_lists']=true;
                io({'action':'load_db'});
            }
        }
        if(response['edit_project']) {
            if (response['edit_project'] != false) {
                document.getElementById('main').className='blur';
                //TM['tmp_rebuild_lists']=true
                TM['tmp_view_upd']=true;
                io({'action':'load_db'});
            }
        }
        if(response['add_group']) {
            if (response['add_group'] != false) {
                DB['GROUP'][DB['GROUP'].length]=response['add_group'];
                TM['tmp_group_add_line']=false;
                get('add_line').innerHTML='<div class="plus"><div id="p1"></div><div id="p2"></div><div id="p3"></div><div id="p4"></div></div>Добавить группу</div>';
                gen_list();
                view(response['add_group']['idgroup'],'group');
            }
        }
        if(response['del_user']) {
            if (response['del_user'] != false) {
                for (g in DB['GROUP']) {
                    if (DB['GROUP'][g]['idgroup'] == TM['view_id']) {
                        group = DB['GROUP'][g];
                        break;
                    }
                }
                for(u in group['users']){
                    if(group['users'][u]['id']==response['del_user']){
                        DB['GROUP'][g]['users'].splice(u,1);
                    }
                }
                if (TM['view_type'] == 'group') {
                    view(TM['view_id'],'group');
                }
            }
        }
        if(response['gen_invite_group']){
            if(response['gen_invite_group']!=false){
                get('invite_text').innerHTML = '<input type="text" value="http://'+location.host+'/?hash='+response['gen_invite_group']+'" size="60" id="name"/>';
            }
        }
    }else{
        if(TM['UID']){
            DB=false;
            clearInterval(TM['AUID']);
            page('auth');
            TM=false;
            TASK=false;
            task=false;
            DAY=false;
            TM=[];
            TM['UID']=false;
            TM['USER_NAME']=false;
            TM['USER_PIC']=false;

            TM['current_page']='auth';
            TM['tasks_mode']='all';
            TM['projects_mode']='all';
            TM['update_db']=false;
            TM['apic_loaded']=false;
            TM['time_offset']=3600000*5;
            TM['months']=["января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"];
            TM['now'] = new Date(new Date().getTime()+TM['time_offset']).getTime();
            //document.getElementById('main').className='noblur';
        }
    }
}

function show_add_task() {
    source = '<div class="task_add"><div class="title">' +
    '<h4>Создание новой задачи</h4></div><p>' +
    '<label for="name">Постановка задачи</label><input type="text" name="task_title" id="name"></p>' +
    '<p><label for="description">Описание</label>' +
    '<textarea type="text" name="task_description" id="description"></textarea></p>' +
    '<p><label for="date_finish">Дата завершения:</label>' +
    '<input onfocus="this.select();lcs(this);position_calen();" onclick="event.cancelBubble=true;this.select();lcs(this);position_calen()" style="width: 5em;" type="text" name="date_final" id="date_finish">' +
    ' Часы: <input type="number" min="0" max="23" value="12" style="width: 3em;" id="hours">' +
    ' Минуты: <input type="number" min="0" max="59" value="00" style="width: 3em;" id="minuts">' +
    '<span id="minical"></span></p>' +
    '<p><label for="project_id">Проект</label>' +
    '<input type="text" class="livesearch_prj" name="project_id" id="idproject" placeholder="Если Ваша задача должна быть включена в проект, укажите его">' +
    '<div class="search_advice_wrapper" id="search_advice_wrapper_prj"></div></p>' +
    '<p><label for="executor">Ответственный</label>' +
    '<input type="text" class="livesearch_exe" placeholder="Начните набирать имя пользователя" name="main_user" value="" autocomplete="off" id="executor">' +
    '<div class="search_advice_wrapper" id="search_advice_wrapper_exe"></div></p>' +
    //'<p><label for="not_main_user">Соисполнители</label><input type="text" name="not_main_user" id="viser"></p>' +
    //'<p>Иван иванов, Иван иванов,Иван иванов</p><p>Прикрепить</p>' +
    '<p><div class="create" onclick="send_task();">Создать</div>' +
    //' * Отображаются, на данный момент,только те поля, которые, функционально, имеют возможность обрабатываться системой!' +
    '<a href="javascript:void(0)">Прикрепить</a></p></div>';
    document.getElementById('view').innerHTML = source;
    loadSearch('#executor','#search_advice_wrapper_exe','get_users');
    loadSearch('#idproject','#search_advice_wrapper_prj','get_projects');
    calendar_init();
    return false;
}

function edit_task(id){
    task=false;
    for(t in DB['TASK']){ // Поиск запрошенной задачи в БД
        if(DB['TASK'][t]['id']==id){
            task=DB['TASK'][t];
            break;
        }
    }
    task['date']=task['date_finish'].split(' ');
    task['time']=task['date'][1].split(':');
    task['hour']=task['time'][0];
    task['minuts']=task['time'][1];
    task['date']=task['date'][0].split('-');
    task['date']=task['date'][2]+'.'+task['date'][1]+'.'+task['date'][0];
    source = '<div class="task_add"><div class="title">' +
    '<h4>Редактирование задачи</h4></div><p>' +
    '<label for="name">Постановка задачи</label><input type="text" name="task_title" value="'+task['name']+'" id="name"></p>' +
    '<p><label for="description">Описание</label>' +
    '<textarea type="text" name="task_description" id="description">'+task['description']+'</textarea></p>' +
    '<p><label for="date_finish">Дата завершения:</label>' +
    '<input value="'+task['date']+'" onfocus="this.select();lcs(this);position_calen();" onclick="event.cancelBubble=true;this.select();lcs(this);position_calen()" style="width: 5em;" type="text" name="date_final" id="date_finish">' +
    ' Часы: <input type="number" min="0" max="23" value="'+task['hour']+'" style="width: 3em;" id="hours">' +
    ' Минуты: <input type="number" min="0" max="59" value="'+task['minuts']+'" style="width: 3em;" id="minuts">' +
    '<span id="minical"></span></p>' +
    '<p><label for="project_id">Проект</label>' +
    '<input type="text" class="livesearch_prj" value="'+(task['projectname']||'')+'" name="project_id" id="idproject" placeholder="Если Ваша задача должна быть включена в проект, укажите его">' +
    '<div class="search_advice_wrapper" id="search_advice_wrapper_prj"></div></p>' +
    '<p><label for="executor">Ответственный</label>' +
    '<input type="text" class="livesearch_exe" value="'+task['executor_name']+'" placeholder="Начните набирать имя пользователя" name="main_user" autocomplete="off" id="executor">' +
    '<div class="search_advice_wrapper" id="search_advice_wrapper_exe"></div></p>' +
        //'<p><label for="not_main_user">Соисполнители</label><input type="text" name="not_main_user" id="viser"></p>' +
        //'<p>Иван иванов, Иван иванов,Иван иванов</p><p>Прикрепить</p>' +
    '<p><div class="create" onclick="send_edit_task();">Изменить</div>' +
        //' * Отображаются, на данный момент,только те поля, которые, функционально, имеют возможность обрабатываться системой!' +
    //'<a href="javascript:void(0)">Прикрепить</a>' +
    '</p></div>';
    document.getElementById('view').innerHTML = source;
    TM['tmp_edit_id']=task['id'];
    TM['tmp_edit_idproject']=task['idproject']||'0';
    TM['tmp_edit_executor']=task['executor'];
    loadSearch('#executor','#search_advice_wrapper_exe','get_users');
    loadSearch('#idproject','#search_advice_wrapper_prj','get_projects');
    calendar_init();
    return false;
}

function edit_project(id) {
    project=false;
    for(p in DB['PROJECT']){ // Поиск запрошенной задачи в БД
        if(DB['PROJECT'][p]['idproject']==id){
            project=DB['PROJECT'][p];
            break;
        }
    }
    if(!project){alert('BAD ID: '+id)};
    project['date']=project['date_finish'].split(' ');
    project['time']=project['date'][1].split(':');
    project['hour']=project['time'][0];
    project['minuts']=project['time'][1];
    project['date']=project['date'][0].split('-');
    project['date']=project['date'][2]+'.'+project['date'][1]+'.'+project['date'][0];
    source = '<div class="task_add"><div class="title">' +
    '<h4>Редактирование проекта</h4></div><p>' +
    '<label for="name">Название</label><input type="text" name="task_title" value="'+project['nameproject']+'" id="name"></p>' +
    '<p><label for="description">Описание</label>' +
    '<textarea type="text" name="task_description" id="description">'+project['description']+'</textarea></p>' +
    '<p><label for="date_finish">Дата завершения:</label>' +
    '<input value="'+project['date']+'" onfocus="this.select();lcs(this);position_calen();" onclick="event.cancelBubble=true;this.select();lcs(this);position_calen()" style="width: 5em;" type="text" name="date_final" id="date_finish">' +
    ' Часы: <input type="number" min="0" max="23" value="'+project['hour']+'" style="width: 3em;" id="hours">' +
    ' Минуты: <input type="number" min="0" max="59" value="'+project['minuts']+'" style="width: 3em;" id="minuts">' +
    '<span id="minical"></span></p>' +
    //'<p><label for="project_id">Проект</label>' +
    //'<input type="text" class="livesearch_prj" value="'+(project['projectname']||'')+'" name="project_id" id="idproject" placeholder="Если Ваша задача должна быть включена в проект, укажите его">' +
    //'<div class="search_advice_wrapper" id="search_advice_wrapper_prj"></div></p>' +
    //'<p><label for="executor">Ответственный</label>' +
    //'<input type="text" class="livesearch_exe" value="'+project['executor_name']+'" placeholder="Начните набирать имя пользователя" name="main_user" autocomplete="off" id="executor">' +
    //'<div class="search_advice_wrapper" id="search_advice_wrapper_exe"></div></p>' +
        //'<p><label for="not_main_user">Соисполнители</label><input type="text" name="not_main_user" id="viser"></p>' +
        //'<p>Иван иванов, Иван иванов,Иван иванов</p><p>Прикрепить</p>' +
    '<p><div class="create" onclick="send_edit_proj();">Изменить</div>' +
        //' * Отображаются, на данный момент,только те поля, которые, функционально, имеют возможность обрабатываться системой!' +
        //'<a href="javascript:void(0)">Прикрепить</a>' +
    '</p></div>';
    document.getElementById('view').innerHTML = source;
    TM['tmp_edit_id']=project['idproject'];
    //TM['tmp_edit_idproject']=project['idproject']||'0';
    //TM['tmp_edit_executor']=project['executor'];
    //loadSearch('#executor','#search_advice_wrapper_exe','get_users');
    //loadSearch('#idproject','#search_advice_wrapper_prj','get_projects');
    calendar_init();
    return false;
}

function send_task(){

    name=document.getElementById('name').value;
    description=document.getElementById('description').value;
    executor=LSEARCH['get_users']['selected_id'];//selected_id;
    project=LSEARCH['get_projects']['selected_id'];
    hours=document.getElementById('hours').value;
    minuts=document.getElementById('minuts').value;
    date_finish=document.getElementById('date_finish').value;
    date_finish=date_finish.split('.');
    date_finish=date_finish[2]+'-'+date_finish[1]+'-'+date_finish[0]+' '+(hours<10?'0':'')+hours+':'+(minuts<10?'0':'')+minuts;

    if(executor!=false) {
        io({
            "action": "add_task",
            "name": name,
            "description": description,
            "executor": executor,
            "date_finish": date_finish,
            "project":project
        });
    }
}

function send_edit_task(){

    id=TM['tmp_edit_id'];
    name=document.getElementById('name').value;
    description=document.getElementById('description').value;
    executor=LSEARCH['get_users']['selected_id']||TM['tmp_edit_executor'];//selected_id;
    project=get('idproject').value==''?'0':(LSEARCH['get_projects']['selected_id']||TM['tmp_edit_idproject']);
    hours=document.getElementById('hours').value;
    minuts=document.getElementById('minuts').value;
    date_finish=document.getElementById('date_finish').value;
    date_finish=date_finish.split('.');
    date_finish=date_finish[2]+'-'+date_finish[1]+'-'+date_finish[0]+' '+(hours<10?'0':'')+hours+':'+(minuts<10?'0':'')+minuts;

    TM['tmp_edit_executor']=false;
    TM['tmp_edit_idproject']=false;

    if(executor!=false) {
        io({
            "action": "edit_task",
            "id":id,
            "name": name,
            "description": description,
            "executor": executor,
            "date_finish": date_finish,
            "project":project
        });
    }
}

function send_edit_proj(){

    id=TM['tmp_edit_id'];
    name=document.getElementById('name').value;
    description=document.getElementById('description').value;
    //executor=LSEARCH['get_users']['selected_id']||TM['tmp_edit_executor'];//selected_id;
    //project=get('idproject').value==''?'0':(LSEARCH['get_projects']['selected_id']||TM['tmp_edit_idproject']);
    hours=document.getElementById('hours').value;
    minuts=document.getElementById('minuts').value;
    date_finish=document.getElementById('date_finish').value;
    date_finish=date_finish.split('.');
    date_finish=date_finish[2]+'-'+date_finish[1]+'-'+date_finish[0]+' '+(hours<10?'0':'')+hours+':'+(minuts<10?'0':'')+minuts;

    //TM['tmp_edit_executor']=false;
    //TM['tmp_edit_idproject']=false;

    //if(executor!=false) {
        io({
            "action": "edit_project",
            "id":id,
            "name": name,
            "description": description,
            //"executor": executor,
            "date_finish": date_finish
            //"project":project
        });
    //}
}

function task_end(id){
    task=false;
    for(t in DB['TASK']){ // Поиск запрошенной задачи в БД
        if(DB['TASK'][t]['id']==id){
            task=DB['TASK'][t];
            break;
        }
    }
    value=task['finished']==1?0:1;
    io({"action":"set_task","param":"finished","value":value,"id":id});
}

function task_del(id){
    /*task=false;
    for(t in DB['TASK']){ // Поиск запрошенной задачи в БД
        if(DB['TASK'][t]['id']==id){
            task=DB['TASK'][t];
            break;
        }
    }*/
    io({"action":"del_task","id":id});
}

function proj_del(id){
    /*project=false;
    for(t in DB['PROJECT']){ // Поиск запрошенной задачи в БД
        if(DB['PROJECT'][t]['id']==id){
            project=DB['PROJECT'][t];
            break;
        }
    }*/
    io({"action":"del_project","id":id});
}

function show_add_project() {
    source = '<div class="task_add"><div class="title"><h4>Создание нового проекта</h4></div><p>' +
    '<label for="name">Название</label><input type="text" name="task_title" id="name"></p>' +
    '<p><label for="description">Описание</label>' +
    '<textarea type="text" name="task_description" id="description"></textarea></p>' +
    '<p><label for="date_finish">Дата завершения:</label>' +
    '<input onfocus="this.select();lcs(this);position_calen();" onclick="event.cancelBubble=true;this.select();lcs(this);position_calen()" style="width: 5em;" type="text" name="date_final" id="date_finish">' +
    ' Часы: <input type="number" min="0" value="12" max="23" style="width: 3em;" id="hours">' +
    ' Минуты: <input type="number" min="0" value="00" max="59" style="width: 3em;" id="minuts">' +
    '<span id="minical"></span></p>' +
        //'<p><label for="project_id">Проект</label>' +
        //'<input type="text" name="project_id" id="idproject" placeholder="Если Ваша задача должна быть включена в проект, укажите его"></p>' +
    '<p><label for="executor">Участники</label>' +
    '<input size="33" type="text" class="livesearch" placeholder="Начните набирать имя пользователя" name="main_user" value="" autocomplete="off" id="executor">' +
    '<div class="search_advice_wrapper" id="prj_wrapp"></div></p>' +
        //'<p><label for="not_main_user">Соисполнители</label><input type="text" name="not_main_user" id="viser"></p>' +
        //'<p>Иван иванов, Иван иванов,Иван иванов</p><p>Прикрепить</p>' +
    '<p><div class="create" onclick="new_project();">Создать</div><a href="javascript:void(0)">Прикрепить</a></div></p>';
    //' * Отображаются, на данный момент,только те поля, которые, функционально, имеют возможность обрабатываться системой!</p>';
    document.getElementById('view').innerHTML = source;
    loadSearch('#executor','#prj_wrapp','get_users');
    calendar_init();
    return false;
}

function new_project() {
    name = document.getElementById('name').value;
    description = document.getElementById('description').value;
    executor = LSEARCH['get_users']['selected_id'];
    hours = document.getElementById('hours').value;
    minuts = document.getElementById('minuts').value;
    date_finish = document.getElementById('date_finish').value;
    date_finish = date_finish.split('.');
    date_finish = date_finish[2] + '-' + date_finish[1] + '-' + date_finish[0] + ' ' + (hours < 10 ? '0' : '') + hours + ':' + (minuts < 10 ? '0' : '') + minuts;
    io({
        "action": "add_project",
        "name": name,
        "description": description,
        "date_finish": date_finish,
        "executor": executor
    });

}

function new_group(){
    name=document.getElementById('name').value;
    io({
        "action": "add_group",
        "name": name
    });
}

function gen_list(){
    MONTH=["января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"];
    date=new Date();
    now = new Date(date.getFullYear(), date.getMonth(), date.getDate()).getTime();
    //day=(date.getDate()<10?'0':'')+date.getDate();month=((date.getMonth()+1)<10?'0':'')+(date.getMonth()+1);
    //today=day+'.'+month+'.'+date.getFullYear();
    // BUILD NEXT DAY
    tomorrow=new Date(date.getFullYear(), date.getMonth(), date.getDate()+1).getTime();
    if(TM['current_page']=='tasks'){
        if(!DB['TASK']){
            TM['update_db']=true;
        }else{
            source='';
            empty_list=true;
            //highlightd=false;
            highlight=false;
            TASK=[];
            DAY=[];
            for(i in DB['TASK']){
                empty=false;
                empty_list=false;
                task=DB['TASK'][i];
                if(TM['tasks_mode']=='unfinished'&&task['finished']==1){
                    continue;
                }
                if(TM['tasks_mode']=='my'&&task['executor']!=TM['UID']){
                    continue;
                }
                date_finish=task['date_finish'].split(' ');
                date_finish=date_finish[0].split('-');
                //key=date_finish[2]+'.'+date_finish[1]+'.'+date_finish[0];
                key=false;
                time=new Date(date_finish[0], date_finish[1]-1, date_finish[2]).getTime();
                for(t in DAY){if(DAY[t]==time){key=t}};
                if(!key){key=DAY.length};
                DAY[key]=time;
                //alert(date_finish[0]+'.'+(date_finish[1]-1)+'.'+date_finish[2]);
                if(!TASK[time]){
                    TASK[time]=[];
                }
                num=TASK[time].length;
                TASK[time][num]='<div id="'+task['id']+'" class="task_info';
                if(TM['highlight_element']==task['id']){
                    TASK[time][num]+=' task_active';highlight=true;}
                TASK[time][num]+='" onclick="view('+task['id']+',\'task\')"><div>';
                if(task['finished']==1){
                    TASK[time][num]+='<div id="fhd'+task['id']+'" class="galka" onclick="task_end('+task['id']+')"><img src="templates/default/images/done.png"></div>';
                }else{
                    TASK[time][num]+='<div id="fhd'+task['id']+'" class="galka" onclick="task_end('+task['id']+')"><img src="templates/default/images/n_done.png"></div>';
                }
                TASK[time][num]+='</div><div class="task_text">'+task['name']+'</div></div>';
                // BUILD SORT BY TIME
            }
            DAY.sort();
            overdue='<div class="task_day'+(highlight&&TM['highlight_day']=='overdue'?' active_day':'')+'" id="overdue"><div class="task_name">Просрочено</div>';
            overdue_view=false;
            for(d in DAY) {
                over=false;
                //alert(DAY[d]+' '+now);
                if(DAY[d]<now){
                    over=true;
                    overdue_view=true;
                }else {
                    if (DAY[d] == now) {
                        source += '<div class="task_day'+(highlight&&TM['highlight_day']==DAY[d]?' active_day':'')+'" id="'+DAY[d]+'"><div class="task_name">Сегодня</div>';
                    } else if (DAY[d] == tomorrow) {
                        source += '<div class="task_day'+(highlight&&TM['highlight_day']==DAY[d]?' active_day':'')+'" id="'+DAY[d]+'"><div class="task_name">Завтра</div>';
                    } else {
                        date = new Date(DAY[d]);
                        day = (date.getDate() < 10 ? '0' : '') + date.getDate();
                        month = MONTH[date.getMonth()];//((date.getMonth() + 1) < 10 ? '0' : '') + (date.getMonth() + 1);
                        source += '<div class="task_day'+(highlight&&TM['highlight_day']==DAY[d]?' active_day':'')+'" id="'+DAY[d]+'"><div class="task_name">' + day + ' ' + month + ' ' + date.getFullYear() + '</div>';
                    }
                }
                for (t in TASK[DAY[d]]) {
                    if(over) {
                        overdue += TASK[DAY[d]][t];
                    }else{
                        source += TASK[DAY[d]][t];
                    }
                }
                if(!over) {
                    source += '</div>';
                }
            }
            if(overdue_view){
                source=overdue+'</div>'+source;
            }
            if(highlight==false){
                TM['highlight_day']=false;
                TM['highlight_element']=false;
            }
            document.getElementById('tasks').innerHTML=empty_list?'Нет задач':source; // BUILD WRITE
        }
    }
    if(TM['current_page']=='projects'){
        if(!DB['PROJECT']){
            TM['update_db']=true;
        }else{
            source='';
            for(i in DB['PROJECT']){
                project=DB['PROJECT'][i];
                source+='<div class="task_day" id="prj'+project['idproject']+'"><div style="cursor: pointer;" onclick="view('+project['idproject']+',\'project\')" class="task_name">'+project['nameproject']+'</div>';
                for(j in project['tasks']){
                    task=project['tasks'][j];
                    source+='<div id="'+task['id']+'" class="task_info" onclick="view('+task['id']+',\'task\')"><div>';
                    if(task['finished']==1){
                        source+='<div id="fhd'+task['id']+'" class="galka" onclick="task_end('+task['id']+')"><img src="templates/default/images/done.png"></div>';
                    }else{
                        source+='<div id="fhd'+task['id']+'" class="galka" onclick="task_end('+task['id']+')"><img src="templates/default/images/n_done.png"></div>';
                    }
                    source+='</div><div class="task_text">'+task['name']+'</div></div>';
                }
                source+='</div>';
            }
            document.getElementById('projects').innerHTML=source;
        }
    }
    if(TM['current_page']=='groups'){
        //alert('load');
        source='';
        for(g in DB['GROUP']){
            group=DB['GROUP'][g];
            source+='<div class="group"><div onclick="view('+group['idgroup']+',\'group\')" class="group_name">'+group['namegroup']+'</div>' +
            '<div class="group_count">Участники ('+group['count_users']+')</div><div class="pod_group">';
            for(i in group['subgroup']){
                source+='<div class="group"><div onclick="view('+group['subgroup'][i]['idgroup']+',\'group\')" class="group_name">'+group['subgroup'][i]['namegroup']+'</div>' +
                '<div class="group_count">Участники ('+group['subgroup'][i]['count_users']+')</div><div class="pod_group"></div></div>';
            }
            source+='</div></div>'
        }
        document.getElementById('groups').innerHTML=source;
    }
}

function groups_query(){
    source='<div class="title"><h4>Запросы</h4></div><div class="item_title">Приглашения в группу (0)</div>';

    source+='<br><div class="item_title">История</div>';

    get('view').innerHTML=source;
}

function show_add_group(){
    //source = '<div class="task_add"><div class="title"><h4>Организация новой группы</h4></div><p>' +
    //'<label for="name">Название</label><input type="text" name="task_title" id="name"></p>' +
    //'<p><div class="create" onclick="new_group();">Создать</div></div></p>';
    //document.getElementById('view').innerHTML = source;
    if(!TM['tmp_group_add_line']) {
        get('add_line').innerHTML = '<input type="text" id="name"/><div class="create" onclick="new_group();">Создать</div>';
        TM['tmp_group_add_line']=true;
    }
    //
    return false;
}

function invite_user_group(id){
io({"action":"gen_invite_group","id":id});
}

function del_user_group(user,group){
    io({"action":"del_user","id":user,"group":group});
}

function view(id,type){
    source='';
    if(type=='task'){
        task=false;
        for(t in DB['TASK']){ // Поиск запрошенной задачи в БД
            if(DB['TASK'][t]['id']==id){
                task=DB['TASK'][t];
                break;
            }
        }
        TM['view_type']=type;
        TM['view_id']=id;
        date=new Date();
        now = new Date(date.getFullYear(), date.getMonth(), date.getDate()).getTime();
        if(TM['highlight_day']){document.getElementById(TM['highlight_day']).className='task_day';TM['highlight_day']=false;}
        if(TM['highlight_element']){document.getElementById(TM['highlight_element']).className='task_info';TM['highlight_element']=false;}
        date_finish=task['date_finish'].split(' ');
        date_finish=date_finish[0].split('-');
        time=new Date(date_finish[0], date_finish[1]-1, date_finish[2]).getTime();
        time=time<now?'overdue':time;
        TM['highlight_day']=TM['current_page']=='tasks'?time:'prj'+task['idproject'];
        TM['highlight_element']=task['id'];
        document.getElementById(TM['highlight_day']).className='task_day active_day';
        document.getElementById(TM['highlight_element']).className='task_info task_active';
        source+='<div class="task_title"><h4>'+task['name']
        if(task['initiator']==TM['UID']) {
            source += '<img onclick="task_del(' + task['id'] + ');" src="templates/default/images/trash.png" id="trash"><img onclick="edit_task(' + task['id'] + ');" src="templates/default/images/b_pan_hover.png" id="edit_pen">';
        }
        source+='</h4></div>';
        source+='<p class="task_description">'+task['description']+'</p>';
        source+='<div class="info_task">';
        if(task['idproject']!=0){
            source+='<div class="task_table"><div>Проект</div>' +
            '<a href="javascript:void(0)" onclick=\'TM["tmp_task_id"]='+task['id']+';view('+task['idproject']+',"project")\'>'+task['projectname']+'</a></div>';
        }
        source+='<div class="task_table"><div>Инициатор</div>' +
        '<a href="javascript:void(0)" onclick=\'view('+task['initiator']+',"user")\'>'+task['initiator_name']+'</a></div>';
        source+='<div class="task_table"><div>Исполнитель</div>' +
        '<a href="javascript:void(0)" onclick=\'view('+task['executor']+',"user")\'>'+task['executor_name']+'</a>' +
        '</div>';
        source+='<div class="files"><div>Прикрепленные файлы</div>' +
        'IS DEVELOPING...'+//'<a href="#user2">Doc1.doc</a>, <a href="#user2">Doc1.doc</a>, <a href="#user2">Doc1.doc</a>' +
        '</div>';
        source+='<h4 class="comments_title">Обсуждение</h4><div style="height: 0px" class="comments" id="comments">'+PART['loader']+
        '</div><textarea id="new_comm" placeholder="Ваш комментарий..."></textarea><p>' +
        '<div class="create" onclick="add_comment()">Отправить</div><a href="javascript:void(0)">Прикрепить</a></p></div>';
        document.getElementById('view').innerHTML=source;
        document.getElementById('spinner').style.position='relative';
        document.getElementById('overlay').style.marginTop='0px';
        init_comments(task['id'],type);
        sizing();
    }
    if(type=='project'){
        var project=false;
        ID=id;
        for(p in DB['PROJECT']){ // Поиск запрошенного проекта в БД
            if(DB['PROJECT'][p]['idproject']==id){
                project=DB['PROJECT'][p];
                id=p;
                break;
            }
        }
        TM['view_type']=type;
        TM['view_id']=ID;
        //alert(DB['PROJECT'][id]['percent']);
        //if(!DB['PROJECT'][id]['percent']){
        DB['PROJECT'][id]['time_finish'] = new Date(new Date(project['date_finish'].replace(' ', 'T')).getTime()+TM['time_offset']).getTime();
        DB['PROJECT'][id]['time_start'] = new Date(new Date(project['date_start'].replace(' ', 'T')).getTime()+TM['time_offset']).getTime();
        now = TM['now'] - DB['PROJECT'][id]['time_start'];
        difference = (DB['PROJECT'][id]['time_finish'] - DB['PROJECT'][id]['time_start']) / 100;
        DB['PROJECT'][id]['percent'] = now / difference;
        DB['PROJECT'][id]['percent_view']=DB['PROJECT'][id]['percent'];
        DB['PROJECT'][id]['percent_view']=DB['PROJECT'][id]['percent_view'].toString();
        DB['PROJECT'][id]['percent_view'] = DB['PROJECT'][id]['percent_view'].split('.');
        DB['PROJECT'][id]['percent_view'] = DB['PROJECT'][id]['percent_view'][0];
        if(DB['PROJECT'][id]['percent']<0){DB['PROJECT'][id]['percent']=100;DB['PROJECT'][id]['percent_view']='Завершено'}else
        if(DB['PROJECT'][id]['percent'] >= 100){DB['PROJECT'][id]['percent'] = 100;DB['PROJECT'][id]['percent_view'] = 'Завершено';}else{DB['PROJECT'][id]['percent_view']+='%';}//}
        if(TM['highlight_day']){document.getElementById(TM['highlight_day']).className='task_day';TM['highlight_day']=false;}
        if(TM['highlight_element']){document.getElementById(TM['highlight_element']).className='task_info';TM['highlight_element']=false;}
        TM['highlight_day']=TM['current_page']=='projects'?'prj'+project['idproject']:false;
        TM['highlight_element']=TM['tmp_task_id']?TM['tmp_task_id']:false;
        TM['tmp_task_id']=false;
        if(TM['highlight_day']!=false){document.getElementById(TM['highlight_day']).className='task_day active_day';}
        if(TM['highlight_element']!=false){document.getElementById(TM['highlight_element']).className='task_info task_active';}
        date = new Date(new Date(new Date(project['date_start'].replace(' ', 'T')).getTime()+TM['time_offset']).getTime());
        day = (date.getDate() < 10 ? '0' : '') + date.getDate();month = TM['months'][date.getMonth()];
        date_start='с '+day+' '+month+' '+date.getFullYear();
        date = new Date(new Date(new Date(project['date_finish'].replace(' ', 'T')).getTime()+TM['time_offset']).getTime());
        day = (date.getDate() < 10 ? '0' : '') + date.getDate();month = TM['months'][date.getMonth()];
        date_finish='по '+day+' '+month+' '+date.getFullYear();
        source+='<div class="project_title"><h4>'+project['nameproject'];
        if(project['initiator']==TM['UID']) {
            source += '<img onclick="proj_del(' + project['idproject'] + ');" src="templates/default/images/trash.png" id="trash">' +
            '<img onclick="edit_project(' + project['idproject'] + ');" src="templates/default/images/b_pan_hover.png" id="edit_pen">';
        }
        source+='</h4></div><p class="project_description">'+project['description']+'</p><div class="project_time">';
        source+='<div class="date_start">'+date_start+'</div><div class="date_end">'+date_finish+
        '</div><div class="project_time_all">';
        source+='<div class="countpercent">'+project['percent_view']+'</div><div class="project_rime_cur" style="width: '+project['percent']+'%"></div></div></div>';
        source+='<div class="task_table"><div>Инициатор</div><a href="javascript:void(0)" onclick="view('+project['initiator']+',\'user\');">'+project['initiator_name']+'</a></div>';
        source+='<div class="task_table"><div>Участники</div>';
        // BUILD EXECUTORS
        user=false;
        for(u in project['users']){
            user=project['users'][u];
            if(u!=0){
                source+=', ';
            }
            source+='<a href="javascript:void(0)" onclick="view('+user['id']+',\'user\');">'+user['lastname']+' '+user['firstname']+'</a>';
        }
        if(!user){
            source+='не назначены';
        }
        source+='</div>';
        if(project['files']) {
            source += '<div class="files"><div>Прикрепленные файлы</div>';
            // BUILD FILES
            source += '</div>';
        }
        source+='<h4 class="comments_title">Обсуждение</h4><div style="height: 0px" class="comments" id="comments">'+PART['loader']+
        '</div><textarea id="new_comm" placeholder="Ваш комментарий..."></textarea><p>' +
        '<div class="create" onclick="add_comment()">Отправить</div><a href="javascript:void(0)">Прикрепить</a></p></div>';
        document.getElementById('view').innerHTML=source;
        document.getElementById('spinner').style.position='relative';
        document.getElementById('overlay').style.marginTop='0px';
        init_comments(project['idproject'],type);
        sizing();
    }
    if(type=='group'){
        source='';
        for(g in DB['GROUP']){
            if(DB['GROUP'][g]['idgroup']==id){
                group=DB['GROUP'][g];
                break;
            }else{
                for(gr in DB['GROUP'][g]['subgroup']){
                    if(DB['GROUP'][g]['subgroup'][gr]['idgroup']==id){
                        group=DB['GROUP'][g]['subgroup'][gr];
                        break;
                    }
                }
            }
        }
        TM['view_type']=type;
        TM['view_id']=id;
        source+='<div class="title"><h4>'+group['namegroup']+'</h4></div>';
        if(group['creator']==TM['UID']) {
            source += '<div id="invite" onclick="invite_user_group('+group['idgroup']+');" class="add_group"><div class="plus"><div id="p1"></div><div id="p2"></div><div id="p3"></div><div id="p4"></div></div></div>' +
            '<div id="invite_text"></div>';
        }
        source+='<div class="item_title">Участники ('+group['count_users']+')</div>' +
        '<div class="group_table"><div class="group_tr"><div class="names"><div class="account">Аккаунт</div>' +
        '<div class="job_pos">Должность</div><div class="scroll"><div>Добавление/удаление пользователей</div>' +
        '<div>Запуск/удаление задач</div></div><div class="status">Статус</div></div></div>';
        for(u in group['users']){
            user=group['users'][u];
            source+='<div class="group_tr"><div><div class="account">' +
            '<a href="javascript:void(0)" onclick="page('+user['id']+',\'user\')">' +
            '<img src="'+user['photo']+'">'+user['lastname']+' '+user['firstname']+'</a></div>';
            job=user['lvl']==5?'Администратор':(user['lvl']==3?'Модератор':'Пользователь');
            source+='<div class="job_pos">'+job+'</div><div class="scroll">';
            source+='<div><img src="templates/default/images/'+(user['lvl']>4?'done.png':'n_done.png')+'"></div>';
            source+='<div><img src="templates/default/images/'+(user['lvl']>2?'done.png':'n_done.png')+'"></div>';
            source+='</div><div class="status">Участвует</div>';
            if(group['creator']==TM['UID']){
                source+='<div class="crest" onclick="del_user_group('+user['id']+','+group['idgroup']+');"><img src="templates/default/images/close.png"></div>';
            }//else{alert(group['creator']+' '+TM['UID']);}
            source+='</div></div>';
        }
        source+='</div><div class="item_title">Подгруппы ('+(group['subgroup'].length)+')</div>';
        get('view').innerHTML=source;
    }
}