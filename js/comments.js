function init_comments(id){
    comments=id;
    io({"action":"get_comments","id":id},'gen_comments');
}

function gen_comments(response){
    response=JSON.parse(response);
    source='';
    if(response['comments']){
        comm=response['comments'];
        for(i in comm){
            source+='<div class="comment">' +
            '<div class="photo"><img src="templates/default/images/avatar.png"></div>' +
            '<div class="comment_info">' +
            '<div class="name">'+comm[i]['usercom_name']+'</div><div class="date">в '+comm[i]['datacom']+'</div>' +
            '<div class="clear"></div><div class="text">'+comm[i]['comment']+'</div></div></div>';
        }
    }
    document.getElementById('comments').innerHTML=source;
    document.getElementById('comments').scrollTop=9999;
    if(new_comm==true&&response['add_comment']!=true){
        alert('Ошибка создания комментария');
        new_comm=false;
    }else if(new_comm==true&&response['add_comment']==true){
        new_comm=false;
    };
}

function add_comment(){
    text=document.getElementById('new_comm').value;
    document.getElementById('new_comm').value='';
    new_comm=true;
    io({"action":"add_comment","id":comments,"text":text},'gen_comments');
}

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
}