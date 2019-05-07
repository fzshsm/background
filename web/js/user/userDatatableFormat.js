var UserDatatableFormat = {
		columns :[
			{"data" : 'userNo'},
			{"data" : 'nickName'},
			// {"data" : 'roleId'},
			{"data" : 'gender'},
			{"data" : 'qq'},
			{"data" : 'gameLeagueId'},
			{"data" : 'isRealInfo'},
			{"data" : 'identity'},
			{"data" : 'clubName'},
			{"data" : 'status'},
			{"data" : 'id'},
		],
		datacolumn : null,
	    data : null,
	    actionsUrl : [],
	    toastrOpts : {},
	    datatable : null,
	    ajaxUrl : '',


    getFormatOptions : function(){
    	var $that = this;
    	return {
    		ajaxUrl : $that.ajaxUrl,
    		columns : $that.columns,
    		infolabel : '用户总数：_TOTAL_',
    		emptyTable : "暂无用户数据！",
            lengthMenu : "每页  ：_MENU_",
    		pageMenu : [[15, 30, 50], [15, 30, 50]],
            search : true,
            ordering:false,
            order:[],
            aoColumnDefs:[],
    	};
    }, 
    setActionsUrl :function(actionsUrl){
    	if(typeof actionsUrl  == 'object'){
    		this.actionsUrl = actionsUrl;
    	}
    },
    
    resetSearchInput : function(){
    	var $that = this;
    	$('.dataTables_filter').empty();
    	$('.dataTables_filter').addClass('col-md-3').html($('#user-search').html());
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
    		})
    		$that.datatable.ajax.reload(function(){
    			$('.dataTables_filter input#content').val(searchText);
    		});
    	});
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
		this.refreshTab();
    },
	execute : function(row , data){
		this.processColumns(row);
		this.data = data;
		this.ID();
//		this.nickname();
		this.gender();
//		this.winRatio();
//		this.medalNum();
		this.realInfo();
		//this.identity();
		this.status();
		this.actions();
	},
	ID : function(){
		this.render(this.datacolumn.userNo , this.data.userNo , 'text-center');
	},
//	nickname : function(){
//		this.render(this.datacolumn.nickName , this.data.nickName);
//	},
	gender : function(){
		var data = parseInt(this.data.gender);
		var el = $('<i>');
		if(data == 0){
			el.addClass("fa fa-mars-stroke color-blue font-18").attr('title' , '男');
		}else{
			el.addClass("fa fa-venus color-nacarat font-18").attr('title' , '女');
		}
		this.render(this.datacolumn.gender , el , 'text-center');
	},
	qq : function(){
		this.render(this.datacolumn.qq , this.data.qq);
	},
	league : function(){
		this.render(this.datacolumn.gameName , this.data.gameName);
	},
