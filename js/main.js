var FLATSY='0.4.1';
var VERSION=41;
var TM=[];
TM['current_page']=false;
TM['tasks_mode']='all';
TM['projects_mode']='all';
TM['update_db']=false;
TM['apic_loaded']=false;
TM['time_offset']=3600000*5;
TM['param_send']=false;
TM['months']=["января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"];
TM['now'] = new Date(new Date().getTime()+TM['time_offset']).getTime();
TM['upl_window']=false;
if(typeof SERVER !='undefined') {
    TM['UID'] = SERVER['ID'];
    TM['USER_NAME'] = SERVER['NAME'];
    TM['USER_PIC'] = SERVER['PIC'];
    TM['current_page'] = SERVER['PAGE']?SERVER['PAGE']:false;
}
if(!DB){
    var DB=[];
    TM['update_db']=true;
}TM['update_db']=true;

function main(){
    io({"action":"check"});
    TM['now'] = new Date(new Date().getTime()+TM['time_offset']).getTime();
}

function sizing() {
    if (document.getElementById('view')) {
        document.getElementById('view').style.height = window.innerHeight - document.getElementById('view').offsetTop + 'px';
    }
    if (TM['current_page'] == 'tasks') {
        document.getElementById('tasks').style.height = window.innerHeight - document.getElementById('tasks').offsetTop-160 + 'px';
    } else if (TM['current_page'] == 'projects') {
        document.getElementById('projects').style.height = window.innerHeight - document.getElementById('projects').offsetTop-160 + 'px';
    } else if (TM['current_page'] == 'groups') {
        document.getElementById('groups').style.height = window.innerHeight - document.getElementById('groups').offsetTop-160 + 'px';
    }
    if (document.getElementById('comments')) {
        // CORRECT HEIGHT
        document.getElementById('comments').style.height = window.innerHeight - document.getElementById('comments').offsetTop - document.getElementById('new_comm').scrollHeight-57 + "px"
    }
    if(TM['current_page']=='auth'){
        document.getElementById('wrapper').style.height = window.innerHeight-240+'px'
    }
    if(TM['current_page']=='reg'){
        document.getElementById('wrapper').style.height = window.innerHeight-100+'px'
    }
}

function init(arg) {
    TM['GET_PARAM']=urlParams;
    if (TM['UID']) {
        TM['current_page']=(TM['current_page']=='auth'||TM['current_page']=='reg')?'tasks':TM['current_page'];
        TM['current_page']=PAGE[TM['current_page']]?TM['current_page']:'tasks';
        if(TM['GET_PARAM']['hash']){
            if(TM['param_send']==true){
                if(arg){
                    arg=JSON.parse(arg);
                    if(!arg['parse_hash']) {
                        alert('Не удалось зарегистрировать ключ');
                        //alert(arg);
                    }
                }
                TM['wait_load']=true;
                TM['GET_PARAM']['hash']=false;
                io({'action':'load_db'});
            }else {
                document.getElementById('main').className = "blur";
                if (TM['update_db'] == false) {
                    page(TM['current_page'] == false ? 'tasks' : TM['current_page'], true);
                }
                TM['param_send']=true;
                io({"action": "parse_hash", "hash": TM['GET_PARAM']['hash']}, init);
            }
            return;
        }
        if(TM['update_db']==true){
            TM['wait_load']=true;
            document.getElementById('main').className="blur";
            io({'action':'load_db'});
        }else{
            page(TM['current_page']==false?'tasks':TM['current_page'],true);
        }
    }else{
        page('auth',false);
    }
}

document.onkeyup = function (e) {
    e = e || window.event;
    //alert(e.keyCode);
    if(TM['current_page']=='auth') {
        if (e.keyCode === 13) {
            if (document.getElementById('pass').value != '') {
                auth_send();
            }
        }
    }
    if(TM['CID']){
        if(e.keyCode==10||(e.ctrlKey&&e.keyCode==13)){
            add_comment();
        }
    }
    return false;
}
