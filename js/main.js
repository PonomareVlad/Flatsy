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
auth=false;

function handler(response){
    response=JSON.parse(response);
    if(response['auth']){
    if(auth!=true){
        auth=true;
        document.getElementById('auther').style='visibility : collapse;';
        document.getElementById('wrapper').className = 'noblur';
    }
        if(response['tasks']){
            tasks=new Array();
            for(i in response['tasks']){
                tasks[i]=new Array();
                for(j in response['tasks'][i]){
                    tasks[i][j]=response['tasks'][i][j];
                }
            }
        }
    }else {
        if (auth != false) {
            auth = false;
            document.getElementById('wrapper').className = 'blur';
        }
    }
}

function check(mode){
    send={"check":mode||"all"}
    query=JSON.stringify(send);
    Ajax('GET','/ajax.php?query='+query+'&rand='+new Date().getTime(),'handler');
}

function main(){


    check();

}

function init(){
    document.getElementById('username').innerHTML='Имя пользователя';
    setInterval('main()',1000);
}

