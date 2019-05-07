var leagueDatatableFormat = {
    columns :[],
    datacolumn : null,
    data : null,
    actionsUrl : [],
    actionsTitle : [],
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
            emptyTable : "暂无联赛信息！",
            lengthMenu : "",
            pageMenu : ""
        };
    },

    resetSearchInput : function(){
        var $that = this;
        $('.dataTables_filter').empty();
        $('.dataTables_filter').addClass('col-md-5').html($('#league-search').html());
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
    initDataTable : function(){
        this.resetSearchInput();
        this.sortList();
        this.refreshTab();
        this.initTabs();
    },
    execute : function(row , data){
        this.processColumns(row);
        this.data = data;
        this.ID();
        this.name();
        this.cover();
        this.shareIcon();
        this.shareCover();
        this.reward();
        this.level();
        this.signinCount();
        this.status();
        this.describe();
        this.leagueDescribe();
        this.actions();
        if ($(row).find('img').length > 0){
            var viewer = new Viewer(row , this.imageViewOptions);
        }
    },

    // initViewimage : function(){
    //     var table = $('#league-data');
    //     if (table.find('img').length > 0){
    //         var viewer = new Viewer(table[0] , this.imageViewOptions);
    //     }
    // },
    complete : function(){
        // this.initViewimage();
    },

    toThousands : function(num){
        return (num || 0).toString().replace(/(\d)(?=(?:\d{3})+$)/g, '$1,');
    },

    ID : function(){
        this.render(this.datacolumn.id , this.data.id);
    },

    name : function(){
        this.render(this.datacolumn.name , this.data.name);
    },

    cover : function(){
        if (this.data.cover != ''){
            var img = $('<img>').attr({
                'src'  : this.data.cover,
                'width' : 100,
                'height' : 100
            });
            this.render(this.datacolumn.cover , img);
        }
    },
    shareIcon : function(){
        if(this.data.shareIcon != ''){
            var img = $('<img>').attr({
                'src'  : this.data.shareIcon ,
                'width' : 100,
                'height' : 100
            });
            this.render(this.datacolumn.shareIcon , img);
        }
    },
    shareCover : function(){
        if(this.data.shareCover != ''){
            var img = $('<img>').attr({
                'src'  : this.data.shareCover ,
                'width' : 100,
                'height' : 100
            });
            this.render(this.datacolumn.shareCover , img);
        }
    },

    reward : function(){
        var reward = this.toThousands(this.data.reward);
        this.render(this.datacolumn.reward , reward , 'color-red');
    },
    level : function(){
        var level = $('<span>').addClass("badge badge-orange").text(this.data.level);
        this.render(this.datacolumn.level , level);
    },

    signinCount : function(){
        var signinCount = $('<span>').addClass('badge badge-info').text(this.data.signinCount);
        this.render(this.datacolumn.signinCount , signinCount);
    },

    describe : function(){
        var intro = $('<div>').addClass('text-intro').text(this.data.describe);
        intro.mouseover(function () {
            $(this).removeClass('text-intro');
            $(this).addClass('text-intro-show');
        });
        intro.mouseout(function () {
            $(this).removeClass('text-intro-show');
            $(this).addClass('text-intro');
        });
        this.render(this.datacolumn.describe , intro);
    },

    leagueDescribe : function(){
        var intro = $('<div>').addClass('text-intro').text(this.data.leagueDescribe);
        intro.mouseover(function () {
            $(this).removeClass('text-intro');
            $(this).addClass('text-intro-show');
        });
        intro.mouseout(function () {
            $(this).removeClass('text-intro-show');
            $(this).addClass('text-intro');
        });
        this.render(this.datacolumn.leagueDescribe , intro);
    },

    status : function(){
        var className = "";
        var text = "";
        switch (this.data.status){
            case 1 :
                className = ' label-default';
                text = '未开始';
                break;
            case 2 :
                className = ' label-success';
                text = '进行中';
                break;
            case 3 :
                className = ' label-primary';
                text = '已关闭';
                break;
        }
        var status = $('<span>').addClass("label " + className).html(text);
        this.render(this.datacolumn.status , status);
    },

    actions : function(){
        var $that = this;
        var statusValue = parseInt(this.data.status);
        var actions = this.actionsTitle
        var url = "#";
        var actionsColumn = $(this.datacolumn.actions);
        actionsColumn.empty();
        actionsColumn.addClass('actions');
        for(var i in actions){
            var el = '';
            if(typeof this.actionsUrl[i] != 'undefined'){
                url = this.actionsUrl[i] + ( i == '0' ? '?id=' : '?leagueId=' ) + this.data.id;
            }
            switch(i){
                case '0' :
                    icon = $('<i>').addClass('fa fa-pencil');
                    el = $('<a>').addClass('btn btn-default btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
                    break;
                case '1' :
                    if (this.data.status == 2){
                        icon = $('<i>').addClass('fa fa-bars');
                        el = $('<a>').addClass('btn btn-orange btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
                    }
                    break;
                case '2' :
                    if (this.data.status == 2) {
                        icon = $('<i>').addClass('fa fa-users');
                        el = $('<a>').addClass('btn btn-info btn-sm btn-icon icon-left').attr('href', url).html(icon).append(actions[i]);
                    }
                    break;
                case '3' :
                    if (this.data.status == 2) {
                        if(actions[i] == '预约'){
                            icon = $('<i>').addClass('fa fa-hand-paper-o');
                        }else{
                            icon = $('<i>').addClass('fa fa-gamepad');
                        }

                        el = $('<a>').addClass('btn btn-success btn-sm btn-icon icon-left').attr('href', url).html(icon).append(actions[i]);
                    }
                    break;
                case '4' :
                    icon = $('<i>').addClass('fa fa-sticky-note-o');
                    el = $('<a>').addClass('btn btn-primary btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
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
    sortList : function(){
        var $that = this;
        $('.dataTables_length').before($('.league-season-filter').html());
        $('.search-type a').off('click');
        $('.search-type a').on('click'  , function () {
            $('.search-type button.dropdown-toggle').attr('data-searchtype' , $(this).attr('data-searchtype'));
            $('.search-type button.dropdown-toggle').html($(this).text() +　'　<span class="caret"></span>');
            var searchType = $('.dataTables_filter button.dropdown-toggle').attr('data-searchtype');
            var searchText = $('.dataTables_filter #content').val();
            $that.datatable.on('preXhr.dt' , function(e , settings , data){
                data.search = {
                    "type" : searchType,
                    "value" : searchText
                };
            });
            $that.datatable.ajax.reload(function(){
                $('.dataTables_filter input#content').val(searchText);
                // $that.initViewimage();
            });
        });
    },
    refreshTab : function () {
        var $rThat = this;
        var datatable = this.datatable;

        $('.refresh a').off('click');
        $('.refresh a').on('click' , function(){
            $('button.dropdown-toggle').attr('data-searchtype' ,0);
            $('button.dropdown-toggle').html('全部' +　'　<span class="caret"></span>');
            datatable.on('preXhr.dt' , function(e , settings , data){
                data.search = {
                    "type" : '',
                    "value" : ''
                };
            });
            datatable.ajax.reload(function(){
                $(".dataTables_filter #content").val('');
            });
        });
    },
    initTabs : function () {
        var $rThat = this;
        var datatable = this.datatable;
        $('.nav-tabs a').off('click');
        $('.nav-tabs a').on('click' , function(){
            var $that = $(this);
            var dataStatus = $that.attr('data-status');
            $('.nav-tabs li').removeClass('active');
            $that.closest('li').addClass('active');
            datatable.destroy();
            $("#glory-league-data").empty();
            $("#pubg-league-data").empty();
            $('button.dropdown-toggle').attr('data-searchtype' ,0);
            $('button.dropdown-toggle').html('全部' +　'　<span class="caret"></span>');
            if(dataStatus == '2'){
                $("#pubg-league-data").append('<thead><tr><td width="2%">ID</td>\n' +
                    '                <td width="5%">名称</td>\n' +
                    '                <td width="8%">封面</td>\n' +
                    '                <td width="8%">分享logo</td>\n' +
                    '                <td width="8%">分享联赛图</td>\n' +
                    '                <td width="5%">联赛分类</td>\n' +
                    '                <td width="4%">奖金</td>\n' +
                    '                <td width="5%">成员人数</td>\n' +
                    '                <td width="8%">举办单位</td>\n' +
                    '                <td width="20%">简介</td>\n' +
                    '                <td width="6%">创建时间</td>\n' +
                    '                <td width="4%">状态</td>\n' +
                    '                <td width="20%">操作</td></tr></thead>');
                getPubgData();
            }else{
                $("#glory-league-data").append('<thead><tr><td width="2%">ID</td>\n' +
                    '                <td width="5%">名称</td>\n' +
                    '                <td width="8%">封面</td>\n' +
                    '                <td width="8%">分享logo</td>\n' +
                    '                <td width="8%">分享联赛图</td>\n' +
                    '                <td width="5%">联赛分类</td>\n' +
                    '                <td width="5%">联赛模式</td>\n' +
                    '                <td width="4%">奖金</td>\n' +
                    '                <td width="4%">等级</td>\n' +
                    '                <td width="5%">成员人数</td>\n' +
                    '                <td width="8%">举办单位</td>\n' +
                    '                <td width="20%">简介</td>\n' +
                    '                <td width="6%">创建时间</td>\n' +
                    '                <td width="4%">状态</td>\n' +
                    '                <td width="20%">操作</td></tr></thead>');
               getGloryData();
            }

            // var searchType = $('button.dropdown-toggle').attr('data-searchtype');
            // var searchText = $('.dataTables_filter #content').val();
            // datatable.on('preXhr.dt' , function(e , settings , data){
            //     data.search = {
            //         "type" : searchType,
            //         "value" : searchText
            //     };
            //     data.status = $that.attr('data-status');
            // });
            //
            // datatable.ajax.url(datatable.ajaxUrl).load(function(){
            //     $('.dataTables_filter input#content').val(searchText);
            //     //$rThat.initViewimage();
            // });
        });
    }
};