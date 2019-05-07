var MemberFormat = {
    columns :[
        {"data" : 'rolerId'},
        {"data" : 'level'},
        {"data" : 'league'},
        {"data" : 'season'},
        {"data" : 'nowRank'},
        {"data" : 'lastRank'},
        {"data" : 'score'},
        {"data" : 'totalCount'},
        {"data" : 'winCount'},
        {"data" : 'loseCount'},
        {"data" : 'winRatio'},
        {"data" : 'time'},
        {"data" : 'qq'},
        {"data" : 'mobile'},
        {"data" : 'screenshot'},
        {"data" : 'status'},
        {"data" : 'forbidEndTime'},
        {"data" : 'id'}
    ],
    rankInfo : {
        "0" : "无",
        "1":"倔强青铜III",
        "2":"倔强青铜II",
        "3":"倔强青铜I",
        "4":"秩序白银III",
        "5":"秩序白银II",
        "6":"秩序白银I",
        "7":"荣耀黄金IV",
        "8":"荣耀黄金III",
        "9":"荣耀黄金II",
        "10":"荣耀黄金I",
        "11":"尊贵铂金V",
        "12":"尊贵铂金IV",
        "13":"尊贵铂金III",
        "14":"尊贵铂金II",
        "15":"尊贵铂金I",
        "16":"永恒钻石V",
        "17":"永恒钻石IV",
        "18":"永恒钻石III",
        "19":"永恒钻石II",
        "20":"永恒钻石I",
        "21":"至尊星耀V",
        "22":"至尊星耀IV",
        "23":"至尊星耀III",
        "24":"至尊星耀II",
        "25":"至尊星耀I",
        "26":"荣耀王者"
    },
    datacolumn : null,
    data : null,
    actionsUrl : [],
    toastrOpts : {},
    datatable : null,
    ajaxUrl : '',
    ajaxData : {"leagueId" : 0},
    imageViewOptions : {
        navbar : false,
        tooltip : false,
        scalable : false,
        fullscreen : false,
        zIndex : 99999
    },
    searchValue : '',

    getFormatOptions : function(){
        var $that = this;
        return {
            ajaxUrl : $that.ajaxUrl,
            ajaxData : $that.ajaxData,
            columns : $that.columns,
            infolabel : "",
            emptyTable : "暂无成员信息！",
            lengthMenu : "每页  ：_MENU_",
            pageMenu : [[15, 30, 50], [15, 30, 50]]
        };
    },

    resetSearchInput : function(){
        var $that = this;
        $('.dataTables_filter').empty();
        $('.dataTables_filter').addClass('col-md-3').html($('#member-search').html());
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
            var searchType = $('.dataTables_filter button.dropdown-toggle').attr('data-searchtype');
            var searchText = $('.dataTables_filter #content').val();
            $that.datatable.on('preXhr.dt' , function(e , settings , data){
                data.search = {
                    "type" : searchType,
                    "value" : searchText
                };
                data.status = $('li.active a').attr('data-status');
            });
            $that.datatable.ajax.reload(function(){
                $('.dataTables_filter input#content').val(searchText);
                $that.initViewimage();
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

    initDataTable : function () {
        this.resetSearchInput();
        this.initTabs();
        this.seasonList();
        this.refreshTab();
        if(this.searchValue != null){
            $('.dataTables_filter #content').val(this.searchValue);
        }
    },

    complete : function(){
        this.initViewimage();
    },

    initTabs : function () {
        var $rThat = this;
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
                $rThat.initViewimage();
            });
        });
    },

    initViewimage : function(){
        var table = $('#member-data');
        if (table.find('img').length > 0){
            var viewer = new Viewer(table[0] , this.imageViewOptions);
        }
    },

    seasonList : function(){
        var status = $('.nav-tabs li.active > a').attr('data-status');
        if (typeof this.ajaxData.leagueId != 'undefined' &&  this.ajaxData.leagueId > 0){
            var $that = this;
            $('.dataTables_wrapper .season-filter').remove();
            if (status != 2 ){
                $('.dataTables_length').before($('#season-filter').html());
                $('.season-filter a').off('click');
                $('.season-filter a').on('click'  , function () {
                    $('.season-filter button.dropdown-toggle').attr('data-seasonid' , $(this).attr('data-seasonid'));
                    $('.season-filter button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
                    var seasonA = $(this);
                    var searchType = $('.dataTables_filter button.dropdown-toggle').attr('data-searchtype');
                    var searchText = $('.dataTables_filter #content').val();
                    $that.datatable.on('preXhr.dt' , function(e , settings , data){
                        data.search = {
                            "type" : searchType,
                            "value" : searchText
                        };
                        data.seasonId = seasonA.attr('data-seasonid');
                    });
                    $that.datatable.ajax.reload(function(){
                        $('.dataTables_filter input#content').val(searchText);
                        $that.initViewimage();
                    });
                });
            }
        }
    },
    execute : function(row , data){
        this.processColumns(row);
        this.data = data;

        if(typeof this.datacolumn.lastRank != 'undefined'){
            this.lastRank();
        }
        this.level();
        this.screenshot();
        this.status();
        this.forbidEndTime();
        this.actions();
    },

    level : function () {
        this.render(this.datacolumn.level , this.rankInfo[this.data.level]);
    },

    lastRank : function(){
        var iconClass = 'fa-minus color-gray';
        var text = '';
        if (this.data.lastRank > 0){
            var rankDiff = this.data.nowRank - this.data.lastRank;
            if (rankDiff < 0){
                iconClass = 'fa-long-arrow-up color-red';
            }
            if (rankDiff > 0){
                iconClass = 'fa-long-arrow-down color-green';
            }
            if (rankDiff != 0){
                text = ' ' + Math.abs(rankDiff);
            }
        }
        var rankChange = $('<i>').addClass('fa ' + iconClass).text(text);
        this.render(this.datacolumn.lastRank , rankChange);
    },
    screenshot : function () {
        if (this.data.screenshot != null){
            var img = $('<img>').attr({
                'src'  : this.data.screenshot,
                'width' : 50,
                'height' : 50,
                'alt' : this.data.rolerId
            });
            this.render(this.datacolumn.screenshot , img);
        }
    },
    status : function(){
        var el = $('<i>');
        var data = parseInt(this.data.status);
        switch (data){
            case 2 :
                el.addClass('fa fa-minus-circle color-orange font-18').attr('title' , '等待审核');
                break;
            case 3 :
                el.addClass('fa fa-check-circle color-green font-18').attr('title' , '正式成员');
                break;
            case 4 :
                el.addClass('fa fa-times-circle color-red font-18').attr('title' , '拒绝加入');
                break;
            case 5 :
                el.addClass('fa fa-ban color-black font-18').attr('title' , '禁赛中');
                break;
        }
        this.render(this.datacolumn.status , el , 'text-center');
    },

    forbidEndTime : function(){
        var el = $('<i>');
        var data = this.data.forbidEndTime;
        if(data == null){
            el.addClass('fa fa-check-circle color-green font-18').attr('title' , '正常');
        }else{
            el = $('<span title="禁言中">').html(data);
        }
        this.render(this.datacolumn.forbidEndTime , el , 'text-center');
    },

    actions : function(){
        var $that = this;
        var actions = ["同意" , "拒绝" , "编辑" , "禁赛" , "解禁" , "勋章","禁言"];
        var url = "#";
        var actionsColumn = $(this.datacolumn.actions);
        actionsColumn.empty();
        actionsColumn.addClass('actions');
        for(var i in actions){
            var el = '';
            var nickname = $that.data.rolerId;
            var league = $that.data.league;
            var rejectId = $that.data.id;
            var userId = $that.data.userId;
            var forbidEndTime = $that.data.forbidEndTime;
            if(typeof this.actionsUrl[i] != 'undefined'){
                var id = this.data.id;
                switch (i){
                    case '2' :
                        id = this.data.leagueKeyId;
                        break;
                    case '5' :
                        id = this.data.userId;
                        break;
                }
                url = this.actionsUrl[i] + '?id='  + id;
            }
            switch(i){
                case '0' :
                    if($that.data.status == 2 || $that.data.status == 4){
                        icon = $('<i>').addClass('fa fa-check');
                        el = $('<a>').addClass('btn btn-success btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
                        el.on('click' , function(){
                            console.log($that.data);
                            confirmText = $('<span>').addClass('color-orange font-16').html("确定同意“" + nickname + "”加入" + league + "吗？");
                            successText = nickname + " 认证成功！";
                            $that.showConfirmModal(this , confirmText , successText);
                            return false;
                        });
                    }
                    break;
                case '1' :
                    if($that.data.status < 3){
                        icon = $('<i>').addClass('fa fa-times');
                        el = $('<a>').addClass('btn btn-danger btn-sm btn-icon icon-left').attr('href',url).html(icon).append(actions[i]);
                        el.on('click' , function(){
                            $that.rejectUser(this,rejectId,nickname);
                            return false;
                        });
                    }
                    break;
                case '2' :
                    if($that.data.status == 3 || $that.data.status == 5){
                        icon = $('<i>').addClass('fa fa-pencil');
                        el = $('<a>').addClass('btn btn-default btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
                    }
                    break;
                case '3' :
                    if($that.data.status == 3){
                        icon = $('<i>').addClass('fa fa-ban');
                        el = $('<a>').addClass('btn btn-primary btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
                        el.on('click' , function(){
                            confirmText = $('<span>').addClass('color-orange font-16').html("确定要将" + league + "中的 “" + nickname + "” 禁赛吗？");
                            successText = "禁止  " + nickname + "  比赛成功！";
                            $that.showConfirmModal(this , confirmText , successText);
                            return false;
                        });
                    }
                    break;
                case '4' :
                    if($that.data.status == 5){
                        icon = $('<i>').addClass('fa fa-check');
                        el = $('<a>').addClass('btn btn-default btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
                        el.on('click' , function(){
                            confirmText = $('<span>').addClass('color-orange font-16').html("确定要解除" + league + "中的 “" + nickname + "” 的禁赛处罚吗？");
                            successText = "解除  " + nickname + "  的禁赛处罚成功！";
                            $that.showConfirmModal(this , confirmText , successText);
                            return false;
                        });
                    }
                    break;
                case '5' :
                    if($that.data.status == 3 || $that.data.status == 5){
                        url = url + '&name=' + $that.data.rolerId;
                        icon = $('<i>').addClass('fa fa-life-ring');
                        el = $('<a>').addClass('btn btn-gold btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
                    }
                    break;
                case '6' :
                    if($that.data.status > 2){
                        icon = $('<i>').addClass('fa fa-volume-off');
                        el = $('<a>').addClass('btn btn-danger btn-sm btn-icon icon-left').attr('href',url).html(icon).append(actions[i]);
                        el.on('click',function(){
                            $that.forbidUserEndTime(this,userId,forbidEndTime)
                            return false;
                        })
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
                            $that.initViewimage();
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

    rejectUser: function(obj,rejectId,nickname){
        var $that = this;
        $("#rejectLabel").html("拒绝"+nickname+"的认证申请");
        $("#reject").modal('show');
        var successText = '成功拒绝('+nickname+')的认证申请';
        $("#reject .confirm").off('click');
        $("#reject .confirm").on('click',function(){
            var remark = $("#remark").val();
            $.ajax({
                url : jQuery(obj).attr('href'),
                type : 'get',
                dataType : 'json',
                data:"id="+rejectId+ "&remark="+remark,
                success : function(response){
                    if(response.status == 'success'){
                        $that.datatable.ajax.reload(function(){
                            $that.initViewimage();
                            $('.loading-shade').fadeOut();
                            toastr.success(successText , '' , $that.toastrOpts);
                        } , false);
                    }else{
                        toastr.error(response.message , '' , $that.toastrOpts);
                    }
                }
            });
            $('#reject').modal('hide');
        })
        return false;
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
                $rThat.initViewimage();
                $(".dataTables_filter #content").val('');
            });
        });
    },
    forbidUserEndTime: function(obj,userId,forbidEndTime){
        var $that = this;
        if(forbidEndTime == null){
            $('#forbid-end-time input[value=1]').trigger('click');
        }else{
            $('#forbid-end-time input[value=0]').trigger('click');
        }
        $("#time").val(0)
        $("#forbid-end-time").modal('show');
        $("#forbid-end-time .confirm").off('click');
        $("#forbid-end-time .confirm").on('click' , function(){
            var controlType = $("input[name='controlType']:checked").val();
            var time = $("#time").val();
            $.ajax({
                url : jQuery(obj).attr('href'),
                type : 'get',
                dataType : 'json',
                data:"controlType="+controlType+ "&userId="+userId+"&time="+time,
                success : function(response){
                    if(response.status == 'success'){
                        $that.datatable.ajax.reload(function(){
                            $that.initViewimage();
                            $('.loading-shade').fadeOut();
                            toastr.success(response.message , '' , $that.toastrOpts);
                        } , false);
                    }else{
                        toastr.error(response.message , '' , $that.toastrOpts);
                    }
                }
            });
            $('#forbid-end-time').modal('hide');
        })
        return false;
    },
};