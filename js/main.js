var TM=[];
TM['current_page']=false;
TM['tasks_mode']='all';
TM['projects_mode']='all';
TM['update_db']=false;
TM['apic_loaded']=false;
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
}

function sizing() {
    if (document.getElementById('view')) {
        document.getElementById('view').style.height = window.innerHeight - document.getElementById('view').offsetTop + 'px';
    }
    if (TM['current_page'] == 'tasks') {
        document.getElementById('tasks').style.height = window.innerHeight - document.getElementById('tasks').offsetTop + 'px';
    } else if (TM['current_page'] == 'projects') {
        document.getElementById('projects').style.height = window.innerHeight - document.getElementById('projects').offsetTop + 'px';
    }
    if (document.getElementById('comments')) {
        // CORRECT HEIGHT
        document.getElementById('comments').style.height = window.innerHeight - document.getElementById('comments').offsetTop - document.getElementById('new_comm').scrollHeight-57 + "px"
    }
    if(TM['current_page']=='auth'){
        document.getElementById('wrapper').style.height = window.innerHeight-240+'px'
    }
}

function init() {
    if (TM['UID']) {
        if(TM['update_db']==true){
            TM['wait_load']=true;
            document.getElementById('main').className="blur";
            io({'action':'load_db'});
        }else{
            page(TM['current_page']!=false?TM['current_page']:'tasks',true);
        }

        //else{
        //    gen_list();
        //}

        //sizing();

        // AutoUpdate


        //init_cal();

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