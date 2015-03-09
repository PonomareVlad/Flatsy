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
            eval(callback+'(\''+xmlhttp.responseText+'\');');
        }
    }
    xmlhttp.open(method,url,true);
    xmlhttp.send();
}

function handler(response) {
    response = JSON.parse(response);
    if (response['auth']==true) {
        auth=true;
        if (response['tasks']) {
            tasks = new Array();
            for (i in response['tasks']) {
                tasks[i] = new Array();
                for (j in response['tasks'][i]) {
                    tasks[i][j] = response['tasks'][i][j];
                }
            }
        }
    }else{
        if(auth==true){
            auth=false;
            window.location='/auth';
        }
    }
}

function check(mode){
    send={"check":mode||"all"}
    query=JSON.stringify(send);
    Ajax('GET','/ajax.php?query='+query+'&rand='+new Date().getTime(),'handler');
}

function logout(){
    send={"action":"logout"}
    query=JSON.stringify(send);
    Ajax('GET','/ajax.php?query='+query+'&rand='+new Date().getTime(),'handler');
}

function main(){
    check();
}

function init() {
    if (auth) {
        setInterval('main()', 5000);
    }
}

