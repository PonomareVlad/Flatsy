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
    //alert(response['auth']);
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