$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function modify_fan_num(selector, task_type, type){
    var info_arr = selector.text().split('｜');
    var index = task_type == 'star' ? 0 : 1;
    var fan_num = info_arr[index].match(/[0-9]+/);
    if(type == 'minus' && fan_num <= 0){
        return;
    }
    fan_num = type == 'add' ? parseInt(fan_num) + 1 : fan_num - 1;
    var reg = new RegExp("[0-9]+","g");
    info_arr[index] = info_arr[index].replace(reg, fan_num);
    selector.text(info_arr.join('｜'));
}
$('.like-button').on('click', function (event){
    var target = $(event.target);
    var current_like = target.attr('like-value');
    var belong_id = target.attr('belong-user');
    var user_id = target.attr('like-user');
    var current_user_id = window.location.pathname.split('/')[2];
    var task_type = current_user_id == belong_id ? 'star' : 'fan';
    var selector = $('body > div.container > div.row > div:nth-child(1) > blockquote > footer');
    if(current_like == 1){
        // 取消關注
        $.ajax({
            'url': '/user/' + user_id + '/unfan',
            'dataType' : 'json',
            'method': 'post',
            'success' : function(data){
                if(data.error != 0){
                    alert(data.msg);
                    return;
                }
                target.attr('like-value', 0);
                target.text('关注');
                modify_fan_num(selector, task_type, 'minus');
            }
        });
    }else{
        $.ajax({
            'url': '/user/' + user_id + '/fan',
            'dataType' : 'json',
            'method': 'post',
            'success' : function(data){
                if(data.error != 0){
                    alert(data.msg);
                    return;
                }
                target.attr('like-value', 1);
                target.text('取消关注');
                modify_fan_num(selector, task_type, 'add');
            }
        });
    }
});
$('.preview_input').on('change', function(event){
    var file = event.currentTarget.files[0];
    var reader = new FileReader();
    // var url = window.URL.createObjectURL(file);
    reader.readAsDataURL (file);
    reader.onload = function () {
        $(".preview_img").attr({"src":this.result}).css({'width':'300px','height':'300px'});
    }
});