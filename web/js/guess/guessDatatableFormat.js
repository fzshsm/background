var GuessDatatableFormat = {
		columns :[
			{"data" : 'guess_name'},
            {"data" : 'profit_amount'},
			{"data" : 'home_team'},
			{"data" : 'home_bets'},
			{"data" : 'home_amount'},
			{"data" : 'guest_team'},
			{"data" : 'guest_bets'},
			{"data" : 'guest_amount'},
			{"data" : 'avg_odds'},
			{"data" : 'result'},
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
            infolabel : '盘口总数：_TOTAL_',
    		emptyTable : "暂无数据！",
            lengthMenu : "每页  ：_MENU_",
    		pageMenu : [[15, 30, 50], [15, 30, 50]],
            search : true,
           	ordering : true,
            order : [[1,'desc']],
        	aoColumnDefs : [{ "bSortable": false, "aTargets": [ 0 ,2,3,4,5,6,7,8,9] }],

    	};
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
    showDatatableTitle : function(settings){
        var $that = this;
        var str = '';
        if (typeof settings != 'undefined'){
			var json=jQuery.parseJSON(settings.jqXHR.responseText);
            str = json.rankTime +'竞猜概览：'+ '结算盘口数：' + json.recordsTotal + '，总投注数：' + json.bets +
                '注，投注总额：' + json.guessAmount + '元，总赔付：'+ json.deficitAmount + '元，总盈利：'+ json.profitAmount+'元';
            $("#rankTime").html(str)
        }
        $('.dataTables_filter').empty();
        $('.dataTables_filter').addClass('col-md-10').html($('#guess-time').html());
    },

	execute : function(row , data){
		this.processColumns(row);
		this.data = data;
		this.result();
	},


	result : function(){
		var data = parseInt(this.data.result);
		var title = '主队胜';
		if(data == 2){
            title = '客队胜';
		}
		this.render(this.datacolumn.result , title , 'text-center');
	},


	render : function(dataColumn , el , className){
		$(dataColumn).html(el).addClass(className);
	},
};
