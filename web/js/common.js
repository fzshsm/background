function showConfirmModal(obj , confirmText , successText , callback){
    var $that = this;
    jQuery('#confirm-modal .modal-body').html(confirmText);
    jQuery('#confirm-modal .confirm').off('click');
    jQuery('#confirm-modal .confirm').on('click' , function(){
        $.ajax({
            url : jQuery(obj).attr('href'),
            type : 'get',
            dataType : 'json',
            success : function(response){
                if(response.status == 'success'){
                    toastr.success(successText , '' , $that.toastrOpts);
                    if (typeof callback != 'undefined'){
                        callback();
                    }else{
                        window.location.reload();
                    }
                }else{
                    toastr.error(response.message , '' , $that.toastrOpts);
                }
            }
        });
        jQuery('#confirm-modal').modal('hide');
    });
    jQuery('#confirm-modal').modal('show');
}

function checkUploadImage(img) {
    var $that = this;
    var imgFileSize = (img.files[0].size / 1024).toFixed(0);
    if(imgFileSize > 500){
        img.value = "";
        toastr.error('图上大小不能超过500kb' , '' , $that.toastrOpts);
    }
}