function init_comments(id,type){
    TM['comments_loaded']=type;
    TM['CID']=id;
    io({"action":"get_comments","id":id,"type":type},gen_comments);
}

function gen_comments(response){
    response=JSON.parse(response);
    source='';
    if(response['comments']){
        comm=response['comments'];
        for(i in comm){
            source+='<div class="comment">' +
            '<img src="'+comm[i]['usercom_photo']+'"><div class="info_text">' +
            '<div class="name">'+comm[i]['usercom_name']+'</div><div class="date">'+comm[i]['datacom']+'</div>' +
            '<p class="text">'+comm[i]['comment']+'</p></div></div>';
        }
    }
    if(source==''){source='<div class="comment"><p class="text">(Комментриев нет)</p></div>'}
    document.getElementById('comments').innerHTML=source;
    document.getElementById('comments').scrollTop=9999;
}

function add_comment(){
    text=document.getElementById('new_comm').value;
    document.getElementById('new_comm').value='';
    io({"action":"add_comment","id":TM['CID'],"type":TM['comments_loaded'],"text":text});
}
/*
document.onkeyup = function (e) {
    e = e || window.event;
    if (e.keyCode === 13) {
        if(document.getElementById('new_comm')) {
            if (document.getElementById('new_comm').value != ''&&new_comm!=true) {
                add_comment();
            }
        }
    }
    return false;
}*/