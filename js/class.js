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
            eval(callback+'(\''+response+'\');');
        }
    }
    xmlhttp.open(method,url,true);
    xmlhttp.send();
}

function io(array,callback){
    query=JSON.stringify(array);
    Ajax('GET','/ajax.php?query='+query+'&rand='+new Date().getTime(),callback||'handler');
}

function check(mode) {
    current_page = location.pathname;
    if (current_page == '/projects') {
        io({"action":"show_projects"});
    } else {
        send = {"check": mode || "simple"};
        io(send);
    }
}

function logout(){
    send={"action":"logout"}
    io(send);
}

