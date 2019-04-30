$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
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