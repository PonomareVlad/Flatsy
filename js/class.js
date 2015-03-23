function Ajax(method,url,callback){
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            response=xmlhttp.responseText;
            //eval(callback+'(\''+response+'\');');
            callback(response);
        }
    }
    xmlhttp.open(method,url,true);
    xmlhttp.send();
}

function io(array,callback){
    query=JSON.stringify(array);
    Ajax('GET','/ajax.php?query='+query+'&rand='+new Date().getTime(),callback?callback:handler);
}

function logout(){
    io({"action":"logout"});
}

function set_vmode(mode){
    TM[TM['current_page'] + '_mode']=mode;
    document.getElementById('currentv').innerHTML=document.getElementById(TM[TM['current_page'] + '_mode']).innerHTML;
    gen_list();
}

function page(name,headgen){
    if(TM['current_page']!=name||headgen) {
        if (name == 'auth') {
            document.getElementById('header').innerHTML = '';
        } else {
            if (TM['current_page'] == 'auth' || headgen) {
                document.getElementById('header').innerHTML = PART['header'];
                document.getElementById('user_name').innerHTML = TM['USER_NAME'];
                document.getElementById('user_pic').src = TM['USER_PIC'];
            }
        }
        document.title = PAGE[name]['title'] + ' | EasyTM';
        document.getElementById('page').innerHTML = PAGE[name]['source'];
        TM['current_page'] = name;
        if (document.getElementById('currentv')) {
            document.getElementById('currentv').innerHTML = document.getElementById(TM[TM['current_page'] + '_mode']).innerHTML;
        }
    }
}

function sort(){

}