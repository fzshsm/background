var AuthenticateDataFormat = {
    columns :[
        {"data" : 'nickName'},
        {"data" : 'qq'},
        {"data" : 'clubName'},
        {"data" : 'personName'},
        {"data" : 'cardId'},
        {"data" : 'facadePhotoUrl'},
        {"data" : 'backFacesPhotoUrl'},
        {"data" : 'bodyHalfPhotoUrl'},
        {"data" : 'status'},
        {"data" : 'id'}
    ],
    datacolumn : null,
    data : null,
    actionsUrl : [],
    toastrOpts : {},
    datatable : null,
    ajaxUrl : '',

    imageViewOptions : {
        navbar : false,
        tooltip : false,
        scalable : false,
        fullscreen : false,
        zIndex : 99999
    },

    getFormatOptions : function(){
        var $that = this;
        return {
            ajaxUrl : $that.ajaxUrl,
            columns : $that.columns,
            infolabel : "",
            emptyTable : "暂无认证信息！",
            lengthMenu : "每页  ：_MENU_",
            pageMenu : [[15, 30, 50], [15, 30, 50]],
            ordering:false,
            order:[],
            aoColumnDefs:[],
        };
    },

    resetSearchInput : function(){
        var $that = this;
        $('.dataTables_filter').empty();
        $('.dataTables_filter').addClass('col-md-3').html($('#authenticate-search').html());
        $('.dataTables_filter .dropdown-menu a').click(function(){
            $('button.dropdown-toggle').attr('data-searchtype' , $(this).attr('data-searchtype'));
            $('button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
        });
        $('.dataTables_filter input#content').off('keypress');
        $('.dataTables_filter input#content').on('keypress' , function(e){
            if(e.keyCode == 13){
                $('button.search').trigger('click');
            }
        });
        $("button.search").off('click');
        $("button.search").on('click' , function(){
            var searchType = $('button.dropdown-toggle').attr('data-searchtype');
            var searchText = $('.dataTables_filter #content').val();
            $that.datatable.on('preXhr.dt' , function(e , settings , data){
                data.search = {
                    "type" : searchType,
                    "value" : searchText
                };
            });
            $that.datatable.ajax.reload(function(){
                $('.dataTables_filter input#content').val(searchText);
            });
        });
    },

    setActionsUrl :function(actionsUrl){
        if(typeof actionsUrl  == 'object'){
            this.actionsUrl = actionsUrl;
        }
    },

    processColumns : function(row){
        var tdList = $(row).find("td");
        this.datacolumn  = {};
        for(var i in this.columns){
            column = this.columns[i].data;
            if (i == this.columns.length - 1){
                column = 'actions';
            }
            this.datacolumn[column] = tdList[i];
        }
    },

    initTabs : function () {
        var datatable = this.datatable;
        $('.nav-tabs a').off('click');
        $('.nav-tabs a').on('click' , function(){
            var $that = $(this);
            $('.nav-tabs li').removeClass('active');
            $that.closest('li').addClass('active');
            var searchType = $('button.dropdown-toggle').attr('data-searchtype');
            var searchText = $('.dataTables_filter #content').val();
            datatable.on('preXhr.dt' , function(e , settings , data){
                data.search = {
                    "type" : searchType,
                    "value" : searchText
                };
                data.status = $that.attr('data-status');
            });
            datatable.ajax.reload(function(){
                $('.dataTables_filter input#content').val(searchText);
            });
        });
    },
    initDataTable : function(){
        this.resetSearchInput();
        this.initTabs();
        this.refreshTab();
    },
    complete : function(){

    },
    execute : function(row , data){
        this.processColumns(row);
        this.data = data;
        this.nickname();
        this.facadeImg();
        this.backFacesImg();
        this.bodyHalfImg();
        this.status();
        this.actions();
        if ($(row).find('img').length > 0){
            var viewer = new Viewer(row , this.imageViewOptions);
        }
    },
    nickname : function(){

    },
    facadeImg : function () {
        if (this.data.facadePhotoUrl != null){
            var img = $('<img>').attr({
                'src'  : this.data.facadePhotoUrl,
                'width' : 50,
                'height' : 50,
                'alt' : this.data.nickName + ' 正面照'
            });
            this.render(this.datacolumn.facadePhotoUrl , img);
        }

    },
    backFacesImg : function () {
        if (this.data.backFacesPhotoUrl != null){
            var img = $('<img>').attr({
                'src'  : this.data.backFacesPhotoUrl,
                'width' : 50,
                'height' : 50,
                'alt' : this.data.nickName + ' 反面照'
            });
            this.render(this.datacolumn.backFacesPhotoUrl , img);
        }
    },
    bodyHalfImg : function () {
        if (this.data.bodyHalfPhotoUrl != null){
            var img = $('<img>').attr({
                'src'  : this.data.bodyHalfPhotoUrl,
                'width' : 50,
                'height' : 50,
                'alt' : this.data.nickName + ' 半身照'
            });
            this.render(this.datacolumn.bodyHalfPhotoUrl , img);
        }
    },
    status : function(){
        var el = $('<i>');
        var data = parseInt(this.data.status);
        switch (data){
            case 0 :
                el.addClass('fa fa-times-circle color-red font-18').attr('title' , '已拒绝');
                break;
            case 1 :
                el.addClass('fa fa-minus-circle color-orange font-18').attr('title' , '待审核');
                break;
            case 2 :
                el.addClass('fa fa-check-circle color-green font-18').attr('title' , '已认证');
                break;
        }
        this.render(this.datacolumn.status , el , 'text-center');
    },

    actions : function(){
        var $that = this;
        var actions = ["同意" , "拒绝"];
        var url = "#";
        var actionsColumn = $(this.datacolumn.actions);
        actionsColumn.empty();
        actionsColumn.addClass('actions');
        for(var i in actions){
            var el = '';
            var nickname = $that.data.nickName;
            if(typeof this.actionsUrl[i] != 'undefined'){
                url = this.actionsUrl[i] + ( i == '0' ? '?id=' : '?id=' ) + this.data.id;
            }
            switch(i){
                case '0' :
                    if($that.data.status < 2){
                        icon = $('<i>').addClass('fa fa-check');
                        el = $('<a>').addClass('btn btn-success btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
                        el.on('click' , function(){
                            console.log($that.data);
                            confirmText = $('<span>').addClass('color-red font-16').html("确定同意  ‘" + nickname + "’ 的认证申请吗？");
                            successText = nickname + " 认证成功！";
                            $that.showConfirmModal(this , confirmText , successText);
                            return false;
                        });
                    }
                    break;
                case '1' :
                    if($that.data.status > 0){
                        icon = $('<i>').addClass('fa fa-times');
                        el = $('<a>').addClass('btn btn-danger btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
                        el.on('click' , function(){
                            confirmText = $('<span>').addClass('color-red font-16').html("确定要拒绝  ‘" + nickname + "’ 的认证申请吗？");
                            successText = "拒绝  " + nickname + "  的认证申请成功！";
                            $that.showConfirmModal(this , confirmText , successText);
                            return false;
                        });
                    }
                    break;
            }
            if(el != ''){
                actionsColumn.append(el);
            }
        }
    },
    render : function(dataColumn , el , className){
        $(dataColumn).html(el).addClass(className);
    },
    showConfirmModal : function(obj , confirmText , successText){
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
                        $that.datatable.ajax.reload(function(){
                            $('.loading-shade').fadeOut();
                            toastr.success(successText , '' , $that.toastrOpts);
                        } , false);
                    }else{
                        toastr.error(response.message , '' , $that.toastrOpts);
                    }
                }
            });
            jQuery('#confirm-modal').modal('hide');
        });
        jQuery('#confirm-modal').modal('show');
    },
    refreshTab : function () {
        var $rThat = this;
        var datatable = this.datatable;

        $('.refresh a').off('click');
        $('.refresh a').on('click' , function(){
            var $that = $(this);
            datatable.on('preXhr.dt' , function(e , settings , data){
                data.search = {
                    "type" : '',
                    "value" : ''
                };
                data.status = $that.attr('data-status');
            });
            datatable.ajax.reload(function(){
                $(".dataTables_filter #content").val('');
            });
        });
    },
};