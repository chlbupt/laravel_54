$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
// 添加关注
$('.post-audit').on('click',function(event){
    var target = $(event.target);
    var post_id = target.attr('post-id');
    var status = target.attr('post-action-status');
    $.ajax({
        url: '/admin/posts/'+ post_id + '/status',
        dataType: 'json',
        data: {'status': status},
        method: 'post',
        success:function(data){
            if(data.error != 0){
                alert(data.msg);
                return;
            }
            target.parent().parent().remove();
        }
    });
});
// 删除专题
$('.resource-delete').click(function(event){
    if(confirm('确定执行删除操作吗?') == false){
        return;
    }
    var target = $(event.target);
    var url = target.attr('delete-url');
    event.preventDefault();
    $.ajax({
        url: url,
        method: 'post',
        data: {'_method': 'delete'},
        dataType: 'json',
        success:function(data){
            if(data.error != 0 ){
                alert(data.msg);
                return;
            }
            window.location.reload();
        }
    });
});