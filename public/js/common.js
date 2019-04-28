$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('.like-button').on('click', function (event){
    var target = $(event.target);
    var current_like = target.attr('like-value');
    var user_id = target.attr('like-user');
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
                target.text('關注');
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
                target.text('取消關注');
            }
        });
    }
});