/*	winRatio : function(){
		var columnNum = 6;
		var data = this.data[columnNum] * 100;
		var text = data.toFixed(2) + " %";
		console.log(text);
		this.render(columnNum , text);
	},
	medalNum : function(){
		var columnNum = 7;
		var el = $('<span>').addClass("badge badge-info").text(this.data[columnNum]);
		this.render(columnNum , el , 'text-center');
	},*/
	realInfo : function(){
		var data = parseInt(this.data.isRealInfo);
		var el = $('<span>');
		if(data == 0){
			el.addClass('td label label-default disabled').text('暂无');
		}else{
			el.addClass('td label label-success').text('实名');
		}
		this.render(this.datacolumn.isRealInfo , el , 'text-center');
	},
	identity : function(){
		var el = $('<span>');
		switch(this.data.identity){
			case 'famous' :
				el.addClass('td label label-secondary').text('职业玩家');
				break;
			case 'player' :
				el.addClass('td label bg-gold').text('知名人士');
				break;
		}
		this.render(this.datacolumn.identity , el , 'text-center');
	},
	status : function(){
		var el = $('<i>');
		var data = parseInt(this.data.status);
		if(data == 1){
			el.addClass('fa fa-lock color-red font-18').attr('title' , '已锁定');
		}else{
			el.addClass('fa fa-check-circle color-green font-18').attr('title' , '正常');
		}
		this.render(this.datacolumn.status , el , 'text-center');
	},

	actions : function(){
		var $that = this;
		var statusValue = parseInt(this.data.status);
		var actions = ["编辑" , "认证" , "封禁" , "解封" , "解绑王者角色","绑定角色",'','背包','赠送豆豆','豆豆详情']
		var url = "#";
		var roleUrl = '';
		if(typeof this.actionsUrl[6] != 'undefined'){
			roleUrl = this.actionsUrl[6] + '?id=' + this.data.id;
		}
        if(typeof this.actionsUrl[8] != 'undefined'){
            roleUrl = this.actionsUrl[8] + '?id=' + this.data.id;
        }
        if(typeof this.actionsUrl[9] != 'undefined'){
            roleUrl = this.actionsUrl[9] + '?id=' + this.data.userNo;
        }
		var actionsColumn = $(this.datacolumn.actions);
		actionsColumn.empty();
		for(var i in actions){
			var el = '';
			var nickname = $that.data.nickName;
			if(typeof this.actionsUrl[i] != 'undefined'){
				url = this.actionsUrl[i] + '?id=' + this.data.id;
			}
            switch(i){
				case '0' :
					icon = $('<i>').addClass('fa fa-pencil');
					el = $('<a>').addClass('btn btn-default btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
					break;
				case '1' :
					icon = $('<i>').addClass('fa fa-check');
					el = $('<a>').addClass('btn btn-orange btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
					break;
				case '2' :
					if(statusValue == 0){
						icon = $('<i>').addClass('fa fa-lock');
						el = $('<a>').addClass('btn btn-danger btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
						el.on('click' , function(){
							confirmText = $('<span>').addClass('color-red font-16').html("确定要封停  ‘" + nickname + "’ 这个用户吗？");
							successText = "对 " + nickname + " 这个用户已成功封停！";
							$that.showConfirmModal(this , confirmText , successText);
							return false;
						});
					}
					break;
				case '3' :
					if(statusValue == 1){
						icon = $('<i>').addClass('fa fa-unlock');
						el = $('<a>').addClass('btn btn-success btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
						el.on('click' , function(){
							confirmText = $('<span>').addClass('color-red font-16').html("确定要解锁  ‘" + nickname + "’ 这个用户吗？");
							successText = "成功解禁  " + nickname + " ！";
							$that.showConfirmModal(this , confirmText , successText);
							return false;
						});
					}
					break;
                case '4' :
					icon = $('<i>').addClass('fa fa-unlock');
					el = $('<a>').addClass('btn btn-blue btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
					el.on('click' , function(){
						confirmText = $('<span>').addClass('color-red font-16').html("确定要解绑  ‘" + nickname + "’ 的王者荣耀角色吗？");
						successText = "成功解绑  " + nickname + " 的王者荣耀角色！";
						$that.showConfirmModal(this , confirmText , successText);
						return false;
					});
                    break;
                case '5' :
                    icon = $('<i>').addClass('fa fa-steam');
                    el = $('<a>').addClass('btn btn-primary btn-sm btn-icon icon-left').attr({'href':url,'data-role':roleUrl}).html(icon).append(actions[i]);
                    el.on('click',function(){
                        $that.bindSteamRole(this)
                        return false;
                    })
					break;
                case '7' :
                    icon = $('<i>').addClass('fa fa-suitcase');
                    el = $('<a>').addClass('btn btn-info btn-sm btn-icon icon-left').attr('href' , url).html(icon).append(actions[i]);
					break;
                case '8' :
                    icon = $('<i>').addClass('fa fa-cny');
                    el = $('<a>').addClass('btn btn-success btn-sm btn-icon icon-left').attr({'href':url,'data-role':roleUrl,'nickname':nickname}).html(icon).append(actions[i]);
                    el.on('click',function(){
                        $that.sendCurrency(this)
                        return false;
                    })
                    break;
                case '9' :
                    icon = $('<i>').addClass('fa fa-cny');
                    el = $('<a>').addClass('btn btn-gold btn-sm btn-icon icon-left').attr('href' , roleUrl).html(icon).append(actions[i]);
                    break;
			}
			if(el != ''){
				$(actionsColumn).append(el);
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
    bindSteamRole: function(obj){
        var $that = this;

		$.ajax({
			url:jQuery(obj).attr('data-role'),
			type:'get',
			dataType:'json',
			success : function (response) {
				if(response.status == 'success'){
                    $("#steamRole").val(response.result)
				}
            }
		})

        $("#bind-steam-role").modal('show');
        $("#bind-steam-role .confirm").off('click');
        $("#bind-steam-role .confirm").on('click' , function(){
            var steamRole = $("#steamRole").val();
            $.ajax({
                url : jQuery(obj).attr('href'),
                type : 'get',
                dataType : 'json',
                data:"nickName="+steamRole,
                success : function(response){
                    if(response.status == 'success'){
                        $that.datatable.ajax.reload(function(){
                            $('.loading-shade').fadeOut();
                            toastr.success(response.message , '' , $that.toastrOpts);
                        } , false);
                    }else{
                        toastr.error(response.message , '' , $that.toastrOpts);
                    }
                }
            });
            $('#bind-steam-role').modal('hide');
        })
        return false;
    },
    sendCurrency: function(obj){
        var $that = this;
		var nickname = $(obj).attr('nickname');
		$("#sendTitle").html('赠送<span class=\'color-orange\'>('+nickname+')</span>豆豆');
        $("#coinB").val('');
        $("#remark").val('')
        $("#send-currency").modal('show');
        $("#send-currency .confirm").off('click');
        $("#send-currency .confirm").on('click' , function(){
            var coinB = $("#coinB").val();
			var remark = $("#remark").val();
			if(remark == ''){
                toastr.error('赠送理由不能为空' , '' , $that.toastrOpts);
                return false;
			}
            if(coinB == ''){
                toastr.error('赠送豆豆数量不能为空' , '' , $that.toastrOpts);
                return false;
            }

            $.ajax({
                url : jQuery(obj).attr('href'),
                type : 'get',
                dataType : 'json',
                data:"coinB="+coinB+'&remark='+remark,
                success : function(response){
                    if(response.status == 'success'){
                        $that.datatable.ajax.reload(function(){
                            $('.loading-shade').fadeOut();
                            toastr.success(response.message , '' , $that.toastrOpts);
                        } , false);
                    }else{
                        toastr.error(response.message , '' , $that.toastrOpts);
                    }
                }
            });
            $('#send-currency').modal('hide');
        })
        return false;
    },
};
