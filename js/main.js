var TM=[];
TM['current_page']=false;
TM['tasks_mode']='all';
TM['projects_mode']='all';
TM['update_db']=false;
if(SERVER) {
    TM['USER_ID'] = SERVER['ID'];
    TM['USER_NAME'] = SERVER['NAME'];
    TM['USER_PIC'] = SERVER['PIC'];
    TM['current_page'] = SERVER['PAGE']?SERVER['PAGE']:false;
}
if(!DB){
    var DB=[];
    TM['update_db']=true;
}

function main(){
    io({"action":"check"});
}

function sizing() {
    document.getElementById('view').style.height = window.innerHeight - document.getElementById('view').offsetTop + 'px';
    if (TM['current_page'] == 'tasks') {
        document.getElementById('tasks').style.height = window.innerHeight - document.getElementById('tasks').offsetTop + 'px';
    }else{
        document.getElementById('projects').style.height = window.innerHeight - document.getElementById('projects').offsetTop + 'px';
    }
    if (TM['comments_loaded']) {
        // CORRECT HEIGHT
        document.getElementById('comments').style.height = window.innerHeight - document.getElementById('comments').offsetTop - document.getElementById('new_comm').scrollHeight-57 + "px"
    }
}

function init() {
    if (TM['USER_ID']) {

        page(TM['current_page']!=false?TM['current_page']:'tasks',true);

        if(TM['update_db']==true){
            io({'action':'load_db'});
        }else{
            gen_list();
        }

        sizing();

        // AutoUpdate
        if(!TM['AUID']) {
            TM['AUID'] = setInterval('main()', 1000);
        }

        init_cal();

    }else{
        page('auth',false);
    }
}

//function postinit(response){}